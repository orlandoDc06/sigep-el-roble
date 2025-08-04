<?php

namespace App\Livewire\Branches;

use Livewire\Component;
use App\Models\Branch;
use App\Livewire\Branches;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    //propiedades 
    public $branches, $search = '';

    //metodo para mostrar los componentes 
    public function mount()
    {
        $this->branches = Branch::all(); //all para mostrar todos
    }

    //metodo para eliminar la sucursal sleccionada
    public function deleteBranch($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        session()->flash('message', 'Sucursal eliminada con éxito.');
        $this->dispatch('branchDeleted');
        redirect()->route('branches.index'); //recarga la vista actual
    }

    // metodo para redirigir al formulario de edición
    public function editBranch($id)
    {
        return redirect()->route('branches.edit', ['id' => $id]);
    }

    public function render()
    {
        //busqueda filtrada por nombre o dirección
        $branches = Branch::where(function($query) {
            $query->where('name', 'ilike', '%' . $this->search . '%')
                ->orWhere('address', 'ilike', '%' . $this->search . '%');
        })->get();

        return view('livewire.branches.index', [
            'branches' => $branches,
        ]);
    }

    //metodo para aplicar la busqueda (se aplica al presionar ENTER o al darle click al boton de buscar)
    public function applySearch()
    {
        $this->branches = Branch::where('name', 'ilike', '%' . $this->search . '%')
            ->orWhere('address', 'ilike', '%' . $this->search . '%')
            ->get();
    }

    //metodo para limpiar la busqueda
    public function resetSearch()
    {
        $this->search = '';
        $this->branches = Branch::all();
    }

}
