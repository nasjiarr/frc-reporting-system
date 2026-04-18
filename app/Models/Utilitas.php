<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utilitas extends Model
{
    protected $table = 'utilitas';
    protected $fillable = ['petugas_id', 'jenis_utilitas', 'periode'];

    // Relasi ke User
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    // Relasi ke tabel turunan
    public function airBersih()
    {
        return $this->hasOne(AirBersih::class, 'id');
    }
    public function airHujan()
    {
        return $this->hasOne(AirHujan::class, 'id');
    }
    public function listrikMdp()
    {
        return $this->hasOne(ListrikMdp::class, 'id');
    }
    public function listrikSdp()
    {
        return $this->hasOne(ListrikSdp::class, 'id');
    }
    public function listrikLift()
    {
        return $this->hasOne(ListrikLift::class, 'id');
    }
    public function listrikAc()
    {
        return $this->hasOne(ListrikAc::class, 'id');
    }
    public function listrikLampu()
    {
        return $this->hasOne(ListrikLampu::class, 'id');
    }

    /**
     * Accessor untuk mendapatkan total konsumsi secara dinamis
     */
    public function getTotalKonsumsiAttribute()
    {
        return match ($this->jenis_utilitas) {
            'AirBersih' => $this->airBersih->konsumsi ?? 0,
            'AirHujan'  => $this->airHujan->konsumsi ?? 0,
            'MDP'       => $this->listrikMdp->konsumsi ?? 0,
            'SDP'       => ($this->listrikSdp->konsumsi_sdp1 ?? 0) + ($this->listrikSdp->konsumsi_sdp2 ?? 0),
            'Lift'      => $this->listrikLift->konsumsi_total ?? 0,
            'AC'        => ($this->listrikAc->konsumsi_l1 ?? 0) + ($this->listrikAc->konsumsi_l2 ?? 0) + ($this->listrikAc->konsumsi_l3 ?? 0),
            'Lampu'     => ($this->listrikLampu->konsumsi_l1 ?? 0) + ($this->listrikLampu->konsumsi_l2 ?? 0) + ($this->listrikLampu->konsumsi_l3 ?? 0),
            default     => 0
        };
    }
}
