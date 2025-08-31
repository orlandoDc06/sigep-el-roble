<div class="container mx-auto px-4">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-lg mb-6">
        <div class="flex justify-between items-center p-6 border-b">
            <h2 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-calculator mr-2 text-green-600"></i>
                Gestión de Fórmulas
            </h2>
            <a href="{{ route('admin.formulas.create') }}" 
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Nueva Fórmula
            </a>
        </div>

        <!-- Filtros -->
        <div class="p-6 border-b bg-gray-50">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                    <input type="text" 
                           wire:model.live="search"
                           placeholder="Buscar por nombre o expresión..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                    <select wire:model.live="selectedType" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Todos los tipos</option>
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes -->
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabla de Fórmulas -->
    <div class="bg-white rounded-lg shadow-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expresión</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($formulas as $formula)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $formula->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                    {{ $types[$formula->type] ?? $formula->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <code class="text-sm bg-gray-100 px-2 py-1 rounded">
                                    {{ Str::limit($formula->expression, 50) }}
                                </code>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($formula->syntax_validated)
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>Validada
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>Sin validar
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <!-- Editar -->
                                    <a href="{{ route('admin.formulas.edit', $formula->id) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Toggle Validación -->
                                    <button wire:click="toggleValidation({{ $formula->id }})" 
                                            class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-{{ $formula->syntax_validated ? 'times' : 'check' }}"></i>
                                    </button>
                                    
                                    <!-- Duplicar -->
                                    <button wire:click="duplicate({{ $formula->id }})" 
                                            class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                    
                                    <!-- Eliminar -->
                                    <button wire:click="confirmDelete({{ $formula->id }})" 
                                            class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-calculator text-4xl mb-4"></i>
                                <p>No se encontraron fórmulas</p>
                                <a href="{{ route('admin.formulas.create') }}" 
                                   class="text-green-600 hover:text-green-800 mt-2 inline-block">
                                    Crear la primera fórmula
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($formulas->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $formulas->links() }}
            </div>
        @endif
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mt-5">Confirmar Eliminación</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            ¿Estás seguro que deseas eliminar esta fórmula? Esta acción no se puede deshacer.
                        </p>
                    </div>
                    <div class="flex justify-center space-x-4 mt-4">
                        <button wire:click="deleteFormula" 
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                            Eliminar
                        </button>
                        <button wire:click="$set('showDeleteModal', false)" 
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>