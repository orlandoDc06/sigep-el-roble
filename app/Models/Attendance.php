<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Shift;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'shift_id',
        'check_in_time',
        'check_out_time',
        'is_manual_entry',
        'device_id',
    ];

    //sirve para poder realizar operaciones de tipo fecha
    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'is_manual_entry' => 'boolean',
    ];

    //relacion con empleado
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    //relacion con tabla de turnos
    public function shiftAssignment()
    {
        return $this->hasOne(EmployeeShiftAssignment::class, 'employee_id', 'employee_id');
    }

    //obtener turno del empleado
    public function getShift()
    {
        return $this->shiftAssignment->shift ?? null;
    }

    //relacion con turno
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

}
