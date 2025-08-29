<div class="container mx-auto px-4 max-w-4xl">
    <div class="bg-white rounded-lg shadow-lg">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <h2 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-calculator mr-2 text-green-600"></i>
                {{ $isEdit ? 'Editar Fórmula' : 'Nueva Fórmula' }}
            </h2>
            <a href="{{ route('admin.formulas.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>

        <!-- Mensajes -->
        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mx-6 mt-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mx-6 mt-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Formulario -->
        <form wire:submit.prevent="save" class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Información Básica -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Información Básica</h3>
                    
                    <!-- Nombre -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre de la Fórmula *
                        </label>
                        <input type="text" 
                               wire:model="name"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('name') border-red-500 @enderror"
                               placeholder="Ej: Cálculo de Salario Base">
                        @error('name')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Tipo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tipo de Fórmula *
                        </label>
                        <select wire:model="type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('type') border-red-500 @enderror">
                            <option value="">Seleccionar tipo...</option>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Descripción
                        </label>
                        <textarea wire:model="description" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('description') border-red-500 @enderror"
                                  placeholder="Describe brevemente qué calcula esta fórmula..."></textarea>
                        @error('description')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Estado de Validación -->
                    @if($syntax_validated)
                        <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-md">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-green-700 font-medium">Sintaxis Validada</span>
                        </div>
                    @endif
                </div>

                <!-- Constructor de Fórmula -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Constructor de Fórmula</h3>
                    
                    <!-- Variables Disponibles -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Variables Disponibles</label>
                        <div class="grid grid-cols-2 gap-2 max-h-32 overflow-y-auto">
                            @foreach($variables as $key => $label)
                                <button type="button" 
                                        onclick="insertText('{{ $key }}')"
                                        class="text-left px-2 py-1 text-xs bg-blue-100 hover:bg-blue-200 rounded border">
                                    <div class="font-medium text-blue-800">{{ $key }}</div>
                                    <div class="text-blue-600">{{ $label }}</div>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Operadores -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Operadores</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($operators as $key => $label)
                                <button type="button" 
                                        onclick="insertText('{{ $key }}')"
                                        class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 rounded border"
                                        title="{{ $label }}">
                                    {{ $key }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expresión de la Fórmula -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Expresión de la Fórmula *
                </label>
                <textarea wire:model="expression" 
                          id="formula-expression"
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 font-mono text-sm @error('expression') border-red-500 @enderror"
                          placeholder="Ejemplo: salario_base + (horas_extra * valor_hora) - (salario_base * porcentaje_afp)"></textarea>
                @error('expression')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
                
                <!-- Botones de Acción para la Expresión -->
                <div class="flex space-x-2 mt-2">
                    <button type="button" 
                            wire:click="validateSyntax"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                        <i class="fas fa-check mr-1"></i>Validar Sintaxis
                    </button>
                    <button type="button" 
                            wire:click="previewFormula"
                            class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded text-sm">
                        <i class="fas fa-eye mr-1"></i>Vista Previa
                    </button>
                </div>
            </div>

            <!-- Preview Result -->
            @if($showPreview && $previewResult)
                <div class="mt-4 p-4 bg-purple-50 border border-purple-200 rounded-md">
                    <h4 class="font-medium text-purple-800 mb-2">Vista Previa del Resultado:</h4>
                    <p class="text-purple-700">{{ $previewResult }}</p>
                </div>
            @endif

            <!-- Botones de Acción -->
            <div class="flex justify-end space-x-4 mt-8 pt-4 border-t">
                <a href="{{ route('admin.formulas.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    {{ $isEdit ? 'Actualizar' : 'Guardar' }} Fórmula
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script para insertar texto en el textarea -->
<script>
function insertText(text) {
    const textarea = document.getElementById('formula-expression');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const value = textarea.value;
    
    textarea.value = value.substring(0, start) + text + value.substring(end);
    textarea.focus();
    textarea.setSelectionRange(start + text.length, start + text.length);
    
    // Trigger Livewire update
    textarea.dispatchEvent(new Event('input'));
}
</script>