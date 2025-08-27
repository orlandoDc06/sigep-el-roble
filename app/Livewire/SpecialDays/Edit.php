<?php

namespace App\Livewire\SpecialDays;

use Livewire\Component;
use App\Models\SpecialDay;

class Edit extends Component
{
    public $specialDayId, $specialDay, $name, $date, $is_paid, $recurring;
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
    public function mount($specialDayId)
    {
        $this->specialDayId = $specialDayId;
        $this->loadSpecialDay();
    }

    // Método para cargar el día festivo
    protected function loadSpecialDay()
    {
        $this->specialDay = SpecialDay::find($this->specialDayId);
        // Verificar si el día festivo existe
        if (!$this->specialDay) {
            session()->flash('error', 'Día festivo no encontrado.');
            return redirect()->route('special-days.index');
        }

        // Cargar datos existentes
        $this->name = $this->specialDay->name;
        $this->date = $this->specialDay->date->format('Y-m-d');
        $this->is_paid = (bool) $this->specialDay->is_paid;
        $this->recurring = (bool) $this->specialDay->recurring;
    }

    // Método para actualizar el día festivo
    public function update()
    {
        $this->validate();

        try { // Intentar actualizar el día festivo
            $this->specialDay->update([
                'name' => $this->name,
                'date' => $this->date,
                'is_paid' => $this->is_paid,
                'recurring' => $this->recurring,
            ]);
            session()->flash('success', 'Día festivo actualizado exitosamente.');
            return redirect()->route('special-days.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el día festivo: ' . $e->getMessage());
        }
    }
    // Método para renderizar la vista
    public function render()
    {
        return view('livewire.special-days.edit');
    }
}