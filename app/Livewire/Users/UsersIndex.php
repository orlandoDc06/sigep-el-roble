<?php
namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UsersIndex extends Component
{
    public $users;
    public $search = '';
    public $filterRole = 'all';

    // Método para inicializar el componente
    public function mount()
    {
        $this->loadUsers();
    }

    // Método para cargar los usuarios
    public function loadUsers()
    {
        $query = User::with(['roles', 'employee']);

        // Aplicar filtro de rol 
        if ($this->filterRole !== 'all') {
            $query->whereHas('roles', function ($roleQuery) {
                $roleQuery->where('name', $this->filterRole);
            });
        }
        
        // Aplicar búsqueda si hay texto en search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'ilike', '%' . $this->search . '%')
                  ->orWhere('email', 'ilike', '%' . $this->search . '%');
            });
        }
        
        // Ordenar por nombre para consistencia
        $query->orderBy('name', 'asc');
        
        $this->users = $query->get()->map(function ($user) {
            $user->isSelf = Auth::id() === $user->id;
            return $user;
        });
    }

    // Método para aplicar la búsqueda
    public function applySearch()
    {
        $this->loadUsers();
    }

    // Método para restablecer la búsqueda
    public function resetSearch()
    {
        $this->search = '';
        $this->filterRole = 'all';
        $this->loadUsers();
    }

    // Método para actualizar el filtro de rol
    public function updatedFilterRole()
    {
        $this->loadUsers();
    }

    // Método para obtener el rol de un usuario
    public function getUserRole($user)
    {
        if (!$user->roles || $user->roles->isEmpty()) {
            return null;
        }
        
        return $user->roles->first()->name;
    }

    // Método para editar un usuario
    public function editUser($id)
    {
        $user = User::with('employee')->findOrFail($id);
        
        if ($user->hasRole('Empleado')) {
            if ($user->employee) {
                return redirect()->route('employees.edit-live', $user->employee->id);
            } else {
                session()->flash('error', 'Este usuario tiene rol Empleado pero no tiene un registro de empleado asociado.');
                return redirect()->route('users.edit', $user->id);
            }
        }
        
        return redirect()->route('users.edit', $user->id);
    }

    // Método para alternar el estado de un usuario
    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        
        if (Auth::id() === $user->id) {
            session()->flash('error', 'No puedes cambiar tu propio estado.');
            return;
        }
        
        $user->is_active = !$user->is_active;
        $user->save();
        
        session()->flash('message', 'Estado actualizado correctamente.');
        $this->loadUsers();
    }

    // Método para editar el estado de un usuario
    public function editStatus($id)
    {
        $user = User::findOrFail($id);
        
        if (Auth::id() === $user->id) {
            session()->flash('error', 'No puedes modificar tu propio estado.');
            return;
        }
        
        return redirect()->route('edit.estado', ['record_id' => $id, 'type' => 'user']);
    }

    // Método para eliminar un usuario
    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            
            if (Auth::id() === $user->id) {
                session()->flash('error', 'No puedes eliminar tu propio usuario.');
                return;
            }
            
            $user->delete();
            session()->flash('message', 'Usuario eliminado correctamente.');
            $this->loadUsers();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }

    // Método para renderizar la vista
    public function render()
    {
        return view('livewire.users.users-index');
    }
}