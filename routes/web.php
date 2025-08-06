<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\ForgotPassword;

use App\Livewire\Branches\Index;
use App\Livewire\Branches\Form;

use App\Livewire\Users\UsersIndex;
use App\Livewire\Users\UsersEdit;


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
});

Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
Route::get('/forgot-password', ForgotPassword::class)->name('password.request');

