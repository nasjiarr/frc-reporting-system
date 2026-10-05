<?php

namespace Tests\Feature;

use App\Models\Laporan;
use App\Models\Penugasan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPenugasanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_penugasan_index_with_tabs_and_statistics(): void
    {
        $admin = User::factory()->create([
            'role' => 'Admin',
            'nama_lengkap' => 'Admin FRC',
        ]);

        $pelapor = User::factory()->create(['role' => 'Pelapor', 'nama_lengkap' => 'Pelapor Satu']);
        $teknisi = User::factory()->create(['role' => 'Teknisi', 'nama_lengkap' => 'Teknisi Alpha']);

        // 1 Laporan baru
        $laporanBaru = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'status' => 'Baru',
            'judul' => 'Kran Toilet Bocor',
            'lokasi' => 'Gedung A Lt 1',
        ]);

        // 1 Laporan diproses dengan penugasan
        $laporanDiproses = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'status' => 'Diproses',
            'judul' => 'AC Ruang Server Mati',
            'lokasi' => 'Server Room',
        ]);

        Penugasan::create([
            'laporan_id' => $laporanDiproses->id,
            'teknisi_id' => $teknisi->id,
            'assigned_by' => $admin->id,
            'instruksi' => 'Harap dicek freon dan kompresor.',
            'status_tugas' => 'Dikerjakan',
            'assigned_at' => now()->subHour(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.penugasan.index'));

        $response->assertStatus(200);
        $response->assertViewHas('stats', function ($stats) {
            return $stats['menunggu'] === 1
                && $stats['dikerjakan'] === 1
                && $stats['teknisi_total'] === 1;
        });

        // Cek teks UI
        $response->assertSee('Manajemen Penugasan Teknisi');
        $response->assertSee('Laporan Menunggu Penugasan');
        $response->assertSee('Monitoring Penugasan Lapangan');
        $response->assertSee('Radar Kesiapan Teknisi');

        // Cek data laporan baru
        $response->assertSee('Kran Toilet Bocor');
        $response->assertSee('Gedung A Lt 1');
        $response->assertSee('Pelapor Satu');

        // Cek data monitoring penugasan
        $response->assertSee('AC Ruang Server Mati');
        $response->assertSee('Server Room');
        $response->assertSee('Teknisi Alpha');
        $response->assertSee('Dikerjakan');

        // Cek modal exists
        $response->assertSee('Penugasan Teknisi Lapangan');
        $response->assertSee('Pratinjau Data Laporan');
        $response->assertSee('Konfirmasi Tolak Laporan');
    }

    public function test_sla_badge_shows_for_reports_over_24_hours(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        $laporan = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'status' => 'Baru',
            'judul' => 'Plafon Rusak Lama',
        ]);
        $laporan->created_at = now()->subHours(30);
        $laporan->saveQuietly();

        $response = $this->actingAs($admin)->get(route('admin.penugasan.index'));

        $response->assertStatus(200);
        $response->assertSee('Plafon Rusak Lama');
        $response->assertSee('> 24 Jam');
    }

    public function test_admin_can_filter_monitoring_by_status_and_search(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $pelapor = User::factory()->create(['role' => 'Pelapor']);
        $teknisiA = User::factory()->create(['role' => 'Teknisi', 'nama_lengkap' => 'Teknisi A']);
        $teknisiB = User::factory()->create(['role' => 'Teknisi', 'nama_lengkap' => 'Teknisi B']);

        $lap1 = Laporan::factory()->create(['pelapor_id' => $pelapor->id, 'status' => 'Diproses', 'judul' => 'Kerusakan Lift']);
        Penugasan::create([
            'laporan_id' => $lap1->id,
            'teknisi_id' => $teknisiA->id,
            'assigned_by' => $admin->id,
            'status_tugas' => 'Ditugaskan',
            'assigned_at' => now(),
        ]);

        $lap2 = Laporan::factory()->create(['pelapor_id' => $pelapor->id, 'status' => 'Diproses', 'judul' => 'Kipas Exhaust Rusak']);
        Penugasan::create([
            'laporan_id' => $lap2->id,
            'teknisi_id' => $teknisiB->id,
            'assigned_by' => $admin->id,
            'status_tugas' => 'Dikerjakan',
            'assigned_at' => now(),
        ]);

        // Filter status 'Ditugaskan'
        $response = $this->actingAs($admin)->get(route('admin.penugasan.index', ['status' => 'Ditugaskan', 'tab' => 'monitoring']));
        $response->assertStatus(200);
        $response->assertSee('Kerusakan Lift');
        $response->assertDontSee('Kipas Exhaust Rusak');

        // Filter search 'Exhaust'
        $responseSearch = $this->actingAs($admin)->get(route('admin.penugasan.index', ['search' => 'Exhaust', 'tab' => 'monitoring']));
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('Kipas Exhaust Rusak');
        $responseSearch->assertDontSee('Kerusakan Lift');
    }

    public function test_assign_modal_displays_technician_workload(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $pelapor = User::factory()->create(['role' => 'Pelapor']);
        $teknisi = User::factory()->create(['role' => 'Teknisi', 'nama_lengkap' => 'Budi Santoso']);

        $laporan1 = Laporan::factory()->create(['pelapor_id' => $pelapor->id, 'status' => 'Diproses']);
        Penugasan::create([
            'laporan_id' => $laporan1->id,
            'teknisi_id' => $teknisi->id,
            'assigned_by' => $admin->id,
            'status_tugas' => 'Dikerjakan',
            'assigned_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.penugasan.index'));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso (1 tugas aktif)');
    }

    public function test_non_admin_cannot_access_penugasan_index(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);
        $this->actingAs($pelapor)->get(route('admin.penugasan.index'))->assertStatus(403);

        $teknisi = User::factory()->create(['role' => 'Teknisi']);
        $this->actingAs($teknisi)->get(route('admin.penugasan.index'))->assertStatus(403);
    }
}
