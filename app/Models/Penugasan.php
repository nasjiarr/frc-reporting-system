<?php

namespace App\Models;

use App\Observers\PenugasanObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([PenugasanObserver::class])]
class Penugasan extends Model
{
    protected $table = 'penugasan';

    protected $fillable = [
        'laporan_id',
        'teknisi_id',
        'assigned_by',
        'instruksi',
        'status_tugas',
        'assigned_at'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }

    public function assigner() // Pembuat tugas (Admin/Kepala)
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function hasilPerbaikan()
    {
        return $this->hasOne(HasilPerbaikan::class);
    }
}
