<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeDeductionAssignment extends Model
{
    ///Factory para EmployeeBonusAssignment
     use HasFactory;

    //Definición de los campos asignables
    protected $fillable = [
        'employee_id',
        'deduction_id',
        'amount',
        'applied_at',
        'notes',
        'assigned_by',
    ];

    //Definición de los casts para los campos
    protected $casts = [
        'applied_at' => 'date',
    ];

    //Relacion con la tabla de empleados
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    //Relacion con la tabla de descuentos
    public function deduction()
    {
        return $this->belongsTo(Deduction::class);
    }

    //Hace referencia al usuario que asignó el descuento
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
