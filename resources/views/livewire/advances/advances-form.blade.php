<div class="p-6 max-w-xl mx-auto bg-white rounded-lg shadow space-y-4">
    <h2 class="text-2xl font-semibold text-gray-700">
        {{ $is_editing ? 'Editar anticipo' : 'Nuevo anticipo' }}
    </h2>

    <form wire:submit.prevent="{{ $is_editing ? 'updateAdvance' : 'createAdvance' }}" class="space-y-4">

        {{-- Empleado --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Empleado</label>
            <select wire:model="employee_id" class="w-full border border-gray-300 rounded px-4 py-2">
                <option value="">Seleccionar empleado</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                @endforeach
            </select>
            @error('employee_id') 
                <span class="text-red-600 text-sm">{{ $message }}</span> 
            @enderror
        </div>

        {{-- Monto --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Monto</label>
            <input type="number" step="0.01" wire:model="amount"
                class="w-full border border-gray-300 rounded px-4 py-2"
                placeholder="Ej: 150.00">
            <p class="text-gray-500 text-xs mt-1">Monto máximo permitido: ${{ number_format($max_amount, 2) }}</p>
            @error('amount') 
                <span class="text-red-600 text-sm">{{ $message }}</span> 
            @enderror
        </div>

        {{-- Fecha --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
            <input type="date" wire:model="date"
                class="w-full border border-gray-300 rounded px-4 py-2">
            @error('date') 
                <span class="text-red-600 text-sm">{{ $message }}</span> 
            @enderror
        </div>

        {{-- Razón --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Razón (opcional)</label>
            <textarea wire:model="reason" rows="3"
                class="w-full border border-gray-300 rounded px-4 py-2"
                placeholder="Motivo del anticipo"></textarea>
            @error('reason') 
                <span class="text-red-600 text-sm">{{ $message }}</span> 
            @enderror
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

        {{-- Mensaje de éxito --}}
        @if (session()->has('message'))
            <p class="text-green-600 font-medium mt-2">{{ session('message') }}</p>
        @endif

    </form>
</div>
