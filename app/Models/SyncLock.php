<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncLock extends Model
{
    protected $fillable = ['key', 'last_run_at'];

    protected $casts = [
        'last_run_at' => 'datetime',
    ];
}