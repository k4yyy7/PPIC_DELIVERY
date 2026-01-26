@extends('dashboard.layouts.index')

@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Chart Laporan Harian</h3>
            <h6 class="op-7 mb-2">Trend laporan per tanggal dalam sebulan</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <form method="GET" action="{{ route('admin.reports.chart') }}" class="d-flex gap-2 align-items-end">
                <div style="flex: 0 0 auto;">
                    <label class="form-label small mb-1">Plat Nomor</label>
                    <select name="user_id" class="form-select" style="min-width: 200px">
                        <option value="">Semua User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                                {{ $user->plat_nomor ?? $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="flex: 0 0 auto;">
                    <label class="form-label small mb-1">Bulan</label>
                    <input type="month" name="month" value="{{ $month }}" class="form-control" style="min-width: 150px" />
                </div>
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-sync"></i> Update Chart
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Trend Laporan - {{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}</h4>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 400px; width: 100%;">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    console.log('Chart data:', {
        dates: {!! json_encode($dates) !!},
        okData: {!! json_encode($okData) !!},
        ngData: {!! json_encode($ngData) !!},
        unknownData: {!! json_encode($unknownData) !!}
    });

    document.addEventListener('DOMContentLoaded', function() {
        var canvas = document.getElementById("lineChart");
        if (!canvas) {
            console.error('Canvas element not found');
            return;
        }

        var ctx = canvas.getContext('2d');
        var myChart = new Chart(ctx, {
            type: "line",
            data: {
                labels: {!! json_encode($dates) !!},
                datasets: [
                    {
                        label: "OK",
                        borderColor: "#31ce36",
                        backgroundColor: "rgba(49, 206, 54, 0.1)",
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        data: {!! json_encode($okData) !!}
                    },
                    {
                        label: "NG",
                        borderColor: "#dc3545",
                        backgroundColor: "rgba(220, 53, 69, 0.1)",
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        data: {!! json_encode($ngData) !!}
                    },
                    {
                        label: "?",
                        borderColor: "#6c757d",
                        backgroundColor: "rgba(108, 117, 125, 0.1)",
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        data: {!! json_encode($unknownData) !!}
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: "top"
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        min: 0,
                        max: 100,
                        ticks: {
                            stepSize: 20
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
