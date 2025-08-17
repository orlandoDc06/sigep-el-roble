<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Employee;
use App\Models\Branch;
use App\Models\ContractType;
use App\Models\Shift;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class EditEmployee extends Component
{
    use WithFileUploads;
    public Employee $employee;

    public $first_name, $last_name, $dui, $phone, $address;
    public $birth_date, $hire_date, $termination_date, $gender, $marital_status, $status, $photo_path;
    public $user_id, $branch_id, $contract_type_id, $email;

    public $shift1_id;
    public $shift2_id;
    
    // Agregar campos para las fechas de turnos
    public $shift1_start_date;
    public $shift2_start_date;
    public $shift2_end_date;

    public $selectedRoles;
    public $branches, $contractTypes, $shifts, $roles;
    public $photoFile;

    public function mount(Employee $employee)
    {
        $this->employee = $employee;

        $this->first_name = $employee->first_name;
        $this->last_name = $employee->last_name;
        $this->dui = $employee->dui;
        $this->phone = $employee->phone;
        $this->address = $employee->address;
        $this->birth_date = $employee->birth_date;
        $this->hire_date = $employee->hire_date;
        $this->termination_date = $employee->termination_date;
        $this->gender = $employee->gender;
        $this->marital_status = $employee->marital_status;
        $this->status = $employee->status;
        $this->photo_path = $employee->photo_path;
        $this->user_id = $employee->user_id;
        $this->branch_id = $employee->branch_id;
        $this->contract_type_id = $employee->contract_type_id;
        $this->email = $employee->user ? $employee->user->email : null;

        // Obtener turnos actuales del empleado usando la relación shifts existente
        $employeeShifts = $employee->shifts()->withPivot('start_date', 'end_date')->orderBy('employee_shift_assignments.start_date')->get();
        $this->shift1_id = $employeeShifts->first()?->id;
        $this->shift2_id = $employeeShifts->skip(1)->first()?->id;
        
        // Obtener fechas de inicio y fin actuales
        $this->shift1_start_date = $employeeShifts->first()?->pivot->start_date;
        $this->shift2_start_date = $employeeShifts->skip(1)->first()?->pivot->start_date;
        $this->shift2_end_date = $employeeShifts->skip(1)->first()?->pivot->end_date;

        $this->branches = Branch::all();
        $this->contractTypes = ContractType::all();
        $this->shifts = Shift::all();
        $this->roles = Role::all();

        $this->selectedRoles = $employee->user ? $employee->user->roles->pluck('name')->first() : null;
    } // <-- Esta llave faltaba

    public function render()
    {
        return view('livewire.employees.edit-employee');
    }

    public function update()
    {
        $this->validate([
            'first_name'        => 'required|string|max:50',
            'last_name'         => 'required|string|max:50',
            'dui'               => 'required|string|max:20|unique:employees,dui,' . $this->employee->id,
            'email'             => 'required|email|max:255|unique:users,email,' . ($this->employee->user ? $this->employee->user->id : 'NULL'),
            'phone'             => 'nullable|string|max:15',
            'address'           => 'nullable|string|max:255',
            'birth_date'        => 'nullable|date',
            'hire_date'         => 'required|date',
            'termination_date'  => 'nullable|date|after_or_equal:hire_date',
            'gender'            => 'nullable|in:male,female,other',
            'marital_status'    => 'nullable|in:single,married,divorced,widowed',
            'status'            => 'required|in:active,inactive,suspended',
            'branch_id'         => 'required|exists:branches,id',
            'contract_type_id'  => 'required|exists:contract_types,id',
            'selectedRoles'     => 'nullable|string|exists:roles,name',
            'photoFile'         => 'nullable|image|max:2048',
            'shift1_id'         => 'required|exists:shifts,id',
            'shift2_id'         => 'nullable|different:shift1_id|exists:shifts,id',
            'shift1_start_date' => 'required|date',
            'shift2_start_date' => 'nullable|date',
            'shift2_end_date'   => 'nullable|date|after_or_equal:shift2_start_date',
        ]);

        if ($this->photoFile) {
            $path = $this->photoFile->store('employees', 'public');
            $this->photo_path = $path;
        }

        $this->employee->update([
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            'dui'               => $this->dui,
            'phone'             => $this->phone,
            'address'           => $this->address,
            'birth_date'        => $this->birth_date,
            'hire_date'         => $this->hire_date,
            'termination_date'  => $this->termination_date,
            'gender'            => $this->gender,
            'marital_status'    => $this->marital_status,
            'status'            => $this->status,
            'photo_path'        => $this->photo_path,
            'user_id'           => $this->user_id,
            'branch_id'         => $this->branch_id,
            'contract_type_id'  => $this->contract_type_id,
        ]);

        // 🔹 SOLUCIÓN: Sincronizar turnos con fechas usando attach/detach
        $this->syncEmployeeShifts();

        // 🔹 actualizar usuario asociado
        if ($this->employee->user) {
            $user = $this->employee->user;
            $user->name = $this->first_name . ' ' . $this->last_name;
            $user->email = $this->email;
            $user->profile_image_path = $this->photo_path;
            $user->is_active = $this->status === 'active';
            $user->save();
        }

        session()->flash('message', 'Empleado actualizado correctamente.');
        return redirect()->route('employees.index');
    }

    /**
     * Sincroniza los turnos del empleado con las fechas correspondientes
     */
    private function syncEmployeeShifts()
    {
        // Eliminar asignaciones actuales
        $this->employee->shifts()->detach();

        // Preparar datos para sincronización con pivot data
        $shiftsData = [];

        // Agregar turno 1 (obligatorio)
        if ($this->shift1_id) {
            $shiftsData[$this->shift1_id] = [
                'start_date' => $this->shift1_start_date ?: Carbon::now()->toDateString(),
                'end_date' => null, // null significa asignación activa
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Agregar turno 2 (opcional)
        if ($this->shift2_id) {
            $shiftsData[$this->shift2_id] = [
                'start_date' => $this->shift2_start_date ?: Carbon::now()->toDateString(),
                'end_date' => $this->shift2_end_date, // Puede ser null (indefinido) o fecha específica
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Sincronizar con datos pivot
        $this->employee->shifts()->sync($shiftsData);
    }

    public function updatedShift2Id($value)
    {
        // Si se deselecciona el turno 2, limpiar las fechas
        if (!$value) {
            $this->shift2_start_date = null;
            $this->shift2_end_date = null;
        } else {
            // Si se selecciona un turno y no hay fecha de inicio, usar la fecha actual
            if (!$this->shift2_start_date) {
                $this->shift2_start_date = Carbon::now()->toDateString();
            }
        }
    }

    public function updatedShift2StartDate($value)
    {
        // Si la fecha de fin es anterior a la de inicio, limpiarla
        if ($this->shift2_end_date && $value && $this->shift2_end_date < $value) {
            $this->shift2_end_date = null;
        }
    }
}