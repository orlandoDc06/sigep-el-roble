@extends('layouts.app')

@section('titulo', 'Panel de Administración')
<a href="{{ route('branches.index') }}" class="text-sm text-gray-600 hover:underline font-semibold uppercase"> 
    Configuración
</a>
@section('contenido')
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-xl font-bold mb-4 text-green-700">Bienvenido al Panel de Administración</h3>
        <p class="text-gray-700">Desde aquí puedes gestionar empleados, turnos, sucursales y más.</p>
        <br>

        <button class="bg-green-500 text-white px-4 py-2 rounded"><a href="{{ route('branches.index') }}"> Sucursales</a></button>

        <!-- Botón para gestionar usuarios -->
        <button class="bg-green-500 text-white px-4 py-2 rounded"><a href="{{ route('users.index') }}"> Usuarios</a></button>
        <!-- Botón para gestionar turnos -->
        <button class="bg-green-500 text-white px-4 py-2 rounded"><a href="{{ route('shifts.index') }}"> Turnos</a></button>

        <!-- Botón para gestionar bonos -->
        <button class="bg-green-500 text-white px-4 py-2 rounded"><a href="{{ route('bonuses.index') }}"> Bonos</a></button>

        <!-- Botón para asignar bonos -->
        <button class="bg-green-500 text-white px-4 py-2 rounded"><a href="{{ route('bonuses-assignments.index') }}"> Bonos Asignados</a></button>

        <!-- Botón para asignar descuentos -->
        <button class="bg-green-500 text-white px-4 py-2 rounded"><a href="{{ route('deductions-assignments.index') }}"> Descuentos Asignados</a></button>

        <!-- Botón para gestion de anticipos -->
        <button class="bg-green-500 text-white px-4 py-2 rounded"><a href="{{ route('advances.index') }}"> Anticipos</a></button>

    </div>

        <button class="bg-green-500 text-white px-4 py-2 rounded"><a href="{{ route('admin.roles.index') }}"> Roles y permisos</a></button>

        <button class="bg-green-500 text-white px-4 py-2 rounded"><a href="{{ route('employees.index') }}"> Empleados</a></button>
        <button class="bg-green-500 text-white px-4 py-2 rounded"><a href="{{ route('deductions.index') }}"> Descuentos</a></button>
        <button class="bg-green-500 text-white px-4 py-2 rounded"><a href="{{ route('attendances.index') }}"> Asistencias</a></button>
        <button class="bg-green-500 text-white px-4 py-2 rounded"><a href="{{ route('admin.justified-absences') }}"> Permisos y Ausencia</a></button>

    </div>
@endsection
