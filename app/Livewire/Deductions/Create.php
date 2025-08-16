<?php

namespace App\Livewire\Deductions;

use App\Models\Deduction;
use Livewire\Component;
class Create extends Component
{        
    //propiedades públicas para vinculación de datos en el formulario
    public $name, $description, $default_amount, $applies_to_all = false, $is_percentage = false;

    //metodo para crear el descuento
    public function createDeduction()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'default_amount' => 'nullable|numeric',
            'applies_to_all' => 'nullable|boolean',
            'is_percentage' => 'nullable|boolean',
        ]);

        // Crear el descuento
        $deduction = new Deduction();
        $deduction->name = $this->name;
        $deduction->description = $this->description;
        $deduction->default_amount = $this->default_amount;
        $deduction->applies_to_all = $this->applies_to_all;
        $deduction->is_percentage = $this->is_percentage;
        $deduction->save();
        
        session()->flash('message', 'Descuento creado exitosamente.');
        return redirect()->route('deductions.index');
    }

    //metodo para redirigir a la lista de los descuentos
    public function returnIndexDeduction()
    {
        return redirect()->route('deductions.index');
    }

    //metodo para renderizar la vista
    public function render()
    {
        return view('livewire.deductions.create');
    }
}
