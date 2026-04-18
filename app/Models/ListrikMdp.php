<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListrikMdp extends Model
{
    protected $table = 'listrik_mdp';

    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'int';

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
