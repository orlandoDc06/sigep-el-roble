<div class="p-6 max-w-xl mx-auto bg-white rounded-lg shadow space-y-4">
    <h2 class="text-2xl font-semibold text-gray">Editar sucursal</h2>

    @if (session()->has('success'))
        <div class="bg-green-600 text-white px-4 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="updateBranch" enctype="multipart/form-data" class="space-y-4">
        <label class="block text-sm font-medium text-gray">Nombre de la sucursal</label>
        <input type="text" wire:model="name" placeholder="Nombre de la sucursal" class="w-full border border-gray rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">

        <label class="block text-sm font-medium text-gray">Dirección de la sucursal</label>
        <input type="text" wire:model="address" placeholder="Dirección de la sucursal" class="w-full border border-gray rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">

        @if ($image_path)
            <br><b>Imagen nueva seleccionada:</b>
            <img src="{{ $image_path->temporaryUrl() }}" class="w-36 mt-2 rounded shadow border">
            <div class="flex items-center space-x-2 mt-2">
                <button type="button" wire:click="removeImage" class="bg-rojo text-white px-3 py-1 rounded hover:bg-rojo-hover">
                    Eliminar imagen
                </button>
            </div>
            @elseif ($original_image_path)
            <br><b>Imagen actual:</b>
            <img src="{{ Storage::url($original_image_path) }}" alt="Sucursal" class="w-36 mt-2 rounded shadow border">
                <button type="button" wire:click="removeImage" class="bg-rojo text-white px-3 py-1 rounded hover:bg-rojo-hover">
                    Eliminar imagen
                </button>
            @else
                <span class="text-gray">Sin imagen</span><br>
            @endif
        <br>
        <div class="relative w-full">
            <input type="file" id="fileInput" wire:model="image_path" class="hidden">

            <div class="flex items-center space-x-3">
                <label for="fileInput" class="cursor-pointer flex items-center bg-gray text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                    </svg>
                    <span>Seleccionar nueva imagen</span>
                </label>

                <span class="text-sm text-gray truncate max-w-xs">
                    @if ($image_path)
                        {{ $image_path->getClientOriginalName() }}
                    @else
                        No hay archivo seleccionado
                    @endif
                </span>
            </div>
        </div>

        <div class="flex justify-center space-x-4 ">
            <button type="submit" class="bg-green-600 hover:bg-verde-sigep-hover transition-colors cursor-pointer text-white px-4 py-2 rounded">
                Actualizar
            </button>

            <button type="button" class="bg-rojo text-white px-4 py-2 rounded hover:bg-rojo-hover transition duration-150" wire:click="returnIndex">
                Cancelar
            </button>
        </div>
    </form>
</div>
