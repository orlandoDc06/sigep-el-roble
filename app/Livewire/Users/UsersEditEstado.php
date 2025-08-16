<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class UsersEditEstado extends Component
{
    //VARIABLES
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
        $this->record_id = $record_id;

        // Intentar cargar empleado primero
        $employee = Employee::find($record_id);
        if ($employee) {
            $this->isEmployee = true;
            $this->name = $employee->first_name . ' ' . $employee->last_name;
            $this->status = $employee->status;
            return;
        }

        // Si no es empleado, cargar usuario
        if ($record_id == Auth::id()) {
            session()->flash('error', 'No puedes editar tu propio estado.');
            return redirect()->route('users.index');
        }

        $user = User::find($record_id);
        if ($user) {
            $this->isUser = true;
            $this->name = $user->name;
            $this->status = $user->is_active ? 'active' : 'inactive';

            // Ajusta esto según tu esquema real de roles
            $this->isAdmin = isset($user->role) && $user->role === 'admin';
            return;
        }

        // Si no existe en ninguna tabla
        session()->flash('error', 'Registro no encontrado.');
        return redirect()->route('users.index');
    }

    /**
     * Actualiza el estado de un usuario.
     */
    public function updateStatus()
    {
        if ($this->isUser) {
            return $this->updateUserStatus();
        } elseif ($this->isEmployee) {
            return $this->updateEmployeeStatus();
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
        $user->is_active = $this->status === 'active' ? 1 : 0;
        $user->save();

        session()->flash('success', 'Estado del administrador actualizado correctamente.');
        return redirect()->route('users.index');
    }

    /**
     * Actualiza el estado de un empleado.
     */
    private function updateEmployeeStatus()
    {
        // Elimina espacios antes y después
        $this->status = trim($this->status);

        $this->validate([
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $employee = Employee::findOrFail($this->record_id);
        $employee->status = $this->status;
        $employee->save();

        session()->flash('success', 'Estado del empleado actualizado correctamente.');
        return redirect()->route('users.index');
    }

    /**
     * Redirige a la página de índice correspondiente según el tipo de registro.
     */
    public function returnIndex()
    {
        return $this->isUser ? redirect()->route('users.index') : redirect()->route('employees.index');
    }

    /**
     * Funcion para renderizar la vista a editar estado
     */
    public function render()
    {
        // Pasar isAdmin para la vista
        return view('livewire.users.users-edit-estado', [
            'isAdmin' => $this->isAdmin,
        ]);
    }
}
