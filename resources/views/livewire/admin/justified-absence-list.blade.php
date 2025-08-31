<div class="p-6 max-w-4xl mx-auto bg-white rounded-lg shadow space-y-4">
    {{-- Mensaje de éxito --}}
    @if(session()->has('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 p-3 rounded flex items-center space-x-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <h2 class="text-2xl font-semibold text-gray-800">Ausencias de Empleados</h2>

    {{-- Barra de búsqueda --}}
    <div class="flex items-center space-x-2">
        <input type="text"
               wire:model.defer="search"
               placeholder="Buscar por empleado"
               class="border rounded px-3 py-2 w-full focus:outline-none focus:ring focus:ring-indigo-300">

        <button wire:click="applySearch"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Buscar
        </button>

        <button wire:click="resetSearch"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
            Limpiar
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-600">Empleado</th>
                    <th class="px-4 py-2 text-left text-gray-600">Fecha</th>
                    <th class="px-4 py-2 text-left text-gray-600">Motivo</th>
                    <th class="px-4 py-2 text-left text-gray-600">Estado</th>
                    <th class="px-4 py-2 text-left text-gray-600">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absences as $absence)
                    <tr class="border-t hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-2">
                            {{ $absence->employee->first_name }} {{ $absence->employee->last_name }}
                        </td>
                        <td class="px-4 py-2">
                            {{ \Carbon\Carbon::parse($absence->date)->format('d-m-Y') }}
                        </td>
                        <td class="px-4 py-2">{{ $absence->reason }}</td>
                        <td class="px-4 py-2 capitalize">
                            <span class="px-2 py-1 rounded
                                @if($absence->status === 'pendiente') bg-yellow-100 text-yellow-800
                                @elseif($absence->status === 'aprobado') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif
                            ">
                                {{ $absence->status }}
                            </span>
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            @if($absence->status === 'pendiente')
                                <button wire:click="updateStatus({{ $absence->id }}, 'aprobado')"
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded transition">
                                    Aprobar
                                </button>
                                <button wire:click="updateStatus({{ $absence->id }}, 'rechazado')"
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded transition">
                                    Rechazar
                                </button>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">
                            No se encontraron resultados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
