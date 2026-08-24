@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1 class="m-0">Data Barang</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Home</a></li>
                        <li class="breadcrumb-item active">Data Barang</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            {{-- Pesan sukses muncul abis tambah/ubah/hapus data --}}
            @if (session('sukses'))
                <div class="alert alert-success">{{ session('sukses') }}</div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm float-right">
                                <i class="fas fa-plus-circle"></i> Tambah Data
                            </a>
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalFilter">
                                <i class="fas fa-search"></i> Filter Tanggal
                            </button>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped" id="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                        <th>Barcode</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @forelse ($dataBarang as $barang)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $barang->nama }}</td>
                                            <td>{{ $barang->jumlah }}</td>
                                            <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <img alt="barcode" src="{{ url('barcode.php?codetype=code128&size=15&text=' . $barang->barcode . '&print=true') }}">
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($barang->tanggal)->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('barang.edit', $barang->id_barang) }}" class="btn btn-success btn-sm">Edit</a>
                                                <form action="{{ route('barang.destroy', $barang->id_barang) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin mau hapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Belum ada data barang</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            {{-- Pagination manual, cuma dipakai kalau bukan mode filter --}}
                            @if ($jumlahHalaman > 1)
                                <nav>
                                    <ul class="pagination justify-content-end">
                                        @if ($halamanAktif > 1)
                                            <li class="page-item">
                                                <a class="page-link" href="?halaman={{ $halamanAktif - 1 }}">&laquo;</a>
                                            </li>
                                        @endif

                                        @for ($i = 1; $i <= $jumlahHalaman; $i++)
                                            <li class="page-item {{ $i == $halamanAktif ? 'active' : '' }}">
                                                <a class="page-link" href="?halaman={{ $i }}">{{ $i }}</a>
                                            </li>
                                        @endfor

                                        @if ($halamanAktif < $jumlahHalaman)
                                            <li class="page-item">
                                                <a class="page-link" href="?halaman={{ $halamanAktif + 1 }}">&raquo;</a>
                                            </li>
                                        @endif
                                    </ul>
                                </nav>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grafik perbandingan, cuma muncul kalau ada datanya --}}
            @if (count($chartNamaBarang) > 0)
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header"><h3 class="card-title">Perbandingan Jumlah Barang</h3></div>
                        <div class="card-body">
                            <div style="position:relative; height:300px;"><canvas id="chartJumlahBarang"></canvas></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header"><h3 class="card-title">Perbandingan Harga Barang</h3></div>
                        <div class="card-body">
                            <div style="position:relative; height:300px;"><canvas id="chartHargaBarang"></canvas></div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </section>
</div>

{{-- Modal Filter Tanggal --}}
<div class="modal fade" id="modalFilter" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title">Filter Data</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('barang.index') }}" method="GET">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tgl_awal">Tanggal Awal</label>
                        <input type="date" name="tgl_awal" id="tgl_awal" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="tgl_akhir">Tanggal Akhir</label>
                        <input type="date" name="tgl_akhir" id="tgl_akhir" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('barang.index') }}" class="btn btn-outline-danger btn-sm mr-auto">Reset</a>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" name="filter" value="1" class="btn btn-success btn-sm">Terapkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if (count($chartNamaBarang) > 0)
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
    // Data grafik dikirim dari Controller, tinggal dirender di sini
    const namaBarang = @json($chartNamaBarang);
    const jumlahBarang = @json($chartJumlahBarang);
    const hargaBarang = @json($chartHargaBarang);

    new Chart(document.getElementById('chartJumlahBarang'), {
        type: 'bar',
        data: { labels: namaBarang, datasets: [{ label: 'Jumlah', data: jumlahBarang, backgroundColor: '#007bff' }] },
        options: { responsive: true, maintainAspectRatio: false }
    });

    new Chart(document.getElementById('chartHargaBarang'), {
        type: 'bar',
        data: { labels: namaBarang, datasets: [{ label: 'Harga (Rp)', data: hargaBarang, backgroundColor: '#28a745' }] },
        options: { responsive: true, maintainAspectRatio: false }
    });
</script>
@endpush
@endif
@endsection