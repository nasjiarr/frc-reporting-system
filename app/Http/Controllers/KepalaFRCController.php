<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\User;
use App\Models\Utilitas;
use App\Models\Penugasan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KepalaFRCController extends Controller
{
    public function dashboard()
    {
        $kpi = [
            'total_masuk' => Laporan::count(),
            'total_selesai' => Laporan::where('status', 'Selesai')->count(),
            // Menghitung rata-rata waktu penanganan dalam jam
            'rata_waktu' => DB::table('laporan')
                ->join('penugasan', 'laporan.id', '=', 'penugasan.laporan_id')
                ->join('hasil_perbaikan', 'penugasan.id', '=', 'hasil_perbaikan.penugasan_id')
                ->selectRaw('ROUND(AVG(TIMESTAMPDIFF(HOUR, laporan.created_at, hasil_perbaikan.selesai_pada)), 1) as avg_hours')
                ->first()->avg_hours ?? 0
        ];

        return view('kepala.dashboard', compact('kpi'));
    }

    public function rekapUtilitas(Request $request)
    {
        $periode = $request->get('bulan', date('Y-m'));

        // Data untuk Grafik (6 bulan terakhir)
        $chartData = Utilitas::select('periode', 'jenis_utilitas')
            ->where('periode', '<=', $periode)
            ->orderBy('periode', 'asc')
            ->take(12)
            ->get()
            ->groupBy('periode');

        // Detail tabel
        $details = Utilitas::with('petugas')->where('periode', $periode)->get();

        return view('kepala.utilitas-rekap', compact('details', 'chartData', 'periode'));
    }

    public function kinerjaTeknisi()
    {
        $kinerja = User::where('role', 'Teknisi')
            ->withCount(['tugas_teknisi as selesai_count' => function ($query) {
                $query->whereHas('laporan', function ($q) {
                    $q->where('status', 'Selesai');
                });
            }])
            ->orderBy('selesai_count', 'desc')
            ->get();

        return view('kepala.kinerja-teknisi', compact('kinerja'));
    }

    public function exportPdf(Request $request)
    {
        $periode = $request->get('bulan', date('Y-m'));
        $details = Utilitas::with('petugas')->where('periode', $periode)->get();

        $pdf = Pdf::loadView('kepala.utilitas-pdf', compact('details', 'periode'));

        return $pdf->download("Laporan-Utilitas-{$periode}.pdf");
    }

    public function rekapLaporan(Request $request)
    {
        $query = \App\Models\Laporan::with(['pelapor', 'penugasan.teknisi']);

        // 1. Filter Status
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }

        // 2. Filter Periode (Format input month HTML5 adalah 'YYYY-MM')
        if ($request->filled('periode')) {
            $query->where('created_at', 'like', $request->periode . '%');
        }

        $laporans = $query->latest()->paginate(15)->withQueryString();

        return view('kepala.laporan.index', compact('laporans'));
    }

    public function showLaporan($id)
    {
        $laporan = \App\Models\Laporan::with(['pelapor', 'penugasan.teknisi', 'penugasan.hasilPerbaikan'])->findOrFail($id);

        return view('kepala.laporan.show', compact('laporan'));
    }
}
