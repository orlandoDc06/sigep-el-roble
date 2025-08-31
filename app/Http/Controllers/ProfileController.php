<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Perfil del usuario autenticado
    public function show()
    {
        $user = Auth::user();
        $employee = $user->employee; // puede ser null

        // Preparamos datos dinámicos opcionales
        $recentBonuses = $employee?->bonuses()->latest('employee_bonus_assignments.applied_at')->take(3)->get();
        $recentDeductions = $employee?->deductions()->latest('employee_deduction_assignments.applied_at')->take(3)->get();

        return view('employees.profile', compact('user', 'employee', 'recentBonuses', 'recentDeductions'));
        //return view('employees.profile', compact('user', 'employee'));
    }

    // Perfil de un empleado (para admins)
    public function showEmployee(Employee $employee)
    {
        $user = $employee->user;
        return view('employees.profile', compact('user', 'employee'));
    }
}
