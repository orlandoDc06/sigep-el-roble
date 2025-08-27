<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IsrRange extends Model
{
    use HasFactory;

    protected $fillable = [
        'legal_configuration_id',
        'min_amount',
        'max_amount',
        'percentage',
        'fixed_fee',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'percentage' => 'decimal:2',
        'fixed_fee' => 'decimal:2',
    ];

    /**
     * Relación con configuración legal
     */
    public function legalConfiguration()
    {
        return $this->belongsTo(LegalConfiguration::class);
    }

    /**
     * Verificar si un salario está dentro de este rango
     */
    public function isInRange($salary)
    {
        return $salary >= $this->min_amount && 
               ($this->max_amount === null || $salary <= $this->max_amount);
    }

    /**
     * Calcular el ISR para un salario usando este rango
     */
    public function calculateIsr($salary)
    {
        if (!$this->isInRange($salary)) {
            return 0;
        }

        $excess = $salary - $this->min_amount;
        return $this->fixed_fee + (($excess * $this->percentage) / 100);
    }

    /**
     * Scope para ordenar por monto mínimo
     */
    public function scopeOrderedByAmount($query)
    {
        return $query->orderBy('min_amount');
    }

    /**
     * Accessor para mostrar el rango en formato texto
     */
    public function getRangeTextAttribute()
    {
        if ($this->max_amount === null) {
            return '$' . number_format($this->min_amount, 2) . ' en adelante';
        }

        return '$' . number_format($this->min_amount, 2) . ' - $' . number_format($this->max_amount, 2);
    }

    /**
     * Accessor para mostrar el porcentaje formateado
     */
    public function getFormattedPercentageAttribute()
    {
        return number_format($this->percentage, 2) . '%';
    }

    /**
     * Accessor para mostrar la cuota fija formateada
     */
    public function getFormattedFixedFeeAttribute()
    {
        return '$' . number_format($this->fixed_fee, 2);
    }
}