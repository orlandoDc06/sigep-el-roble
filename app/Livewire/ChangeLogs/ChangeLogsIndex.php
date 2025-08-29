<?php

namespace App\Livewire\ChangeLogs;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ChangeLog;
use Illuminate\Support\Facades\Auth;

class ChangeLogsIndex extends Component
{
       use WithPagination;

    public $search = '';
    protected $paginationTheme = 'tailwind'; // Para usar Tailwind en la paginación

    /**
     * Renderiza la vista de la bitácora con filtros y paginación
     */
    public function render()
    {
        // Validar permisos: solo administradores pueden ver la bitácora
        if (!Auth::user()->hasRole('Administrador')) {
            abort(403, 'No tienes permisos para acceder a la bitácora.');
        }

        // Query de bitácora con búsqueda
        $logs = ChangeLog::with('changedBy')
            ->when($this->search, function ($query) {
                $query->where('model', 'like', "%{$this->search}%")
                      ->orWhere('field_changed', 'like', "%{$this->search}%")
                      ->orWhere('old_value', 'like', "%{$this->search}%")
                      ->orWhere('new_value', 'like', "%{$this->search}%");
            })
            ->orderBy('changed_at', 'desc')
            ->paginate(10); // Paginación de 10 por página

        return view('livewire.change-logs.change-logs-index', [
            'logs' => $logs
        ]);
    }

    
}
