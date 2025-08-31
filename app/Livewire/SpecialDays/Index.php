<?php

namespace App\Livewire\SpecialDays;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SpecialDay;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public $search = '', $perPage = 10, $yearFilter;
    // Método para inicializar valores
    public function mount()
    {
        $this->yearFilter = now()->year;
    }

    // Método para obtener los días festivos
    public function getSpecialDaysProperty()
    {
        $query = SpecialDay::query();
        // Filtrar por búsqueda
        if ($this->search) {
            $query->where('name', 'ilike', '%' . $this->search . '%');
        }
        // Filtrar por año
        if ($this->yearFilter) {
            $query->whereYear('date', $this->yearFilter);
        }
        // Ordenar y paginar resultados
        return $query->orderBy('date')->paginate($this->perPage);
    }
    // Método para obtener los años disponibles
    public function getYearsProperty()
    {
        return range(now()->year - 2, now()->year + 2);
    }
    // Método para eliminar un día festivo
    public function deleteSpecialDay($id)
    {
        $specialDay = SpecialDay::find($id);
        // Verificar si el día festivo existe
        if ($specialDay) {
            $specialDay->delete();
            session()->flash('success', 'Día festivo eliminado correctamente.');
        }
    }
    // Método para generar días festivos del año
    public function generateYearHolidays()
    {
        $generated = SpecialDay::generateRecurringHolidays($this->yearFilter);
        // Verificar si se generaron días festivos
        if (count($generated) > 0) {
            session()->flash('success', 'Se generaron ' . count($generated) . ' días festivos para ' . $this->yearFilter);
        } else {
            session()->flash('info', 'Ya existen todos los días festivos recurrentes para ' . $this->yearFilter);
        }
    }
    // Método para actualizar la búsqueda
    public function updatedSearch()
    {
        $this->resetPage();
    }
    // Método para actualizar el filtro de año
    public function updatedYearFilter()
    {
        $this->resetPage();
    }
    // Método para renderizar la vista
    public function render()
    {

        return view('livewire.special-days.index', [
            'specialDays' => $this->specialDays,
            'years' => $this->years,
        ]);
    }
}