<?php
namespace App\Livewire\Employees;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EditProfile extends Component
{
    use WithFileUploads;

    public Employee $employee;

    // Campos de perfil
    public $first_name, $last_name, $email, $phone, $address;
    public $birth_date, $gender, $marital_status, $photo_path;

    // Foto temporal
    public $photoFile;

    // Contraseña
    public $password, $password_confirmation;

    public function mount(Employee $employee)
    {
        $this->employee = $employee;
        $this->first_name = $employee->first_name;
        $this->last_name = $employee->last_name;
        $this->email = $employee->user?->email;
        $this->phone = $employee->phone;
        $this->address = $employee->address;
        $this->birth_date = $employee->birth_date;
        $this->gender = $employee->gender;
        $this->marital_status = $employee->marital_status;
        $this->photo_path = $employee->photo_path;
    }

    public function render()
    {
        return view('livewire.employees.edit-profile');
    }

    public function update()
    {
        $this->validate([
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'email'      => 'required|email|unique:users,email,' . $this->employee->user_id,
            'phone'      => 'nullable|string|max:15',
            'address'    => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'gender'     => 'nullable|in:male,female,other',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'photoFile'  => 'nullable|image|max:2048',
            'password'   => 'nullable|string|min:8|confirmed',
        ]);

        // Foto
        if ($this->photoFile) {
            $path = $this->photoFile->store('employees', 'public');
            $this->photo_path = $path;
        }

        // Actualizar Employee
        $this->employee->update([
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'phone'      => $this->phone,
            'address'    => $this->address,
            'birth_date' => $this->birth_date,
            'gender'     => $this->gender,
            'marital_status' => $this->marital_status,
            'photo_path' => $this->photo_path,
        ]);

        // Actualizar usuario relacionado
        if ($this->employee->user) {
            $user = $this->employee->user;
            $user->name = $this->first_name . ' ' . $this->last_name;
            $user->email = $this->email;

            if ($this->password) {
                $user->password = Hash::make($this->password);
            }

            $user->profile_image_path = $this->photo_path;
            $user->save();
        }

        session()->flash('message', 'Perfil actualizado correctamente.');

        return $this->redirect(route('profile.show', $this->employee->user_id));
    }
}
