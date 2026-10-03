<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\User;
use App\Models\Notifikasi;

class PelaporController extends Controller
{
    public function dashboard()
    {
        $userId = auth()->id();
        $statistik = [
            'total'    => Laporan::where('pelapor_id', $userId)->count(),
            'baru'     => Laporan::where('pelapor_id', $userId)->where('status', 'Baru')->count(),
            'diproses' => Laporan::where('pelapor_id', $userId)->where('status', 'Diproses')->count(),
            'selesai'  => Laporan::where('pelapor_id', $userId)->where('status', 'Selesai')->count(),
            'ditolak'  => Laporan::where('pelapor_id', $userId)->where('status', 'Ditolak')->count(),
        ];

        $laporanTerbaru = Laporan::where('pelapor_id', $userId)->latest()->take(5)->get();

        return view('pelapor.dashboard', compact('statistik', 'laporanTerbaru'));
    }

    public function index(Request $request)
    {
        $query = Laporan::where('pelapor_id', auth()->id())->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $laporans = $query->paginate(10);
        return view('pelapor.laporan.index', compact('laporans'));
    }

    public function create()
    {
        $daftarRuangan = config('frc.ruangan', []);

        return view('pelapor.laporan.create', compact('daftarRuangan'));
    }

    public function store(Request $request)
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
                'pesan'   => "Terdapat laporan baru mengenai '{$laporan->judul}' di {$laporan->lokasi}.",
            ]);
        }

        return redirect()->route('pelapor.laporan.index')->with('success', 'Laporan berhasil dikirim.');
    }

    public function show($id)
    {
        // Pastikan pelapor hanya bisa melihat laporannya sendiri (Keamanan)
        $laporan = Laporan::with(['penugasan.teknisi', 'penugasan.hasilPerbaikan'])
            ->where('pelapor_id', auth()->id())
            ->findOrFail($id);

        return view('pelapor.laporan.show', compact('laporan'));
    }
}
