<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AssignmentService;

class AssignmentController extends Controller
{
    protected $service;

    public function __construct(AssignmentService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'employee_id' => 'required|exists:employees,id',
        ]);

        $this->service->assign(
            $request->guest_id,
            $request->employee_id
        );

        return back()->with('success', 'Tamu berhasil di-assign');
    }
}
