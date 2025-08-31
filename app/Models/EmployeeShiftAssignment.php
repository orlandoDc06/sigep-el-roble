<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeShiftAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'shift_id', 
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Relación con empleado
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Relación con turno
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * Scope para asignaciones activas
     */
    public function scopeActive($query)
    {
        return $query->whereNull('end_date');
    }

    /**
     * Scope para asignaciones en una fecha específica
     */
    public function scopeOnDate($query, $date)
    {
        return $query->where('start_date', '<=', $date)
                    ->where(function ($query) use ($date) {
                        $query->whereNull('end_date')
                              ->orWhere('end_date', '>=', $date);
                    });
    }
    //para obtener el turno actual
    public function getCurrentShift()
    {
        $today = now()->toDateString();
        return $this->where('start_date', '<=', $today)
                    ->where(function ($query) use ($today) {
                        $query->whereNull('end_date')
                              ->orWhere('end_date', '>=', $today);
                    })
                    ->first();
    }
}