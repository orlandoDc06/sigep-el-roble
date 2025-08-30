<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollDetail extends Model
{
    protected $fillable = [
        'payroll_id',
        'employee_id',
        'base_salary',
        'bonuses_total',
        'deductions_total',
        'advances_total',
        'extra_hours_total',
        'isss',
        'afp',
        'isr',
        'net_salary',
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

}
