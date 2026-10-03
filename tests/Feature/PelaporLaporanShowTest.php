<?php

namespace Tests\Feature;

use App\Models\HasilPerbaikan;
use App\Models\Laporan;
use App\Models\Penugasan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PelaporLaporanShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_pelapor_can_access_their_own_report_detail(): void
    {
        $pelapor = User::factory()->create([
            'role' => 'Pelapor',
            'name' => 'Siti Pelapor',
            'nama_lengkap' => 'Siti Pelapor',
        ]);

        $laporan = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'judul' => 'Kran Air Wastafel Bocor di Lantai 2',
            'lokasi' => 'Toilet Barat Lantai 2 FRC',
            'deskripsi' => 'Air kran terus menetes kencang dan tidak bisa ditutup rapat.',
            'status' => 'Baru',
            'foto_sebelum' => 'foto_sebelum/kran_bocor.jpg',
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.laporan.show', $laporan->id));

        $response->assertStatus(200);

        // Header & Breadcrumb
        $response->assertSee('Dashboard');
        $response->assertSee('Laporan Saya');
        $response->assertSee('Detail Laporan #' . $laporan->id);
        $response->assertSee(route('pelapor.dashboard'));
        $response->assertSee(route('pelapor.laporan.index'));

        // Report Title, ID, & Metadata
        $response->assertSee('Kran Air Wastafel Bocor di Lantai 2');
        $response->assertSee('#' . $laporan->id);
        $response->assertSee('Toilet Barat Lantai 2 FRC');
        $response->assertSee('Air kran terus menetes kencang dan tidak bisa ditutup rapat.');
        $response->assertSee('Baru (Menunggu Verifikasi)');

        // Back Button
        $response->assertSee('Kembali ke Daftar Laporan');
        $response->assertSee(route('pelapor.laporan.index'));

        // Stepper
        $response->assertSee('Tahapan Penanganan');
        $response->assertSee('Laporan Diterima');
        $response->assertSee('Verifikasi & Penugasan');
    }

    public function test_pelapor_cannot_access_another_reporters_report_returns_403(): void
    {
        $pelaporA = User::factory()->create([
            'role' => 'Pelapor',
            'name' => 'Pelapor Satu',
            'nama_lengkap' => 'Pelapor Satu',
        ]);

        $pelaporB = User::factory()->create([
            'role' => 'Pelapor',
            'name' => 'Pelapor Dua',
            'nama_lengkap' => 'Pelapor Dua',
        ]);

        $laporanA = Laporan::factory()->create([
            'pelapor_id' => $pelaporA->id,
            'judul' => 'Laporan Rahasia Pelapor Satu',
        ]);

        // Pelapor B tries to view Pelapor A's report
        $response = $this->actingAs($pelaporB)->get(route('pelapor.laporan.show', $laporanA->id));

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);
        $laporan = Laporan::factory()->create(['pelapor_id' => $pelapor->id]);

        $response = $this->get(route('pelapor.laporan.show', $laporan->id));

        $response->assertRedirect(route('login'));
    }

    public function test_non_pelapor_cannot_access_pelapor_show_route(): void
    {
        $teknisi = User::factory()->create(['role' => 'Teknisi']);
        $pelapor = User::factory()->create(['role' => 'Pelapor']);
        $laporan = Laporan::factory()->create(['pelapor_id' => $pelapor->id]);

        $response = $this->actingAs($teknisi)->get(route('pelapor.laporan.show', $laporan->id));

        $response->assertStatus(403);
    }

    public function test_non_existent_report_returns_404(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        $response = $this->actingAs($pelapor)->get(route('pelapor.laporan.show', 999999));

        $response->assertStatus(404);
    }

    public function test_evidence_photo_is_visible_when_status_is_baru(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        $laporan = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'status' => 'Baru',
            'foto_sebelum' => 'foto_sebelum/bukti_awal_baru.jpg',
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.laporan.show', $laporan->id));

        $response->assertStatus(200);
        $response->assertSee('storage/foto_sebelum/bukti_awal_baru.jpg');
        $response->assertSee('Foto Bukti Kerusakan Awal');
        $response->assertSee('Perbesar Foto');
    }

    public function test_evidence_photo_is_visible_when_status_is_diproses(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);
        $admin = User::factory()->create(['role' => 'Admin']);
        $teknisi = User::factory()->create([
            'role' => 'Teknisi',
            'nama_lengkap' => 'Pak Bambang Teknisi',
            'email' => 'bambang@frc.ugm.ac.id',
            'no_telepon' => '081234567890',
        ]);

        $laporan = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'status' => 'Diproses',
            'foto_sebelum' => 'foto_sebelum/bukti_sedang_proses.jpg',
        ]);

        Penugasan::create([
            'laporan_id' => $laporan->id,
            'teknisi_id' => $teknisi->id,
            'assigned_by' => $admin->id,
            'instruksi' => 'Tolong bawa kunci inggris dan seal tape.',
            'status_tugas' => 'Dikerjakan',
            'assigned_at' => now(),
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.laporan.show', $laporan->id));

        $response->assertStatus(200);

        // Photo is visible
        $response->assertSee('storage/foto_sebelum/bukti_sedang_proses.jpg');

        // Status badge
        $response->assertSee('Sedang Diproses');

        // Technician card is visible
        $response->assertSee('Teknisi Penanggung Jawab');
        $response->assertSee('Pak Bambang Teknisi');
        $response->assertSee('bambang@frc.ugm.ac.id');
        $response->assertSee('081234567890');
        $response->assertSee('Tolong bawa kunci inggris dan seal tape.');
        $response->assertSee('Dikerjakan');
    }

    public function test_evidence_photo_is_visible_when_status_is_ditolak(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        $laporan = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'status' => 'Ditolak',
            'alasan_penolakan' => 'Kendala bukan merupakan fasilitas FRC.',
            'foto_sebelum' => 'foto_sebelum/bukti_ditolak.jpg',
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.laporan.show', $laporan->id));

        $response->assertStatus(200);

        // Photo must be visible even when rejected!
        $response->assertSee('storage/foto_sebelum/bukti_ditolak.jpg');
        $response->assertSee('Foto Bukti Kerusakan Awal');
    }

    public function test_rejection_banner_is_displayed_when_status_is_ditolak(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        $laporan = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'status' => 'Ditolak',
            'alasan_penolakan' => 'Foto bukti tidak jelas dan lokasi di luar area gedung FRC.',
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.laporan.show', $laporan->id));

        $response->assertStatus(200);

        // Rejection banner elements
        $response->assertSee('Laporan Kerusakan Ditolak');
        $response->assertSee('Alasan Penolakan:');
        $response->assertSee('Foto bukti tidak jelas dan lokasi di luar area gedung FRC.');
        $response->assertSee('Buat Laporan Baru');
        $response->assertSee(route('pelapor.laporan.create'));

        // Stepper reflects rejection
        $response->assertSee('Verifikasi Admin (Ditolak)');
    }

    public function test_rejection_banner_is_not_displayed_when_status_is_not_ditolak(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        $laporan = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'status' => 'Baru',
            'alasan_penolakan' => null,
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.laporan.show', $laporan->id));

        $response->assertStatus(200);
        $response->assertDontSee('Laporan Kerusakan Ditolak');
        $response->assertDontSee('Alasan Penolakan:');
    }

    public function test_resolution_showcase_is_displayed_when_status_is_selesai(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);
        $admin = User::factory()->create(['role' => 'Admin']);
        $teknisi = User::factory()->create([
            'role' => 'Teknisi',
            'nama_lengkap' => 'Joko Teknisi',
        ]);

        $laporan = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'status' => 'Selesai',
            'foto_sebelum' => 'foto_sebelum/ac_rusak_awal.jpg',
        ]);

        $penugasan = Penugasan::create([
            'laporan_id' => $laporan->id,
            'teknisi_id' => $teknisi->id,
            'assigned_by' => $admin->id,
            'status_tugas' => 'Selesai',
            'assigned_at' => now()->subDay(),
        ]);

        HasilPerbaikan::create([
            'penugasan_id' => $penugasan->id,
            'tindakan' => 'Penggantian kapasitor fan outdoor dan pembersihan filter indoor.',
            'material_digunakan' => 'Kapasitor 35uF & Freon R32',
            'foto_sebelum' => 'perbaikan/sebelum/ac_awal.jpg',
            'foto_sesudah' => 'perbaikan/sesudah/ac_selesai.jpg',
            'selesai_pada' => now(),
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.laporan.show', $laporan->id));

        $response->assertStatus(200);

        // Status badge
        $response->assertSee('Selesai Diperbaiki');

        // Resolution showcase
        $response->assertSee('Dokumentasi & Hasil Perbaikan');
        $response->assertSee('Komparasi Visual (Sebelum vs Sesudah Perbaikan)');
        $response->assertSee('Penggantian kapasitor fan outdoor dan pembersihan filter indoor.');
        $response->assertSee('Kapasitor 35uF & Freon R32');
        $response->assertSee('storage/perbaikan/sesudah/ac_selesai.jpg');

        // Original photo is also visible
        $response->assertSee('storage/foto_sebelum/ac_rusak_awal.jpg');

        // Stepper reflects completion
        $response->assertSee('Selesai & Terverifikasi');
    }

    public function test_resolution_showcase_is_not_displayed_when_status_is_not_selesai(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        $laporan = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'status' => 'Baru',
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.laporan.show', $laporan->id));

        $response->assertStatus(200);
        $response->assertDontSee('Dokumentasi & Hasil Perbaikan');
        $response->assertDontSee('Komparasi Visual (Sebelum vs Sesudah Perbaikan)');
    }

    public function test_status_badges_use_consistent_tailwind_tokens_for_all_statuses(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        $statuses = [
            'Baru' => ['bg-blue-100', 'text-blue-800', 'Baru (Menunggu Verifikasi)'],
            'Diproses' => ['bg-amber-100', 'text-amber-800', 'Sedang Diproses'],
            'Selesai' => ['bg-emerald-100', 'text-emerald-800', 'Selesai Diperbaiki'],
            'Ditolak' => ['bg-rose-100', 'text-rose-800', 'Laporan Ditolak'],
        ];

        foreach ($statuses as $status => [$bgClass, $textClass, $badgeLabel]) {
            $laporan = Laporan::factory()->create([
                'pelapor_id' => $pelapor->id,
                'status' => $status,
                'alasan_penolakan' => $status === 'Ditolak' ? 'Alasan testing' : null,
            ]);

            $response = $this->actingAs($pelapor)->get(route('pelapor.laporan.show', $laporan->id));

            $response->assertStatus(200);
            $response->assertSee($bgClass);
            $response->assertSee($textClass);
            $response->assertSee($badgeLabel);
        }
    }

    public function test_lightbox_modal_and_responsive_grid_elements_are_present(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        $laporan = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'status' => 'Baru',
            'foto_sebelum' => 'foto_sebelum/grid_test.jpg',
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.laporan.show', $laporan->id));

        $response->assertStatus(200);

        // Responsive grid architecture
        $response->assertSee('grid-cols-1');
        $response->assertSee('lg:grid-cols-12');
        $response->assertSee('lg:col-span-7');
        $response->assertSee('lg:col-span-5');

        // Dark mode classes
        $response->assertSee('dark:bg-gray-800');
        $response->assertSee('dark:text-gray-100');

        // Lightbox modal component
        $response->assertSee('openLightbox');
        $response->assertSee('closeLightbox');
        $response->assertSee('lightboxOpen');
        $response->assertSee('cursor-zoom-in');
    }

    public function test_placeholder_is_rendered_when_original_evidence_photo_is_null(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        $laporan = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'status' => 'Baru',
            'foto_sebelum' => null,
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.laporan.show', $laporan->id));

        $response->assertStatus(200);
        $response->assertSee('Tidak ada foto bukti kerusakan yang dilampirkan');
        $response->assertDontSee('alt="Bukti Kerusakan:');
    }

    public function test_resolution_showcase_handles_null_photos_gracefully(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);
        $admin = User::factory()->create(['role' => 'Admin']);
        $teknisi = User::factory()->create([
            'role' => 'Teknisi',
            'nama_lengkap' => 'Teknisi Handal',
        ]);

        $laporan = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'status' => 'Selesai',
            'foto_sebelum' => null,
        ]);

        $penugasan = Penugasan::create([
            'laporan_id' => $laporan->id,
            'teknisi_id' => $teknisi->id,
            'assigned_by' => $admin->id,
            'status_tugas' => 'Selesai',
            'assigned_at' => now()->subDay(),
        ]);

        HasilPerbaikan::create([
            'penugasan_id' => $penugasan->id,
            'tindakan' => 'Perbaikan sirkuit listrik tanpa foto.',
            'material_digunakan' => 'Kabel tembaga 2.5mm',
            'foto_sebelum' => null,
            'foto_sesudah' => null,
            'selesai_pada' => now(),
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.laporan.show', $laporan->id));

        $response->assertStatus(200);
        $response->assertSee('Foto sebelum tidak tersedia');
        $response->assertSee('Foto bukti sesudah tidak tersedia');
        $response->assertSee('Perbaikan sirkuit listrik tanpa foto.');
    }

    public function test_stepper_displays_assigned_technician_name(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);
        $admin = User::factory()->create(['role' => 'Admin']);
        $teknisi = User::factory()->create([
            'role' => 'Teknisi',
            'name' => 'teknisi_username',
            'nama_lengkap' => 'Budi Santoso Teknisi',
        ]);

        $laporan = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'status' => 'Diproses',
        ]);

        Penugasan::create([
            'laporan_id' => $laporan->id,
            'teknisi_id' => $teknisi->id,
            'assigned_by' => $admin->id,
            'status_tugas' => 'Ditugaskan',
            'assigned_at' => now(),
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.laporan.show', $laporan->id));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso Teknisi');
    }
}
