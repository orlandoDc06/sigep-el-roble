<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Branches\Index;
use App\Livewire\Branches\Form;
use App\Livewire\Deductions\Index as DeductionsIndex;

use App\Livewire\Attendance\Index as AttendanceIndex;
use App\Livewire\Attendance\Register as AttendanceRegister;
use App\Livewire\Attendance\Edit as AttendanceEdit;
use App\Livewire\Attendance\InfoEmployee as InfoEmployee;

use App\Livewire\Roles\ManageRoles;
use App\Livewire\Roles\ViewRoles;

use App\Livewire\Users\UsersIndex;
use App\Livewire\Users\UsersEdit;
use App\Livewire\Users\UsersEditEstado;
use App\Livewire\Users\UsersForm;

use App\Livewire\Shifts\ShiftsIndex;
use App\Livewire\Shifts\ShiftsEdit;
use App\Livewire\Shifts\ShiftsForm;

use App\Livewire\Bonuses\BonusesIndex;
use App\Livewire\Bonuses\BonusesForm;

use App\Livewire\Advances\AdvancesIndex;
use App\Livewire\Advances\AdvancesForm;
use App\Livewire\Advances\AdvancesEdit;
use App\Livewire\ChangeLogs\ChangeLogsIndex;


use App\Livewire\EmployeeBunusAssigments\EmployeeBonusAssignmentIndex;
use App\Livewire\EmployeeBunusAssigments\EmployeeBonusAssignmentForm;
use App\Livewire\EmployeeBunusAssigments\EmployeeBonusAssignmentEdit;


use App\Livewire\EmployeeDeductionsAssignments\EmployeeDeductionAssignmentIndex;
use App\Livewire\EmployeeDeductionsAssignments\EmployeeDeductionAssignmentForm;
use App\Livewire\EmployeeDeductionsAssignments\EmployeeDeductionAssignmentEdit;


use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Management\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Employees\EditEmployee;
use App\Models\Employee;
use App\Models\Deduction;
use App\Models\Attendance;
use Dom\Attr;
use Livewire\Livewire;
use App\Livewire\Admin\JustifiedAbsenceList;
use App\Livewire\Employees\JustifiedAbsence\JustifiedAbsenceManager;

use App\Http\Controllers\PayrollController;

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

//Rutas protegidas para los bonos
Route::middleware('auth')->group(function () {
    Route::get('/bonuses', BonusesIndex::class)->name('bonuses.index');
    Route::get('/bonuses/create', BonusesForm::class)->name('bonuses.create');
    Route::get('/bonuses/{id}/edit', BonusesForm::class)->name('bonuses.edit');
});

//Rutas proteginas  para asignacion de bonos
Route::middleware('auth')->group(function () {

    // Listado
    Route::get('/bonuses-assignments', EmployeeBonusAssignmentIndex::class)
        ->name('bonuses-assignments.index');

    // Crear
    Route::get('/bonuses-assignments/create', EmployeeBonusAssignmentForm::class)
        ->name('bonuses-assignments.create');

    // Editar
    Route::get('/bonuses-assignments/{id}/edit', EmployeeBonusAssignmentEdit::class)
        ->whereNumber('id')
        ->name('bonuses-assignments.edit');

});



//Rutas protegidas para anticipos
Route::middleware('auth')->group(function () {
    Route::get('/advances', AdvancesIndex::class)->name('advances.index');
    Route::get('/advances/create', AdvancesForm::class)->name('advances.create');
    Route::get('/advances/{id}/edit', AdvancesEdit::class)->name('advances.edit');
});


// Ruta para la bitácora de cambios
Route::middleware(['auth'])->group(function () {
    Route::get('/change-logs', ChangeLogsIndex::class)
        ->name('change-logs.index');
    });

// Ruta para la bitácora de cambios
Route::middleware(['auth'])->group(function () {
    Route::get('/change-logs', ChangeLogsIndex::class)
        ->name('change-logs.index');
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
    return app(BonusesForm::class)();
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

//RUTAS PROTEGIDAS PARA LOS BONOS -- SOLO ADMINISTRADORES
Route::middleware('auth')->get('/bonuses', function() {
    checkAdmin();
    return app(BonusesIndex::class)();
})->name('bonuses.index');

Route::middleware('auth')->get('/bonuses/create', function() {
    checkAdmin();
    return app(BonusesForm::class)();
})->name('bonuses.create');

Route::middleware('auth')->get('/bonuses/{id}/edit', function($id) {
    checkAdmin();
    return app(BonusesForm::class)(['id' => $id]);
})->whereNumber('id')->name('bonuses.edit');

//RUTAS PROTEGIDAS PARA ASSIGNACION DE BONOS -- SOLO LOS ADMINISTRDORES
Route::middleware('auth')->get('/bonuses-assignments', function() {
    checkAdmin();
    return app(EmployeeBonusAssignmentIndex::class)();
})->name('bonuses-assignments.index');

Route::middleware('auth')->get('/bonuses-assignments/create', function() {
    checkAdmin();
    return app(EmployeeBonusAssignmentForm::class)();
})->name('bonuses-assignments.create');

Route::middleware('auth')->get('/bonuses-assignments/{id}/edit', function($id) {
    checkAdmin();
    return app(EmployeeBonusAssignmentEdit::class)(['id' => $id]);
})->whereNumber('id')->name('bonuses-assignments.edit');

//RUTAS PROTEGIDAS PARA ASSIGNACION DE DESCUENTOS -- SOLO LOS ADMINISTRDORES
Route::middleware('auth')->get('/deductions-assignments', function() {
    checkAdmin();
    return app(EmployeeDeductionAssignmentIndex::class)();
})->name('deductions-assignments.index');

Route::middleware('auth')->get('/deductions-assignments/create', function() {
    checkAdmin();
    return app(EmployeeDeductionAssignmentForm::class)();
})->name('deductions-assignments.create');

Route::middleware('auth')->get('/deductions-assignments/{id}/edit', function($id) {
    checkAdmin();
    return app(EmployeeDeductionAssignmentEdit::class)(['id' => $id]);
})->whereNumber('id')->name('deductions-assignments.edit');

// Registrar componente Livewire
Livewire::component('employees.edit-employee', EditEmployee::class);

// Perfil del usuario autenticado
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');
});

// Perfil de un empleado (solo admin puede verlo)
Route::middleware(['auth', 'can:admin']) // o tu checkAdmin()
    ->get('/employees/{employee}/profile', [ProfileController::class, 'showEmployee'])
    ->name('employees.profile');

// Rutas de asistencia
Route::middleware('auth')->group(function() {
    Route::get('/attendances', function() {
        checkAdmin();
        return app(\App\Livewire\Attendance\Index::class)();
    })->name('attendances.index');
    Route::get('/attendance/register/{employeeId}', function($employeeId) {
        checkAdmin();
        return app(\App\Livewire\Attendance\Register::class, ['employeeId' => $employeeId])();
    })->name('attendance.register');
    Route::get('/attendance/edit/{attendanceId}', \App\Livewire\Attendance\Edit::class)
        ->name('attendance.edit');
    });
    Route::get('/employees/{employeeId}/infoAsistencias', InfoEmployee::class)
        ->name('employee.infoAsistencias');

    //Rutas de Special Days
    Route::middleware(['auth'])->group(function() {
        Route::get('/special-days', \App\Livewire\SpecialDays\Index::class)
            ->name('special-days.index');
        Route::get('/special-days/create', \App\Livewire\SpecialDays\Create::class)
            ->name('special-days.create');
        Route::get('/special-days/{specialDayId}/edit', \App\Livewire\SpecialDays\Edit::class)
            ->name('special-days.edit');
    });

// Rutas de ausencias justificadas para empleados
Route::middleware(['auth'])->group(function() {
    Route::get('/ausencias-justificadas', function() {
        checkEmployee();
        return app(JustifiedAbsenceManager::class)();
    })->name('ausencias-justificadas');
});

// Rutas de ausencias justificadas para administradores
Route::middleware(['auth'])->group(function() {
    Route::get('/admin/justified-absences', function() {
        checkAdmin();
        return app(JustifiedAbsenceList::class)();
    })->name('admin.justified-absences');
});
//RUTAS PROTEGIDAS PARA FÓRMULAS -- SOLO ADMINISTRADORES
Route::middleware('auth')->get('/admin/formulas', function() {
    checkAdmin();
    return app(\App\Livewire\Formulas\FormulasIndex::class)();
})->name('admin.formulas.index');

Route::middleware('auth')->get('/admin/formulas/create', function() {
    checkAdmin();
    return app(\App\Livewire\Formulas\FormulasForm::class)();
})->name('admin.formulas.create');

Route::middleware('auth')->get('/admin/formulas/{id}/edit', function($id) {
    checkAdmin();
    return app(\App\Livewire\Formulas\FormulasForm::class)(['id' => $id]);
})->whereNumber('id')->name('admin.formulas.edit');

//RUTAS PROTEGIDAS PARA CONFIGURACIONES LEGALES -- SOLO ADMINISTRADORES
Route::middleware('auth')->get('/admin/legal-configurations', function() {
    checkAdmin();
    return app(\App\Livewire\LegalConfigurations\LegalConfigurationIndex::class)();
})->name('admin.legal-configurations.index');

Route::middleware('auth')->get('/admin/legal-configurations/create', function() {
    checkAdmin();
    return app(\App\Livewire\LegalConfigurations\LegalConfigurationForm::class)();
})->name('admin.legal-configurations.create');

Route::middleware('auth')->get('/admin/legal-configurations/{id}/edit', function($id) {
    checkAdmin();
    return app(\App\Livewire\LegalConfigurations\LegalConfigurationForm::class)(['id' => $id]);
})->whereNumber('id')->name('admin.legal-configurations.edit');

// Ruta principal: listado de empleados con estado de planilla
Route::middleware('auth')->group(function () {    
    
    //Listado de planillas (vista con Livewire)
    Route::get('/payrolls', [PayrollController::class, 'index'])->name('payrolls.index');

    //Generar planilla individual (lleva al detalle antes de confirmar)
    Route::get('/payrolls/generate/{employee}', [PayrollController::class, 'generate'])
        ->name('payrolls.generate');

    //Guardar/generar planilla individual (POST desde confirmación)
    Route::post('/payrolls/generate/{employee}', [PayrollController::class, 'store'])
        ->name('payrolls.store');

    //Ver detalle de planilla ya generada
    Route::get('/payrolls/{payroll}', [PayrollController::class, 'show'])
        ->name('payrolls.show');

    Route::post('/payrolls/generate-all', [PayrollController::class, 'generateAll'])
        ->name('payrolls.generateAll');

    //Actualizar estado (approved, paid, etc.)
    Route::patch('/payrolls/{payroll}/status', [PayrollController::class, 'updateStatus'])
        ->name('payrolls.updateStatus');
});

// Ruta para que un empleado vea su propia planilla
Route::middleware('auth')->get('/employee/payroll', function() {
    checkEmployee();
    return app(PayrollController::class)->showEmployeePayroll();
})->name('employee.payroll');

// Ruta para descargar PDF de planilla del empleado
Route::middleware('auth')->get('/employee/payroll/pdf', function() {
    checkEmployee();
    return app(PayrollController::class)->downloadEmployeePayrollPDF();
})->name('employee.payroll.pdf');