<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\SyncLock;

class SyncController extends Controller
{
    public function run()
    {
        $lock = SyncLock::firstOrCreate(
            ['key' => 'guests'],
            ['last_run_at' => null]
        );

        $now = now();

        if ($lock->last_run_at) {
            $next = $lock->last_run_at->copy()->addMinutes(30);

            if (now()->lt($next)) {
                $remaining = now()->diffInSeconds($next);

                return back()->with('error', "Tunggu " . ceil($remaining / 60) . " menit lagi.");
            }
        }

        DB::transaction(function () use ($lock, $now) {

            // update lock dulu (anti double click)
            $lock->update(['last_run_at' => $now]);

            \Artisan::call('sync:guests');
        });

        return back()->with('success', 'Sync berhasil.');
    }
}
