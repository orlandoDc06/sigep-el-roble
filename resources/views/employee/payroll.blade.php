@extends('layouts.app')

@section('contenido')
<div class="max-w-4xl mx-auto"> 
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Mi Planilla de Pagos</h1>
                <p class="text-gray-600 mt-1">
                    Período: {{ $periodStart->format('d/m/Y') }} - {{ $periodEnd->format('d/m/Y') }}
                </p>
            </div>
            <div class="text-right">
                <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                    @if($payrollDetail->payroll->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($payrollDetail->payroll->status === 'generated') bg-blue-100 text-blue-800
                    @elseif($payrollDetail->payroll->status === 'approved') bg-green-100 text-green-800
                    @elseif($payrollDetail->payroll->status === 'paid') bg-purple-100 text-purple-800
                    @endif">
                    @if($payrollDetail->payroll->status === 'pending') Pendiente
                    @elseif($payrollDetail->payroll->status === 'generated') Generada
                    @elseif($payrollDetail->payroll->status === 'approved') Aprobada
                    @elseif($payrollDetail->payroll->status === 'paid') Pagada
                    @endif
                </span>
            </div>
        </div>
    </div>

    <!-- Información del Empleado -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Información Personal</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Nombre Completo</p>
                <p class="font-medium">{{ $employee->first_name }} {{ $employee->last_name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">DUI</p>
                <p class="font-medium">{{ $employee->dui }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Sucursal</p>
                <p class="font-medium">{{ $employee->branch->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm">Salario Base</p>
                <p class="font-medium text-green-600">${{ number_format($payrollDetail->base_salary * 2, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Resumen de Planilla -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Ingresos -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-green-600 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 1.414L10.586 9.5H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path>
                </svg>
                Ingresos
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Salario Base (Quincenal)</span>
                    <span class="font-medium">${{ number_format($payrollDetail->base_salary, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Bonificaciones</span>
                    <span class="font-medium">${{ number_format($payrollDetail->bonuses_total, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Horas Extra</span>
                    <span class="font-medium">${{ number_format($payrollDetail->extra_hours_total, 2) }}</span>
                </div>
                <div class="border-t pt-2">
                    <div class="flex justify-between font-semibold text-green-600">
                        <span>Total Ingresos</span>
                        <span>${{ number_format($payrollDetail->base_salary + $payrollDetail->bonuses_total + $payrollDetail->extra_hours_total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deducciones -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-red-600 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 1.414L10.586 9.5H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd" transform="rotate(180 10 10)"></path>
                </svg>
                Deducciones
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">ISSS</span>
                    <span class="font-medium">${{ number_format($payrollDetail->isss, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">AFP</span>
                    <span class="font-medium">${{ number_format($payrollDetail->afp, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">ISR</span>
                    <span class="font-medium">${{ number_format($payrollDetail->isr, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Otros Descuentos</span>
                    <span class="font-medium">${{ number_format($payrollDetail->deductions_total, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Anticipos</span>
                    <span class="font-medium">${{ number_format($payrollDetail->advances_total, 2) }}</span>
                </div>
                <div class="border-t pt-2">
                    <div class="flex justify-between font-semibold text-red-600">
                        <span>Total Deducciones</span>
                        <span>${{ number_format($payrollDetail->isss + $payrollDetail->afp + $payrollDetail->isr + $payrollDetail->deductions_total + $payrollDetail->advances_total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Salario Neto -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-md p-6 text-white mb-6">
        <div class="text-center">
            <h2 class="text-2xl font-bold mb-2 text-gray-700">Salario Neto a Recibir</h2>
            <p class="text-3xl font-bold text-green-600">${{ number_format($payrollDetail->net_salary, 2) }}</p>
        </div>
    </div>

    @if($bonuses->count() > 0 || $deductions->count() > 0 || $advances->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Detalles del Período</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @if($bonuses->count() > 0)
            <div>
                <h4 class="font-medium text-green-600 mb-2">Bonificaciones</h4>
                <div class="space-y-1">
                    @foreach($bonuses as $bonus)
                    <div class="flex justify-between text-sm">
                        <span>{{ $bonus->name }}</span>
                        <span>${{ number_format($bonus->pivot->amount, 2) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($deductions->count() > 0)
            <div>
                <h4 class="font-medium text-red-600 mb-2">Descuentos</h4>
                <div class="space-y-1">
                    @foreach($deductions as $deduction)
                    <div class="flex justify-between text-sm">
                        <span>{{ $deduction->name }}</span>
                        <span>${{ number_format($deduction->pivot->amount, 2) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($advances->count() > 0)
            <div>
                <h4 class="font-medium text-orange-600 mb-2">Anticipos</h4>
                <div class="space-y-1">
                    @foreach($advances as $advance)
                    <div class="flex justify-between text-sm">
                        <span>{{ $advance->date->format('d/m/Y') }}</span>
                        <span>${{ number_format($advance->amount, 2) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

<!-- Botones de acción -->
    <div class="text-center space-x-4">
        <a href="{{ route('employee.payroll.pdf') }}" 
           class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition duration-200 inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Descargar PDF
        </a>
        <a href="{{ route('employee.dashboard') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
            Volver al Dashboard
        </a>
    </div>
@endsection