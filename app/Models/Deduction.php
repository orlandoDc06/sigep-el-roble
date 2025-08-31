<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    protected $fillable = [
        'name',
        'description',
        'default_amount',
        'applies_to_all',
        'is_percentage',
    ];

    // Relación muchos a muchos con Employee a través de employee_deduction_assignments
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_deduction_assignments')
                    ->withPivot(['amount', 'applied_at', 'notes', 'assigned_by'])
                    ->withTimestamps();
    }

    // Función para obtener las asignaciones de empleados relacionadas con este descuento
    public function assignments()
    {
        return $this->hasMany(EmployeeDeductionAssignment::class, 'deduction_id');
    }
}
