<?php

namespace Database\Factories;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LaporanFactory extends Factory
{
    protected $model = Laporan::class;

    public function definition()
    {
        // Mencari user dengan role 'Pelapor' secara acak. Jika tidak ada, buat 1 user pelapor baru.
        $pelapor = User::where('role', 'Pelapor')->inRandomOrder()->first()
            ?? User::factory()->create(['role' => 'Pelapor']);

        return [
            'pelapor_id' => $pelapor->id,
            'judul' => $this->faker->randomElement(['AC Rusak', 'Lampu Mati', 'Kran Air Bocor', 'Internet Down', 'Pintu Macet']) . ' di ' . $this->faker->word(),
            'lokasi' => 'FRC (Field Research Center, Sekolah Vokasi UGM) Ruang ' . $this->faker->numberBetween(101, 305),
            'deskripsi' => $this->faker->paragraph(2),
            'status' => $this->faker->randomElement(['Baru', 'Diproses', 'Selesai', 'Ditolak']),
            'foto_sebelum' => null,
            'created_at' => $this->faker->dateTimeBetween('-2 months', 'now'),
        ];
    }
}
