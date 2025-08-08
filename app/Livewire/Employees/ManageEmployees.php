<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Employee;
use App\Models\Branch;
use App\Models\ContractType;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Notifications\SendCredentialNotification;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Spatie\Permission\Models\Role;

class ManageEmployees extends Component
{
    use WithPagination, WithFileUploads;

    public $modoCreacion = false;
    public $first_name, $last_name, $dui, $phone, $address;
    public $birth_date, $hire_date, $termination_date, $gender, $marital_status, $status;
    public $branch_id, $contract_type_id, $shift_id;

    public $editing = false;
    public $employeeId;

    public $branches;
    public $contractTypes;
    public $shifts;

    public $employees;

    public $email, $password;
    public $password_confirmation;

    public $photo_path;
    public $photoFile;

    public $roles;
    public $selectedRoles;

    protected $rules = [
        'first_name' => 'required|string|max:50',
        'last_name' => 'required|string|max:50',
        'dui' => 'required|string|max:20|unique:employees,dui',
        'phone' => 'nullable|string|max:15',
        'address' => 'nullable|string|max:255',
        'birth_date' => 'nullable|date',
        'hire_date' => 'nullable|date',
        'termination_date' => 'nullable|date|after_or_equal:hire_date',
        'gender' => 'nullable|in:male,female,other',
        'marital_status' => 'nullable|in:single,married,divorced,widowed',
        'status' => 'nullable|in:active,inactive,suspended',
        'branch_id' => 'required|exists:branches,id',
        'contract_type_id' => 'required|exists:contract_types,id',
        'shift_id' => 'required|exists:shifts,id',
        'email' => 'required|email|max:255|unique:users,email',
        'photoFile' => 'nullable|image|max:2048', // 2MB max
        'selectedRoles' => 'required|exists:roles,name',
    ];

    public function mount()
    {
        $this->branches = Branch::all();
        $this->contractTypes = ContractType::all();
        $this->shifts = Shift::all();
        $this->roles = Role::all();

        if (!$this->modoCreacion) {
            $this->loadEmployees();
        }
    }

    public function loadEmployees()
    {
        $this->employees = Employee::with(['branch', 'contractType'])
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.employees.manage-employees');
    }

    public function store()
    {
        $this->validate();
        if ($this->photoFile) {
        $this->photo_path = $this->photoFile->store('photos', 'public');
        }

        if (!$this->hire_date) {
            $this->hire_date = now()->toDateString();
        }

        if (!$this->status) {
            $this->status = 'active';
        }

        $empleado = Employee::create($this->formData());


        $password = Str::random(10);

        // Crear usuario asociado al empleado
        $user = User::create([
            'name' => $this->first_name . ' ' . $this->last_name,
            'email' => $this->email,
            'password' => Hash::make($password),
            'employee_id' => $empleado->id,
        ]);
        $user->assignRole($this->selectedRoles);

        $user->notify(new SendCredentialNotification($this->email, $password));

        session()->flash('message', 'Empleado y usuario creados correctamente. Credenciales enviadas por correo.');

        $this->resetForm();
        $this->loadEmployees();
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);

        $this->employeeId = $employee->id;
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
        $this->branch_id = $employee->branch_id;
        $this->contract_type_id = $employee->contract_type_id;
        $this->shift_id = $employee->shift_id;
        $user = User::where('employee_id', $this->employeeId)->first();
        $this->selectedRoles = $user ? $user->getRoleNames()->first() : null;


        $this->editing = true;
    }

    public function update()
    {
        $this->validate([
            ...$this->rules,
            'dui' => 'required|string|max:20|unique:employees,dui,' . $this->employeeId,
            'email' => 'required|email|max:255|unique:users,email,' . optional(User::where('employee_id', $this->employeeId)->first())->id,
        ]);

        $employee = Employee::findOrFail($this->employeeId);
        $employee->update($this->formData());

        // Actualizar email del usuario asociado si cambia
        $user = User::where('employee_id', $this->employeeId)->first();
        if ($user) {
            $user->email = $this->email;
            $user->syncRoles([$this->selectedRoles]);
            $user->save();
        }

        session()->flash('message', 'Empleado actualizado correctamente.');

        $this->resetForm();
        $this->loadEmployees();
    }

    public function delete($id)
    {
        $employee = Employee::findOrFail($id);

        // También elimina el usuario asociado si quieres
        $user = User::where('employee_id', $id)->first();
        if ($user) {
            $user->delete();
        }

        $employee->delete();

        session()->flash('message', 'Empleado eliminado correctamente.');
        $this->loadEmployees();
    }

    public function cancel()
    {
        $this->resetForm();
    }

    private function formData()
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'dui' => $this->dui,
            'phone' => $this->phone,
            'address' => $this->address,
            'birth_date' => $this->birth_date,
            'hire_date' => $this->hire_date,
            'termination_date' => $this->termination_date,
            'gender' => $this->gender,
            'marital_status' => $this->marital_status,
            'status' => $this->status,
            'branch_id' => $this->branch_id,
            'contract_type_id' => $this->contract_type_id,
            'shift_id' => $this->shift_id,
            'photo_path' => $this->photo_path,
        ];
    }

    private function resetForm()
    {
        $this->reset([
            'first_name', 'last_name', 'dui', 'phone', 'address',
            'birth_date', 'hire_date', 'termination_date', 'gender',
            'marital_status', 'status', 'branch_id', 'contract_type_id',
            'email', 'password', 'password_confirmation',
            'editing', 'employeeId', 'photo_path', 'selectedRoles'
        ]);
    }
}
