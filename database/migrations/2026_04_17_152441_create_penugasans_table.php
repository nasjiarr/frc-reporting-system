<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penugasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->unique()->nullable()->constrained('laporan')->nullOnDelete();
            $table->foreignId('teknisi_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('assigned_by')->constrained('users')->onDelete('restrict');
            $table->text('instruksi')->nullable();
            $table->enum('status_tugas', ['Ditugaskan', 'Dikerjakan', 'Tertunda'])->default('Ditugaskan');
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamps(); // Opsional tapi direkomendasikan untuk track update data
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penugasans');
    }
};
