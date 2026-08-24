@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Daftar Mahasiswa</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('mahasiswa.index') }}">Home</a></li>
                            <li class="breadcrumb-item active">Mahasiswa</li>
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

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Data mahasiswa</h3>
                            </div>
                            <div class="card-body">
                                <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary mb-3">
                                    <i class="fas fa-plus-circle"></i> Tambah
                                </a>
                                {{-- Tombol export excel/pdf nyusul di fitur berikutnya --}}
                                <a href="{{ route('mahasiswa.export.excel') }}" class="btn btn-success mb-3"><i
                                        class="fas fa-file-excel"></i> Download Excel</a>
                                <a href="{{ route('mahasiswa.export.pdf') }}" class="btn btn-danger mb-3"><i
                                        class="fas fa-file-pdf"></i> Download PDF</a>
                                <table class="table table-bordered table-striped" id="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Prodi</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Telepon</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; @endphp
                                        @forelse ($dataMahasiswa as $mahasiswa)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $mahasiswa->nama }}</td>
                                                <td>{{ $mahasiswa->prodi }}</td>
                                                <td>{{ $mahasiswa->jk }}</td>
                                                <td>{{ $mahasiswa->telepon }}</td>
                                                <td>
                                                    <a href="{{ route('mahasiswa.show', $mahasiswa->id_mahasiswa) }}"
                                                        class="btn btn-secondary btn-sm">Detail</a>
                                                    <a href="{{ route('mahasiswa.edit', $mahasiswa->id_mahasiswa) }}"
                                                        class="btn btn-success btn-sm">Edit</a>
                                                    <form action="{{ route('mahasiswa.destroy', $mahasiswa->id_mahasiswa) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Belum ada data mahasiswa</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                @if (count($dataMahasiswa) > 0)
                    <div class="row">
                        <div class="col-12 col-lg-5">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Distribusi Jenis Kelamin</h3>
                                </div>
                                <div class="card-body">
                                    <div style="position:relative; height:290px;"><canvas id="chartJkMahasiswa"></canvas></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-7">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Jumlah Mahasiswa per Program Studi</h3>
                                </div>
                                <div class="card-body">
                                    <div style="position:relative; height:290px;"><canvas id="chartProdiMahasiswa"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </section>
    </div>

    @if (count($dataMahasiswa) > 0)
        @push('scripts')
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
            <script>
                new Chart(document.getElementById('chartJkMahasiswa'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($chartJkLabels),
                        datasets: [{
                            data: @json($chartJkCounts),
                            backgroundColor: ['#007bff', '#e83e8c'],
                            borderColor: '#ffffff',
                            borderWidth: 2
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '60%' }
                });

                new Chart(document.getElementById('chartProdiMahasiswa'), {
                    type: 'bar',
                    data: {
                        labels: @json($chartProdiLabels),
                        datasets: [{ label: 'Jumlah Mahasiswa', data: @json($chartProdiValues), backgroundColor: '#17a2b8', borderRadius: 4 }]
                    },
                    options: {
                        indexAxis: 'y', responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
                    }
                });
            </script>
        @endpush
    @endif
@endsection