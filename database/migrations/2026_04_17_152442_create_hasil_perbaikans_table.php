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
        Schema::create('hasil_perbaikan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penugasan_id')->unique()->nullable()->constrained('penugasan')->nullOnDelete();
            $table->text('tindakan');
            $table->text('material_digunakan')->nullable();
            $table->string('foto_sebelum')->nullable();
            $table->string('foto_sesudah')->nullable();
            $table->timestamp('selesai_pada')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_perbaikans');
    }
};
