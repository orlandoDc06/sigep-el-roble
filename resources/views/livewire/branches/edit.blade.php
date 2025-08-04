<div class="p-6 max-w-xl mx-auto bg-white rounded-lg shadow space-y-4">
    <h2 class="text-2xl font-semibold text-gray-800">Editar sucursal</h2>

    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="updateBranch" enctype="multipart/form-data" class="space-y-4">
        <label class="block text-sm font-medium text-gray-700">Nombre de la sucursal</label>
        <input type="text" wire:model="name" placeholder="Nombre de la sucursal" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">

        <label class="block text-sm font-medium text-gray-700">Dirección de la sucursal</label>
        <input type="text" wire:model="address" placeholder="Dirección de la sucursal" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
        
        <label class="block text-sm font-medium text-gray-700">Imagen de la sucursal</label>
        <input type="file" wire:model="image_path" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">

        @if ($image_path)
            <img src="{{ $image_path->temporaryUrl() }}" class="w-36 mt-2 rounded shadow border">
        @elseif ($this->image_path)
            <img src="{{ Storage::url($this->image_path) }}" alt="Sucursal" class="w-36 mt-2 rounded shadow border">
        @else
            <span class="bg-gray-100 text-gray-500 ">Sin imagen</span>
        @endif

        <div class="flex justify-center space-x-4 ">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition duration-150">
                Actualizar
            </button>

            <button type="button" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition duration-150" wire:click="returnIndex">
                Cancelar
            </button>
        </div>
    </form>
</div>
