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
        // Tugas yang statusnya belum selesai (Ditugaskan atau Dikerjakan)
        $tugasAktif = Penugasan::with('laporan')
            ->where('teknisi_id', auth()->id())
            ->whereIn('status_tugas', ['Ditugaskan', 'Dikerjakan'])
            ->latest()
            ->get();

        return view('teknisi.dashboard', compact('tugasAktif'));
    }

    public function show($id)
    {
        $tugas = Penugasan::with('laporan.pelapor')->where('teknisi_id', auth()->id())->findOrFail($id);
        return view('teknisi.tugas-detail', compact('tugas'));
    }

    public function updateProgress(Request $request, $id)
    {
        $tugas = Penugasan::findOrFail($id);

        $request->validate([
            'tindakan' => 'required|string',
            'material_digunakan' => 'nullable|string',
            'foto_sebelum' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_sesudah' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::transaction(function () use ($request, $tugas) {
            // 1. Upload Foto
            $pathSebelum = $request->file('foto_sebelum') ? $request->file('foto_sebelum')->store('perbaikan/sebelum', 'public') : null;
            $pathSesudah = $request->file('foto_sesudah') ? $request->file('foto_sesudah')->store('perbaikan/sesudah', 'public') : null;

            // 2. Simpan Hasil Perbaikan
            HasilPerbaikan::create([
                'penugasan_id' => $tugas->id,
                'tindakan' => $request->tindakan,
                'material_digunakan' => $request->material_digunakan,
                'foto_sebelum' => $pathSebelum,
                'foto_sesudah' => $pathSesudah,
                'selesai_pada' => now(),
            ]);

            // 3. Update Status Tugas & Laporan
            $tugas->update(['status_tugas' => 'Dikerjakan']); // Atau langsung selesai sesuai alur
            $tugas->laporan->update(['status' => 'Selesai']);

            // 4. Kirim Notifikasi ke Admin (Notifikasi ke Pelapor sudah dihandle Observer Laporan)
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
