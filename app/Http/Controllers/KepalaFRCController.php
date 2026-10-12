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
        $totalMasuk = Laporan::count();
        $sedangDikerjakan = Laporan::where('status', 'Diproses')->count();
        $totalSelesai = Laporan::where('status', 'Selesai')->count();
        $baruCount = Laporan::where('status', 'Baru')->count();
        $ditugaskanCount = Laporan::where('status', 'Ditugaskan')->count();
        $ditolakCount = Laporan::where('status', 'Ditolak')->count();

        $completionRate = $totalMasuk > 0 ? round(($totalSelesai / $totalMasuk) * 100, 1) : 0;
        $totalTeknisi = User::where('role', 'Teknisi')->count();
        $currentMonth = date('Y-m');
        $utilitasBulanIni = Utilitas::where('periode', $currentMonth)->count();

        $kpi = [
            'total_masuk'       => $totalMasuk,
            'sedang_dikerjakan' => $sedangDikerjakan,
            'total_selesai'     => $totalSelesai,
            'baru'              => $baruCount,
            'ditugaskan'        => $ditugaskanCount,
            'ditolak'           => $ditolakCount,
            'completion_rate'   => $completionRate,
            'total_teknisi'     => $totalTeknisi,
            'utilitas_bulan_ini' => $utilitasBulanIni,
        ];

        $laporanTerbaru = Laporan::with(['pelapor', 'penugasan.teknisi'])
            ->latest()
            ->take(5)
            ->get();

        $kinerjaTeknisi = User::where('role', 'Teknisi')
            ->withCount(['tugas_teknisi as selesai_count' => function ($query) {
                $query->whereHas('laporan', function ($q) {
                    $q->where('status', 'Selesai');
                });
            }])
            ->orderBy('selesai_count', 'desc')
            ->take(5)
            ->get();

        return view('kepala.dashboard', compact('kpi', 'laporanTerbaru', 'kinerjaTeknisi'));
    }

    public function rekapUtilitas(Request $request)
    {
        $periode = $request->get('bulan', date('Y-m'));

        // Detail tabel
        $details = Utilitas::with(['petugas', 'airBersih', 'airHujan', 'listrikMdp', 'listrikSdp', 'listrikLift', 'listrikAc', 'listrikLampu'])
            ->where('periode', $periode)
            ->get();

        $totalListrik = 0;
        $totalAir = 0;
        foreach ($details as $d) {
            if (in_array($d->jenis_utilitas, ['AirBersih', 'AirHujan'])) {
                $totalAir += $d->total_konsumsi;
            } else {
                $totalListrik += $d->total_konsumsi;
            }
        }

        $kategoriTercatat = $details->pluck('jenis_utilitas')->unique()->count();
        $totalKategori = count(Utilitas::JENIS_UTILITAS);

        return view('kepala.utilitas-rekap', compact(
            'details',
            'periode',
            'totalListrik',
            'totalAir',
            'kategoriTercatat',
            'totalKategori'
        ));
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
        $details = Utilitas::with(['petugas', 'airBersih', 'airHujan', 'listrikMdp', 'listrikSdp', 'listrikLift', 'listrikAc', 'listrikLampu'])
            ->where('periode', $periode)
            ->get();

        $pdf = Pdf::loadView('kepala.utilitas-pdf', compact('details', 'periode'));

        return $pdf->download("Laporan-Utilitas-{$periode}.pdf");
    }

    public function rekapLaporan(Request $request)
    {
        $query = Laporan::with(['pelapor', 'penugasan.teknisi']);

        // 1. Filter Status
        if ($request->filled('status') && $request->status !== 'Semua') {
            $status = $request->status === 'Menunggu' ? 'Baru' : $request->status;
            $query->where('status', $status);
        }

        // 2. Filter Periode (Format input month HTML5 adalah 'YYYY-MM')
        if ($request->filled('periode')) {
            $query->where('created_at', 'like', $request->periode . '%');
        }

        // 3. Pencarian Kata Kunci
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhereHas('pelapor', function ($sub) use ($search) {
                      $sub->where('nama_lengkap', 'like', "%{$search}%")
                          ->orWhere('name', 'like', "%{$search}%");
                  });
            });
        }

        $stats = [
            'total'    => Laporan::count(),
            'baru'     => Laporan::where('status', 'Baru')->count(),
            'diproses' => Laporan::where('status', 'Diproses')->count(),
            'selesai'  => Laporan::where('status', 'Selesai')->count(),
            'ditolak'  => Laporan::where('status', 'Ditolak')->count(),
        ];

        $laporans = $query->latest()->paginate(15)->withQueryString();

        return view('kepala.laporan.index', compact('laporans', 'stats'));
    }

    public function showLaporan($id)
    {
        $laporan = \App\Models\Laporan::with(['pelapor', 'penugasan.teknisi', 'penugasan.hasilPerbaikan'])->findOrFail($id);

        return view('kepala.laporan.show', compact('laporan'));
    }

    public function createLaporan()
    {
        $daftarRuangan = config('frc.ruangan', []);

        return view('kepala.laporan.create', compact('daftarRuangan'));
    }

    public function storeLaporan(Request $request)
    {
        $request->validate([
            'judul'         => 'required|string|max:255',
            'lokasi'        => 'required|string|max:255',
            'deskripsi'     => 'required|string',
            'foto_sebelum'  => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'foto_sebelum.required' => 'Foto bukti kerusakan wajib diunggah.',
            'foto_sebelum.image'    => 'File bukti kerusakan harus berupa gambar.',
            'foto_sebelum.mimes'    => 'Format foto harus berupa JPG atau PNG.',
            'foto_sebelum.max'      => 'Ukuran foto maksimal adalah 2 MB.',
        ]);

        $path = null;
        if ($request->hasFile('foto_sebelum')) {
            $path = $request->file('foto_sebelum')->store('foto_sebelum', 'public');
        }

        DB::transaction(function () use ($request, $path) {
            $laporan = Laporan::create([
                'pelapor_id'    => auth()->id(),
                'judul'         => $request->judul,
                'lokasi'        => $request->lokasi,
                'deskripsi'     => $request->deskripsi,
                'foto_sebelum'  => $path,
                'status'        => 'Baru',
            ]);

            // Mengirim notifikasi ke semua Admin yang aktif
            $admins = User::where('role', 'Admin')->where('is_active', true)->get();
            foreach ($admins as $admin) {
                Notifikasi::create([
                    'user_id' => $admin->id,
                    'judul'   => 'Laporan Kerusakan Baru',
                    'pesan'   => "Terdapat laporan baru dari Kepala FRC mengenai '{$laporan->judul}' di {$laporan->lokasi}.",
                    'link'    => route('admin.laporan.show', $laporan->id, false),
                ]);
            }
        });

        return redirect()->route('kepala.laporan.index')->with('success', 'Laporan berhasil dikirim.');
    }

    public function laporanSaya(Request $request)
    {
        $userId = auth()->id();
        $query = Laporan::with(['penugasan.teknisi'])
            ->where('pelapor_id', $userId);

        // 1. Filter Status
        if ($request->filled('status') && $request->status !== 'Semua') {
            $status = $request->status === 'Menunggu' ? 'Baru' : $request->status;
            $query->where('status', $status);
        }

        // 2. Filter Periode
        if ($request->filled('periode')) {
            $query->where('created_at', 'like', $request->periode . '%');
        }

        // 3. Search Kata Kunci
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $stats = [
            'total'    => Laporan::where('pelapor_id', $userId)->count(),
            'baru'     => Laporan::where('pelapor_id', $userId)->where('status', 'Baru')->count(),
            'diproses' => Laporan::where('pelapor_id', $userId)->where('status', 'Diproses')->count(),
            'selesai'  => Laporan::where('pelapor_id', $userId)->where('status', 'Selesai')->count(),
            'ditolak'  => Laporan::where('pelapor_id', $userId)->where('status', 'Ditolak')->count(),
        ];

        $laporans = $query->latest()->paginate(10)->withQueryString();

        return view('kepala.laporan.saya', compact('laporans', 'stats'));
    }
}
