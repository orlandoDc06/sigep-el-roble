@extends('layouts.app')

@section('titulo', 'Detalle de Planilla')

@section('contenido')
<div class="max-w-7xl mx-auto py-8">

    <!-- Encabezado -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                 d="M9 17v-2h6v2m-6 4h6v-2H9v2zm11-9.5V21a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2h7.5L20 7.5z"/></svg>
            Detalle de Planilla
        </h1>
        <p class="text-gray-600">
            Período: {{ $payroll->period_start->format('d/m/Y') }} - {{ $payroll->period_end->format('d/m/Y') }}
        </p>
        <p class="text-gray-600 mt-2">
            Estado actual: 
            <span class="px-2 py-1 rounded text-white
                @if($payroll->status === 'generated') bg-yellow-500
                @elseif($payroll->status === 'approved') bg-blue-500
                @elseif($payroll->status === 'paid') bg-green-600
                @else bg-gray-400 @endif">
                {{ ucfirst($payroll->status) }}
            </span>
        </p>
    </div>

    <!-- Tabla -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">Empleado</th>
                    <th class="px-4 py-2 text-right">Salario Base</th>
                    <th class="px-4 py-2 text-right">Bonos</th>
                    <th class="px-4 py-2 text-right">Deducciones</th>
                    <th class="px-4 py-2 text-right">Anticipos</th>
                    <th class="px-4 py-2 text-right">Horas Extra</th>
                    <th class="px-4 py-2 text-right">ISSS</th>
                    <th class="px-4 py-2 text-right">AFP</th>
                    <th class="px-4 py-2 text-right">ISR</th>
                    <th class="px-4 py-2 text-right">Neto</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($payroll->details as $detail)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $detail->employee->first_name }} {{ $detail->employee->last_name }}</td>
                    <td class="px-4 py-2 text-right">${{ number_format($detail->base_salary, 2) }}</td>
                    <td class="px-4 py-2 text-right text-green-600">+ ${{ number_format($detail->bonuses_total, 2) }}</td>
                    <td class="px-4 py-2 text-right text-red-600">- ${{ number_format($detail->deductions_total, 2) }}</td>
                    <td class="px-4 py-2 text-right text-red-600">- ${{ number_format($detail->advances_total ?? 0, 2) }}</td>
                    <td class="px-4 py-2 text-right text-green-600">+ ${{ number_format($detail->extra_hours_total, 2) }}</td>
                    <td class="px-4 py-2 text-right text-red-600">- ${{ number_format($detail->isss, 2) }}</td>
                    <td class="px-4 py-2 text-right text-red-600">- ${{ number_format($detail->afp, 2) }}</td>
                    <td class="px-4 py-2 text-right text-red-600">- ${{ number_format($detail->isr, 2) }}</td>
                    <td class="px-4 py-2 text-right font-bold">${{ number_format($detail->net_salary, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Acciones de Estado -->
    @if($payroll->status !== 'paid')
        <div class="mt-6 bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Actualizar Estado</h2>
            <form action="{{ route('payrolls.updateStatus', $payroll) }}" method="POST" class="flex gap-4">
                @csrf
                @method('PATCH')

                @if($payroll->status === 'generated')
                    <button type="submit" name="status" value="approved"
                        class="px-5 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                        Aprobar Planilla
                    </button>
                @elseif($payroll->status === 'approved')
                    <button type="submit" name="status" value="paid"
                        class="px-5 py-2 bg-green-600 text-white font-semibold rounded-lg shadow hover:bg-green-700 transition">
                        Marcar como Pagada
                    </button>
                @endif
            </form>
        </div>
    @endif

</div>
@endsection
