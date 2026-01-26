@extends('dashboard.layouts.index')

@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Rekap Bulanan - Admin</h3>
            <h6 class="op-7 mb-2">Rekapitulasi laporan harian semua user</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <form method="GET" action="{{ route('admin.reports.monthly') }}" class="d-flex gap-2 align-items-end">
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
                    <i class="fas fa-search"></i> Search
                </button>
                <button class="btn btn-success" name="export" value="1" type="submit">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
            </form>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">OK</p>
                                <h4 class="card-title">{{ $summary['OK'] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(method_exists($reports, 'hasPages') && $reports->hasPages())
                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Menampilkan {{ $reports->firstItem() }} - {{ $reports->lastItem() }} dari {{ $reports->total() }} laporan
                    </div>
                    <div class="d-flex gap-3 align-items-center">
                        @if ($reports->onFirstPage())
                            <i class="fas fa-chevron-left text-muted" style="opacity: 0.5; cursor: not-allowed;"></i>
                        @else
                            <a href="{{ $reports->previousPageUrl() }}"><i class="fas fa-chevron-left text-primary" style="cursor: pointer;"></i></a>
                        @endif

                        <span class="text-muted small">Halaman {{ $reports->currentPage() }} dari {{ $reports->lastPage() }}</span>

                        @if ($reports->hasMorePages())
                            <a href="{{ $reports->nextPageUrl() }}"><i class="fas fa-chevron-right text-primary" style="cursor: pointer;"></i></a>
                        @else
                            <i class="fas fa-chevron-right text-muted" style="opacity: 0.5; cursor: not-allowed;"></i>
                        @endif
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="fas fa-times-circle text-danger"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">NG</p>
                                <h4 class="card-title">{{ $summary['NG'] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="fas fa-question-circle text-secondary"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">?</p>
                                <h4 class="card-title">{{ $summary['UNKNOWN'] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="fas fa-clipboard-list text-primary"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">Total</p>
                                <h4 class="card-title">{{ $total }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Grafik Laporan Bulan {{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}</div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Detail Laporan Bulan {{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-hover table-bordered table-striped">
                    <thead class="table-head-bg-success">
                        <tr>
                            <th>Tanggal</th>
                            <th>User</th>
                            <th>Nama Driver</th>
                            <th>Tipe</th>
                            <th>Item</th>
                            <th>Status</th>
                            <th>Foto</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($report->date)->format('d M Y') }}</td>
                                <td>
                                    <strong>{{ $report->user->name ?? '-' }}</strong>
                                    @if($report->user && $report->user->plat_nomor)
                                        <br><small class="text-muted">{{ $report->user->plat_nomor }}</small>
                                    @endif
                                </td>
                                <td>{{ $report->driver_name ?? '-' }}</td>
                                <td>
                                    @switch($report->subject_type)
                                        @case(App\Models\DriverItem::class)
                                            <span class="badge badge-primary">Driver</span>
                                            @break
                                        @case(App\Models\ArmadaItem::class)
                                            <span class="badge badge-info">Armada</span>
                                            @break
                                        @case(App\Models\Dokument::class)
                                            <span class="badge badge-secondary">Dokumen</span>
                                            @break
                                        @case(App\Models\Environment::class)
                                            <span class="badge badge-success">Environment</span>
                                            @break
                                        @case(App\Models\Safety::class)
                                            <span class="badge badge-warning text-dark">Safety</span>
                                            @break
                                        @default
                                            <span class="badge badge-light">Lainnya</span>
                                    @endswitch
                                </td>
                                <td>
                                    @if($report->subject)
                                        @if($report->subject->car?->plat_nomor)
                                            <div class="badge bg-primary mb-1">{{ $report->subject->car->plat_nomor }}</div>
                                        @elseif($report->user && $report->user->plat_nomor)
                                            <div class="badge bg-primary mb-1">{{ $report->user->plat_nomor }}</div>
                                        @endif
                                        <br><small class="text-muted">Safety: {{ Str::limit($report->subject->safety_items ?? '-', 30) }}</small>
                                        <br><small class="text-muted">Standard: {{ Str::limit($report->subject->standard_items ?? '-', 30) }}</small>
                                    @else
                                        <span class="text-muted">Item #{{ $report->subject_id }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($report->status === 'OK')
                                        <span class="badge badge-success">OK</span>
                                    @elseif($report->status === 'NG')
                                        <span class="badge badge-danger">NG</span>
                                    @else
                                        <span class="badge badge-secondary">?</span>
                                    @endif
                                </td>
                                <td>
                                    @if($report->image_path)
                                        <a href="{{ asset('storage/'.$report->image_path) }}" target="_blank">
                                            <img src="{{ asset('storage/'.$report->image_path) }}" alt="evidence" class="img-thumbnail" style="max-height: 60px">
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($report->notes ?? '-', 40) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada laporan untuk bulan ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById("lineChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: "line",
            data: {
                labels: [{{ implode(',', array_map(fn($d) => '"' . $d . '"', $dayLabels)) }}],
                datasets: [
                    {
                        label: "OK",
                        borderColor: "#31ce36",
                        backgroundColor: "rgba(49, 206, 54, 0.1)",
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        data: [{{ implode(',', $okData) }}]
                    },
                    {
                        label: "NG",
                        borderColor: "#dc3545",
                        backgroundColor: "rgba(220, 53, 69, 0.1)",
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        data: [{{ implode(',', $ngData) }}]
                    },
                    {
                        label: "?",
                        borderColor: "#6c757d",
                        backgroundColor: "rgba(108, 117, 125, 0.1)",
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        data: [{{ implode(',', $unknownData) }}]
                    }
                ]
            },
            options: {
                responsive: true,
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
