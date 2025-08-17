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

    /**
     * Relación muchos a muchos con turnos usando tabla pivote
     */
    public function shifts()
    {
        return $this->belongsToMany(Shift::class, 'employee_shift_assignments')
                   ->withPivot('start_date', 'end_date')
                   ->withTimestamps();
    }

    /**
     * Obtener turnos activos (sin fecha de fin o con fecha de fin futura)
     */
    public function activeShifts()
    {
        $today = now()->toDateString();
        return $this->shifts()
                   ->wherePivot(function ($query) use ($today) {
                       $query->whereNull('end_date')
                             ->orWhere('end_date', '>=', $today);
                   })
                   ->wherePivot('start_date', '<=', $today);
    }

    /**
     * Obtener turnos permanentes (sin fecha de fin)
     */
    public function permanentShifts()
    {
        return $this->shifts()->wherePivot('end_date', null);
    }

    /**
     * Obtener turnos temporales (con fecha de fin)
     */
    public function temporaryShifts()
    {
        return $this->shifts()->whereNotNull('employee_shift_assignments.end_date');
    }

    /**
     * Obtener turnos vencidos
     */
    public function expiredShifts()
    {
        return $this->shifts()
                   ->whereNotNull('employee_shift_assignments.end_date')
                   ->wherePivot('end_date', '<', now()->toDateString());
    }

    /**
     * Obtener el turno principal (el primero asignado permanente)
     */
    public function primaryShift()
    {
        return $this->permanentShifts()
                   ->orderBy('employee_shift_assignments.start_date')
                   ->first();
    }

    /**
     * Verificar si el empleado tiene turnos activos en una fecha específica
     */
    public function hasActiveShiftOnDate($date)
    {
        return $this->shifts()
                   ->wherePivot('start_date', '<=', $date)
                   ->wherePivot(function ($query) use ($date) {
                       $query->whereNull('end_date')
                             ->orWhere('end_date', '>=', $date);
                   })
                   ->exists();
    }

}
