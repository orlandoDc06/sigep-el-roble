<?php

namespace App\Livewire\Deductions;

use Livewire\Component;
use App\Models\Deduction;

class Edit extends Component
{
    // Propiedades
    public $deductionId, $name, $description, $default_amount, $applies_to_all, $is_percentage;

    //metodo para inicializar el componente
    public function mount($id)
    {
        $deduction = Deduction::findOrFail($id);
        $this->deductionId = $deduction->id;
        $this->name = $deduction->name;
        $this->description = $deduction->description;
        $this->default_amount = $deduction->default_amount;
        $this->applies_to_all = (bool)$deduction->applies_to_all;
        $this->is_percentage = (bool)$deduction->is_percentage;
    }

    //metodo para actualizar el descuento
    public function updateDeduction()
    {
        // Validar los datos
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'default_amount' => 'required|numeric|min:0',
            'applies_to_all' => 'boolean',
            'is_percentage' => 'boolean',
        ], [// Mensajes de error personalizados
            'name.required' => 'El nombre es obligatorio',
            'default_amount.required' => 'El monto es requerido',
            'default_amount.numeric' => 'El monto debe ser un número',
            'default_amount.min' => 'El monto no puede ser negativo'
        ]);

        // Actualizar el descuento
        $deduction = Deduction::findOrFail($this->deductionId);
        $deduction->name = $this->name;
        $deduction->description = $this->description;
        $deduction->default_amount = $this->default_amount;
        $deduction->applies_to_all = $this->applies_to_all;
        $deduction->is_percentage = $this->is_percentage;
        $deduction->save();

        session()->flash('success', 'Deducción actualizada exitosamente!');
        // Redirigir a la lista de deducciones
        return redirect()->route('deductions.index');
    }

    //metodo para cancelar la edicion
    public function cancel()
    {
        return redirect()->route('deductions.index');
    }

    //metodo para renderizar la vista
    public function render()
    {
        return view('livewire.deductions.edit');
    }
}