<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Notification;

class ForgotPassword extends Component
{
    public $email;

    public function sendResetLink()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        try {
            // Encontrar el usuario
            $user = User::where('email', $this->email)->first();
            
            // Generar token
            $token = Str::random(64);
            
            // Guardar el token en la base de datos
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $this->email],
                [
                    'email' => $this->email,
                    'token' => $token,
                    'created_at' => now()
                ]
            );

            // Crear URL de reset
            $resetUrl = url(route('password.reset', [
                'token' => $token,
                'email' => $this->email
            ]));

            // Enviar notificación usando MailMessage directamente
            Notification::send($user, new class($resetUrl) extends \Illuminate\Notifications\Notification {
                protected $resetUrl;

                public function __construct($resetUrl)
                {
                    $this->resetUrl = $resetUrl;
                }

                public function via($notifiable)
                {
                    return ['mail'];
                }

                public function toMail($notifiable)
                {
                    return (new MailMessage)
                        ->subject('Recuperar Contraseña')
                        ->greeting('¡Hola!')
                        ->line('Recibiste este correo porque solicitaste recuperar tu contraseña.')
                        ->action('Recuperar Contraseña', $this->resetUrl)
                        ->line('Este enlace expirará en 60 minutos.')
                        ->line('Si no solicitaste esto, puedes ignorar este correo.')
                        ->salutation('Saludos, ' . config('app.name'));
                }
            });

            session()->flash('status', 'Enlace de recuperación enviado al correo.');
            
        } catch (\Exception $e) {
            \Log::error('Error enviando email de reset: ' . $e->getMessage());
            $this->addError('email', 'No se pudo enviar el enlace.');
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}