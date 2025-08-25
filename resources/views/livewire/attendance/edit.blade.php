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
                    <p class="font-medium">{{ $attendance->check_in_time->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

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
                    <label class="flex items-center">
                        <input type="radio" wire:model="attendanceType" value="absent" class="mr-2">
                        <span class="text-red-600 font-medium">Ausente</span>
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

            <!-- Notas -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones:</label>
                <textarea 
                    wire:model="notes" 
                    placeholder="Notas adicionales..."
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="3"
                >{{ $notes }}</textarea>
                @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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