<div class="p-4">
    <div class="flex items-center gap-4 mb-4">
        <input wire:model.debounce.300ms="search" type="text" placeholder="Buscar empleado (nombre / DUI)" class="border rounded px-3 py-2 w-1/3" />
        <select wire:model="month" class="border rounded px-3 py-2">
            @for($m=1;$m<=12;$m++)
                <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
            @endfor
        </select>
        <input wire:model="year" type="number" class="border rounded px-3 py-2 w-24" />
        <select wire:model="half" class="border rounded px-3 py-2">
            <option value="first">1ra quincena (1-15)</option>
            <option value="second">2da quincena (16-fin)</option>
        </select>

        <button onclick="return confirm('¿Generar planilla para todos los empleados en la quincena seleccionada?') || event.stopImmediatePropagation()" wire:click="generateAll" class="ml-auto bg-blue-600 text-white px-4 py-2 rounded">Generar planilla para todos</button>
    </div>

    @if(session()->has('success'))
        <div class="bg-green-100 text-green-800 p-2 mb-2 rounded">{{ session('success') }}</div>
    @endif
    @if(session()->has('error'))
        <div class="bg-red-100 text-red-800 p-2 mb-2 rounded">{{ session('error') }}</div>
    @endif

    <div class="overflow-auto bg-white shadow rounded">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Empleado</th>
                    <th class="px-4 py-2 text-left">Cargo</th>
                    <th class="px-4 py-2 text-left">Estado planilla</th>
                    <th class="px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($employees as $emp)
                @php
                    $detail = $payrollDetailsMap[$emp->id] ?? null;
                @endphp
                <tr>
                    <td class="px-4 py-3">
                        {{ $emp->first_name }} {{ $emp->last_name }}
                        <div class="text-xs text-gray-500">{{ $emp->dui ?? '' }}</div>
                    </td>
                    <td class="px-4 py-3">{{ optional($emp->contract)->title ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($detail)
                            <span class="px-2 py-1 rounded {{ $detail['status'] === 'paid' ? 'bg-green-100 text-green-700' : ($detail['status'] === 'approved' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700') }}">
                                {{ ucfirst($detail['status']) }}
                            </span>
                        @else
                            <span class="px-2 py-1 rounded bg-red-100 text-red-700">Falta generar</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($detail)
                            <a href="{{ route('payrolls.employee.generate', $emp->id) }}" class="text-sm text-blue-600">Ver / Editar</a>
                        @else
                            <a href="{{ route('payrolls.employee.generate', $emp->id) }}" class="text-sm text-green-600">Generar</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $employees->links() }}
    </div>
</div>
