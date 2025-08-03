<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmpleadoSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario
        $user = User::firstOrCreate([
            'email' => 'empleado@gmail.com',
        ], [
            'name' => 'Empleado',
            'password' => bcrypt('empleado123'),
            'is_active' => true,
        ]);

        // Asignar rol
        $user->assignRole('Empleado');
        
    }
}
