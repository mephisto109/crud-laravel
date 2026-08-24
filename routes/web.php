<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PegawaiController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth:akun')->group(function () {
    Route::get('/', [BarangController::class, 'index'])->name('home');

    Route::middleware('level:1,2')->group(function () {
        Route::resource('barang', BarangController::class)->except(['show']);
    });

    Route::middleware('level:1,3')->group(function () {
        Route::resource('mahasiswa', MahasiswaController::class);
        Route::get('/mahasiswa-export/excel', [MahasiswaController::class, 'exportExcel'])->name('mahasiswa.export.excel');
        Route::get('/mahasiswa-export/pdf', [MahasiswaController::class, 'exportPdf'])->name('mahasiswa.export.pdf');
    });

    Route::middleware('level:1,2,3')->group(function () {
        Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
        Route::get('/pegawai/live', [PegawaiController::class, 'live'])->name('pegawai.live');
    });

    Route::get('/akun', [AkunController::class, 'index'])->name('akun.index');
    Route::post('/akun', [AkunController::class, 'store'])->name('akun.store');
    Route::put('/akun/{id}', [AkunController::class, 'update'])->name('akun.update');
    Route::delete('/akun/{id}', [AkunController::class, 'destroy'])->name('akun.destroy');
});