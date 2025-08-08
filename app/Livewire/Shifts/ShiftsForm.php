<?php

namespace App\Livewire\Shifts;

use App\Models\Shift;
use Livewire\Component;

class ShiftsForm extends Component
{
    public $shift_id;
    public $name, $start_time, $end_time, $is_night_shift = false;
    public $is_editing = false;

    public function mount($shift = null)
    {
        if ($shift) {
            $this->is_editing = true;
            $this->shift_id = $shift->id;
            $this->name = $shift->name;
            $this->start_time = $shift->start_time;
            $this->end_time = $shift->end_time;
            $this->is_night_shift = $shift->is_night_shift;
        }
    }

    public function createShift()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'is_night_shift' => 'boolean',
        ], [
            'name.required' => 'El nombre del turno es obligatorio.',
            'start_time.required' => 'La hora de inicio es obligatoria.',
            'end_time.required' => 'La hora de fin es obligatoria.',
        ]);

        // Validar que end_time sea después de start_time si no es nocturno
        if (!$this->is_night_shift && strtotime($this->end_time) <= strtotime($this->start_time)) {
            $this->addError('end_time', 'La hora de fin debe ser posterior a la hora de inicio.');
            return;
        }

        /*-----------------------------------------------------------------------------------------
            Validacion de solapamiento de turnos
            el turno que se intenta crear o editar
            no se solape con ningun otro turno existente, considerando que los turnos nocturnos
            pueden cruzar la medianoche.
        -------------------------------------------------------------------------------------------------*/

        // Convertimos horas a minutos desde medianoche para comparación lineal
        $startMinutes = $this->timeToMinutes($this->start_time);
        $endMinutes = $this->timeToMinutes($this->end_time);

         // Ajuste para turnos nocturnos que cruzan medianoche
        if ($this->is_night_shift && $endMinutes <= $startMinutes) {
            $endMinutes += 1440;
        }

        // Consulta para detectar si hay turnos que se solapan con el rango actual
        $overlapping = Shift::where(function ($query) use ($startMinutes, $endMinutes) {
            $query->where(function ($q) use ($startMinutes, $endMinutes) {
                $q->whereRaw('( ( (EXTRACT(HOUR FROM start_time)*60 + EXTRACT(MINUTE FROM start_time)) < ? ) AND ( (EXTRACT(HOUR FROM end_time)*60 + EXTRACT(MINUTE FROM end_time)) > ? ) )', [$endMinutes, $startMinutes]);
            })
            ->orWhere(function ($q) use ($startMinutes, $endMinutes) {
                // Otro turno que cruza medianoche
                $q->whereRaw('( ( (EXTRACT(HOUR FROM start_time)*60 + EXTRACT(MINUTE FROM start_time)) < (? + 1440) ) AND ( (EXTRACT(HOUR FROM end_time)*60 + EXTRACT(MINUTE FROM end_time)) > ? ) )', [$endMinutes, $startMinutes]);
            });
        });

        // Si estamos editando, excluimos el turno actual de la verificación
        if ($this->is_editing) {
            $overlapping->where('id', '!=', $this->shift_id);
        }

        // Si se detecta solapamiento, se agrega error para impedir guardar
        if ($overlapping->exists()) {
            $this->addError('start_time', 'Este turno se solapa con otro existente.');
            return;
        }

        // Preparar datos para crear o actualizar turno
        $data = [
            'name' => $this->name,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'is_night_shift' => $this->is_night_shift,
        ];

        // Crear o actualizar registro en base de datos
        if ($this->is_editing) {
            Shift::find($this->shift_id)->update($data);
            session()->flash('message', 'Turno actualizado exitosamente.');
        } else {
            Shift::create($data);
            session()->flash('message', 'Turno creado exitosamente.');
        }

        return redirect()->route('shifts.index');
    }

    // Metodo auxiliar para convertir formato HH:mm a minutos desde medianoche
    private function timeToMinutes($time)
    {
        [$h, $m] = explode(':', $time);
        return intval($h) * 60 + intval($m);
    }

    public function returnIndex()
    {
        return redirect()->route('shifts.index');
    }

    // Actualizaciones reactivas para detectar si el turno es nocturno
    public function updatedStartTime()
    {
        $this->detectNightShift();
    }

    public function updatedEndTime()
    {
        $this->detectNightShift();
    }

    // Logica para detectar si el turno debe marcarse como nocturno
    private function detectNightShift()
    {
        if ($this->start_time && $this->end_time) {
            $start = strtotime($this->start_time);
            $end = strtotime($this->end_time);

            // Considera turno nocturno si cruza medianoche o empieza entre 22 y 6
            if ($end < $start || date('H', $start) >= 22 || date('H', $start) <= 6 || date('H', $end) <= 6) {
                $this->is_night_shift = true;
            } else {
                $this->is_night_shift = false;
            }
        }
    }

    public function render()
    {
        return view('livewire.shifts.shifts-form');
    }

    // Metodo para eliminar un turno
    public function deleteShift($id)
    {
        $shift = Shift::findOrFail($id);

        // Verifica si hay asistencias futuras asociadas al turno (asumiendo relación 'attendances' en Shift)
        $hasFutureAttendances = $shift->attendances()
            ->whereDate('date', '>=', now()->toDateString())
            ->exists();

        if ($hasFutureAttendances) {
            session()->flash('error', 'No se puede eliminar este turno porque tiene asistencias futuras asignadas.');
            return;
        }

        $shift->delete();

        session()->flash('message', 'Turno eliminado con éxito.');
        $this->dispatch('shiftDeleted');
        return redirect()->route('shifts.index');
    }

}
