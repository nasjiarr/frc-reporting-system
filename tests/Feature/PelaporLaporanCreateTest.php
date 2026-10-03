<?php

namespace Tests\Feature;

use App\Models\Laporan;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PelaporLaporanCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_pelapor_can_render_modern_create_laporan_page(): void
    {
        $pelapor = User::factory()->create([
            'role' => 'Pelapor',
            'name' => 'Ahmad Pelapor',
            'nama_lengkap' => 'Ahmad Pelapor',
        ]);

        $response = $this->actingAs($pelapor)->get(route('pelapor.laporan.create'));

        $response->assertStatus(200);

        // Header & Breadcrumbs
        $response->assertSee('Form Pelaporan Kerusakan');
        $response->assertSee('Dashboard');
        $response->assertSee('Laporan Saya');
        $response->assertSee('Buat Laporan');
        $response->assertSee(route('pelapor.dashboard'));
        $response->assertSee(route('pelapor.laporan.index'));

        // Form & Inputs
        $response->assertSee(route('pelapor.laporan.store'));
        $response->assertSee('name="judul"', false);
        $response->assertSee('name="lokasi"', false);
        $response->assertSee('name="deskripsi"', false);
        $response->assertSee('name="foto_sebelum"', false);

        // Quick Suggestion Chips
        $response->assertSee('Pilihan Cepat Masalah Umum:');
        $response->assertSee('AC Tidak Dingin / Bocor');
        $response->assertSee('Lampu / Kelistrikan Mati');
        $response->assertSee('Kran / Pipa Bocor');
        $response->assertSee('Proyektor / Audio Rusak');
        $response->assertSee('Pintu / Kunci Rusak');

        // Dropzone & Camera Capture Attributes
        $response->assertSee('dropzone-container');
        $response->assertSee('dropzone-empty');
        $response->assertSee('dropzone-preview');
        $response->assertSee('image-preview');
        $response->assertSee('btn-change-photo');
        $response->assertSee('btn-remove-photo');
        $response->assertSee('capture="environment"', false);
        $response->assertSee('accept="image/jpeg,image/png,image/jpg"', false);
        $response->assertSee('compress-info-foto_sebelum');

        // Textarea & Character Counter
        $response->assertSee('char-count');
        $response->assertSee('char-counter');

        // Double-Submit Protection Elements
        $response->assertSee('btn-submit');
        $response->assertSee('Kirim Laporan');

        // Sidebar Helper Cards
        $response->assertSee('Panduan Foto Bukti');
        $response->assertSee('Disarankan (Do)');
        $response->assertSee('Hindari (Don\'t)', false);
        $response->assertSee('Alur Penanganan Laporan');
        $response->assertSee('Laporan Terkirim (Baru)');
        $response->assertSee('Penugasan Teknisi (Diproses)');
        $response->assertSee('Pengerjaan di Lapangan');
        $response->assertSee('Konfirmasi Selesai');
        $response->assertSee('Kontak Darurat Fasilitas');
        $response->assertSee('Pos Keamanan FRC');
        $response->assertSee('Helpdesk Sarpras');

        // Datalist Locations from config
        $daftarRuangan = config('frc.ruangan', []);
        if (!empty($daftarRuangan)) {
            $response->assertSee($daftarRuangan[0]);
        }
    }

    public function test_unauthenticated_user_cannot_access_create_page(): void
    {
        $response = $this->get(route('pelapor.laporan.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_non_pelapor_cannot_access_pelapor_create_page(): void
    {
        $teknisi = User::factory()->create([
            'role' => 'Teknisi',
        ]);

        $response = $this->actingAs($teknisi)->get(route('pelapor.laporan.create'));
        $response->assertStatus(403);
    }

    public function test_pelapor_can_submit_valid_report_and_it_is_saved(): void
    {
        Storage::fake('public');

        $pelapor = User::factory()->create([
            'role' => 'Pelapor',
        ]);

        $admin = User::factory()->create([
            'role' => 'Admin',
        ]);

        $file = UploadedFile::fake()->image('bukti_ac_rusak.jpg', 600, 600);

        $payload = [
            'judul' => 'AC Tidak Dingin / Bocor',
            'lokasi' => 'Hall',
            'deskripsi' => 'AC di pojok kiri atas meneteskan air sangat deras hingga membasahi karpet.',
            'foto_sebelum' => $file,
        ];

        $response = $this
            ->actingAs($pelapor)
            ->post(route('pelapor.laporan.store'), $payload);

        $response->assertRedirect(route('pelapor.laporan.index'));
        $response->assertSessionHas('success', 'Laporan berhasil dikirim.');

        $this->assertDatabaseHas('laporan', [
            'pelapor_id' => $pelapor->id,
            'judul' => 'AC Tidak Dingin / Bocor',
            'lokasi' => 'Hall',
            'deskripsi' => 'AC di pojok kiri atas meneteskan air sangat deras hingga membasahi karpet.',
            'status' => 'Baru',
        ]);

        $laporan = Laporan::where('pelapor_id', $pelapor->id)->first();
        $this->assertNotNull($laporan->foto_sebelum);
        Storage::disk('public')->assertExists($laporan->foto_sebelum);

        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $admin->id,
            'judul' => 'Laporan Kerusakan Baru',
        ]);
    }

    public function test_validation_errors_when_required_fields_are_missing(): void
    {
        $pelapor = User::factory()->create([
            'role' => 'Pelapor',
        ]);

        $response = $this
            ->actingAs($pelapor)
            ->post(route('pelapor.laporan.store'), []);

        $response->assertSessionHasErrors(['judul', 'lokasi', 'deskripsi', 'foto_sebelum']);
    }

    public function test_validation_fails_for_non_image_file(): void
    {
        $pelapor = User::factory()->create([
            'role' => 'Pelapor',
        ]);

        $file = UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf');

        $response = $this
            ->actingAs($pelapor)
            ->post(route('pelapor.laporan.store'), [
                'judul' => 'Kendala Kursi',
                'lokasi' => 'Hall',
                'deskripsi' => 'Deskripsi kendala minimal 20 karakter yang valid.',
                'foto_sebelum' => $file,
            ]);

        $response->assertSessionHasErrors(['foto_sebelum']);
    }

    public function test_validation_fails_when_image_exceeds_max_size(): void
    {
        $pelapor = User::factory()->create([
            'role' => 'Pelapor',
        ]);

        // 3MB image exceeds the 2048 KB limit
        $file = UploadedFile::fake()->image('foto_besar.jpg')->size(3000);

        $response = $this
            ->actingAs($pelapor)
            ->post(route('pelapor.laporan.store'), [
                'judul' => 'Kendala AC Rusak',
                'lokasi' => 'Hall',
                'deskripsi' => 'Deskripsi kendala minimal 20 karakter yang valid.',
                'foto_sebelum' => $file,
            ]);

        $response->assertSessionHasErrors(['foto_sebelum']);
    }
}
