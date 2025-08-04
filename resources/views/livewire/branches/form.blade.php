<div class="p-6 max-w-xl mx-auto bg-white rounded-lg shadow space-y-4">
    <h2 class="text-2xl font-semibold text-gray-800">Nueva sucursal</h2>

    <form wire:submit.prevent="createBranch" enctype="multipart/form-data" class="space-y-4">
        <input type="text" wire:model="name" placeholder="Nombre de la sucursal" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
        <input type="text" wire:model="address" placeholder="Dirección de la sucursal" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">

        <input type="file" wire:model="image_path" class="w-full border border-gray-300 rounded px-4 py-2">

        @if ($image_path)
            <img src="{{ $image_path->temporaryUrl() }}" class="w-36 mt-2 rounded shadow border">
        @endif
        <div class="flex justify-center space-x-4">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition duration-150">
                Guardar
            </button>
            
            <button type="button" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition duration-150" wire:click="returnIndex">
                Cancelar
            </button>
        </div>
    </form>
</div>
