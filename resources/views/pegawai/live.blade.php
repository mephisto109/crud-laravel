{{-- Ini partial view: nggak pakai layout, cuma potongan <tr> doang buat diinject via AJAX --}}
@php $no = 1; @endphp
@foreach ($dataPegawai as $pegawai)
    <tr>
        <td>{{ $no++ }}</td>
        <td>{{ $pegawai->nama }}</td>
        <td>{{ $pegawai->jabatan }}</td>
        <td>{{ $pegawai->email }}</td>
        <td>{{ $pegawai->telepon }}</td>
        <td>{{ $pegawai->alamat }}</td>
    </tr>
@endforeach