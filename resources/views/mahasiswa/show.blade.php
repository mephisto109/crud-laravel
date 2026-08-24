@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1 class="m-0">Data {{ $mahasiswa->nama }}</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('mahasiswa.index') }}">Home</a></li>
                        <li class="breadcrumb-item active">Detail Mahasiswa</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h3 class="card-title">Detail mahasiswa</h3></div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <tr><th>Nama</th><td>: {{ $mahasiswa->nama }}</td></tr>
                                <tr><th>Prodi</th><td>: {{ $mahasiswa->prodi }}</td></tr>
                                <tr><th>Jenis Kelamin</th><td>: {{ $mahasiswa->jk }}</td></tr>
                                <tr><th>Telepon</th><td>: {{ $mahasiswa->telepon }}</td></tr>
                                <tr><th>Alamat</th><td>: {{ $mahasiswa->alamat }}</td></tr>
                                <tr><th>Email</th><td>: {{ $mahasiswa->email }}</td></tr>
                                <tr>
                                    <th>Foto</th>
                                    <td>
                                        <a href="{{ asset('assets/img/' . $mahasiswa->foto) }}" target="_blank">
                                            <img src="{{ asset('assets/img/' . $mahasiswa->foto) }}" alt="Foto {{ $mahasiswa->nama }}" width="100">
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary float-end">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection