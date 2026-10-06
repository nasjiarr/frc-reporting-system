<?php

namespace App\Http\Controllers;

use App\Models\Penugasan;
use App\Models\HasilPerbaikan;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TeknisiController extends Controller
{
    public function dashboard()
    {
        $userId = auth()->id();

        // 1. Data untuk KPI Stat Cards
        $totalDitugaskan = Penugasan::where('teknisi_id', $userId)
            ->where('status_tugas', 'Ditugaskan')
            ->count();

        $totalDikerjakan = Penugasan::where('teknisi_id', $userId)
            ->where('status_tugas', 'Dikerjakan')
            ->count();

        $totalAktif = $totalDitugaskan + $totalDikerjakan;

        $totalSelesaiBulanIni = Penugasan::where('teknisi_id', $userId)
            ->where('status_tugas', 'Selesai')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();

        $totalSelesaiSemua = Penugasan::where('teknisi_id', $userId)
            ->where('status_tugas', 'Selesai')
            ->count();

        // Variabel lama untuk kompatibilitas
        $jumlahTugasAktif = $totalAktif;
        $jumlahTugasSelesai = $totalSelesaiSemua;

        // 2. Data untuk Tabel/Kartu Tugas Aktif Saat Ini
        $tugasAktif = Penugasan::with(['laporan.pelapor', 'assigner'])
            ->where('teknisi_id', $userId)
            ->whereIn('status_tugas', ['Ditugaskan', 'Dikerjakan'])
            ->latest()
            ->get();

        return view('teknisi.dashboard', compact(
            'totalDitugaskan',
            'totalDikerjakan',
            'totalAktif',
            'totalSelesaiBulanIni',
            'totalSelesaiSemua',
            'jumlahTugasAktif',
            'jumlahTugasSelesai',
            'tugasAktif'
        ));
    }

    public function tugasAktif()
    {
        $tugasAktif = Penugasan::with(['laporan.pelapor', 'assigner'])
            ->where('teknisi_id', auth()->id())
            ->whereIn('status_tugas', ['Ditugaskan', 'Dikerjakan'])
            ->latest()
            ->get();

        return view('teknisi.tugas-aktif', compact('tugasAktif'));
    }

    public function mulaiKerjakan($id)
    {
        $tugas = Penugasan::with('laporan.pelapor')->findOrFail($id);

        if ($tugas->teknisi_id !== auth()->id()) {
            abort(403);
        }

        if ($tugas->status_tugas === 'Ditugaskan') {
            DB::transaction(function () use ($tugas) {
                $tugas->update(['status_tugas' => 'Dikerjakan']);

                if ($tugas->laporan && $tugas->laporan->status !== 'Diproses') {
                    $tugas->laporan->update(['status' => 'Diproses']);
                }

                // Kirim notifikasi ke pelapor
                if ($tugas->laporan && $tugas->laporan->pelapor_id) {
                    $teknisiNama = auth()->user()->nama_lengkap ?? auth()->user()->name ?? 'Teknisi';
                    $pelaporLink = match ($tugas->laporan->pelapor?->role) {
                        'Admin'     => route('admin.laporan.show', $tugas->laporan->id, false),
                        'KepalaFRC' => route('kepala.laporan.show', $tugas->laporan->id, false),
                        default     => route('pelapor.laporan.show', $tugas->laporan->id, false),
                    };

                    Notifikasi::create([
                        'user_id' => $tugas->laporan->pelapor_id,
                        'judul' => 'Perbaikan Dimulai',
                        'pesan' => "Teknisi {$teknisiNama} telah mulai mengerjakan perbaikan untuk laporan: {$tugas->laporan->judul}.",
                        'link'  => $pelaporLink,
                    ]);
                }
            });

            return redirect()->back()->with('success', 'Status penugasan berhasil diperbarui menjadi Dikerjakan.');
        }

        return redirect()->back()->with('info', 'Penugasan sudah berstatus ' . $tugas->status_tugas . '.');
    }

    public function show($id)
    {
        $tugas = Penugasan::with('laporan.pelapor')->where('teknisi_id', auth()->id())->findOrFail($id);
        return view('teknisi.tugas-detail', compact('tugas'));
    }

    public function updateProgress(Request $request, $id)
    {
        $tugas = Penugasan::findOrFail($id);

        if ($tugas->teknisi_id !== auth()->id()) {
            abort(403);
        }

        // 1. Sesuaikan validasi dengan UI terbaru
        $request->validate([
            'tindakan' => 'required|string',
            'material_digunakan' => 'nullable|string',
            // foto_sebelum dihapus dari validasi
            // foto_sesudah diubah menjadi wajib (required)
            'foto_sesudah' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'foto_sesudah.required' => 'Foto bukti hasil perbaikan wajib diunggah.',
            'foto_sesudah.max' => 'Ukuran foto maksimal adalah 2MB.'
        ]);

        DB::transaction(function () use ($request, $tugas) {
            // 2. Upload Foto Sesudah Saja
            $pathSesudah = null;
            if ($request->hasFile('foto_sesudah')) {
                $pathSesudah = $request->file('foto_sesudah')->store('perbaikan/sesudah', 'public');
            }

            // 3. Simpan Hasil Perbaikan
            HasilPerbaikan::create([
                'penugasan_id' => $tugas->id,
                'tindakan' => $request->tindakan,
                'material_digunakan' => $request->material_digunakan,
                'foto_sesudah' => $pathSesudah,
                'selesai_pada' => now(),
            ]);

            // 4. Update Status Tugas & Laporan
            $tugas->update(['status_tugas' => 'Selesai']);
            $tugas->laporan->update(['status' => 'Selesai']);

            // 5. Kirim Notifikasi ke Admin yang menugaskan
            if ($tugas->assigned_by) {
                Notifikasi::create([
                    'user_id' => $tugas->assigned_by,
                    'judul' => 'Pekerjaan Selesai',
                    'pesan' => "Teknisi {$tugas->teknisi->nama_lengkap} telah menyelesaikan perbaikan: {$tugas->laporan->judul}.",
                    'link'  => route('admin.laporan.show', $tugas->laporan_id, false),
                ]);
            }

            // 6. Kirim Notifikasi ke Pelapor
            $pelaporLink = match ($tugas->laporan->pelapor?->role) {
                'Admin'     => route('admin.laporan.show', $tugas->laporan_id, false),
                'KepalaFRC' => route('kepala.laporan.show', $tugas->laporan_id, false),
                default     => route('pelapor.laporan.show', $tugas->laporan_id, false),
            };

            Notifikasi::create([
                'user_id' => $tugas->laporan->pelapor_id,
                'judul' => 'Perbaikan Selesai',
                'pesan' => "Laporan Anda yang berjudul '{$tugas->laporan->judul}' telah selesai diperbaiki oleh teknisi. Silakan cek hasilnya.",
                'link'  => $pelaporLink,
            ]);
        });

        return redirect()->route('teknisi.dashboard')->with('success', 'Laporan perbaikan telah dikirim.');
    }

    public function riwayat(Request $request)
    {
        $userId = auth()->id();

        // Query dasar untuk penugasan selesai milik teknisi ini
        $baseQuery = Penugasan::where('teknisi_id', $userId)
            ->where('status_tugas', 'Selesai');

        // Statistik KPI Pekerjaan Selesai
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'bulan_ini' => (clone $baseQuery)->whereHas('hasilPerbaikan', function ($q) {
                $q->whereMonth('selesai_pada', now()->month)
                  ->whereYear('selesai_pada', now()->year);
            })->count(),
            'minggu_ini' => (clone $baseQuery)->whereHas('hasilPerbaikan', function ($q) {
                $q->whereBetween('selesai_pada', [now()->startOfWeek(), now()->endOfWeek()]);
            })->count(),
            'hari_ini' => (clone $baseQuery)->whereHas('hasilPerbaikan', function ($q) {
                $q->whereDate('selesai_pada', now()->today());
            })->count(),
        ];

        // Query riwayat dengan eager loading lengkap untuk menghindari N+1 queries
        $query = Penugasan::with(['laporan.pelapor', 'hasilPerbaikan', 'assigner'])
            ->where('teknisi_id', $userId)
            ->where('status_tugas', 'Selesai');

        // Pencarian multi-kolom (Judul, Lokasi, Deskripsi, Pelapor, Tindakan, Material)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereHas('laporan', function ($qLaporan) use ($search) {
                    $qLaporan->where('judul', 'like', "%{$search}%")
                        ->orWhere('lokasi', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%")
                        ->orWhereHas('pelapor', function ($qPelapor) use ($search) {
                            $qPelapor->where('nama_lengkap', 'like', "%{$search}%")
                                ->orWhere('name', 'like', "%{$search}%");
                        });
                })->orWhereHas('hasilPerbaikan', function ($qHasil) use ($search) {
                    $qHasil->where('tindakan', 'like', "%{$search}%")
                        ->orWhere('material_digunakan', 'like', "%{$search}%");
                });
            });
        }

        // Filter Periode
        if ($request->filled('periode')) {
            if ($request->periode === 'bulan_ini') {
                $query->whereHas('hasilPerbaikan', function ($q) {
                    $q->whereMonth('selesai_pada', now()->month)
                      ->whereYear('selesai_pada', now()->year);
                });
            } elseif ($request->periode === 'bulan_lalu') {
                $lastMonth = now()->subMonth();
                $query->whereHas('hasilPerbaikan', function ($q) use ($lastMonth) {
                    $q->whereMonth('selesai_pada', $lastMonth->month)
                      ->whereYear('selesai_pada', $lastMonth->year);
                });
            } elseif ($request->periode === 'tahun_ini') {
                $query->whereHas('hasilPerbaikan', function ($q) {
                    $q->whereYear('selesai_pada', now()->year);
                });
            }
        }

        // Tampilkan yang paling baru selesai di atas
        $riwayat = $query->latest('updated_at')->paginate(10)->withQueryString();

        return view('teknisi.riwayat', compact('riwayat', 'stats'));
    }

    public function exportPdf($id)
    {
        $penugasan = Penugasan::with(['laporan.pelapor', 'teknisi', 'assigner', 'hasilPerbaikan'])
            ->where('teknisi_id', auth()->id())
            ->findOrFail($id);

        if ($penugasan->status_tugas !== 'Selesai') {
            return back()->with('error', 'Pekerjaan belum selesai.');
        }

        if (!$penugasan->hasilPerbaikan) {
            return back()->with('error', 'Data hasil perbaikan belum lengkap untuk dicetak.');
        }

        $laporan = $penugasan->laporan;

        // 1. Konversi Foto SEBELUM (dari Pelapor)
        $fotoSebelumBase64 = null;
        $pathSebelum = $laporan->foto_sebelum ? public_path('storage/' . $laporan->foto_sebelum) : null;
        if ($pathSebelum && file_exists($pathSebelum)) {
            $dataSebelum = file_get_contents($pathSebelum);
            $fotoSebelumBase64 = 'data:image/' . pathinfo($pathSebelum, PATHINFO_EXTENSION) . ';base64,' . base64_encode($dataSebelum);
        }

        // 2. Konversi Foto SESUDAH (dari Teknisi)
        $fotoSesudahBase64 = null;
        $pathSesudah = $penugasan->hasilPerbaikan?->foto_sesudah ? public_path('storage/' . $penugasan->hasilPerbaikan->foto_sesudah) : null;
        if ($pathSesudah && file_exists($pathSesudah)) {
            $dataSesudah = file_get_contents($pathSesudah);
            $fotoSesudahBase64 = 'data:image/' . pathinfo($pathSesudah, PATHINFO_EXTENSION) . ';base64,' . base64_encode($dataSesudah);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.pdf', compact('laporan', 'fotoSebelumBase64', 'fotoSesudahBase64'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("Berita_Acara_Perbaikan_FRC_{$laporan->id}.pdf");
    }
}
