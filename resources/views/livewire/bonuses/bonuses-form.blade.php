<div class="p-6 max-w-xl mx-auto bg-white rounded-lg shadow space-y-4">
    <h2 class="text-2xl font-semibold text-gray">
        {{ $is_editing ? 'Editar bonificación' : 'Nueva bonificación' }}
    </h2>

    <form wire:submit.prevent="{{ $is_editing ? 'updateBonus' : 'createBonus' }}" class="space-y-4">
        {{-- Nombre --}}
        @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        <input type="text" wire:model="name" placeholder="Nombre de la bonificación"
            class="w-full border border-gray rounded px-4 py-2">

        {{-- Descripción --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
            <textarea wire:model="description" rows="3"
                class="w-full border border-gray rounded px-4 py-2"
                placeholder="Breve descripción del bono"></textarea>
            @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Monto por defecto --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Monto por defecto</label>
            <input type="number" step="0.01" wire:model="default_amount"
                class="w-full border border-gray rounded px-4 py-2"
                placeholder="Ej: 150.00">
            @error('default_amount') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- ¿Es porcentaje? --}}
        <div class="flex items-center space-x-3">
            <input type="checkbox" wire:model="is_percentage" id="is_percentage"
                class="h-4 w-4 text-blue-600 border-gray-300 rounded">
            <label for="is_percentage" class="text-sm font-medium text-gray-700">
                ¿Es porcentaje?
            </label>
        </div>

        {{-- ¿Aplica a todos? --}}
        <div class="flex items-center space-x-3">
            <input type="checkbox" wire:model="applies_to_all" id="applies_to_all"
                class="h-4 w-4 text-blue-600 border-gray-300 rounded">
            <label for="applies_to_all" class="text-sm font-medium text-gray-700">
                Aplica a todos los empleados
            </label>
        </div>

        {{-- Botones --}}
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
