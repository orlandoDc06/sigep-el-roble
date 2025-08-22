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

    public $search = '', $perPage = 15, $selectedDate;

    public function mount()
    {
        $this->selectedDate = Carbon::now()->format('Y-m-d');
    }

    // Obtiene la lista de empleados
    public function getEmployeesProperty()
    {
        $date = Carbon::parse($this->selectedDate);
        
        return Employee::query()
            ->with(['attendances' => function($query) use ($date) {
                $query->whereDate('check_in_time', $date);
            }, 'user'])
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function($userQuery) {
                          $userQuery->where('email', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->orderBy('first_name', 'asc')
            ->orderBy('last_name', 'asc')
            ->paginate($this->perPage);
    }

    public function getAttendanceStatus($employeeData)
    {
        $date = Carbon::parse($this->selectedDate);

        // Obtiene el objeto Employee
        $employee = $this->getEmployeeObject($employeeData);
        if (!$employee) {
            return ['status' => 'pending'];
        }

        // Carga las asistencias para la fecha seleccionada
        $employee->load(['attendances' => function($query) use ($date) {
            $query->whereDate('check_in_time', $date);
        }]);

        // Verifica si hay asistencia registrada
        if ($employee->attendances->isNotEmpty()) {
            $attendance = $employee->attendances->first();
            return [
                'status' => 'registered',
                'time' => $attendance->check_in_time->format('H:i'),
                'is_manual' => $attendance->is_manual_entry
            ];
        }
        return ['status' => 'pending'];
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
    //aplica la busqueda
    public function applySearch()
    {
            $employees = Employee::where(function($query) {
            $query->where('first_name', 'ilike', '%' . $this->search . '%')
                ->orWhere('last_name', 'ilike', '%' . $this->search . '%')
                ->orWhere('address', 'ilike', '%' . $this->search . '%');
        })->get();
    }

    //restablece la busqueda
    public function resetSearch()
    {
        $this->search = '';
    }
    
    public function render()
    {
        //busqueda filtrada por nombre o dirección
        $employees = Employee::where(function($query) {
            $query->where('first_name', 'ilike', '%' . $this->search . '%')
                ->orWhere('last_name', 'ilike', '%' . $this->search . '%')
                ->orWhere('address', 'ilike', '%' . $this->search . '%');
        })->get();

        return view('livewire.attendance.index', [
            'employees' => $this->employees,
            'totalEmployees' => Employee::count(),
            'registeredToday' => Attendance::whereDate('check_in_time', $this->selectedDate)->count()
        ]);
    }
}