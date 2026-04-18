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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // Bawaan Laravel, bisa diabaikan atau dihapus jika murni pakai nama_lengkap
            $table->string('nama_lengkap');
            $table->string('no_telepon', 20);
            $table->enum('role', ['Pelapor', 'Admin', 'Teknisi', 'KepalaFRC'])->default('Pelapor');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
