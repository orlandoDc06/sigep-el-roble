<div class="p-4 space-y-4">
    {{-- Care about people's approval and you will be their prisoner. --}}
    <h1 class="text-2xl font-bold">Lista de usuarios</h1>
   
    <a href="" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Crear usuario</a>
    <br><br>
    <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
        <input type="text" wire:model.defer="search" wire:keydown.enter="applySearch" placeholder="Buscar..." class="border border-gray-300 rounded px-3 py-2 w-full sm:w-auto">
        <button wire:click="applySearch" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Buscar</button>
        <button wire:click="resetSearch" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Eliminar filtro</button>
    </div>
    <hr class="border-gray-300">
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
                                @can('editar usuarios')
                                    <a href="{{ route('users.edit', ['id' => $user->id]) }}" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Editar</a>
                                @endcan
                                
                                @can('editar usuarios')
                                    @if ($user->is_active)
                                    @else
                                        <button onclick="confirm('¿Seguro que deseas activar este usuario?') || event.stopImmediatePropagation()" wire:click="toggleUserStatus({{ $user->id }})" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Activar</button>
                                    @endif
                                @endcan
                                
                                @can('eliminar usuarios')
                                    <button onclick="confirm('¿Seguro que deseas eliminar este usuario? Esta acción no se puede deshacer.') || event.stopImmediatePropagation()" wire:click="deleteUser({{ $user->id }})" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Eliminar</button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-600 italic">No hay usuarios disponibles.</p>
    @endif
</div>