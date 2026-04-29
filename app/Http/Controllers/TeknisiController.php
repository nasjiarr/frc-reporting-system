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

        // 1. Data untuk Cards
        $jumlahTugasAktif = Penugasan::where('teknisi_id', $userId)
            ->whereIn('status_tugas', ['Ditugaskan', 'Dikerjakan'])
            ->count();

        $jumlahTugasSelesai = Penugasan::where('teknisi_id', $userId)
            ->where('status_tugas', 'Selesai')
            ->count();

        // 2. Data untuk Tabel Tugas Aktif Saat Ini
        $tugasAktif = Penugasan::with('laporan')
            ->where('teknisi_id', $userId)
            ->whereIn('status_tugas', ['Ditugaskan', 'Dikerjakan'])
            ->latest()
            ->get();

        return view('teknisi.dashboard', compact('jumlahTugasAktif', 'jumlahTugasSelesai', 'tugasAktif'));
    }

    // Tambahkan method baru ini
    public function tugasAktif()
    {
        $tugasAktif = Penugasan::with('laporan')
            ->where('teknisi_id', auth()->id())
            ->whereIn('status_tugas', ['Ditugaskan', 'Dikerjakan'])
            ->latest()
            ->get();

        return view('teknisi.tugas-aktif', compact('tugasAktif'));
    }

    public function show($id)
    {
        $tugas = Penugasan::with('laporan.pelapor')->where('teknisi_id', auth()->id())->findOrFail($id);
        return view('teknisi.tugas-detail', compact('tugas'));
    }

    public function updateProgress(Request $request, $id)
    {
        $tugas = Penugasan::findOrFail($id);

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

            // 5. Kirim Notifikasi ke Admin
            Notifikasi::create([
                'user_id' => $tugas->assigned_by,
                'judul' => 'Pekerjaan Selesai',
                'pesan' => "Teknisi {$tugas->teknisi->nama_lengkap} telah menyelesaikan perbaikan: {$tugas->laporan->judul}.",
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
