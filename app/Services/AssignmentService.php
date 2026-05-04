<?php

namespace App\Services;

use App\Models\GuestAssignment;
use App\Models\Queue;
use Illuminate\Support\Facades\DB;

class AssignmentService
{
    protected $queueService;

    public function __construct(QueueService $queueService)
    {
        $this->queueService = $queueService;
    }

    public function assign($guestId, $employeeId)
    {
        return DB::transaction(function () use ($guestId, $employeeId) {

            // cek sudah pernah assign?
            $existing = GuestAssignment::where('guest_id', $guestId)->first();

            if ($existing) {
                return $existing; // tidak duplicate
            }

            $assignment = GuestAssignment::create([
                'guest_id' => $guestId,
                'employee_id' => $employeeId,
                'assigned_at' => now(),
            ]);

            // generate queue
            // $this->queueService->generate($guestId);

            return $assignment;
        });
    }
}