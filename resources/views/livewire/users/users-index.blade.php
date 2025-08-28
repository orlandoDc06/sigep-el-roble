<div class="p-4 space-y-4">
    <h1 class="text-2xl font-bold">Lista de usuarios</h1>
    <a href="{{ route('users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Crear usuario</a>

    {{-- Barra de búsqueda --}}
    <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center mt-4">
        <input 
            type="text" 
            wire:model.defer="search" 
            wire:keydown.enter="applySearch" 
            placeholder="Buscar..." 
            class="border border-gray-300 rounded px-3 py-2 w-full sm:w-auto"
        >
        <button wire:click="applySearch" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Buscar</button>
        <button wire:click="resetSearch" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Eliminar filtro</button>

        {{-- Filtro por rol --}}
        <select wire:model="filterRole" wire:change="loadUsers" class="border border-gray-300 rounded px-3 py-2">
            <option value="all">Todos los roles</option>
            <option value="Administrador">Administrador</option>
            <option value="Supervisor">Supervisor</option>
            <option value="Empleado">Empleado</option>
        </select>
    </div>

    <hr class="border-gray-300 mt-2">

    {{-- Debug temporal --}}
    <div class="text-xs text-gray-500">
        Filtro actual: {{ $filterRole }} | Total usuarios: {{ $users->count() }}
    </div>

    @if($users->count())
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-sm mt-2">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left">Nombre</th>
                        <th class="border px-4 py-2 text-left">Email</th>
                        <th class="border px-4 py-2 text-left">Rol</th>
                        <th class="border px-4 py-2 text-left">Imagen de perfil</th>
                        <th class="border px-4 py-2 text-left">Estado</th>
                        <th class="border px-4 py-2 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2">{{ $user->name }}</td>
                            <td class="border px-4 py-2">{{ $user->email }}</td>
                            <td class="border px-4 py-2">
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
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Sin rol asignado</span>
                                @endif
                            </td>
                            <td class="border px-4 py-2">
                                @if($user->profile_image_path)
                                    <img src="{{ asset('storage/' . $user->profile_image_path) }}" alt="Foto de perfil" class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <span>Sin foto</span>
                                @endif
                            </td>
                            <td class="border px-4 py-2">
                                @if($user->is_active)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Activo</span>
                                @else
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Inactivo</span>
                                @endif
                            </td>
                            <td class="border px-4 py-2 space-x-2">
                                {{-- Botón Editar --}}
                                @can('editar usuarios')
                                    @if($userRole === 'Empleado')
                                        @if($user->employee)
                                            <a href="{{ route('employees.edit-live', $user->employee->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Editar</a>
                                        @else
                                            <a href="#" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Editar</a>
                                        @endif
                                    @else
                                        <a href="#" wire:click.prevent="editUser({{ $user->id }})" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Editar</a>
                                    @endif
                                @endcan

                                {{-- Botón Activar solo para inactivos --}}
                                @can('editar usuarios')
                                    @if(!$user->is_active)
                                        <button wire:click="confirmActivation({{ $user->id }})" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Activar</button>
                                    @endif
                                @endcan

                                {{-- Botón Estado para activos o propio usuario --}}
                                @can('estado usuarios')
                                    @if($user->isSelf)
                                        <a href="#" onclick="alert('⚠ No puedes modificar tu propio estado.'); event.preventDefault();" class="cursor-not-allowed opacity-50 bg-gray-400 px-3 py-1 rounded text-white">Estado</a>
                                    @elseif($user->is_active)
                                        <a href="#" wire:click.prevent="editStatus({{ $user->id }})" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Estado</a>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-600 italic">No hay usuarios disponibles con los filtros aplicados.</p>
    @endif

    {{-- Modal de activación --}}
    @if($confirmingActivation)
        @php $userToActivateData = $users->firstWhere('id', $userToActivate); @endphp
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                <h3 class="text-lg font-bold mb-4">Activar Usuario</h3>
                <p class="mb-4">
                    ¿Seguro que deseas activar al usuario <strong>{{ $userToActivateData->name ?? '' }}</strong>? Esta acción no se puede deshacer.
                </p>
                <div class="flex justify-end space-x-2">
                    <button wire:click="$set('confirmingActivation', false)" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancelar</button>
                    <button wire:click="activateUser" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Confirmar</button>
                </div>
            </div>
        </div>
    @endif
</div>
