<div class="p-6 max-w-xl mx-auto bg-white rounded-lg shadow space-y-4">
    <h2 class="text-2xl font-semibold text-gray">
        {{ $is_editing ? 'Editar turno' : 'Nuevo turno' }}
    </h2>

    <form wire:submit.prevent="createShift" class="space-y-4">
        @error('name') <span class="text-red-600 text-sm" style="color: red;">{{ $message }}</span> @enderror
        <input type="text" wire:model="name" placeholder="Nombre del turno"
            class="w-full border border-gray rounded px-4 py-2">


        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hora de inicio</label>
                <input type="time" wire:model="start_time"
                    class="w-full border border-gray rounded px-4 py-2">
                @error('start_time') <span class="text-red-600 text-sm" style="color: red;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hora de fin</label>
                <input type="time" wire:model="end_time"
                    class="w-full border border-gray rounded px-4 py-2">
                @error('end_time') <span class="text-red-600 text-sm" style="color: red;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <input type="checkbox" wire:model="is_night_shift" id="is_night_shift"
                class="h-4 w-4 text-blue-600 border-gray-300 rounded">
            <label for="is_night_shift" class="text-sm font-medium text-gray-700">
                ¿Es turno nocturno?
            </label>
        </div>

        @if($is_night_shift)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                <div class="flex items-center">
                    <svg ... class="w-5 h-5 text-blue-500 mr-2"> ... </svg>
                    <span class="text-sm text-blue-700">Este turno está marcado como nocturno</span>
                </div>
            </div>
        @endif

        <div class="flex justify-center space-x-4">
            <button type="submit"
                class="bg-verde-sigep hover:bg-verde-sigep-hover text-white px-4 py-2 rounded">
                {{ $is_editing ? 'Actualizar' : 'Guardar' }}
            </button>

            <button type="button"
                class="bg-rojo text-white px-4 py-2 rounded hover:bg-rojo-hover"
                wire:click="returnIndex">
                Cancelar
            </button>
        </div>
    </form>
</div>
