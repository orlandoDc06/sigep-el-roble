<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UsersForm extends Component
{
    use WithFileUploads;

    // Propiedades del formulario
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $profile_image;
    public $profile_image_path = '';
    
    // Control de estados
    public $is_editing = false;
    public $user_id = null;

    // Reglas de validación
    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255|min:2',
            'email' => 'required|email|unique:users,email' . ($this->is_editing ? ',' . $this->user_id : ''),
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB máximo
        ];

        // Solo validar contraseña al crear
        if (!$this->is_editing) {
            $rules['password'] = ['required', 'confirmed', Password::min(8)];
            $rules['password_confirmation'] = 'required';
        } else {
            // Al editar, la contraseña es opcional
            $rules['password'] = ['nullable', 'confirmed', Password::min(8)];
        }

        return $rules;
    }

    // Mensajes de validación personalizados
    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'name.min' => 'El nombre debe tener al menos 2 caracteres.',
        'name.max' => 'El nombre no puede superar los 255 caracteres.',
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'Debe ser un correo electrónico válido.',
        'email.unique' => 'Este correo electrónico ya está en uso.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
        'password_confirmation.required' => 'Debe confirmar la contraseña.',
        'profile_image.image' => 'El archivo debe ser una imagen.',
        'profile_image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
        'profile_image.max' => 'La imagen no debe superar los 2MB.',
    ];

    // Método para inicializar el componente
    public function mount($user_id = null)
    {
        if ($user_id) {
            $this->is_editing = true;
            $this->user_id = $user_id;
            $this->loadUser();
        }
    }

    // Método para cargar un usuario
    public function loadUser()
    {
        try {
            $user = User::findOrFail($this->user_id);
            
            // Verificar permisos
            if (!Auth::user()->can('editar usuarios')) {
                session()->flash('error', 'No tienes permisos para editar usuarios.');
                return redirect()->route('users.index');
            }

            // Verificar que sea administrador 
            if (!$user->hasRole('Administrador')) {
                session()->flash('error', 'Solo se pueden editar usuarios administrativos desde este módulo.');
                return redirect()->route('users.index');
            }

            // Cargar datos del usuario
            $this->name = $user->name;
            $this->email = $user->email;
            $this->profile_image_path = $user->profile_image_path;

        } catch (\Exception $e) {
            session()->flash('error', 'Usuario no encontrado.');
            return redirect()->route('users.index');
        }
    }

    // Método para crear un usuario
    public function createUser()
    {
        // Verificar permisos
        if (!Auth::user()->can('crear usuarios')) {
            session()->flash('error', 'No tienes permisos para crear usuarios.');
            return;
        }

        $this->validate();

        // Subir imagen si hay
        $imagePath = null;
        if ($this->profile_image) {
            $imagePath = $this->profile_image->store('profile-images', 'public');
        }


        try {          
            // Crear usuario
            $user = User::create([
                'name' => trim($this->name),
                'email' => strtolower(trim($this->email)),
                'password' => Hash::make($this->password),
                'profile_image_path' => $imagePath,
                'is_active' => true,
                'email_verified_at' => now(), 
            ]);

            // Asignar rol de Administrador
            $user->assignRole('Administrador');

            session()->flash('message', 'Usuario administrativo creado exitosamente.');
            
            //  Enviar email con credenciales
            // Mail::to($user->email)->send(new WelcomeAdminMail($user, $this->password));

            return redirect()->route('users.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    // Método para actualizar un usuario
    public function updateUser()
    {
        // Verificar permisos
        if (!Auth::user()->can('editar usuarios')) {
            session()->flash('error', 'No tienes permisos para editar usuarios.');
            return;
        }

        $this->validate();

        try {
            $user = User::findOrFail($this->user_id);

            // Verificar que no esté editando su propio usuario si no tiene permisos
            if (Auth::id() === $user->id && !Auth::user()->can('editar propio perfil')) {
                session()->flash('error', 'No puedes editar tu propio perfil.');
                return;
            }

            // Manejar imagen
            $imagePath = $user->profile_image_path;
            if ($this->profile_image) {
                // Eliminar imagen anterior si existe
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $this->profile_image->store('profile-images', 'public');
            }

            // Preparar datos para actualizar
            $updateData = [
                'name' => trim($this->name),
                'email' => strtolower(trim($this->email)),
                'profile_image_path' => $imagePath,
            ];

            // Solo actualizar contraseña si se proporcionó una nueva
            if (!empty($this->password)) {
                $updateData['password'] = Hash::make($this->password);
            }

            $user->update($updateData);

            // Asegurar que mantenga el rol de Administrador
            if (!$user->hasRole('Administrador')) {
                $user->syncRoles(['Administrador']);
            }

            // Verificar si cambió el status
            if ($user->status !== $this->status) {
                ChangeLog::create([
                    'model'         => User::class,
                    'model_id'      => $user->id,
                    'field_changed' => 'status',
                    'old_value'     => $user->status,   // valor anterior
                    'new_value'     => $this->status,   // valor nuevo
                    'changed_by'    => Auth::id(),      // usuario que hace el cambio
                    'changed_at'    => now(),
                ]);
            }

            // Luego actualizas el usuario
            $user->update([
                'status' => $this->status,
            ]);
            session()->flash('message', 'Usuario actualizado exitosamente.');
            return redirect()->route('users.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    // Método para regresar al índice de usuarios
    public function returnIndex()
    {
        return redirect()->route('users.index');
    }

    public function updatedProfileImage()
    {
        $this->validateOnly('profile_image');
    }

    // Método para eliminar la imagen de perfil
    public function removeImage()
    {
        try {
            if ($this->is_editing && $this->profile_image_path) {
                $user = User::findOrFail($this->user_id);
                
                // Eliminar imagen del storage
                if (Storage::disk('public')->exists($this->profile_image_path)) {
                    Storage::disk('public')->delete($this->profile_image_path);
                }
                
                // Actualizar usuario
                $user->update(['profile_image_path' => null]);
                $this->profile_image_path = null;
                
                session()->flash('message', 'Imagen eliminada exitosamente.');
            } else {
                // Si está creando, solo limpiar la variable
                $this->profile_image = null;
                $this->profile_image_path = null;
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar la imagen: ' . $e->getMessage());
        }
    }

    // Método para restablecer el formulario
    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->profile_image = null;
        $this->profile_image_path = '';
        $this->resetValidation();
    }

    // Método para validar en tiempo real
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    // Método para renderizar la vista
    public function render()
    {
        return view('livewire.users.users-form');
    }
}