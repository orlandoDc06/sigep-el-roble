<?php
namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UsersIndex extends Component
{
    //Variables 
    public $users;
    public $search = '';
    public $filterRole = 'all';
    
    // Variables para el modal de activación
    public $confirmingActivation = false;
    public $userToActivate = null;

    // Variables para mostrar info temporal
    public $infoModal = false;
    public $infoMessage = '';

    //Array para los listenesr
    protected $listeners = ['userCreated' => 'loadUsers', 'userUpdated' => 'loadUsers'];

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
        //Validar los permisos
        if (!Auth::user()->can('editar usuarios')) {
            session()->flash('error', 'No tienes permisos para editar usuarios.');
            return redirect()->route('users.index');
        }

        $user = User::with('employee')->findOrFail($id);

        $role = $this->getUserRole($user); // Obtener el rol

        if ($role === 'Empleado') {
            if ($user->employee) {
                return redirect()->route('employees.edit-live', $user->employee->id);
            } else {
                session()->flash('error', 'Este usuario tiene rol Empleado pero no tiene un registro de empleado asociado.');
                return redirect()->route('users.index');
            }
        }

        if ($role === 'Administrador' || $role === 'Supervisor') {
            return redirect()->route('users.edit', $user->id);
        }

        // Solo permitir editar administradores desde este módulo
        if (!$user->hasRole('Administrador')) {
            session()->flash('error', 'Solo se pueden editar usuarios administrativos desde este módulo.');
            return redirect()->route('users.index');
        }
        return redirect()->route('users.edit', $user->id);
    }

    // Método para alternar el estado de un usuario
    public function toggleUserStatus($id)
    {
        //Validar los permisos
        if (!Auth::user()->can('editar usuarios')) {
            session()->flash('error', 'No tienes permisos para cambiar el estado de usuarios.');
            return;
        }

        //Obtener por id
        $user = User::findOrFail($id);

        //Validar lo permisos para poder editar
        if (Auth::id() === $user->id) {
            session()->flash('error', 'No puedes cambiar tu propio estado.');
            return;
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $this->loadUsers();
    }

    // Método para editar el estado de un usuario
    public function editStatus($id)
    {
        //Validar los permisos para poder editar
        if (!Auth::user()->can('editar usuarios')) {
            session()->flash('error', 'No tienes permisos para modificar el estado de usuarios.');
            return;
        }

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
        if (!Auth::user()->can('eliminar usuarios')) {
            session()->flash('error', 'No tienes permisos para eliminar usuarios.');
            return;
        }

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

     // Mostrar modal para activar
    public function confirmActivation($userId)
    {
        $this->userToActivate = $userId;
        $this->confirmingActivation = true;
    }

    // Activar usuario
    public function activateUser()
    {
        $user = User::find($this->userToActivate);
        if ($user) {
            $user->is_active = true;
            $user->save();

            $this->infoMessage = "Usuario activado con éxito.";
            $this->infoModal = true;
        }

        $this->confirmingActivation = false;
        $this->userToActivate = null;

        $this->loadUsers();
    }
    // Método para renderizar la vista
    public function render()
    {
        return view('livewire.users.users-index');
    }
}