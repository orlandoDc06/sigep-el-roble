<?php

namespace App\Livewire\Advances;

use Livewire\Component;
use App\Models\Advance;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class AdvancesEdit extends Component
{
    public $advance_id;
    public $employee_id;
    public $amount;
    public $date;
    public $reason;

    public $max_amount = 500; // monto máximo permitido

    //Reglas de validación
    protected $rules = [
        'employee_id' => 'required|exists:employees,id',
        'amount' => 'required|numeric|min:0.01',
        'date' => 'required|date',
        'reason' => 'nullable|string|max:500',
    ];

    //Metodo para inicializar los datos
    public function mount($id)
    {
        // Inicializar los datos
        $advance = Advance::findOrFail($id);

        // Asignar los valores a las propiedades
        $this->advance_id = $advance->id;
        $this->employee_id = $advance->employee_id;
        $this->amount = $advance->amount;
        $this->date = $advance->date;
        $this->reason = $advance->reason;
    }

    //Metodo para las reglas de validación
    private function rulesWithMaxAmount()
    {
        $rules = $this->rules;

        // Agregar validación para el monto máximo
        if (is_string($rules['amount'])) {
            $rules['amount'] = explode('|', $rules['amount']);
        }

        // Validar monto máximo
        $rules['amount'][] = function($attribute, $value, $fail) {
            if ($value > $this->max_amount) {
                $fail("El monto máximo permitido es $" . number_format($this->max_amount, 2));
            }
        };

        return $rules;
    }

    //Metodo para actualizar el anticipo
    public function updateAdvance()
    {
        // Validar los datos con las reglas definidas
        $this->validate($this->rulesWithMaxAmount());

        // Actualizar el anticipo
        $advance = Advance::findOrFail($this->advance_id);
        $advance->update([
            'employee_id' => $this->employee_id,
            'amount' => $this->amount,
            'date' => $this->date,
            'reason' => $this->reason,
        ]);

        // Mostrar mensaje de éxito
        session()->flash('message', 'Anticipo actualizado correctamente.');
        return redirect()->route('advances.index');
    }

    //Metodo para renderizar la vista
    public function render()
    {
        return view('livewire.advances.advances-edit', [
            // Obtener todos los empleados
            'employees' => Employee::all(),
        ]);
    }
}
