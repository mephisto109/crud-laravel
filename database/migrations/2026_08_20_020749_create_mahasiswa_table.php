<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id('id_mahasiswa');
            $table->string('nama', 100);
            $table->string('prodi', 50);
            $table->string('jk', 10);
            $table->string('telepon', 30);
            // Kolom text buat alamat, bisa nampung tulisan panjang
            $table->text('alamat');
            $table->string('email', 30);
            $table->string('foto', 100);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};