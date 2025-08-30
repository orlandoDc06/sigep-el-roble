<div class="p-4 space-y-4">
    {{-- Care about people's approval and you will be their prisoner. --}}
    <h1 class="text-gray text-2xl font-bold">Lista de sucursales</h1>

    <a href="{{ route('branches.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Crear sucursal</a>
    <br><br>
    
    <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
        <input type="text" wire:model.defer="search" wire:keydown.enter="applySearch" placeholder="Buscar..." class="border border-gray-300 rounded px-3 py-2 w-full sm:w-auto">
        <button wire:click="applySearch" class="bg-gray text-white px-4 py-2 rounded hover:bg-gray">Buscar</button>
        <button wire:click="resetSearch" class="bg-gray text-white px-4 py-2 rounded hover:bg-gray">Eliminar filtro</button>
    </div>
    
    <hr class="border-gray-300">
    
    @if($branches->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($branches as $branch)
                <div class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-200">
 
                    <!-- Información de la sucursal -->
                    <div class="space-y-2">
                        <div class="flex items-start space-x-3">
                            <div class="bg-blue-500 p-2 rounded-lg group-hover:bg-blue-600 transition-colors flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-medium text-gray-900 truncate">{{ $branch->name }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $branch->address }}</p>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex gap-2 pt-3">
                            <a href="{{ route('branches.edit', ['id' => $branch->id]) }}" 
                               class="flex-1 bg-azul-sigep text-white px-3 py-2 rounded text-center text-sm hover:bg-amarillo-sigep-hover transition duration-150">
                                Editar
                            </a>
                            <button onclick="confirm('¿Seguro que deseas eliminar esta sucursal?') || event.stopImmediatePropagation()" 
                                    wire:click="deleteBranch({{ $branch->id }})" 
                                    class="flex-1 bg-rojo text-white px-3 py-2 rounded text-sm hover:bg-rojo-hover transition duration-150">
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <p class="mt-4 text-gray-500 italic">No hay sucursales disponibles.</p>
        </div>
    @endif
</div>