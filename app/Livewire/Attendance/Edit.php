<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Shift;
use App\Models\ExtraHour;
use Carbon\Carbon;

class Edit extends Component
{
    public $attendanceId, $attendance, $employee, $attendanceType = 'on_time', $notes = '', $selectedShift;
    public $extraHours;
    public $regularHoursTotal = 0;
    public $doubleHoursTotal = 0;
    public $totalHours = 0;
    public $hasOvertime = false, $regularOvertime = 0, $doubleOvertime = 0;
    
    protected $rules = [
        'attendanceType' => 'required|in:on_time,late,absent',
        'notes' => 'nullable|string|max:500',
        'selectedShift' => 'required|exists:shifts,id',
        'hasOvertime' => 'boolean',
        'regularOvertime' => 'required_if:hasOvertime,true|numeric|min:0|max:12',
        'doubleOvertime' => 'required_if:hasOvertime,true|numeric|min:0|max:12',
    ];

    protected $messages = [
        'regularOvertime.required_if' => 'Las horas extras normales son requeridas cuando se editan horas extras.',
        'doubleOvertime.required_if' => 'Las horas extras dobles son requeridas cuando se editan horas extras.',
    ];

    public function mount($attendanceId)
    {
        $this->attendanceId = $attendanceId;
        $this->loadAttendanceData();
    }

    public function loadAttendanceData()
    { 
        $this->attendance = Attendance::with(['employee.user', 'shift'])->find($this->attendanceId);
    
        if (!$this->attendance) {
            session()->flash('error', 'Registro de asistencia no encontrado.');
            return redirect()->route('attendances.index');
        }
        
        $this->employee = $this->attendance->employee;
        $this->attendanceType = $this->determineAttendanceStatus();
        $this->selectedShift = $this->attendance->shift_id;
        $this->notes = $this->attendance->notes ?? '';
        
        // Cargar horas extras existentes
        $this->loadExtraHours();
        
        // Esto permite agregar horas extras aunque no haya ninguna registrada
        $this->hasOvertime = true;
        
        // Cargar los valores actuales
        $this->regularOvertime = $this->regularHoursTotal;
        $this->doubleOvertime = $this->doubleHoursTotal;
    }
    // Carga las horas ectras
    protected function loadExtraHours()
    {// obtenemos las horas extras asociadas al ID del empleado
        $this->extraHours = ExtraHour::where('employee_id', $this->attendance->employee_id)
            ->whereDate('date', $this->attendance->check_in_time->toDateString())
            ->get();
        
        $this->regularHoursTotal = $this->extraHours->where('rate_multiplier', 1.0)->sum('hours');
        $this->doubleHoursTotal = $this->extraHours->where('rate_multiplier', 2.0)->sum('hours');
        $this->totalHours = $this->regularHoursTotal + $this->doubleHoursTotal;
    }

    public function updatedHasOvertime($value)
    {
        if (!$value) {
            $this->reset(['regularOvertime', 'doubleOvertime']);
        } else {
            $this->regularOvertime = $this->regularHoursTotal;
            $this->doubleOvertime = $this->doubleHoursTotal;
        }
    }

    protected function determineAttendanceStatus()
    {
        if (!$this->attendance->check_in_time) {
            return 'absent';
        }

        $scheduledTime = '08:00:00';
        $actualTime = $this->attendance->check_in_time->format('H:i:s');
        
        return ($actualTime > $scheduledTime) ? 'late' : 'on_time';
    }

    public function updateAttendance()
    {
        $this->validate();

        try {
            $checkInTime = $this->attendance->check_in_time; // Mantener la hora original
            
            if ($this->attendanceType === 'late') {
                $checkInTime->setTime(10, 0, 0); 
            } elseif ($this->attendanceType === 'on_time') {
                $checkInTime->setTime(8, 0, 0);
            }

            // Actualizar solo los campos necesarios
            $this->attendance->update([
                'shift_id' => $this->selectedShift,
                'check_in_time' => $checkInTime,
                'notes' => $this->notes,
            ]);

            // Manejar horas extras
            if ($this->hasOvertime) {
                $this->updateExtraHours();
            } else {
                $this->deleteExtraHours();
            }

            session()->flash('success', 'Asistencia actualizada exitosamente para ' . $this->employee->first_name);
            return redirect()->route('attendances.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar asistencia: ' . $e->getMessage());
        }
    }

    protected function updateExtraHours()
    {
        // Eliminar horas extras existentes
        ExtraHour::where('employee_id', $this->attendance->employee_id)
            ->whereDate('date', $this->attendance->check_in_time->toDateString())
            ->delete();

        // Crear nuevas horas extras
        if ($this->regularOvertime > 0) {
            ExtraHour::create([
                'employee_id' => $this->attendance->employee_id,
                'date' => $this->attendance->check_in_time->toDateString(),
                'hours' => $this->regularOvertime,
                'rate_multiplier' => 1.0,
                'approved_by' => auth()->id(),
            ]);
        }

        if ($this->doubleOvertime > 0) {
            ExtraHour::create([
                'employee_id' => $this->attendance->employee_id,
                'date' => $this->attendance->check_in_time->toDateString(),
                'hours' => $this->doubleOvertime,
                'rate_multiplier' => 2.0,
                'approved_by' => auth()->id(),
            ]);
        }
    }

    protected function deleteExtraHours()
    {
        ExtraHour::where('employee_id', $this->attendance->employee_id)
            ->whereDate('date', $this->attendance->check_in_time->toDateString())
            ->delete();
    }

    public function render()
    {
        return view('livewire.attendance.edit', [
            'shifts' => Shift::all(),
        ]);
    }
}