<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListrikLampu extends Model
{
    protected $table = 'listrik_lampu';

    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'tgl_awal',
        'tgl_akhir',
        'stand_awal_l1',
        'stand_awal_l2',
        'stand_awal_l3',
        'stand_akhir_l1',
        'stand_akhir_l2',
        'stand_akhir_l3'
    ];

    public function utilitas()
    {
        return $this->belongsTo(Utilitas::class, 'id');
    }
}
