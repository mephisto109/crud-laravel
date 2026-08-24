<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id('id_pegawai');
            $table->string('nama', 100);
            $table->string('jabatan', 100);
            $table->string('email', 100);
            $table->string('telepon', 100);
            $table->text('alamat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};