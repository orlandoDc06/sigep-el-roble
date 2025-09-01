@extends('layouts.app')

@section('titulo', 'Perfil de Usuario')

@section('contenido')
    <div class="max-w-7xl mx-auto p-6 space-y-8">

    {{-- Encabezado --}}
    <div class="flex items-center text-gray-900 bg-gradient-to-r from-indigo-600 to-blue-500 text-white rounded-2xl p-6 shadow-lg">
        <img src="{{ $user->profile_image_path ? Storage::url($user->profile_image_path) : '/img/default-avatar.png' }}"
             alt="Foto de perfil"
             class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md">
        <div class="ml-6">
            <h1 class="text-3xl font-bold text-gray-900 ml-2">{{ $employee ? $employee->first_name.' '.$employee->last_name : $user->name }}</h1>
            <p class="text-white/80 text-gray-900 ml-2">{{ $user->email }}</p>
            <p class="mt-2 text-sm text-gray-900 bg-white/20 px-3 py-1 rounded-full inline-block">
                {{ $user->getRoleNames()->implode(', ') }}
            </p>
        </div>
        @if($employee)
        <div class="ml-auto">
            <a href="{{ route('employees.edit', $employee->id) }}"
               class="bg-white text-indigo-600 px-4 py-2 rounded-xl font-medium hover:bg-gray-100 shadow">
                ✏️ Editar información
            </a>
        </div>
        @endif
    </div>

    {{-- Información personal --}}
    @if($employee)
    <section class="bg-white rounded-2xl shadow p-6">
        <div class="flex items-center mb-4">
            <div class="bg-blue-100 p-2 rounded-lg mr-2">
                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zM3 18a7 7 0 1114 0H3z"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-800">Información Personal</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
            <p><span class="font-medium">DUI:</span> {{ $employee->dui }}</p>
            <p><span class="font-medium">Teléfono:</span> {{ $employee->phone }}</p>
            <p><span class="font-medium">Nacimiento:</span> {{ $employee->birth_date }}</p>
            <p><span class="font-medium">Género:</span> {{ $employee->gender }}</p>
            <p><span class="font-medium">Estado civil:</span> {{ $employee->marital_status }}</p>
            <p class="col-span-2 md:col-span-3"><span class="font-medium">Dirección:</span> {{ $employee->address }}</p>
        </div>
    </section>

    {{-- Información laboral --}}
    <section class="bg-white rounded-2xl shadow p-6">
        <div class="flex items-center mb-4">
            <div class="bg-green-100 p-2 rounded-lg mr-2">
                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.6A23 23 0 0110 13a23 23 0 01-8-1.4V8a2 2 0 012-2h2z"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-800">Información Laboral</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
            <p><span class="font-medium">Sucursal:</span> {{ $employee->branch->name ?? '-' }}</p>
            <p><span class="font-medium">Contrato:</span> {{ $employee->contractType->name ?? '-' }}</p>
            <p><span class="font-medium">Salario base:</span> ${{ number_format($employee->contractType->base_salary ?? 0, 2) }}</p>
            <p><span class="font-medium">Fecha contratación:</span> {{ $employee->hire_date }}</p>
            <p><span class="font-medium">Fecha terminación:</span> {{ $employee->termination_date ?? '---' }}</p>
            <p><span class="font-medium">Estado:</span> {{ $employee->status }}</p>
        </div>
    </section>

    {{-- Turnos de trabajo --}}
    <section class="bg-white rounded-2xl shadow p-6">
        <div class="flex items-center mb-4">
            <div class="bg-yellow-100 p-2 rounded-lg mr-2">
                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 9H9V5a1 1 0 112 0v6z"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-800">Turnos de trabajo</h2>
        </div>
        <ul class="list-disc list-inside text-sm space-y-1">
            @forelse($employee->shifts as $shift)
                <li>
                    <span class="font-medium">{{ $shift->name }}</span>
                    ({{ $shift->pivot->start_date }} - {{ $shift->pivot->end_date ?? 'Actual' }})
                </li>
            @empty
                <li>No tiene turnos asignados</li>
            @endforelse
        </ul>
    </section>

    {{-- Bonos recientes --}}
    <section class="bg-white rounded-2xl shadow p-6">
        <div class="flex items-center mb-4">
            <div class="bg-pink-100 p-2 rounded-lg mr-2">
                <svg class="w-5 h-5 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2l2 6h6l-5 4 2 6-5-4-5 4 2-6-5-4h6z"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-800">Bonos recientes</h2>
        </div>
        <ul class="list-disc list-inside text-sm space-y-1">
            @forelse($recentBonuses as $bonus)
                <li>{{ $bonus->name }} - ${{ number_format($bonus->pivot->amount ?? $bonus->default_amount, 2) }} ({{ $bonus->pivot->applied_at }})</li>
            @empty
                <li>No tiene bonos recientes</li>
            @endforelse
        </ul>
    </section>

    {{-- Deducciones recientes --}}
    <section class="bg-white rounded-2xl shadow p-6">
        <div class="flex items-center mb-4">
            <div class="bg-red-100 p-2 rounded-lg mr-2">
                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12h2v2H9v-2zm0-8h2v6H9V4z"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-800">Deducciones recientes</h2>
        </div>
        <ul class="list-disc list-inside text-sm space-y-1">
            @forelse($recentDeductions as $deduction)
                <li>{{ $deduction->name }} - ${{ number_format($deduction->pivot->amount ?? $deduction->default_amount, 2) }} ({{ $deduction->pivot->applied_at }})</li>
            @empty
                <li>No tiene deducciones recientes</li>
            @endforelse
        </ul>
    </section>

    @else
        <p class="mt-6 text-gray-500 bg-white rounded-xl shadow p-6">
            Este usuario no está vinculado a un empleado.
        </p>
    @endif

    {{-- Acceso al sistema --}}
    <section class="bg-white rounded-2xl shadow p-6">
        <div class="flex items-center mb-4">
            <div class="bg-indigo-100 p-2 rounded-lg mr-2">
                <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a6 6 0 016 6v2a6 6 0 01-12 0V8a6 6 0 016-6zM4 8v2a6 6 0 0012 0V8H4z"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-800">Acceso al sistema</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
            <p><span class="font-medium">Rol:</span> {{ $user->getRoleNames()->implode(', ') }}</p>
            <p><span class="font-medium">Último acceso:</span> {{ $user->last_login_at ?? 'Nunca' }}</p>
            <p><span class="font-medium">Estado usuario:</span> {{ $user->is_active ? 'Activo' : 'Suspendido' }}</p>
        </div>
    </section>
</div>
@endsection
