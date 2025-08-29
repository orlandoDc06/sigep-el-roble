@extends('layouts.app')

@section('titulo', 'Ver Configuración Legal')

@section('contenido')
<div class="container mx-auto px-4 max-w-4xl">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="flex justify-between items-center p-6 border-b">
            <h2 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-balance-scale mr-2 text-blue-600"></i>
                Configuración Legal
            </h2>
            <div class="space-x-2">
                <a href="{{ route('admin.legal-configurations.edit', $configuration) }}" 
                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-edit mr-1"></i>Editar
                </a>
                <a href="{{ route('admin.legal-configurations.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-arrow-left mr-1"></i>Volver
                </a>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Información General</h3>
                    <p><strong>Estado:</strong> 
                        @if($configuration->is_active)
                            <span class="text-green-600">Activa</span>
                        @else
                            <span class="text-gray-600">Inactiva</span>
                        @endif
                    </p>
                    <p><strong>Inicio:</strong> {{ $configuration->start_date->format('d/m/Y') }}</p>
                    <p><strong>Fin:</strong> {{ $configuration->end_date ? $configuration->end_date->format('d/m/Y') : 'Indefinido' }}</p>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Parámetros</h3>
                    <p><strong>AFP:</strong> {{ number_format($configuration->afp_percentage, 2) }}%</p>
                    <p><strong>ISSS:</strong> {{ number_format($configuration->isss_percentage, 2) }}%</p>
                    <p><strong>Tope ISSS:</strong> ${{ number_format($configuration->isss_max_cap, 2) }}</p>
                    <p><strong>Salario Mínimo:</strong> ${{ number_format($configuration->minimum_wage, 2) }}</p>
                    <p><strong>Bono Vacacional:</strong> {{ number_format($configuration->vacation_bonus_percentage, 2) }}%</p>
                    <p><strong>Días Aguinaldo:</strong> {{ $configuration->year_end_bonus_days }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection