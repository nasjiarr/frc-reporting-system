<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Laporan;
use App\Models\Penugasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Data Statistik (DIPERBARUI DENGAN METRIK BARU)
        $stats = [
            // Laporan Baru
            'laporan_baru' => \App\Models\Laporan::where('status', 'Baru')->count(),

            // Penugasan Aktif (Bisa dari tabel Penugasan atau Laporan, sesuai logika asli Anda)
            'tugas_aktif'  => \App\Models\Penugasan::whereIn('status_tugas', ['Ditugaskan', 'Dikerjakan'])->count(),

            // Laporan Selesai Bulan Ini
            'selesai_bulan_ini' => \App\Models\Laporan::where('status', 'Selesai')
                ->whereMonth('updated_at', \Carbon\Carbon::now()->month)
                ->whereYear('updated_at', \Carbon\Carbon::now()->year)
                ->count(),

            // Pengguna Aktif
            'pengguna_aktif' => \App\Models\User::where('is_active', true)->count(),
        ];

        // 2. Data Panel Kiri: Laporan Perlu Tindak Lanjut (Status 'Baru')
        $laporanPerluTindakLanjut = \App\Models\Laporan::with('pelapor')
            ->where('status', 'Baru')
            ->latest()
            ->take(5)
            ->get();

        // 3. Data Panel Kanan: Penugasan Aktif (Ditugaskan / Dikerjakan)
        $penugasanAktif = \App\Models\Penugasan::with(['laporan', 'teknisi'])
            ->has('laporan')
            ->whereIn('status_tugas', ['Ditugaskan', 'Dikerjakan'])
            ->latest('assigned_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'laporanPerluTindakLanjut', 'penugasanAktif'));
    }

    public function index(Request $request)
    {
        $users = User::when($request->role, fn($q) => $q->where('role', $request->role))->get();
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_lengkap' => 'required|string',
            'email' => 'required|email|unique:users',
            'no_telepon' => 'required',
            'role' => 'required',
            'password' => 'required|min:8'
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function penugasanIndex()
    {
        $laporanBaru = Laporan::where('status', 'Baru')->latest()->get();
        $teknisi = User::where('role', 'Teknisi')->get();
        return view('admin.penugasan.index', compact('laporanBaru', 'teknisi'));
    }

    public function assignStore(Request $request, Laporan $laporan)
    {
        $request->validate(['teknisi_id' => 'required|exists:users,id']);

        // HAPUS backslash (\) sebelum DB, sehingga menjadi seperti ini:
        DB::transaction(function () use ($request, $laporan) {
            Penugasan::create([
                'laporan_id' => $laporan->id,
                'teknisi_id' => $request->teknisi_id,
                'assigned_by' => auth()->id(),
                'instruksi' => $request->instruksi,
                'status_tugas' => 'Ditugaskan',
                'assigned_at' => now(),
            ]);

            $laporan->update(['status' => 'Diproses']);
        });

        return back()->with('success', 'Teknisi berhasil ditugaskan.');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'nama_lengkap' => 'required|string',
            // Validasi email harus unik, KECUALI untuk email milik user ini sendiri
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_telepon' => 'required|string',
            'role' => 'required|in:Admin,Teknisi,Pelapor,KepalaFRC',
            // Password opsional saat edit (hanya diisi jika ingin diganti)
            'password' => 'nullable|min:8'
        ]);

        // Cek apakah form password diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        } else {
            // Jika kosong, hapus 'password' dari array agar tidak tertimpa null di database
            unset($data['password']);
        }

        $user->update($data);

        return back()->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function laporanIndex()
    {
        // Mengambil laporan yang dibuat oleh Admin ini sendiri
        $laporans = Laporan::where('pelapor_id', auth()->id())->latest()->paginate(10);
        return view('admin.laporan.index', compact('laporans'));
    }

    public function laporanCreate()
    {
        return view('admin.laporan.create');
    }

    public function laporanStore(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        Laporan::create([
            'pelapor_id' => auth()->id(),
            'judul' => $request->judul,
            'lokasi' => $request->lokasi,
            'deskripsi' => $request->deskripsi,
            'status' => 'Baru',
        ]);

        return redirect()->route('admin.laporan.index')->with('success', 'Laporan Anda berhasil dibuat.');
    }

    public function laporanShow($id)
    {
        // 1. Ambil data laporan beserta seluruh relasinya
        $laporan = Laporan::with(['pelapor', 'penugasan.teknisi', 'penugasan.hasilPerbaikan'])->findOrFail($id);

        // 2. KARENA USER INI ADALAH ADMIN, IA BEBAS MELIHAT SEMUA LAPORAN
        // (Logika abort(403) sebelumnya telah dihapus)

        return view('admin.laporan.show', compact('laporan'));
    }

    // Fungsi untuk mengaktifkan/menonaktifkan user
    public function toggleStatus(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Tindakan Ditolak: Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        // Balikkan nilainya (Jika true jadi false, jika false jadi true)
        $user->update([
            'is_active' => !$user->is_active
        ]);

        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun {$user->nama_lengkap} berhasil {$statusText}.");
    }

    // Method untuk melihat daftar laporan yang sudah selesai (Arsip)
    public function laporanSelesai(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Laporan::with(['pelapor', 'penugasan.teknisi'])
            ->where('status', 'Selesai');

        // Fitur Pencarian (Search)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                    ->orWhere('lokasi', 'like', '%' . $search . '%')
                    // Cari berdasarkan nama pelapor
                    ->orWhereHas('pelapor', function ($qPelapor) use ($search) {
                        $qPelapor->where('nama_lengkap', 'like', '%' . $search . '%');
                    })
                    // Cari berdasarkan nama teknisi
                    ->orWhereHas('penugasan.teknisi', function ($qTeknisi) use ($search) {
                        $qTeknisi->where('nama_lengkap', 'like', '%' . $search . '%');
                    });
            });
        }

        // withQueryString() agar saat pindah halaman (pagination), kata kuncinya tidak hilang
        $laporans = $query->latest('updated_at')->paginate(15)->withQueryString();

        return view('admin.laporan.selesai', compact('laporans'));
    }

    public function laporanDestroy($id)
    {
        $laporan = \App\Models\Laporan::with('penugasan.hasilPerbaikan')->findOrFail($id);

        if ($laporan->penugasan) {
            $hasil = $laporan->penugasan->hasilPerbaikan;
            if ($hasil) {
                if ($hasil->foto_sebelum) Storage::disk('public')->delete($hasil->foto_sebelum);
                if ($hasil->foto_sesudah) Storage::disk('public')->delete($hasil->foto_sesudah);
            }
            // Tambahkan baris ini untuk menghapus penugasan terkait
            $laporan->penugasan->delete();
        }

        $laporan->delete();

        return back()->with('success', 'Laporan beserta bukti dokumentasinya berhasil dihapus permanen.');
    }

    // Method untuk halaman Rekap Utilitas Admin
    public function utilitasIndex()
    {
        $rekapUtilitas = \App\Models\Utilitas::with([
            'petugas',
            'airBersih',
            'airHujan',
            'listrikMdp',
            'listrikSdp',
            'listrikLift',
            'listrikAc',
            'listrikLampu'
        ])->latest()->paginate(15);

        return view('admin.utilitas.index', compact('rekapUtilitas'));
    }

    // Method untuk Memperbarui Data Utilitas (Edit)
    public function utilitasUpdate(Request $request, \App\Models\Utilitas $utilitas)
    {
        $request->validate([
            'tanggal_pencatatan' => 'required|date',
            'listrik_kwh' => 'required|numeric|min:0',
            'air_m3' => 'required|numeric|min:0',
        ]);

        $utilitas->update([
            'tanggal_pencatatan' => $request->tanggal_pencatatan,
            'listrik_kwh' => $request->listrik_kwh,
            'air_m3' => $request->air_m3,
        ]);

        return back()->with('success', 'Data pencatatan utilitas berhasil diperbarui.');
    }

    // Method untuk Menghapus Data Utilitas
    public function utilitasDestroy(\App\Models\Utilitas $utilitas)
    {
        $utilitas->delete();

        return back()->with('success', 'Data pencatatan utilitas berhasil dihapus secara permanen.');
    }

    public function utilitasEdit($id)
    {
        // Ambil data utilitas beserta semua kemungkinan relasi tabel turunannya
        $utilitas = \App\Models\Utilitas::with([
            'airBersih',
            'airHujan',
            'listrikMdp',
            'listrikSdp',
            'listrikLift',
            'listrikAc',
            'listrikLampu'
        ])->findOrFail($id);

        return view('admin.utilitas.edit', compact('utilitas'));
    }
}
