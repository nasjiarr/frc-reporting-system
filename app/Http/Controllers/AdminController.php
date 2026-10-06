<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\User;
use App\Models\Laporan;
use App\Models\Penugasan;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Data Statistik
        $stats = [
            'laporan_baru' => \App\Models\Laporan::where('status', 'Baru')->count(),
            'tugas_aktif'  => \App\Models\Penugasan::whereIn('status_tugas', ['Ditugaskan', 'Dikerjakan'])->count(),
            'selesai_bulan_ini' => \App\Models\Laporan::where('status', 'Selesai')
                ->whereMonth('updated_at', \Carbon\Carbon::now()->month)
                ->whereYear('updated_at', \Carbon\Carbon::now()->year)
                ->count(),
            'pengguna_aktif' => \App\Models\User::where('is_active', true)->count(),
        ];

        // 2. Status Utilitas Bulan Ini
        $currentPeriode = now()->format('Y-m');
        $utilitasBulanIni = \App\Models\Utilitas::where('periode', $currentPeriode)->get();
        $bln_ini_belum_isi = $utilitasBulanIni->isEmpty();
        $utilitasCount = $utilitasBulanIni->count();

        // 3. Data Panel Kiri & Kanan
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

        // 4. Data Teknisi & Beban Kerja Aktif
        $teknisiList = \App\Models\User::where('role', 'Teknisi')
            ->where('is_active', true)
            ->withCount(['tugas_teknisi as tugas_aktif_count' => function ($q) {
                $q->whereIn('status_tugas', ['Ditugaskan', 'Dikerjakan']);
            }])
            ->orderBy('tugas_aktif_count', 'asc')
            ->get();

        // 5. Daftar Laporan Baru untuk Assign Modal
        $laporanBaruList = \App\Models\Laporan::where('status', 'Baru')->latest()->get();

        return view('admin.dashboard', compact(
            'stats',
            'laporanPerluTindakLanjut',
            'penugasanAktif',
            'bln_ini_belum_isi',
            'utilitasCount',
            'teknisiList',
            'laporanBaruList'
        ));
    }

    public function index(Request $request)
    {
        // 1. Metrik / Statistik KPI Pengguna
        $stats = [
            'total' => User::count(),
            'aktif' => User::where('is_active', true)->count(),
            'nonaktif' => User::where('is_active', false)->count(),
            'teknisi_total' => User::where('role', 'Teknisi')->count(),
            'teknisi_ready' => User::where('role', 'Teknisi')
                ->where('is_active', true)
                ->whereDoesntHave('tugas_teknisi', function ($q) {
                    $q->whereIn('status_tugas', ['Ditugaskan', 'Dikerjakan']);
                })
                ->count(),
            'pelapor_total' => User::where('role', 'Pelapor')->count(),
            'admin_total' => User::where('role', 'Admin')->count(),
        ];

        // 2. Query Pengguna dengan Filter, Search & Relasi Beban Tugas Teknisi
        $query = User::query()
            ->withCount(['tugas_teknisi as tugas_aktif_count' => function ($q) {
                $q->whereIn('status_tugas', ['Ditugaskan', 'Dikerjakan']);
            }]);

        // Filter Pencarian (Nama, Email, No. Telepon)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('no_telepon', 'like', "%{$search}%");
            });
        }

        // Filter Role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter Status Akun (Aktif / Nonaktif)
        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->where('is_active', true);
            } elseif ($request->status === 'nonaktif') {
                $query->where('is_active', false);
            }
        }

        // Pagination 10 data per halaman dengan query string terikat
        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'no_telepon' => 'required|string|max:30',
            'role' => 'required|in:Admin,Teknisi,Pelapor,KepalaFRC',
            'password' => 'required|min:8'
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'no_telepon.required' => 'Nomor telepon/WA wajib diisi.',
            'role.required' => 'Role hak akses wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
        ]);

        $data['name'] = $data['nama_lengkap'];
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = true;
        $user = User::create($data);

        return back()->with('success', "Pengguna {$user->nama_lengkap} berhasil ditambahkan.");
    }

    public function penugasanIndex(Request $request)
    {
        // 1. Ambil data teknisi dengan beban kerja aktif
        $teknisi = User::where('role', 'Teknisi')
            ->where('is_active', true)
            ->withCount(['tugas_teknisi as tugas_aktif_count' => function ($q) {
                $q->whereIn('status_tugas', ['Ditugaskan', 'Dikerjakan']);
            }])
            ->orderBy('tugas_aktif_count', 'asc')
            ->get();

        // 2. Laporan baru yang menunggu penugasan
        $laporanBaru = Laporan::with('pelapor')
            ->where('status', 'Baru')
            ->latest()
            ->get();

        // 3. Query monitoring penugasan dengan filter & pencarian
        $penugasanQuery = Penugasan::with(['laporan.pelapor', 'teknisi', 'assigner', 'hasilPerbaikan'])
            ->has('laporan');

        if ($request->filled('status')) {
            $penugasanQuery->where('status_tugas', $request->status);
        }

        if ($request->filled('teknisi_id')) {
            $penugasanQuery->where('teknisi_id', $request->teknisi_id);
        }

        if ($request->filled('search')) {
            $keyword = '%' . trim($request->search) . '%';
            $penugasanQuery->where(function ($q) use ($keyword) {
                $q->whereHas('laporan', function ($lq) use ($keyword) {
                    $lq->where('judul', 'like', $keyword)
                       ->orWhere('lokasi', 'like', $keyword)
                       ->orWhereHas('pelapor', function ($pq) use ($keyword) {
                           $pq->where('nama_lengkap', 'like', $keyword);
                       });
                })->orWhereHas('teknisi', function ($tq) use ($keyword) {
                    $tq->where('nama_lengkap', 'like', $keyword);
                });
            });
        }

        $penugasans = $penugasanQuery->latest('assigned_at')->paginate(10)->withQueryString();

        // 4. Statistik untuk KPI Cards
        $stats = [
            'menunggu' => $laporanBaru->count(),
            'ditugaskan' => Penugasan::where('status_tugas', 'Ditugaskan')->count(),
            'dikerjakan' => Penugasan::where('status_tugas', 'Dikerjakan')->count(),
            'tugas_aktif' => Penugasan::whereIn('status_tugas', ['Ditugaskan', 'Dikerjakan'])->count(),
            'selesai' => Penugasan::where('status_tugas', 'Selesai')->count(),
            'teknisi_total' => $teknisi->count(),
            'teknisi_ready' => $teknisi->where('tugas_aktif_count', 0)->count(),
        ];

        return view('admin.penugasan.index', compact('penugasans', 'laporanBaru', 'teknisi', 'stats'));
    }

    public function assignStore(Request $request, Laporan $laporan)
    {
        $request->validate(['teknisi_id' => 'required|exists:users,id']);

        // HAPUS backslash (\) sebelum DB, sehingga menjadi seperti ini:
        DB::transaction(function () use ($request, $laporan) {
            $penugasan = Penugasan::create([
                'laporan_id' => $laporan->id,
                'teknisi_id' => $request->teknisi_id,
                'assigned_by' => auth()->id(),
                'instruksi' => $request->instruksi,
                'status_tugas' => 'Ditugaskan',
                'assigned_at' => now(),
            ]);

            $laporan->update(['status' => 'Diproses']);

            Notifikasi::create([
                'user_id' => $request->teknisi_id,
                'judul'   => 'Tugas Baru Diberikan',
                'pesan'   => "Anda telah ditugaskan untuk memperbaiki: '{$laporan->judul}' di {$laporan->lokasi}. Silakan cek detail penugasan Anda.",
                'link'    => route('teknisi.tugas.show', $penugasan->id, false),
            ]);

            $pelaporLink = match ($laporan->pelapor?->role) {
                'Admin'     => route('admin.laporan.show', $laporan->id, false),
                'KepalaFRC' => route('kepala.laporan.show', $laporan->id, false),
                default     => route('pelapor.laporan.show', $laporan->id, false),
            };

            // Notifikasi kepada Pelapor
            Notifikasi::create([
                'user_id' => $laporan->pelapor_id,
                'judul'   => 'Laporan Diproses',
                'pesan'   => "Laporan Anda yang berjudul '{$laporan->judul}' telah ditugaskan kepada teknisi dan sedang dalam proses perbaikan.",
                'link'    => $pelaporLink,
            ]);
        });

        return back()->with('success', 'Teknisi berhasil ditugaskan.');
    }

    public function tolakLaporan(Request $request, Laporan $laporan)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:1000',
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi.',
            'alasan_penolakan.max' => 'Alasan penolakan maksimal 1000 karakter.',
        ]);

        DB::transaction(function () use ($request, $laporan) {
            $laporan->update([
                'status' => 'Ditolak',
                'alasan_penolakan' => $request->alasan_penolakan,
            ]);

            $pelaporLink = match ($laporan->pelapor?->role) {
                'Admin'     => route('admin.laporan.show', $laporan->id, false),
                'KepalaFRC' => route('kepala.laporan.show', $laporan->id, false),
                default     => route('pelapor.laporan.show', $laporan->id, false),
            };

            Notifikasi::create([
                'user_id' => $laporan->pelapor_id,
                'judul' => 'Laporan Ditolak',
                'pesan' => "Laporan Anda yang berjudul '{$laporan->judul}' ditolak oleh Admin. Alasan: {$request->alasan_penolakan}",
                'link' => $pelaporLink,
            ]);
        });

        return back()->with('success', 'Laporan berhasil ditolak.');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            // Validasi email harus unik, KECUALI untuk email milik user ini sendiri
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'no_telepon' => 'required|string|max:30',
            'role' => 'required|in:Admin,Teknisi,Pelapor,KepalaFRC',
            // Password opsional saat edit (hanya diisi jika ingin diganti)
            'password' => 'nullable|min:8'
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'no_telepon.required' => 'Nomor telepon/WA wajib diisi.',
            'role.required' => 'Role hak akses wajib dipilih.',
            'role.in' => 'Role tidak valid.',
            'password.min' => 'Password baru minimal harus 8 karakter.',
        ]);

        // Proteksi Self-Lockout: Admin yang sedang login tidak boleh mengubah perannya sendiri menjadi non-Admin
        if (auth()->id() === $user->id && $data['role'] !== 'Admin') {
            return back()->with('error', 'Tindakan Ditolak: Anda tidak dapat mengubah peran akun Anda sendiri.');
        }

        $data['name'] = $data['nama_lengkap'];

        // Cek apakah form password diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        } else {
            // Jika kosong, hapus 'password' dari array agar tidak tertimpa null di database
            unset($data['password']);
        }

        $user->update($data);

        return back()->with('success', "Data pengguna {$user->nama_lengkap} berhasil diperbarui.");
    }

    public function laporanIndex()
    {
        // Mengambil laporan yang dibuat oleh Admin ini sendiri
        $laporans = Laporan::where('pelapor_id', auth()->id())->latest()->paginate(10);
        return view('admin.laporan.index', compact('laporans'));
    }

    public function laporanCreate()
    {
        $daftarRuangan = config('frc.ruangan', []);

        return view('admin.laporan.create', compact('daftarRuangan'));
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

        DB::transaction(function () use ($request, $fotoPath) {
            $laporan = Laporan::create([
                'pelapor_id' => auth()->id(),
                'judul' => $request->judul,
                'lokasi' => $request->lokasi,
                'deskripsi' => $request->deskripsi,
                'foto_sebelum' => $fotoPath, // Simpan path gambar ke DB
                'status' => 'Baru',
            ]);

            // Notifikasi untuk Admin yang lain (mengecualikan admin yang membuat laporan)
            $admins = User::where('role', 'Admin')
                ->where('is_active', true)
                ->where('id', '!=', auth()->id())
                ->get();
            foreach ($admins as $admin) {
                Notifikasi::create([
                    'user_id' => $admin->id,
                    'judul'   => 'Laporan Kerusakan Baru',
                    'pesan'   => "Terdapat laporan baru dari sesama Admin mengenai '{$laporan->judul}' di {$laporan->lokasi}.",
                    'link'    => route('admin.laporan.show', $laporan->id, false),
                ]);
            }
        });

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
        $query = \App\Models\Laporan::with(['pelapor', 'penugasan.teknisi', 'penugasan.hasilPerbaikan'])
            ->where('status', 'Selesai');

        // Fitur Pencarian (Search)
        if ($request->filled('search')) {
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

        // Filter rentang tanggal berdasarkan tanggal penyelesaian tiket
        if ($request->filled('tgl_mulai')) {
            $tglMulai = $request->tgl_mulai;
            $query->where(function ($q) use ($tglMulai) {
                $q->whereHas('penugasan.hasilPerbaikan', function ($qHp) use ($tglMulai) {
                    $qHp->whereDate('selesai_pada', '>=', $tglMulai);
                })->orWhere(function ($qFallback) use ($tglMulai) {
                    $qFallback->whereDoesntHave('penugasan.hasilPerbaikan')
                        ->whereDate('updated_at', '>=', $tglMulai);
                });
            });
        }
        if ($request->filled('tgl_selesai')) {
            $tglSelesai = $request->tgl_selesai;
            $query->where(function ($q) use ($tglSelesai) {
                $q->whereHas('penugasan.hasilPerbaikan', function ($qHp) use ($tglSelesai) {
                    $qHp->whereDate('selesai_pada', '<=', $tglSelesai);
                })->orWhere(function ($qFallback) use ($tglSelesai) {
                    $qFallback->whereDoesntHave('penugasan.hasilPerbaikan')
                        ->whereDate('updated_at', '<=', $tglSelesai);
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

    public function laporanExportPdf($id)
    {
        $laporan = \App\Models\Laporan::with(['pelapor', 'penugasan.teknisi', 'penugasan.hasilPerbaikan'])->findOrFail($id);

        if ($laporan->status !== 'Selesai') {
            return back()->with('error', 'Laporan belum selesai.');
        }

        // Validasi ketersediaan data penugasan & hasil perbaikan
        if (!$laporan->penugasan || !$laporan->penugasan->hasilPerbaikan) {
            return back()->with('error', 'Data hasil perbaikan belum lengkap untuk dicetak.');
        }

        // 1. Konversi Foto SEBELUM (dari Pelapor)
        $fotoSebelumBase64 = $this->convertFotoToBase64($laporan->foto_sebelum);

        // 2. Konversi Foto SESUDAH (dari Teknisi)
        $fotoSesudahBase64 = $this->convertFotoToBase64(
            $laporan->penugasan?->hasilPerbaikan?->foto_sesudah
        );

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.pdf', compact('laporan', 'fotoSebelumBase64', 'fotoSesudahBase64'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("Laporan_Perbaikan_{$laporan->id}.pdf");
    }

    public function exportAllLaporan(Request $request)
    {
        ini_set('memory_limit', '256M');

        $query = \App\Models\Laporan::with(['pelapor', 'penugasan.teknisi', 'penugasan.hasilPerbaikan']);

        // Terapkan filter yang sama dengan halaman index
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }

        // Filter tanggal pada rekap semua laporan
        if ($request->filled('tgl_mulai')) {
            $query->whereDate('created_at', '>=', $request->tgl_mulai);
        }
        if ($request->filled('tgl_selesai')) {
            $query->whereDate('created_at', '<=', $request->tgl_selesai);
        }

        // Batasi maksimal 100 laporan untuk menjaga stabilitas DomPDF
        $count = (clone $query)->count();
        if ($count > 100) {
            return back()->with('error', "Jumlah data yang akan diekspor ({$count} laporan) melebihi batas maksimal 100 laporan. Silakan persempit filter Anda.");
        }

        $laporans = $query->latest()->get();

        // Konversi foto sebelum & sesudah ke base64 untuk setiap laporan
        foreach ($laporans as $laporan) {
            $laporan->foto_sebelum_base64 = $this->convertFotoToBase64($laporan->foto_sebelum);
            $laporan->foto_sesudah_base64 = $this->convertFotoToBase64(
                $laporan->penugasan?->hasilPerbaikan?->foto_sesudah
            );
        }

        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $periodeAll = \Carbon\Carbon::parse($request->tgl_mulai)->format('d/m/Y') . ' s/d ' . \Carbon\Carbon::parse($request->tgl_selesai)->format('d/m/Y');
        } elseif ($request->filled('tgl_mulai')) {
            $periodeAll = 'Mulai ' . \Carbon\Carbon::parse($request->tgl_mulai)->format('d/m/Y');
        } elseif ($request->filled('tgl_selesai')) {
            $periodeAll = 'Sampai ' . \Carbon\Carbon::parse($request->tgl_selesai)->format('d/m/Y');
        } else {
            $periodeAll = 'Semua Waktu';
        }

        $filters = [
            'status' => $request->status ?? 'Semua',
            'periode' => $periodeAll
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.pdf_rekap', compact('laporans', 'filters'));
        $pdf->setPaper('A4', 'landscape'); // Landscape agar tabel muat banyak kolom

        return $pdf->download("Rekap_Laporan_Kerusakan_" . date('Ymd') . ".pdf");
    }

    public function exportSelesaiPdf(Request $request)
    {
        ini_set('memory_limit', '256M');

        // Ambil data hanya yang berstatus Selesai
        $query = \App\Models\Laporan::with(['pelapor', 'penugasan.teknisi', 'penugasan.hasilPerbaikan'])
            ->where('status', 'Selesai');

        // Filter kata kunci pencarian (identik dengan tampilan web)
        if ($request->filled('search')) {
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

        // Filter rentang tanggal berdasarkan tanggal penyelesaian tiket
        if ($request->filled('tgl_mulai')) {
            $tglMulai = $request->tgl_mulai;
            $query->where(function ($q) use ($tglMulai) {
                $q->whereHas('penugasan.hasilPerbaikan', function ($qHp) use ($tglMulai) {
                    $qHp->whereDate('selesai_pada', '>=', $tglMulai);
                })->orWhere(function ($qFallback) use ($tglMulai) {
                    $qFallback->whereDoesntHave('penugasan.hasilPerbaikan')
                        ->whereDate('updated_at', '>=', $tglMulai);
                });
            });
        }
        if ($request->filled('tgl_selesai')) {
            $tglSelesai = $request->tgl_selesai;
            $query->where(function ($q) use ($tglSelesai) {
                $q->whereHas('penugasan.hasilPerbaikan', function ($qHp) use ($tglSelesai) {
                    $qHp->whereDate('selesai_pada', '<=', $tglSelesai);
                })->orWhere(function ($qFallback) use ($tglSelesai) {
                    $qFallback->whereDoesntHave('penugasan.hasilPerbaikan')
                        ->whereDate('updated_at', '<=', $tglSelesai);
                });
            });
        }

        // Batasi maksimal 100 laporan untuk menjaga stabilitas DomPDF
        $count = (clone $query)->count();
        if ($count > 100) {
            return back()->with('error', "Jumlah data yang akan diekspor ({$count} laporan) melebihi batas maksimal 100 laporan. Silakan persempit rentang tanggal atau kata kunci pencarian Anda.");
        }

        $laporans = $query->latest('updated_at')->get();

        // Konversi foto sebelum & sesudah ke base64 untuk setiap laporan
        foreach ($laporans as $laporan) {
            $laporan->foto_sebelum_base64 = $this->convertFotoToBase64($laporan->foto_sebelum);
            $laporan->foto_sesudah_base64 = $this->convertFotoToBase64(
                $laporan->penugasan?->hasilPerbaikan?->foto_sesudah
            );
        }

        // Susun teks periode untuk header PDF
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $periode = \Carbon\Carbon::parse($request->tgl_mulai)->format('d/m/Y') . ' s/d ' . \Carbon\Carbon::parse($request->tgl_selesai)->format('d/m/Y');
        } elseif ($request->filled('tgl_mulai')) {
            $periode = 'Mulai ' . \Carbon\Carbon::parse($request->tgl_mulai)->format('d/m/Y');
        } elseif ($request->filled('tgl_selesai')) {
            $periode = 'Sampai ' . \Carbon\Carbon::parse($request->tgl_selesai)->format('d/m/Y');
        } else {
            $periode = 'Semua Periode';
        }

        $search = $request->input('search');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.pdf_rekap_selesai', compact('laporans', 'periode', 'search'));

        // Gunakan Landscape agar informasi teknisi dan tindakan muat dalam tabel
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download("Rekap_Laporan_Selesai_" . date('Ymd') . ".pdf");
    }

    /**
     * Helper: Konversi path foto dari storage ke string base64.
     * DomPDF tidak bisa membaca file dari URL, jadi perlu dikonversi.
     */
    private function convertFotoToBase64(?string $fotoPath): ?string
    {
        if (!$fotoPath) {
            return null;
        }

        $fullPath = public_path('storage/' . $fotoPath);

        if (!file_exists($fullPath)) {
            return null;
        }

        $data = file_get_contents($fullPath);
        $extension = pathinfo($fullPath, PATHINFO_EXTENSION);

        return 'data:image/' . $extension . ';base64,' . base64_encode($data);
    }
}
