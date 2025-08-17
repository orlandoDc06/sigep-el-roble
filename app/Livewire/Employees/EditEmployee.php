<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Employee;
use App\Models\Branch;
use App\Models\ContractType;
use App\Models\Shift;
use Spatie\Permission\Models\Role;

class EditEmployee extends Component
{
    use WithFileUploads;
    public Employee $employee;

    public $first_name, $last_name, $dui, $phone, $address;
    public $birth_date, $hire_date, $termination_date, $gender, $marital_status, $status, $photo_path;
    public $user_id, $branch_id, $contract_type_id, $email;


    public $shift_id, $selectedRoles;
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
        $this->shift_id = $employee->shift_id;
        $this->email = $employee->user ? $employee->user->email : null;

        $this->branches      = Branch::all();
        $this->contractTypes = ContractType::all();
        $this->shifts        = Shift::all();
        $this->roles         = Role::all();

        $this->selectedRoles = $employee->user ? $employee->user->roles->pluck('name')->first() : null;

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
            'shift_id'          => 'required|exists:shifts,id',
            'selectedRoles'     => 'nullable|string|exists:roles,name',
            'photoFile'         => 'nullable|image|max:2048',
        ]);

        if ($this->photoFile) {
            $path = $this->photoFile->store('employees', 'public');
            $this->photo_path = $path;
        }


        $this->employee->update([
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            // 'email'          => $this->email, // Activa esto SOLO si tu tabla employees tiene columna email
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
            'shift_id'          => $this->shift_id,
        ]);

         // Actualizar tabla users (solo los campos correspondientes)
        if ($this->employee->user) {
            $user = $this->employee->user;
            $user->name = $this->first_name . ' ' . $this->last_name;
            $user->email = $this->email;
            $user->profile_image_path = $this->photo_path; // si quieres sincronizar foto también
            $user->is_active = $this->status === 'active';
            $user->save();
        }

        session()->flash('message', 'Empleado actualizado correctamente.');
        return redirect()->route('employees.index');
    }

    public function render()
    {
        return view('livewire.employees.edit-employee');
    }
}
