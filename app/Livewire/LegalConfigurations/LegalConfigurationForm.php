<?php

namespace App\Livewire\LegalConfigurations;

use Livewire\Component;
use App\Models\LegalConfiguration;
use App\Models\IsrRange;
use Carbon\Carbon;

class LegalConfigurationForm extends Component
{
    public $configurationId;
    public $afp_percentage = 6.25;
    public $isss_percentage = 3.00;
    public $isss_max_cap = 1000.00;
    public $minimum_wage = 365.00;
    public $vacation_bonus_percentage = 30.00;
    public $year_end_bonus_days = 15;
    public $income_tax_enabled = true;
    public $start_date;
    public $end_date;
    public $is_active = false;

    // Rangos ISR
    public $isr_ranges = [
        ['min_amount' => 0.01, 'max_amount' => 472.00, 'percentage' => 0.00, 'fixed_fee' => 0.00],
        ['min_amount' => 472.01, 'max_amount' => 895.24, 'percentage' => 10.00, 'fixed_fee' => 17.67],
        ['min_amount' => 895.25, 'max_amount' => 2038.10, 'percentage' => 20.00, 'fixed_fee' => 60.00],
        ['min_amount' => 2038.11, 'max_amount' => null, 'percentage' => 30.00, 'fixed_fee' => 288.57],
    ];

    protected $rules = [
        'afp_percentage' => 'required|numeric|min:0|max:100',
        'isss_percentage' => 'required|numeric|min:0|max:100',
        'isss_max_cap' => 'required|numeric|min:0',
        'minimum_wage' => 'required|numeric|min:0',
        'vacation_bonus_percentage' => 'required|numeric|min:0|max:100',
        'year_end_bonus_days' => 'required|integer|min:0|max:365',
        'income_tax_enabled' => 'boolean',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after:start_date',
        'is_active' => 'boolean',
        'isr_ranges.*.min_amount' => 'required|numeric|min:0',
        'isr_ranges.*.max_amount' => 'nullable|numeric|gt:isr_ranges.*.min_amount',
        'isr_ranges.*.percentage' => 'required|numeric|min:0|max:100',
        'isr_ranges.*.fixed_fee' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'afp_percentage.required' => 'El porcentaje de AFP es obligatorio.',
        'afp_percentage.numeric' => 'El porcentaje de AFP debe ser numérico.',
        'isss_percentage.required' => 'El porcentaje de ISSS es obligatorio.',
        'minimum_wage.required' => 'El salario mínimo es obligatorio.',
        'start_date.required' => 'La fecha de inicio es obligatoria.',
        'end_date.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $this->configurationId = $id;
            $this->loadConfiguration($id);
        } else {
            $this->start_date = Carbon::now()->format('Y-m-d');
        }
    }

    public function loadConfiguration($id)
    {
        $config = LegalConfiguration::with('isrRanges')->findOrFail($id);
        
        $this->afp_percentage = $config->afp_percentage;
        $this->isss_percentage = $config->isss_percentage;
        $this->isss_max_cap = $config->isss_max_cap;
        $this->minimum_wage = $config->minimum_wage;
        $this->vacation_bonus_percentage = $config->vacation_bonus_percentage;
        $this->year_end_bonus_days = $config->year_end_bonus_days;
        $this->income_tax_enabled = $config->income_tax_enabled;
        $this->start_date = $config->start_date;
        $this->end_date = $config->end_date;
        $this->is_active = $config->is_active;

        // Cargar rangos ISR si existen
        if ($config->isrRanges->count() > 0) {
            $this->isr_ranges = $config->isrRanges->map(function ($range) {
                return [
                    'min_amount' => $range->min_amount,
                    'max_amount' => $range->max_amount,
                    'percentage' => $range->percentage,
                    'fixed_fee' => $range->fixed_fee,
                ];
            })->toArray();
        }
    }

    public function addIsrRange()
    {
        $this->isr_ranges[] = [
            'min_amount' => 0.00,
            'max_amount' => null,
            'percentage' => 0.00,
            'fixed_fee' => 0.00
        ];
    }

    public function removeIsrRange($index)
    {
        unset($this->isr_ranges[$index]);
        $this->isr_ranges = array_values($this->isr_ranges);
    }

    public function save()
    {
        $this->validate();

        try {
            // Si se marca como activa, desactivar todas las demás
            if ($this->is_active) {
                LegalConfiguration::query()->update(['is_active' => false]);
            }

            $data = [
                'afp_percentage' => $this->afp_percentage,
                'isss_percentage' => $this->isss_percentage,
                'isss_max_cap' => $this->isss_max_cap,
                'minimum_wage' => $this->minimum_wage,
                'vacation_bonus_percentage' => $this->vacation_bonus_percentage,
                'year_end_bonus_days' => $this->year_end_bonus_days,
                'income_tax_enabled' => $this->income_tax_enabled,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'is_active' => $this->is_active,
            ];

            if ($this->configurationId) {
                $config = LegalConfiguration::findOrFail($this->configurationId);
                $config->update($data);
            } else {
                $config = LegalConfiguration::create($data);
            }

            // Guardar rangos ISR
            $config->isrRanges()->delete(); // Eliminar rangos anteriores
            
            foreach ($this->isr_ranges as $range) {
                $config->isrRanges()->create([
                    'min_amount' => $range['min_amount'],
                    'max_amount' => $range['max_amount'],
                    'percentage' => $range['percentage'],
                    'fixed_fee' => $range['fixed_fee'],
                ]);
            }

            session()->flash('message', $this->configurationId ? 'Configuración actualizada exitosamente.' : 'Configuración creada exitosamente.');
            
            return redirect()->route('admin.legal-configurations.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar la configuración: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.legal-configurations.legal-configuration-form');
    }
}