<?php

namespace Tests\Feature;

use App\Models\Laporan;
use App\Models\Penugasan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeknisiDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_teknisi_can_view_dashboard_with_complete_statistics(): void
    {
        $teknisi = User::factory()->create([
            'role' => 'Teknisi',
            'name' => 'Agus Teknisi',
            'nama_lengkap' => 'Agus Teknisi',
        ]);

        $admin = User::factory()->create(['role' => 'Admin']);
        $pelapor = User::factory()->create(['role' => 'Pelapor', 'nama_lengkap' => 'Doni Pelapor']);

        // 1. Tugas Ditugaskan
        $lap1 = Laporan::factory()->create(['pelapor_id' => $pelapor->id, 'judul' => 'AC Mati Lab 1', 'lokasi' => 'Lab 1']);
        Penugasan::create([
            'laporan_id' => $lap1->id,
            'teknisi_id' => $teknisi->id,
            'assigned_by' => $admin->id,
            'status_tugas' => 'Ditugaskan',
        ]);

        // 2. Tugas Dikerjakan
        $lap2 = Laporan::factory()->create(['pelapor_id' => $pelapor->id, 'judul' => 'Kran Patah Toilet', 'lokasi' => 'Toilet Lt 2']);
        Penugasan::create([
            'laporan_id' => $lap2->id,
            'teknisi_id' => $teknisi->id,
            'assigned_by' => $admin->id,
            'status_tugas' => 'Dikerjakan',
        ]);

        // 3. Tugas Selesai
        $lap3 = Laporan::factory()->create(['pelapor_id' => $pelapor->id, 'judul' => 'Lampu Redup', 'lokasi' => 'Ruang 101']);
        Penugasan::create([
            'laporan_id' => $lap3->id,
            'teknisi_id' => $teknisi->id,
            'assigned_by' => $admin->id,
            'status_tugas' => 'Selesai',
        ]);

        $response = $this->actingAs($teknisi)->get(route('teknisi.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('totalDitugaskan', 1);
        $response->assertViewHas('totalDikerjakan', 1);
        $response->assertViewHas('totalAktif', 2);
        $response->assertViewHas('totalSelesaiSemua', 1);

        // Greeting & Header
        $response->assertSee('Dashboard Teknisi');
        $response->assertSee('Halo, Agus Teknisi');
        $response->assertSee('2 tugas aktif');
        $response->assertSee('Riwayat Pekerjaan');

        // Task items rendered
        $response->assertSee('AC Mati Lab 1');
        $response->assertSee('Lab 1');
        $response->assertSee('Kran Patah Toilet');
        $response->assertSee('Toilet Lt 2');
        $response->assertSee('Doni Pelapor');

        // Actions
        $response->assertSee('Mulai Kerjakan');
        $response->assertSee('Form Selesai');
        $response->assertSee('Detail');
    }

    public function test_empty_state_rendered_when_teknisi_has_no_active_tasks(): void
    {
        $teknisi = User::factory()->create(['role' => 'Teknisi']);

        $response = $this->actingAs($teknisi)->get(route('teknisi.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Tidak Ada Tugas Aktif Saat Ini');
    }

    public function test_non_teknisi_cannot_access_teknisi_dashboard(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        $response = $this->actingAs($pelapor)->get(route('teknisi.dashboard'));

        $response->assertStatus(403);
    }

    public function test_teknisi_can_view_tugas_aktif_page_with_active_tasks(): void
    {
        $teknisi = User::factory()->create(['role' => 'Teknisi']);
        $admin = User::factory()->create(['role' => 'Admin']);
        $pelapor = User::factory()->create(['role' => 'Pelapor', 'nama_lengkap' => 'Siti Pelapor']);

        $lap1 = Laporan::factory()->create(['pelapor_id' => $pelapor->id, 'judul' => 'Pintu Rusak Lt 1', 'lokasi' => 'Gedung A']);
        Penugasan::create([
            'laporan_id' => $lap1->id,
            'teknisi_id' => $teknisi->id,
            'assigned_by' => $admin->id,
            'status_tugas' => 'Ditugaskan',
        ]);

        $lap2 = Laporan::factory()->create(['pelapor_id' => $pelapor->id, 'judul' => 'Wifi Lemah Ruang 2', 'lokasi' => 'Gedung B']);
        Penugasan::create([
            'laporan_id' => $lap2->id,
            'teknisi_id' => $teknisi->id,
            'assigned_by' => $admin->id,
            'status_tugas' => 'Dikerjakan',
        ]);

        $response = $this->actingAs($teknisi)->get(route('teknisi.tugas-aktif'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Tugas Aktif');
        $response->assertSee('Total Tugas Aktif');
        $response->assertSee('Baru Ditugaskan');
        $response->assertSee('Sedang Dikerjakan');
        $response->assertSee('Pintu Rusak Lt 1');
        $response->assertSee('Wifi Lemah Ruang 2');
        $response->assertSee('Siti Pelapor');
        $response->assertSee('Detail');
        $response->assertSee('Mulai Kerjakan');
        $response->assertSee('Form Selesai');
    }

    public function test_teknisi_can_view_tugas_aktif_empty_state(): void
    {
        $teknisi = User::factory()->create(['role' => 'Teknisi']);

        $response = $this->actingAs($teknisi)->get(route('teknisi.tugas-aktif'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Tugas Aktif');
        $response->assertSee('Tidak Ada Tugas Aktif Saat Ini');
    }

    public function test_non_teknisi_cannot_access_tugas_aktif(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        $response = $this->actingAs($pelapor)->get(route('teknisi.tugas-aktif'));

        $response->assertStatus(403);
    }

    public function test_teknisi_can_view_riwayat_page_with_completed_reports(): void
    {
        $teknisi = User::factory()->create(['role' => 'Teknisi']);
        $admin = User::factory()->create(['role' => 'Admin']);
        $pelapor = User::factory()->create(['role' => 'Pelapor', 'nama_lengkap' => 'Bambang Pelapor']);

        $lap = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'judul' => 'Perbaikan Pipa Bocor',
            'lokasi' => 'Gedung Rektorat Lt 1',
            'status' => 'Selesai',
        ]);

        $penugasan = Penugasan::create([
            'laporan_id' => $lap->id,
            'teknisi_id' => $teknisi->id,
            'assigned_by' => $admin->id,
            'status_tugas' => 'Selesai',
        ]);

        \App\Models\HasilPerbaikan::create([
            'penugasan_id' => $penugasan->id,
            'tindakan' => 'Mengganti pipa PVC 2 inch dan lem pipa',
            'material_digunakan' => 'Pipa PVC 2 inch, Lem Isarplas',
            'selesai_pada' => now(),
        ]);

        $response = $this->actingAs($teknisi)->get(route('teknisi.riwayat'));

        $response->assertStatus(200);
        $response->assertSee('Riwayat Pekerjaan Selesai');
        $response->assertSee('Total Riwayat Selesai');
        $response->assertSee('Perbaikan Pipa Bocor');
        $response->assertSee('Gedung Rektorat Lt 1');
        $response->assertSee('Bambang Pelapor');
        $response->assertSee('Mengganti pipa PVC 2 inch');
        $response->assertSee(route('teknisi.riwayat.export_pdf', $penugasan->id));
    }

    public function test_teknisi_can_view_riwayat_empty_state(): void
    {
        $teknisi = User::factory()->create(['role' => 'Teknisi']);

        $response = $this->actingAs($teknisi)->get(route('teknisi.riwayat'));

        $response->assertStatus(200);
        $response->assertSee('Riwayat Pekerjaan Selesai');
        $response->assertSee('Tidak Ada Riwayat Pekerjaan');
    }

    public function test_non_teknisi_cannot_access_riwayat(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        $response = $this->actingAs($pelapor)->get(route('teknisi.riwayat'));

        $response->assertStatus(403);
    }
}
