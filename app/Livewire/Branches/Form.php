<?php

namespace App\Livewire\Branches;

use App\Models\Branch;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;
    public $name, $address, $image_path;

    public function createBranch()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'image_path' => 'nullable|image|max:1024',
        ]);

        Branch::create([
            'name' => $this->name,
            'address' => $this->address,
            'image_path' => $this->image_path ? $this->image_path->store('branches', 'public') : null,

        ]);

        session()->flash('message', 'Sucursal creada exitosamente.');
        return redirect()->route('branches.index');
    }
    public function returnIndex()
    {
        return redirect()->route('branches.index');
    }
    public function render()
    {
        return view('livewire.branches.form');
    }
}
