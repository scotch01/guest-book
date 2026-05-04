<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Guest;
use App\Models\GuestAssignment;
use App\Models\Queue;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        // =========================
        // KPI
        // =========================
        $totalToday = Guest::whereDate('tanggal_kunjungan', $today)->count();

        $assignedToday = GuestAssignment::whereHas('guest', function ($q) use ($today) {
            $q->whereDate('tanggal_kunjungan', $today);
        })->count();

        $unassignedToday = $totalToday - $assignedToday;

        $lastQueue = Queue::where('queue_date', $today)->max('queue_number') ?? 0;

        // =========================
        // LAYANAN
        // =========================
        $ppidToday = Guest::whereDate('tanggal_kunjungan', $today)
            ->where('keperluan', 'PPID')
            ->count();

        $pstToday = Guest::whereDate('tanggal_kunjungan', $today)
            ->where('keperluan', 'Pelayanan PST')
            ->count();

        // =========================
        // ANTRIAN HARI INI
        // =========================
        $queues = Queue::with(['guest.assignment.employee'])
            ->where('queue_date', $today)
            ->orderBy('queue_number')
            ->get();

        // =========================
        // DISTRIBUSI PEGAWAI
        // =========================
        $employeeStats = DB::table('guest_assignments')
            ->join('guests', 'guests.id', '=', 'guest_assignments.guest_id')
            ->join('employees', 'employees.id', '=', 'guest_assignments.employee_id')
            ->whereDate('guests.tanggal_kunjungan', $today)
            ->select(
                'employees.nama',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('employees.nama')
            ->orderByDesc('total')
            ->get();

        return view('dashboard', compact(
            'totalToday',
            'assignedToday',
            'unassignedToday',
            'lastQueue',
            'ppidToday',
            'pstToday',
            'queues',
            'employeeStats'
        ));
    }
}