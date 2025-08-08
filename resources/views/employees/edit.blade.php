@extends('layouts.app')

@section('titulo', 'Editar Empleado')

@section('contenido')
    <h1 class="text-2xl font-bold mb-4">Editar Empleado</h1>
    @livewire('employees.edit-employee', ['employee' => $employee])
@endsection
