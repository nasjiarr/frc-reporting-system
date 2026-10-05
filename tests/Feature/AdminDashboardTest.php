<?php

namespace Tests\Feature;

use App\Models\Laporan;
use App\Models\Penugasan;
use App\Models\User;
use App\Models\Utilitas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_dashboard_with_complete_statistics_and_greetings(): void
    {
        $admin = User::factory()->create([
            'role' => 'Admin',
            'name' => 'Super Admin FRC',
            'nama_lengkap' => 'Super Admin FRC',
        ]);

        $pelapor = User::factory()->create(['role' => 'Pelapor']);
        $teknisi = User::factory()->create(['role' => 'Teknisi', 'nama_lengkap' => 'Teknisi Handal']);

        // Create reports
        $laporanBaru = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'status' => 'Baru',
            'judul' => 'AC Bocor di Lab 1',
            'lokasi' => 'Lantai 2 - Lab 1',
        ]);

        $laporanProses = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'status' => 'Diproses',
            'judul' => 'Lampu Mati di Koridor',
        ]);

        Penugasan::create([
            'laporan_id' => $laporanProses->id,
            'teknisi_id' => $teknisi->id,
            'assigned_by' => $admin->id,
            'status_tugas' => 'Dikerjakan',
            'assigned_at' => now()->subHours(2),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('stats', function ($stats) {
            return $stats['laporan_baru'] === 1
                && $stats['tugas_aktif'] === 1
                && $stats['pengguna_aktif'] === 3;
        });

        // Check Greetings & Hero Banner
        $response->assertSee('Halo, Super Admin FRC!');
        $response->assertSee('Admin Operasional');
        $response->assertSee(route('admin.laporan.create'));
        $response->assertSee(route('admin.penugasan.index'));

        // Check KPI links
        $response->assertSee(route('admin.penugasan.index'));
        $response->assertSee(route('admin.laporan.selesai'));
        $response->assertSee(route('admin.users.index'));

        // Check Quick Actions
        $response->assertSee('Aksi Cepat Operasional');
        $response->assertSee(route('admin.utilitas.create'));
        $response->assertSee(route('admin.laporan.export_all'));

        // Check Reports and Task Panels
        $response->assertSee('AC Bocor di Lab 1');
        $response->assertSee('Lampu Mati di Koridor');
        $response->assertSee('Teknisi Handal');

        // Check Technician workload section
        $response->assertSee('Ketersediaan & Beban Kerja Teknisi');
        $response->assertSee('Teknisi Handal');
        $response->assertSee('1 Tugas Aktif');

        // Check modals exist in view
        $response->assertSee('Pratinjau Data Laporan');
        $response->assertSee('Penugasan Teknisi Lapangan');
        $response->assertSee('Tolak Laporan Kerusakan');
    }

    public function test_utilitas_warning_shown_when_not_filled(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Peringatan Pencatatan Utilitas');
        $response->assertSee(route('admin.utilitas.create'));
    }

    public function test_utilitas_success_shown_when_already_filled(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);

        Utilitas::create([
            'petugas_id' => $admin->id,
            'jenis_utilitas' => 'AirBersih',
            'periode' => now()->format('Y-m'),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertDontSee('Peringatan Pencatatan Utilitas');
        $response->assertSee('Buka Rekap Utilitas');
    }

    public function test_sla_badge_shows_when_report_is_over_24_hours_old(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        $oldReport = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'status' => 'Baru',
            'judul' => 'Kerusakan Lama Menumpuk',
        ]);
        // Update created_at directly in DB to bypass timestamps overwrite
        $oldReport->created_at = now()->subHours(26);
        $oldReport->saveQuietly();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Kerusakan Lama Menumpuk');
        $response->assertSee('> 24 Jam');
    }

    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);
        $response = $this->actingAs($pelapor)->get(route('admin.dashboard'));
        $response->assertStatus(403);

        $teknisi = User::factory()->create(['role' => 'Teknisi']);
        $response = $this->actingAs($teknisi)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }
}
