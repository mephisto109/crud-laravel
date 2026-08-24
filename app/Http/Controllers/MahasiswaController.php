<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index()
    {
        $dataMahasiswa = Mahasiswa::orderBy('id_mahasiswa', 'desc')->get();

        $chartJkLabels = ['Laki-laki', 'Perempuan'];
        $chartJkCounts = [0, 0];
        $chartProdiCounts = [];

        foreach ($dataMahasiswa as $mhs) {
            $jk = strtoupper(trim($mhs->jk));
            if ($jk == 'L' || $jk == 'LAKI-LAKI') {
                $chartJkCounts[0]++;
            } elseif ($jk == 'P' || $jk == 'PEREMPUAN') {
                $chartJkCounts[1]++;
            }
            $chartProdiCounts[$mhs->prodi] = ($chartProdiCounts[$mhs->prodi] ?? 0) + 1;
        }

        return view('mahasiswa.index', [
            'title' => 'Daftar Mahasiswa',
            'dataMahasiswa' => $dataMahasiswa,
            'chartJkLabels' => $chartJkLabels,
            'chartJkCounts' => $chartJkCounts,
            'chartProdiLabels' => array_keys($chartProdiCounts),
            'chartProdiValues' => array_values($chartProdiCounts),
        ]);
    }

    public function create()
    {
        return view('mahasiswa.create', ['title' => 'Tambah Mahasiswa']);
    }

    public function store(Request $request)
    {
        $validasi = $request->validate([
            'Nama' => 'required|string|max:100',
            'prodi' => 'required|string',
            'jk' => 'required|string',
            'telepon' => 'required|string|max:30',
            'alamat' => 'required|string',
            'email' => 'required|email|max:30',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2000',
        ]);

        $fileFoto = $request->file('foto');
        $namaFileFoto = uniqid() . '.' . $fileFoto->getClientOriginalExtension();
        $tujuanFoto = public_path('assets/img/' . $namaFileFoto);

        copy($fileFoto->getPathname(), $tujuanFoto);
        @unlink($fileFoto->getPathname());

        Mahasiswa::create([
            'nama' => $validasi['Nama'],
            'prodi' => $validasi['prodi'],
            'jk' => $validasi['jk'],
            'telepon' => $validasi['telepon'],
            'alamat' => $validasi['alamat'],
            'email' => $validasi['email'],
            'foto' => $namaFileFoto,
        ]);

        return redirect()->route('mahasiswa.index')->with('sukses', 'Data Mahasiswa berhasil ditambahkan!');
    }

    public function show($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        return view('mahasiswa.show', [
            'title' => 'Detail Mahasiswa',
            'mahasiswa' => $mahasiswa,
        ]);
    }

    public function edit($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        return view('mahasiswa.edit', [
            'title' => 'Ubah Mahasiswa',
            'mahasiswa' => $mahasiswa,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validasi = $request->validate([
            'Nama' => 'required|string|max:100',
            'prodi' => 'required|string',
            'jk' => 'required|string',
            'telepon' => 'required|string|max:30',
            'alamat' => 'required|string',
            'email' => 'required|email|max:30',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2000',
        ]);

        $mahasiswa = Mahasiswa::findOrFail($id);

        if ($request->hasFile('foto')) {
            $fileFoto = $request->file('foto');
            $namaFileFoto = uniqid() . '.' . $fileFoto->getClientOriginalExtension();
            $tujuanFoto = public_path('assets/img/' . $namaFileFoto);

            copy($fileFoto->getPathname(), $tujuanFoto);
            @unlink($fileFoto->getPathname());
        } else {
            $namaFileFoto = $mahasiswa->foto;
        }

        $mahasiswa->update([
            'nama' => $validasi['Nama'],
            'prodi' => $validasi['prodi'],
            'jk' => $validasi['jk'],
            'telepon' => $validasi['telepon'],
            'alamat' => $validasi['alamat'],
            'email' => $validasi['email'],
            'foto' => $namaFileFoto,
        ]);

        return redirect()->route('mahasiswa.index')->with('sukses', 'Data Mahasiswa berhasil diubah!');
    }

    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $pathFoto = public_path('assets/img/' . $mahasiswa->foto);
        if (file_exists($pathFoto)) {
            unlink($pathFoto);
        }

        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')->with('sukses', 'Data Mahasiswa berhasil dihapus!');
    }

    public function exportExcel()
    {
        $dataMahasiswa = Mahasiswa::all();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A2', 'No');
        $sheet->setCellValue('B2', 'Nama');
        $sheet->setCellValue('C2', 'Program Studi');
        $sheet->setCellValue('D2', 'Jenis Kelamin');
        $sheet->setCellValue('E2', 'Telepon');
        $sheet->setCellValue('F2', 'Email');
        $sheet->setCellValue('G2', 'Foto');

        $no = 1;
        $baris = 3;
        foreach ($dataMahasiswa as $mahasiswa) {
            $sheet->setCellValue('A' . $baris, $no++);
            $sheet->setCellValue('B' . $baris, $mahasiswa->nama);
            $sheet->setCellValue('C' . $baris, $mahasiswa->prodi);
            $sheet->setCellValue('D' . $baris, $mahasiswa->jk);
            $sheet->setCellValue('E' . $baris, $mahasiswa->telepon);
            $sheet->setCellValue('F' . $baris, $mahasiswa->email);
            $sheet->setCellValue('G' . $baris, url('assets/img/' . $mahasiswa->foto));
            $baris++;
        }

        foreach (range('A', 'G') as $kolom) {
            $sheet->getColumnDimension($kolom)->setAutoSize(true);
        }

        $sheet->getStyle('A2:G' . ($baris - 1))->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
        ]);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'Laporan Data Mahasiswa.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function exportPdf()
    {
        $dataMahasiswa = Mahasiswa::all();

        $content = '<style type="text/css">.gambar { width: 50px; }</style>';
        $content .= '<page>
            <table border="1" align="center">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Program Studi</th>
                    <th>Jenis Kelamin</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th>Foto</th>
                </tr>';

        $no = 1;
        foreach ($dataMahasiswa as $mahasiswa) {
            $pathFoto = public_path('assets/img/' . $mahasiswa->foto);
            $content .= '<tr>
                <td>' . $no++ . '</td>
                <td>' . e($mahasiswa->nama) . '</td>
                <td>' . e($mahasiswa->prodi) . '</td>
                <td>' . e($mahasiswa->jk) . '</td>
                <td>' . e($mahasiswa->telepon) . '</td>
                <td>' . e($mahasiswa->email) . '</td>
                <td><img src="' . $pathFoto . '" class="gambar"></td>
            </tr>';
        }

        $content .= '</table></page>';

        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf();
        $html2pdf->writeHTML($content);

        return response($html2pdf->output('Laporan-mahasiswa.pdf', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="Laporan-mahasiswa.pdf"',
        ]);
    }
}