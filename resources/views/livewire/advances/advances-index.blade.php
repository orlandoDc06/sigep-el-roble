<div class="p-4 space-y-4">
    <h1 class="text-2xl font-bold">Lista de Anticipos</h1>

    <a href="{{ route('advances.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Registrar Anticipo
    </a>

    <br><br>

    {{-- Barra de búsqueda --}}
     <div class="flex justify-between items-center mb-4">
            <div class="relative w-1/3">
                <input type="text" wire:model.live="search" placeholder="Buscar por empleado..." class="px-3 py-2 pr-10 border rounded w-full" />
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            {{-- Select con wire:change para filtro automático --}}
            <select wire:model="filterMonth" wire:change="applySearch" class="border border-gray-300 rounded px-3 py-2">
                <option value="all">Todos los meses</option>
                <option value="01">Enero</option>
                <option value="02">Febrero</option>
                <option value="03">Marzo</option>
                <option value="04">Abril</option>
                <option value="05">Mayo</option>
                <option value="06">Junio</option>
                <option value="07">Julio</option>
                <option value="08">Agosto</option>
                <option value="09">Septiembre</option>
                <option value="10">Octubre</option>
                <option value="11">Noviembre</option>
                <option value="12">Diciembre</option>
            </select>
    </div>

    <hr class="border-gray-300">

    @if($advances->count())
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left">Empleado</th>
                        <th class="border px-4 py-2 text-left">Monto</th>
                        <th class="border px-4 py-2 text-left">Fecha</th>
                        <th class="border px-4 py-2 text-left">Motivo</th>
                        <th class="border px-4 py-2 text-left">Aprobado por</th>
                        <th class="border px-4 py-2 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($advances as $advance)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2">{{ $advance->employee->first_name }} {{ $advance->employee->last_name }}</td>
                            <td class="border px-4 py-2">${{ number_format($advance->amount, 2) }}</td>
                            <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($advance->date)->locale('es')->translatedFormat('d \\d\\e F \\d\\e Y') }}</td>
                            <td class="border px-4 py-2">{{ $advance->reason ?? '---' }}</td>
                            <td class="border px-4 py-2">
                                @if($advance->approvedBy)
                                    {{ $advance->approvedBy->name }}
                                @else
                                    <span class="text-yellow-600">Sin aprobar</span>
                                @endif
                            </td>
                            <td class="border px-4 py-2 space-x-2">
                                <a href="{{ route('advances.edit', $advance->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Editar</a>
                                <button wire:click="confirmDelete({{ $advance->id }})"
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Eliminar
                                </button>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $advances->links() }}
        </div>
    @else
        <p class="text-gray-600 italic">No hay anticipos disponibles con los filtros aplicados.</p>
    @endif

    
{{-- Modal de confirmación --}}
@if($confirmingDeletion)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
            <h3 class="text-lg font-bold mb-4">Eliminar Anticipo</h3>
            <p class="mb-4">¿Seguro que deseas eliminar este anticipo? Esta acción no se puede deshacer.</p>
            <div class="flex justify-end space-x-2">
                <button wire:click="$set('confirmingDeletion', false)" 
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Cancelar
                </button>
                <button wire:click="deleteConfirmed" 
                        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
@endif

    {{-- Modal informativo --}}
    @if($infoModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                <h3 class="text-lg font-bold mb-4">Información</h3>
                <p class="mb-4">{{ $infoMessage }}</p>
                <div class="flex justify-end">
                    <button wire:click="$set('infoModal', false)" 
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
