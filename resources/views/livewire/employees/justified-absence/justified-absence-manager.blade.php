<div class="p-6 max-w-3xl mx-auto bg-white rounded-lg shadow space-y-6">

    {{-- Mensaje de éxito --}}
    @if(session()->has('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 p-3 rounded flex items-center space-x-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Formulario de solicitud --}}
    <div class="bg-gray-50 p-5 rounded-lg shadow-inner space-y-4">
        <h2 class="text-xl font-semibold text-gray-700">Solicitar permiso</h2>

        <form wire:submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-600">Fecha</label>
                <input type="date" wire:model="date"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Motivo</label>
                <textarea wire:model="reason"
                          class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                @error('reason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                Enviar solicitud
            </button>
        </form>
    </div>

    {{-- Lista de permisos --}}
    <div class="space-y-3">
        <h2 class="text-xl font-semibold text-gray-700">Mis permisos</h2>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left text-gray-600">Fecha</th>
                        <th class="border px-4 py-2 text-left text-gray-600">Motivo</th>
                        <th class="border px-4 py-2 text-left text-gray-600">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($justifiedAbsences as $absence)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($absence->date)->format('d-m-Y') }}</td>
                            <td class="border px-4 py-2">{{ $absence->reason }}</td>
                            <td class="border px-4 py-2 capitalize">
                                <span class="px-2 py-1 rounded
                                    @if($absence->status === 'pendiente') bg-yellow-100 text-yellow-800
                                    @elseif($absence->status === 'aprobado') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $absence->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="border px-4 py-2 text-center text-gray-500">No tienes solicitudes de permiso</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
