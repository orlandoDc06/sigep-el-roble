<div class="p-4 space-y-4">
    <h1 class="text-2xl font-bold">Asignaciones de Descuentos</h1>

    <a href="{{ route('deductions-assignments.create') }}" 
       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Crear Asignación
    </a>

    <br><br>

    {{-- Barra de búsqueda --}}
    <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
        <input type="text" 
               wire:model.defer="search" 
               wire:keydown.enter="applySearch" 
               placeholder="Buscar por empleado o descuento..." 
               class="border border-gray-300 rounded px-3 py-2 w-full sm:w-auto">

        <button wire:click="applySearch" 
                class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
            Buscar
        </button>

        <button wire:click="resetSearch" 
                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            Eliminar filtro
        </button>
    </div>

    <hr class="border-gray-300">

    @if($assignments->count())
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left">Empleado</th>
                        <th class="border px-4 py-2 text-left">Descuento</th>
                        <th class="border px-4 py-2 text-left">Monto</th>
                        <th class="border px-4 py-2 text-left">Notas</th>
                        <th class="border px-4 py-2 text-left">Fecha</th>
                        <th class="border px-4 py-2 text-left">Asignado por</th>
                        <th class="border px-4 py-2 text-left">Estado</th>
                        <th class="border px-4 py-2 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignments as $assignment)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2">
                                {{ $assignment->employee->first_name }} {{ $assignment->employee->last_name }}
                            </td>
                            <td class="border px-4 py-2">{{ $assignment->deduction->name }}</td>
                            <td class="border px-4 py-2">${{ number_format($assignment->amount, 2) }}</td>
                            <td class="border px-4 py-2">{{ $assignment->notes ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $assignment->applied_at->format('Y-m-d') }}</td>
                            <td class="border px-4 py-2">{{ $assignment->assignedBy?->name ?? '-' }}</td>
                            <td class="border px-4 py-2">
                                @if ($assignment->status === 'active')
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Activo</span>
                                @else
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Anulado</span>
                                @endif
                            </td>
                            <td class="border px-4 py-2 space-x-2">
                                {{-- Botón Editar --}}
                                <a href="{{ route('bonuses-assignments.edit', $assignment->id) }}" 
                                   class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                    Editar
                                </a>

                                {{-- Botón Anular / Activar según estado --}}
                                <button 
                                    wire:click="confirmStatusChange({{ $assignment->id }})"
                                    class="{{ $assignment->status === 'active' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} 
                                           text-white px-3 py-1 rounded">
                                    {{ $assignment->status === 'active' ? 'Anular' : 'Activar' }}
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-600 italic">No hay asignaciones disponibles.</p>
    @endif

    {{-- Modal de confirmación --}}
    @if($confirmingStatusChange && $assignmentIdBeingUpdated)
        @php
            $selectedAssignment = $assignments->find($assignmentIdBeingUpdated);
        @endphp

        @if($selectedAssignment)
            <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                    <h3 class="text-lg font-bold mb-4">
                        {{ $selectedAssignment->status === 'active' ? 'Anular Asignación' : 'Activar Asignación' }}
                    </h3>

                    <p class="mb-4">
                        ¿Seguro que deseas 
                        <strong>{{ $selectedAssignment->status === 'active' ? 'ANULAR' : 'ACTIVAR' }}</strong> 
                        esta asignación?
                    </p>

                    <div class="flex justify-end space-x-2">
                        <button wire:click="$set('confirmingStatusChange', false)" 
                                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                            Cancelar
                        </button>
                        <button wire:click="changeStatus" 
                                class="px-4 py-2 rounded 
                                {{ $selectedAssignment->status === 'active' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white">
                            {{ $selectedAssignment->status === 'active' ? 'Anular' : 'Activar' }}
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
