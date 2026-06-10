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

        return view('pelapor.laporan.create', compact('daftarRuangan'));
    }

    public function store(Request $request)
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

        Laporan::create([
            'pelapor_id'    => auth()->id(),
            'judul'         => $request->judul,
            'lokasi'        => $request->lokasi,
            'deskripsi'     => $request->deskripsi,
            'foto_sebelum'  => $path,
            'status'        => 'Baru',
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
