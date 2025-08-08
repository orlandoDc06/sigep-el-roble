@extends('layouts.app')

@section('titulo', 'Nuevo Empleado')

@section('contenido')
    @livewire('employees.manage-employees')
    <a href="{{ route('employees.index') }}" class="mt-4 inline-block text-blue-500 hover:underline">Volver a Empleados</a>
@endsection
