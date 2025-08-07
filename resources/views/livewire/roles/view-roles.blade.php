<div class="p-4">
    @if (session()->has('success'))
        <div class="p-4 mb-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-4">
        <input type="text" wire:model.live="search" placeholder="Buscar rol..."
            class="px-3 py-2 border rounded w-1/3" />
        <button
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            onclick="window.location.href='{{ route('admin.roles.create') }}'">
            Nuevo Rol
        </button>
    </div>

    <table class="min-w-full bg-white border rounded shadow">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="p-2 border-b">Nombre</th>
                <th class="p-2 border-b">Permisos</th>
                <th class="p-2 border-b">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($roles as $role)
                <tr class="hover:bg-gray-50">
                    <td class="p-2 border-b">{{ $role->name }}</td>
                    <td class="p-2 border-b">
                        <button class="text-blue-600 hover:underline" wire:click="showPermissions({{ $role->id }})">
                            Ver permisos
                        </button>
                    </td>
                    <td class="p-2 border-b flex space-x-2">
                        <button
                            class="text-green-600 hover:underline"
                            wire:click="editRole({{ $role->id }})">
                            Editar
                        </button>
                        <button
                            class="text-red-600 hover:underline"
                            wire:click="confirmDelete({{ $role->id }})">
                            Eliminar
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-8">
                        <div class="flex flex-col items-center justify-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.75 9.75h.008v.008H9.75V9.75zm0 4.5h.008v.008H9.75v-.008zm4.5-4.5h.008v.008H14.25V9.75zm0 4.5h.008v.008H14.25v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm">No se encontraron roles que coincidan con tu búsqueda.</p>
                        </div>
                    </td>
                </tr>

            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $roles->links() }}
    </div>

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
