@extends('layouts.app')

@section('titulo', $configurationId ? 'Editar Configuración Legal' : 'Nueva Configuración Legal')

@section('contenido')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                {{ $configurationId ? 'Editar Configuración Legal' : 'Nueva Configuración Legal' }}
            </h2>
            <a href="{{ route('admin.legal-configurations.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>

        <!-- Mensajes -->
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Formulario -->
        <form wire:submit="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Información General -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Información General</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Inicio *</label>
                        <input type="date" wire:model="start_date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Fin</label>
                        <input type="date" wire:model="end_date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error('end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Salario Mínimo ($) *</label>
                        <input type="number" step="0.01" wire:model="minimum_wage" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error('minimum_wage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" wire:model="is_active" id="is_active"
                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">
                            Configuración Activa
                        </label>
                    </div>
                </div>

                <!-- Retenciones -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Retenciones Legales</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">AFP (%) *</label>
                        <input type="number" step="0.01" wire:model="afp_percentage" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error('afp_percentage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ISSS (%) *</label>
                        <input type="number" step="0.01" wire:model="isss_percentage" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error('isss_percentage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tope Máximo ISSS ($) *</label>
                        <input type="number" step="0.01" wire:model="isss_max_cap" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error('isss_max_cap') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" wire:model="income_tax_enabled" id="income_tax_enabled"
                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <label for="income_tax_enabled" class="ml-2 block text-sm text-gray-700">
                            Habilitar Retención ISR
                        </label>
                    </div>
                </div>

                <!-- Bonificaciones -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Bonificaciones</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bono Vacacional (%) *</label>
                        <input type="number" step="0.01" wire:model="vacation_bonus_percentage" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error('vacation_bonus_percentage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Días Aguinaldo *</label>
                        <input type="number" wire:model="year_end_bonus_days" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error('year_end_bonus_days') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Rangos ISR -->
            @if($income_tax_enabled)
            <div class="mt-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Rangos de ISR</h3>
                    <button type="button" wire:click="addIsrRange" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                        <i class="fas fa-plus mr-1"></i>Agregar Rango
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Desde ($)</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Hasta ($)</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Porcentaje (%)</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cuota Fija ($)</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($isr_ranges as $index => $range)
                            <tr class="border-t">
                                <td class="px-4 py-2">
                                    <input type="number" step="0.01" wire:model="isr_ranges.{{ $index }}.min_amount" 
                                           class="w-full px-2 py-1 border border-gray-300 rounded text-sm">
                                    @error("isr_ranges.{$index}.min_amount") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" step="0.01" wire:model="isr_ranges.{{ $index }}.max_amount" 
                                           placeholder="Sin límite" 
                                           class="w-full px-2 py-1 border border-gray-300 rounded text-sm">
                                    @error("isr_ranges.{$index}.max_amount") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" step="0.01" wire:model="isr_ranges.{{ $index }}.percentage" 
                                           class="w-full px-2 py-1 border border-gray-300 rounded text-sm">
                                    @error("isr_ranges.{$index}.percentage") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" step="0.01" wire:model="isr_ranges.{{ $index }}.fixed_fee" 
                                           class="w-full px-2 py-1 border border-gray-300 rounded text-sm">
                                    @error("isr_ranges.{$index}.fixed_fee") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </td>
                                <td class="px-4 py-2">
                                    @if(count($isr_ranges) > 1)
                                    <button type="button" wire:click="removeIsrRange({{ $index }})" 
                                            class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Botones de Acción -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('admin.legal-configurations.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-save mr-2"></i>
                    {{ $configurationId ? 'Actualizar' : 'Crear' }} Configuración
                </button>
            </div>
        </form>
    </div>
</div>
@endsection