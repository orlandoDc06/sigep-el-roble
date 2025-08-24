<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\JustifiedAbsence;
use Illuminate\Support\Facades\Auth;
class JustifiedAbsenceList extends Component
{

    public $absences;

    public function mount()
    {
        $this->absences = JustifiedAbsence::with('employee')->latest()->get();
    }

    public function updateStatus($id, $status)
    {
        $absence = JustifiedAbsence::findOrFail($id);
        $absence->update([
            'status' => $status,
            'approved_by' => Auth::id(),
        ]);

        $this->absences = JustifiedAbsence::with('employee')->latest()->get();
        session()->flash('success', "Solicitud marcada como $status.");
    }

    public function render()
    {
        return view('livewire.admin.justified-absence-list');
    }
}
