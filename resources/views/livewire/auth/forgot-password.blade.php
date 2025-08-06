<div>
    <div class="md:flex md:justify-center md:gap-10 md:items-center">
        <div class="md:w-6/12 p-5">
            <!-- Imagen opcional -->
        </div>
        <div class="md:w-4/12 bg-white p-6 rounded-lg shadow-xl">
            <h2 class="text-2xl font-bold mb-6 text-center">Recuperar contraseña</h2>

            @if (session()->has('status'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form wire:submit.prevent="sendResetLink" novalidate>
                <div class="mb-5">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Correo electrónico</label>
                    <input
                        id="email"
                        type="email"
                        wire:model.defer="email"
                        placeholder="Tu correo electrónico"
                        class="border p-3 w-full rounded-lg bg-white @error('email') border-red-500 @enderror"
                        required
                    />
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded w-full transition-colors"
                >
                    Enviar enlace de recuperación
                </button>
            </form>
        </div>
    </div>
</div>
