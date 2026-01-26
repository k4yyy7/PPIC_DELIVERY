@extends('dashboard.layouts.index')

@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Chart Overview</h3>
            <h6 class="op-7 mb-2">Distribusi status laporan keseluruhan</h6>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Pie Chart - Distribusi Status</h4>
                </div>
                <div class="card-body">
                    <div id="pieChart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Summary Statistics</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $summary['OK'] ?? 0 }}</h3>
                                    <p class="mb-0">Status OK</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $summary['NG'] ?? 0 }}</h3>
                                    <p class="mb-0">Status NG</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card bg-secondary text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $summary['UNKNOWN'] ?? 0 }}</h3>
                                    <p class="mb-0">Status ? (Belum Dicek)</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $totalReports }}</h3>
                                    <p class="mb-0">Total Laporan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
var pieOptions = {
    series: [
        {{ $summary['OK'] ?? 0 }},
        {{ $summary['NG'] ?? 0 }},
        {{ $summary['UNKNOWN'] ?? 0 }}
    ],
    chart: {
        type: 'pie',
        height: 400
    },
    labels: ['OK', 'NG', '? (Belum Dicek)'],
    colors: ['#31ce36', '#dc3545', '#6c757d'],
    dataLabels: {
        enabled: true,
        formatter: function(val) {
            return val.toFixed(1) + '%'
        }
    },
    legend: {
        position: 'bottom'
    }
};

var pieChart = new ApexCharts(document.querySelector("#pieChart"), pieOptions);
pieChart.render();
</script>
@endsection
