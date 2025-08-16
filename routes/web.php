<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Branches\Index;
use App\Livewire\Branches\Form;
use App\Livewire\Deductions\Index as DeductionsIndex;
use App\Livewire\Roles\ManageRoles;
use App\Livewire\Roles\ViewRoles;

use App\Livewire\Users\UsersIndex;
use App\Livewire\Users\UsersEdit;
use App\Livewire\Users\UsersEditEstado;
use App\Livewire\Users\UsersForm;

use App\Livewire\Shifts\ShiftsIndex;
use App\Livewire\Shifts\ShiftsEdit;
use App\Livewire\Shifts\ShiftsForm;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Management\EmployeeController;
use App\Livewire\Employees\EditEmployee;
use App\Models\Employee;
use App\Models\Deduction;
use Livewire\Livewire;

Route::get('/', function () {
    return view('auth.login');
});

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', fn () => view('admin.dashboard'))->name('admin.dashboard');
    Route::get('/employee/dashboard', fn () => view('employee.dashboard'))->name('employee.dashboard');
});
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', fn () => view('admin.dashboard'))->name('admin.dashboard');
    Route::get('/employee/dashboard', fn () => view('employee.dashboard'))->name('employee.dashboard');
});
// Rutas de sucursales
Route::get('/branches', Index::class)->name('branches.index');
Route::get('/branches/create', Form::class)->name('branches.create');
Route::get('/branches/{id}/edit', \App\Livewire\Branches\Edit::class)->name('branches.edit');

// Rutas protegidas de sucursales
Route::middleware('auth')->group(function () {
    Route::get('/branches', Index::class)->name('branches.index');
    Route::get('/branches/create', Form::class)->name('branches.create');
    Route::get('/branches/{id}/edit', \App\Livewire\Branches\Edit::class)->name('branches.edit');
});


// Rutas protegidas de gestión de usuarios
Route::middleware('auth')->group(function () {
    Route::get('/users', UsersIndex::class)->name('users.index');
    Route::get('/users/{id}/edit', UsersEdit::class)->name('users.edit');
    Route::get('/edit-estado/{record_id}/{type}', UsersEditEstado::class)->name('edit.estado');
    Route::get('/users/edit-estado/{user_id}', UsersEditEstado::class)->name('users.edit-estado');
    Route::get('/employees/{employee}/edit-live', EditEmployee::class)->name('employees.edit-live');
    Route::get('/users/create', UsersForm::class)->name('users.create');
    Route::get('/employees/{employee}/edit', \App\Livewire\Employees\EditEmployee::class)->name('employees.edit');
});

Route::middleware('auth')->group(function () {
    // Ruta para editar usuario normal (Livewire)
    Route::get('/users/{id}/edit', UsersEdit::class)->name('users.edit');

    // Ruta para editar empleado (Livewire)
    Route::get('/employees/{employee}/edit-live', EditEmployee::class)->name('employees.edit-live');
});


// Rutas protegidas de gestión de usuarios
Route::middleware('auth')->group(function () {
    Route::get('/shifts', ShiftsIndex::class)->name('shifts.index');
    Route::get('/shifts/create', ShiftsForm::class)->name('shifts.create');
    Route::get('/shifts/{id}/edit', ShiftsEdit::class)->name('shifts.edit');
    Route::get('/edit-estado/{record_id}/{type}', UsersEditEstado::class)->name('edit.estado');
});

// Rutas públicas 
Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
Route::get('/forgot-password', ForgotPassword::class)->name('password.request');

// Función helper para verificar si es admin
function checkAdmin() {
    if (!auth()->check()) {
        abort(401, 'No autenticado');
    }
    
    if (!auth()->user()->hasRole('Administrador')) {
        abort(403, 'Acceso denegado. Solo administradores pueden acceder.');
    }
}
// Función helper para verificar si es empleado
function checkEmployee() {
    if (!auth()->check()) {
        abort(401, 'No autenticado');
    }
    
    if (!auth()->user()->hasRole('Empleado')) {
        abort(403, 'Acceso denegado. Solo empleados pueden acceder.');
    }
}

// Dashboard - Cualquier usuario autenticado
Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'redirect'])->name('dashboard.redirect');

// Dashboard específico para administradores
Route::middleware('auth')->get('/admin/dashboard', function() {
    checkAdmin();
    return view('admin.dashboard');
})->name('admin.dashboard');

// Dashboard específico para empleados
Route::middleware('auth')->get('/employee/dashboard', function() {
    checkEmployee();
    return view('employee.dashboard');
})->name('employee.dashboard');

// Rutas de sucursales 
Route::middleware('auth')->get('/branches', function() {
    checkAdmin();
    return app(Index::class)();
})->name('branches.index');

Route::middleware('auth')->get('/branches/create', function() {
    checkAdmin();
    return app(Form::class)();
})->name('branches.create');

Route::middleware('auth')->get('/branches/{id}/edit', function($id) {
    checkAdmin();
    return app(\App\Livewire\Branches\Edit::class)();
})->name('branches.edit');

// RUTAS PARA GESTIONAR USUARIOS- SOLO ADMINISTRADORES
Route::middleware('auth')->get('/users', function() {
    checkAdmin();
    return app(UsersIndex::class)();
})->name('users.index');

Route::middleware('auth')->get('/users/{id}/edit', function($id) {
    checkAdmin();
    return app(UsersEdit::class)();
})->name('users.edit');

Route::middleware('auth')->get('/edit-estado/{record_id}/{type}', function($record_id, $type) {
    checkAdmin();
    return app(UsersEditEstado::class)();
})->name('edit.estado');

Route::middleware('auth')->get('/users/edit-estado/{user_id}', function($user_id) {
    checkAdmin();
    return app(UsersEditEstado::class)();
})->name('users.edit-estado');

Route::middleware('auth')->get('/employees/{employee}/edit-live', function($employee) {
    checkAdmin();
    return app(EditEmployee::class)();
})->name('employees.edit-live');

Route::middleware('auth')->get('/users/create', function() {
    checkAdmin();
    return app(UsersForm::class)();
})->name('users.create');

Route::middleware('auth')->get('/employees/{employee}/edit', function($employee) {
    checkAdmin();
    return app(\App\Livewire\Employees\EditEmployee::class)();
})->name('employees.edit');
//----------------------------------------------------------------------------------------------------------

// Rutas de gestión de turnos - SOLO ADMINISTRADORES
Route::middleware('auth')->get('/shifts', function() {
    checkAdmin();
    return app(ShiftsIndex::class)();
})->name('shifts.index');

Route::middleware('auth')->get('/shifts/create', function() {
    checkAdmin();
    return app(ShiftsForm::class)();
})->name('shifts.create');

Route::middleware('auth')->get('/shifts/{id}/edit', function($id) {
    checkAdmin();
    return app(ShiftsEdit::class)();
})->name('shifts.edit');

// Rutas de roles - SOLO ADMINISTRADORES
Route::middleware('auth')->get('/admin/roles', function() {
    checkAdmin();
    return app(ViewRoles::class)();
})->name('admin.roles.index');

Route::middleware('auth')->get('/admin/roles/create', function() {
    checkAdmin();
    return app(ManageRoles::class)();
})->name('admin.roles.create');

Route::middleware('auth')->get('/admin/roles/{role}/edit', function($role) {
    checkAdmin();
    return app(ManageRoles::class)();
})->name('admin.roles.edit');

// Rutas de empleados - SOLO ADMINISTRADORES
Route::middleware('auth')->get('/employees', function() {
    checkAdmin();
    return app(EmployeeController::class)->index();
})->name('employees.index');

Route::middleware('auth')->get('/employees/create', function() {
    checkAdmin();
    return app(EmployeeController::class)->create();
})->name('employees.create');

Route::middleware('auth')->post('/employees', function() {
    checkAdmin();
    return app(EmployeeController::class)->store(request());
})->name('employees.store');

Route::middleware('auth')->get('/employees/{employee}', function(Employee $employee) {
    checkAdmin();
    return app(EmployeeController::class)->show($employee);
})->name('employees.show');

Route::middleware('auth')->get('/employees/{employee}/edit', function(Employee $employee) {
    checkAdmin();
    return view('employees.edit', compact('employee'));
})->name('employees.edit');

Route::middleware('auth')->put('/employees/{employee}', function(Employee $employee) {
    checkAdmin();
    return app(EmployeeController::class)->update(request(), $employee);
})->name('employees.update');

Route::middleware('auth')->delete('/employees/{employee}', function(Employee $employee) {
    checkAdmin();
    return app(EmployeeController::class)->destroy($employee);
})->name('employees.destroy');

Route::middleware('auth')->get('/employees/{employee}/edit-live', function(Employee $employee) {
    checkAdmin();
    return app(EditEmployee::class)();
})->name('employees.edit-live');

Route::middleware('auth')->get('/bonuses/create', function() {
    checkAdmin();
    return 'Formulario de bonificación aún no implementado.';
})->name('bonuses.create');

//rutas de descuentos
Route::middleware('auth')->group(function() {
    Route::get('/deductions', function() {
        checkAdmin();
        return app(\App\Livewire\Deductions\Index::class)();
    })->name('deductions.index');

    Route::get('/deductions/create', function() {
        checkAdmin();
        return app(\App\Livewire\Deductions\Create::class)();
    })->name('deductions.create');

    Route::get('/deductions/{id}/edit', function($id) {
        checkAdmin();
        return app(\App\Livewire\Deductions\Edit::class)();
    })->name('deductions.edit');
});

// Registrar componente Livewire
Livewire::component('employees.edit-employee', EditEmployee::class);