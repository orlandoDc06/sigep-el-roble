<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public $search = '', $perPage = 15, $selectedDate, $attendanceFilter = '';

    public function mount()
    {
        $this->selectedDate = Carbon::now()->format('Y-m-d');
    }

    // Obtiene la lista de empleados
    public function getEmployeesProperty()
    {
        $date = Carbon::parse($this->selectedDate);
        
        // Filtrar empleados que estaban contratados para la fecha seleccionada
        $query = Employee::where('hire_date', '<=', $date->format('Y-m-d'))
            ->where(function($q) use ($date) {
                $q->whereNull('termination_date')
                ->orWhere('termination_date', '>=', $date->format('Y-m-d'));
            })
            ->with(['attendances' => function($query) use ($date) {
                $query->whereDate('check_in_time', $date);
            }, 'user']);

        // Aplicar filtro de búsqueda
        if ($this->search) {
            $query->where(function($q) {
                $q->where('first_name', 'ilike', '%' . $this->search . '%')
                ->orWhere('last_name', 'ilike', '%' . $this->search . '%')
                ->orWhereHas('user', function($userQuery) {
                    $userQuery->where('email', 'ilike', '%' . $this->search . '%');
                });
            });
        }

        // Aplicar filtro de estado de asistencia
        switch ($this->attendanceFilter) {
            case 'absent':
                $query->whereDoesntHave('attendances', function($q) use ($date) {
                    $q->whereDate('check_in_time', $date);
                });
                break;
            case 'on_time':
                $query->whereHas('attendances', function($q) use ($date) {
                    $q->whereDate('check_in_time', $date)
                    ->whereTime('check_in_time', '<=', '08:15:00');
                });
                break;
            case 'late':
                $query->whereHas('attendances', function($q) use ($date) {
                    $q->whereDate('check_in_time', $date)
                    ->whereTime('check_in_time', '>', '08:15:00');
                });
                break;
            }

        return $query->orderBy('first_name')->orderBy('last_name')->paginate($this->perPage);
    }

    // Obtiene el objeto Employee
    protected function getEmployeeObject($employeeData)
    {
        if ($employeeData instanceof Employee) {
            return $employeeData;
        }
        
        if (is_array($employeeData) && isset($employeeData['id'])) {
            return Employee::with('user')->find($employeeData['id']);
        }
        
        return null;
    }

    //obtiene el nombre completo del empleado
    public function getFullName($employeeData)
    {
        $employee = $this->getEmployeeObject($employeeData);
        if (!$employee) {
            return 'Empleado no encontrado';
        }
        
        return $employee->first_name . ' ' . $employee->last_name;
    }

    //obtiene el email del empleado
    public function getEmail($employeeData)
    {
        $employee = $this->getEmployeeObject($employeeData);
        if (!$employee) {
            return 'Sin email';
        }
        
        return $employee->user->email ?? 'Sin email';
    }

    //redirige a la página de registro de asistencia
    public function redirigirRegistro($employeeId)
    {
        return redirect()->route('attendance.register', ['employeeId' => $employeeId]);
    }

    public function redirigirEditar($attendanceId)
    {
        return redirect()->route('attendance.edit', ['attendanceId' => $attendanceId]);
    }

    //registra la asistencia sin turno(en caso hubiera)
    public function registerWithoutShift($employeeId)
    {
        $employee = Employee::find($employeeId);
        if (!$employee) {
            session()->flash('error', 'Empleado no encontrado.');
            return;
        }

        try {
            Attendance::create([
                'employee_id' => $employee->id,
                'shift_id' => null,
                'check_in_time' => Carbon::now(),
                'is_manual_entry' => true,
                'device_id' => 'manual_' . auth()->id(),
            ]);

            session()->flash('success', 'Asistencia registrada para ' . $this->getFullName($employee));
            $this->emit('attendanceRegistered');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al registrar asistencia: ' . $e->getMessage());
        }
    }
    
    //restablece la busqueda
    public function resetSearch()
    {
        $this->search = ''; 
        $this->resetPage();
    }
    
    public function render()
    {
        $date = Carbon::parse($this->selectedDate);
        
        $registeredToday = Attendance::whereDate('check_in_time', $this->selectedDate)->count();
        
        // Total de empleados activos en la fecha seleccionada
        $totalEmployees = Employee::where('hire_date', '<=', $date->format('Y-m-d'))
            ->where(function($q) use ($date) {
                $q->whereNull('termination_date')
                ->orWhere('termination_date', '>=', $date->format('Y-m-d'));
            })
            ->count();
        
        return view('livewire.attendance.index', [
            'employees' => $this->employees,
            'totalEmployees' => $totalEmployees,
            'registeredToday' => $registeredToday
        ]);
    }

    public function updatedSelectedDate()
    {
        $this->resetPage(); // Reiniciar paginación cuando cambia la fecha
    }

    // restablece el filtro de fecha
    public function resetDateFilter()
    {
        $this->selectedDate = Carbon::now()->format('Y-m-d');
        $this->resetPage();
    }

    // obtiene el estado de asistencia del empleado
    public function getAttendanceStatus($employeeData)
    {
        $date = Carbon::parse($this->selectedDate);
        
        $employee = $this->getEmployeeObject($employeeData);
        if (!$employee) {
            return ['status' => 'pending', 'status_type' => 'absent'];
        }

        // Cargar asistencias para la fecha seleccionada
        $employee->load(['attendances' => function($query) use ($date) {
            $query->whereDate('check_in_time', $date);
        }]);

        if ($employee->attendances->isNotEmpty()) {
            $attendance = $employee->attendances->first();
            
            $status = $this->determineAttendanceStatus($attendance);
            
            return [
                'status' => 'registered',
                'time' => $attendance->check_in_time->format('H:i'),
                'is_manual' => $attendance->is_manual_entry,
                'attendance_id' => $attendance->id,
                'status_type' => $status 
            ];
        }
        return ['status' => 'pending', 'status_type' => 'absent'];
    }

    protected function determineAttendanceStatus($attendance)
    {
        if (!$attendance->check_in_time) {
            return 'absent';
        }
        $scheduledTime = '08:00:00'; 
        $actualTime = $attendance->check_in_time->format('H:i:s');
        
        $tolerance = strtotime('+15 minutes', strtotime($scheduledTime));
        $actualTimestamp = strtotime($actualTime);
        
        if ($actualTimestamp > $tolerance) {
            return 'late';
        }
        
        return 'on_time';
    }

    public function updatedAttendanceFilter()
    {
        $this->resetPage(); // Reiniciar paginación al cambiar filtro
    }

    public function resetAllFilters()
    {
        $this->reset(['search', 'attendanceFilter']);
        $this->selectedDate = Carbon::now()->format('Y-m-d');
        $this->resetPage();
    }

    // Método para aplicar búsqueda
    public function applySearch()
    {
        $this->resetPage();
    }

    // Método para filtrar por fecha 
    public function filterByDate($date)
    {
        $this->selectedDate = $date;
        $this->resetPage();
    }

    public function applyDateFilter()
    {
        $this->resetPage();
    }

    // Método para filtrar por estado de asistencia
    public function filterByAttendanceStatus($status)
    {
        $this->attendanceFilter = $status;
        $this->resetPage();
    }
    public function redirigirInfoAsistencias($employeeId)
    {
        return redirect()->route('employee.infoAsistencias', ['employeeId' => $employeeId]);
    }
}