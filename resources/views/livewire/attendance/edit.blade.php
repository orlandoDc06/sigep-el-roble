<div class="container mx-auto p-6">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Editar Asistencia</h1>
            <a href="{{ route('attendances.index') }}" class="text-blue-600 hover:text-blue-800">
                ← Volver al listado
            </a>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h2 class="text-lg font-semibold mb-2">Información del Empleado</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nombre completo:</p>
                    <p class="font-medium">{{ $employee->first_name }} {{ $employee->last_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email:</p>
                    <p class="font-medium">{{ $employee->user->email ?? 'No tiene email' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">ID:</p>
                    <p class="font-medium">{{ $employee->id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Fecha:</p>
                <div class="mb-4">
                    <div class="flex items-center gap-2">
                        <input type="date" 
                            wire:model="newDate" 
                            class="border border-gray-300 rounded-md px-3 py-2"
                            min="{{ $minDate }}"
                            max="{{ $maxDate }}">
                        <button type="button" 
                                wire:click="updateDate" 
                                class="p-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                aceptar
                        </button>
                        <button type="button" 
                                wire:click="toggleCalendar" 
                                class="p-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        </button>
                    </div>
                    @error('newDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                </div>
            </div>
        </div>
        <!-- Calendario-->
        <form wire:submit.prevent="updateAttendance">
            <!-- Tipo de asistencia -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Tipo de asistencia:</label>
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="radio" wire:model="attendanceType" value="on_time" class="mr-2">
                        <span class="text-green-600 font-medium">A tiempo</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" wire:model="attendanceType" value="late" class="mr-2">
                        <span class="text-yellow-600 font-medium">Retraso</span>
                    </label>
                </div>
                @error('attendanceType') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Turno -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Turno asignado:</label>
                <div class="p-3 bg-gray-100 rounded-md">
                    @if($employee->getCurrentShift())
                        <p class="font-medium text-gray-800">{{ $employee->getCurrentShift()->name }}</p>
                        <p class="text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($employee->getCurrentShift()->start_time)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($employee->getCurrentShift()->end_time)->format('H:i') }}
                        </p>
                    @else
                        <p class="text-red-500 font-medium">No tiene turno asignado</p>
                    @endif
                </div>
            </div>
            
            <!-- Campo oculto para el turno -->
            <input type="hidden" wire:model="selectedShift" value="{{ $employee->getCurrentShift()->id ?? '' }}">

            <!-- Sección de Horas Extras para EDITAR -->
            <div class="mb-6 border-t pt-6">
                <div class="flex items-center mb-4">
                    <input type="checkbox" 
                           wire:model="hasOvertime" 
                           id="hasOvertime"
                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="hasOvertime" class="ml-2 block text-sm font-medium text-gray-700">
                        Editar Horas Extras
                    </label>
                </div>

                @if($hasOvertime)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 transition-all duration-300">
                    <h2 class="text-lg font-semibold text-blue-800 mb-4">Editar Horas Extras</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Horas Extras Normales -->
                        <div class="bg-white p-4 rounded-lg border border-blue-100">
                            <h3 class="text-sm font-medium text-blue-800 mb-2">Horas Extras Normales</h3>
                            <div class="flex items-center gap-2">
                                <input type="number" 
                                       wire:model="regularOvertime" 
                                       min="0" 
                                       max="12"
                                       class="w-20 border border-gray-300 rounded-md px-3 py-2 text-center"
                                       value="{{ $regularHoursTotal }}">
                                <span class="text-sm text-gray-600">horas</span>
                            </div>
                            <p class="text-xs text-blue-600 mt-2">100% de recargo</p>
                            @error('regularOvertime') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Horas Extras Dobles -->
                        <div class="bg-white p-4 rounded-lg border border-green-100">
                            <h3 class="text-sm font-medium text-green-800 mb-2">Horas Extras Dobles</h3>
                            <div class="flex items-center gap-2">
                                <input type="number" 
                                       wire:model="doubleOvertime" 
                                       min="0" 
                                       max="12"
                                       class="w-20 border border-gray-300 rounded-md px-3 py-2 text-center"
                                       value="{{ $doubleHoursTotal }}">
                                <span class="text-sm text-gray-600">horas</span>
                            </div>
                            <p class="text-xs text-green-600 mt-2">200% de recargo</p>
                            @error('doubleOvertime') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Resumen de Horas Extras -->
                    <div class="bg-blue-100 p-3 rounded-md">
                        <h3 class="text-sm font-medium text-blue-800 mb-2">Resumen de horas extras:</h3>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div class="text-blue-600">Normales: <span class="font-medium">{{ $regularOvertime }}</span> horas</div>
                            <div class="text-green-600">Dobles: <span class="font-medium">{{ $doubleOvertime }}</span> horas</div>
                            <div class="col-span-2 text-blue-800 font-medium">Total: <span class="text-lg">{{ $regularOvertime + $doubleOvertime }}</span> horas extras</div>
                        </div>
                    </div>
                </div>
                @else
                <!-- Mostrar solo el total cuando no se está editando -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600">Horas extras registradas:</p>
                        <p class="text-lg font-bold text-blue-600">{{ $totalHours }} hrs</p>
                    </div>
                    @if($totalHours > 0)
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $regularHoursTotal }} hrs normales + {{ $doubleHoursTotal }} hrs dobles
                    </p>
                    @endif
                </div>
                @endif
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('attendances.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Actualizar Asistencia
                </button>
            </div>
        </form>

        <!-- Mensajes flash -->
        @if(session()->has('success'))
            <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session()->has('error'))
            <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif
    </div>
</div>