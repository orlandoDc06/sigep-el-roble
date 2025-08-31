<?php

namespace App\Livewire\Branches;

use App\Models\Branch;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;
    //propiedades públicas para vinculación de datos en el formulario
    public $name, $address, $image_path;

    //metodo para crear una nueva sucursal
    public function createBranch()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'image_path' => 'nullable|image|max:1024',
        ]);

        //crea el registro en la base de datos
        Branch::create([
            'name' => $this->name,
            'address' => $this->address,
            'image_path' => $this->image_path ? $this->image_path->store('branches', 'public') : null, //si hay imagen se guarda, sino se guarda como NULL

        ]);

        session()->flash('message', 'Sucursal creada exitosamente.');
        return redirect()->route('branches.index');
    }

    //metodo para cancelar y volver al índice sin hacer cambios
    public function returnIndex()
    {
        return redirect()->route('branches.index');
    }

    public function render()
    {
        return view('livewire.branches.form');
    }

    //metodo para eliminar imagen
    public function removeImage(){
        $this->image_path = null;
    }
}
