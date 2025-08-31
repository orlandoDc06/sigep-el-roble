<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;

class EmployeeAttendance extends Component
{
    use WithPagination;

    public $employee, $searchDate = '', $startDate = '', $endDate = '', $perPage = 15;

    public function mount()
    {
        // Obtener el empleado autenticado
        $this->employee = Employee::where('user_id', auth()->id())->first();
    }

    public function getAttendancesProperty()
    {
        $query = Attendance::where('employee_id', $this->employee->id)
            ->with('shift')
            ->orderBy('check_in_time', 'desc');

        // Filtrar por rango de fechas
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('check_in_time', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ]);
        }

        // Filtrar por fecha específica
        if ($this->searchDate) {
            $query->whereDate('check_in_time', Carbon::parse($this->searchDate));
        }

        return $query->paginate($this->perPage);
    }

    public function getAttendanceStatus($attendance)
    {
        if (!$attendance->check_in_time) {
            return ['status' => 'absent', 'label' => 'Ausente', 'class' => 'bg-red-100 text-red-800'];
        }

        $scheduledTime = '08:00:00';
        $toleranceTime = '08:15:00';
        $actualTime = $attendance->check_in_time->format('H:i:s');
        
        if ($actualTime <= $toleranceTime) {
            return ['status' => 'on_time', 'label' => 'A tiempo', 'class' => 'bg-green-100 text-green-800'];
        } else {
            return ['status' => 'late', 'label' => 'Retraso', 'class' => 'bg-yellow-100 text-yellow-800'];
        }
    }

    public function render()
    {
        return view('livewire.attendance.employee-attendance', [
            'attendances' => $this->attendances,
            'totalAttendances' => Attendance::where('employee_id', $this->employee->id)->count()
        ]);
    }
}