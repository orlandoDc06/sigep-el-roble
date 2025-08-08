<?php

namespace App\Livewire\Shifts;

use Livewire\Component;
use App\Models\Shift;
use Carbon\Carbon;

class ShiftsEdit extends Component
{
    // Definir las propiedades del componente
    public $shift_id;
    public $name, $start_time, $end_time, $is_night_shift = false;

    // Metodo para inicializar el componente
    public function mount($id)
    {
        // Cargar el turno existente
        $shift = Shift::findOrFail($id);
        $this->shift_id = $shift->id;
        $this->name = $shift->name;
        
        // Formatear las horas usando Carbon para quitar los segundos
        $this->start_time = $shift->start_time ? Carbon::parse($shift->start_time)->format('H:i') : null;
        $this->end_time = $shift->end_time ? Carbon::parse($shift->end_time)->format('H:i') : null;
        
        // Asignar el estado del turno nocturno
        $this->is_night_shift = $shift->is_night_shift;
    }

    // Metodo para guardar los cambios
    public function updateShift()
    {
        // Limpiar valores vacios y espacios en blanco
        $this->start_time = trim($this->start_time) === '' ? null : $this->start_time;
        $this->end_time = trim($this->end_time) === '' ? null : $this->end_time;

        // Validar los campos
        $this->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'is_night_shift' => 'boolean',
        ], [
            'name.required' => 'El nombre del turno es obligatorio.',
            'start_time.date_format' => 'La hora de inicio debe tener el formato HH:mm.',
            'end_time.date_format' => 'La hora de fin debe tener el formato HH:mm.',
        ]);

        // Solo validar logica de horas si AMBAS estan presentes
        if ($this->start_time && $this->end_time) {
            if (!$this->is_night_shift && strtotime($this->end_time) <= strtotime($this->start_time)) {
                $this->addError('end_time', 'La hora de fin debe ser posterior a la hora de inicio.');
                return;
            }
        }

        // Preparar los datos para la actualizacion
        $shiftData = [
            'name' => $this->name,
            'is_night_shift' => $this->is_night_shift,
        ];

        // SOLO actualizar horas si AMBAS estan definidas (no null)
        if ($this->start_time && $this->end_time) {
            $shiftData['start_time'] = $this->start_time;
            $shiftData['end_time'] = $this->end_time;
        }
        /* Si no estan definidas, no se incluyen en $shiftData y Laravel mantiene los valores originales */

        // Buscar el turno por ID y actualizarlo
        $shift = Shift::findOrFail($this->shift_id);
        $shift->update($shiftData);

        // Mostrar mensaje de exito
        session()->flash('message', 'Turno actualizado correctamente.');
        return redirect()->route('shifts.index');
    }

    // Metodo para regresar al indice de turnos
    public function returnIndex()
    {
        return redirect()->route('shifts.index');
    }

    // Metodo para renderizar la vista
    public function render()
    {
        return view('livewire.shifts.shifts-edit');
    }
}
