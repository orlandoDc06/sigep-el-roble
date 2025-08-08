<div class="p-6 max-w-xl mx-auto bg-white rounded-lg shadow space-y-4">
    <h2 class="text-2xl font-semibold text-gray-800">
        {{ $shift_id ? 'Editar turno' : 'Crear turno' }}
    </h2>

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="updateShift" class="space-y-4">
        <label class="block text-sm font-medium text-gray-700">Nombre del turno</label>
        <input type="text" wire:model="name" placeholder="Nombre del turno" 
            class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hora de inicio</label>
                <input type="time" wire:model="start_time" 
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('start_time') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hora de fin</label>
                <input type="time" wire:model="end_time" 
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('end_time') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <input type="checkbox" wire:model="is_night_shift" id="is_night_shift" 
                class="h-4 w-4 text-blue-600 border-gray-300 rounded">
            <label for="is_night_shift" class="text-sm font-medium text-gray-700">
                ¿Es turno nocturno?
            </label>
        </div>

        @if ($is_night_shift)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                <span class="text-sm text-blue-700">Este turno está marcado como nocturno.</span>
            </div>
        @endif

        <div class="flex justify-center space-x-4">
            <button type="submit" 
               class="bg-verde-sigep hover:bg-verde-sigep-hover transition-colors cursor-pointer text-white px-4 py-2 rounded"class="bg-verde-sigep hover:bg-verde-sigep-hover transition-colors cursor-pointer text-white px-4 py-2 rounded"class="bg-verde-sigep hover:bg-verde-sigep-hover transition-colors cursor-pointer text-white px-4 py-2 rounded">
                {{ $shift_id ? 'Actualizar turno' : 'Guardar turno' }}
            </button>

            <button type="button" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition duration-150" wire:click="returnIndex">
                Cancelar
            </button>
        </div>
    </form>
</div>
