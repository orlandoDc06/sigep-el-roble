<?php
namespace App\Livewire\Branches;

use Livewire\Component;
use App\Models\Branch;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;
    //variables utilizadas en el formulario
    public $branchId, $name, $address, $image_path, $branch, $original_image_path;

    //carga los datos de la sucursal seleccionada
    public function mount($id)
    {
        $branch = Branch::findOrFail($id);
        
        $this->branchId = $branch->id;
        $this->name = $branch->name;
        $this->address = $branch->address;
        $this->original_image_path = $branch->image_path; //guarda la imagen original para mostrarla si no se sube otra

    }
    //actualiza los datos de la sucursal
    public function updateBranch()
    {   //valida los datos
        $this->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'image_path' => 'nullable|image|max:1024',
        ]);

        $branch = Branch::findOrFail($this->branchId);
        //almacena la nueva imagen 
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

    //metodo para cancelar y volver al índice sin hacer cambios
    public function returnIndex()
    {
        return redirect()->route('branches.index');
    }

    public function render()
    {
        return view('livewire.branches.edit');
    }
}
