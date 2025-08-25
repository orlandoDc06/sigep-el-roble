<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Shift;
use Carbon\Carbon;

class Register extends Component
{
    public $employeeId, $employee, $attendanceType = 'on_time', $notes = '', $selectedShift, $shifts = [];

    protected $rules = [
        'attendanceType' => 'required|in:on_time,late,absent',
        'notes' => 'nullable|string|max:500',
        'selectedShift' => 'nullable|exists:shifts,id'
    ];

    public function mount($employeeId)
    {
        $this->employeeId = $employeeId;
        $this->employee = Employee::with(['user'])->find($employeeId);

        if (!$this->employee) {
            session()->flash('error', 'Empleado no encontrado.');
            return redirect()->route('attendances.index');
        }

        // Obtener todos los turnos disponibles
        $this->shifts = Shift::all();
        
        // Obtener el turno actual del empleado
        $currentShift = $this->employee->getCurrentShift();
        
        // Establecer el turno seleccionado
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
            $checkInTime = Carbon::now();
            
            switch ($this->attendanceType) {
                case 'on_time':
                    break;
                case 'late':
                    break;
                case 'absent':
                    session()->flash('warning', 'El estado "Ausente" se maneja de forma especial.');
                    return;
            }

            Attendance::create([
                'employee_id' => $this->employeeId,
                'shift_id' => $this->selectedShift,
                'check_in_time' => $checkInTime,
                'is_manual_entry' => true,
                'device_id' => 'manual_' . auth()->id(),
                'notes' => $this->notes,
            ]);

            session()->flash('success', 'Asistencia registrada exitosamente para ' . $this->employee->first_name);
            return redirect()->route('attendances.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al registrar asistencia: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.attendance.register');
    }
}