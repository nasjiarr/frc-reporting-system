<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UtilitasController;
use App\Http\Controllers\PelaporController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\KepalaFRCController;
use Illuminate\Support\Facades\Route;
use App\Models\Notifikasi;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $role = auth()->user()->role;

    return match ($role) {
        'Admin'     => redirect()->route('admin.dashboard'),
        'Pelapor'   => redirect()->route('pelapor.dashboard'),
        'Teknisi'   => redirect()->route('teknisi.dashboard'),
        'KepalaFRC' => redirect()->route('kepala.dashboard'),
        default     => abort(403, 'Role tidak memiliki akses.'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/utilitas/air-bersih', [UtilitasController::class, 'storeAirBersih'])->name('utilitas.air_bersih.store');
});

Route::middleware(['auth', 'role:Pelapor'])->prefix('pelapor')->name('pelapor.')->group(function () {
    Route::get('/dashboard', [PelaporController::class, 'dashboard'])->name('dashboard');
    Route::get('/laporan', [PelaporController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/create', [PelaporController::class, 'create'])->name('laporan.create');
    Route::post('/laporan', [PelaporController::class, 'store'])->name('laporan.store');
    Route::get('/laporan/{id}', [PelaporController::class, 'show'])->name('laporan.show');
});

Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard & Users
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('users', AdminController::class)->except(['show']);
    Route::patch('/users/{user}/toggle', [AdminController::class, 'toggleStatus'])->name('users.toggle');

    // Penugasan
    Route::get('/penugasan', [AdminController::class, 'penugasanIndex'])->name('penugasan.index');
    Route::post('/penugasan/{laporan}', [AdminController::class, 'assignStore'])->name('penugasan.store');

    // Utilitas
    Route::get('/utilitas', [AdminController::class, 'utilitasIndex'])->name('utilitas.index');
    Route::get('/utilitas/create', [UtilitasController::class, 'create'])->name('utilitas.create');
    Route::post('/utilitas', [UtilitasController::class, 'store'])->name('utilitas.store');
    Route::put('/utilitas/{utilitas}', [AdminController::class, 'utilitasUpdate'])->name('utilitas.update');
    Route::delete('/utilitas/{utilitas}', [AdminController::class, 'utilitasDestroy'])->name('utilitas.destroy');
    Route::get('/utilitas/{id}/edit', [AdminController::class, 'utilitasEdit'])->name('utilitas.edit');

    Route::get('/laporan-saya', [AdminController::class, 'laporanIndex'])->name('laporan.index');
    Route::get('/laporan/create', [AdminController::class, 'laporanCreate'])->name('laporan.create');
    Route::post('/laporan', [AdminController::class, 'laporanStore'])->name('laporan.store');
    Route::get('/laporan-selesai', [AdminController::class, 'laporanSelesai'])->name('laporan.selesai');
    Route::get('/laporan/{id}', [AdminController::class, 'laporanShow'])->name('laporan.show');
    Route::delete('/laporan/{id}', [AdminController::class, 'laporanDestroy'])->name('laporan.destroy');
    Route::patch('/users/{user}/toggle-status', [AdminController::class, 'toggleStatus'])->name('users.toggle-status');
});

Route::middleware(['auth', 'role:Teknisi'])->prefix('teknisi')->name('teknisi.')->group(function () {
    Route::get('/dashboard', [TeknisiController::class, 'dashboard'])->name('dashboard');
    Route::get('/tugas-aktif', [TeknisiController::class, 'tugasAktif'])->name('tugas-aktif');
    Route::get('/tugas/{id}', [TeknisiController::class, 'show'])->name('tugas.show');
    Route::post('/tugas/{id}/selesai', [TeknisiController::class, 'updateProgress'])->name('tugas.update');
    Route::get('/riwayat', [TeknisiController::class, 'riwayat'])->name('riwayat');
    Route::get('/penugasan/{id}', [TeknisiController::class, 'penugasanShow'])->name('penugasan.show');
    Route::patch('/penugasan/{id}/mulai', [TeknisiController::class, 'mulaiTugas'])->name('penugasan.mulai');
});

Route::middleware(['auth', 'role:KepalaFRC'])->prefix('kepala')->name('kepala.')->group(function () {
    Route::get('/dashboard', [KepalaFRCController::class, 'dashboard'])->name('dashboard');
    Route::get('/rekap-utilitas', [KepalaFRCController::class, 'rekapUtilitas'])->name('utilitas.index');
    Route::get('/kinerja-teknisi', [KepalaFRCController::class, 'kinerjaTeknisi'])->name('kinerja.index');
    Route::get('/rekap-laporan', [KepalaFRCController::class, 'rekapLaporan'])->name('laporan.index');
});

Route::get('/api/notifications/unread', function () {
    $userId = auth()->id(); // Ambil ID user yang sedang login

    return response()->json([
        'count'  => Notifikasi::where('user_id', $userId)->where('is_read', false)->count(),
        'latest' => Notifikasi::where('user_id', $userId)->where('is_read', false)->latest()->first()
    ]);
})->middleware('auth');

Route::post('/api/notifications/mark-read', function () {
    // Ubah status is_read menjadi true (1) untuk semua notifikasi user ini yang belum dibaca
    \App\Models\Notifikasi::where('user_id', auth()->id())
        ->where('is_read', false)
        ->update(['is_read' => true]);

    return response()->json(['success' => true]);
})->middleware('auth');

require __DIR__ . '/auth.php';
