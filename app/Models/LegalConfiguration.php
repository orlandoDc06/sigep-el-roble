<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LegalConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'afp_percentage',
        'isss_percentage',
        'isss_max_cap',
        'minimum_wage',
        'vacation_bonus_percentage',
        'year_end_bonus_days',
        'income_tax_enabled',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'afp_percentage' => 'decimal:2',
        'isss_percentage' => 'decimal:2',
        'isss_max_cap' => 'decimal:2',
        'minimum_wage' => 'decimal:2',
        'vacation_bonus_percentage' => 'decimal:2',
        'year_end_bonus_days' => 'integer',
        'income_tax_enabled' => 'boolean',
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Relación con rangos de ISR
     */
    public function isrRanges()
    {
        return $this->hasMany(IsrRange::class);
    }

    /**
     * Scope para obtener la configuración activa
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para configuraciones vigentes en una fecha específica
     */
    public function scopeValidForDate($query, $date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        
        return $query->where('start_date', '<=', $date)
                    ->where(function ($q) use ($date) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', $date);
                    });
    }

    /**
     * Obtener la configuración legal activa
     */
    public static function getActive()
    {
        return self::where('is_active', true)->with('isrRanges')->first();
    }

    /**
     * Calcular AFP para un salario
     */
    public function calculateAfp($salary)
    {
        return round(($salary * $this->afp_percentage) / 100, 2);
    }

    /**
     * Calcular ISSS para un salario
     */
    public function calculateIsss($salary)
    {
        $baseForIsss = min($salary, $this->isss_max_cap);
        return round(($baseForIsss * $this->isss_percentage) / 100, 2);
    }

    /**
     * Calcular ISR para un salario
     */
    public function calculateIsr($salary)
    {
        if (!$this->income_tax_enabled) {
            return 0;
        }

        $ranges = $this->isrRanges()->orderBy('min_amount')->get();
        
        foreach ($ranges as $range) {
            if ($salary >= $range->min_amount && ($range->max_amount === null || $salary <= $range->max_amount)) {
                $excess = $salary - $range->min_amount;
                $isr = $range->fixed_fee + (($excess * $range->percentage) / 100);
                return round($isr, 2);
            }
        }

        return 0;
    }

    /**
     * Calcular bono vacacional
     */
    public function calculateVacationBonus($salary)
    {
        return round(($salary * $this->vacation_bonus_percentage) / 100, 2);
    }

    /**
     * Calcular aguinaldo proporcional
     */
    public function calculateYearEndBonus($salary, $workedDays = 365)
    {
        $dailySalary = $salary / 30; // Salario diario
        $proportionalDays = min($this->year_end_bonus_days, ($workedDays / 365) * $this->year_end_bonus_days);
        
        return round($dailySalary * $proportionalDays, 2);
    }

    /**
     * Activar esta configuración y desactivar las demás
     */
    public function activate()
    {
        // Desactivar todas las configuraciones
        self::query()->update(['is_active' => false]);
        
        // Activar esta configuración
        $this->update(['is_active' => true]);
    }

    /**
     * Verificar si la configuración está vigente para una fecha
     */
    public function isValidForDate($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        
        $startDate = Carbon::parse($this->start_date);
        $endDate = $this->end_date ? Carbon::parse($this->end_date) : null;
        
        return $date->greaterThanOrEqualTo($startDate) && 
               ($endDate === null || $date->lessThanOrEqualTo($endDate));
    }

    /**
     * Boot method para eventos del modelo
     */
    protected static function boot()
    {
        parent::boot();

        // Al activar una configuración, desactivar las demás
        static::updating(function ($model) {
            if ($model->is_active && $model->isDirty('is_active')) {
                self::where('id', '!=', $model->id)->update(['is_active' => false]);
            }
        });

        static::creating(function ($model) {
            if ($model->is_active) {
                self::query()->update(['is_active' => false]);
            }
        });
    }
}