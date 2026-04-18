<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Induk
        Schema::create('utilitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petugas_id')->constrained('users')->onDelete('restrict');
            $table->enum('jenis_utilitas', ['AirBersih', 'AirHujan', 'MDP', 'SDP', 'Lift', 'AC', 'Lampu']);
            $table->string('periode', 7); // Format: YYYY-MM
            $table->timestamps();
        });

        // 2. Tabel Turunan: Air Bersih
        Schema::create('air_bersih', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary(); // Mencegah Auto-Increment
            $table->foreign('id')->references('id')->on('utilitas')->cascadeOnDelete();

            $table->date('tgl_awal');
            $table->date('tgl_akhir');
            $table->decimal('stand_awal', 10, 2);
            $table->decimal('stand_akhir', 10, 2);
            $table->decimal('konsumsi', 10, 2)->storedAs('stand_akhir - stand_awal');
        });

        // 3. Tabel Turunan: Air Hujan
        Schema::create('air_hujan', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->foreign('id')->references('id')->on('utilitas')->cascadeOnDelete();

            $table->date('tgl_awal');
            $table->date('tgl_akhir');
            $table->decimal('stand_awal', 10, 2);
            $table->decimal('stand_akhir', 10, 2);
            $table->decimal('konsumsi', 10, 2)->storedAs('stand_akhir - stand_awal');
        });

        // 4. Tabel Turunan: Listrik MDP
        Schema::create('listrik_mdp', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->foreign('id')->references('id')->on('utilitas')->cascadeOnDelete();

            $table->date('tgl_awal');
            $table->date('tgl_akhir');
            $table->decimal('stand_awal', 12, 2);
            $table->decimal('stand_akhir', 12, 2);
            $table->decimal('konsumsi', 12, 2)->storedAs('stand_akhir - stand_awal');
        });

        // 5. Tabel Turunan: Listrik SDP
        Schema::create('listrik_sdp', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->foreign('id')->references('id')->on('utilitas')->cascadeOnDelete();

            $table->date('tgl_awal');
            $table->date('tgl_akhir');
            $table->decimal('stand_awal_sdp1', 12, 2);
            $table->decimal('stand_awal_sdp2', 12, 2);
            $table->decimal('stand_akhir_sdp1', 12, 2);
            $table->decimal('stand_akhir_sdp2', 12, 2);
            $table->decimal('konsumsi_sdp1', 12, 2)->storedAs('stand_akhir_sdp1 - stand_awal_sdp1');
            $table->decimal('konsumsi_sdp2', 12, 2)->storedAs('stand_akhir_sdp2 - stand_awal_sdp2');
        });

        // 6. Tabel Turunan: Listrik Lift
        Schema::create('listrik_lift', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->foreign('id')->references('id')->on('utilitas')->cascadeOnDelete();

            $table->date('tgl_awal');
            $table->date('tgl_akhir');
            $table->decimal('stand_awal_g', 12, 2);
            $table->decimal('stand_awal_g2', 12, 2);
            $table->decimal('stand_akhir_g', 12, 2);
            $table->decimal('stand_akhir_g2', 12, 2);
            $table->decimal('konsumsi_g', 12, 2)->storedAs('stand_akhir_g - stand_awal_g');
            $table->decimal('konsumsi_g2', 12, 2)->storedAs('stand_akhir_g2 - stand_awal_g2');
            $table->decimal('konsumsi_total', 12, 2)->storedAs('(stand_akhir_g - stand_awal_g) + (stand_akhir_g2 - stand_awal_g2)');
        });

        // 7. Tabel Turunan: Listrik AC
        Schema::create('listrik_ac', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->foreign('id')->references('id')->on('utilitas')->cascadeOnDelete();

            $table->date('tgl_awal');
            $table->date('tgl_akhir');
            $table->decimal('stand_awal_l1', 12, 2);
            $table->decimal('stand_awal_l2', 12, 2);
            $table->decimal('stand_awal_l3', 12, 2);
            $table->decimal('stand_akhir_l1', 12, 2);
            $table->decimal('stand_akhir_l2', 12, 2);
            $table->decimal('stand_akhir_l3', 12, 2);
            $table->decimal('konsumsi_l1', 12, 2)->storedAs('stand_akhir_l1 - stand_awal_l1');
            $table->decimal('konsumsi_l2', 12, 2)->storedAs('stand_akhir_l2 - stand_awal_l2');
            $table->decimal('konsumsi_l3', 12, 2)->storedAs('stand_akhir_l3 - stand_awal_l3');
        });

        // 8. Tabel Turunan: Listrik Lampu (Menggunakan struktur AC)
        Schema::create('listrik_lampu', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->foreign('id')->references('id')->on('utilitas')->cascadeOnDelete();

            $table->date('tgl_awal');
            $table->date('tgl_akhir');
            $table->decimal('stand_awal_l1', 12, 2);
            $table->decimal('stand_awal_l2', 12, 2);
            $table->decimal('stand_awal_l3', 12, 2);
            $table->decimal('stand_akhir_l1', 12, 2);
            $table->decimal('stand_akhir_l2', 12, 2);
            $table->decimal('stand_akhir_l3', 12, 2);
            $table->decimal('konsumsi_l1', 12, 2)->storedAs('stand_akhir_l1 - stand_awal_l1');
            $table->decimal('konsumsi_l2', 12, 2)->storedAs('stand_akhir_l2 - stand_awal_l2');
            $table->decimal('konsumsi_l3', 12, 2)->storedAs('stand_akhir_l3 - stand_awal_l3');
        });
    }

    // method down() isinya reverse / Schema::dropIfExists() dari bawah ke atas...
};
