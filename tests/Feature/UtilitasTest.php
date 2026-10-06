<?php

namespace Tests\Feature;

use App\Models\AirBersih;
use App\Models\User;
use App\Models\Utilitas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UtilitasTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $kepala;
    protected User $pelapor;
    protected Utilitas $utilitasAir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'Admin',
            'is_active' => true,
        ]);

        $this->kepala = User::factory()->create([
            'role' => 'KepalaFRC',
            'is_active' => true,
        ]);

        $this->pelapor = User::factory()->create([
            'role' => 'Pelapor',
            'is_active' => true,
        ]);

        $this->utilitasAir = Utilitas::create([
            'petugas_id' => $this->admin->id,
            'jenis_utilitas' => 'AirBersih',
            'periode' => '2026-09',
        ]);

        AirBersih::create([
            'id' => $this->utilitasAir->id,
            'tgl_awal' => '2026-09-01',
            'tgl_akhir' => '2026-09-30',
            'stand_awal' => 100.00,
            'stand_akhir' => 150.00,
        ]);
    }

    public function test_admin_can_view_utilitas_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.utilitas.index'));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Utilitas Gedung');
    }

    public function test_admin_can_view_catat_utilitas_create_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.utilitas.create'));

        $response->assertStatus(200);
        $response->assertSee('Catat Stand Meter Utilitas');
        $response->assertSee('AirBersih');
        $response->assertSee('Simpan Data Utilitas');
        $response->assertSee('dark:bg-gray-800');
    }


    public function test_admin_edit_page_renders_html_view_not_raw_json(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.utilitas.edit', $this->utilitasAir->id));

        $response->assertStatus(200);
        $response->assertSee('Edit Pencatatan Utilitas');
        $response->assertSee('2026-09');
        $response->assertHeader('Content-Type', 'text/html; charset=UTF-8');
    }

    public function test_admin_can_update_utilitas(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.utilitas.update', $this->utilitasAir->id), [
            'periode' => '2026-09',
            'tgl_awal' => '2026-09-01',
            'tgl_akhir' => '2026-09-30',
            'stand_awal' => 100.00,
            'stand_akhir' => 175.00,
        ]);

        $response->assertRedirect(route('admin.utilitas.show', 'AirBersih'));
        $this->assertDatabaseHas('air_bersih', [
            'id' => $this->utilitasAir->id,
            'stand_akhir' => 175.00,
        ]);
    }

    public function test_kepala_frc_can_view_rekap_utilitas_with_consumption(): void
    {
        $response = $this->actingAs($this->kepala)->get(route('kepala.utilitas.index', ['bulan' => '2026-09']));

        $response->assertStatus(200);
        $response->assertSee('Rekapitulasi Utilitas');
        $response->assertSee('AirBersih');
        $response->assertSee('50,00 m³');
    }

    public function test_kepala_frc_can_export_rekap_utilitas_pdf(): void
    {
        $response = $this->actingAs($this->kepala)->get(route('kepala.utilitas.export', ['bulan' => '2026-09']));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('content-type'));
    }

    public function test_admin_can_export_utilitas_pdf(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.utilitas.export_pdf', [
            'jenis' => 'AirBersih',
            'tahun' => '2026',
        ]));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('content-type'));
    }

    public function test_pelapor_cannot_access_utilitas_admin_or_kepala_routes(): void
    {
        $this->actingAs($this->pelapor)->get(route('admin.utilitas.index'))->assertStatus(403);
        $this->actingAs($this->pelapor)->get(route('kepala.utilitas.index'))->assertStatus(403);
    }

    public function test_admin_cannot_store_duplicate_periode_for_same_utilitas(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.utilitas.store'), [
            'jenis_utilitas' => 'AirBersih',
            'periode' => '2026-09', // Periode yang sudah ada di setUp
            'tgl_awal' => '2026-09-01',
            'tgl_akhir' => '2026-09-30',
            'stand_awal' => 150.00,
            'stand_akhir' => 200.00,
        ]);

        $response->assertSessionHasErrors('periode');
    }

    public function test_admin_cannot_input_stand_akhir_less_than_stand_awal(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.utilitas.store'), [
            'jenis_utilitas' => 'AirHujan',
            'periode' => '2026-10',
            'tgl_awal' => '2026-10-01',
            'tgl_akhir' => '2026-10-31',
            'stand_awal' => 500.00,
            'stand_akhir' => 450.00, // Error: stand akhir < stand awal
        ]);

        $response->assertSessionHasErrors('stand_akhir');
    }

    public function test_admin_can_successfully_store_new_utilitas(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.utilitas.store'), [
            'jenis_utilitas' => 'MDP',
            'periode' => '2026-10',
            'tgl_awal' => '2026-10-01',
            'tgl_akhir' => '2026-10-31',
            'stand_awal' => 1000.00,
            'stand_akhir' => 1250.00,
        ]);

        $response->assertRedirect(route('admin.utilitas.create'));
        $this->assertDatabaseHas('utilitas', [
            'jenis_utilitas' => 'MDP',
            'periode' => '2026-10',
        ]);
        $this->assertDatabaseHas('listrik_mdp', [
            'stand_awal' => 1000.00,
            'stand_akhir' => 1250.00,
        ]);
    }

    public function test_admin_can_view_utilitas_show_page_with_kpi_metrics(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.utilitas.show', 'AirBersih'));

        $response->assertStatus(200);
        $response->assertSee('Detail Utilitas: AirBersih');
        $response->assertSee('Total Konsumsi');
        $response->assertSee('Rata-rata Bulanan');
        $response->assertSee('Puncak Konsumsi Bulanan');
        $response->assertSee('50,00 m³');
    }

    public function test_utilitas_detail_route_redirects_to_show(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.utilitas.detail', 'AirBersih'));

        $response->assertRedirect(route('admin.utilitas.show', [
            'jenis' => 'AirBersih',
            'tahun' => date('Y'),
        ]));
    }

    public function test_invalid_jenis_utilitas_returns_404(): void
    {
        $this->actingAs($this->admin)->get(route('admin.utilitas.show', 'JenisNgawur'))->assertStatus(404);
        $this->actingAs($this->admin)->get(route('admin.utilitas.export_pdf', 'JenisNgawur'))->assertStatus(404);
        $this->actingAs($this->admin)->get(route('admin.utilitas.detail', 'JenisNgawur'))->assertStatus(404);
    }

    public function test_kepala_utilitas_pdf_view_renders_empty_state(): void
    {
        $view = $this->view('kepala.utilitas-pdf', [
            'details' => collect([]),
            'periode' => '2019-01',
        ]);

        $view->assertSee('Belum ada data pencatatan utilitas untuk periode ini.');
    }
}
