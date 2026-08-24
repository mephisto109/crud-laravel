@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1 class="m-0">Daftar Pegawai</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('pegawai.index') }}">Home</a></li>
                        <li class="breadcrumb-item active">Pegawai</li>
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
                        <div class="card-header">
                            <h3 class="card-title">Data Pegawai</h3>
                            <div class="card-tools float-right">
                                <a href="#" class="btn btn-primary btn-sm mr-2 disabled">
                                    <i class="fas fa-plus-circle"></i> Tambah Data
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- Search box manual, gantiin fitur search DataTables yang nggak kompatibel sama realtime --}}
                            <div class="mb-3">
                                <input type="text" id="searchPegawai" class="form-control" placeholder="Cari nama, jabatan, email, atau alamat...">
                            </div>

                            {{-- id sengaja BUKAN "table", biar nggak ke-detect sama script DataTables global di layout --}}
                            <table id="tabelPegawaiRealtime" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>Email</th>
                                        <th>Telepon</th>
                                        <th>Alamat</th>
                                    </tr>
                                </thead>
                                <tbody id="live_data"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="chartPegawaiWrapper" style="display:none;">
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header"><h3 class="card-title">Distribusi Pegawai per Jabatan</h3></div>
                        <div class="card-body">
                            <div style="position:relative; height:290px;"><canvas id="chartJabatanPegawai"></canvas></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
    var chartJabatanPegawaiInstance = null;

    $(document).ready(function () {
        setInterval(function () {
            getPegawai();
        }, 200);
    });

    function getPegawai() {
        $.ajax({
            url: "{{ route('pegawai.live') }}",
            type: "GET",
            success: function (response) {
                $('#live_data').html(response);
                // Terapkan ulang filter search tiap kali data baru masuk
                filterPegawaiTable();
                updateChartJabatan();
            }
        });
    }

    // Search manual: sembunyiin baris yang teksnya nggak cocok sama keyword
    function filterPegawaiTable() {
        var keyword = $('#searchPegawai').val().toLowerCase();
        $('#live_data tr').each(function () {
            var teksBaris = $(this).text().toLowerCase();
            $(this).toggle(teksBaris.indexOf(keyword) !== -1);
        });
    }

    $(document).on('keyup', '#searchPegawai', function () {
        filterPegawaiTable();
    });

    function updateChartJabatan() {
        if (typeof Chart === 'undefined') return;

        var counts = {};
        $('#live_data tr:visible').each(function () {
            var jabatan = $(this).find('td').eq(2).text().trim();
            if (jabatan) counts[jabatan] = (counts[jabatan] || 0) + 1;
        });

        var labels = Object.keys(counts);
        var values = Object.values(counts);
        var wrapper = document.getElementById('chartPegawaiWrapper');
        var ctx = document.getElementById('chartJabatanPegawai');

        if (labels.length === 0) {
            if (wrapper) wrapper.style.display = 'none';
            return;
        }
        if (wrapper) wrapper.style.display = '';

        var palette = ['#007bff', '#28a745', '#ffc107', '#17a2b8', '#e83e8c', '#6f42c1', '#fd7e14'];

        if (chartJabatanPegawaiInstance) {
            chartJabatanPegawaiInstance.data.labels = labels;
            chartJabatanPegawaiInstance.data.datasets[0].data = values;
            chartJabatanPegawaiInstance.update();
        } else if (ctx) {
            chartJabatanPegawaiInstance = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{ data: values, backgroundColor: palette, borderColor: '#ffffff', borderWidth: 2 }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }
    }
</script>
@endpush
@endsection