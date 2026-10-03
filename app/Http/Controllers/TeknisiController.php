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
                    Notifikasi::create([
                        'user_id' => $tugas->laporan->pelapor_id,
                        'judul' => 'Perbaikan Dimulai',
                        'pesan' => "Teknisi {$teknisiNama} telah mulai mengerjakan perbaikan untuk laporan: {$tugas->laporan->judul}.",
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
                'material' => $request->material_digunakan, // Kolom di DB bernama 'material'
                'foto_sesudah' => $pathSesudah, // Hanya menyimpan foto_sesudah
                'selesai_pada' => now(),
            ]);

            // 4. Update Status Tugas & Laporan
            $tugas->update(['status_tugas' => 'Selesai']);
            $tugas->laporan->update(['status' => 'Selesai']);

            // 5. Kirim Notifikasi ke Admin yang menugaskan
            Notifikasi::create([
                'user_id' => $tugas->assigned_by,
                'judul' => 'Pekerjaan Selesai',
                'pesan' => "Teknisi {$tugas->teknisi->nama_lengkap} telah menyelesaikan perbaikan: {$tugas->laporan->judul}.",
            ]);

            // 6. Kirim Notifikasi ke Pelapor
            Notifikasi::create([
                'user_id' => $tugas->laporan->pelapor_id,
                'judul' => 'Perbaikan Selesai',
                'pesan' => "Laporan Anda yang berjudul '{$tugas->laporan->judul}' telah selesai diperbaiki oleh teknisi. Silakan cek hasilnya.",
            ]);
        });

        return redirect()->route('teknisi.dashboard')->with('success', 'Laporan perbaikan telah dikirim.');
    }

    public function riwayat()
    {
        $riwayat = Penugasan::with('laporan')
            ->where('teknisi_id', auth()->id())
            ->whereHas('laporan', function ($query) {
                $query->where('status', 'Selesai');
            })
            ->latest()
            ->paginate(10);

        return view('teknisi.riwayat', compact('riwayat'));
    }
}
