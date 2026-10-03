<?php

namespace Tests\Feature;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PelaporDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_pelapor_can_view_dashboard_with_complete_statistics(): void
    {
        $pelapor = User::factory()->create([
            'role' => 'Pelapor',
            'name' => 'Budi Santoso',
            'nama_lengkap' => 'Budi Santoso',
        ]);

        // Create reports with different statuses
        Laporan::factory()->create(['pelapor_id' => $pelapor->id, 'status' => 'Baru']);
        Laporan::factory()->create(['pelapor_id' => $pelapor->id, 'status' => 'Diproses']);
        Laporan::factory()->create(['pelapor_id' => $pelapor->id, 'status' => 'Selesai']);
        Laporan::factory()->create(['pelapor_id' => $pelapor->id, 'status' => 'Ditolak']);

        $response = $this->actingAs($pelapor)->get(route('pelapor.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('statistik', function ($statistik) {
            return $statistik['total'] === 4
                && $statistik['baru'] === 1
                && $statistik['diproses'] === 1
                && $statistik['selesai'] === 1
                && $statistik['ditolak'] === 1;
        });

        // Greeting & CTA
        $response->assertSee('Halo, Budi Santoso');
        $response->assertSee('+ Buat Laporan Kerusakan');
        $response->assertSee(route('pelapor.laporan.create'));

        // Interactive stat card links
        $response->assertSee(route('pelapor.laporan.index'));
        $response->assertSee(route('pelapor.laporan.index', ['status' => 'Baru']));
        $response->assertSee(route('pelapor.laporan.index', ['status' => 'Diproses']));
        $response->assertSee(route('pelapor.laporan.index', ['status' => 'Selesai']));
        $response->assertSee(route('pelapor.laporan.index', ['status' => 'Ditolak']));
    }

    public function test_alert_callout_is_shown_when_there_are_rejected_reports(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'status' => 'Ditolak',
            'alasan_penolakan' => 'Bukan fasilitas kampus',
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Perhatian: Terdapat 1 Laporan Ditolak');
        $response->assertSee(route('pelapor.laporan.index', ['status' => 'Ditolak']));
    }

    public function test_alert_callout_is_not_shown_when_no_rejected_reports(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'status' => 'Baru',
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.dashboard'));

        $response->assertStatus(200);
        $response->assertDontSee('Perhatian: Terdapat');
    }

    public function test_empty_state_is_rendered_when_pelapor_has_no_reports(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        $response = $this->actingAs($pelapor)->get(route('pelapor.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Belum Ada Laporan Kerusakan');
        $response->assertSee('+ Buat Laporan Kerusakan');
    }

    public function test_recent_reports_table_has_detail_links_and_view_all_link(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        $laporan = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'judul' => 'Kipas Angin Rusak di Lab 2',
            'lokasi' => 'Lab Komputer 2',
            'status' => 'Baru',
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('5 Laporan Terbaru');
        $response->assertSee('Lihat Semua Laporan');
        $response->assertSee('Kipas Angin Rusak di Lab 2');
        $response->assertSee('Lab Komputer 2');
        $response->assertSee(route('pelapor.laporan.show', $laporan->id));
        $response->assertSee('Detail');
    }
}
