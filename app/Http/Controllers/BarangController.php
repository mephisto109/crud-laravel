<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('filter')) {
            // Mode filter tanggal, tanpa pagination (sama kayak versi native)
            $tglAwal = $request->input('tgl_awal') . ' 00:00:00';
            $tglAkhir = $request->input('tgl_akhir') . ' 23:59:59';

            $dataBarang = Barang::whereBetween('tanggal', [$tglAwal, $tglAkhir])
                ->orderBy('id_barang', 'desc')
                ->get();

            $halamanAktif = 1;
            $jumlahHalaman = 1;
        } else {
            // Mode normal, pakai pagination bawaan Laravel
            $halamanAktif = (int) $request->input('halaman', 1);

            $paginator = Barang::orderBy('id_barang', 'desc')
                ->paginate(10, ['*'], 'halaman', $halamanAktif);

            $dataBarang = $paginator->items();
            $jumlahHalaman = $paginator->lastPage();
        }

        // Siapin data buat grafik Chart.js dari data barang yang lagi tampil
        $chartNamaBarang = [];
        $chartJumlahBarang = [];
        $chartHargaBarang = [];
        foreach ($dataBarang as $item) {
            $chartNamaBarang[] = $item->nama;
            $chartJumlahBarang[] = (int) $item->jumlah;
            $chartHargaBarang[] = (float) $item->harga;
        }

        return view('barang.index', [
            'title' => 'Data Barang',
            'dataBarang' => $dataBarang,
            'halamanAktif' => $halamanAktif,
            'jumlahHalaman' => $jumlahHalaman,
            'chartNamaBarang' => $chartNamaBarang,
            'chartJumlahBarang' => $chartJumlahBarang,
            'chartHargaBarang' => $chartHargaBarang,
        ]);
    }

    public function create()
    {
        return view('barang.create', ['title' => 'Tambah Barang']);
    }

    public function store(Request $request)
    {
        $validasi = $request->validate([
            'Nama' => 'required|string|max:50',
            'Jumlah' => 'required|integer',
            'Harga' => 'required|integer',
        ]);

        // Barcode dibikin acak 6 digit, persis kayak versi native
        Barang::create([
            'nama' => $validasi['Nama'],
            'jumlah' => $validasi['Jumlah'],
            'harga' => $validasi['Harga'],
            'barcode' => rand(100000, 999999),
            'tanggal' => now(),
        ]);

        return redirect()->route('barang.index')->with('sukses', 'Data Barang berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);

        return view('barang.edit', [
            'title' => 'Ubah Barang',
            'barang' => $barang,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validasi = $request->validate([
            'Nama' => 'required|string|max:50',
            'Jumlah' => 'required|integer',
            'Harga' => 'required|integer',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update([
            'nama' => $validasi['Nama'],
            'jumlah' => $validasi['Jumlah'],
            'harga' => $validasi['Harga'],
        ]);

        return redirect()->route('barang.index')->with('sukses', 'Data Barang berhasil diubah!');
    }

    public function destroy($id)
    {
        Barang::destroy($id);

        return redirect()->route('barang.index')->with('sukses', 'Data Barang berhasil dihapus!');
    }
}