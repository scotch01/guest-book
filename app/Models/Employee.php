<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'nama',
        'nip',
        'is_active'
    ];

    public function assignments()
    {
        return $this->hasMany(GuestAssignment::class);
    }
}
