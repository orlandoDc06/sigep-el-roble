<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;

class ForgotPassword extends Component
{
    public $email;

    public function sendResetLink()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Enviar a cola en lugar de envío directo
        dispatch(function () {
            Password::sendResetLink(['email' => $this->email]);
        });

        // Siempre mostrar mensaje de éxito para mejor UX
        session()->flash('status', 'Enlace de recuperación enviado al correo.');
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
