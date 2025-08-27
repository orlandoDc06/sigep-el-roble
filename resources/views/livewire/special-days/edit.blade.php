<div>
    <div class="container mx-auto p-6">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">

            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Editar Día Festivo</h1>
                    <p class="text-gray-600">Modificar día festivo existente</p>
                </div>
                <a href="{{ route('special-days.index') }}" class="text-blue-600 hover:text-blue-800">
                    ← Volver al listado
                </a>
            </div>

            <!-- Formulario -->
            <form wire:submit.prevent="update" class="space-y-6">
                <!-- Nombre -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre del día festivo *
                    </label>
                    <input type="text" wire:model="name" id="name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ej: Navidad, Año Nuevo, etc.">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Fecha -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha *
                    </label>
                    <input type="date" wire:model="date" id="date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Checkboxes -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Día pagado -->
                    <div class="flex items-center">
                        <input type="checkbox" wire:model="is_paid" id="is_paid" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="is_paid" class="ml-2 block text-sm text-gray-700">
                            Día pagado
                        </label>
                    </div>

                    <!-- Recurrente -->
                    <div class="flex items-center">
                        <input type="checkbox" wire:model="recurring" id="recurring" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="recurring" class="ml-2 block text-sm text-gray-700">
                            Se repite cada año
                        </label>
                    </div>
                </div>

                <!-- Información del día -->
                <div class="bg-gray-50 p-4 rounded-md">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Información actual:</h3>
                    <p class="text-sm text-gray-600">
                        <strong>Nombre:</strong> {{ $specialDay->name ?? '' }}<br>
                        <strong>Fecha original:</strong> {{ $specialDay->date->format('d/m/Y') ?? '' }}<br>
                        <strong>Estado:</strong> 
                        @if($specialDay->is_paid ?? false)
                            <span class="text-green-600">Pagado</span>
                        @else
                            <span class="text-gray-600">No pagado</span>
                        @endif
                        <br>
                        <strong>Recurrente:</strong> 
                        @if($specialDay->recurring ?? false)
                            <span class="text-purple-600">Sí</span>
                        @else
                            <span class="text-gray-600">No</span>
                        @endif
                    </p>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-4 pt-6">
                    <a href="{{ route('special-days.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Actualizar Día Festivo
                    </button>
                </div>
            </form>

            <!-- Mensajes -->
            @if(session()->has('success'))
                <div class="mt-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session()->has('error'))
                <div class="mt-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>
</div>