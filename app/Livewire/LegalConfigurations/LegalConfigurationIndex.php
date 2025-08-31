<?php

namespace App\Livewire\LegalConfigurations;

use Livewire\Component;
use App\Models\LegalConfiguration;
use Livewire\WithPagination;

class LegalConfigurationIndex extends Component
{
    use WithPagination;

    public $search = '';
    
    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $config = LegalConfiguration::findOrFail($id);
        
        // No permitir eliminar la configuración activa
        if ($config->is_active) {
            session()->flash('error', 'No se puede eliminar la configuración activa.');
            return;
        }
        
        $config->delete();
        session()->flash('message', 'Configuración legal eliminada exitosamente.');
    }

    public function activate($id)
    {
        // Desactivar todas las configuraciones
        LegalConfiguration::query()->update(['is_active' => false]);
        
        // Activar la seleccionada
        $config = LegalConfiguration::findOrFail($id);
        $config->update(['is_active' => true]);
        
        session()->flash('message', 'Configuración legal activada exitosamente.');
    }

    public function render()
    {
        $configurations = LegalConfiguration::query()
            ->when($this->search, function ($query) {
                $query->where('start_date', 'like', '%' . $this->search . '%')
                      ->orWhere('minimum_wage', 'like', '%' . $this->search . '%');
            })
            ->orderBy('is_active', 'desc')
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        return view('livewire.legal-configurations.legal-configuration-index', compact('configurations'));
    }
}