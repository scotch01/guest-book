<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use App\Models\Employee;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'today');

        $query = \App\Models\Guest::with(['assignment.employee', 'queue']);

        // TAB: HARI INI
        if ($tab === 'today') {
            $query->whereDate('tanggal_kunjungan', now()->toDateString());
        }

        // TAB: RIWAYAT
        if ($tab === 'history') {
            if ($request->filled('start_date')) {
                $query->whereDate('tanggal_kunjungan', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->whereDate('tanggal_kunjungan', '<=', $request->end_date);
            }
        }

        $perPage = $request->get('per_page', 20);

        $guests = $query->orderByDesc('tanggal_kunjungan')
            ->paginate($perPage)
            ->withQueryString();

        $employees = \App\Models\Employee::where('is_active', 1)->get();

        return view('guests.index', compact('guests', 'employees', 'tab'));
    }

    public function show(Guest $guest)
    {
        return view('guests.show', compact('guest'));
    }
}
