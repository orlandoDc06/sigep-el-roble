<?php

namespace App\Livewire\Bonuses;

use Livewire\Component;
use App\Models\Bonus;
use Illuminate\Validation\Rule;

class BonusesForm extends Component
{
    public $bonus_id;
    public $name;
    public $description;
    public $default_amount;
    public $applies_to_all = false;
    public $is_percentage = false;
    public $is_editing = false;

    //Método para inicializar el componente
    public function mount($id = null)
    {
        //Condiciona si se está editando una bonificación existente
        if ($id) {
            $bonus = Bonus::findOrFail($id);
            $this->bonus_id = $bonus->id;
            $this->name = $bonus->name;
            $this->description = $bonus->description;
            $this->default_amount = $bonus->default_amount;
            $this->applies_to_all = $bonus->applies_to_all;
            $this->is_percentage = $bonus->is_percentage;
            $this->is_editing = true;
        }
    }

    //Método para las reglas de validación
    protected function rules()
    {
        //Retorna las reglas de validación
        return [
            'name' => ['required', 'string', Rule::unique('bonuses', 'name')->ignore($this->bonus_id)],
            'description' => 'nullable|string',
            'default_amount' => 'required|numeric|min:0',
            'applies_to_all' => 'boolean',
            'is_percentage' => 'boolean',
        ];
    }

    //Método para crear una nueva bonificación
    public function createBonus()
    {
        $this->validate();

        // Crear la bonificación e insetar en la bd
        Bonus::create([
            'name' => $this->name,
            'description' => $this->description,
            'default_amount' => $this->default_amount,
            'applies_to_all' => $this->applies_to_all,
            'is_percentage' => $this->is_percentage,
        ]);

        session()->flash('message', 'Bonificación creada con éxito.');
        return redirect()->route('bonuses.index');
    }

    //Funcion para actualizar una bonificación
    public function updateBonus()
    {
        $this->validate();

        //Actualizar la bonificación
        $bonus = Bonus::findOrFail($this->bonus_id);
        $bonus->update([
            'name' => $this->name,
            'description' => $this->description,
            'default_amount' => $this->default_amount,
            'applies_to_all' => $this->applies_to_all,
            'is_percentage' => $this->is_percentage,
        ]);

        session()->flash('message', 'Bonificación actualizada con éxito.');
        return redirect()->route('bonuses.index');
    }

    //Retornaar a la vista bonuses index 
    public function returnIndex()
    {
        return redirect()->route('bonuses.index');
    }

    //renderizar 
    public function render()
    {
        return view('livewire.bonuses.bonuses-form');
    }
}
