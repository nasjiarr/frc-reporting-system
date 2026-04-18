<?php

namespace App\Models;

use App\Observers\LaporanObserver; // Import class Observer-nya
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([LaporanObserver::class])]
class Laporan extends Model
{
    protected $table = 'laporan'; // Mengatasi asumsi pluralisasi otomatis Laravel

    protected $fillable = [
        'pelapor_id',
        'judul',
        'deskripsi',
        'lokasi',
        'status'
    ];

    public function pelapor()
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }

    public function penugasan()
    {
        return $this->hasOne(Penugasan::class);
    }
}
