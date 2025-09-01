<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-t-lg p-6 text-white shadow-lg">
            <div class="flex items-center space-x-4">
                <div class="bg-white bg-opacity-20 p-3 rounded-full">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M11 17a1 1 0 01-.894-.553L7 11.618l-2.106 4.829A1 1 0 014 17H2a1 1 0 010-2h1.382l3.724-8.553A1 1 0 018 6h4a1 1 0 01.894.553L16.618 15H18a1 1 0 010 2h-2a1 1 0 01-.894-.553L11 7.618 8.106 13.447A1 1 0 017 14H5a1 1 0 110-2h1.382L11 17z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Reporte de Bonos y Descuentos</h1>
                    <p class="text-green-100 mt-1">Visualiza los movimientos aplicados al empleado</p>
                </div>
            </div>
        </div>

        <!-- Contenido -->
        <div class="bg-white rounded-b-lg shadow-lg p-6 space-y-10">

            <!-- Bonos -->
            <div>
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 p-2 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 6.707 9.293a1 1 0 10-1.414 1.414L9 14.414l8-8a1 1 0 000-1.414z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Bonos</h3>
                </div>

                <table class="w-full border-collapse border border-gray-300 rounded-lg overflow-hidden shadow-sm">
                    <thead>
                        <tr class="bg-green-50 text-green-800">
                            <th class="border px-4 py-2 text-left">Nombre</th>
                            <th class="border px-4 py-2 text-left">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bonuses as $bonus)
                            <tr class="hover:bg-green-50 transition">
                                <td class="border px-4 py-2">{{ $bonus->name }}</td>
                                <td class="border px-4 py-2 font-medium">${{ number_format($bonus->default_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-gray-500 py-3">No hay bonos registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Descuentos -->
            <div>
                <div class="flex items-center mb-4">
                    <div class="bg-red-100 p-2 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12h2V8H9v4zm0 4h2v-2H9v2zM10 2a8 8 0 100 16 8 8 0 000-16z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Descuentos</h3>
                </div>

                <table class="w-full border-collapse border border-gray-300 rounded-lg overflow-hidden shadow-sm">
                    <thead>
                        <tr class="bg-red-50 text-red-800">
                            <th class="border px-4 py-2 text-left">Nombre</th>
                            <th class="border px-4 py-2 text-left">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deductions as $deduction)
                            <tr class="hover:bg-red-50 transition">
                                <td class="border px-4 py-2">{{ $deduction->name }}</td>
                                <td class="border px-4 py-2 font-medium">${{ number_format($deduction->default_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-gray-500 py-3">No hay descuentos registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Botón -->
            <div class="flex justify-end pt-4 border-t border-gray-200">
                <a href="{{ route('reportes.bonos-descuentos.pdf') }}"
                   class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 shadow">
                    Generar PDF
                </a>
            </div>
        </div>
    </div>
</div>
