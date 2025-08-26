<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\JustifiedAbsence;
use Illuminate\Support\Facades\Auth;
class JustifiedAbsenceList extends Component
{

    public $absences;
    public $search = '';

    public function mount()
    {
        $this->loadAbsences();
    }

    public function loadAbsences()
    {
        $query = JustifiedAbsence::with('employee');

        // Si hay texto en búsqueda
        if ($this->search) {
        $query->whereHas('employee', function ($q) {
            $q->where('first_name', 'ilike', '%' . $this->search . '%')
            ->orWhere('last_name', 'ilike', '%' . $this->search . '%')
            ->orWhereRaw("CONCAT(first_name, ' ', last_name) ILIKE ?", ['%' . $this->search . '%'])
            ->orWhere('dui', 'ilike', '%' . $this->search . '%');
        });
        }


        $query->latest();
        $this->absences = $query->get();
    }

    public function updateStatus($id, $status)
    {
        $absence = JustifiedAbsence::findOrFail($id);
        $absence->update([
            'status' => $status,
            'approved_by' => Auth::id(),
        ]);

        $this->loadAbsences();
        session()->flash('success', "Solicitud marcada como $status.");
    }

    // Método para aplicar búsqueda
    public function applySearch()
    {
        $this->loadAbsences();
    }

    // Método para resetear búsqueda
    public function resetSearch()
    {
        $this->search = '';
        $this->loadAbsences();
    }

    public function render()
    {
        return view('livewire.admin.justified-absence-list');
    }
}
