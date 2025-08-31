@extends('layouts.app')

@section('titulo', 'Panel del Empleado')

@section('contenido')
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-xl font-bold mb-4 text-green-700">
            Bienvenido al Sistema, {{ auth()->user()->employee->first_name ?? auth()->user()->name }}
        </h3>
        
        <p class="text-gray-700 mb-6">Aquí verás tus horarios, asistencias, bonos y más.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Botón de Permisos y Ausencias -->
            
            <button class="bg-green-500 text-white px-4 py-2 rounded"><a href="{{ route('employee.attendance') }}"> Asistencia</a></button>

            <a href="{{ route('ausencias-justificadas') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg text-center font-medium transition duration-200 flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h3z"/>
                </svg>
                Permisos y Ausencias
            </a>

            <!-- Botón de Mi Planilla -->
            <a href="{{ route('employee.payroll') }}" 
               class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg text-center font-medium transition duration-200 flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Mi Planilla
            </a>
        </div>
    </div>
@endsection