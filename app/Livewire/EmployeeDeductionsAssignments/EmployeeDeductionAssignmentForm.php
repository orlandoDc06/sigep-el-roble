<?php

namespace App\Livewire\EmployeeDeductionsAssignments;

use Livewire\Component;
use App\Models\EmployeeDeductionAssignment;
use App\Models\Employee;
use App\Models\Deduction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployeeDeductionAssignmentForm extends Component
{
    public $employee_id;
    public $deduction_id;
    public $amount;
    public $applied_at;
    public $notes;

    public $employees = [];
    public $deductions = [];

    //Array para las reglas de validación
    protected $rules = [
        'employee_id' => 'required|exists:employees,id',
        'deduction_id' => 'required|exists:deductions,id',
        'amount' => 'required|numeric|min:0',
        'notes' => 'nullable|string',
    ];

    //Función para inicializar el componente
    public function mount()
    {
        $this->employees = Employee::all();
        $this->deductions = Deduction::all();
        $this->applied_at = Carbon::now()->format('Y-m-d'); // Fecha actual
    }

    // Método para guardar la asignación
    public function save()
    {
        $this->validate();

        // Crear la asignación insetar en la bd
        EmployeeDeductionAssignment::create([
            'employee_id' => $this->employee_id,
            'deduction_id' => $this->deduction_id,
            'amount' => $this->amount,
            'applied_at' => $this->applied_at, // se usa la fecha actual
            'notes' => $this->notes,
            'assigned_by' => Auth::id(),
        ]);

        session()->flash('success', 'Descuento asignado con éxito.');

        $this->reset(['employee_id', 'deduction_id', 'amount', 'notes']);
        $this->applied_at = Carbon::now()->format('Y-m-d'); // Resetear la fecha también

        $this->dispatch('deduction-assignment-created');

        redirect()->route('deductions-assignments.index');
    }

    public function emit()
    {
        $this->dispatch('deduction-assignment-created');
    }

    //renderizar
    public function render()
    {
        return view('livewire.employee-deductions-assignments.employee-deduction-assignment-form');
    }
}
