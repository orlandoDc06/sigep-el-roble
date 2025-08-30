@extends('layouts.app')

@section('titulo', 'Gestión de Planillas')

@section('contenido')
<div class="max-w-7xl mx-auto py-8">

    <!-- Encabezado -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                 d="M9 17v-2h6v2m-6 4h6v-2H9v2zm11-9.5V21a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2h7.5L20 7.5z"/></svg>
            Gestión de Planillas
        </h1>

        <!-- Botón generar para todos -->
        <form action="{{ route('payrolls.generateAll') }}" method="POST">
            @csrf
            <button type="submit"
                class="px-5 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                     d="M12 4v16m8-8H4"/></svg>
                Generar planilla para todos
            </button>
        </form>
    </div>

    <!-- Buscador -->
    <form method="GET" action="{{ route('payrolls.index') }}" class="mb-6">
        <div class="flex items-center">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Buscar empleado..."
                class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-indigo-500 focus:border-indigo-500">
            <button type="submit"
                class="px-4 py-2 bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg hover:bg-gray-200">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                     d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </button>
        </div>
    </form>

    <!-- Tabla empleados -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">Empleado</th>
                    <th class="px-4 py-2 text-left">Sucursal</th>
                    <th class="px-4 py-2 text-center">Estado Planilla</th>
                    <th class="px-4 py-2 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($employees as $employee)
                    @php
                        $detail = $employee->payrollForPeriod($currentPeriod['start'], $currentPeriod['end']);
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                        <td class="px-4 py-2">{{ $employee->branch->name ?? '-' }}</td>
                        <td class="px-4 py-2 text-center">
                            @if($detail)
                                <span class="px-2 py-1 rounded text-white
                                    @if($detail->payroll->status === 'generated') bg-yellow-500
                                    @elseif($detail->payroll->status === 'approved') bg-blue-500
                                    @elseif($detail->payroll->status === 'paid') bg-green-600
                                    @else bg-gray-400 @endif">
                                    {{ ucfirst($detail->payroll->status) }}
                                </span>
                            @else
                                <span class="text-gray-500">No generada</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-center">
                            @if(!$detail)
                                <a href="{{ route('payrolls.generate', $employee) }}"
                                   class="px-3 py-1 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                         viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                                         d="M12 4v16m8-8H4"/></svg>
                                    Generar
                                </a>
                            @else
                                <a href="{{ route('payrolls.show', $detail->payroll) }}"
                                   class="px-3 py-1 bg-gray-600 text-white rounded-lg shadow hover:bg-gray-700 inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                         viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                                         d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Ver Detalle
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-6">
        {{ $employees->links() }}
    </div>
</div>
@endsection
