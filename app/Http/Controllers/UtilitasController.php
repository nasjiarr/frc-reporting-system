<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Utilitas;
use App\Models\AirBersih;
use App\Models\AirHujan;
use App\Models\ListrikMdp;
use App\Models\ListrikSdp;
use App\Models\ListrikLift;
use App\Models\ListrikAc;
use App\Models\ListrikLampu;

class UtilitasController extends Controller
{
    // Method untuk menampilkan form
    public function create()
    {
        return view('admin.utilitas.create');
    }

    // Method untuk memproses data dari form dinamis
    public function store(Request $request)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'jenis_utilitas' => 'required|in:AirBersih,AirHujan,MDP,SDP,Lift,AC,Lampu',
            'periode'        => 'required|date_format:Y-m',
            'tgl_awal'       => 'required|date',
            'tgl_akhir'      => 'required|date|after_or_equal:tgl_awal',
        ]);

        try {
            DB::transaction(function () use ($request) {

                // 2. Simpan ke Tabel Induk (Utilitas)
                $utilitas = Utilitas::create([
                    'petugas_id'     => auth()->id(),
                    'jenis_utilitas' => $request->jenis_utilitas,
                    'periode'        => $request->periode,
                ]);

                $id = $utilitas->id;
                $jenis = $request->jenis_utilitas;

                // 3. Simpan ke Tabel Turunan berdasarkan 'Jenis Utilitas'

                // KELOMPOK 1: Single Input (Air & MDP)
                if (in_array($jenis, ['AirBersih', 'AirHujan', 'MDP'])) {
                    $modelClass = match ($jenis) {
                        'AirBersih' => AirBersih::class,
                        'AirHujan'  => AirHujan::class,
                        'MDP'       => ListrikMdp::class,
                    };

                    $modelClass::create([
                        'id'          => $id,
                        'tgl_awal'    => $request->tgl_awal,
                        'tgl_akhir'   => $request->tgl_akhir,
                        'stand_awal'  => $request->stand_awal,
                        'stand_akhir' => $request->stand_akhir,
                    ]);
                }

                // KELOMPOK 2: Double Input (SDP & Lift)
                elseif (in_array($jenis, ['SDP', 'Lift'])) {
                    $modelClass = match ($jenis) {
                        'SDP'  => ListrikSdp::class,
                        'Lift' => ListrikLift::class,
                    };

                    // Prefix kolom database berbeda antara SDP dan Lift
                    $p1 = $jenis === 'Lift' ? '_g' : '_sdp1';
                    $p2 = $jenis === 'Lift' ? '_g2' : '_sdp2';

                    $modelClass::create([
                        'id'          => $id,
                        'tgl_awal'    => $request->tgl_awal,
                        'tgl_akhir'   => $request->tgl_akhir,
                        'stand_awal' . $p1  => $request->stand_awal_1,
                        'stand_akhir' . $p1  => $request->stand_akhir_1,
                        'stand_awal' . $p2  => $request->stand_awal_2,
                        'stand_akhir' . $p2  => $request->stand_akhir_2,
                    ]);
                }

                // KELOMPOK 3: Triple Input (AC & Lampu)
                elseif (in_array($jenis, ['AC', 'Lampu'])) {
                    $modelClass = match ($jenis) {
                        'AC'    => ListrikAc::class,
                        'Lampu' => ListrikLampu::class,
                    };

                    $modelClass::create([
                        'id'             => $id,
                        'tgl_awal'       => $request->tgl_awal,
                        'tgl_akhir'      => $request->tgl_akhir,
                        'stand_awal_l1'  => $request->stand_awal_l1,
                        'stand_akhir_l1' => $request->stand_akhir_l1,
                        'stand_awal_l2'  => $request->stand_awal_l2,
                        'stand_akhir_l2' => $request->stand_akhir_l2,
                        'stand_awal_l3'  => $request->stand_awal_l3,
                        'stand_akhir_l3' => $request->stand_akhir_l3,
                    ]);
                }
            });

            // Redirect kembali ke halaman form dengan pesan sukses
            return redirect()->route('admin.utilitas.create')->with('success', 'Data utilitas berhasil disimpan!');
        } catch (\Exception $e) {
            // Tangkap jika terjadi error database
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }
}
