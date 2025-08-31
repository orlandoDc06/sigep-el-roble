<div>
    <div class="container mx-auto p-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Días Festivos</h1>
                    <p class="text-gray-600">Gestión de días festivos</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2">
                    <a href="{{ route('special-days.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nuevo Día Festivo
                    </a>
                    <button wire:click="generateYearHolidays" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Generar {{ $yearFilter }}
                    </button>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buscar por nombre</label>
                        <input type="text" wire:model.live="search"  placeholder="Escribe para buscar..." class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Año</label>
                        <select wire:model.live="yearFilter" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mostrar</label>
                        <select wire:model.live="perPage" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="10">10 registros</option>
                            <option value="25">25 registros</option>
                            <option value="50">50 registros</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Tabla de días festivos -->
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nombre
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($specialDays as $day)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $day->name }}</div>
                                @if($day->description)
                                <div class="text-sm text-gray-500">{{ Str::limit($day->description, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $day->formatted_date }}</div>
                                <div class="text-sm text-gray-500">{{ $day->day_name }}</div>
                            </td>
                
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('special-days.edit', $day->id) }}" class="text-blue-600 hover:text-blue-800" title="Editar">
                                        Editar
                                    </a>
                                    <button onclick="confirm('¿Seguro que deseas eliminar este día festivo?') || event.stopImmediatePropagation()" wire:click="deleteSpecialDay({{ $day->id }})" class="text-red-600 hover:text-red-800" title="Eliminar">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                No se encontraron días festivos
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-6">
                {{ $specialDays->links() }}
            </div>
        </div>
    </div>
</div>