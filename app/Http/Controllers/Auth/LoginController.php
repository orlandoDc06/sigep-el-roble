<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); 
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credenciales = $request->only('email', 'password');

        //Mantiene al usuario autenticado incluso despues de cerrar el navegador
        if (Auth::attempt($credenciales, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Validar si el usuario está activo
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'mensaje' => 'Este usuario está inactivo.',
                ]);
            }

            // Redirigir por rol
            if ($user->hasRole('Administrador')) {
                return redirect('/admin/dashboard'); 
            }

            if ($user->hasRole('Empleado')) {
                return redirect('/employee/dashboard'); 
            }

            // Si no tiene rol asignado
            Auth::logout();
            return back()->withErrors([
                'mensaje' => 'Este usuario no tiene un rol asignado.',
            ]);
        }

        return back()->withErrors([
            'mensaje' => 'Las credenciales no son correctas.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
