@extends('layouts.app')

@section('titulo', 'Configuraciones Legales')

@section('contenido')
<div class="container mx-auto px-4">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="flex justify-between items-center p-6 border-b">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-balance-scale mr-2 text-blue-600"></i>
                    Configuraciones Legales
                </h2>
                <p class="text-gray-600 mt-1">Gestiona los porcentajes y parámetros legales para cálculos de planilla</p>
            </div>
            <a href="{{ route('admin.legal-configurations.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>Nueva Configuración
            </a>
        </div>

        <div class="p-6">
            <!-- Filtros -->
            <form method="GET" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">Todos los estados</option>
                            <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>
                                Activa
                            </option>
                            <option value="inactive" {{ ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' }}>
                                Inactiva
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Año</label>
                        <select name="year" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">Todos los años</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ ($filters['year'] ?? '') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" 
                                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition-colors w-full">
                            <i class="fas fa-search mr-2"></i>Filtrar
                        </button>
                    </div>
                </div>
            </form>

            @if($configurations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Periodo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    AFP
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ISSS
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Salario Mínimo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ISR
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($configurations as $config)
                                <tr class="{{ $config->is_active ? 'bg-green-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($config->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>Activa
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-circle mr-1"></i>Inactiva
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <strong>Desde:</strong> {{ $config->start_date->format('d/m/Y') }}
                                        </div>
                                        @if($config->end_date)
                                            <div class="text-sm text-gray-500">
                                                <strong>Hasta:</strong> {{ $config->end_date->format('d/m/Y') }}
                                            </div>
                                        @else
                                            <div class="text-sm text-blue-600">
                                                <strong>Vigente</strong>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-lg font-semibold text-blue-600">{{ number_format($config->afp_percentage, 2) }}%</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm">
                                            <div class="font-semibold text-purple-600">{{ number_format($config->isss_percentage, 2) }}%</div>
                                            <div class="text-gray-500">Tope: ${{ number_format($config->isss_max_cap, 2) }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-lg font-semibold text-green-600">${{ number_format($config->minimum_wage, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($config->income_tax_enabled)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-check mr-1"></i>Habilitado
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times mr-1"></i>Deshabilitado
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.legal-configurations.show', $config) }}" 
                                               class="text-blue-600 hover:text-blue-900" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.legal-configurations.edit', $config) }}" 
                                               class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(!$config->is_active)
                                                <form action="{{ route('admin.legal-configurations.activate', $config) }}" 
                                                      method="POST" 
                                                      class="inline"
                                                      onsubmit="return confirm('¿Activar esta configuración? Se desactivará la actual.')">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="text-green-600 hover:text-green-900" 
                                                            title="Activar">
                                                        <i class="fas fa-play-circle"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.legal-configurations.duplicate', $config) }}" 
                                                  method="POST" 
                                                  class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-purple-600 hover:text-purple-900" 
                                                        title="Duplicar">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </form>
                                            @if(!$config->is_active)
                                                <form action="{{ route('admin.legal-configurations.destroy', $config) }}" 
                                                      method="POST" 
                                                      class="inline"
                                                      onsubmit="return confirm('¿Está seguro de eliminar esta configuración?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900" 
                                                            title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="mt-6">
                    {{ $configurations->appends($filters)->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-balance-scale fa-4x text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay configuraciones legales</h3>
                    <p class="text-gray-500 mb-4">Crea la primera configuración legal para comenzar.</p>
                    <a href="{{ route('admin.legal-configurations.create') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>Crear Configuración
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .grid {
        display: grid;
    }
    
    .grid-cols-1 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
    
    @media (min-width: 768px) {
        .md\:grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
    
    .gap-4 > * + * {
        margin-top: 1rem;
    }
    
    @media (min-width: 768px) {
        .gap-4 {
            gap: 1rem;
        }
        .gap-4 > * + * {
            margin-top: 0;
        }
    }
</style>
@endpush