<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function monthly(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');

        $data = $this->getReportData($month);

        return view('reports.monthly', $data);
    }

    public function monthlyPdf(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');

        $data = $this->getReportData($month);

        $pdf = Pdf::loadView('reports.monthly-pdf', $data);

        return $pdf->stream('report-{$month}.pdf');
    }

    private function getReportData($month)
    {
        $date = \Carbon\Carbon::createFromFormat('Y-m', $month);
        $monthNum = $date->month;
        $yearNum  = $date->year;

        // =========================
        // 1. BASE DATA (ASSIGNED ONLY)
        // =========================
        $base = DB::table('guest_assignments')
            ->join('guests', 'guests.id', '=', 'guest_assignments.guest_id')
            ->join('employees', 'employees.id', '=', 'guest_assignments.employee_id')
            ->whereMonth('guests.tanggal_kunjungan', $monthNum)
            ->whereYear('guests.tanggal_kunjungan', $yearNum);

        // =========================
        // 2. TOTAL TAMU (ASSIGNED ONLY)
        // =========================
        $total = (clone $base)->count();

        // =========================
        // 3. REKAP PER PEGAWAI
        // =========================
        $data = (clone $base)
            ->select(
                'employees.nama',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('employees.nama')
            ->orderBy('employees.nama')
            ->get();

        // =========================
        // 4. BREAKDOWN (ASSIGNED ONLY)
        // =========================
        $breakdown = (clone $base)
            ->select('guests.keperluan', 'guests.jenis_layanan')
            ->get()
            ->map(function ($row) {

                // NORMALISASI KEPERLUAN
                $keperluan = strtolower(trim($row->keperluan ?? ''));

                if (str_contains($keperluan, 'ppid')) {
                    $keperluan = 'PPID';
                } else {
                    $keperluan = 'Pelayanan PST';
                }

                // NORMALISASI JENIS
                $jenis = strtolower(trim($row->jenis_layanan ?? ''));

                if ($jenis === '') {
                    $jenis = null;
                } elseif (str_contains($jenis, 'konsultasi')) {
                    $jenis = 'Konsultasi Statistik';
                } elseif (str_contains($jenis, 'pustaka')) {
                    $jenis = 'Perpustakaan';
                } elseif (str_contains($jenis, 'mikro')) {
                    $jenis = 'Data Mikro';
                } else {
                    $jenis = ucfirst($jenis);
                }

                return (object)[
                    'keperluan'      => $keperluan,
                    'jenis_layanan' => $jenis,
                ];
            });

        // =========================
        // 5. PPID
        // =========================
        $ppid = $breakdown
            ->where('keperluan', 'PPID')
            ->count();

        // =========================
        // 6. PST
        // =========================
        $pst = $breakdown
            ->where('keperluan', 'Pelayanan PST');

        $pstGrouped = $pst
            ->groupBy(function ($item) {
                return $item->jenis_layanan ?? 'Lainnya';
            })
            ->map(function ($items) {
                return $items->count();
            })
            ->sortKeys();

        $totalPst = $pst->count();

        // =========================
        // 7. VALIDASI (OPSIONAL)
        // =========================
        /*
        if ($total !== ($ppid + $totalPst)) {
            dd('Mismatch!', compact('total', 'ppid', 'totalPst'));
        }
        */

        return [
            'data'       => $data,
            'total'      => $total,
            'ppid'       => $ppid,
            'pstGrouped' => $pstGrouped,
            'totalPst'   => $totalPst,
            'month'      => $month,
        ];
    }
}