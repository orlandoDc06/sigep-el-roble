<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

class InfoEmployee extends Component
{
    use WithPagination;

    public $employeeId;
    public $employee;
    public $searchDate = '';
    public $perPage = 10;
    public $startDate;
    public $endDate, $notes;

    public function mount($employeeId)
    {
        $this->employeeId = $employeeId;
        $this->employee = Employee::with('user')->find($employeeId);
        
        if (!$this->employee) {
            session()->flash('error', 'Empleado no encontrado.');
            return redirect()->route('attendances.index');
        }

        $this->endDate = Carbon::now()->format('Y-m-d');
        $this->startDate = Carbon::now()->subDays(30)->format('Y-m-d');
    }

    public function getAttendancesProperty()
    {
        $query = Attendance::where('employee_id', $this->employeeId)
            ->with('shift')
            ->orderBy('check_in_time', 'desc');

        // Filtrar por rango de fechas
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('check_in_time', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ]);
        }

        // Filtrar por fecha específica si se busca
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

    public function resetFilters()
    {
        $this->reset(['searchDate', 'startDate', 'endDate']);
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.attendance.info-employee', [
            'attendances' => $this->attendances,
            'totalAttendances' => Attendance::where('employee_id', $this->employeeId)->count()
        ]);

    }
}