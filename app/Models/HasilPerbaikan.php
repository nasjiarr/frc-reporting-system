<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilPerbaikan extends Model
{
    protected $table = 'hasil_perbaikan';

    protected $fillable = [
        'penugasan_id',
        'tindakan',
        'material_digunakan',
        'foto_sebelum',
        'foto_sesudah',
        'selesai_pada'
    ];

    protected $casts = [
        'selesai_pada' => 'datetime',
    ];

    public function penugasan()
    {
        return $this->belongsTo(Penugasan::class);
    }
}
