<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
        'guest_id',
        'queue_number',
        'queue_date'
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
}
