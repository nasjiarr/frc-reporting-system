<?php

namespace Tests\Feature;

use App\Models\Penugasan;
use App\Models\Laporan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_users_index_with_kpi_statistics_and_user_list(): void
    {
        $admin = User::factory()->create([
            'role' => 'Admin',
            'nama_lengkap' => 'Admin FRC Utama',
        ]);

        $teknisi = User::factory()->create([
            'role' => 'Teknisi',
            'nama_lengkap' => 'Teknisi Lapangan 1',
            'is_active' => true,
        ]);

        $pelapor = User::factory()->create([
            'role' => 'Pelapor',
            'nama_lengkap' => 'Pelapor Ruangan A',
            'is_active' => false,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertViewHas('users');
        $response->assertViewHas('stats', function ($stats) {
            return $stats['total'] === 3
                && $stats['aktif'] === 2
                && $stats['nonaktif'] === 1
                && $stats['teknisi_total'] === 1
                && $stats['pelapor_total'] === 1
                && $stats['admin_total'] === 1;
        });

        $response->assertSee('Admin FRC Utama');
        $response->assertSee('Teknisi Lapangan 1');
        $response->assertSee('Pelapor Ruangan A');
        $response->assertSee('3 Akun Terdaftar');
        $response->assertSee('open-user-modal');
        $response->assertSee('Registrasi Pengguna Baru');
        $response->assertSee('Tambah User Baru');
    }

    public function test_admin_can_filter_users_by_role_and_status(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $teknisiAktif = User::factory()->create(['role' => 'Teknisi', 'nama_lengkap' => 'Budi Teknisi', 'is_active' => true]);
        $pelaporNonaktif = User::factory()->create(['role' => 'Pelapor', 'nama_lengkap' => 'Siti Pelapor', 'is_active' => false]);

        // Filter role Teknisi
        $responseRole = $this->actingAs($admin)->get(route('admin.users.index', ['role' => 'Teknisi']));
        $responseRole->assertStatus(200);
        $responseRole->assertSee('Budi Teknisi');
        $responseRole->assertDontSee('Siti Pelapor');

        // Filter status nonaktif
        $responseStatus = $this->actingAs($admin)->get(route('admin.users.index', ['status' => 'nonaktif']));
        $responseStatus->assertStatus(200);
        $responseStatus->assertSee('Siti Pelapor');
        $responseStatus->assertDontSee('Budi Teknisi');
    }

    public function test_admin_can_search_users_by_name_email_or_phone(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $userA = User::factory()->create([
            'nama_lengkap' => 'Bambang Pamungkas',
            'email' => 'bambang@frc.test',
            'no_telepon' => '081299998888',
        ]);
        $userB = User::factory()->create([
            'nama_lengkap' => 'Dewi Sartika',
            'email' => 'dewi@frc.test',
            'no_telepon' => '087711112222',
        ]);

        // Search by name
        $responseName = $this->actingAs($admin)->get(route('admin.users.index', ['search' => 'Bambang']));
        $responseName->assertStatus(200);
        $responseName->assertSee('Bambang Pamungkas');
        $responseName->assertDontSee('Dewi Sartika');

        // Search by phone
        $responsePhone = $this->actingAs($admin)->get(route('admin.users.index', ['search' => '087711112222']));
        $responsePhone->assertStatus(200);
        $responsePhone->assertSee('Dewi Sartika');
        $responsePhone->assertDontSee('Bambang Pamungkas');
    }

    public function test_admin_can_store_new_user_successfully(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'nama_lengkap' => 'Rahmat Teknisi',
            'email' => 'rahmat@frc.test',
            'no_telepon' => '081233445566',
            'role' => 'Teknisi',
            'password' => 'secret1234',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'nama_lengkap' => 'Rahmat Teknisi',
            'email' => 'rahmat@frc.test',
            'role' => 'Teknisi',
            'is_active' => true,
        ]);

        $newUser = User::where('email', 'rahmat@frc.test')->first();
        $this->assertTrue(Hash::check('secret1234', $newUser->password));
    }

    public function test_store_validation_fails_for_invalid_data(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'nama_lengkap' => '',
            'email' => 'invalid-email',
            'no_telepon' => '',
            'role' => 'InvalidRole',
            'password' => 'short',
        ]);

        $response->assertSessionHasErrors(['nama_lengkap', 'email', 'no_telepon', 'role', 'password']);
    }

    public function test_admin_can_update_existing_user(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $user = User::factory()->create([
            'nama_lengkap' => 'Nama Lama',
            'email' => 'lama@frc.test',
            'role' => 'Pelapor',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $user->id), [
            'nama_lengkap' => 'Nama Baru',
            'email' => 'baru@frc.test',
            'no_telepon' => '081999888777',
            'role' => 'Teknisi',
            'password' => '', // optional password
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals('Nama Baru', $user->nama_lengkap);
        $this->assertEquals('baru@frc.test', $user->email);
        $this->assertEquals('Teknisi', $user->role);
    }

    public function test_admin_cannot_demote_their_own_account_role(): void
    {
        $admin = User::factory()->create([
            'role' => 'Admin',
            'nama_lengkap' => 'Admin Master',
            'email' => 'master@frc.test',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $admin->id), [
            'nama_lengkap' => 'Admin Master',
            'email' => 'master@frc.test',
            'no_telepon' => '08123456789',
            'role' => 'Pelapor', // attempt self-demotion
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $admin->refresh();
        $this->assertEquals('Admin', $admin->role);
    }

    public function test_admin_cannot_deactivate_their_own_account(): void
    {
        $admin = User::factory()->create([
            'role' => 'Admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.users.toggle-status', $admin->id));

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $admin->refresh();
        $this->assertTrue($admin->is_active);
    }

    public function test_admin_can_toggle_status_of_other_users(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $user = User::factory()->create([
            'role' => 'Teknisi',
            'is_active' => true,
        ]);

        // Toggle to inactive
        $response1 = $this->actingAs($admin)->patch(route('admin.users.toggle-status', $user->id));
        $response1->assertRedirect();
        $response1->assertSessionHas('success');
        $user->refresh();
        $this->assertFalse($user->is_active);

        // Toggle to active
        $response2 = $this->actingAs($admin)->patch(route('admin.users.toggle-status', $user->id));
        $response2->assertRedirect();
        $response2->assertSessionHas('success');
        $user->refresh();
        $this->assertTrue($user->is_active);
    }

    public function test_teknisi_workload_is_displayed_accurately(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $pelapor = User::factory()->create(['role' => 'Pelapor']);
        $teknisi = User::factory()->create(['role' => 'Teknisi', 'nama_lengkap' => 'Teknisi Sibuk']);

        $laporan = Laporan::factory()->create(['pelapor_id' => $pelapor->id, 'status' => 'Diproses']);
        Penugasan::create([
            'laporan_id' => $laporan->id,
            'teknisi_id' => $teknisi->id,
            'assigned_by' => $admin->id,
            'status_tugas' => 'Dikerjakan',
            'assigned_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.users.index'));
        $response->assertStatus(200);
        $response->assertSee('1 Tugas Aktif');
    }

    public function test_non_admin_cannot_access_admin_users_index(): void
    {
        $pelapor = User::factory()->create(['role' => 'Pelapor']);

        $response = $this->actingAs($pelapor)->get(route('admin.users.index'));
        $response->assertStatus(403);
    }
}
