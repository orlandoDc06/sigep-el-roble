<div class="p-4 space-y-4">
    <h1 class="text-gray text-2xl font-bold">Lista de descuentos</h1>
    <a href="{{ route('deductions.create') }}" class="bg-azul-sigep text-white px-4 py-2 rounded hover:bg-azul-sigep-hover">Crear descuento</a>
    <br><br>
    <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
        <input type="text" wire:model.defer="search" wire:keydown.enter="applySearch" placeholder="Buscar..." class="border border-gray-300 rounded px-3 py-2 w-full sm:w-auto">
        <button wire:click="applySearch" class="bg-gray text-white px-4 py-2 rounded hover:bg-gray">Buscar</button>
        <button wire:click="resetSearch" class="bg-gray text-white px-4 py-2 rounded hover:bg-gray">Eliminar filtro</button>
    </div>
    <hr class="border-gray-300">
    @if($deductions->count())
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border text-black px-4 py-2 text-left">Nombre</th>
                        <th class="border text-black px-4 py-2 text-left">Descripción</th>
                        <th class="border text-black px-4 py-2 text-left">Monto</th>
                        <th class="border text-black px-4 py-2 text-left">Aplica a todos</th>
                        <th class="border text-black px-4 py-2 text-left">Es porcentaje</th>
                        <th class="border text-black px-4 py-2 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deductions as $deduction)
                        <tr class="hover:bg-gray-50">
                            <td class="text-black border px-4 py-2">{{ $deduction->name }}</td>
                            <td class="text-black border px-4 py-2">{{ $deduction->description }}</td>
                            <td class="text-black border px-4 py-2">
                                {{ $deduction->default_amount }}
                                {{ $deduction->is_percentage ? '%' : '' }}
                            </td>
                            <td class="text-black border px-4 py-2">
                                {{ $deduction->applies_to_all ? 'Sí' : 'No' }}
                            </td>
                            <td class="text-black border px-4 py-2">
                                {{ $deduction->is_percentage ? 'Sí' : 'No' }}
                            </td>
                            <td class="text-black border px-4 py-2 flex gap-2">
                                <button wire:click="editDeduction({{ $deduction->id }})" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                    Editar
                                </button>
                                <button wire:click="deleteDeduction({{ $deduction->id }})" 
                                onclick="confirm('¿Seguro que deseas eliminar este descuento?') || event.stopImmediatePropagation()" wire:click="deleteDeduction({{ $deduction->id }})" class="bg-rojo text-white px-4 py-2 rounded hover:bg-rojo-hover transition duration-150">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray italic">No hay descuentos disponibles.</p>
    @endif
</div>