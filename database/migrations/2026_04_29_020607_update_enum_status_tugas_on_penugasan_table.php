<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Menggunakan Raw SQL untuk mengubah ENUM agar lebih aman dan tidak membutuhkan library tambahan
        DB::statement("ALTER TABLE penugasan MODIFY COLUMN status_tugas ENUM('Ditugaskan', 'Dikerjakan', 'Tertunda', 'Selesai') DEFAULT 'Ditugaskan'");
    }

    public function down(): void
    {
        // Mengembalikan ke struktur awal jika di-rollback
        DB::statement("ALTER TABLE penugasan MODIFY COLUMN status_tugas ENUM('Ditugaskan', 'Dikerjakan', 'Tertunda') DEFAULT 'Ditugaskan'");
    }
};
