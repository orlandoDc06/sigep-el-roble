@extends('layouts.app')

@section('titulo', 'Panel de Administración')

@section('contenido')
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-xl font-bold mb-4 text-green-700">Bienvenido al Panel de Administración</h3>
        <p class="text-gray-700">Desde aquí puedes gestionar empleados, turnos, sucursales y más.</p>
        <br>
        <button class="bg-green-500 text-white px-4 py-2 rounded"><a href="{{ route('branches.index') }}"> Sucursales</a></button>
    </div>
@endsection
