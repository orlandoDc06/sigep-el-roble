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

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('status', 'Enlace de recuperación enviado al correo.');
        } else {
            $this->addError('email', 'No se pudo enviar el enlace.');
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
