<?php

namespace App\Livewire\Employees\JustifiedAbsence;

use Livewire\Component;
use App\Models\JustifiedAbsence;
use Illuminate\Support\Facades\Auth;

class JustifiedAbsenceManager extends Component
{

    public $date;
    public $reason;
    public $justifiedAbsences;

    public function mount()
    {
        $this->justifiedAbsences = Auth::user()->employee->justifiedAbsences()->latest()->get();
    }


    public function submit(){
        $this->validate([
            'date' => 'required|date',
            'reason' => 'required|string|max:1000',
        ]);

        $absence = JustifiedAbsence::create([
            'employee_id' => Auth::user()->employee->id,
            'date' => $this->date,
            'reason' => $this->reason,
            'status' => 'pendiente',
            'approved_by' => null,
        ]);

        session()->flash('message', 'Justified absence request submitted successfully.');

        $this->reset(['date', 'reason']);

        $this->reset(['date', 'reason']);

        $this->justifiedAbsences->prepend($absence);
    }

    public function render()
    {
        return view('livewire.employees.justified-absence.justified-absence-manager');
    }
}
