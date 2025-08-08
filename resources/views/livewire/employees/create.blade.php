@extends('layouts.app')

@section('titulo', 'Nuevo Empleado')

@section('contenido')
    @livewire('employees.manage-employees', ['modoCreacion' => true])
@endsection
