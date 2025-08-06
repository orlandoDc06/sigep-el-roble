<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Restablecer contraseña</h2>

    @if (session('status'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit.prevent="resetPassword">
        <div class="mb-4">
            <label for="email" class="block text-gray-700">Correo</label>
            <input type="email" id="email" wire:model.defer="email" class="w-full p-2 border rounded" required>
            @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700">Nueva contraseña</label>
            <input type="password" id="password" wire:model.defer="password" class="w-full p-2 border rounded" required>
            @error('password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-gray-700">Confirmar contraseña</label>
            <input type="password" id="password_confirmation" wire:model.defer="password_confirmation" class="w-full p-2 border rounded" required>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">
            Restablecer contraseña
        </button>
    </form>
</div>
