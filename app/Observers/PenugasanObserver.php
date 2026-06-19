<?php

namespace App\Observers;

use App\Models\Penugasan;
use App\Models\Notifikasi;

class PenugasanObserver
{
    /**
     * Handle the Penugasan "created" event.
     *
     * Catatan: Notifikasi penugasan baru ke Teknisi sudah ditangani
     * langsung di AdminController@assignStore dengan pesan yang lebih
     * detail (menyertakan judul laporan, lokasi, dan instruksi).
     * Observer ini TIDAK lagi membuat notifikasi untuk menghindari duplikasi.
     */
    public function created(Penugasan $penugasan): void
    {
        // Sengaja dikosongkan karena AdminController@assignStore
        // sudah mengirim notifikasi yang lebih lengkap dan informatif
        // ke Teknisi maupun Pelapor.
    }
}
