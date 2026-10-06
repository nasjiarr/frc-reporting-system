<?php

namespace Tests\Feature;

use App\Models\HasilPerbaikan;
use App\Models\Laporan;
use App\Models\Penugasan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanExportPdfTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $teknisi;
    private User $pelapor;
    private Laporan $laporan;
    private Penugasan $penugasan;
    private HasilPerbaikan $hasilPerbaikan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'Admin',
            'is_active' => true,
        ]);

        $this->teknisi = User::factory()->create([
            'role' => 'Teknisi',
            'is_active' => true,
        ]);

        $this->pelapor = User::factory()->create([
            'role' => 'Pelapor',
            'is_active' => true,
        ]);

        $this->laporan = Laporan::create([
            'pelapor_id' => $this->pelapor->id,
            'judul' => 'Pipa Wastafel Bocor',
            'deskripsi' => 'Pipa di bawah wastafel bocor.',
            'lokasi' => 'Toilet Lantai 2',
            'kategori' => 'Plumbing',
            'status' => 'Selesai',
        ]);

        $this->penugasan = Penugasan::create([
            'laporan_id' => $this->laporan->id,
            'teknisi_id' => $this->teknisi->id,
            'assigned_by' => $this->admin->id,
            'status' => 'Selesai',
            'instruksi' => 'Segera perbaiki pipa',
        ]);

        $this->hasilPerbaikan = HasilPerbaikan::create([
            'penugasan_id' => $this->penugasan->id,
            'tindakan' => 'Mengganti pipa PVC yang retak',
            'material_digunakan' => 'Pipa PVC 1/2 inch & Seal Tape',
            'selesai_pada' => now(),
        ]);
    }

    public function test_admin_can_export_rekap_selesai_pdf(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.laporan.export_selesai'));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('content-type'));
    }

    public function test_admin_can_export_single_laporan_pdf(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.laporan.export_pdf', $this->laporan->id));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('content-type'));
    }

    public function test_pdf_rekap_selesai_view_renders_material_digunakan(): void
    {
        $laporans = Laporan::with(['pelapor', 'penugasan.teknisi', 'penugasan.hasilPerbaikan'])->get();
        $periode = 'Semua Periode';
        $search = null;

        $view = $this->view('admin.laporan.pdf_rekap_selesai', compact('laporans', 'periode', 'search'));

        $view->assertSee('Pipa PVC 1/2 inch & Seal Tape');
        $view->assertSee('Mengganti pipa PVC yang retak');
    }

    public function test_pdf_single_laporan_view_renders_material_digunakan(): void
    {
        $laporan = Laporan::with(['pelapor', 'penugasan.teknisi', 'penugasan.hasilPerbaikan'])->first();
        $fotoSebelumBase64 = null;
        $fotoSesudahBase64 = null;

        $view = $this->view('admin.laporan.pdf', compact('laporan', 'fotoSebelumBase64', 'fotoSesudahBase64'));

        $view->assertSee('Pipa PVC 1/2 inch & Seal Tape');
        $view->assertSee('Mengganti pipa PVC yang retak');
    }

    public function test_web_laporan_selesai_filters_by_search_and_date(): void
    {
        // Search match
        $resMatch = $this->actingAs($this->admin)->get(route('admin.laporan.selesai', ['search' => 'Wastafel']));
        $resMatch->assertStatus(200);
        $resMatch->assertSee('Pipa Wastafel Bocor');

        // Search no match
        $resNoMatch = $this->actingAs($this->admin)->get(route('admin.laporan.selesai', ['search' => 'Kelistrikan']));
        $resNoMatch->assertStatus(200);
        $resNoMatch->assertDontSee('Pipa Wastafel Bocor');

        // Date match (today)
        $today = now()->format('Y-m-d');
        $resDate = $this->actingAs($this->admin)->get(route('admin.laporan.selesai', ['tgl_mulai' => $today, 'tgl_selesai' => $today]));
        $resDate->assertStatus(200);
        $resDate->assertSee('Pipa Wastafel Bocor');

        // Date mismatch (past date)
        $resDatePast = $this->actingAs($this->admin)->get(route('admin.laporan.selesai', ['tgl_mulai' => '2020-01-01', 'tgl_selesai' => '2020-01-02']));
        $resDatePast->assertStatus(200);
        $resDatePast->assertDontSee('Pipa Wastafel Bocor');
    }

    public function test_export_selesai_pdf_filters_by_search_and_date(): void
    {
        // Search match
        $resMatch = $this->actingAs($this->admin)->get(route('admin.laporan.export_selesai', ['search' => 'Wastafel']));
        $resMatch->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $resMatch->headers->get('content-type'));

        // Search mismatch
        $resNoMatch = $this->actingAs($this->admin)->get(route('admin.laporan.export_selesai', ['search' => 'NonExistentXYZ']));
        $resNoMatch->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $resNoMatch->headers->get('content-type'));

        // Date match
        $today = now()->format('Y-m-d');
        $resDate = $this->actingAs($this->admin)->get(route('admin.laporan.export_selesai', ['tgl_mulai' => $today, 'tgl_selesai' => $today]));
        $resDate->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $resDate->headers->get('content-type'));
    }

    public function test_pdf_rekap_selesai_header_displays_search_and_period(): void
    {
        $laporans = Laporan::with(['pelapor', 'penugasan.teknisi', 'penugasan.hasilPerbaikan'])->get();
        $periode = '01/10/2026 s/d 05/10/2026';
        $search = 'Wastafel';

        $view = $this->view('admin.laporan.pdf_rekap_selesai', compact('laporans', 'periode', 'search'));

        $view->assertSee('Periode: 01/10/2026 s/d 05/10/2026');
        $view->assertSee('Kata Kunci: "Wastafel"', false);
    }

    public function test_filter_uses_completion_date_instead_of_creation_date(): void
    {
        // Ubah created_at laporan menjadi 10 hari yang lalu
        $this->laporan->update(['created_at' => now()->subDays(10)]);
        $this->hasilPerbaikan->update(['selesai_pada' => now()]);

        $today = now()->format('Y-m-d');

        // Web filter: mencari berdasarkan tanggal penyelesaian hari ini harus menemukan tiket
        $resWeb = $this->actingAs($this->admin)->get(route('admin.laporan.selesai', [
            'tgl_mulai' => $today,
            'tgl_selesai' => $today,
        ]));
        $resWeb->assertStatus(200);
        $resWeb->assertSee('Pipa Wastafel Bocor');

        // Export PDF filter: mencari berdasarkan tanggal penyelesaian hari ini harus mengikutsertakan tiket
        $resPdf = $this->actingAs($this->admin)->get(route('admin.laporan.export_selesai', [
            'tgl_mulai' => $today,
            'tgl_selesai' => $today,
        ]));
        $resPdf->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $resPdf->headers->get('content-type'));

        // Jika mencari dengan rentang tanggal lapor 10 hari lalu, tidak muncul di laporan selesai
        $tenDaysAgo = now()->subDays(10)->format('Y-m-d');
        $resWebPast = $this->actingAs($this->admin)->get(route('admin.laporan.selesai', [
            'tgl_mulai' => $tenDaysAgo,
            'tgl_selesai' => $tenDaysAgo,
        ]));
        $resWebPast->assertStatus(200);
        $resWebPast->assertDontSee('Pipa Wastafel Bocor');
    }

    public function test_admin_export_single_laporan_pdf_fails_gracefully_when_hasil_perbaikan_missing(): void
    {
        // Buat laporan selesai tapi tanpa hasil perbaikan (edge case)
        $laporanIncomplete = Laporan::create([
            'pelapor_id' => $this->pelapor->id,
            'judul' => 'Kabel Putus',
            'deskripsi' => 'Kabel putus di koridor',
            'lokasi' => 'Koridor Barat',
            'kategori' => 'Listrik',
            'status' => 'Selesai',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.laporan.export_pdf', $laporanIncomplete->id));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Data hasil perbaikan belum lengkap untuk dicetak.');
    }

    public function test_admin_export_single_laporan_pdf_fails_gracefully_when_status_not_selesai(): void
    {
        $laporanBaru = Laporan::create([
            'pelapor_id' => $this->pelapor->id,
            'judul' => 'Lampu Kedip',
            'deskripsi' => 'Lampu berkedip',
            'lokasi' => 'Lab 1',
            'kategori' => 'Listrik',
            'status' => 'Baru',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.laporan.export_pdf', $laporanBaru->id));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Laporan belum selesai.');
    }

    public function test_teknisi_export_pdf_fails_gracefully_when_hasil_perbaikan_missing(): void
    {
        $laporanBaru = Laporan::create([
            'pelapor_id' => $this->pelapor->id,
            'judul' => 'Kran Patah',
            'deskripsi' => 'Kran air patah',
            'lokasi' => 'Toilet 1',
            'kategori' => 'Plumbing',
            'status' => 'Selesai',
        ]);

        $penugasanIncomplete = Penugasan::create([
            'laporan_id' => $laporanBaru->id,
            'teknisi_id' => $this->teknisi->id,
            'assigned_by' => $this->admin->id,
            'status_tugas' => 'Selesai',
            'instruksi' => 'Instruksi',
        ]);

        $response = $this->actingAs($this->teknisi)->get(route('teknisi.riwayat.export_pdf', $penugasanIncomplete->id));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Data hasil perbaikan belum lengkap untuk dicetak.');
    }

    public function test_pdf_rekap_selesai_view_renders_empty_state_gracefully(): void
    {
        $laporans = collect([]);
        $periode = 'Semua Periode';
        $search = null;

        $view = $this->view('admin.laporan.pdf_rekap_selesai', compact('laporans', 'periode', 'search'));

        $view->assertSee('Tidak ada data laporan selesai pada kriteria atau periode ini.');
    }

    public function test_web_laporan_selesai_shows_direct_pdf_button_for_completed_reports(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.laporan.selesai'));

        $response->assertStatus(200);
        $response->assertSee(route('admin.laporan.export_pdf', $this->laporan->id));
        $response->assertSee('PDF');
    }
}
