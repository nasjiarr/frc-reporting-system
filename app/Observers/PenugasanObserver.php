<?php

namespace App\Observers;

use App\Models\Penugasan;
use App\Models\Notifikasi;

class PenugasanObserver
{
    /**
     * Handle the Penugasan "created" event.
     */
    public function created(Penugasan $penugasan): void
    {
        // Load relasi laporan untuk mengambil judul laporannya
        $penugasan->load('laporan');

        Notifikasi::create([
            'user_id' => $penugasan->teknisi_id,
            'judul'   => 'Tugas Baru Diberikan',
            'pesan'   => "Anda mendapat tugas perbaikan baru untuk laporan: '{$penugasan->laporan->judul}'. Segera cek detail penugasan Anda.",
        ]);
    }
}
