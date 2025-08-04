<div class="p-4 space-y-4">
    {{-- Care about people's approval and you will be their prisoner. --}}
    <h1 class="text-2xl font-bold">Lista de sucursales</h1>
    
    <a href="{{ route('branches.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Crear sucursal</a>
    <br><br>
    <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
        <input type="text" wire:model.defer="search" wire:keydown.enter="applySearch" placeholder="Buscar..." class="border border-gray-300 rounded px-3 py-2 w-full sm:w-auto">
        <button wire:click="applySearch" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Buscar</button>
        <button wire:click="resetSearch" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Eliminar filtro</button>
    </div>

    <hr class="border-gray-300">

    @if($branches->count())
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left">Nombre</th>
                        <th class="border px-4 py-2 text-left">Dirección</th>
                        <th class="border px-4 py-2 text-left">Imagen</th>
                        <th class="border px-4 py-2 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($branches as $branch)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2">{{ $branch->name }}</td>
                            <td class="border px-4 py-2">{{ $branch->address }}</td>
                            <td class="border px-4 py-2">
                                @if ($branch->image_path)
                                    <img src="{{ Storage::url($branch->image_path) }}" alt="Sucursal" class="w-24 h-auto">
                                @else
                                    <span class="text-gray-500">Sin imagen</span>
                                @endif
                            </td>
                            <td class="border px-4 py-2 space-x-2">
                                <a href="{{ route('branches.edit', ['id' => $branch->id]) }}" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Editar</a>
                                <button onclick="confirm('¿Seguro que deseas eliminar esta sucursal?') || event.stopImmediatePropagation()" wire:click="deleteBranch({{ $branch->id }})" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-600 italic">No hay sucursales disponibles.</p>
    @endif
</div>
