<div class="p-6 max-w-xl mx-auto bg-white rounded-lg shadow space-y-4">
    <h2 class="text-2xl font-semibold text-gray-800">
        {{ $is_editing ? 'Editar usuario' : 'Nuevo usuario administrativo' }}
    </h2>

    <form wire:submit.prevent="{{ $is_editing ? 'updateUser' : 'createUser' }}" enctype="multipart/form-data" class="space-y-4">
        
        {{-- Campo Nombre --}}
        <input type="text" wire:model="name" placeholder="Nombre completo del usuario" 
               class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('name') <span class="text-red-600 text-sm" style="color: red;">{{ $message }}</span> @enderror

        {{-- Campo Email --}}
        <input type="email" wire:model="email" placeholder="Correo electrónico" 
               class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('email') <span class="text-red-600 text-sm" style="color: red;">{{ $message }}</span> @enderror

        {{-- Campos de Contraseña (solo al crear) --}}
        @if(!$is_editing)
            <input type="password" wire:model="password" placeholder="Contraseña (mínimo 8 caracteres)" 
                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('password') <span class="text-red-600 text-sm" style="color: red;">{{ $message }}</span> @enderror

            <input type="password" wire:model="password_confirmation" placeholder="Confirmar contraseña" 
                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('password_confirmation') <span class="text-red-600 text-sm" style="color: red;">{{ $message }}</span> @enderror
        @endif

        {{-- Al editar, mostrar campos de contraseña opcionales --}}
        @if($is_editing)
            <input type="password" wire:model="password" placeholder="Nueva contraseña (dejar vacío si no desea cambiar)" 
                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('password') <span class="text-red-600 text-sm" style="color: red;">{{ $message }}</span> @enderror

            @if($password)
                <input type="password" wire:model="password_confirmation" placeholder="Confirmar nueva contraseña" 
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('password_confirmation') <span class="text-red-600 text-sm" style="color: red;">{{ $message }}</span> @enderror
            @endif
        @endif

        {{-- Campo Rol (solo informativo) --}}
        <div class="relative">
            <input type="text" value="Administrador" readonly
                   class="w-full border border-gray-300 rounded px-4 py-2 bg-gray-100 text-gray-600 cursor-not-allowed">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-500 -mt-2">Los usuarios creados aquí tendrán rol de Administrador por defecto</p>

        {{-- Preview de imagen actual o seleccionada --}}
        @if ($profile_image || $profile_image_path)
            @if($profile_image)
                <img src="{{ $profile_image->temporaryUrl() }}" class="w-24 h-24 mt-2 rounded-full object-cover shadow border">
            @elseif($profile_image_path)
                <img src="{{ Storage::url($profile_image_path) }}" class="w-24 h-24 mt-2 rounded-full object-cover shadow border">
            @endif
            
            <div class="flex items-center space-x-2 mt-2">
                <button type="button" wire:click="removeImage" 
                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition duration-150">
                    Eliminar imagen
                </button>
            </div>
        @endif

        {{-- Selector de imagen --}}
        <br>
        <div class="relative w-full">
            <input type="file" id="fileInput" wire:model="profile_image" accept="image/*" class="hidden">
            @error('profile_image') <span class="text-red-600 text-sm" style="color: red;">{{ $message }}</span> @enderror

            <div class="flex items-center space-x-3">
                <label for="fileInput" class="cursor-pointer flex items-center bg-gray-500 text-white px-4 py-2 rounded hover:bg-blue-500 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    <span>Seleccionar imagen de perfil</span>
                </label>

                <span class="text-sm text-gray-700 truncate max-w-xs">
                    @if ($profile_image)
                        {{ $profile_image->getClientOriginalName() }}
                    @else
                        No hay archivo seleccionado
                    @endif
                </span>
            </div>

            {{-- Loading indicator para subida de imagen --}}
            <div wire:loading wire:target="profile_image" class="text-sm text-blue-600 mt-1">
                <div class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Subiendo imagen...
                </div>
            </div>
        </div>

        {{-- Información adicional --}}
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm text-blue-700">
                    <p class="font-medium">Información importante:</p>
                    <ul class="mt-1 list-disc list-inside space-y-1 text-xs">
                        <li>Este usuario tendrá permisos de administrador</li>
                        <li>{{ $is_editing ? 'Solo se cambiará la contraseña si especifica una nueva' : 'Se creará con las credenciales proporcionadas' }}</li>
                        <li>Para crear empleados, use el módulo correspondiente</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Botones de acción --}}
        <div class="flex justify-center space-x-4">
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 transition-colors cursor-pointer text-white px-4 py-2 rounded flex items-center"
                    wire:loading.attr="disabled">
                <div wire:loading wire:target="{{ $is_editing ? 'updateUser' : 'createUser' }}" class="mr-2">
                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                {{ $is_editing ? 'Actualizar' : 'Guardar' }}
            </button>

            <button type="button" 
                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition duration-150" 
                    wire:click="returnIndex">
                Cancelar
            </button>
        </div>
    </form>
</div>