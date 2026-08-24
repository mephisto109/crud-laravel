<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id('id_barang');
            $table->string('nama', 50);
            $table->integer('jumlah');
            $table->integer('harga');
            $table->string('barcode', 15);
            // Kolom tanggal otomatis keisi waktu sekarang saat data dibuat
            $table->timestamp('tanggal')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};