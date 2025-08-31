<?php

namespace App\Livewire\EmployeeBunusAssigments;

use Livewire\Component;
use App\Models\EmployeeBonusAssignment;
use App\Models\Employee;
use App\Models\Bonus;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployeeBonusAssignmentForm extends Component
{
    public $employee_id;
    public $bonus_id;
    public $amount;
    public $applied_at;
    public $notes;

    public $employees = [];
    public $bonuses = [];

    //Array para las reglas de validación
    protected $rules = [
        'employee_id' => 'required|exists:employees,id',
        'bonus_id' => 'required|exists:bonuses,id',
        'amount' => 'required|numeric|min:0',
        'notes' => 'nullable|string',
    ];

    //Función para inicializar el componente
    public function mount()
    {
        $this->employees = Employee::all();
        $this->bonuses = Bonus::all();
        $this->applied_at = Carbon::now()->format('Y-m-d'); // Fecha actual
    }

    // Método para guardar la asignación
    public function save()
    {
        $this->validate();

        // Crear la asignación insetar en la bd
        EmployeeBonusAssignment::create([
            'employee_id' => $this->employee_id,
            'bonus_id' => $this->bonus_id,
            'amount' => $this->amount,
            'applied_at' => $this->applied_at, // se usa la fecha actual
            'notes' => $this->notes,
            'assigned_by' => Auth::id(),
        ]);

        session()->flash('success', 'Bonificación asignada con éxito.');

        $this->reset(['employee_id', 'bonus_id', 'amount', 'notes']);
        $this->applied_at = Carbon::now()->format('Y-m-d'); // Resetear la fecha también

        $this->dispatch('bonus-assignment-created');

        redirect()->route('bonuses-assignments.index');
    }

    //renderizar
    public function render()
    {
        return view('livewire.employee-bunus-assigments.employee-bonus-assignment-form');
    }
}
