<div class="max-w-3xl mx-auto bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        </div>
    @endif

    <!-- Header del formulario -->
    <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-gray-200">
        <div class="bg-blue-500 p-3 rounded-lg">
            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
            </svg>
        </div>
        <div>
            <h3 class="text-2xl font-bold text-gray-900">
                {{ $editing ? 'Editar Empleado' : 'Nuevo Empleado' }}
            </h3>
            <p class="text-sm text-gray-500">
                {{ $editing ? 'Actualizar información del empleado' : 'Completar información del nuevo empleado' }}
            </p>
        </div>
    </div>

    <form wire:submit.prevent="{{ $editing ? 'update' : 'store' }}">
        <!-- Información Personal -->
        <div class="mb-8 ">
            <div class="flex items-center space-x-2 mb-4">
                <div class="bg-gray-100 p-2 rounded">
                    <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/>
                    </svg>
                </div>
                <h4 class="text-lg font-semibold text-gray-800">Información Personal</h4>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" wire:model.defer="first_name" 
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    @error('first_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Apellido</label>
                    <input type="text" wire:model.defer="last_name" 
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    @error('last_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">DUI</label>
                    <input type="text" wire:model.defer="dui" 
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    @error('dui') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" wire:model.defer="phone" 
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input type="text" wire:model.defer="address" 
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Fecha de nacimiento</label>
                    <input type="date" wire:model.defer="birth_date" 
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    @error('birth_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Género</label>
                    <select wire:model.defer="gender" 
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="">Seleccionar</option>
                        <option value="male">Masculino</option>
                        <option value="female">Femenino</option>
                        <option value="other">Otro</option>
                    </select>
                    @error('gender') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Estado civil</label>
                    <select wire:model.defer="marital_status" 
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="">Seleccionar</option>
                        <option value="single">Soltero/a</option>
                        <option value="married">Casado/a</option>
                        <option value="divorced">Divorciado/a</option>
                        <option value="widowed">Viudo/a</option>
                    </select>
                    @error('marital_status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Correo</label>
                    <input type="email" wire:model.defer="email" 
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Información Laboral -->
        <div class="mb-8 mt-4">
            <div class="flex items-center space-x-2 mb-4">
                <div class="bg-green-100 p-2 rounded">
                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm5 3a1 1 0 011-1h1a1 1 0 110 2h-1a1 1 0 01-1-1zm-3 4a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1z"/>
                    </svg>
                </div>
                <h4 class="text-lg font-semibold text-gray-800">Información Laboral</h4>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Sucursal</label>
                    <select wire:model.defer="branch_id" 
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="">Seleccionar</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('branch_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Tipo de contrato</label>
                    <select wire:model.defer="contract_type_id" 
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="">Seleccionar</option>
                        @foreach ($contractTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('contract_type_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Turno</label>
                    <select wire:model.defer="shift_id" 
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="">Seleccionar</option>
                        @foreach ($shifts as $shift)
                            <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                        @endforeach
                    </select>
                    @error('shift_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Rol</label>
                    <select wire:model.defer="selectedRoles" 
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="">Seleccionar</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                    @error('selectedRoles') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Fecha de contratación</label>
                    <input type="date" wire:model.defer="hire_date" 
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    @error('hire_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Estado</label>
                    <select wire:model.defer="status" 
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="">Seleccionar</option>
                        <option value="active">Activo</option>
                        <option value="inactive">Inactivo</option>
                        <option value="suspended">Suspendido</option>
                    </select>
                    @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Fecha de terminación</label>
                    <input type="date" wire:model.defer="termination_date" 
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    @error('termination_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Foto del empleado -->
        <div class="mb-8 mt-4">
            <div class="flex items-center space-x-2 mb-4">
                <div class="bg-purple-100 p-2 rounded">
                    <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h4 class="text-lg font-semibold text-gray-800">Foto del Empleado</h4>
            </div>
            
            <div class="space-y-4">
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Seleccionar foto</label>
                    <input type="file" wire:model="photoFile" 
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                           accept="image/*">
                    @error('photoFile') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                @if ($photoFile)
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border">
                        <img src="{{ $photoFile->temporaryUrl() }}" class="h-16 w-16 object-cover rounded-lg border">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Vista previa</p>
                            <p class="text-xs text-gray-500">Imagen seleccionada</p>
                        </div>
                    </div>
                @elseif ($photo_path)
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border">
                        <img src="{{ Storage::url($photo_path) }}" class="h-16 w-16 object-cover rounded-lg border">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Foto actual</p>
                            <p class="text-xs text-gray-500">Imagen guardada</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="centerflex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
            @if ($editing)
                <button type="button" wire:click="cancel" 
                        class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                    Cancelar
                </button>
            @endif
            
            <button type="submit" 
                    class="center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                <span>{{ $editing ? 'Actualizar Empleado' : 'Guardar Empleado' }}</span>
            </button>
        </div>
    </form>
</div>