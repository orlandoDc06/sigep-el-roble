<?php
namespace App\Livewire\Branches;

use Livewire\Component;
use App\Models\Branch;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $branchId, $name, $address, $image_path, $branch;

    public function mount($id)
    {
        $branch = Branch::findOrFail($id);
        $this->branchId = $branch->id;
        $this->name = $branch->name;
        $this->address = $branch->address;
    }

    public function updateBranch()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'image_path' => 'nullable|image|max:1024',
        ]);

        $branch = Branch::findOrFail($this->branchId);

        if ($this->image_path) {
            $imagePath = $this->image_path->store('branches', 'public');
            $branch->image_path = $imagePath;
        }

        $branch->name = $this->name;
        $branch->address = $this->address;
        $branch->save();

        session()->flash('message', 'Sucursal actualizada exitosamente.');
        return redirect()->route('branches.index');
    }
    public function returnIndex()
    {
        return redirect()->route('branches.index');
    }
    public function render()
    {
        return view('livewire.branches.edit');
    }
}
