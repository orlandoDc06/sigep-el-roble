<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
   protected $fillable = [
        'first_name',
        'last_name',
        'dui',
        'phone',
        'address',
        'birth_date',
        'hire_date',
        'termination_date',
        'gender',
        'marital_status',
        'photo_path',
        'status',
        'user_id',
        'branch_id',
        'contract_type_id',
    ];

    // Relación con Branch (Sucursal)
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    // Relación con ContractType (Tipo de contrato)
    public function contractType()
    {
        return $this->belongsTo(ContractType::class, 'contract_type_id');
    }

    // Relación con User (Usuario)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con Bonus (Bonos asignados al empleado)
    public function bonuses()
    {
        return $this->belongsToMany(Bonus::class, 'employee_bonus_assignments')
                    ->withPivot(['amount', 'applied_at', 'notes', 'assigned_by'])
                    ->withTimestamps();
    }
}
