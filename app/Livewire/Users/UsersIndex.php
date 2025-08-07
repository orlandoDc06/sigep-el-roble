<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;

class UsersIndex extends Component
{
    public function render()
    {
        return view('livewire.users.users-index');
    }
    
    // Propiedades para almacenar los usuarios y la búsqueda
    public $users, $search = '';

    // Método para cargar los usuarios de la base de datos
    public function mount()
    {
        $this->users = User::all();
    }

    // Método para eliminar un usuario
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        session()->flash('message', 'Usuario eliminado con éxito.');
        $this->dispatch('userDeleted');
        return redirect()->route('users.index');
    }

    // Método para buscar usuarios
    public function applySearch()
    {
        $this->users = User::where('name', 'ilike', '%' . $this->search . '%')
            ->orWhere('email', 'ilike', '%' . $this->search . '%')
            ->get();
    }

    // Método para restablecer la búsqueda
    public function resetSearch()
    {
        $this->search = '';
        $this->users = User::all();
    }

    // Método para obtener el rol del usuario usando Spatie
    public function getUserRole($user)
    {
        if (!$user->roles || $user->roles->isEmpty()) {
            return null;
        }
        
        // Retorna el primer rol del usuario
        return $user->roles->first()->name;
    }
}
