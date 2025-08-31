<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Management\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PayrollController;

// Livewire Components
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Branches\{Index, Form, Edit as BranchEdit};
use App\Livewire\Deductions\Index as DeductionsIndex;
use App\Livewire\Attendance\{Index as AttendanceIndex, Register as AttendanceRegister, Edit as AttendanceEdit, InfoEmployee};
use App\Livewire\Roles\{ManageRoles, ViewRoles};
use App\Livewire\Users\{UsersIndex, UsersEdit, UsersEditEstado, UsersForm};
use App\Livewire\Shifts\{ShiftsIndex, ShiftsEdit, ShiftsForm};
use App\Livewire\Bonuses\{BonusesIndex, BonusesForm};
use App\Livewire\Advances\{AdvancesIndex, AdvancesForm, AdvancesEdit};
use App\Livewire\ChangeLogs\ChangeLogsIndex;
use App\Livewire\EmployeeBunusAssigments\{EmployeeBonusAssignmentIndex, EmployeeBonusAssignmentForm, EmployeeBonusAssignmentEdit};
use App\Livewire\EmployeeDeductionsAssignments\{EmployeeDeductionAssignmentIndex, EmployeeDeductionAssignmentForm, EmployeeDeductionAssignmentEdit};
use App\Livewire\Employees\EditEmployee;
use App\Livewire\Admin\JustifiedAbsenceList;
use App\Livewire\Employees\JustifiedAbsence\JustifiedAbsenceManager;

use App\Models\Employee;
use Livewire\Livewire;

// Helper functions
function checkAdmin() {
    if (!auth()->check()) {
        abort(401, 'No autenticado');
    }
    if (!auth()->user()->hasRole('Administrador')) {
        abort(403, 'Acceso denegado.');
    }
}

function checkEmployee() {
    if (!auth()->check()) {
        abort(401, 'No autenticado');
    }
    if (!auth()->user()->hasRole('Empleado')) {
        abort(403, 'Acceso denegado. Solo empleados pueden acceder.');
    }
}

// Public routes
Route::get('/', function () {
    return view('auth.login');
});

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Password reset routes
Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
Route::get('/forgot-password', ForgotPassword::class)->name('password.request');

// Protected routes
Route::middleware('auth')->group(function () {
    
    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'redirect'])->name('dashboard.redirect');
    
    Route::get('/admin/dashboard', function() {
        checkAdmin();
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    Route::get('/employee/dashboard', function() {
        checkEmployee();
        return view('employee.dashboard');
    })->name('employee.dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/employees/{employee}/profile', [ProfileController::class, 'showEmployee'])->name('employees.profile');

    // Admin only routes
    Route::group(['middleware' => function($request, $next) { checkAdmin(); return $next($request); }], function() {
        
        // Branches
        Route::get('/branches', Index::class)->name('branches.index');
        Route::get('/branches/create', Form::class)->name('branches.create');
        Route::get('/branches/{id}/edit', BranchEdit::class)->name('branches.edit');

        // Users management
        Route::get('/users', UsersIndex::class)->name('users.index');
        Route::get('/users/create', UsersForm::class)->name('users.create');
        Route::get('/users/{id}/edit', UsersEdit::class)->name('users.edit');
        Route::get('/users/edit-estado/{user_id}', UsersEditEstado::class)->name('users.edit-estado');
        Route::get('/edit-estado/{record_id}/{type}', UsersEditEstado::class)->name('edit.estado');

        // Employees management
        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
        Route::get('/employees/{employee}/edit', function(Employee $employee) {
            return view('employees.edit', compact('employee'));
        })->name('employees.edit');
        Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
        Route::get('/employees/{employee}/edit-live', EditEmployee::class)->name('employees.edit-live');

        // Shifts
        Route::get('/shifts', ShiftsIndex::class)->name('shifts.index');
        Route::get('/shifts/create', ShiftsForm::class)->name('shifts.create');
        Route::get('/shifts/{id}/edit', ShiftsEdit::class)->name('shifts.edit');

        // Roles
        Route::get('/admin/roles', ViewRoles::class)->name('admin.roles.index');
        Route::get('/admin/roles/create', ManageRoles::class)->name('admin.roles.create');
        Route::get('/admin/roles/{role}/edit', ManageRoles::class)->name('admin.roles.edit');

        // Deductions
        Route::get('/deductions', \App\Livewire\Deductions\Index::class)->name('deductions.index');
        Route::get('/deductions/create', \App\Livewire\Deductions\Create::class)->name('deductions.create');
        Route::get('/deductions/{id}/edit', \App\Livewire\Deductions\Edit::class)->name('deductions.edit');

        // Bonuses
        Route::get('/bonuses', BonusesIndex::class)->name('bonuses.index');
        Route::get('/bonuses/create', BonusesForm::class)->name('bonuses.create');
        Route::get('/bonuses/{id}/edit', BonusesForm::class)->whereNumber('id')->name('bonuses.edit');

        // Bonus assignments
        Route::get('/bonuses-assignments', EmployeeBonusAssignmentIndex::class)->name('bonuses-assignments.index');
        Route::get('/bonuses-assignments/create', EmployeeBonusAssignmentForm::class)->name('bonuses-assignments.create');
        Route::get('/bonuses-assignments/{id}/edit', EmployeeBonusAssignmentEdit::class)->whereNumber('id')->name('bonuses-assignments.edit');

        // Deduction assignments
        Route::get('/deductions-assignments', EmployeeDeductionAssignmentIndex::class)->name('deductions-assignments.index');
        Route::get('/deductions-assignments/create', EmployeeDeductionAssignmentForm::class)->name('deductions-assignments.create');
        Route::get('/deductions-assignments/{id}/edit', EmployeeDeductionAssignmentEdit::class)->whereNumber('id')->name('deductions-assignments.edit');

        // Advances
        Route::get('/advances', AdvancesIndex::class)->name('advances.index');
        Route::get('/advances/create', AdvancesForm::class)->name('advances.create');
        Route::get('/advances/{id}/edit', AdvancesEdit::class)->name('advances.edit');

        // Attendance
        Route::get('/attendances', \App\Livewire\Attendance\Index::class)->name('attendances.index');
        Route::get('/attendance/register/{employeeId}', \App\Livewire\Attendance\Register::class)->name('attendance.register');
        Route::get('/attendance/edit/{attendanceId}', AttendanceEdit::class)->name('attendance.edit');
        Route::get('/employees/{employeeId}/infoAsistencias', InfoEmployee::class)->name('employee.infoAsistencias');

        // Special Days
        Route::get('/special-days', \App\Livewire\SpecialDays\Index::class)->name('special-days.index');
        Route::get('/special-days/create', \App\Livewire\SpecialDays\Create::class)->name('special-days.create');
        Route::get('/special-days/{specialDayId}/edit', \App\Livewire\SpecialDays\Edit::class)->name('special-days.edit');

        // Justified absences (admin view)
        Route::get('/admin/justified-absences', JustifiedAbsenceList::class)->name('admin.justified-absences');

        // Formulas
        Route::get('/admin/formulas', \App\Livewire\Formulas\FormulasIndex::class)->name('admin.formulas.index');
        Route::get('/admin/formulas/create', \App\Livewire\Formulas\FormulasForm::class)->name('admin.formulas.create');
        Route::get('/admin/formulas/{id}/edit', \App\Livewire\Formulas\FormulasForm::class)->whereNumber('id')->name('admin.formulas.edit');

        // Legal configurations
        Route::get('/admin/legal-configurations', \App\Livewire\LegalConfigurations\LegalConfigurationIndex::class)->name('admin.legal-configurations.index');
        Route::get('/admin/legal-configurations/create', \App\Livewire\LegalConfigurations\LegalConfigurationForm::class)->name('admin.legal-configurations.create');
        Route::get('/admin/legal-configurations/{id}/edit', \App\Livewire\LegalConfigurations\LegalConfigurationForm::class)->whereNumber('id')->name('admin.legal-configurations.edit');

        // Change logs
        Route::get('/change-logs', ChangeLogsIndex::class)->name('change-logs.index');

        // Payrolls
        Route::get('/payrolls', [PayrollController::class, 'index'])->name('payrolls.index');
        Route::get('/payrolls/generate/{employee}', [PayrollController::class, 'generate'])->name('payrolls.generate');
        Route::post('/payrolls/generate/{employee}', [PayrollController::class, 'store'])->name('payrolls.store');
        Route::get('/payrolls/{payroll}', [PayrollController::class, 'show'])->name('payrolls.show');
        Route::post('/payrolls/generate-all', [PayrollController::class, 'generateAll'])->name('payrolls.generateAll');
        Route::patch('/payrolls/{payroll}/status', [PayrollController::class, 'updateStatus'])->name('payrolls.updateStatus');
    });

    // Employee only routes
    Route::group(['middleware' => function($request, $next) { checkEmployee(); return $next($request); }], function() {
        
        // Employee attendance
        Route::get('/employee/attendance', \App\Livewire\Attendance\EmployeeAttendance::class)->name('employee.attendance');
        
        // Justified absences (employee view)
        Route::get('/ausencias-justificadas', JustifiedAbsenceManager::class)->name('ausencias-justificadas');
        
        // Employee payroll
        Route::get('/employee/payroll', [PayrollController::class, 'showEmployeePayroll'])->name('employee.payroll');
        Route::get('/employee/payroll/pdf', [PayrollController::class, 'downloadEmployeePayrollPDF'])->name('employee.payroll.pdf');
    });
});

// Register Livewire components
Livewire::component('employees.edit-employee', EditEmployee::class);