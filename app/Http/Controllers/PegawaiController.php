<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;

class PegawaiController extends Controller
{
    public function index()
    {
        return view('pegawai.index', ['title' => 'Daftar Pegawai']);
    }

    // Endpoint yang dipanggil AJAX tiap 200ms, balikin potongan HTML tabel doang
    public function live()
    {
        $dataPegawai = Pegawai::orderBy('id_pegawai', 'desc')->get();

        return view('pegawai.live', ['dataPegawai' => $dataPegawai]);
    }
}