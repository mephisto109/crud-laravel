<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    public function index()
    {
        $userLogin = Auth::guard('akun')->user();

        // Admin liat semua akun, selain admin cuma liat akunnya sendiri
        if ($userLogin->level == '1') {
            $dataAkun = Akun::orderBy('id_akun', 'desc')->get();
        } else {
            $dataAkun = Akun::where('id_akun', $userLogin->id_akun)->get();
        }

        $chartLevelLabels = ['Admin', 'Operator Barang', 'Operator Mahasiswa'];
        $chartLevelCounts = [0, 0, 0];

        if ($userLogin->level == '1') {
            foreach ($dataAkun as $akun) {
                if ($akun->level == '1') $chartLevelCounts[0]++;
                elseif ($akun->level == '2') $chartLevelCounts[1]++;
                elseif ($akun->level == '3') $chartLevelCounts[2]++;
            }
        }

        return view('akun.index', [
            'title' => 'Data Akun',
            'dataAkun' => $dataAkun,
            'userLogin' => $userLogin,
            'chartLevelLabels' => $chartLevelLabels,
            'chartLevelCounts' => $chartLevelCounts,
        ]);
    }

    public function store(Request $request)
    {
        // Cuma admin yang boleh nambah akun baru
        if (Auth::guard('akun')->user()->level != '1') {
            abort(403, 'Kamu nggak punya akses buat nambah akun.');
        }

        $validasi = $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:100|unique:akun,username',
            'email' => 'required|email|max:50',
            'password' => 'required|string|min:6',
            'level' => 'required|in:1,2,3',
        ]);

        Akun::create([
            'nama' => $validasi['nama'],
            'username' => $validasi['username'],
            'email' => $validasi['email'],
            // Password wajib di-hash, jangan disimpan mentah
            'password' => Hash::make($validasi['password']),
            'level' => $validasi['level'],
        ]);

        return redirect()->route('akun.index')->with('sukses', 'Data Akun berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $userLogin = Auth::guard('akun')->user();
        $akun = Akun::findOrFail($id);

        // Non-admin cuma boleh update akunnya sendiri, bukan punya orang lain
        if ($userLogin->level != '1' && $userLogin->id_akun != $akun->id_akun) {
            abort(403, 'Kamu nggak punya akses buat ubah akun ini.');
        }

        $validasi = $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:100|unique:akun,username,' . $akun->id_akun . ',id_akun',
            'email' => 'required|email|max:50',
            // Password nullable, boleh dikosongin kalau nggak mau ganti
            'password' => 'nullable|string|min:6',
        ]);

        $dataUpdate = [
            'nama' => $validasi['nama'],
            'username' => $validasi['username'],
            'email' => $validasi['email'],
        ];

        if (!empty($validasi['password'])) {
            $dataUpdate['password'] = Hash::make($validasi['password']);
        }

        // Cuma admin yang boleh ganti level punya siapapun
        if ($userLogin->level == '1') {
            $dataUpdate['level'] = $request->input('level', $akun->level);
        }

        $akun->update($dataUpdate);

        return redirect()->route('akun.index')->with('sukses', 'Data Akun berhasil diubah!');
    }

    public function destroy($id)
    {
        // Cuma admin yang boleh hapus akun
        if (Auth::guard('akun')->user()->level != '1') {
            abort(403, 'Kamu nggak punya akses buat hapus akun.');
        }

        Akun::destroy($id);

        return redirect()->route('akun.index')->with('sukses', 'Data Akun berhasil dihapus!');
    }
}