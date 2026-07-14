<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Utilitas;
use App\Models\AirBersih;
use App\Models\AirHujan;
use App\Models\ListrikMdp;
use App\Models\ListrikSdp;
use App\Models\ListrikLift;
use App\Models\ListrikAc;
use App\Models\ListrikLampu;
use App\Models\User;
use Carbon\Carbon;

class UtilitasSeeder extends Seeder
{
    public function run(): void
    {
        // Cari user Admin sebagai petugas pencatat
        $admin = User::where('role', 'Admin')->first();

        if (!$admin) {
            $this->command->error('Tidak ada user Admin. Jalankan DatabaseSeeder terlebih dahulu.');
            return;
        }

        $petugasId = $admin->id;

        // ============================================================
        // DATA REALISTIS UNTUK 7 BULAN (Januari - Juli 2026)
        // Stand meter naik kumulatif setiap bulan
        // ============================================================

        // --- 1. AIR BERSIH (m³) ---
        $airBersihData = [
            ['periode' => '2026-01', 'stand_awal' => 1000.00, 'stand_akhir' => 1145.50],
            ['periode' => '2026-02', 'stand_awal' => 1145.50, 'stand_akhir' => 1278.30],
            ['periode' => '2026-03', 'stand_awal' => 1278.30, 'stand_akhir' => 1430.10],
            ['periode' => '2026-04', 'stand_awal' => 1430.10, 'stand_akhir' => 1560.80],
            ['periode' => '2026-05', 'stand_awal' => 1560.80, 'stand_akhir' => 1715.40],
            ['periode' => '2026-06', 'stand_awal' => 1715.40, 'stand_akhir' => 1848.90],
            ['periode' => '2026-07', 'stand_awal' => 1848.90, 'stand_akhir' => 1990.20],
        ];

        foreach ($airBersihData as $data) {
            $tglAwal  = Carbon::createFromFormat('Y-m', $data['periode'])->startOfMonth();
            $tglAkhir = Carbon::createFromFormat('Y-m', $data['periode'])->endOfMonth();

            $utilitas = Utilitas::firstOrCreate(
                ['jenis_utilitas' => 'AirBersih', 'periode' => $data['periode']],
                ['petugas_id' => $petugasId]
            );
            AirBersih::updateOrCreate(
                ['id' => $utilitas->id],
                [
                    'tgl_awal'    => $tglAwal->toDateString(),
                    'tgl_akhir'   => $tglAkhir->toDateString(),
                    'stand_awal'  => $data['stand_awal'],
                    'stand_akhir' => $data['stand_akhir'],
                ]
            );
        }

        // --- 2. AIR HUJAN (m³) ---
        $airHujanData = [
            ['periode' => '2026-01', 'stand_awal' => 500.00, 'stand_akhir' => 620.30],
            ['periode' => '2026-02', 'stand_awal' => 620.30, 'stand_akhir' => 755.10],
            ['periode' => '2026-03', 'stand_awal' => 755.10, 'stand_akhir' => 870.50],
            ['periode' => '2026-04', 'stand_awal' => 870.50, 'stand_akhir' => 960.80],
            ['periode' => '2026-05', 'stand_awal' => 960.80, 'stand_akhir' => 1020.40],
            ['periode' => '2026-06', 'stand_awal' => 1020.40, 'stand_akhir' => 1065.90],
            ['periode' => '2026-07', 'stand_awal' => 1065.90, 'stand_akhir' => 1100.20],
        ];

        foreach ($airHujanData as $data) {
            $tglAwal  = Carbon::createFromFormat('Y-m', $data['periode'])->startOfMonth();
            $tglAkhir = Carbon::createFromFormat('Y-m', $data['periode'])->endOfMonth();

            $utilitas = Utilitas::firstOrCreate(
                ['jenis_utilitas' => 'AirHujan', 'periode' => $data['periode']],
                ['petugas_id' => $petugasId]
            );
            AirHujan::updateOrCreate(
                ['id' => $utilitas->id],
                [
                    'tgl_awal'    => $tglAwal->toDateString(),
                    'tgl_akhir'   => $tglAkhir->toDateString(),
                    'stand_awal'  => $data['stand_awal'],
                    'stand_akhir' => $data['stand_akhir'],
                ]
            );
        }

        // --- 3. LISTRIK MDP / Panel Utama (kWh) ---
        $mdpData = [
            ['periode' => '2026-01', 'stand_awal' => 50000.00, 'stand_akhir' => 52350.00],
            ['periode' => '2026-02', 'stand_awal' => 52350.00, 'stand_akhir' => 54500.50],
            ['periode' => '2026-03', 'stand_awal' => 54500.50, 'stand_akhir' => 56900.80],
            ['periode' => '2026-04', 'stand_awal' => 56900.80, 'stand_akhir' => 59100.30],
            ['periode' => '2026-05', 'stand_awal' => 59100.30, 'stand_akhir' => 61550.70],
            ['periode' => '2026-06', 'stand_awal' => 61550.70, 'stand_akhir' => 63800.20],
            ['periode' => '2026-07', 'stand_awal' => 63800.20, 'stand_akhir' => 66210.90],
        ];

        foreach ($mdpData as $data) {
            $tglAwal  = Carbon::createFromFormat('Y-m', $data['periode'])->startOfMonth();
            $tglAkhir = Carbon::createFromFormat('Y-m', $data['periode'])->endOfMonth();

            $utilitas = Utilitas::firstOrCreate(
                ['jenis_utilitas' => 'MDP', 'periode' => $data['periode']],
                ['petugas_id' => $petugasId]
            );
            ListrikMdp::updateOrCreate(
                ['id' => $utilitas->id],
                [
                    'tgl_awal'    => $tglAwal->toDateString(),
                    'tgl_akhir'   => $tglAkhir->toDateString(),
                    'stand_awal'  => $data['stand_awal'],
                    'stand_akhir' => $data['stand_akhir'],
                ]
            );
        }

        // --- 4. LISTRIK SDP / Panel Distribusi (kWh) ---
        $sdpData = [
            ['periode' => '2026-01', 'awal_1' => 10000.00, 'akhir_1' => 10820.50, 'awal_2' => 8000.00, 'akhir_2' => 8650.30],
            ['periode' => '2026-02', 'awal_1' => 10820.50, 'akhir_1' => 11600.00, 'awal_2' => 8650.30, 'akhir_2' => 9250.80],
            ['periode' => '2026-03', 'awal_1' => 11600.00, 'akhir_1' => 12450.30, 'awal_2' => 9250.80, 'akhir_2' => 9900.10],
            ['periode' => '2026-04', 'awal_1' => 12450.30, 'akhir_1' => 13200.70, 'awal_2' => 9900.10, 'akhir_2' => 10500.60],
            ['periode' => '2026-05', 'awal_1' => 13200.70, 'akhir_1' => 14050.20, 'awal_2' => 10500.60, 'akhir_2' => 11180.40],
            ['periode' => '2026-06', 'awal_1' => 14050.20, 'akhir_1' => 14800.80, 'awal_2' => 11180.40, 'akhir_2' => 11790.20],
            ['periode' => '2026-07', 'awal_1' => 14800.80, 'akhir_1' => 15630.50, 'awal_2' => 11790.20, 'akhir_2' => 12450.70],
        ];

        foreach ($sdpData as $data) {
            $tglAwal  = Carbon::createFromFormat('Y-m', $data['periode'])->startOfMonth();
            $tglAkhir = Carbon::createFromFormat('Y-m', $data['periode'])->endOfMonth();

            $utilitas = Utilitas::firstOrCreate(
                ['jenis_utilitas' => 'SDP', 'periode' => $data['periode']],
                ['petugas_id' => $petugasId]
            );
            ListrikSdp::updateOrCreate(
                ['id' => $utilitas->id],
                [
                    'tgl_awal'        => $tglAwal->toDateString(),
                    'tgl_akhir'       => $tglAkhir->toDateString(),
                    'stand_awal_sdp1' => $data['awal_1'],
                    'stand_akhir_sdp1' => $data['akhir_1'],
                    'stand_awal_sdp2' => $data['awal_2'],
                    'stand_akhir_sdp2' => $data['akhir_2'],
                ]
            );
        }

        // --- 5. LISTRIK LIFT (kWh) ---
        $liftData = [
            ['periode' => '2026-01', 'awal_g' => 3000.00, 'akhir_g' => 3280.50, 'awal_g2' => 2500.00, 'akhir_g2' => 2750.30],
            ['periode' => '2026-02', 'awal_g' => 3280.50, 'akhir_g' => 3540.20, 'awal_g2' => 2750.30, 'akhir_g2' => 2990.80],
            ['periode' => '2026-03', 'awal_g' => 3540.20, 'akhir_g' => 3830.70, 'awal_g2' => 2990.80, 'akhir_g2' => 3250.40],
            ['periode' => '2026-04', 'awal_g' => 3830.70, 'akhir_g' => 4100.10, 'awal_g2' => 3250.40, 'akhir_g2' => 3490.60],
            ['periode' => '2026-05', 'awal_g' => 4100.10, 'akhir_g' => 4390.80, 'awal_g2' => 3490.60, 'akhir_g2' => 3750.20],
            ['periode' => '2026-06', 'awal_g' => 4390.80, 'akhir_g' => 4650.30, 'awal_g2' => 3750.20, 'akhir_g2' => 3990.50],
            ['periode' => '2026-07', 'awal_g' => 4650.30, 'akhir_g' => 4940.70, 'awal_g2' => 3990.50, 'akhir_g2' => 4260.10],
        ];

        foreach ($liftData as $data) {
            $tglAwal  = Carbon::createFromFormat('Y-m', $data['periode'])->startOfMonth();
            $tglAkhir = Carbon::createFromFormat('Y-m', $data['periode'])->endOfMonth();

            $utilitas = Utilitas::firstOrCreate(
                ['jenis_utilitas' => 'Lift', 'periode' => $data['periode']],
                ['petugas_id' => $petugasId]
            );
            ListrikLift::updateOrCreate(
                ['id' => $utilitas->id],
                [
                    'tgl_awal'      => $tglAwal->toDateString(),
                    'tgl_akhir'     => $tglAkhir->toDateString(),
                    'stand_awal_g'  => $data['awal_g'],
                    'stand_akhir_g' => $data['akhir_g'],
                    'stand_awal_g2' => $data['awal_g2'],
                    'stand_akhir_g2' => $data['akhir_g2'],
                ]
            );
        }

        // --- 6. LISTRIK AC (kWh) - 3 Lantai ---
        $acData = [
            ['periode' => '2026-01', 'al1' => 5000.00, 'kl1' => 5420.30, 'al2' => 4500.00, 'kl2' => 4880.50, 'al3' => 4000.00, 'kl3' => 4350.70],
            ['periode' => '2026-02', 'al1' => 5420.30, 'kl1' => 5870.10, 'al2' => 4880.50, 'kl2' => 5300.80, 'al3' => 4350.70, 'kl3' => 4730.20],
            ['periode' => '2026-03', 'al1' => 5870.10, 'kl1' => 6350.40, 'al2' => 5300.80, 'kl2' => 5750.30, 'al3' => 4730.20, 'kl3' => 5140.60],
            ['periode' => '2026-04', 'al1' => 6350.40, 'kl1' => 6800.20, 'al2' => 5750.30, 'kl2' => 6170.70, 'al3' => 5140.60, 'kl3' => 5530.90],
            ['periode' => '2026-05', 'al1' => 6800.20, 'kl1' => 7300.50, 'al2' => 6170.70, 'kl2' => 6630.40, 'al3' => 5530.90, 'kl3' => 5960.30],
            ['periode' => '2026-06', 'al1' => 7300.50, 'kl1' => 7750.80, 'al2' => 6630.40, 'kl2' => 7050.60, 'al3' => 5960.30, 'kl3' => 6350.50],
            ['periode' => '2026-07', 'al1' => 7750.80, 'kl1' => 8240.30, 'al2' => 7050.60, 'kl2' => 7500.40, 'al3' => 6350.50, 'kl3' => 6780.90],
        ];

        foreach ($acData as $data) {
            $tglAwal  = Carbon::createFromFormat('Y-m', $data['periode'])->startOfMonth();
            $tglAkhir = Carbon::createFromFormat('Y-m', $data['periode'])->endOfMonth();

            $utilitas = Utilitas::firstOrCreate(
                ['jenis_utilitas' => 'AC', 'periode' => $data['periode']],
                ['petugas_id' => $petugasId]
            );
            ListrikAc::updateOrCreate(
                ['id' => $utilitas->id],
                [
                    'tgl_awal'       => $tglAwal->toDateString(),
                    'tgl_akhir'      => $tglAkhir->toDateString(),
                    'stand_awal_l1'  => $data['al1'],
                    'stand_akhir_l1' => $data['kl1'],
                    'stand_awal_l2'  => $data['al2'],
                    'stand_akhir_l2' => $data['kl2'],
                    'stand_awal_l3'  => $data['al3'],
                    'stand_akhir_l3' => $data['kl3'],
                ]
            );
        }

        // --- 7. LISTRIK LAMPU (kWh) - 3 Lantai ---
        $lampuData = [
            ['periode' => '2026-01', 'al1' => 2000.00, 'kl1' => 2180.50, 'al2' => 1800.00, 'kl2' => 1960.30, 'al3' => 1600.00, 'kl3' => 1740.70],
            ['periode' => '2026-02', 'al1' => 2180.50, 'kl1' => 2370.20, 'al2' => 1960.30, 'kl2' => 2130.80, 'al3' => 1740.70, 'kl3' => 1890.40],
            ['periode' => '2026-03', 'al1' => 2370.20, 'kl1' => 2580.60, 'al2' => 2130.80, 'kl2' => 2320.10, 'al3' => 1890.40, 'kl3' => 2060.30],
            ['periode' => '2026-04', 'al1' => 2580.60, 'kl1' => 2770.30, 'al2' => 2320.10, 'kl2' => 2490.50, 'al3' => 2060.30, 'kl3' => 2210.80],
            ['periode' => '2026-05', 'al1' => 2770.30, 'kl1' => 2980.70, 'al2' => 2490.50, 'kl2' => 2680.20, 'al3' => 2210.80, 'kl3' => 2380.50],
            ['periode' => '2026-06', 'al1' => 2980.70, 'kl1' => 3170.40, 'al2' => 2680.20, 'kl2' => 2850.60, 'al3' => 2380.50, 'kl3' => 2530.90],
            ['periode' => '2026-07', 'al1' => 3170.40, 'kl1' => 3380.80, 'al2' => 2850.60, 'kl2' => 3040.30, 'al3' => 2530.90, 'kl3' => 2700.50],
        ];

        foreach ($lampuData as $data) {
            $tglAwal  = Carbon::createFromFormat('Y-m', $data['periode'])->startOfMonth();
            $tglAkhir = Carbon::createFromFormat('Y-m', $data['periode'])->endOfMonth();

            $utilitas = Utilitas::firstOrCreate(
                ['jenis_utilitas' => 'Lampu', 'periode' => $data['periode']],
                ['petugas_id' => $petugasId]
            );
            ListrikLampu::updateOrCreate(
                ['id' => $utilitas->id],
                [
                    'tgl_awal'       => $tglAwal->toDateString(),
                    'tgl_akhir'      => $tglAkhir->toDateString(),
                    'stand_awal_l1'  => $data['al1'],
                    'stand_akhir_l1' => $data['kl1'],
                    'stand_awal_l2'  => $data['al2'],
                    'stand_akhir_l2' => $data['kl2'],
                    'stand_awal_l3'  => $data['al3'],
                    'stand_akhir_l3' => $data['kl3'],
                ]
            );
        }

        $this->command->info('✅ Data utilitas 7 jenis x 7 bulan (Jan-Jul 2026) berhasil di-seed!');
    }
}
