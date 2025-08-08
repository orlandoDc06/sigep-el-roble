<div class="p-4 space-y-4">
    {{-- Care about people's approval and you will be their prisoner. --}}
    <h1 class="text-2xl font-bold">Lista de turnos</h1>
   
    <a href="{{ route('shifts.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Crear turno</a>
    <br><br>
    <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
        <input type="text" wire:model.defer="search" wire:keydown.enter="applySearch" placeholder="Buscar..." class="border border-gray-300 rounded px-3 py-2 w-full sm:w-auto">
        <button wire:click="applySearch" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Buscar</button>
        <button wire:click="resetSearch" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Eliminar filtro</button>
    </div>
    <hr class="border-gray-300">
    @if($shifts->count())
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left">Nombre</th>
                        <th class="border px-4 py-2 text-left">Hora de inicio</th>
                        <th class="border px-4 py-2 text-left">Hora de fin</th>
                        <th class="border px-4 py-2 text-left">Turno nocturno</th>
                        <th class="border px-4 py-2 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shifts as $shift)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2">{{ $shift->name }}</td>
                            <td class="border px-4 py-2">
                                {{ $shift->start_time ? date('H:i', strtotime($shift->start_time)) : '-' }}
                            </td>
                            <td class="border px-4 py-2">
                                {{ $shift->end_time ? date('H:i', strtotime($shift->end_time)) : '-' }}
                            </td>
                            <td class="border px-4 py-2">
                                @if($shift->is_night_shift)
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">Sí</span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">No</span>
                                @endif
                            </td>
                            
                            <td class="border px-4 py-2 space-x-2">
                                @can('editar turnos')
                                    <a href="{{ route('shifts.edit', ['id' => $shift->id]) }}" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Editar</a>
                                @endcan
                                
                                @can('eliminar turnos')
                                    <button onclick="confirm('¿Seguro que deseas eliminar este turno? Esta acción no se puede deshacer.') || event.stopImmediatePropagation()" wire:click="deleteShift({{ $shift->id }})" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Eliminar</button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-600 italic">No hay turnos disponibles.</p>
    @endif
</div>