<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UsersEdit extends Component
{
    use WithFileUploads;//Habilita la carga de archivos

    // Propiedades del usuario
    public $userId;
    public $name;
    public $email;
    public $profile_image;//imagen nueva
    public $profile_image_path;//Imagen antigua

    // Método para cargar los datos del usuario (como constructor)
    public function mount($id)
    {
        $this->userId = $id;
        $user = User::findOrFail($id);
        
        $this->name = $user->name;
        $this->email = $user->email;
        $this->profile_image_path = $user->profile_image ?? null;
    }

    // Método para actualizar el usuario
    public function updateUser()
    {
        // Validaciones
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'profile_image' => 'nullable|image|max:2048', // 2MB máximo
        ]);

        $user = User::findOrFail($this->userId);
        
        // Actualizar datos básicos
        $user->name = $this->name; 
        $user->email = $this->email;

        // Manejar la imagen de perfil
        if ($this->profile_image) {
            // Eliminar imagen anterior si existe
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // Guardar nueva imagen
            $imagePath = $this->profile_image->store('profile-images', 'public');
            $user->profile_image = $imagePath;
        }

        $user->save();

        session()->flash('success', 'Usuario actualizado correctamente.');

        // Redireccionar a la vista de la tabla de usuarios
        return redirect()->route('users.index');
    }

    // Método para eliminar imagen
    public function removeImage()
    {
        if ($this->profile_image) {
            $this->profile_image = null;
        } elseif ($this->original_profile_image) {
            $user = User::findOrFail($this->userId);
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
                $user->profile_image = null;
                $user->save();
            }
            $this->original_profile_image = null;
        }
    }

    // Método para volver al índice
    public function returnIndex()
    {
        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.users-edit');
    }
}