<?php

namespace Tests\Feature;

use App\Models\Laporan;
use App\Models\Notifikasi;
use App\Models\Penugasan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_unread_returns_notification_with_link(): void
    {
        $user = User::factory()->create(['role' => 'Pelapor']);
        $notif = Notifikasi::create([
            'user_id' => $user->id,
            'judul'   => 'Laporan Diproses',
            'pesan'   => 'Laporan Anda sedang diperbaiki.',
            'link'    => '/pelapor/laporan/1',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user)->getJson('/api/notifications/unread');

        $response->assertOk()
            ->assertJsonPath('count', 1)
            ->assertJsonPath('items.0.id', $notif->id)
            ->assertJsonPath('items.0.link', '/pelapor/laporan/1');
    }

    public function test_api_mark_read_marks_notifications_as_read(): void
    {
        $user = User::factory()->create(['role' => 'Pelapor']);
        Notifikasi::create([
            'user_id' => $user->id,
            'judul'   => 'Tugas',
            'pesan'   => 'Pesan',
            'link'    => '/link',
            'is_read' => false,
        ]);

        $this->actingAs($user)->postJson('/api/notifications/mark-read')
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $user->id,
            'is_read' => true,
        ]);
    }

    public function test_assign_technician_stores_notification_with_correct_links(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $teknisi = User::factory()->create(['role' => 'Teknisi']);
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        $laporan = Laporan::create([
            'pelapor_id' => $pelapor->id,
            'judul'      => 'Kran Rusak',
            'lokasi'     => 'Lab Kimia',
            'deskripsi'  => 'Air menetes',
            'status'     => 'Baru',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.penugasan.store', $laporan->id), [
            'teknisi_id' => $teknisi->id,
            'instruksi'  => 'Perbaiki segera',
        ]);

        $response->assertSessionHas('success');

        $penugasan = Penugasan::where('laporan_id', $laporan->id)->first();
        $this->assertNotNull($penugasan);

        // Notifikasi ke teknisi memiliki link ke detail tugasnya
        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $teknisi->id,
            'judul'   => 'Tugas Baru Diberikan',
            'link'    => route('teknisi.tugas.show', $penugasan->id, false),
        ]);

        // Notifikasi ke pelapor memiliki link ke show laporan
        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $pelapor->id,
            'judul'   => 'Laporan Diproses',
            'link'    => route('pelapor.laporan.show', $laporan->id, false),
        ]);
    }

    public function test_notifications_only_sent_to_active_admins(): void
    {
        $activeAdmin = User::factory()->create(['role' => 'Admin', 'is_active' => true]);
        $inactiveAdmin = User::factory()->create(['role' => 'Admin', 'is_active' => false]);
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        $image = \Illuminate\Http\UploadedFile::fake()->image('bukti.jpg');

        $response = $this->actingAs($pelapor)->post(route('pelapor.laporan.store'), [
            'judul'        => 'Lampu Mati',
            'lokasi'       => 'Ruang 101',
            'deskripsi'    => 'Lampu padam total',
            'foto_sebelum' => $image,
        ]);

        $response->assertRedirect(route('pelapor.laporan.index'));

        // Admin aktif menerima notifikasi
        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $activeAdmin->id,
            'judul'   => 'Laporan Kerusakan Baru',
        ]);

        // Admin nonaktif TIDAK menerima notifikasi
        $this->assertDatabaseMissing('notifikasi', [
            'user_id' => $inactiveAdmin->id,
        ]);
    }
}
