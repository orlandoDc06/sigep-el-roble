<div>
    <div class="container mx-auto p-6">
        <div class="bg-white rounded-lg shadow-md p-6">

            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Mi Historial de Asistencia</h1>
                    <p class="text-gray-600">{{ $employee->first_name }} {{ $employee->last_name }}</p>
                </div>
                <div class="text-sm text-gray-500">
                    ID: {{ $employee->id }} | {{ $employee->user->email }}
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h2 class="text-lg font-semibold mb-4">Filtrar por fecha</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha específica:</label>
                        <input type="date" wire:model.live="searchDate" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Desde:</label>
                        <input type="date" wire:model.live="startDate" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hasta:</label>
                        <input type="date" wire:model.live="endDate" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <select wire:model="perPage" class="px-3 py-2 border border-gray-300 rounded-md">
                        <option value="10">10 registros</option>
                        <option value="15">15 registros</option>
                        <option value="25">25 registros</option>
                        <option value="50">50 registros</option>
                    </select>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg text-center border border-blue-200">
                    <p class="text-2xl font-bold text-blue-600">{{ $totalAttendances }}</p>
                    <p class="text-sm text-blue-800">Total registros</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg text-center border border-green-200">
                    <p class="text-2xl font-bold text-green-600">
                        {{ $attendances->whereNotNull('check_in_time')->count() }}
                    </p>
                    <p class="text-sm text-green-800">Asistencias</p>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg text-center border border-yellow-200">
                    <p class="text-2xl font-bold text-yellow-600">
                        {{ $attendances->filter(function($attendance) {
                            return $attendance->check_in_time && 
                                $attendance->shift &&
                                $attendance->check_in_time->format('H:i:s') > $attendance->shift->start_time;
                        })->count() }}
                    </p>
                    <p class="text-sm text-yellow-800">Llegadas Tardías</p>
                </div>
            </div>

            <!-- Tabla de asistencias -->
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                #
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Turno
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Horas Extras
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($attendances as $index => $attendance)
                            @php
                                $status = $this->getAttendanceStatus($attendance);
                                $numero = ($attendances->currentPage() - 1) * $attendances->perPage() + $loop->iteration;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    {{ $numero }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $attendance->check_in_time ? $attendance->check_in_time->format('d/m/Y') : 'N/A' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $attendance->check_in_time ? $attendance->check_in_time->translatedFormat('l') : '' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $attendance->shift->name ?? 'Sin turno' }}
                                    </div>
                                    @if($attendance->shift)
                                    <div class="text-sm text-gray-500">
                                        {{ $attendance->shift->start_time }} - {{ $attendance->shift->end_time }}
                                    </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $status['class'] }}">
                                        {{ $status['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">
                                        {{ $attendance->extra_hours_total }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center">
                                    <div class="text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="mt-2 text-sm font-medium">No se encontraron registros de asistencia</p>
                                        <p class="text-xs mt-1">Intenta ajustar los filtros de fecha</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
</div>