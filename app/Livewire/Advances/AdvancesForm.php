<?php

namespace App\Livewire\Advances;

use Livewire\Component;
use App\Models\Advance;
use App\Models\Employee;
use App\Models\Deduction;
use App\Models\EmployeeDeductionAssignment;
use Illuminate\Support\Facades\Auth;

class AdvancesForm extends Component
{
    public $employee_id;
    public $amount;
    public $date;
    public $reason;
    public $is_editing = false;
    public $advance_id;

    // Monto max permitido por anticipo
    public $max_amount = 500;

    // Reglas base de validación
    protected $rules = [
        'employee_id' => 'required|exists:employees,id',
        'amount' => 'required|numeric|min:0.01',
        'date' => 'required|date',
        'reason' => 'nullable|string|max:500',
    ];

    public function mount($advance = null)
    {
        // Si se pasa un anticipo, cargar sus datos para edición
        if ($advance) {
            $this->is_editing = true;
            $this->advance_id = $advance->id;
            $this->employee_id = $advance->employee_id;
            $this->amount = $advance->amount;
            $this->date = $advance->date;
            $this->reason = $advance->reason;
        } else {
            // fecha por defecto hoy
            $this->date = now()->format('Y-m-d'); 
        }
    }

    // Crear anticipo
    public function createAdvance()
    {
        // Validar los datos con las reglas que incluyen el monto máximo
        $this->validate($this->rulesWithMaxAmount());

        // Crear el anticipo (Advance)
        $advance =Advance::create([
            'employee_id' => $this->employee_id,
            'amount' => $this->amount,
            'date' => $this->date,
            'reason' => $this->reason,
            'approved_by' => Auth::id(),
        ]);

        // Mensaje de éxito
        session()->flash('message', 'Anticipo registrado correctamente.');
        return redirect()->route('advances.index');
    }

    // Redirigir al índice
    public function returnIndex()
    {
        return redirect()->route('advances.index');
    }

    // Renderizar vista
    public function render()
    {
        return view('livewire.advances.advances-form', [
            'employees' => Employee::all(),
        ]);
    }

    // Reglas dinámicas incluyendo monto máximo
    private function rulesWithMaxAmount()
    {
       $rules = $this->rules;

        // Asegurarte de que amount sea un array
        if (is_string($rules['amount'])) {
            $rules['amount'] = explode('|', $rules['amount']);
        }

        // Validar monto máximo
        $rules['amount'][] = function($attribute, $value, $fail) {
            if ($value > $this->max_amount) {
                $fail("El monto máximo permitido es $" . number_format($this->max_amount, 2));
            }
        };

        // Retornar las reglas
        return $rules;
    }
}
