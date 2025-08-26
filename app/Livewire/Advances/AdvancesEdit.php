<?php

namespace App\Livewire\Advances;

use Livewire\Component;
use App\Models\Advance;

class AdvancesEdit extends Component
{
    public $advance_id;
    public $employee_name;
    public $amount;
    public $date;
    public $reason;

    // monto máximo permitido
    public $max_amount = 500; 

    // Reglas de validación
    protected $rules = [
        'amount' => 'required|numeric|min:0.01',
        'date' => 'required|date',
        'reason' => 'nullable|string|max:500',
    ];

    // Inicializar datos
    public function mount($id)
    {
        //Consulta para obtener el anticipo  por id
        $advance = Advance::findOrFail($id);

        $this->advance_id = $advance->id;
        $this->employee_name = $advance->employee->first_name . ' ' . $advance->employee->last_name;
        $this->amount = $advance->amount;
        $this->date = $advance->date;
        $this->reason = $advance->reason;
    }

    // Reglas con validación de monto máximo
    private function rulesWithMaxAmount()
    {
        $rules = $this->rules;

        //condicionar que el monto no exceda el máximo permitido
        if (is_string($rules['amount'])) {
            $rules['amount'] = explode('|', $rules['amount']);
        }

        $rules['amount'][] = function ($attribute, $value, $fail) {
            if ($value > $this->max_amount) {
                $fail("El monto máximo permitido es $" . number_format($this->max_amount, 2));
            }
        };

        return $rules;
    }

    // Actualizar anticipo
    public function updateAdvance()
    {
        $this->validate($this->rulesWithMaxAmount());

        $advance = Advance::findOrFail($this->advance_id);
        $advance->update([
            'amount' => $this->amount,
            'date' => $this->date,
            'reason' => $this->reason,
        ]);

        session()->flash('message', 'Anticipo actualizado correctamente.');
        return redirect()->route('advances.index');
    }

    // Renderizar vista
    public function render()
    {
        return view('livewire.advances.advances-edit');
    }
}
