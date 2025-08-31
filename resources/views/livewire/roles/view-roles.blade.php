<div class="p-4 space-y-4">
    @if (session()->has('success'))
        <div class="p-4 mb-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <h1 class="text-gray text-2xl font-bold">Lista de roles</h1>

    <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center justify-between">
        <input type="text" wire:model.live="search" placeholder="Buscar rol..."
            class="border border-gray-300 rounded px-3 py-2 w-full sm:w-auto" />
        <button
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
            onclick="window.location.href='{{ route('admin.roles.create') }}'">
            Nuevo Rol
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse ($roles as $role)
                <div class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-200">
                    <!-- Cabecera con icono y nombre del rol -->
                    <div class="flex items-start space-x-3 mb-4">
                        <div class="bg-blue-500 p-2 rounded-lg group-hover:bg-blue-600 transition-colors flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-medium text-gray-900 truncate">{{ $role->name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $role->permissions->count() }} 
                                {{ $role->permissions->count() === 1 ? 'permiso' : 'permisos' }}
                            </p>
                        </div>
                    </div>

                    <!-- Información de permisos -->
                    <div class="mb-4">
                        <button class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center" 
                                wire:click="showPermissions({{ $role->id }})">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                            Ver permisos
                        </button>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex gap-2 pt-3">
                        <button
                            class="flex-1 bg-blue-600 text-white px-3 py-2 rounded text-center text-sm hover:bg-blue-700 transition duration-150"
                            wire:click="editRole({{ $role->id }})">
                            Editar
                        </button>
                        <button
                            class="flex-1 bg-red-600 text-white px-3 py-2 rounded text-sm hover:bg-red-700 transition duration-150"
                            wire:click="confirmDelete({{ $role->id }})">
                            Eliminar
                        </button>
                    </div>
                </div>
            @empty
        <div class="text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9.75 9.75h.008v.008H9.75V9.75zm0 4.5h.008v.008H9.75v-.008zm4.5-4.5h.008v.008H14.25V9.75zm0 4.5h.008v.008H14.25v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="mt-4 text-gray-600 italic">No se encontraron roles que coincidan con tu búsqueda.</p>
        </div>
    @endforelse

    <div class="mt-4">
        {{ $roles->links() }}
    </div>

    <!-- Modal de confirmación de eliminación -->
    <div x-data="{ open: @entangle('confirmingRoleDeletion') }">
        <div x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-6 rounded shadow-xl max-w-md w-full">
                <h2 class="text-lg font-bold mb-4">¿Eliminar rol?</h2>
                <p class="text-gray-700 mb-4">¿Estás seguro de que deseas eliminar este rol? Esta acción no se puede deshacer.</p>
                <div class="flex justify-end space-x-2">
                    <button @click="open = false" class="bg-gray-300 px-4 py-2 rounded">Cancelar</button>
                    <button wire:click="deleteConfirmed" class="bg-red-600 text-white px-4 py-2 rounded">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de permisos -->
    <div x-data="{ open: @entangle('showModal') }">
        <div x-show="open"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-6 rounded shadow-xl max-w-lg w-full">
                <h2 class="text-lg font-bold mb-4">Permisos del rol</h2>

                <ul class="list-disc list-inside text-sm">
                    @if ($selectedRoleId)
                        @php
                            $role = \Spatie\Permission\Models\Role::find($selectedRoleId);
                            $permissions = $role?->permissions->pluck('name') ?? collect();
                        @endphp

                        @forelse ($permissions as $permission)
                            <li>{{ $permission }}</li>
                        @empty
                            <li class="text-gray-500">Este rol no tiene permisos asignados.</li>
                        @endforelse
                    @endif
                </ul>

                <div class="mt-4 text-right">
                    <button @click="open = false" class="bg-gray-300 px-3 py-1 rounded hover:bg-gray-400">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>