<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Shift;
use App\Models\ExtraHour;
use Carbon\Carbon;

class Register extends Component
{
    public $employeeId, $employee, $attendanceType = 'on_time', $notes = '', $selectedShift, $shifts = [];
    public $hasOvertime = false, $regularOvertime = 0, $doubleOvertime = 0, $overtimeType = '', $overtimeNotes = '';
    
    protected $rules = [
        'attendanceType' => 'required|in:on_time,late,absent',
        'notes' => 'nullable|string|max:500',
        'selectedShift' => 'nullable|exists:shifts,id',
        'hasOvertime' => 'boolean',
        'regularOvertime' => 'required_if:hasOvertime,true|integer|min:0|max:12',
        'doubleOvertime' => 'required_if:hasOvertime,true|integer|min:0|max:12',
        'overtimeType' => 'required_if:hasOvertime,true|in:diurnas,nocturnas,mixtas',
        'overtimeNotes' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'regularOvertime.required_if' => 'Las horas extras normales son requeridas cuando se registran horas extras.',
        'doubleOvertime.required_if' => 'Las horas extras dobles son requeridas cuando se registran horas extras.',
        'overtimeType.required_if' => 'El tipo de horas extras es requerido cuando se registran horas extras.',
        'attendanceType.required' => 'El tipo de asistencia es obligatorio.',
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

    // Método para manejar el cambio del checkbox
    public function updatedHasOvertime($value)
    {
        // Si se desmarca el checkbox, resetear los valores de horas extras
        if (!$value) {
            $this->reset(['regularOvertime', 'doubleOvertime', 'overtimeType', 'overtimeNotes']);
        }
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

            // Crear el registro de asistencia
            $attendance = Attendance::create([
                'employee_id' => $this->employeeId,
                'shift_id' => $this->selectedShift,
                'check_in_time' => $checkInTime,
                'is_manual_entry' => true,
                'device_id' => 'manual_' . auth()->id(),
                'notes' => $this->notes,
            ]);

            // Si hay horas extras se guardarlas en la tabla extra_hours
            if ($this->hasOvertime && ($this->regularOvertime > 0 || $this->doubleOvertime > 0)) {
                
                // Guardar horas extras normales
                if ($this->regularOvertime > 0) {
                    ExtraHour::create([
                        'employee_id' => $this->employeeId,
                        'date' => Carbon::today(),
                        'hours' => $this->regularOvertime,
                        'rate_multiplier' => 1.0, // 100%
                        'approved_by' => auth()->id(),
                    ]);
                }

                // Guardar horas extras dobles
                if ($this->doubleOvertime > 0) {
                    ExtraHour::create([
                        'employee_id' => $this->employeeId,
                        'date' => Carbon::today(),
                        'hours' => $this->doubleOvertime,
                        'rate_multiplier' => 2.0, // 200%
                        'approved_by' => auth()->id(),
                    ]);
                }

                session()->flash('info', 'Horas extras registradas correctamente.');
            }

            $successMessage = 'Asistencia registrada exitosamente para ' . $this->employee->first_name;
            
            if ($this->attendanceType === 'late') {
                $successMessage .= ' (Registro con retraso)';
            }

            session()->flash('success', $successMessage);
            return redirect()->route('attendances.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al registrar asistencia: ' . $e->getMessage());
        }
    }

    // Método para calcular el total de horas extras
    public function getTotalOvertimeProperty()
    {
        return $this->regularOvertime + $this->doubleOvertime;
    }

    // Método para resetear completamente las horas extras
    public function resetOvertime()
    {
        $this->reset([
            'hasOvertime', 
            'regularOvertime', 
            'doubleOvertime', 
            'overtimeType', 
            'overtimeNotes'
        ]);
    }

    public function render()
    {
        return view('livewire.attendance.register', [
            'totalOvertime' => $this->totalOvertime,
        ]);
    }
}