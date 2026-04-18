<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListrikLift extends Model
{
    protected $table = 'listrik_lift';

    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'tgl_awal',
        'tgl_akhir',
        'stand_awal_g',
        'stand_awal_g2',
        'stand_akhir_g',
        'stand_akhir_g2'
    ];

    public function utilitas()
    {
        return $this->belongsTo(Utilitas::class, 'id');
    }
}
