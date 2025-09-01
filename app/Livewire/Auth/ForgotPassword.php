<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Bus;

class ForgotPassword extends Component
{
    public $email;

    public function sendResetLink() 
    { 
        $this->validate([
            'email' => 'required|email|exists:users,email',
        ]);  

        $email = $this->email; // Capturar la variable

        try {
            Bus::dispatch(function () use ($email) { 
                $status = Password::sendResetLink(['email' => $email]);
                
                if ($status !== Password::RESET_LINK_SENT) {
                    \Log::error('Failed to send reset link for: ' . $email);
                }
            }); 

            session()->flash('status', 'Enlace de recuperación enviado al correo.'); 
        } catch (\Exception $e) {
            $this->addError('email', 'No se pudo enviar el enlace.');
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}