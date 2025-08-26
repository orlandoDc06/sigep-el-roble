<?php

namespace App\Livewire\Advances;

use Livewire\Component;
use App\Models\Advance;
use App\Models\Employee;
use Livewire\WithPagination;

class AdvancesIndex extends Component
{
    //Usa la 
    use WithPagination;

    //Variables para la búsqueda y filtros
    public $search = '';
    public $employees;
    public $filterMonth = 'all';

    protected $paginationTheme = 'tailwind';

    public $confirmingDeletion = false;
    public $advanceToDelete = null;

    // Para modal informativo si no se puede eliminar
    public $infoModal = false;
    public $infoMessage = '';


    //Metodo para inicializar los datos
    public function mount()
    {
        // Obtener todos los empleados
        $this->employees = Employee::all();
    }

    //Metodo para aplicar la búsqueda
    public function applySearch()
    {
        $this->resetPage();
    }

    //Metodo para reiniciar la búsqueda
    public function resetSearch()
    {
        $this->search = '';
        $this->filterStatus = 'all';
        $this->resetPage();
    }

    //Metodo para renderizar la vista
    public function render()
    {
        // Crear la consulta base para los anticipos
        $query = Advance::with('employee');

        // Buscar por nombre de empleado
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

        // Paginación para los anticipos 
        $advances = $query->latest()->paginate(10);

        return view('livewire.advances.advances-index', compact('advances'));
    }

    // Confirmar eliminación
    public function confirmDelete($id)
    {
        $this->advanceToDelete = $id;
        $this->confirmingDeletion = true;
    }

    // Eliminar anticipo
    public function deleteConfirmed()
    {
        $advance = Advance::findOrFail($this->advanceToDelete);

        // No eliminar si ya fue aprobado
        if ($advance->approved_by) {
            $this->infoMessage = "No se puede eliminar un anticipo que ya fue aprobado.";
            $this->infoModal = true;
        } else {
            $advance->delete();
            session()->flash('success', 'Anticipo eliminado correctamente.');
        }

        $this->confirmingDeletion = false;
        $this->advanceToDelete = null;

        $this->resetPage(); // Recarga los anticipos
    }

}
