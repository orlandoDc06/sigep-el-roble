<?php

namespace App\Livewire\Bonuses;

use Livewire\Component;
use App\Models\Bonus;
use App\Models\EmployeeBonusAssignment;

class BonusesIndex extends Component
{
    public $bonuses;
    public $search = '';
    public $confirmingDeletion = false;
    public $bonusToDelete;

    // Variables para el modal de información
    public $infoModal = false;
    public $infoMessage = '';

    // Arreglo para los listeners
    protected $listeners = ['bonusCreated' => 'loadBonuses', 'bonusUpdated' => 'loadBonuses'];

    // Método para inicializar el componente
    public function mount()
    {
        $this->loadBonuses();
    }

    // Método para cargar todos los bonos (con filtro si hay búsqueda)
    public function loadBonuses()
    {
        $this->bonuses = Bonus::query()
            ->when($this->search != '', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name', 'asc')
            ->get();
    }

    // Método para aplicar búsqueda
    public function applySearch()
    {
        $this->loadBonuses();
    }

    // Método para limpiar búsqueda
    public function resetSearch()
    {
        $this->search = '';
        $this->loadBonuses();
    }

    // Confirmar eliminación
    public function confirmDelete($id)
    {
        $this->bonusToDelete = $id;
        $this->confirmingDeletion = true;
    }

    // Eliminar bono con validación
    public function deleteConfirmed()
    {
        $bonus = Bonus::findOrFail($this->bonusToDelete);

        $assignedCount = EmployeeBonusAssignment::where('bonus_id', $bonus->id)->count();

        if ($bonus->applies_to_all || $assignedCount > 0) {
            // Mostrar alerta y no eliminar
            $this->infoMessage = 'No es posible eliminar este bono porque está asignado a empleados o aplicado a todos.';
            $this->infoModal = true;
        } else {
            // Eliminar bono
            $bonus->delete();
            session()->flash('success', 'Bonificación eliminada con éxito.');
        }

        // Resetear modal de confirmación
        $this->confirmingDeletion = false;
        $this->bonusToDelete = null;

        $this->loadBonuses();
    }

    // Redirigir a edición
    public function editBonus($id)
    {
        return redirect()->route('bonuses.edit', ['id' => $id]);
    }

    //Funcion para retornar la vista con los bonos
    public function render()
    {
        return view('livewire.bonuses.bonuses-index', [
            'bonuses' => $this->bonuses,
        ]);
    }
}
