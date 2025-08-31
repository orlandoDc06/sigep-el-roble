<?php

namespace App\Livewire\SpecialDays;

use Livewire\Component;
use App\Models\SpecialDay;
use Carbon\Carbon;

class Create extends Component
{
    public $name, $date, $is_paid = true, $recurring = true;
    // Reglas de validación
    protected $rules = [
        'name' => 'required|string|max:255',
        'date' => 'required|date',
        'is_paid' => 'boolean',
        'recurring' => 'boolean', 
    ];
    // Mensajes de error personalizados
    protected $messages = [
        'name.required' => 'El nombre del día festivo es obligatorio.',
        'date.required' => 'La fecha es obligatoria.',
        'date.date' => 'La fecha debe ser válida.',
    ];

    // Método para inicializar valores
    public function mount()
    {
        $this->date = now()->format('Y-m-d');
    }
    // Método para guardar el día festivo
    public function save()
    {
        $this->validate();

        try { // Intentar crear el día festivo
            SpecialDay::create([
                'name' => $this->name,
                'date' => $this->date,
                'is_paid' => $this->is_paid,
                'recurring' => $this->recurring, 
            ]);
            
            session()->flash('success', 'Día festivo creado exitosamente.');
            return redirect()->route('special-days.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el día festivo: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.special-days.create');
    }
}