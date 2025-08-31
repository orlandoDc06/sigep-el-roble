@extends('layouts.app')

@section('titulo', 'Generar Planilla')

@section('contenido')
<div class="max-w-5xl mx-auto py-8">

    <!-- Encabezado -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                 d="M9 17v-2h6v2m-6 4h6v-2H9v2zm11-9.5V21a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2h7.5L20 7.5z"/></svg>
            Generación de Planilla - {{ $employee->first_name }} {{ $employee->last_name }}
        </h1>
        <p class="text-gray-600">Período: {{ $periodStart->format('d/m/Y') }} - {{ $periodEnd->format('d/m/Y') }}</p>
    </div>

    <!-- Resumen -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-lg font-semibold mb-4">Resumen de Cálculo</h2>
        <ul class="space-y-2 text-gray-700">
            <li><strong>Salario Base Quincena:</strong> ${{ number_format($baseSalaryQuincena, 2) }}</li>
            <li><strong>Bonos:</strong> ${{ number_format($bonusesTotal, 2) }}</li>
            <li><strong>Deducciones:</strong> ${{ number_format($deductionsTotal, 2) }}</li>
            <li><strong>Anticipos:</strong> ${{ number_format($advancesTotal, 2) }}</li>
            <li><strong>Horas Extra:</strong> ${{ number_format($extraHoursTotal, 2) }}</li>
            <li><strong>ISSS:</strong> ${{ number_format($isss, 2) }}</li>
            <li><strong>AFP:</strong> ${{ number_format($afp, 2) }}</li>
            <li><strong>ISR:</strong> ${{ number_format($isr, 2) }}</li>
            <li class="font-bold text-green-600 text-lg">Neto a Pagar: ${{ number_format($netSalary, 2) }}</li>
        </ul>
    </div>

    <!-- Listas detalladas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                     d="M12 8c-1.657 0-3 1.343-3 3 0 1.28.805 2.377 1.929 2.787L11 18h2l.071-4.213A3 3 0 0015 11c0-1.657-1.343-3-3-3z"/></svg>
                Bonos
            </h3>
            <ul class="text-sm text-gray-600 list-disc list-inside">
                @forelse($bonuses as $b)
                    <li>{{ $b->name }}: ${{ number_format($b->pivot->amount ?? $b->default_amount, 2) }}</li>
                @empty
                    <li>No hay bonos</li>
                @endforelse
            </ul>
        </div>
        <div>
            <h3 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                     d="M12 14l9-5-9-5-9 5 9 5z"/></svg>
                Deducciones
            </h3>
            <ul class="text-sm text-gray-600 list-disc list-inside">
                @forelse($deductions as $d)
                    <li>{{ $d->name }}: ${{ number_format($d->pivot->amount ?? $d->default_amount, 2) }}</li>
                @empty
                    <li>No hay deducciones</li>
                @endforelse
            </ul>
        </div>
        <div>
            <h3 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                     d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Anticipos
            </h3>
            <ul class="text-sm text-gray-600 list-disc list-inside">
                @forelse($advances as $a)
                    <li>{{ $a->reason ?? 'Anticipo' }}: ${{ number_format($a->amount, 2) }}</li>
                @empty
                    <li>No hay anticipos</li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Confirmar -->
    <form action="{{ route('payrolls.store', $employee) }}" method="POST" class="mt-8">
        @csrf
        <button type="submit"
            class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition">
            Confirmar y Guardar Planilla
        </button>
    </form>
</div>
@endsection
