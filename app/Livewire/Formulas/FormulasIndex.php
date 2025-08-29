<?php

namespace App\Livewire\Formulas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Formula;

class FormulasIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedType = '';
    public $showDeleteModal = false;
    public $formulaToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedType' => ['except' => '']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedType()
    {
        $this->resetPage();
    }

    public function confirmDelete($formulaId)
    {
        $this->formulaToDelete = $formulaId;
        $this->showDeleteModal = true;
    }

    public function deleteFormula()
    {
        if ($this->formulaToDelete) {
            Formula::find($this->formulaToDelete)->delete();
            $this->showDeleteModal = false;
            $this->formulaToDelete = null;
            session()->flash('success', 'Fórmula eliminada correctamente');
        }
    }

    public function toggleValidation($formulaId)
    {
        $formula = Formula::find($formulaId);
        if ($formula) {
            $formula->update(['syntax_validated' => !$formula->syntax_validated]);
            session()->flash('success', 'Estado de validación actualizado');
        }
    }

    public function duplicate($formulaId)
    {
        $formula = Formula::find($formulaId);
        if ($formula) {
            $newFormula = $formula->replicate();
            $newFormula->name = $formula->name . ' (Copia)';
            $newFormula->syntax_validated = false;
            $newFormula->save();
            session()->flash('success', 'Fórmula duplicada correctamente');
        }
    }

    public function render()
    {
        $formulas = Formula::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('expression', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedType, function($query) {
                $query->where('type', $this->selectedType);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $types = [
            'salary' => 'Salario',
            'bonus' => 'Bono',
            'deduction' => 'Descuento',
            'overtime' => 'Horas Extra',
            'commission' => 'Comisión'
        ];

        return view('livewire.formulas.formulas-index', compact('formulas', 'types'))
            ->layout('layouts.app', ['titulo' => 'Gestión de Fórmulas']);
    }
}