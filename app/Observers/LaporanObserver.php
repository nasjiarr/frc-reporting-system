<?php

namespace App\Observers;

use App\Models\Laporan;
use App\Models\Notifikasi;

class LaporanObserver
{
    /**
     * Handle the Laporan "updated" event.
     */
    public function updated(Laporan $laporan): void
    {
        // Cek apakah kolom 'status' mengalami perubahan
        if ($laporan->wasChanged('status')) {
            Notifikasi::create([
                'user_id' => $laporan->pelapor_id,
                'judul'   => 'Update Status Laporan',
                'pesan'   => "Laporan Anda mengenai '{$laporan->judul}' sekarang berstatus: {$laporan->status}.",
            ]);
        }
    }
}
