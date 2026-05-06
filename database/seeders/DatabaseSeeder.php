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

        // 1. Membuat Akun Admin (Aman dari duplikasi)
        $admin = User::firstOrCreate(
            ['email' => 'admin@frc.com'], // Kriteria pencarian
            [
                'nama_lengkap' => 'Ari Kustanto',
                'no_telepon'   => '0851374398234',
                'role'         => 'Admin',
                'password'     => $password,
            ] // Data yang diisi JIKA email belum ada
        );

        // 2. Membuat Akun Kepala FRC
        $kepala = User::firstOrCreate(
            ['email' => 'kepala@frc.com'],
            [
                'nama_lengkap' => 'Pimpinan FRC',
                'no_telepon'   => '081200000002',
                'role'         => 'KepalaFRC',
                'password'     => $password,
            ]
        );

        // 3. Membuat 2 Akun Teknisi
        $teknisi1 = User::firstOrCreate(
            ['email' => 'teknisi1@frc.com'],
            [
                'nama_lengkap' => 'Budi Teknisi (Listrik)',
                'no_telepon'   => '081200000003',
                'role'         => 'Teknisi',
                'password'     => $password,
            ]
        );

        $teknisi2 = User::firstOrCreate(
            ['email' => 'teknisi2@frc.com'],
            [
                'nama_lengkap' => 'Joko Teknisi (Air & AC)',
                'no_telepon'   => '081200000004',
                'role'         => 'Teknisi',
                'password'     => $password,
            ]
        );

        // 4. Membuat 3 Akun Pelapor
        $pelapor1 = User::firstOrCreate(['email' => 'pelapor1@frc.com'], ['nama_lengkap' => 'Rahayu', 'no_telepon' => '081111', 'role' => 'Pelapor', 'password' => $password]);
        $pelapor2 = User::firstOrCreate(['email' => 'pelapor2@frc.com'], ['nama_lengkap' => 'Jono', 'no_telepon' => '082222', 'role' => 'Pelapor', 'password' => $password]);
        $pelapor3 = User::firstOrCreate(['email' => 'pelapor3@frc.com'], ['nama_lengkap' => 'Keling', 'no_telepon' => '083333', 'role' => 'Pelapor', 'password' => $password]);

        // 5. Membuat Data Dummy Laporan (Aman dari duplikasi judul per pelapor)
        Laporan::firstOrCreate(
            ['judul' => 'AC Ruang Rapat Lt 2 Tidak Dingin', 'pelapor_id' => $pelapor1->id],
            [
                'deskripsi' => 'Sudah dinyalakan 2 jam tapi hanya keluar angin biasa.',
                'lokasi'    => 'Ruang Rapat Lantai 2',
                'status'    => 'Baru',
            ]
        );

        Laporan::firstOrCreate(
            ['judul' => 'Lampu Koridor Utama Mati', 'pelapor_id' => $pelapor2->id],
            [
                'deskripsi' => 'Ada 3 lampu TL yang berkedip dan mati di koridor depan toilet.',
                'lokasi'    => 'Koridor Utama Lt 1',
                'status'    => 'Baru',
            ]
        );

        Laporan::firstOrCreate(
            ['judul' => 'Keran Wastafel Bocor', 'pelapor_id' => $pelapor3->id],
            [
                'deskripsi' => 'Keran di toilet pria lantai 3 bocor terus menerus.',
                'lokasi'    => 'Toilet Pria Lt 3',
                'status'    => 'Baru',
            ]
        );

        // 6. Membuat Data Dummy Utilitas (Aman dari duplikasi periode)
        $periode = date('Y-m'); // Bulan ini
        $utilitasAir = Utilitas::firstOrCreate(
            ['jenis_utilitas' => 'AirBersih', 'periode' => $periode],
            [
                'petugas_id' => $admin->id,
            ]
        );

        // Menggunakan updateOrCreate untuk AirBersih agar aman jika relasi ID sudah ada
        AirBersih::updateOrCreate(
            ['id' => $utilitasAir->id],
            [
                'tgl_awal'    => date('Y-m-01'),
                'tgl_akhir'   => date('Y-m-t'),
                'stand_awal'  => 1200.50,
                'stand_akhir' => 1350.75,
            ]
        );

        // 7. Generate 50 Laporan Random dari Factory
        // Dibungkus kondisi agar tidak terus bertambah 50 setiap kali di-seed
        if (Laporan::count() < 50) {
            \App\Models\Laporan::factory(50)->create();
        }

        /* =================================================================
           SIMULASI ALUR APLIKASI FRC (BARU -> DIKERJAKAN -> SELESAI)
        ================================================================= */

        // FASE 1: LAPORAN BARU (Hanya Laporan, belum ditugaskan)
        // Skenario: Dosen A melaporkan kerusakan Proyektor. Admin belum memproses.
        $laporanFase1 = \App\Models\Laporan::firstOrCreate(
            ['judul' => 'Proyektor Ruang Kelas 101 Mati', 'pelapor_id' => $pelapor1->id],
            [
                'deskripsi' => 'Lampu indikator merah berkedip, proyektor tidak menembakkan cahaya.',
                'lokasi'    => 'FRC Ruang Kelas 101',
                'status'    => 'Baru',
            ]
        );

        // FASE 2: LAPORAN DIKERJAKAN (Ada Laporan + Ada Penugasan)
        // Skenario: Mahasiswa B melapor AC bocor. Admin sudah menugaskan Teknisi 2 (Joko).
        $laporanFase2 = \App\Models\Laporan::firstOrCreate(
            ['judul' => 'AC Lab Komputer Bocor Menetes', 'pelapor_id' => $pelapor2->id],
            [
                'deskripsi' => 'Air menetes cukup deras mengenai meja komputer nomor 5.',
                'lokasi'    => 'FRC Lab Komputer',
                'status'    => 'Diproses', // Status laporan naik
            ]
        );
        // Buat Penugasannya
        $penugasanFase2 = \App\Models\Penugasan::firstOrCreate(
            ['laporan_id' => $laporanFase2->id],
            [
                'teknisi_id'   => $teknisi2->id,
                'assigned_by'  => $admin->id, // <--- TAMBAHKAN BARIS INI
                'instruksi'    => 'Segera cek saluran pembuangan AC, bawa tangga dan ember.',
                'status_tugas' => 'Dikerjakan',
                'assigned_at'  => now(),
            ]
        );

        // FASE 3: LAPORAN SELESAI (Ada Laporan + Penugasan + Hasil Perbaikan)
        // Skenario: Staf C melapor listrik padam. Admin menugaskan Teknisi 1 (Budi). Teknisi sudah selesai lapor.
        $laporanFase3 = \App\Models\Laporan::firstOrCreate(
            ['judul' => 'MCB Ruang Arsip Jeglek Terus', 'pelapor_id' => $pelapor3->id],
            [
                'deskripsi' => 'Setiap kali mesin fotokopi dinyalakan, listrik satu ruangan mati.',
                'lokasi'    => 'FRC Ruang Arsip',
                'status'    => 'Selesai', // Status laporan ditutup
            ]
        );
        // Buat Penugasannya
        $penugasanFase3 = \App\Models\Penugasan::firstOrCreate(
            ['laporan_id' => $laporanFase3->id],
            [
                'teknisi_id'   => $teknisi1->id,
                'assigned_by'  => $admin->id, // <--- TAMBAHKAN BARIS INI
                'instruksi'    => 'Cek kapasitas daya MCB, kemungkinan over-load.',
                'status_tugas' => 'Selesai',
                'assigned_at'  => now()->subDays(2),
            ]
        );
        // Buat Form Hasil Perbaikannya dari Teknisi
        \App\Models\HasilPerbaikan::firstOrCreate(
            ['penugasan_id' => $penugasanFase3->id], // Sesuaikan dengan foreign key di DB Anda (penugasan_id atau laporan_id)
            [
                'tindakan'      => 'Telah dilakukan pembagian jalur beban listrik. Mesin fotokopi dipindah ke jalur MCB utama.',
                'foto_sesudah'    => null,
                'selesai_pada' => now()->subDay(1),
            ]
        );
    }
}
