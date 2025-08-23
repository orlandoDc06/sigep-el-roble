<?php

namespace App\Livewire\EmployeeDeductionsAssignments;

use Livewire\Component;
use App\Models\EmployeeDeductionAssignment;

class EmployeeDeductionAssignmentIndex extends Component
{
     public $assignments;
    public $assignmentIdBeingUpdated = null; // ID de la asignación
    public $confirmingStatusChange = false;  // controla el modal
    public $search = '';

    protected $listeners = ['bonus-assignment-created' => 'loadAssignments'];

    // Inicializa el componente
    public function mount()
    {
        $this->loadAssignments();
    }

    // Cargar asignaciones
    public function loadAssignments()
    {
        $this->assignments = EmployeeDeductionAssignment::with(['employee', 'deduction', 'assignedBy'])
            ->orderBy('applied_at', 'desc')
            ->get();
    }

    // Abrir el modal para confirmar cambio de estado
    public function confirmStatusChange($id)
    {
        $this->assignmentIdBeingUpdated = $id;
        $this->confirmingStatusChange = true;
    }

    // Cambiar estado: activo <-> suspend
    public function changeStatus()
    {
        $assignment = EmployeeDeductionAssignment::findOrFail($this->assignmentIdBeingUpdated);

        $assignment->status = $assignment->status === 'active' ? 'suspend' : 'active';
        $assignment->save();

        $this->confirmingStatusChange = false;
        $this->assignmentIdBeingUpdated = null;

        session()->flash('success', 'Estado actualizado correctamente.');
        $this->loadAssignments();
    }

    // Aplicar búsqueda filtrada
    public function applySearch()
    {
        $this->assignments = EmployeeDeductionAssignment::with(['employee', 'deduction', 'assignedBy'])
            ->whereHas('employee', function($q) {
                $q->where('first_name', 'ilike', '%' . $this->search . '%')
                  ->orWhere('last_name', 'ilike', '%' . $this->search . '%');
            })
            ->orWhereHas('deduction', function($q) {
                $q->where('name', 'ilike', '%' . $this->search . '%');
            })
            ->orderBy('applied_at', 'desc')
            ->get();
    }

    // Resetear búsqueda
    public function resetSearch()
    {
        $this->search = '';
        $this->loadAssignments();
    }

    //renderizar a las asignnaciones 
    public function render()
    {
        return view('livewire.employee-deductions-assignments.employee-deduction-assignment-index', [
            'assignments' => $this->assignments,
        ]);
    }
}
