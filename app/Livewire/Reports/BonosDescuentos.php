<?php namespace App\Livewire\Reports;
use Livewire\Component;
use App\Models\Bonus;
use App\Models\Deduction;
class BonosDescuentos extends Component
{
    public $bonuses;
    public $deductions;
    public function mount()
    { // Cargar toda la info de bonos y descuentos
        $this->bonuses = Bonus::all();
        $this->deductions = Deduction::all();
    }
    public function render()
    {
        return view('livewire.reports.bonos-descuentos');
    }
}
