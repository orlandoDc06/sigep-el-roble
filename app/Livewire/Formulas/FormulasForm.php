<?php

namespace App\Livewire\Formulas;

use Livewire\Component;
use App\Models\Formula;

class FormulasForm extends Component
{
    public $formulaId;
    public $name = '';
    public $type = '';
    public $expression = '';
    public $description = '';
    public $syntax_validated = false;
    
    public $isEdit = false;
    public $showPreview = false;
    public $previewResult = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required|in:salary,bonus,deduction,overtime,commission',
        'expression' => 'required|string',
        'description' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'name.required' => 'El nombre es obligatorio',
        'type.required' => 'El tipo es obligatorio',
        'expression.required' => 'La expresión es obligatoria',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $this->formulaId = $id;
            $this->isEdit = true;
            $this->loadFormula();
        }
    }

    public function loadFormula()
    {
        $formula = Formula::findOrFail($this->formulaId);
        $this->name = $formula->name;
        $this->type = $formula->type;
        $this->expression = $formula->expression;
        $this->description = $formula->description;
        $this->syntax_validated = $formula->syntax_validated;
    }

    public function validateSyntax()
    {
        try {
            // Aquí puedes implementar la validación de sintaxis
            // Por ahora, simulamos una validación básica
            if (empty($this->expression)) {
                throw new \Exception('La expresión no puede estar vacía');
            }
            
            // Verificar que contenga elementos básicos de una fórmula
            if (!preg_match('/[+\-*\/()]/', $this->expression)) {
                throw new \Exception('La expresión debe contener operadores matemáticos');
            }

            $this->syntax_validated = true;
            session()->flash('success', 'Sintaxis validada correctamente');
        } catch (\Exception $e) {
            $this->syntax_validated = false;
            session()->flash('error', 'Error en la sintaxis: ' . $e->getMessage());
        }
    }

    public function previewFormula()
    {
        try {
            // Simulación de preview con valores de ejemplo
            $sampleData = [
                'salario_base' => 500,
                'horas_extra' => 10,
                'valor_hora' => 5,
                'porcentaje_bono' => 0.15
            ];

            // Aquí implementarías la lógica de evaluación
            $this->previewResult = 'Ejemplo: Con salario base $500, el resultado sería aproximadamente $575';
            $this->showPreview = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Error en el preview: ' . $e->getMessage());
        }
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'type' => $this->type,
                'expression' => $this->expression,
                'description' => $this->description,
                'syntax_validated' => $this->syntax_validated,
            ];

            if ($this->isEdit) {
                $formula = Formula::findOrFail($this->formulaId);
                $formula->update($data);
                $message = 'Fórmula actualizada correctamente';
            } else {
                Formula::create($data);
                $message = 'Fórmula creada correctamente';
            }

            session()->flash('success', $message);
            return redirect()->route('admin.formulas.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $types = [
            'salary' => 'Salario',
            'bonus' => 'Bono',
            'deduction' => 'Descuento',
            'overtime' => 'Horas Extra',
            'commission' => 'Comisión'
        ];

        $operators = [
            '+' => 'Suma',
            '-' => 'Resta',
            '*' => 'Multiplicación',
            '/' => 'División',
            '()' => 'Paréntesis',
            'IF' => 'Condicional',
            'MAX' => 'Máximo',
            'MIN' => 'Mínimo'
        ];

        $variables = [
            'salario_base' => 'Salario Base',
            'horas_trabajadas' => 'Horas Trabajadas',
            'horas_extra' => 'Horas Extra',
            'dias_trabajados' => 'Días Trabajados',
            'antiguedad_anos' => 'Años de Antigüedad',
            'porcentaje_afp' => 'Porcentaje AFP',
            'porcentaje_isss' => 'Porcentaje ISSS'
        ];

        return view('livewire.formulas.formulas-form', compact('types', 'operators', 'variables'))
            ->layout('layouts.app', ['titulo' => $this->isEdit ? 'Editar Fórmula' : 'Nueva Fórmula']);
    }
}