<div>
    <div class="container mx-auto p-4">
        
        <div class="bg-white rounded-lg shadow-md p-6">    
        <h1 class="text-2xl font-bold text-gray-800 ">Registros de Asistencia</h1>
        <br>
        
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
                
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <div class="relative w-full sm:w-96">
                <input type="text" wire:click="applySearch" wire:keydown.enter="applySearch"  wire:model.debounce.300ms="search" placeholder="Buscar por nombre, apellido o email..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button wire:click="resetAllFilters" class="flex items-center gap-1 px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors border border-gray-300" title="Eliminar filtros de búsqueda">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Limpiar
                </button>

                <select wire:model="perPage" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <div class="flex items-center gap-2 w-full">
                <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Filtrar por fecha:</label>
                <input type="date" wire:model.live="selectedDate" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button wire:click="resetDateFilter" class="flex items-center gap-1 px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors border border-gray-300"  title="Restablecer fecha a hoy">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Hoy
                </button>
            </div>
        </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Empleado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <!--Agregando filtro-->
                                <select wire:model.live="attendanceFilter" class="text-xs border border-gray-300 rounded px-2 py-1">
                                    <option value="" wire:click='resetAllFilters'>Todos los estados</option>
                                    <option value="on_time">A tiempo</option>
                                    <option value="late">Retraso</option>
                                    <option value="absent">Ausente</option>
                                </select>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Informacion de Asistencia
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($employees as $employee)
                            @php
                                $attendance = $this->getAttendanceStatus($employee);
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $this->getFullName($employee) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500">
                                        {{ $this->getEmail($employee) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($attendance['status'] === 'registered')
                                        <div class="flex items-center gap-2">
                                            @if($attendance['status_type'] === 'on_time')
                                                <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full" title="A tiempo">
                                                    A tiempo
                                                </span>
                                            @elseif($attendance['status_type'] === 'late')
                                                <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full" title="Retraso">
                                                    Retraso
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full" title="Ausente">
                                                    Ausente
                                                </span>
                                            @endif
                                            <a href="{{ route('attendance.edit', ['attendanceId' => $attendance['attendance_id']]) }}" class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm hover:bg-blue-200" title="Editar">Editar</a>
                                        </div>
                                    @else
                                        <button class="px-2 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-600" wire:click="redirigirRegistro({{ $employee->id }})" title="Registrar Asistencia">Registrar Asistencia</button>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                        <button wire:click="redirigirInfoAsistencias({{ $employee->id }})" class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700 transition-colors">
                                            Ver mas
                                        </button>
                                </td>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center">
                                    <div class="text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                        <p class="mt-2 text-sm font-medium">No se encontraron empleados</p>
                                        <p class="text-xs mt-1">Intenta ajustar los filtros de búsqueda</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</div>