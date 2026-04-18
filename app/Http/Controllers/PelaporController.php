<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;

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
        return view('pelapor.laporan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'lokasi'    => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        Laporan::create([
            'pelapor_id' => auth()->id(), // Keamanan: Paksa gunakan ID user login
            'judul'      => $request->judul,
            'lokasi'     => $request->lokasi,
            'deskripsi'  => $request->deskripsi,
            'status'     => 'Baru',
        ]);

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
