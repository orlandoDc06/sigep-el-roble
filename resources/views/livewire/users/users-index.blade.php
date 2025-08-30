<div class="p-8 space-y-8">
    <!-- Header Section -->
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Lista de Usuarios</h1>
                    <p class="text-gray-600 text-sm">Administra y gestiona todos los usuarios del sistema</p>
                </div>
            </div>
            <a href="{{ route('users.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                </svg>
                <span>Crear Usuario</span>
            </a>
        </div>

        <!-- Search and Filters -->
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar usuarios</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            wire:model.defer="search" 
                            wire:keydown.enter="applySearch" 
                            placeholder="Buscar por nombre o email..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                     
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por rol</label>
                    <select wire:model="filterRole" wire:change="loadUsers" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">Todos los roles</option>
                        <option value="Administrador">Administrador</option>
                        <option value="Supervisor">Supervisor</option>
                        <option value="Empleado">Empleado</option>
                    </select>
                </div>
                
                <div class="flex items-end space-x-2">
                    <button wire:click="applySearch" 
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        Buscar
                    </button>
                    <button wire:click="resetSearch" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        Limpiar
                    </button>
                </div>
            </div>
            
            <!-- Stats Info -->
            <div class="mt-4 flex items-center justify-between text-sm text-gray-600">
                <div>
                    Filtro actual: <span class="font-medium">{{ $filterRole === 'all' ? 'Todos los roles' : $filterRole }}</span>
                </div>
                <div>
                    Total usuarios: <span class="font-medium">{{ $users->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Grid -->
    @if($users->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($users as $user)
                
            @endforeach
        </div>

        <!-- Mobile Table View (Hidden by default, can be toggled) -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden lg:hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Vista de tabla</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            @if($user->profile_image_path)
                                                <img src="{{ asset('storage/' . $user->profile_image_path) }}" 
                                                     alt="Foto de perfil" 
                                                     class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php $userRole = $this->getUserRole($user); @endphp
                                    @if($userRole)
                                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                            @if($userRole === 'Administrador') bg-red-100 text-red-800
                                            @elseif($userRole === 'Supervisor') bg-green-100 text-green-800
                                            @elseif($userRole === 'Empleado') bg-blue-100 text-blue-800
                                            @else bg-purple-100 text-purple-800
                                            @endif">
                                            {{ $userRole }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Sin rol</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->is_active)
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Activo</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">Inactivo</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <!-- Actions (same as above but condensed) -->
                                    @can('editar usuarios')
                                        @if($userRole === 'Empleado')
                                            @if($user->employee)
                                                <a href="{{ route('employees.edit-live', $user->employee->id) }}" class="text-yellow-600 hover:text-yellow-900">Editar</a>
                                            @else
                                                <a href="#" class="text-yellow-600 hover:text-yellow-900">Editar</a>
                                            @endif
                                        @else
                                            <button wire:click.prevent="editUser({{ $user->id }})" class="text-yellow-600 hover:text-yellow-900">Editar</button>
                                        @endif
                                    @endcan

                                    @can('editar usuarios')
                                        @if(!$user->is_active)
                                            <button wire:click="confirmActivation({{ $user->id }})" class="text-green-600 hover:text-green-900">Activar</button>
                                        @endif
                                    @endcan

                                    @can('estado usuarios')
                                        @if($user->isSelf)
                                            <span class="text-gray-400 cursor-not-allowed">Estado</span>
                                        @elseif($user->is_active)
                                            <button wire:click.prevent="editStatus({{ $user->id }})" class="text-blue-600 hover:text-blue-900">Estado</button>
                                        @endif
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.121M9 12a3 3 0 106 0 3 3 0 00-6 0m8 8v-1a3 3 0 00-3-3H9a3 3 0 00-3 3v1"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No hay usuarios disponibles</h3>
            <p class="mt-2 text-gray-500">No se encontraron usuarios con los filtros aplicados.</p>
            <div class="mt-6">
                <button wire:click="resetSearch" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    Limpiar filtros
                </button>
            </div>
        </div>
    @endif

    {{-- Modal de activación --}}
    @if($confirmingActivation)
        @php $userToActivateData = $users->firstWhere('id', $userToActivate); @endphp
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-xl p-6 w-96 mx-4">
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 p-2 rounded-full mr-3">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Activar Usuario</h3>
                </div>
                <p class="mb-6 text-gray-600">
                    ¿Seguro que deseas activar al usuario <strong>{{ $userToActivateData->name ?? '' }}</strong>? 
                </p>
                <div class="flex justify-end space-x-3">
                    <button wire:click="$set('confirmingActivation', false)" 
                            class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-200">
                        Cancelar
                    </button>
                    <button wire:click="activateUser" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        Confirmar Activación
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>