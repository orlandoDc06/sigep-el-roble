<?php

namespace App\Livewire\Advances;

use Livewire\Component;
use App\Models\Advance;
use App\Models\Employee;
use Livewire\WithPagination;

class AdvancesIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $employees;
    public $filterMonth = 'all';

    protected $paginationTheme = 'tailwind';

    // Modal de confirmación para cambio de estado
    public $confirmingStatusChange = false;
    public $advanceIdBeingUpdated = null;

    // Modal informativo
    public $infoModal = false;
    public $infoMessage = '';

    // Metodo para Inicializar componente
    public function mount()
    {
        $this->employees = Employee::all();
    }

    // Metodo para Aplicar búsqueda
    public function applySearch()
    {
        $this->resetPage();
    }

    // Metodo para Reiniciar búsqueda
    public function resetSearch()
    {
        $this->search = '';
        $this->filterMonth = 'all';
        $this->resetPage();
    }

    // Metodo para Renderizar la vista
    public function render()
    {
        $query = Advance::with('employee');

        // Buscar por nombre
        if ($this->search) {
            $query->whereHas('employee', function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%');
            });
        }

        // Filtrar por mes
        if ($this->filterMonth !== 'all') {
            $query->whereMonth('date', $this->filterMonth);
        }

        $advances = $query->latest()->paginate(10);

        return view('livewire.advances.advances-index', compact('advances'));
    }

    // Metodo para Confirmar cambio de estado
    public function confirmStatusChange($id)
    {
        $this->advanceIdBeingUpdated = $id;
        $this->confirmingStatusChange = true;
    }

    // Metodo para Cambiar estado (activar o suspender)
    public function changeStatus()
    {
        // Cambiar estado según la acción
        $advance = Advance::findOrFail($this->advanceIdBeingUpdated);

        // Cambiar estado
        $advance->status = $advance->status === 'active' ? 'suspend' : 'active';
        $advance->save();

        session()->flash('success', 'Estado actualizado correctamente.');

        $this->confirmingStatusChange = false;
        $this->advanceIdBeingUpdated = null;
        $this->resetPage();
    }
}
