<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Shift;
use Carbon\Carbon;

class Edit extends Component
{
    public $attendanceId, $attendance, $employee, $attendanceType = 'on_time', $notes = '', $selectedShift, $checkInTime;

    protected $rules = [
        'attendanceType' => 'required|in:on_time,late,absent',
        'notes' => 'nullable|string|max:500',
        'selectedShift' => 'required|exists:shifts,id',
        'checkInTime' => 'required|date'
    ];
    // Carga los datos de asistencia
    public function mount($attendanceId)
    {
        $this->attendanceId = $attendanceId;
        $this->loadAttendanceData();
    }

    // Carga los datos de asistencia
    protected function loadAttendanceData()
    {   
        $this->attendance = Attendance::with(['employee.user', 'shift'])->find($this->attendanceId);
        
        if (!$this->attendance) {
            session()->flash('error', 'Registro de asistencia no encontrado.');
            return redirect()->route('attendances.index');
        }
        $this->employee = $this->attendance->employee;
        
        // Cargar datos existentes
        $this->attendanceType = $this->determineAttendanceStatus();
        $this->notes = $this->attendance->notes ?? '';
        $this->selectedShift = $this->employee->getCurrentShift()->id ?? $this->attendance->shift_id;
        $this->checkInTime = $this->attendance->check_in_time->format('Y-m-d');
    }

    // Determina el estado de asistencia
    protected function determineAttendanceStatus()
    {
        if (!$this->attendance->check_in_time) {
            return 'absent';
        }

        $scheduledTime = '08:00:00';
        $actualTime = $this->attendance->check_in_time->format('H:i:s');
        
        return ($actualTime > $scheduledTime) ? 'late' : 'on_time';
    }

    // Actualiza los datos de asistencia
    public function updateAttendance()
    {
        $this->validate();

        try {
            $checkInTime = Carbon::parse($this->checkInTime);
            switch ($this->attendanceType) {
                case 'late':
                    $checkInTime->setTime(10, 0, 0); 
                    break;
                case 'absent':
                    session()->flash('warning', 'El estado "Ausente" se maneja de forma especial.');
                    return;
                default: 
                    $checkInTime->setTime(8, 0, 0);
            }
            // Actualiza los datos de asistencia
            $this->attendance->update([
                'shift_id' => $this->selectedShift,
                'check_in_time' => $checkInTime,
                'is_manual_entry' => true,
                'notes' => $this->notes,
            ]);

            session()->flash('success', 'Asistencia actualizada exitosamente para ' . $this->employee->first_name);
            
            return redirect()->route('attendances.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar asistencia: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.attendance.edit');
    }
}