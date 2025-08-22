<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Shift;
use Carbon\Carbon;

class Register extends Component
{
    public $employeeId, $employee, $attendanceType = 'on_time', $notes = '', $selectedShift, $shifts = [], $getShift;

    protected $rules = [
        'attendanceType' => 'required|in:on_time,late,absent',
        'notes' => 'nullable|string|max:500',
        'selectedShift' => 'nullable|exists:shifts,id'
    ];

    // Inicializa el componente
    public function mount($employeeId)
    {
        $this->employeeId = $employeeId;
        $this->employee = Employee::with(['user', 'shiftAssignments.shift'])->find($employeeId);

    if (!$this->employee) {
        session()->flash('error', 'Empleado no encontrado.');
        return redirect()->route('attendances.index');
    }

    // obtiene el turno actual del empleado
    $currentShift = $this->employee->getCurrentShift();

    // obtiene todos los turnos disponibles
    $this->shifts = Shift::all();

    $this->selectedShift = $currentShift ? $currentShift->id : ($this->shifts->first()?->id);
}

    // Registra la asistencia
    public function registerAttendance()
    {
        $this->validate();

        // Verificar si ya tiene asistencia registrada hoy
        $existingAttendance = Attendance::where('employee_id', $this->employeeId)
            ->whereDate('check_in_time', Carbon::today())
            ->first();

        if ($existingAttendance) {
            session()->flash('error', 'Este empleado ya tiene asistencia registrada para hoy.');
            return;
        }

        try {
            // usa el turno seleccionado o el turno actual del empleado
            $shiftId = $this->selectedShift ?: ($this->employee->currentShift?->id);

            Attendance::create([
                'employee_id' => $this->employeeId,
                'shift_id' => $shiftId,
                'check_in_time' => Carbon::now(),
                'is_manual_entry' => true,
                'device_id' => 'manual_' . auth()->id(),
            ]);

            session()->flash('success', 'Asistencia registrada exitosamente para ' . $this->employee->first_name);
            return redirect()->route('attendances.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al registrar asistencia: ' . $e->getMessage());
        }
    }
}