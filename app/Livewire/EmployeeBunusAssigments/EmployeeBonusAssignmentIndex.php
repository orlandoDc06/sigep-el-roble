<?php

namespace App\Livewire\EmployeeBunusAssigments;

use Livewire\Component;
use App\Models\EmployeeBonusAssignment;

class EmployeeBonusAssignmentIndex extends Component
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
        $this->assignments = EmployeeBonusAssignment::with(['employee', 'bonus', 'assignedBy'])
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
        $assignment = EmployeeBonusAssignment::findOrFail($this->assignmentIdBeingUpdated);

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
        $this->assignments = EmployeeBonusAssignment::with(['employee', 'bonus', 'assignedBy'])
            ->whereHas('employee', function($q) {
                $q->where('first_name', 'ilike', '%' . $this->search . '%')
                  ->orWhere('last_name', 'ilike', '%' . $this->search . '%');
            })
            ->orWhereHas('bonus', function($q) {
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
        return view('livewire.employee-bunus-assigments.employee-bonus-assignment-index', [
            'assignments' => $this->assignments,
        ]);
    }
}
