@extends('layouts.app')

@section('titulo', $configuration->exists ? 'Editar Configuración Legal' : 'Nueva Configuración Legal')

@section('contenido')
<div class="container mx-auto px-4 max-w-4xl">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="p-6 border-b">
            <h2 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-balance-scale mr-2 text-blue-600"></i>
                {{ $configuration->exists ? 'Editar Configuración Legal' : 'Nueva Configuración Legal' }}
            </h2>
            <p class="text-gray-600 mt-1">
                Define los parámetros legales para cálculos de planilla según la legislación salvadoreña
            </p>
        </div>

        <form action="{{ $configuration->exists ? route('admin.legal-configurations.update', $configuration) : route('admin.legal-configurations.store') }}" 
              method="POST">
            @csrf
            @if($configuration->exists)
                @method('PUT')
            @endif

            <div class="p-6">
                <!-- Información de Periodo -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                        <i class="fas fa-calendar-alt mr-2"></i>Periodo de Vigencia
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha de Inicio <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('start_date') border-red-500 @enderror" 
                                   id="start_date" 
                                   name="start_date" 
                                   value="{{ old('start_date', $configuration->start_date?->format('Y-m-d')) }}" 
                                   required>
                            @error('start_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha de Fin
                                <small class="text-gray-500">(Opcional - dejar vacío si es indefinida)</small>
                            </label>
                            <input type="date" 
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('end_date') border-red-500 @enderror" 
                                   id="end_date" 
                                   name="end_date" 
                                   value="{{ old('end_date', $configuration->end_date?->format('Y-m-d')) }}">
                            @error('end_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1" 
                                       class="mr-2"
                                       {{ old('is_active', $configuration->is_active) ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-gray-700">Configuración Activa</span>
                            </label>
                            <div class="ml-2">
                                <i class="fas fa-info-circle text-blue-500" 
                                   title="Solo puede haber una configuración activa. Al activar esta se desactivará la anterior."></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Porcentajes Legales -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                        <i class="fas fa-percentage mr-2"></i>Porcentajes Legales
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label for="afp_percentage" class="block text-sm font-medium text-gray-700 mb-2">
                                    AFP (%) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" 
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('afp_percentage') border-red-500 @enderror" 
                                           id="afp_percentage" 
                                           name="afp_percentage" 
                                           value="{{ old('afp_percentage', $configuration->afp_percentage) }}" 
                                           step="0.01" 
                                           min="0" 
                                           max="100" 
                                           required 
                                           placeholder="6.25">
                                    <span class="absolute right-2 top-2 text-gray-500">%</span>
                                </div>
                                @error('afp_percentage')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Ejemplo: 6.25 para AFP</p>
                            </div>

                            <div>
                                <label for="isss_percentage" class="block text-sm font-medium text-gray-700 mb-2">
                                    ISSS (%) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" 
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('isss_percentage') border-red-500 @enderror" 
                                           id="isss_percentage" 
                                           name="isss_percentage" 
                                           value="{{ old('isss_percentage', $configuration->isss_percentage) }}" 
                                           step="0.01" 
                                           min="0" 
                                           max="100" 
                                           required 
                                           placeholder="3.00">
                                    <span class="absolute right-2 top-2 text-gray-500">%</span>
                                </div>
                                @error('isss_percentage')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="vacation_bonus_percentage" class="block text-sm font-medium text-gray-700 mb-2">
                                    Bono Vacacional (%) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" 
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('vacation_bonus_percentage') border-red-500 @enderror" 
                                           id="vacation_bonus_percentage" 
                                           name="vacation_bonus_percentage" 
                                           value="{{ old('vacation_bonus_percentage', $configuration->vacation_bonus_percentage) }}" 
                                           step="0.01" 
                                           min="0" 
                                           max="100" 
                                           required 
                                           placeholder="30.00">
                                    <span class="absolute right-2 top-2 text-gray-500">%</span>
                                </div>
                                @error('vacation_bonus_percentage')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Usualmente 30% del salario base</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="isss_max_cap" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tope Máximo ISSS ($) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">$</span>
                                    <input type="number" 
                                           class="w-full border border-gray-300 rounded-md pl-8 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('isss_max_cap') border-red-500 @enderror" 
                                           id="isss_max_cap" 
                                           name="isss_max_cap" 
                                           value="{{ old('isss_max_cap', $configuration->isss_max_cap) }}" 
                                           step="0.01" 
                                           min="0" 
                                           required 
                                           placeholder="1000.00">
                                </div>
                                @error('isss_max_cap')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="minimum_wage" class="block text-sm font-medium text-gray-700 mb-2">
                                    Salario Mínimo ($) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">$</span>
                                    <input type="number" 
                                           class="w-full border border-gray-300 rounded-md pl-8 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('minimum_wage') border-red-500 @enderror" 
                                           id="minimum_wage" 
                                           name="minimum_wage" 
                                           value="{{ old('minimum_wage', $configuration->minimum_wage) }}" 
                                           step="0.01" 
                                           min="0" 
                                           required 
                                           placeholder="365.00">
                                </div>
                                @error('minimum_wage')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="year_end_bonus_days" class="block text-sm font-medium text-gray-700 mb-2">
                                    Días de Aguinaldo <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('year_end_bonus_days') border-red-500 @enderror" 
                                       id="year_end_bonus_days" 
                                       name="year_end_bonus_days" 
                                       value="{{ old('year_end_bonus_days', $configuration->year_end_bonus_days) }}" 
                                       min="0" 
                                       max="365" 
                                       required 
                                       placeholder="15">
                                @error('year_end_bonus_days')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Días laborales para aguinaldo</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Configuración ISR -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                        <i class="fas fa-receipt mr-2"></i>Impuesto Sobre la Renta (ISR)
                    </h3>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="income_tax_enabled" 
                                   value="1" 
                                   class="mr-2"
                                   {{ old('income_tax_enabled', $configuration->income_tax_enabled) ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-700">Habilitar retención de ISR</span>
                        </label>
                        <div>
                            <i class="fas fa-info-circle text-blue-500" 
                               title="Activa o desactiva el cálculo automático de ISR en las planillas"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        Los tramos de ISR se configuran por separado una vez creada la configuración legal
                    </p>
                </div>

                @if(isset($currentConfig) && $currentConfig && !$configuration->exists)
                    <!-- Comparación con configuración actual -->
                    <div class="mb-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-md font-semibold text-blue-800 mb-3">
                            <i class="fas fa-info-circle mr-2"></i>Configuración Actual (Referencia)
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="font-medium">AFP:</span> {{ number_format($currentConfig->afp_percentage, 2) }}%
                            </div>
                            <div>
                                <span class="font-medium">ISSS:</span> {{ number_format($currentConfig->isss_percentage, 2) }}%
                            </div>
                            <div>
                                <span class="font-medium">Salario Mínimo:</span> ${{ number_format($currentConfig->minimum_wage, 2) }}
                            </div>
                            <div>
                                <span class="font-medium">Tope ISSS:</span> ${{ number_format($currentConfig->isss_max_cap, 2) }}
                            </div>
                            <div>
                                <span class="font-medium">Bono Vacacional:</span> {{ number_format($currentConfig->vacation_bonus_percentage, 2) }}%
                            </div>
                            <div>
                                <span class="font-medium">Aguinaldo:</span> {{ $currentConfig->year_end_bonus_days }} días
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Botones de acción -->
            <div class="px-6 py-4 bg-gray-50 border-t flex justify-between">
                <a href="{{ route('admin.legal-configurations.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    {{ $configuration->exists ? 'Actualizar' : 'Crear' }} Configuración
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    .grid {
        display: grid;
    }
    
    .grid-cols-1 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
    
    @media (min-width: 768px) {
        .md\:grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .md\:grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
    
    .gap-4 > * + * {
        margin-top: 1rem;
    }
    
    .gap-6 > * + * {
        margin-top: 1.5rem;
    }
    
    @media (min-width: 768px) {
        .gap-4 {
            gap: 1rem;
        }
        .gap-6 {
            gap: 1.5rem;
        }
        .gap-4 > * + *,
        .gap-6 > * + * {
            margin-top: 0;
        }
    }
    
    input:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
</style>
@endpush
@endsection