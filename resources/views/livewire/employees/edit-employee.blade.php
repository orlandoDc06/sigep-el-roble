<div class="p-6 bg-white rounded shadow-md max-w-3xl mx-auto">
    @if (session()->has('message'))
        <div class="mb-4 text-green-700 bg-green-100 p-3 rounded">
            {{ session('message') }}
        </div>
    @endif

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
                    <h1 class="text-3xl font-bold">Editar Empleado</h1>
                    <p class="text-blue-100 mt-1">Actualiza la información del empleado</p>
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
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                            <input type="text" wire:model="first_name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200" />
                            @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Apellido *</label>
                            <input type="text" wire:model="last_name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200" />
                            @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">DUI *</label>
                            <input type="text" wire:model="dui" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200" />
                            @error('dui') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Correo electrónico *</label>
                            <input type="email" wire:model="email" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200" />
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input type="text" wire:model="phone" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200" />
                            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Fecha de nacimiento</label>
                            <input type="date" wire:model="birth_date" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200" />
                            @error('birth_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Género</label>
                            <select wire:model="gender" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <option value="">Seleccionar</option>
                                <option value="male">Masculino</option>
                                <option value="female">Femenino</option>
                                <option value="other">Otro</option>
                            </select>
                            @error('gender') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Estado civil</label>
                            <select wire:model="marital_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <option value="">Seleccionar</option>
                                <option value="single">Soltero/a</option>
                                <option value="married">Casado/a</option>
                                <option value="divorced">Divorciado/a</option>
                                <option value="widowed">Viudo/a</option>
                            </select>
                            @error('marital_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2 lg:col-span-3 space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Dirección</label>
                            <input type="text" wire:model="address" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200" />
                            @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Información Laboral -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-100 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">Información Laboral</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Sucursal *</label>
                            <select wire:model="branch_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <option value="">Seleccionar</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            @error('branch_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Tipo de contrato *</label>
                            <select wire:model="contract_type_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <option value="">Seleccionar</option>
                                @foreach ($contractTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @error('contract_type_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Rol del sistema</label>
                            <select wire:model="selectedRoles" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <option value="">Seleccionar</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                            @error('selectedRoles') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Fecha de contratación *</label>
                            <input type="date" wire:model="hire_date" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200" />
                            @error('hire_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Fecha de terminación</label>
                            <input type="date" wire:model="termination_date" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200" />
                            @error('termination_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Estado *</label>
                            <select wire:model="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <option value="">Seleccionar</option>
                                <option value="active">Activo</option>
                                <option value="inactive">Inactivo</option>
                                <option value="suspended">Suspendido</option>
                            </select>
                            @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Gestión de Turnos -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center mb-6">
                        <div class="bg-purple-100 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">Gestión de Turnos</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Turno Principal -->
                        <div class="border border-gray-200 rounded-lg p-5 bg-white shadow-sm">
                            <div class="flex items-center mb-4">
                                <div class="bg-blue-500 w-3 h-3 rounded-full mr-2"></div>
                                <h4 class="font-semibold text-gray-800">Turno Principal</h4>
                                <span class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">Requerido</span>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Seleccionar turno *</label>
                                    <select wire:model="shift1_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                        <option value="">Seleccionar turno</option>
                                        @foreach ($shifts as $shift)
                                            <option value="{{ $shift->id }}">
                                                {{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('shift1_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de inicio *</label>
                                    <input type="date" wire:model="shift1_start_date" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                    @error('shift1_start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Turno Secundario -->
                        <div class="border border-gray-200 rounded-lg p-5 bg-white shadow-sm">
                            <div class="flex items-center mb-4">
                                <div class="bg-green-500 w-3 h-3 rounded-full mr-2"></div>
                                <h4 class="font-semibold text-gray-800">Turno Secundario</h4>
                                <span class="ml-2 text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Opcional</span>
                                @if($shift2_id)
                                @endif
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Seleccionar turno</label>
                                    <select wire:model.live="shift2_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                        <option value="">Sin turno secundario</option>
                                        @foreach ($shifts as $shift)
                                            <option value="{{ $shift->id }}" 
                                                    @if($shift->id == $shift1_id) disabled class="text-gray-400" @endif>
                                                {{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})
                                                @if($shift->id == $shift1_id) - Ya seleccionado @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('shift2_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Fecha de inicio *
                                            @if(!$shift2_id) <span class="text-gray-400 text-xs">(selecciona un turno primero)</span> @endif
                                        </label>
                                        <input type="date" wire:model.live="shift2_start_date" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200 @if(!$shift2_id) bg-gray-100 cursor-not-allowed @endif"
                                               @if(!$shift2_id) disabled @endif>
                                        @error('shift2_start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Fecha de fin
                                            @if(!$shift2_id) <span class="text-gray-400 text-xs">(selecciona un turno primero)</span> @endif
                                        </label>
                                        <input type="date" wire:model.live="shift2_end_date" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200 @if(!$shift2_id) bg-gray-100 cursor-not-allowed @endif"
                                               @if(!$shift2_id) disabled @endif
                                               placeholder="Opcional - Permanente si vacío"
                                               @if($shift2_start_date) min="{{ $shift2_start_date }}" @endif>
                                        @error('shift2_end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
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
                        <h3 class="text-xl font-semibold text-gray-900">Fotografía del Empleado</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Subir nueva foto</label>
                                <input type="file" wire:model="photoFile" 
                                       class="w-full px-3 py-2 border-2 border-dashed border-gray-300 rounded-lg hover:border-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200" 
                                       accept="image/*">
                                @error('photoFile') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                <p class="text-xs text-gray-500 mt-1">Formatos: JPG, PNG, GIF. Máximo 2MB</p>
                            </div>

                            @if ($photoFile)
                                <div class="mt-4">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Vista previa:</p>
                                    <img src="{{ $photoFile->temporaryUrl() }}" 
                                         class="h-32 w-32 object-cover rounded-lg border-2 border-gray-200 shadow-sm">
                                </div>
                            @endif
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">O usar URL/ruta existente</label>
                                <input type="text" wire:model="photo_path" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                       placeholder="https://ejemplo.com/foto.jpg" />
                                @error('photo_path') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            @if ($photo_path && !$photoFile)
                                <div class="mt-4">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Foto actual:</p>
                                    <img src="{{ Storage::url($photo_path) }}" 
                                         alt="Foto del empleado" 
                                         class="h-32 w-32 object-cover rounded-lg border-2 border-gray-200 shadow-sm" />
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('employees.index') }}" 
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-200 font-medium">
                        <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                        </svg>
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 font-medium shadow-sm">
                        <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
