@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1 class="m-0">Ubah Barang</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Home</a></li>
                        <li class="breadcrumb-item active">Ubah Barang</li>
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
                        <div class="card-header"><h3 class="card-title">Form Ubah Barang</h3></div>
                        <div class="card-body">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('barang.update', $barang->id_barang) }}">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="Nama">Nama Barang</label>
                                    <input type="text" class="form-control" id="Nama" name="Nama" value="{{ old('Nama', $barang->nama) }}">
                                </div>
                                <div class="form-group">
                                    <label for="Jumlah">Jumlah</label>
                                    <input type="number" class="form-control" id="Jumlah" name="Jumlah" value="{{ old('Jumlah', $barang->jumlah) }}">
                                </div>
                                <div class="form-group">
                                    <label for="Harga">Harga Barang</label>
                                    <input type="number" class="form-control" id="Harga" name="Harga" value="{{ old('Harga', $barang->harga) }}">
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
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