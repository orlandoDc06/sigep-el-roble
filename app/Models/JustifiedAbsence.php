<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JustifiedAbsence extends Model
{
    //
    protected $fillable = [
        'employee_id',
        'date',
        'reason',
        'status',
        'approved_by',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
