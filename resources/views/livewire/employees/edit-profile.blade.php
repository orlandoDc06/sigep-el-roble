<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-t-lg p-6 text-white shadow-lg">
            <div class="flex items-center space-x-4">
                <div class="bg-white bg-opacity-20 p-3 rounded-full">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Editar Información</h1>
                    <p class="text-blue-100 mt-1">Actualiza los datos del empleado</p>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-b-lg shadow-lg">
            @if (session()->has('message'))
                <div class="mx-6 pt-6">
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-800 font-medium">{{ session('message') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form wire:submit.prevent="update" class="p-6 space-y-8">

                <!-- Información Personal -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">Información Personal</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                            <input type="text" wire:model="first_name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200"/>
                            @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Apellido *</label>
                            <input type="text" wire:model="last_name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200"/>
                            @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Correo *</label>
                            <input type="email" wire:model="email"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200"/>
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input type="text" wire:model="phone"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200"/>
                            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Dirección</label>
                            <input type="text" wire:model="address"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200"/>
                            @error('address') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Fecha de nacimiento</label>
                            <input type="date" wire:model="birth_date"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200"/>
                            @error('birth_date') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Género</label>
                            <select wire:model="gender"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <option value="">Seleccionar</option>
                                <option value="male">Masculino</option>
                                <option value="female">Femenino</option>
                                <option value="other">Otro</option>
                            </select>
                            @error('gender') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Estado civil</label>
                            <select wire:model="marital_status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <option value="">Seleccionar</option>
                                <option value="single">Soltero/a</option>
                                <option value="married">Casado/a</option>
                                <option value="divorced">Divorciado/a</option>
                                <option value="widowed">Viudo/a</option>
                            </select>
                            @error('marital_status') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <!-- Fotografía -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center mb-6">
                        <div class="bg-orange-100 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">Fotografía</h3>
                    </div>

                    <input type="file" wire:model="photoFile"
                        class="w-full px-3 py-2 border-2 border-dashed border-gray-300 rounded-lg hover:border-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
                        accept="image/*">
                    @error('photoFile') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror

                    @if ($photoFile)
                        <img src="{{ $photoFile->temporaryUrl() }}" class="h-24 w-24 mt-4 object-cover rounded-lg border">
                    @elseif($photo_path)
                        <img src="{{ Storage::url($photo_path) }}" class="h-24 w-24 mt-4 object-cover rounded-lg border">
                    @endif
                </div>

                <!-- Contraseña -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center mb-6">
                        <div class="bg-indigo-100 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">Cambiar Contraseña</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Nueva contraseña</label>
                            <input type="password" wire:model="password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200"/>
                            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Confirmar contraseña</label>
                            <input type="password" wire:model="password_confirmation"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200"/>
                        </div>
                    </div>

                    <div class="mt-4 p-4 bg-blue-50 border-l-4 border-blue-400 rounded-r-lg">
                        <p class="text-sm text-blue-800">
                            <strong>Nota:</strong> Si no deseas cambiar la contraseña, deja estos campos vacíos.
                        </p>
                    </div>
                </div>

                <!-- Botón -->
                <div class="flex justify-end pt-6 border-t border-gray-200">
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
