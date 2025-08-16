<?php

namespace App\Livewire\Deductions;

use App\Models\Deduction;
use Livewire\Component;

class Index extends Component
{
     //propiedades 
    public $deductions, $search = '';

    //metodo para mostrar los componentes 
    public function mount()
    {
        $this->deductions = Deduction::all(); //all para mostrar todos
    }

    //metodo para eliminar el descuento seleccionado
    public function deleteDeduction($id)
    {
        $deduction = Deduction::findOrFail($id);
        $deduction->delete();

        session()->flash('message', 'Deducción eliminada con éxito.');
        $this->dispatch('deductionDeleted');
        redirect()->route('deductions.index'); //recarga la vista actual
    }

    // metodo para redirigir al formulario de edición
    public function editDeduction($id)
    {
        return redirect()->route('deductions.edit', ['id' => $id]);
    }

    public function render()
    {
        //busqueda filtrada 
            $deductions = Deduction::where(function($query) {
            $query->where('name', 'ilike', '%' . $this->search . '%');
            $query->orWhere('description', 'ilike', '%' . $this->search . '%');
            $query->orWhere('default_amount', 'ilike', '%' . $this->search . '%');
            $query->orWhere('applies_to_all', 'ilike', '%' . $this->search . '%');
        })->get();

        return view('livewire.deductions.index', [
            'deductions' => $deductions,
        ]);
    }

    //metodo para aplicar la busqueda (se aplica al presionar ENTER o al darle click al boton de buscar)
    public function applySearch()
    {
        $this->deductions = Deduction::where('name', 'ilike', '%' . $this->search . '%')
            ->orWhere('description', 'ilike', '%' . $this->search . '%')
            ->orWhere('default_amount', 'ilike', '%' . $this->search . '%')
            ->orWhere('applies_to_all', 'ilike', '%' . $this->search . '%')
            ->orWhere('is_percentage', 'ilike', '%' . $this->search . '%')
            ->get();
    }

    //metodo para limpiar la busqueda
    public function resetSearch()
    {
        $this->search = '';
        $this->deductions = Deduction::all();
    }
}
