<?php

namespace App\Livewire\EmployeeDeductionsAssignments;

use Livewire\Component;
use App\Models\EmployeeDeductionAssignment;

class EmployeeDeductionAssignmentEdit extends Component
{
    // Definir propiedades públicas
    public $assignment;
    public $employee_id;
    public $deduction_id;
    public $amount;
    public $applied_at;
    public $notes;

    //Propiedades para mostrar información
    public $employee_name;
    public $deduction_name;

    //Array para las reglas de validación
    protected $rules = [
        'amount' => 'required|numeric|min:0',
        'notes' => 'nullable|string',
    ];

    // Método para inicializar el componente
    public function mount($id)
    {
        $this->assignment = EmployeeDeductionAssignment::findOrFail($id);

        // Datos del empleado y descuento solo para mostrar
        $this->employee_id = $this->assignment->employee_id;
        $this->deduction_id = $this->assignment->deduction_id;
        $this->employee_name = $this->assignment->employee->first_name . ' ' . $this->assignment->employee->last_name;
        $this->deduction_name = $this->assignment->deduction->name;

        // Campos editables
        $this->amount = $this->assignment->amount;
        $this->notes = $this->assignment->notes;
        $this->applied_at = $this->assignment->applied_at; // solo se muestra
    }

    // Método para actualizar la asignación
    public function update()
    {
        $this->validate();//LLama al metodo 

        $this->assignment->update([
            'amount' => $this->amount,
            'notes' => $this->notes,
        ]);

        //Mensajes 
        session()->flash('success', 'Asignación actualizada correctamente.');
        return redirect()->route('deductions-assignments.index');
    }

    //renderizar 
    public function render()
    {
        return view('livewire.employee-deductions-assignments.employee-deduction-assignment-edit');
    }
}
