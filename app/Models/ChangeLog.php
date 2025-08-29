<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 

class ChangeLog extends Model
{
    //// Deshabilitar timestamps automáticos porque usamos changed_at
    public $timestamps = false;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'model',
        'model_id',
        'field_changed',
        'old_value',
        'new_value',
        'changed_by',
        'changed_at',
    ];

    /**
     * Relación con el usuario que hizo el cambio
     */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
