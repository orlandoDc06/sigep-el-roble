<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advance extends Model
{
    //Atributos de la tabla
    protected $fillable = ['employee_id', 'amount', 'date', 'reason', 'approved_by',  'status',];

    // Relación con el empleado
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Relación con el usuario que aprobó el anticipo
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    
}
