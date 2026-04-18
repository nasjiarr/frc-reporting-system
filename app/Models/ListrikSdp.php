<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListrikSdp extends Model
{
    protected $table = 'listrik_sdp';

    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'tgl_awal',
        'tgl_akhir',
        'stand_awal_sdp1',
        'stand_awal_sdp2',
        'stand_akhir_sdp1',
        'stand_akhir_sdp2'
    ];

    public function utilitas()
    {
        return $this->belongsTo(Utilitas::class, 'id');
    }
}
