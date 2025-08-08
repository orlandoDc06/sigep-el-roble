<div class="max-w-6xl mx-auto p-8 bg-white shadow-xl rounded-2xl space-y-8">

    <h2 class="text-3xl font-bold text-gray-900">
        {{ $editingRoleId ? 'Editar rol' : 'Crear nuevo rol' }}
    </h2>

    @if (session()->has('success'))
        <div class="p-4 bg-green-100 text-green-800 rounded-md border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    @error('selectedPermissions')
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror

    <form wire:submit.prevent="storeRole" class="space-y-8">

        {{-- Nombre del rol --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre del rol</label>
            <input type="text" wire:model.defer="roleName"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400"
                placeholder="Ej. administrador, RRHH" />
            @error('roleName')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Permisos dual listbox --}}
        <div class="grid md:grid-cols-2 gap-6">

            {{-- Permisos disponibles --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Permisos disponibles</label>
                <input type="text" placeholder="Buscar permisos..."
                    class="w-full mb-3 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-300 focus:border-indigo-500"
                    oninput="filterPermissions(this.value)" />

                <ul id="available-permissions"
                    class="h-64 overflow-y-auto bg-gray-50 border border-gray-300 rounded-lg p-2 space-y-2 divide-y">
                    @foreach ($permissions as $permission)
                        @if (!in_array($permission->id, $selectedPermissions))
                            <li class="permission-item flex justify-between items-center py-1">
                                <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                <div class="flex items-center space-x-2">
                                    <button type="button"
                                        wire:click.prevent="editPermission({{ $permission->id }})"
                                        class="text-indigo-600 hover:text-indigo-800 transition">
                                        ✏️
                                    </button>
                                    <button type="button"
                                        wire:click.prevent="confirmDeletePermission({{ $permission->id }})"
                                        class="text-red-600 hover:text-red-800 transition">
                                        🗑️
                                    </button>
                                    <button type="button"
                                        wire:click="addPermission({{ $permission->id }})"
                                        class="px-2 py-1 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-md hover:bg-indigo-200 transition">
                                        + Agregar
                                    </button>
                                </div>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>

            {{-- Permisos asignados --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Permisos asignados</label>
                <ul class="h-64 overflow-y-auto bg-gray-50 border border-gray-300 rounded-lg p-2 space-y-2 divide-y">
                    @foreach ($permissions->whereIn('id', $selectedPermissions) as $permission)
                        <li class="flex justify-between items-center py-1">
                            <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                            <button type="button"
                                wire:click="removePermission({{ $permission->id }})"
                                class="px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-md hover:bg-red-200 transition">
                                ✕ Quitar
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Crear nuevo permiso --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nuevo permiso</label>
            <div class="flex gap-2 mt-1">
                <input type="text" wire:model.defer="newPermissionName"
                    class="flex-1 px-3 py-2 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400"
                    placeholder="Ej. editar empleados" />
                <button type="button" wire:click="createPermission"
                    class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Guardar
                </button>
            </div>
            @error('newPermissionName')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Guardar --}}
        <div class="pt-6">
            <button type="submit"
                class="w-full md:w-auto px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                Guardar rol
            </button>
        </div>
    </form>

    {{-- MODAL DE CONFIRMACIÓN --}}
    <div x-data="{ show: @entangle('showDeleteModal') }"
        x-show="show"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        x-cloak>
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 space-y-4">
            <h2 class="text-xl font-semibold text-gray-900">¿Eliminar permiso?</h2>
            <p class="text-gray-600">¿Estás seguro de que deseas eliminar este permiso? Esta acción no se puede deshacer.</p>
            <div class="flex justify-end space-x-2 pt-4">
                <button @click="show = false"
                        wire:click="$set('showDeleteModal', false)"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg transition">
                    Cancelar
                </button>
                <button wire:click="deletePermission"
                        class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded-lg transition">
                    Eliminar
                </button>
            </div>
        </div>
    </div>

</div>

<script>
    function filterPermissions(query) {
        const cleanQuery = query.trim().toLowerCase();
        document.querySelectorAll('#available-permissions .permission-item').forEach(item => {
            const name = item.querySelector('span').innerText.toLowerCase();
            item.style.display = name.includes(cleanQuery) ? '' : 'none';
        });
    }
</script>
