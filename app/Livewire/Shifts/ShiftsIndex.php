<?php

namespace App\Livewire\Shifts;

use Livewire\Component;
use App\Models\Shift;

class ShiftsIndex extends Component
{
    // propiedades
    public $shifts, $search = '';

    // metodo para mostrar los componentes
    public function mount()
    {
        $this->shifts = Shift::all(); // all para mostrar todos
    }

    /*-------------------------------------------------------------------------------------
        VERIFICAR USO FUTURO EN MODULO DE ASISTENCIA 

       - Antes de eliminar, verificas que el turno no tenga asistencias futuras asignadas.
       - Si las tiene, muestras un mensaje de error y no eliminas.
       - Si no, permites la eliminación
    ------------------------------------------------------------------------------------ */
    
    //Metodo para eliminar un turno
    public function deleteShift($id)
    {
        $shift = Shift::findOrFail($id);

        // Verificar si tiene asistencias futuras asignadas (usando relación 'attendances')
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
        redirect()->route('shifts.index');
    }

    // metodo para redirigir al formulario de edición
    public function editShift($id)
    {
        return redirect()->route('shifts.edit', ['id' => $id]);
    }

    public function render()
    {
        // busqueda filtrada por nombre
        $shifts = Shift::where(function($query) {
            $query->where('name', 'ilike', '%' . $this->search . '%');
        })->orderBy('start_time', 'asc')->get();

        return view('livewire.shifts.shifts-index', [
            'shifts' => $shifts,
        ]);
    }

    // metodo para aplicar la busqueda (se aplica al presionar ENTER o al darle click al boton de buscar)
    public function applySearch()
    {
        $this->shifts = Shift::where('name', 'ilike', '%' . $this->search . '%')
            ->orderBy('start_time', 'asc')
            ->get();
    }

    // metodo para limpiar la busqueda
    public function resetSearch()
    {
        $this->search = '';
        $this->shifts = Shift::all();
    }

    // metodo para formatear la hora para mostrar
    public function formatTime($time)
    {
        return date('H:i', strtotime($time));
    }

    // metodo para determinar si es turno nocturno
    public function isNightShiftText($isNight)
    {
        return $isNight ? 'Sí' : 'No';
    }
}