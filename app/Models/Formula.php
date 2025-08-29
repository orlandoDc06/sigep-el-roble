<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formula extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'expression',
        'type',
        'syntax_validated',
    ];

    protected $casts = [
        'syntax_validated' => 'boolean',
    ];

    // Constantes para los tipos de fórmulas
    const TYPE_ISR = 'isr';
    const TYPE_BONUS = 'bonus';
    const TYPE_DEDUCTION = 'deduction';
    const TYPE_OVERTIME = 'overtime';
    const TYPE_VACATION = 'vacation';
    const TYPE_OTHER = 'other';

    // Getter para obtener todos los tipos disponibles
    public static function getTypes()
    {
        return [
            self::TYPE_ISR => 'ISR (Impuesto Sobre la Renta)',
            self::TYPE_BONUS => 'Bonificaciones',
            self::TYPE_DEDUCTION => 'Deducciones',
            self::TYPE_OVERTIME => 'Horas Extra',
            self::TYPE_VACATION => 'Vacaciones',
            self::TYPE_OTHER => 'Otros',
        ];
    }

    // Método para validar la sintaxis de la expresión
    public function validateSyntax()
    {
        try {
            // Aquí puedes agregar tu lógica de validación
            // Por ejemplo, verificar que la expresión tenga variables válidas
            $validVariables = ['salary', 'days_worked', 'hours', 'rate'];
            
            // Simple validación - puedes expandirla según tus necesidades
            if (empty($this->expression)) {
                return false;
            }

            // Marcar como validada si pasa las verificaciones
            $this->syntax_validated = true;
            $this->save();
            
            return true;
        } catch (\Exception $e) {
            $this->syntax_validated = false;
            $this->save();
            return false;
        }
    }

    // Scope para obtener solo fórmulas validadas
    public function scopeValidated($query)
    {
        return $query->where('syntax_validated', true);
    }

    // Scope para obtener fórmulas por tipo
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
