@extends('layouts.app')

@section('titulo', 'Listado de Empleados')
@section('contenido')
    <div class="">
        <h3 class="text-xl font-bold mb-4">Listado de empleados</h3>
        <a href="{{ route('employees.create') }}"
           class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
            Agregar nuevo empleado
        </a></div><br>
    @livewire('employees.employee-list')
@endsection
