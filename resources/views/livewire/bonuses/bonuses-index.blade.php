<div class="p-4 space-y-4">
    <h1 class="text-2xl font-bold">Lista de bonificaciones</h1>

    <a href="{{ route('bonuses.create') }}" 
       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Crear bonificación
    </a>

    {{-- Mensajes flash --}}
    @if(session()->has('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mt-2">
            {{ session('success') }}
        </div>
    @elseif(session()->has('error'))
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mt-2">
            {{ session('error') }}
        </div>
    @endif

    {{-- Barra de búsqueda --}}
    <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center mt-4">
        <input type="text" 
               wire:model.defer="search" 
               wire:keydown.enter="applySearch" 
               placeholder="Buscar..." 
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

    <hr class="border-gray-300 mt-2">

    @if($bonuses->count())
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-sm mt-2">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left">Nombre</th>
                        <th class="border px-4 py-2 text-left">Descripción</th>
                        <th class="border px-4 py-2 text-left">Monto por defecto</th>
                        <th class="border px-4 py-2 text-left">Es %</th>
                        <th class="border px-4 py-2 text-left">Aplica a todos</th>
                        <th class="border px-4 py-2 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bonuses as $bonus)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2">{{ $bonus->name }}</td>
                            <td class="border px-4 py-2">{{ $bonus->description ?? '-' }}</td>
                            <td class="border px-4 py-2">${{ number_format($bonus->default_amount, 2) }}</td>
                            <td class="border px-4 py-2">
                                @if($bonus->is_percentage)
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">Sí</span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">No</span>
                                @endif
                            </td>
                            <td class="border px-4 py-2">
                                @if($bonus->applies_to_all)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Sí</span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">No</span>
                                @endif
                            </td>
                            <td class="border px-4 py-2 space-x-2">
                                <a href="{{ route('bonuses.edit', ['id' => $bonus->id]) }}" 
                                   class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                    Editar
                                </a>
                                <button wire:click="confirmDelete({{ $bonus->id }})" 
                                        class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-600 italic mt-2">No hay bonificaciones que coincidan con la búsqueda.</p>
    @endif

    {{-- Modal de confirmación --}}
    @if($confirmingDeletion)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                <h3 class="text-lg font-bold mb-4">Eliminar Bonificación</h3>
                <p class="mb-4">¿Seguro que deseas eliminar esta bonificación? Esta acción no se puede deshacer.</p>
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
