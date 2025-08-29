<div class="p-6 max-w-xl mx-auto bg-white rounded-lg shadow space-y-4">
    <h2 class="text-2xl font-semibold text-gray-800">Editar usuario</h2>

    {{-- Mensajes de éxito --}}
    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="updateUser" enctype="multipart/form-data" class="space-y-4">
        {{-- Nombre completo --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Nombre completo</label>
            <input type="text" wire:model="name" placeholder="Nombre completo del usuario"
                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Correo electrónico --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Correo electrónico</label>
            <input type="email" wire:model="email" placeholder="correo@ejemplo.com"
                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Preview de imagen --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Imagen de perfil</label>

            {{-- Preview de nueva imagen --}}
            @if ($profile_image)
                <div class="mt-2">
                    <b>Imagen nueva seleccionada:</b>
                    <img src="{{ $profile_image->temporaryUrl() }}" class="w-36 mt-2 rounded shadow border">
                    <div class="flex items-center space-x-2 mt-2">
                        <button type="button" wire:click="removeImage"
                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                            Eliminar imagen
                        </button>
                    </div>
                </div>
            @endif

            {{-- Preview de imagen existente en BD --}}
            @if ($profile_image_path && !$profile_image)
                <div class="mt-2">
                    <b>Imagen de perfil actual:</b>
                    <img src="{{ Storage::url($profile_image_path) }}" alt="Perfil"
                         class="w-36 mt-2 rounded shadow border">
                    <div class="flex items-center space-x-2 mt-2">
                        <button type="button" wire:click="removeImage"
                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                            Eliminar imagen
                        </button>
                    </div>
                </div>
            @endif

            {{-- Input para seleccionar nueva imagen --}}
            <div class="relative w-full mt-2">
                <input type="file" id="fileInput" wire:model="profile_image" accept="image/*" class="hidden">
                <label for="fileInput"
                       class="cursor-pointer flex items-center bg-gray-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Seleccionar imagen de perfil
                </label>

                <span class="text-sm text-gray-600 ml-2">
                    @if ($profile_image)
                        {{ $profile_image->getClientOriginalName() }}
                    @else
                        No hay archivo seleccionado
                    @endif
                </span>
            </div>
            @error('profile_image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Botones de acción --}}
        <div class="flex justify-center space-x-4 mt-4">
            <button type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition duration-150">
                Actualizar Usuario
            </button>

            <button type="button" wire:click="returnIndex"
                    class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition duration-150">
                Cancelar
            </button>
        </div>
    </form>
</div>
