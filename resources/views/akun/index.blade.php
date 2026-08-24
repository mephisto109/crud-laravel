@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1 class="m-0">Data Akun</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Data Akun</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            @if (session('sukses'))
                <div class="alert alert-success">{{ session('sukses') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h3 class="card-title">Daftar akun pengguna</h3></div>
                        <div class="card-body">

                            @if ($userLogin->level == '1')
                                <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambah">
                                    <i class="fas fa-plus"></i> Tambah Akun
                                </button>
                            @endif

                            <table class="table table-bordered table-striped" id="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Password</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($dataAkun as $akun)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $akun->nama }}</td>
                                            <td>{{ $akun->username }}</td>
                                            <td>{{ $akun->email }}</td>
                                            <td>Password Ter-enkripsi</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalUbah{{ $akun->id_akun }}">
                                                    Edit
                                                </button>
                                                @if ($userLogin->level == '1')
                                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalHapus{{ $akun->id_akun }}">
                                                        Hapus
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @if ($userLogin->level == '1')
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header"><h3 class="card-title">Distribusi Akun per Level</h3></div>
                        <div class="card-body">
                            <div style="position:relative; height:280px;"><canvas id="chartLevelAkun"></canvas></div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </section>
</div>

{{-- Modal Tambah, cuma dirender kalau admin --}}
@if ($userLogin->level == '1')
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Akun</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('akun.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-2">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-2">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-2">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="level" class="form-label">Level</label>
                        <select class="form-control" id="level" name="level" required>
                            <option value="">Pilih Level</option>
                            <option value="1">Admin</option>
                            <option value="2">Operator Barang</option>
                            <option value="3">Operator Mahasiswa</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- Modal Hapus, satu per akun, cuma buat admin --}}
@if ($userLogin->level == '1')
    @foreach ($dataAkun as $akun)
    <div class="modal fade" id="modalHapus{{ $akun->id_akun }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Hapus Akun</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus akun {{ $akun->nama }}?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form action="{{ route('akun.destroy', $akun->id_akun) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif

{{-- Modal Ubah, satu per akun (admin bisa ubah semua, non-admin cuma liat akunnya sendiri di $dataAkun) --}}
@foreach ($dataAkun as $akun)
<div class="modal fade" id="modalUbah{{ $akun->id_akun }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Ubah Akun {{ $akun->nama }}</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('akun.update', $akun->id_akun) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control" name="nama" value="{{ $akun->nama }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" value="{{ $akun->username }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ $akun->email }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Password <small>(Kosongkan jika tidak ingin mengubah)</small></label>
                        <input type="password" class="form-control" name="password">
                    </div>

                    @if ($userLogin->level == '1')
                        <div class="mb-3">
                            <label class="form-label">Level</label>
                            <select name="level" class="form-control" required>
                                <option value="1" {{ $akun->level == '1' ? 'selected' : '' }}>Admin</option>
                                <option value="2" {{ $akun->level == '2' ? 'selected' : '' }}>Operator Barang</option>
                                <option value="3" {{ $akun->level == '3' ? 'selected' : '' }}>Operator Mahasiswa</option>
                            </select>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-primary">Ubah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@if ($userLogin->level == '1')
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('chartLevelAkun'), {
        type: 'doughnut',
        data: {
            labels: @json($chartLevelLabels),
            datasets: [{
                data: @json($chartLevelCounts),
                backgroundColor: ['#007bff', '#28a745', '#ffc107'],
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, cutout: '60%', plugins: { legend: { position: 'bottom' } } }
    });
</script>
@endpush
@endif
@endsection