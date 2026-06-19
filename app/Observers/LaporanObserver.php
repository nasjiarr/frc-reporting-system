<?php

namespace App\Observers;

use App\Models\Laporan;
use App\Models\Notifikasi;

class LaporanObserver
{
    /**
     * Handle the Laporan "updated" event.
     *
     * Catatan: Notifikasi spesifik untuk perubahan status (Ditolak, Diproses, Selesai)
     * sudah ditangani langsung di masing-masing Controller (AdminController, TeknisiController).
     * Observer ini TIDAK lagi membuat notifikasi untuk menghindari duplikasi.
     */
    public function updated(Laporan $laporan): void
    {
        // Sengaja dikosongkan karena setiap aksi perubahan status
        // sudah mengirim notifikasi yang lebih spesifik dan informatif
        // dari dalam Controller masing-masing.
    }
}
