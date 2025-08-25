@extends('layouts.app')

@section('titulo', 'Panel del Empleado')

@section('contenido')
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-xl font-bold mb-4 text-green-700">Bienvenido al Sistema, {{ auth()->user()->employee->first_name ?? auth()->user()->name }}</h3>
        <p class="text-gray-700">Aquí verás tus horarios, asistencias, bonos y más.</p>
        <button class="bg-green-500 text-white px-4 py-2 rounded"><a href="{{ route('ausencias-justificadas') }}"> Permisos y Ausencia</a></button>

    </div>
@endsection
