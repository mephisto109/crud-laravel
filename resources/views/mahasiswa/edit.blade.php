@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1 class="m-0">Ubah Mahasiswa</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('mahasiswa.index') }}">Home</a></li>
                        <li class="breadcrumb-item active">Ubah Mahasiswa</li>
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
                        <div class="card-header"><h3 class="card-title">Form Ubah Mahasiswa</h3></div>
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

                            <form method="POST" action="{{ route('mahasiswa.update', $mahasiswa->id_mahasiswa) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="Nama" class="form-label">Nama Mahasiswa</label>
                                    <input type="text" class="form-control" id="Nama" name="Nama" value="{{ old('Nama', $mahasiswa->nama) }}">
                                </div>
                                <div class="mb-3">
                                    <label for="prodi" class="form-label">Program Studi</label>
                                    <select name="prodi" id="prodi" class="form-control">
                                        <option value="">-- Pilih Program Studi --</option>
                                        <option value="Teknik Informatika" {{ $mahasiswa->prodi == 'Teknik Informatika' ? 'selected' : '' }}>Teknik Informatika</option>
                                        <option value="Teknik Mesin" {{ $mahasiswa->prodi == 'Teknik Mesin' ? 'selected' : '' }}>Teknik Mesin</option>
                                        <option value="Teknik Listrik" {{ $mahasiswa->prodi == 'Teknik Listrik' ? 'selected' : '' }}>Teknik Listrik</option>
                                        {{-- Jaga-jaga kalau prodi lama nggak ada di 3 pilihan di atas --}}
                                        @if (!in_array($mahasiswa->prodi, ['Teknik Informatika', 'Teknik Mesin', 'Teknik Listrik']))
                                            <option value="{{ $mahasiswa->prodi }}" selected>{{ $mahasiswa->prodi }}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="jk" class="form-label">Jenis Kelamin</label>
                                    <select name="jk" id="jk" class="form-control">
                                        <option value="Laki-laki" {{ $mahasiswa->jk == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ $mahasiswa->jk == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="telepon" class="form-label">Telepon</label>
                                    <input type="text" class="form-control" id="telepon" name="telepon" value="{{ old('telepon', $mahasiswa->telepon) }}">
                                </div>
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea class="form-control" id="alamat" name="alamat">{{ old('alamat', $mahasiswa->alamat) }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $mahasiswa->email) }}">
                                </div>
                                <div class="mb-3">
                                    <label for="foto" class="form-label">Foto</label>
                                    <input type="file" class="form-control" id="foto" name="foto" onchange="previewImg()">
                                    <small class="text-muted">Kosongkan kalau nggak mau ganti foto</small>
                                    <br>
                                    <a href="{{ asset('assets/img/' . $mahasiswa->foto) }}" target="_blank">
                                        <img src="{{ asset('assets/img/' . $mahasiswa->foto) }}" alt="foto" class="img-thumbnail img-preview" width="100">
                                    </a>
                                </div>
                                <button type="submit" class="btn btn-primary float-end">Ubah</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    function previewImg() {
        const foto = document.querySelector('#foto');
        const imgPreview = document.querySelector('.img-preview');
        if (!foto.files[0]) return;
        const fileFoto = new FileReader();
        fileFoto.readAsDataURL(foto.files[0]);
        fileFoto.onload = function (e) { imgPreview.src = e.target.result; }
    }
</script>
@endpush
@endsection