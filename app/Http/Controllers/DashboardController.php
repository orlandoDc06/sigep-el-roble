<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function redirect()
    {
        $user = auth()->user();
        
        if ($user->hasRole('Administrador')) {
            return redirect()->route('admin.dashboard');
        }
        
        if ($user->hasRole('Empleado') || $user->hasRole('Supervisor')) {
            return redirect()->route('employee.dashboard');
        }
        
        // Fallback por si no tiene rol asignado
        return redirect()->route('employee.dashboard');
    }
}