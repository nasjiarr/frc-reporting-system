<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

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
        // 1. Data Statistik (Eksis)
        $stats = [
            'laporan_baru' => \App\Models\Laporan::where('status', 'Baru')->count(),
            'tugas_aktif'  => \App\Models\Penugasan::whereIn('status_tugas', ['Ditugaskan', 'Dikerjakan'])->count(),
            'selesai_bulan_ini' => \App\Models\Laporan::where('status', 'Selesai')
                ->whereMonth('updated_at', \Carbon\Carbon::now()->month)
                ->whereYear('updated_at', \Carbon\Carbon::now()->year)
                ->count(),
            'pengguna_aktif' => \App\Models\User::where('is_active', true)->count(),
        ];

        // --- TAMBAHAN LOGIKA INSTRUKSI #3 ---
        // Cek apakah ada data di tabel utilitas dengan periode bulan ini (Format: YYYY-MM)
        $currentPeriode = now()->format('Y-m');
        $bln_ini_belum_isi = !\App\Models\Utilitas::where('periode', $currentPeriode)->exists();
        // ------------------------------------

        // 2. Data Panel Kiri & Kanan (Eksis)
        $laporanPerluTindakLanjut = \App\Models\Laporan::with('pelapor')
            ->where('status', 'Baru')
            ->latest()
            ->take(5)
            ->get();

        $penugasanAktif = \App\Models\Penugasan::with(['laporan', 'teknisi'])
            ->has('laporan')
            ->whereIn('status_tugas', ['Ditugaskan', 'Dikerjakan'])
            ->latest('assigned_at')
            ->take(5)
            ->get();

        // Kirim variabel bln_ini_belum_isi ke view
        return view('admin.dashboard', compact(
            'stats',
            'laporanPerluTindakLanjut',
            'penugasanAktif',
            'bln_ini_belum_isi'
        ));
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
        // Tambahkan validasi foto_sebelum
        $request->validate([
            'judul' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto_sebelum' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Aturan unggah file
        ], [
            'foto_sebelum.image' => 'File harus berupa gambar.',
            'foto_sebelum.mimes' => 'Format gambar harus JPG atau PNG.',
            'foto_sebelum.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Proses penyimpanan file foto
        $fotoPath = null;
        if ($request->hasFile('foto_sebelum')) {
            $fotoPath = $request->file('foto_sebelum')->store('foto_sebelum', 'public');
        }

        Laporan::create([
            'pelapor_id' => auth()->id(),
            'judul' => $request->judul,
            'lokasi' => $request->lokasi,
            'deskripsi' => $request->deskripsi,
            'foto_sebelum' => $fotoPath, // Simpan path gambar ke DB
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
        // Daftar 7 jenis utilitas secara statis untuk menu depan
        $jenisUtilitas = [
            ['nama' => 'Air Bersih', 'slug' => 'AirBersih', 'icon' => 'droplet', 'warna' => 'blue'],
            ['nama' => 'Air Hujan', 'slug' => 'AirHujan', 'icon' => 'cloud-rain', 'warna' => 'cyan'],
            ['nama' => 'Listrik MDP', 'slug' => 'MDP', 'icon' => 'bolt', 'warna' => 'amber'],
            ['nama' => 'Listrik SDP', 'slug' => 'SDP', 'icon' => 'zap', 'warna' => 'yellow'],
            ['nama' => 'Listrik Lift', 'slug' => 'Lift', 'icon' => 'arrow-up-down', 'warna' => 'orange'],
            ['nama' => 'Listrik AC', 'slug' => 'AC', 'icon' => 'wind', 'warna' => 'indigo'],
            ['nama' => 'Listrik Lampu', 'slug' => 'Lampu', 'icon' => 'lightbulb', 'warna' => 'emerald'],
        ];

        return view('admin.utilitas.index', compact('jenisUtilitas'));
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

    public function utilitasShow(Request $request, $jenis)
    {
        $tahun = $request->query('tahun', date('Y'));

        // Ambil riwayat data khusus jenis yang diklik
        $riwayat = \App\Models\Utilitas::with(['petugas', 'airBersih', 'airHujan', 'listrikMdp', 'listrikSdp', 'listrikLift', 'listrikAc', 'listrikLampu'])
            ->where('jenis_utilitas', $jenis)
            ->where('periode', 'like', "$tahun-%")
            ->latest('periode')
            ->get();

        // Data untuk Grafik Chart.js
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $consumptions = array_fill(0, 12, 0);

        foreach ($riwayat as $data) {
            $bulanIndex = (int) substr($data->periode, 5, 2) - 1;
            if ($bulanIndex >= 0 && $bulanIndex < 12) {
                // Pastikan model Utilitas Anda memiliki accessor untuk total_konsumsi
                $consumptions[$bulanIndex] = $data->total_konsumsi;
            }
        }

        return view('admin.utilitas.show', compact('jenis', 'tahun', 'riwayat', 'labels', 'consumptions'));
    }

    public function utilitasExportPdf(Request $request, $jenis)
    {
        $tahun = $request->query('tahun', date('Y'));

        // Tarik data persis seperti logika pada utilitasShow, namun tanpa perhitungan grafik
        $riwayat = \App\Models\Utilitas::with(['petugas'])
            ->where('jenis_utilitas', $jenis)
            ->where('periode', 'like', "$tahun-%")
            ->latest('periode')
            ->get();

        // Panggil view khusus PDF
        $pdf = Pdf::loadView('admin.utilitas.pdf', compact('jenis', 'tahun', 'riwayat'));

        // Atur ukuran kertas
        $pdf->setPaper('A4', 'portrait');

        // Kembalikan sebagai file unduhan langsung
        return $pdf->download("Laporan_Utilitas_{$jenis}_{$tahun}.pdf");
    }

    public function laporanExportPdf($id)
    {
        $laporan = \App\Models\Laporan::with(['pelapor', 'penugasan.teknisi', 'penugasan.hasilPerbaikan'])->findOrFail($id);

        if ($laporan->status !== 'Selesai') {
            return back()->with('error', 'Laporan belum selesai.');
        }

        // 1. Konversi Foto SEBELUM (dari Pelapor)
        $fotoSebelumBase64 = null;
        $pathSebelum = public_path('storage/' . $laporan->foto_sebelum);
        if ($laporan->foto_sebelum && file_exists($pathSebelum)) {
            $dataSebelum = file_get_contents($pathSebelum);
            $fotoSebelumBase64 = 'data:image/' . pathinfo($pathSebelum, PATHINFO_EXTENSION) . ';base64,' . base64_encode($dataSebelum);
        }

        // 2. Konversi Foto SESUDAH (dari Teknisi)
        $fotoSesudahBase64 = null;
        $pathSesudah = public_path('storage/' . $laporan->penugasan->hasilPerbaikan->foto_sesudah);
        if ($laporan->penugasan->hasilPerbaikan->foto_sesudah && file_exists($pathSesudah)) {
            $dataSesudah = file_get_contents($pathSesudah);
            $fotoSesudahBase64 = 'data:image/' . pathinfo($pathSesudah, PATHINFO_EXTENSION) . ';base64,' . base64_encode($dataSesudah);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.pdf', compact('laporan', 'fotoSebelumBase64', 'fotoSesudahBase64'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("Laporan_Perbaikan_{$laporan->id}.pdf");
    }

    public function exportAllLaporan(Request $request)
    {
        $query = \App\Models\Laporan::with(['pelapor', 'penugasan.teknisi']);

        // Terapkan filter yang sama dengan halaman index
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }

        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('created_at', [$request->tgl_mulai, $request->tgl_selesai]);
        }

        $laporans = $query->latest()->get();
        $filters = [
            'status' => $request->status ?? 'Semua',
            'periode' => $request->filled('tgl_mulai') ? $request->tgl_mulai . ' s/d ' . $request->tgl_selesai : 'Semua Waktu'
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.pdf_rekap', compact('laporans', 'filters'));
        $pdf->setPaper('A4', 'landscape'); // Landscape agar tabel muat banyak kolom

        return $pdf->download("Rekap_Laporan_Kerusakan_" . date('Ymd') . ".pdf");
    }

    public function exportSelesaiPdf(Request $request)
    {
        // Ambil data hanya yang berstatus Selesai
        $query = \App\Models\Laporan::with(['pelapor', 'penugasan.teknisi', 'penugasan.hasilPerbaikan'])
            ->where('status', 'Selesai');

        // Filter berdasarkan rentang tanggal jika diisi
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('created_at', [$request->tgl_mulai, $request->tgl_selesai]);
        }

        $laporans = $query->latest()->get();

        $periode = $request->filled('tgl_mulai')
            ? $request->tgl_mulai . ' s/d ' . $request->tgl_selesai
            : 'Semua Periode';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.pdf_rekap_selesai', compact('laporans', 'periode'));

        // Gunakan Landscape agar informasi teknisi dan tindakan muat dalam tabel
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download("Rekap_Laporan_Selesai_" . date('Ymd') . ".pdf");
    }
}
