<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\ForgotPassword;

use App\Livewire\Branches\Index;
use App\Livewire\Branches\Form;
use App\Livewire\Roles\ManageRoles;
use App\Livewire\Roles\ViewRoles;

use App\Livewire\Users\UsersIndex;
use App\Livewire\Users\UsersEdit;
use App\Livewire\Users\UsersEditEstado;
use App\Livewire\Users\UsersForm;

use App\Livewire\Shifts\ShiftsIndex;
use App\Livewire\Shifts\ShiftsEdit;
use App\Livewire\Shifts\ShiftsForm;

use App\Http\Controllers\Management\EmployeeController;
use App\Livewire\Employees\EditEmployee;
use app\Models\Employee;
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

Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
Route::get('/forgot-password', ForgotPassword::class)->name('password.request');

// Vista principal de roles
Route::get('/admin/roles', ViewRoles::class)
    ->middleware(['auth'])
    ->name('admin.roles.index');

// Crear nuevo rol
Route::get('/admin/roles/create', ManageRoles::class)
    ->middleware(['auth'])
    ->name('admin.roles.create');

// Editar un rol específico
Route::get('/admin/roles/{role}/edit', ManageRoles::class)
    ->middleware(['auth'])
    ->name('admin.roles.edit');
// Rutas de gestión de empleados
Route::resource('employees', EmployeeController::class);
Route::get('/employees/{employee}/edit-live', EditEmployee::class)->name('employees.edit-live');
Livewire::component('employees.edit-employee', EditEmployee::class);

Route::get('/employees/{employee}/edit', function (Employee $employee) {
    return view('employees.edit', compact('employee'));
})->name('employees.edit');


// En routes/web.php
Route::get('/bonuses/create', function () {
    return 'Formulario de bonificación aún no implementado.';
})->name('bonuses.create');
