<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use App\Models\Employee;
use App\Models\ChangeLog;
use Illuminate\Support\Facades\Auth;

class UsersEditEstado extends Component
{
    // VARIABLES
    public $record_id;
    public $status;
    public $name;
    public $isUser = false;
    public $isEmployee = false;
    public $isAdmin = false;

    /**
     * Carga el registro a editar según el ID proporcionado.
     */
    public function mount($record_id)
    {
        // Validar que el usuario logueado sea administrador
        if (!Auth::user()->can('editar usuarios')) {
            session()->flash('error', 'No tienes permisos para editar estados de usuarios.');
            return redirect()->route('users.index');
        }

        $this->record_id = $record_id;

        // Intentar cargar empleado primero
        $employee = Employee::find($record_id);
        if ($employee) {
            $this->isEmployee = true;
            $this->name = $employee->first_name . ' ' . $employee->last_name;
            $this->status = $employee->status;
            return;
        }

        // Evitar que el admin edite su propio estado
        if ($record_id == Auth::id()) {
            session()->flash('error', 'No puedes editar tu propio estado.');
            return redirect()->route('users.index');
        }

        // Si no es empleado, cargar usuario
        $user = User::find($record_id);
        if ($user) {
            $this->isUser = true;
            $this->name = $user->name;
            $this->status = $user->is_active ? 'active' : 'inactive';
            $this->isAdmin = $user->hasRole('Administrador');
            return;
        }

        // Si no existe en ninguna tabla
        session()->flash('error', 'Registro no encontrado.');
        return redirect()->route('users.index');
    }

    /**
     * Actualiza el estado de un registro (usuario o empleado).
     */
    public function updateStatus()
    {
        // Validar permisos antes de actualizar
        if (!Auth::user()->can('editar usuarios')) {
            session()->flash('error', 'No tienes permisos para actualizar estados.');
            return redirect()->route('users.index');
        }

        if ($this->isUser) {
            $this->updateUserStatus();
        } elseif ($this->isEmployee) {
            $this->updateEmployeeStatus();
        } else {
            session()->flash('error', 'Tipo de registro inválido.');
            return redirect()->route('users.index');
        }
    }

    /**
     * Actualiza el estado de un usuario.
     */
    private function updateUserStatus()
    {
        if ($this->record_id == Auth::id()) {
            session()->flash('error', 'No puedes editar tu propio estado.');
            return;
        }

        $this->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::findOrFail($this->record_id);

        $oldValue = $user->is_active;
        $user->is_active = $this->status === 'active' ? 1 : 0;
        $user->save();

        $this->logChange('User', $user->id, 'is_active', $oldValue, $user->is_active);

        session()->flash('success', 'Estado del usuario actualizado correctamente.');
        return redirect()->route('users.index');
    }

    /**
     * Actualiza el estado de un empleado.
     */
    private function updateEmployeeStatus()
    {
        $this->status = trim($this->status);

        $this->validate([
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $employee = Employee::findOrFail($this->record_id);

        $oldValue = $employee->status;
        $employee->status = $this->status;
        $employee->save();

        $this->logChange(Employee::class, $employee->id, 'status', $oldValue, $employee->status);

        session()->flash('success', 'Estado del empleado actualizado correctamente.');
        return redirect()->route('users.index');
    }

    /**
     * Función para crear un registro en la bitácora (ChangeLog)
     */
    private function logChange($model, $model_id, $field, $oldValue, $newValue)
    {
        ChangeLog::create([
            'model' => $model,
            'model_id' => $model_id,
            'field_changed' => $field,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'changed_by' => Auth::id(),
            'changed_at' => now(),
        ]);
    }

    /**
     * Redirige a la página de índice según el tipo de registro.
     */
    public function returnIndex()
    {
        return $this->isUser ? redirect()->route('users.index') : redirect()->route('employees.index');
    }

    /**
     * Renderiza la vista para editar estado.
     */
    public function render()
    {
        return view('livewire.users.users-edit-estado', [
            'isAdmin' => $this->isAdmin,
        ]);
    }
}
