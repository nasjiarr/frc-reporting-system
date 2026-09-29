<?php

namespace Tests\Feature;

use App\Models\Laporan;
use App\Models\Penugasan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LaporanWorkflowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test pelapor dapat membuat laporan baru dan status awalnya 'Baru'.
     */
    public function test_pelapor_can_create_new_laporan_and_initial_status_is_baru(): void
    {
        Storage::fake('public');

        $pelapor = User::factory()->create([
            'role' => 'Pelapor',
        ]);

        $admin = User::factory()->create([
            'role' => 'Admin',
        ]);

        $file = UploadedFile::fake()->image('bukti_rusak.jpg');

        $payload = [
            'judul' => 'AC Ruang Kelas Bocor',
            'lokasi' => 'Hall',
            'deskripsi' => 'Air AC menetes deras membasahi lantai dan meja.',
            'foto_sebelum' => $file,
        ];

        $response = $this
            ->actingAs($pelapor)
            ->post(route('pelapor.laporan.store'), $payload);

        $response->assertRedirect(route('pelapor.laporan.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('laporan', [
            'pelapor_id' => $pelapor->id,
            'judul' => 'AC Ruang Kelas Bocor',
            'lokasi' => 'Hall',
            'status' => 'Baru',
        ]);
    }

    /**
     * Test admin dapat menugaskan teknisi ke laporan tersebut dan status berubah menjadi 'Diproses'.
     */
    public function test_admin_can_assign_technician_and_status_changes_to_diproses(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $teknisi = User::factory()->create(['role' => 'Teknisi']);
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        $laporan = Laporan::create([
            'pelapor_id' => $pelapor->id,
            'judul' => 'Lampu Koridor Mati',
            'lokasi' => 'Panel Room',
            'deskripsi' => 'Lampu utama tidak menyala saat sakelar dinyalakan.',
            'status' => 'Baru',
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.penugasan.store', $laporan->id), [
                'teknisi_id' => $teknisi->id,
                'instruksi' => 'Harap bawa lampu cadangan dan obeng tespen.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Pastikan status laporan berubah menjadi 'Diproses'
        $this->assertDatabaseHas('laporan', [
            'id' => $laporan->id,
            'status' => 'Diproses',
        ]);

        // Pastikan penugasan dibuat untuk teknisi yang bersangkutan
        $this->assertDatabaseHas('penugasan', [
            'laporan_id' => $laporan->id,
            'teknisi_id' => $teknisi->id,
            'assigned_by' => $admin->id,
            'status_tugas' => 'Ditugaskan',
        ]);
    }

    /**
     * Test teknisi yang bersangkutan dapat mengunggah bukti dan menyelesaikan tugas (status menjadi 'Selesai').
     */
    public function test_assigned_technician_can_upload_proof_and_complete_task(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'Admin']);
        $teknisi = User::factory()->create(['role' => 'Teknisi']);
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        $laporan = Laporan::create([
            'pelapor_id' => $pelapor->id,
            'judul' => 'Keran Air Patah',
            'lokasi' => 'Mushola',
            'deskripsi' => 'Keran tempat wudhu patah dan bocor.',
            'status' => 'Diproses',
        ]);

        $penugasan = Penugasan::create([
            'laporan_id' => $laporan->id,
            'teknisi_id' => $teknisi->id,
            'assigned_by' => $admin->id,
            'instruksi' => 'Ganti keran dengan tipe stainless.',
            'status_tugas' => 'Dikerjakan',
        ]);

        $fotoSesudah = UploadedFile::fake()->image('hasil_perbaikan.jpg');

        $response = $this
            ->actingAs($teknisi)
            ->post(route('teknisi.tugas.update', $penugasan->id), [
                'tindakan' => 'Keran lama diganti dengan unit keran baru.',
                'material_digunakan' => 'Keran 1/2 inch, Seal tape',
                'foto_sesudah' => $fotoSesudah,
            ]);

        $response->assertRedirect(route('teknisi.dashboard'));
        $response->assertSessionHas('success');

        // Pastikan status penugasan dan laporan menjadi 'Selesai'
        $this->assertDatabaseHas('penugasan', [
            'id' => $penugasan->id,
            'status_tugas' => 'Selesai',
        ]);

        $this->assertDatabaseHas('laporan', [
            'id' => $laporan->id,
            'status' => 'Selesai',
        ]);

        // Pastikan hasil perbaikan tersimpan
        $this->assertDatabaseHas('hasil_perbaikan', [
            'penugasan_id' => $penugasan->id,
            'tindakan' => 'Keran lama diganti dengan unit keran baru.',
        ]);
    }

    /**
     * Test teknisi lain tidak dapat menyelesaikan tugas yang bukan miliknya (IDOR protection).
     */
    public function test_unassigned_technician_cannot_complete_task_due_to_idor_protection(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'Admin']);
        $teknisiA = User::factory()->create(['role' => 'Teknisi']);
        $teknisiB = User::factory()->create(['role' => 'Teknisi']);
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        $laporan = Laporan::create([
            'pelapor_id' => $pelapor->id,
            'judul' => 'Stopkontak Konslet',
            'lokasi' => 'IT Design Room',
            'deskripsi' => 'Keluar percikan api saat dicolokkan.',
            'status' => 'Diproses',
        ]);

        $penugasan = Penugasan::create([
            'laporan_id' => $laporan->id,
            'teknisi_id' => $teknisiA->id,
            'assigned_by' => $admin->id,
            'instruksi' => 'Ganti stopkontak.',
            'status_tugas' => 'Ditugaskan',
        ]);

        $fotoSesudah = UploadedFile::fake()->image('hasil_perbaikan.jpg');

        // Teknisi B mencoba mengupdate tugas milik Teknisi A
        $response = $this
            ->actingAs($teknisiB)
            ->post(route('teknisi.tugas.update', $penugasan->id), [
                'tindakan' => 'Mencoba bypass',
                'material_digunakan' => 'Kabel',
                'foto_sesudah' => $fotoSesudah,
            ]);

        $response->assertStatus(403);

        // Status tugas dan laporan tidak boleh berubah
        $this->assertDatabaseHas('penugasan', [
            'id' => $penugasan->id,
            'status_tugas' => 'Ditugaskan',
        ]);
        $this->assertDatabaseHas('laporan', [
            'id' => $laporan->id,
            'status' => 'Diproses',
        ]);
    }

    /**
     * Test pengguna dengan role selain Admin tidak bisa mengakses rute admin (harus mengembalikan HTTP 403).
     */
    public function test_non_admin_users_cannot_access_admin_routes(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);
        $teknisi = User::factory()->create(['role' => 'Teknisi']);
        $kepala = User::factory()->create(['role' => 'KepalaFRC']);

        // Pelapor mencoba mengakses rute admin
        $responsePelapor = $this->actingAs($pelapor)->get(route('admin.dashboard'));
        $responsePelapor->assertStatus(403);

        // Teknisi mencoba mengakses rute admin
        $responseTeknisi = $this->actingAs($teknisi)->get(route('admin.dashboard'));
        $responseTeknisi->assertStatus(403);

        // Kepala FRC mencoba mengakses rute admin
        $responseKepala = $this->actingAs($kepala)->get(route('admin.dashboard'));
        $responseKepala->assertStatus(403);
    }
}
