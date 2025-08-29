<div class="p-4 space-y-4">
    <h1 class="text-2xl font-bold">Bitácora de Cambios</h1>

    {{-- Barra de búsqueda --}}
    <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
        <input 
            type="text" 
            wire:model.defer="search" 
            wire:keydown.enter="$refresh" 
            placeholder="Buscar por módulo, campo o valor..." 
            class="border border-gray-300 rounded px-3 py-2 w-full sm:w-auto"
        >
        <button wire:click="$refresh" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
            Buscar
        </button>
        <button wire:click="$set('search', '')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            Limpiar filtro
        </button>
    </div>

    <hr class="border-gray-300">

    @if($logs->count())
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left">Fecha</th>
                        <th class="border px-4 py-2 text-left">Módulo</th>
                        <th class="border px-4 py-2 text-left">Campo Modificado</th>
                        <th class="border px-4 py-2 text-left">Valor Anterior</th>
                        <th class="border px-4 py-2 text-left">Nuevo Valor</th>
                        <th class="border px-4 py-2 text-left">Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2">{{ $log->changed_at }}</td>
                            <td class="border px-4 py-2">{{ class_basename($log->model) }}</td>
                            <td class="border px-4 py-2">{{ $log->field_changed }}</td>
                            <td class="border px-4 py-2">
                                <span class="text-gray-700">{{ $log->old_value ?? '-' }}</span>
                            </td>
                            <td class="border px-4 py-2">
                                <span class="text-green-700">{{ $log->new_value ?? '-' }}</span>
                            </td>
                            <td class="border px-4 py-2">
                                @if($log->changedBy)
                                    <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-medium">
                                        {{ $log->changedBy->name }}
                                    </span>
                                @else
                                    <span class="text-gray-500 italic">Desconocido</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    @else
        <p class="text-gray-600 italic">No se encontraron registros con los filtros aplicados.</p>
    @endif
</div>
