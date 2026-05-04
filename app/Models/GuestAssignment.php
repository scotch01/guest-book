<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestAssignment extends Model
{
    protected $fillable = [
        'guest_id',
        'employee_id',
        'assigned_at'
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
