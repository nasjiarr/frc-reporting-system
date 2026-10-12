<?php

namespace Tests\Feature;

use App\Models\Laporan;
use App\Models\Penugasan;
use App\Models\User;
use App\Models\Utilitas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KepalaDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_kepala_can_view_dashboard_with_complete_kpi_metrics(): void
    {
        $kepala = User::factory()->create([
            'role' => 'KepalaFRC',
            'name' => 'Dr. Bambang',
            'nama_lengkap' => 'Dr. Bambang Kepala FRC',
        ]);

        $admin = User::factory()->create(['role' => 'Admin']);
        $teknisi = User::factory()->create(['role' => 'Teknisi', 'name' => 'Teknisi Satu', 'nama_lengkap' => 'Teknisi Satu']);
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        // 1. Laporan Baru
        Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'status' => 'Baru',
            'judul' => 'Kabel Terkelupas di Koridor',
            'lokasi' => 'Koridor Lt 1',
        ]);

        // 2. Laporan Diproses
        $lapProses = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'status' => 'Diproses',
            'judul' => 'AC Ruang Rapat Panas',
            'lokasi' => 'Ruang Rapat Utama',
        ]);
        Penugasan::create([
            'laporan_id' => $lapProses->id,
            'teknisi_id' => $teknisi->id,
            'assigned_by' => $admin->id,
            'status_tugas' => 'Dikerjakan',
        ]);

        // 3. Laporan Selesai
        $lapSelesai = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'status' => 'Selesai',
            'judul' => 'Kran Wastafel Bocor',
            'lokasi' => 'Toilet Lantai 2',
        ]);
        Penugasan::create([
            'laporan_id' => $lapSelesai->id,
            'teknisi_id' => $teknisi->id,
            'assigned_by' => $admin->id,
            'status_tugas' => 'Selesai',
        ]);

        $response = $this->actingAs($kepala)->get(route('kepala.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('kpi');
        $response->assertViewHas('laporanTerbaru');
        $response->assertViewHas('kinerjaTeknisi');

        $kpi = $response->viewData('kpi');
        $this->assertEquals(3, $kpi['total_masuk']);
        $this->assertEquals(1, $kpi['baru']);
        $this->assertEquals(1, $kpi['sedang_dikerjakan']);
        $this->assertEquals(1, $kpi['total_selesai']);
        $this->assertEquals(33.3, $kpi['completion_rate']);

        $response->assertSee('Dashboard Kepala FRC');
        $response->assertSee('Selamat Datang, Dr. Bambang Kepala FRC!');
        $response->assertSee('Kabel Terkelupas di Koridor');
        $response->assertSee('AC Ruang Rapat Panas');
        $response->assertSee('Kran Wastafel Bocor');
        $response->assertSee('Teknisi Satu');
    }

    public function test_kepala_dashboard_renders_empty_states_gracefully(): void
    {
        $kepala = User::factory()->create(['role' => 'KepalaFRC']);

        $response = $this->actingAs($kepala)->get(route('kepala.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Belum Ada Laporan Masuk');
        $response->assertSee('Belum ada data teknisi.');

        $kpi = $response->viewData('kpi');
        $this->assertEquals(0, $kpi['total_masuk']);
        $this->assertEquals(0, $kpi['completion_rate']);
    }

    public function test_non_kepala_cannot_access_kepala_dashboard(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);
        $teknisi = User::factory()->create(['role' => 'Teknisi']);

        $this->actingAs($pelapor)->get(route('kepala.dashboard'))->assertStatus(403);
        $this->actingAs($teknisi)->get(route('kepala.dashboard'))->assertStatus(403);
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $this->get(route('kepala.dashboard'))->assertRedirect(route('login'));
        $this->get(route('kepala.laporan.index'))->assertRedirect(route('login'));
    }

    public function test_kepala_can_view_rekap_laporan_with_filters_and_search(): void
    {
        $kepala = User::factory()->create(['role' => 'KepalaFRC']);
        $pelapor = User::factory()->create(['role' => 'Pelapor', 'name' => 'Budi Santoso', 'nama_lengkap' => 'Budi Santoso']);
        $teknisi = User::factory()->create(['role' => 'Teknisi', 'name' => 'Teknisi Joko']);
        $admin = User::factory()->create(['role' => 'Admin']);

        $lap1 = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'judul' => 'Genset Mati Ruang Server',
            'lokasi' => 'Server Lt 3',
            'status' => 'Baru',
            'created_at' => now(),
        ]);

        $lap2 = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'judul' => 'Lift Macet Gedung B',
            'lokasi' => 'Lift Gedung B',
            'status' => 'Diproses',
            'created_at' => now(),
        ]);
        Penugasan::create([
            'laporan_id' => $lap2->id,
            'teknisi_id' => $teknisi->id,
            'assigned_by' => $admin->id,
            'status_tugas' => 'Dikerjakan',
        ]);

        $lap3 = Laporan::factory()->create([
            'pelapor_id' => $pelapor->id,
            'judul' => 'Plafon Bocor Ruang Tamu',
            'lokasi' => 'Lobi Utama',
            'status' => 'Selesai',
            'created_at' => now(),
        ]);

        // 1. Akses Rekap Laporan Tanpa Filter
        $response = $this->actingAs($kepala)->get(route('kepala.laporan.index'));
        $response->assertStatus(200);
        $response->assertViewHas('laporans');
        $response->assertViewHas('stats');
        $response->assertSee('Rekapitulasi Laporan Kerusakan');
        $response->assertSee('Genset Mati Ruang Server');
        $response->assertSee('Lift Macet Gedung B');
        $response->assertSee('Plafon Bocor Ruang Tamu');

        // 2. Filter Status = Diproses
        $resFilter = $this->actingAs($kepala)->get(route('kepala.laporan.index', ['status' => 'Diproses']));
        $resFilter->assertStatus(200);
        $resFilter->assertSee('Lift Macet Gedung B');
        $resFilter->assertDontSee('Genset Mati Ruang Server');

        // 3. Search Kata Kunci = "Genset"
        $resSearch = $this->actingAs($kepala)->get(route('kepala.laporan.index', ['search' => 'Genset']));
        $resSearch->assertStatus(200);
        $resSearch->assertSee('Genset Mati Ruang Server');
        $resSearch->assertDontSee('Lift Macet Gedung B');

        // 4. Search Nama Pelapor = "Budi Santoso"
        $resSearchPelapor = $this->actingAs($kepala)->get(route('kepala.laporan.index', ['search' => 'Budi Santoso']));
        $resSearchPelapor->assertStatus(200);
        $resSearchPelapor->assertSee('Genset Mati Ruang Server');
    }

    public function test_kepala_rekap_laporan_handles_empty_results(): void
    {
        $kepala = User::factory()->create(['role' => 'KepalaFRC']);

        $response = $this->actingAs($kepala)->get(route('kepala.laporan.index', ['search' => 'KeywordTidakAdaSamaSekali']));
        $response->assertStatus(200);
        $response->assertSee('Tidak Ada Laporan yang Sesuai');
    }

    public function test_non_kepala_cannot_access_rekap_laporan(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);
        $teknisi = User::factory()->create(['role' => 'Teknisi']);

        $this->actingAs($pelapor)->get(route('kepala.laporan.index'))->assertStatus(403);
        $this->actingAs($teknisi)->get(route('kepala.laporan.index'))->assertStatus(403);
    }

    public function test_kepala_can_view_laporan_saya_with_own_reports(): void
    {
        $kepala = User::factory()->create(['role' => 'KepalaFRC', 'name' => 'Dr. Bambang']);
        $otherPelapor = User::factory()->create(['role' => 'Pelapor']);
        $teknisi = User::factory()->create(['role' => 'Teknisi', 'name' => 'Teknisi Roni']);
        $admin = User::factory()->create(['role' => 'Admin']);

        // Laporan milik Kepala sendiri (Baru)
        Laporan::factory()->create([
            'pelapor_id' => $kepala->id,
            'judul' => 'Lampu Sorot Halaman Padam',
            'lokasi' => 'Halaman Depan FRC',
            'status' => 'Baru',
        ]);

        // Laporan milik Kepala sendiri (Ditolak)
        Laporan::factory()->create([
            'pelapor_id' => $kepala->id,
            'judul' => 'Penggantian Karpet Aula',
            'lokasi' => 'Aula Utama',
            'status' => 'Ditolak',
            'alasan_penolakan' => 'Menunggu anggaran triwulan depan',
        ]);

        // Laporan orang lain (tidak boleh muncul di Laporan Saya)
        Laporan::factory()->create([
            'pelapor_id' => $otherPelapor->id,
            'judul' => 'Kerusakan Milik Orang Lain',
            'lokasi' => 'Ruang 404',
            'status' => 'Baru',
        ]);

        $response = $this->actingAs($kepala)->get(route('kepala.laporan_saya.index'));
        $response->assertStatus(200);
        $response->assertViewHas('laporans');
        $response->assertViewHas('stats');
        $response->assertSee('Daftar Laporan Saya');
        $response->assertSee('Lampu Sorot Halaman Padam');
        $response->assertSee('Penggantian Karpet Aula');
        $response->assertSee('Perhatian: Terdapat 1 Laporan Ditolak');
        $response->assertDontSee('Kerusakan Milik Orang Lain');

        // Test filter status
        $resBaru = $this->actingAs($kepala)->get(route('kepala.laporan_saya.index', ['status' => 'Baru']));
        $resBaru->assertStatus(200);
        $resBaru->assertSee('Lampu Sorot Halaman Padam');
        $resBaru->assertDontSee('Penggantian Karpet Aula');

        // Test search
        $resSearch = $this->actingAs($kepala)->get(route('kepala.laporan_saya.index', ['search' => 'Sorot']));
        $resSearch->assertStatus(200);
        $resSearch->assertSee('Lampu Sorot Halaman Padam');
        $resSearch->assertDontSee('Penggantian Karpet Aula');
    }

    public function test_non_kepala_cannot_access_laporan_saya(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);
        $teknisi = User::factory()->create(['role' => 'Teknisi']);

        $this->actingAs($pelapor)->get(route('kepala.laporan_saya.index'))->assertStatus(403);
        $this->actingAs($teknisi)->get(route('kepala.laporan_saya.index'))->assertStatus(403);
    }
}
