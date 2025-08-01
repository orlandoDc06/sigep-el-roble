<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reiniciar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'ver usuarios',
            'crear usuarios',
            'editar usuarios',
            'eliminar usuarios',
            'ver empleados',
            'crear empleados',
            'editar empleados',
            'eliminar empleados',
            'ver asistencias',
            'registrar asistencias',
            'modificar asistencias',
            'ver turnos',
            'asignar turnos',
            'ver planillas',
            'generar planillas',
            'aprobar planillas',
            'eliminar planillas',
            'asignar bonificaciones',
            'asignar descuentos',
            'ver reportes',
            'ver configuración',
            'editar configuración',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Crear rol Administrador con todos los permisos
        $adminRole = Role::firstOrCreate(['name' => 'Administrador']);
        $adminRole->syncPermissions(Permission::all());

        // Puedes crear otros roles vacíos si lo deseas
        Role::firstOrCreate(['name' => 'Empleado']);
        Role::firstOrCreate(['name' => 'Supervisor']);
    }
}
