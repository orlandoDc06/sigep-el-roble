<div class="p-6 max-w-xl mx-auto bg-white rounded-lg shadow space-y-4">
    <h2 class="text-2xl font-semibold text-gray-800">
        Editar estado de {{ $name }}
        @if($isUser)
            @if($isAdmin)
                <span class="text-sm text-blue-600">(Administrador)</span>
            @else
                <span class="text-sm text-indigo-600">(Usuario)</span>
            @endif
        @elseif($isEmployee)
            <span class="text-sm text-green-600">(Empleado)</span>
        @endif
    </h2>

    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="updateStatus" class="space-y-4">
        <label class="block text-sm font-medium text-gray-700" for="status">Estado</label>
        <select id="status" wire:model="status" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">Seleccionar estado</option>

            @if($isUser)
                <option value="active" @if($status === 'active') selected @endif>Activo</option>
                <option value="inactive" @if($status === 'inactive') selected @endif>Inactivo</option>
            @endif
            @if($isEmployee)
                <option value="active" @if($status === 'active') selected @endif>Activo</option>
                <option value="inactive" @if($status === 'inactive') selected @endif>Inactivo</option>
                <option value="suspended" @if($status === 'suspended') selected @endif>Suspendido</option>
            @endif

        </select>

        @error('status')
            <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror

        <div class="flex justify-center space-x-4">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition duration-150">
                Actualizar Estado
            </button>

            <button type="button" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition duration-150" wire:click="returnIndex">
                Cancelar
            </button>
        </div>
    </form>
</div>
