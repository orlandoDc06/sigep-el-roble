<div class="p-6 bg-white rounded-lg shadow-lg max-w-xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Editar Asignación de Descuentos</h2>

    <form wire:submit.prevent="update">
        <!-- Empleado (solo lectura) -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Empleado</label>
            <input type="text" 
                value="{{ $employee_name }}" 
                class="w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-600" 
                readonly>
        </div>

        <!-- Descuento (solo lectura) -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Descuento</label>
            <input type="text" 
                value="{{ $deduction_name }}" 
                class="w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-600" 
                readonly>
        </div>

        <!-- Monto (editable) -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Monto</label>
            <input type="number" step="0.01" wire:model="amount" class="w-full border rounded-lg px-3 py-2">
            @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Notas (editable) -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Notas (opcional)</label>
            <textarea wire:model="notes" class="w-full border rounded-lg px-3 py-2" rows="2"></textarea>
            @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Botones -->
        <div class="flex justify-end space-x-2">
            <a href="{{ route('deductions-assignments.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                Cancelar
            </a>
            <button type="submit" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Actualizar
            </button>
        </div>
    </form>
</div>
