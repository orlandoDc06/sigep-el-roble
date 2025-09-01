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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\ChangeLog;

class EditEmployee extends Component
{
    use WithFileUploads;

    public Employee $employee;

    public $first_name, $last_name, $dui, $phone, $address;
    public $birth_date, $hire_date, $termination_date, $gender, $marital_status, $status, $photo_path;
    public $user_id, $branch_id, $contract_type_id, $email;

    // NUEVO: Campo para contraseña
    public $password;
    public $password_confirmation;

    // Campos para turnos
    public $shift1_id;
    public $shift2_id;
    public $shift1_start_date;
    public $shift2_start_date;
    public $shift2_end_date;

    // AGREGAR este campo que faltaba
    public $shift_id;

    public $selectedRoles;
    public $branches, $contractTypes, $shifts, $roles;
    public $photoFile;

    public function mount(Employee $employee)
    {
        $this->employee = $employee;

        // Cargar todos los campos básicos
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

        // AGREGAR: Obtener el turno principal actual del empleado
        $currentShift = $employee->shifts()
            ->withPivot('start_date', 'end_date')
            ->where('employee_shift_assignments.end_date', null)
            ->orderBy('employee_shift_assignments.start_date')
            ->first();

        $this->shift_id = $currentShift ? $currentShift->id : null;

        // Obtener turnos para el sistema de múltiples turnos
        $employeeShifts = $employee->shifts()
            ->withPivot('start_date', 'end_date')
            ->orderBy('employee_shift_assignments.start_date')
            ->get();

        $this->shift1_id = $employeeShifts->first()?->id;
        $this->shift2_id = $employeeShifts->skip(1)->first()?->id;

        // Obtener fechas de inicio y fin actuales
        $this->shift1_start_date = $employeeShifts->first()?->pivot->start_date;
        $this->shift2_start_date = $employeeShifts->skip(1)->first()?->pivot->start_date;
        $this->shift2_end_date = $employeeShifts->skip(1)->first()?->pivot->end_date;

        // Cargar datos para los selects
        $this->branches = Branch::all();
        $this->contractTypes = ContractType::all();
        $this->shifts = Shift::all();
        $this->roles = Role::all();

        $this->selectedRoles = $employee->user ? $employee->user->roles->pluck('name')->first() : null;
    }

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
            'shift_id'          => 'nullable|exists:shifts,id',
            'shift1_id'         => 'required|exists:shifts,id',
            'shift2_id'         => 'nullable|different:shift1_id|exists:shifts,id',
            'shift1_start_date' => 'required|date',
            'shift2_start_date' => 'nullable|date',
            'shift2_end_date'   => 'nullable|date|after_or_equal:shift2_start_date',
            // NUEVO: Validaciones para contraseña
            'password'          => 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable|string|min:8',
        ]);

        if ($this->photoFile) {
            $path = $this->photoFile->store('employees', 'public');
            $this->photo_path = $path;
        }

        // Guardamos valores viejos antes de actualizar
        $oldValues = $this->employee->getOriginal();

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

        // Detectar cambios en Employee y guardarlos en la bitácora
        foreach ($this->employee->getChanges() as $field => $newValue) {
            if ($field === 'updated_at') continue;

            ChangeLog::create([
                'model'         => 'Employee',
                'model_id'      => $this->employee->id,
                'field_changed' => $field,
                'old_value'     => $oldValues[$field] ?? null,
                'new_value'     => $newValue,
                'changed_by'    => Auth::id(),
                'changed_at'    => now(),
            ]);
        }

        // Sincronizar turnos con fechas
        $this->syncEmployeeShifts();

    // Actualizar usuario asociado y registrar cambios
    if ($this->employee->user) {
        $user = $this->employee->user;
        $oldUserValues = $user->getOriginal();

        // Obtener roles anteriores para la bitácora
        $oldRoles = $user->roles->pluck('name')->toArray();

        $user->name = $this->first_name . ' ' . $this->last_name;
        $user->email = $this->email;
        $user->profile_image_path = $this->photo_path;
        $user->is_active = $this->status === 'active';

        // NUEVO: Actualizar contraseña si se proporcionó una nueva
        if ($this->password) {
            $user->password = Hash::make($this->password);

            // Registrar cambio de contraseña en bitácora
            ChangeLog::create([
                'model'         => 'User',
                'model_id'      => $user->id,
                'field_changed' => 'password',
                'old_value'     => '[PROTEGIDO]',
                'new_value'     => '[ACTUALIZADO]',
                'changed_by'    => Auth::id(),
                'changed_at'    => now(),
            ]);
        }

        // AGREGAR: Sincronizar roles
        if ($this->selectedRoles) {
            $user->syncRoles([$this->selectedRoles]);

            // Registrar cambio de rol en bitácora
            $newRoles = $user->fresh()->roles->pluck('name')->toArray();
            if ($oldRoles !== $newRoles) {
                ChangeLog::create([
                    'model'         => 'User',
                    'model_id'      => $user->id,
                    'field_changed' => 'roles',
                    'old_value'     => implode(', ', $oldRoles),
                    'new_value'     => implode(', ', $newRoles),
                    'changed_by'    => Auth::id(),
                    'changed_at'    => now(),
                ]);
            }
        }

        $user->save();

        foreach ($user->getChanges() as $field => $newValue) {
            if ($field === 'updated_at' || $field === 'password') continue; // Skip password ya que se registró arriba

            ChangeLog::create([
                'model'         => 'User',
                'model_id'      => $user->id,
                'field_changed' => $field,
                'old_value'     => $oldUserValues[$field] ?? null,
                'new_value'     => $newValue,
                'changed_by'    => Auth::id(),
                'changed_at'    => now(),
            ]);
        }
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
                'end_date' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Agregar turno 2 (opcional)
        if ($this->shift2_id) {
            $shiftsData[$this->shift2_id] = [
                'start_date' => $this->shift2_start_date ?: Carbon::now()->toDateString(),
                'end_date' => $this->shift2_end_date,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Registrar en bitácora
        foreach ($shiftsData as $shiftId => $pivotData) {
            ChangeLog::create([
                'model'         => 'EmployeeShift',
                'model_id'      => $this->employee->id,
                'field_changed' => 'shift_assignment',
                'old_value'     => 'Turnos previos eliminados',
                'new_value'     => 'Asignado turno ID ' . $shiftId . ' desde ' . $pivotData['start_date'] . ' hasta ' . ($pivotData['end_date'] ?? 'indefinido'),
                'changed_by'    => Auth::id(),
                'changed_at'    => now(),
            ]);
        }

        // Sincronizar con datos pivot
        $this->employee->shifts()->sync($shiftsData);
    }

    public function updatedShift2Id($value)
    {
        if (!$value) {
            $this->shift2_start_date = null;
            $this->shift2_end_date = null;
        } else {
            if (!$this->shift2_start_date) {
                $this->shift2_start_date = Carbon::now()->toDateString();
            }
        }
    }

    public function updatedShift2StartDate($value)
    {
        if ($this->shift2_end_date && $value && $this->shift2_end_date < $value) {
            $this->shift2_end_date = null;
        }
    }
}
