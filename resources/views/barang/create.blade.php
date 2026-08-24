@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1 class="m-0">Tambah Barang</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Home</a></li>
                        <li class="breadcrumb-item active">Tambah Barang</li>
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
                        <div class="card-header"><h3 class="card-title">Form Tambah Barang</h3></div>
                        <div class="card-body">

                            {{-- Kalau ada input yang salah, error-nya tampil di sini --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('barang.store') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="Nama">Nama Barang</label>
                                    <input type="text" class="form-control" id="Nama" name="Nama" value="{{ old('Nama') }}" placeholder="Nama Barang..">
                                </div>
                                <div class="form-group">
                                    <label for="Jumlah">Jumlah</label>
                                    <input type="number" class="form-control" id="Jumlah" name="Jumlah" value="{{ old('Jumlah') }}" placeholder="Jumlah Barang..">
                                </div>
                                <div class="form-group">
                                    <label for="Harga">Harga Barang</label>
                                    <input type="number" class="form-control" id="Harga" name="Harga" value="{{ old('Harga') }}" placeholder="Harga Barang..">
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection