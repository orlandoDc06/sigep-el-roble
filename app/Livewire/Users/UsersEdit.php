<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UsersEdit extends Component
{
    use WithFileUploads;

    public $userId;
    public $name;
    public $email;
    public $profile_image;      // Imagen nueva subida
    public $profile_image_path; // Ruta de imagen existente

    /**
     * Carga el usuario a editar según el ID proporcionado.
     */
    public function mount($id)
    {
        // Cargar datos del usuario a editar
        $this->userId = $id;
        $user = User::findOrFail($id);

        // Cargar datos del usuario
        $this->name = $user->name;
        $this->email = $user->email;
        $this->profile_image_path = $user->profile_image_path ?? null;
    }

    /**
     * Actualiza los datos del usuario.
     */
    public function updateUser()
    {
        // Validar datos
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'profile_image' => 'nullable|image|max:2048',
        ]);

        // Cargar usuario
        $user = User::findOrFail($this->userId);

        // Actualizar datos del usuario
        $user->name = $this->name;
        $user->email = $this->email;

        if ($this->profile_image) {
            // Eliminar imagen anterior si existe
            if ($user->profile_image_path) {
                Storage::disk('public')->delete($user->profile_image_path);
            }

            // Guardar nueva imagen
            $imagePath = $this->profile_image->store('profile-images', 'public');
            $user->profile_image_path = $imagePath;
        }

        $user->save();
        session()->flash('success', 'Usuario actualizado correctamente.');
        return redirect()->route('users.index');
    }

    // Método para eliminar la imagen de perfil
    public function removeImage()
    {
        $user = User::findOrFail($this->userId);

        // Eliminar imagen en disco si existe
        if ($user->profile_image_path) {
            Storage::disk('public')->delete($user->profile_image_path);
        }

        // Limpiar propiedades y actualizar DB
        $user->profile_image_path = null;
        $user->save();

        $this->profile_image = null;
        $this->profile_image_path = null;
    }

    // Método para regresar al índice de usuarios
    public function returnIndex()
    {
        return redirect()->route('users.index');
    }

    // Método para renderizar la vista
    public function render()
    {
        return view('livewire.users.users-edit');
    }
}
