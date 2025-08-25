<div class="container mx-auto p-6">
    <div class="max-w-6xl mx-auto bg-white rounded-lg shadow-md p-6">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Historial de Asistencia</h1>
                <p class="text-gray-600">{{ $employee->first_name }} {{ $employee->last_name }}</p>
            </div>
            <a href="{{ route('attendances.index') }}" class="text-blue-600 hover:text-blue-800">
                ← Volver al listado
            </a>
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
                <button wire:click="resetFilters" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                    Limpiar filtros
                </button>
                <select wire:model="perPage" class="px-3 py-2 border border-gray-300 rounded-md">
                    <option value="10">10 por página</option>
                    <option value="25">25 por página</option>
                    <option value="50">50 por página</option>
                </select>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $totalAttendances }}</p>
                <p class="text-sm text-blue-800">Total de registros</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg text-center">
                <p class="text-2xl font-bold text-green-600">
                    {{ $attendances->where('check_in_time', '!=', null)->count() }}
                </p>
                <p class="text-sm text-green-800">Asistencias registradas</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg text-center">
                <p class="text-2xl font-bold text-gray-600">
                    {{ $employee->hire_date ? Carbon\Carbon::parse($employee->hire_date)->diffInDays(now()) : 'N/A' }}
                </p>
                <p class="text-sm text-gray-800">Días en la empresa</p>
            </div>
        </div>

        <!-- Tabla de asistencias -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fecha
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Hora de entrada
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Turno
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tipo
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($attendances as $attendance)
                        @php
                            $status = $this->getAttendanceStatus($attendance);
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $attendance->check_in_time ? $attendance->check_in_time->format('d/m/Y') : 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i:s') : 'No registrada' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $attendance->shift->name ?? 'Sin turno' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $status['class'] }}">
                                    {{ $status['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">
                                    {{ $attendance->is_manual_entry ? 'Manual' : 'Automático' }}
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