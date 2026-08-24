<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('akun', function (Blueprint $table) {
            // Primary key custom, bukan "id" default Laravel
            $table->id('id_akun');
            $table->string('nama', 100);
            $table->string('username', 100);
            $table->string('email', 50);
            $table->string('password', 100);
            $table->string('level', 2);
        });
    }

    public function down(): void
    {
        // Perintah buat "membatalkan" migration ini, hapus tabelnya
        Schema::dropIfExists('akun');
    }
};