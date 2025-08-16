<div class="p-4 space-y-4">
    <h1 class="text-2xl font-bold">Lista de usuarios</h1>
    <a href="{{ route('users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Crear usuario</a>
    <br><br>

    {{-- Barra de búsqueda --}}
    <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
        <input 
            type="text" 
            wire:model.defer="search" 
            wire:keydown.enter="applySearch" 
            placeholder="Buscar..." 
            class="border border-gray-300 rounded px-3 py-2 w-full sm:w-auto"
        >
        <button wire:click="applySearch" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Buscar</button>
        <button wire:click="resetSearch" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Eliminar filtro</button>
       
        {{-- Select con wire:change para filtro automático --}}
        <select wire:model="filterRole" wire:change="loadUsers" class="border border-gray-300 rounded px-3 py-2">
            <option value="all">Todos los roles</option>
            <option value="Administrador">Administrador</option>
            <option value="Supervisor">Supervisor</option>
            <option value="Empleado">Empleado</option>
        </select>
    </div>

    <hr class="border-gray-300">

    {{-- Debug temporal - puedes eliminar después --}}
    <div class="text-xs text-gray-500">
        Filtro actual: {{ $filterRole }} | Total usuarios: {{ $users->count() }}
    </div>

    @if($users->count())
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-sm">
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
                                @php
                                    $userRole = $this->getUserRole($user);
                                @endphp

                                @if($userRole)
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        @if($userRole === 'Administrador')
                                            bg-red-100 text-red-800
                                        @elseif($userRole === 'Supervisor')
                                            bg-green-100 text-green-800
                                        @elseif($userRole === 'Empleado')
                                            bg-blue-100 text-blue-800
                                        @else
                                            bg-purple-100 text-purple-800
                                        @endif
                                    ">
                                        {{ $userRole }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        Sin rol asignado
                                    </span>
                                @endif
                            </td>
                            <td class="border px-4 py-2">
                                @if ($user->profile_image_path)
                                    <img src="{{ Storage::url($user->profile_image_path) }}" alt="Perfil" class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                                        <span class="text-gray-600 text-xs">Sin foto</span>
                                    </div>
                                @endif
                            </td>
                            <td class="border px-4 py-2">
                                @if ($user->is_active)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Activo</span>
                                @else
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Inactivo</span>
                                @endif
                            </td>
                            <td class="border px-4 py-2 space-x-2">
                                {{-- Botón Editar --}}
                                @can('editar usuarios')
                                    <a href="#" wire:click.prevent="editUser({{ $user->id }})" 
                                       class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                        Editar
                                    </a>
                                @endcan

                                {{-- Boton Activar: Solo para usuarios INACTIVOS --}}
                                @can('editar usuarios')
                                    @if (!$user->is_active)
                                        <button onclick="confirm('¿Seguro que deseas activar este usuario?') || event.stopImmediatePropagation()" 
                                                wire:click="toggleUserStatus({{ $user->id }})" 
                                                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                            Activar
                                        </button>
                                    @endif
                                @endcan

                                {{-- Botón Estado: SOLO para usuarios ACTIVOS (o propio usuario deshabilitado) --}}
                                @can('estado usuarios')
                                    @if ($user->isSelf)
                                        {{-- Propio usuario: siempre deshabilitado --}}
                                        <a href="#" 
                                           onclick="alert('⚠ No puedes modificar tu propio estado.'); event.preventDefault();" 
                                           class="cursor-not-allowed opacity-50 bg-gray-400 px-3 py-1 rounded text-white">
                                            Estado
                                        </a>
                                    @elseif ($user->is_active)
                                        {{-- Usuario ACTIVO: mostrar botón Estado para desactivar --}}
                                        <a href="#" wire:click.prevent="editStatus({{ $user->id }})" 
                                           class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                            Estado
                                        </a>
                                    @endif
                                    {{-- Usuario INACTIVO: NO mostrar botón Estado (ya tiene Activar) --}}
                                @endcan

                                {{-- Botón Eliminar --}}
                                @can('eliminar usuarios')
                                    
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
</div>