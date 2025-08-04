<?php

namespace App\Livewire\Branches;

use Livewire\Component;
use App\Models\Branch;
use App\Livewire\Branches;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    public $branches, $search = '';

    public function mount()
    {
        $this->branches = Branch::all();
    }

    public function deleteBranch($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        session()->flash('message', 'Sucursal eliminada con éxito.');
        $this->dispatch('branchDeleted');
        redirect()->route('branches.index');
    }

    public function editBranch($id)
    {
        return redirect()->route('branches.edit', ['id' => $id]);
    }

    public function render()
    {
        $branches = Branch::where(function($query) {
            $query->where('name', 'ilike', '%' . $this->search . '%')
                ->orWhere('address', 'ilike', '%' . $this->search . '%');
        })->get();

        return view('livewire.branches.index', [
            'branches' => $branches,
        ]);
    }
    
    public function applySearch()
    {
        $this->branches = Branch::where('name', 'ilike', '%' . $this->search . '%')
            ->orWhere('address', 'ilike', '%' . $this->search . '%')
            ->get();
    }

    public function resetSearch()
    {
        $this->search = '';
        $this->branches = Branch::all();
    }

}
