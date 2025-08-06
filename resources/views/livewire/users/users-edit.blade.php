<div class="p-6 max-w-xl mx-auto bg-white rounded-lg shadow space-y-4">
    <h2 class="text-2xl font-semibold text-gray-800">Editar usuario</h2>

    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="updateUser" enctype="multipart/form-data" class="space-y-4">
        <label class="block text-sm font-medium text-gray-700">Nombre completo</label>
        <input type="text" wire:model="name" placeholder="Nombre completo del usuario" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">

        <label class="block text-sm font-medium text-gray-700">Correo electrónico</label>
        <input type="email" wire:model="email" placeholder="correo@ejemplo.com" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">

        @if ($profile_image)
            <br><b>Imagen nueva seleccionada:</b>
            <img src="{{ $profile_image->temporaryUrl() }}" class="w-36 mt-2 rounded shadow border">
            <div class="flex items-center space-x-2 mt-2">
                <button type="button" wire:click="removeImage" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                    Eliminar imagen
                </button>
            </div>
            @elseif ($profile_image)
            <br><b>Imagen de perfil actual:</b>
            <img src="{{ Storage::url($original_profile_image) }}" alt="Perfil" class="w-36 mt-2 rounded-full shadow border">
                <button type="button" wire:click="removeImage" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                    Eliminar imagen
                </button>
            @else
                <span class="text-gray-500">Sin imagen de perfil</span><br>
            @endif
        <br>
        <div class="relative w-full">
            <input type="file" id="fileInput" wire:model="profile_image" accept="image/*" class="hidden">

            <div class="flex items-center space-x-3">
                <label for="fileInput" class="cursor-pointer flex items-center bg-gray-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                    </svg>
                    <span>Seleccionar imagen de perfil</span>
                </label>

                <span class="text-sm text-gray-600 truncate max-w-xs">
                    @if ($profile_image)
                        {{ $profile_image->getClientOriginalName() }}
                    @else
                        No hay archivo seleccionado
                    @endif
                </span>
            </div>
        </div>

        <div class="flex justify-center space-x-4">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition duration-150">
                Actualizar Usuario
            </button>

            <button type="button" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition duration-150" wire:click="returnIndex">
                Cancelar
            </button>
        </div>
    </form>
</div>