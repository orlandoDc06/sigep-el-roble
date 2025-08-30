<div class="p-4 space-y-4">
    {{-- Care about people's approval and you will be their prisoner. --}}
    <h1 class="text-gray text-2xl font-bold">Lista de turnos</h1>

    <a href="{{ route('shifts.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Crear turno</a>
    <br><br>
    
    <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
        <input type="text" wire:model.defer="search" wire:keydown.enter="applySearch" placeholder="Buscar..." class="border border-gray-300 rounded px-3 py-2 w-full sm:w-auto">
        <button wire:click="applySearch" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Buscar</button>
        <button wire:click="resetSearch" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Eliminar filtro</button>
    </div>
    
    @if($shifts->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($shifts as $shift)
                <div class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-200">
                    <!-- Cabecera con icono y nombre -->
                    <div class="flex items-start space-x-3 mb-4">
                        <div class="bg-blue-500 p-2 rounded-lg group-hover:bg-blue-600 transition-colors flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-medium text-gray-900 truncate">{{ $shift->name }}</h3>
                            <div class="mt-1">
                                @if($shift->is_night_shift)
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">Turno Nocturno</span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs font-medium">Turno Diurno</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Información de horarios -->
                    <div class="space-y-3 mb-4">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-600">Inicio:</span>
                            <span class="text-sm font-medium text-gray-900">
                                {{ $shift->start_time ? date('H:i', strtotime($shift->start_time)) : '-' }}
                            </span>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-600">Fin:</span>
                            <span class="text-sm font-medium text-gray-900">
                                {{ $shift->end_time ? date('H:i', strtotime($shift->end_time)) : '-' }}
                            </span>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex gap-2 pt-3">
                        @can('editar turnos')
                            <a href="{{ route('shifts.edit', ['id' => $shift->id]) }}" 
                               class="flex-1 bg-blue-600 text-white px-3 py-2 rounded text-center text-sm hover:bg-blue-700 transition duration-150">
                                Editar
                            </a>
                        @endcan
                        
                        @can('eliminar turnos')
                            <button onclick="confirm('¿Seguro que deseas eliminar este turno? Esta acción no se puede deshacer.') || event.stopImmediatePropagation()" 
                                    wire:click="deleteShift({{ $shift->id }})" 
                                    class="flex-1 bg-red-600 text-white px-3 py-2 rounded text-sm hover:bg-red-700 transition duration-150">
                                Eliminar
                            </button>
                        @endcan
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="mt-4 text-gray-600 italic">No hay turnos disponibles.</p>
        </div>
    @endif
</div>