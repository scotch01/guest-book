<?php

namespace App\Services;

use App\Models\Queue;
use Illuminate\Support\Facades\DB;

class QueueService
{
    public function generate($guestId, $date)
    {
        // ❗ STOP kalau tanggal kosong
        if (empty($date)) {
            return null;
        }

        return DB::transaction(function () use ($guestId, $date) {

            // ❗ TIDAK ADA fallback ke now()
            $queueDate = $date;

            $last = Queue::where('queue_date', $queueDate)
                ->lockForUpdate()
                ->max('queue_number');

            $next = ($last ?? 0) + 1;

            return Queue::create([
                'guest_id'     => $guestId,
                'queue_number' => $next,
                'queue_date'   => $queueDate
            ]);
        });
    }
}