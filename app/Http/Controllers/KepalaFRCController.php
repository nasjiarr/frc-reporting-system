<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\User;
use App\Models\Utilitas;
use App\Models\Penugasan;
use App\Models\Notifikasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KepalaFRCController extends Controller
{
    public function dashboard()
    {
        $kpi = [
            'total_masuk' => Laporan::count(),
            'sedang_dikerjakan' => Laporan::where('status', 'Diproses')->count(),
            'total_selesai' => Laporan::where('status', 'Selesai')->count(),
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

    public function createLaporan()
    {
        // Daftar ruangan di FRC UGM (duplikat sudah dibersihkan)
        $daftarRuangan = [
            'Wood pellet production laboratory',
            'Patient simulators & phantoms for medical nursing production',
            'Product analysis & quality control laboratory',
            'Cocoa production laboratory',
            'Cocoa production laboratory - Packaging',
            'Dairy production laboratory',
            'Dairy production laboratory - Packaging',
            'Incubation & Design Room 1',
            'Incubation & Design Room 2',
            'Incubation & Design Room 3',
            'Incubation & Design Room 4',
            'IT Design Room',
            'Hall',
            'UGM Information (Showroom)',
            'Locker room',
            'Machine room',
            'Storage Room',
            'Product analysis & quality control laboratory - Head Room',
            'Meeting room - Lantai 2',
            'Meeting room - Lantai 3',
            'Conference room',
            'Office',
            'Head Room',
            'Personal computer room',
            'Seminar Room 1',
            'Seminar Room 2',
            'Seminar Room 3',
            'Open Terrace',
            'Mushola',
            'Panel Room',
            'Coom Room',
            'Wood Pellet Industry'
        ];

        return view('kepala.laporan.create', compact('daftarRuangan'));
    }

    public function storeLaporan(Request $request)
    {
        $request->validate([
            'judul'         => 'required|string|max:255',
            'lokasi'        => 'required|string|max:255',
            'deskripsi'     => 'required|string',
            'foto_sebelum'  => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('foto_sebelum')) {
            $path = $request->file('foto_sebelum')->store('foto_sebelum', 'public');
        }

        $laporan = Laporan::create([
            'pelapor_id'    => auth()->id(),
            'judul'         => $request->judul,
            'lokasi'        => $request->lokasi,
            'deskripsi'     => $request->deskripsi,
            'foto_sebelum'  => $path,
            'status'        => 'Baru',
        ]);

        // Mengirim notifikasi ke semua Admin
        $admins = User::where('role', 'Admin')->get();
        foreach ($admins as $admin) {
            Notifikasi::create([
                'user_id' => $admin->id,
                'judul'   => 'Laporan Kerusakan Baru',
                'pesan'   => "Terdapat laporan baru dari Kepala FRC mengenai '{$laporan->judul}' di {$laporan->lokasi}.",
            ]);
        }

        return redirect()->route('kepala.laporan.index')->with('success', 'Laporan berhasil dikirim.');
    }

    public function laporanSaya(Request $request)
    {
        $query = \App\Models\Laporan::where('pelapor_id', auth()->id())->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $laporans = $query->paginate(10)->withQueryString();
        return view('kepala.laporan.saya', compact('laporans'));
    }
}
