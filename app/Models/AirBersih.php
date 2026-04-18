<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AirBersih extends Model
{
    protected $table = 'air_bersih';

    // Matikan timestamps karena sudah di-handle oleh induk
    public $timestamps = false;

    // Matikan auto-increment agar Eloquent tidak menolak ID dari induk
    public $incrementing = false;
    protected $keyType = 'int';

    // Perhatikan: Kolom 'konsumsi' TIDAK BOLEH masuk fillable karena di-generate database
    protected $fillable = [
        'id',
        'tgl_awal',
        'tgl_akhir',
        'stand_awal',
        'stand_akhir'
    ];

    public function utilitas()
    {
        return $this->belongsTo(Utilitas::class, 'id');
    }
}
