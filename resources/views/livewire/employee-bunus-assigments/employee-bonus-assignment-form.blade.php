<div class="p-6 bg-white rounded-lg shadow-lg max-w-xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Nueva Asignación de Bono</h2>

    <form wire:submit.prevent="save">
        <!-- Empleado -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Empleado</label>
            <select wire:model="employee_id" class="w-full border rounded-lg px-3 py-2">
                <option value="">Seleccione un empleado</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                @endforeach
            </select>
            @error('employee_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Bono -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Bono</label>
            <select wire:model="bonus_id" class="w-full border rounded-lg px-3 py-2">
                <option value="">Seleccione un bono</option>
                @foreach($bonuses as $bonus)
                    <option value="{{ $bonus->id }}">{{ $bonus->name }}</option>
                @endforeach
            </select>
            @error('bonus_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Monto -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Monto</label>
            <input type="number" step="0.01" wire:model="amount" class="w-full border rounded-lg px-3 py-2">
            @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Notas (opcional) -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Notas (opcional)</label>
            <textarea wire:model="notes" class="w-full border rounded-lg px-3 py-2" rows="2"></textarea>
            @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Fecha aplicada (oculta, se toma automáticamente) -->
        <input type="hidden" wire:model="applied_at">

        <!-- Botones -->
        <div class="flex justify-end space-x-2">
            <button type="button" wire:click="$emit('closeForm')" 
                class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                Cancelar
            </button>
            <button type="submit" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Guardar
            </button>
        </div>
    </form>
</div>
