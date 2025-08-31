<div class="p-6 bg-white rounded shadow-md">
    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left">Foto</th>
                    <th class="px-4 py-2 text-left">Nombre Completo</th>
                    <th class="px-4 py-2 text-left">DUI</th>
                    <th class="px-4 py-2 text-left">Teléfono</th>
                    <th class="px-4 py-2 text-left">Sucursal</th>
                    <th class="px-4 py-2 text-left">Contrato</th>
                    <th class="px-4 py-2 text-left">Estado</th>
                    <th class="px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $employee)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">
                            @if ($employee->photo_path)
                                <img src="{{ asset('storage/' . $employee->photo_path) }}" alt="Foto" class="h-12 w-12 rounded-full object-cover border-2 border-gray-200">
                            @else
                                <div class="h-12 w-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                    {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <div class="font-medium text-gray-900">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                            <div class="text-sm text-gray-500">{{ $employee->user?->email ?? 'Sin email' }}</div>
                        </td>
                        <td class="px-4 py-2 font-mono text-sm">{{ $employee->dui }}</td>
                        <td class="px-4 py-2">{{ $employee->phone ?? 'N/A' }}</td>
                        <td class="px-4 py-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $employee->branch?->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-4 py-2">{{ $employee->contractType?->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">
                            @if($employee->status === 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Activo
                                </span>
                            @elseif($employee->status === 'inactive')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Inactivo
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Suspendido
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex items-center space-x-2">
                                <!-- Ver detalles -->
                                <button onclick="toggleDetails({{ $employee->id }})"
                                        class="text-gray-600 hover:text-gray-800 p-1 rounded hover:bg-gray-100"
                                        title="Ver detalles">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>

                                <!-- Editar -->
                                <a href="{{ route('employees.edit-live', $employee->id) }}"
                                   class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50"
                                   title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                <!-- Bono -->
                                @if ($employee->bonuses->isNotEmpty())
                                    <a href="{{ route('bonuses.index', ['bonus' => $employee->bonuses->first()->id]) }}"
                                       class="text-green-600 hover:text-green-800 p-1 rounded hover:bg-green-50"
                                       title="Ver bono">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                        </svg>
                                    </a>
                                @else
                                    <a href="{{ route('bonuses.create', ['employee' => $employee->id]) }}"
                                       class="text-green-600 hover:text-green-800 p-1 rounded hover:bg-green-50"
                                       title="Asignar bono">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                    </a>
                                @endif

                                <!-- Eliminar -->
                                <form action="{{ route('employees.destroy', $employee->id) }}" method="POST"
                                      onsubmit="return confirm('⚠️ ADVERTENCIA\n\nEstás a punto de eliminar permanentemente al empleado:\n{{ $employee->first_name }} {{ $employee->last_name }}\n\nEsta acción NO se puede deshacer y eliminará:\n• Todos los datos del empleado\n• Su usuario del sistema\n• Historial de asistencias\n• Información de nómina\n\n¿Estás completamente seguro?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800 p-1 rounded hover:bg-red-50"
                                            title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Fila de detalles expandible -->
                    <tr id="details-{{ $employee->id }}" class="hidden bg-gray-50">
                        <td colspan="8" class="px-4 py-4">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">Dirección:</span>
                                    <p class="text-gray-600">{{ $employee->address ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Fecha de nacimiento:</span>
                                    <p class="text-gray-600">{{ $employee->birth_date ? \Carbon\Carbon::parse($employee->birth_date)->format('d/m/Y') : 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Fecha de contratación:</span>
                                    <p class="text-gray-600">{{ $employee->hire_date ? \Carbon\Carbon::parse($employee->hire_date)->format('d/m/Y') : 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Fecha de baja:</span>
                                    <p class="text-gray-600">{{ $employee->termination_date ? \Carbon\Carbon::parse($employee->termination_date)->format('d/m/Y') : '-' }}</p>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Género:</span>
                                    <p class="text-gray-600 capitalize">{{ $employee->gender ?? '-' }}</p>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Estado civil:</span>
                                    <p class="text-gray-600 capitalize">{{ $employee->marital_status ?? '-' }}</p>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Turnos:</span>
                                    <p class="text-gray-600">
                                        @if($employee->shifts->isNotEmpty())
                                            @foreach($employee->shifts->take(2) as $shift)
                                                <span class="inline-block bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded mr-1 mb-1">
                                                    {{ $shift->name }}
                                                </span>
                                            @endforeach
                                        @else
                                            Sin turnos asignados
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Rol del sistema:</span>
                                    <p class="text-gray-600">{{ $employee->user?->roles?->first()?->name ?? 'Sin rol' }}</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p class="text-lg font-medium">No hay empleados registrados</p>
                                <p class="text-sm">Comienza agregando tu primer empleado</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $employees->links() }}
    </div>
</div>

<script>
function toggleDetails(employeeId) {
    const detailsRow = document.getElementById('details-' + employeeId);
    const isHidden = detailsRow.classList.contains('hidden');

    // Cerrar todas las demás filas de detalles
    document.querySelectorAll('[id^="details-"]').forEach(row => {
        if (row !== detailsRow) {
            row.classList.add('hidden');
        }
    });

    // Toggle la fila actual
    if (isHidden) {
        detailsRow.classList.remove('hidden');
    } else {
        detailsRow.classList.add('hidden');
    }
}
</script>
