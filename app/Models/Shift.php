<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    // Definir los campos que se pueden asignar masivamente
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'is_night_shift',
    ];

    // Definir los casts para los atributos para convertir a booleano
    protected $casts = [
        'is_night_shift' => 'boolean',
    ];

    // Definir las relaciones (Un turno puede tener varias asistencias asociadas)
    public function attendances()
    {
        // Retorna la relacion "hasMany" con el modelo Attendance
        return $this->hasMany(Attendance::class);
    }
    

}
