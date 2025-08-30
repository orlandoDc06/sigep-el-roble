<div class="p-4 space-y-4">
    <h1 class="text-gray text-2xl font-bold">Bitácora de Cambios</h1>
    
    {{-- Barra de búsqueda --}}
    <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
        <input 
            type="text" 
            wire:model.defer="search" 
            wire:keydown.enter="$refresh" 
            placeholder="Buscar por módulo, campo o valor..." 
            class="border border-gray-300 rounded px-3 py-2 w-full sm:w-auto"
        >
        <button wire:click="$refresh" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition duration-150">
            Buscar
        </button>
        <button wire:click="$set('search', '')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition duration-150">
            Limpiar filtro
        </button>
    </div>
    
    @if($logs->count())
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="border-b border-gray-200 px-4 py-3 text-left font-medium text-gray-900">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Fecha</span>
                                </div>
                            </th>
                            <th class="border-b border-gray-200 px-4 py-3 text-left font-medium text-gray-900">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                                    </svg>
                                    <span>Módulo</span>
                                </div>
                            </th>
                            <th class="border-b border-gray-200 px-4 py-3 text-left font-medium text-gray-900">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 01-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 010-2h4a1 1 0 011 1v4a1 1 0 01-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12zm-9 7a1 1 0 012 0v1.586l2.293-2.293a1 1 0 111.414 1.414L6.414 15H8a1 1 0 010 2H4a1 1 0 01-1-1v-4zm13-1a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 010-2h1.586l-2.293-2.293a1 1 0 111.414-1.414L15 13.586V12a1 1 0 011-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Campo Modificado</span>
                                </div>
                            </th>
                            <th class="border-b border-gray-200 px-4 py-3 text-left font-medium text-gray-900">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Valor Anterior</span>
                                </div>
                            </th>
                            <th class="border-b border-gray-200 px-4 py-3 text-left font-medium text-gray-900">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Nuevo Valor</span>
                                </div>
                            </th>
                            <th class="border-b border-gray-200 px-4 py-3 text-left font-medium text-gray-900">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                    </svg>
                                    <span>Usuario</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($logs as $log)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-3 text-gray-900">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-blue-400 rounded-full"></div>
                                        <span class="font-medium text-sm">{{ $log->changed_at }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ class_basename($log->model) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-medium text-gray-900">{{ $log->field_changed }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($log->old_value)
                                        <div class="bg-red-50 border border-red-200 rounded px-2 py-1">
                                            <span class="text-red-700 text-sm">{{ $log->old_value }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($log->new_value)
                                        <div class="bg-green-50 border border-green-200 rounded px-2 py-1">
                                            <span class="text-green-700 text-sm">{{ $log->new_value }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($log->changedBy)
                                        <div class="flex items-center space-x-2">
                                            <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                                <span class="text-white text-xs font-medium">
                                                    {{ substr($log->changedBy->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">{{ $log->changedBy->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-500 italic">Desconocido</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Paginación --}}
        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-white rounded-lg border border-gray-200">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="mt-4 text-gray-600 italic">No se encontraron registros con los filtros aplicados.</p>
        </div>
    @endif
</div>