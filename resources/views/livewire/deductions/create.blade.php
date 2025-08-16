<div class="p-6 max-w-xl mx-auto bg-white rounded-lg shadow-md space-y-6">
    <h2 class="text-2xl font-semibold text-gray-800 border-b pb-2">Nuevo descuento</h2>

    <form wire:submit.prevent="createDeduction" class="space-y-6">
        <div class="space-y-1">
            <label class="block text-sm font-medium text-gray-700">Nombre del descuento *</label>
            <input type="text" wire:model="name" placeholder="Ej. Seguro médico" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
            @error('name') <span class="text-red-500 text-xs">Por favor ingrese un nombre válido</span> @enderror
        </div>

        <div class="space-y-1">
            <label class="block text-sm font-medium text-gray-700">Descripción *</label>
            <textarea wire:model="description" placeholder="Ej. Deducción mensual para seguro médico" rows="3" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
            @error('description') <span class="text-red-500 text-xs">Por favor ingrese una descripción válida</span> @enderror
        </div>

        <div class="space-y-1">
            <label class="block text-sm font-medium text-gray-700">Monto por defecto</label>
            <input type="number" wire:model="default_amount" placeholder="0.00" step="0.01" min="0" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
            @error('default_amount') <span class="text-red-500 text-xs">Ingrese un monto válido (ej. 10.50)</span> @enderror
        </div>

        <div class="space-y-3">
            <div class="flex items-center">
                <input type="checkbox" id="aplica_todos" wire:model="applies_to_all" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                <label for="aplica_todos" class="ml-2 block text-sm text-gray-700">Aplicar a todos los empleados</label>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="es_porcentaje" wire:model="is_percentage" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                <label for="es_porcentaje" class="ml-2 block text-sm text-gray-700">Es un porcentaje</label>
            </div>
        </div>

        <div class="flex justify-end space-x-4 pt-4 border-t">
            <button type="button" wire:click="returnIndexDeduction" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Cancelar
            </button>
            
            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Guardar descuento
            </button>
        </div>
    </form>
</div>