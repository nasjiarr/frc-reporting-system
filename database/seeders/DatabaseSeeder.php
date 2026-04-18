<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Laporan;
use App\Models\Utilitas;
use App\Models\AirBersih;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password123'); // Password seragam untuk semua akun dummy

        // 1. Membuat Akun Admin
        $admin = User::create([
            'nama_lengkap' => 'Administrator Sistem',
            'email'        => 'admin@frc.com',
            'no_telepon'   => '081200000001',
            'role'         => 'Admin',
            'password'     => $password,
        ]);

        // 2. Membuat Akun Kepala FRC
        $kepala = User::create([
            'nama_lengkap' => 'Pimpinan FRC',
            'email'        => 'kepala@frc.com',
            'no_telepon'   => '081200000002',
            'role'         => 'KepalaFRC',
            'password'     => $password,
        ]);

        // 3. Membuat 2 Akun Teknisi
        $teknisi1 = User::create([
            'nama_lengkap' => 'Budi Teknisi (Listrik)',
            'email'        => 'teknisi1@frc.com',
            'no_telepon'   => '081200000003',
            'role'         => 'Teknisi',
            'password'     => $password,
        ]);

        $teknisi2 = User::create([
            'nama_lengkap' => 'Joko Teknisi (Air & AC)',
            'email'        => 'teknisi2@frc.com',
            'no_telepon'   => '081200000004',
            'role'         => 'Teknisi',
            'password'     => $password,
        ]);

        // 4. Membuat 3 Akun Pelapor
        $pelapor1 = User::create(['nama_lengkap' => 'Dosen A', 'email' => 'pelapor1@frc.com', 'no_telepon' => '081111', 'role' => 'Pelapor', 'password' => $password]);
        $pelapor2 = User::create(['nama_lengkap' => 'Mahasiswa B', 'email' => 'pelapor2@frc.com', 'no_telepon' => '082222', 'role' => 'Pelapor', 'password' => $password]);
        $pelapor3 = User::create(['nama_lengkap' => 'Staf C', 'email' => 'pelapor3@frc.com', 'no_telepon' => '083333', 'role' => 'Pelapor', 'password' => $password]);

        // 5. Membuat Data Dummy Laporan
        Laporan::create([
            'pelapor_id' => $pelapor1->id,
            'judul'      => 'AC Ruang Rapat Lt 2 Tidak Dingin',
            'deskripsi'  => 'Sudah dinyalakan 2 jam tapi hanya keluar angin biasa.',
            'lokasi'     => 'Ruang Rapat Lantai 2',
            'status'     => 'Baru',
        ]);

        Laporan::create([
            'pelapor_id' => $pelapor2->id,
            'judul'      => 'Lampu Koridor Utama Mati',
            'deskripsi'  => 'Ada 3 lampu TL yang berkedip dan mati di koridor depan toilet.',
            'lokasi'     => 'Koridor Utama Lt 1',
            'status'     => 'Baru',
        ]);

        Laporan::create([
            'pelapor_id' => $pelapor3->id,
            'judul'      => 'Keran Wastafel Bocor',
            'deskripsi'  => 'Keran di toilet pria lantai 3 bocor terus menerus.',
            'lokasi'     => 'Toilet Pria Lt 3',
            'status'     => 'Baru',
        ]);

        // 6. Membuat Data Dummy Utilitas (Menggunakan Transaksi CTI)
        $periode = date('Y-m'); // Bulan ini
        $utilitasAir = Utilitas::create([
            'petugas_id'     => $admin->id,
            'jenis_utilitas' => 'AirBersih',
            'periode'        => $periode,
        ]);

        AirBersih::create([
            'id'          => $utilitasAir->id,
            'tgl_awal'    => date('Y-m-01'),
            'tgl_akhir'   => date('Y-m-t'), // Akhir bulan
            'stand_awal'  => 1200.50,
            'stand_akhir' => 1350.75, // Otomatis terhitung konsumsi 150.25 di DB
        ]);
    }
}
