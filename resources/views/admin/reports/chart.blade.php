@extends('dashboard.layouts.index')

@section('content')
<style>
    @font-face {
        font-family: 'Plus Jakarta Sans';
        font-weight: 400; font-style: normal; font-display: swap;
        src: url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-400.woff2') format('woff2'),
             url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-400.woff') format('woff');
    }
    @font-face {
        font-family: 'Plus Jakarta Sans';
        font-weight: 600; font-style: normal; font-display: swap;
        src: url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-600.woff2') format('woff2'),
             url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-600.woff') format('woff');
    }
    @font-face {
        font-family: 'Plus Jakarta Sans';
        font-weight: 700; font-style: normal; font-display: swap;
        src: url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-700.woff2') format('woff2'),
             url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-700.woff') format('woff');
    }
    @font-face {
        font-family: 'Plus Jakarta Sans';
        font-weight: 800; font-style: normal; font-display: swap;
        src: url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-800.woff2') format('woff2'),
             url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-800.woff') format('woff');
    }

    :root {
        --blue:      #3b5bdb;
        --blue-lt:   #e8edff;
        --green:     #12b886;
        --green-lt:  #d3f9d8;
        --red:       #e03131;
        --red-lt:    #ffe3e3;
        --yellow:    #f59f00;
        --yellow-lt: #fff3bf;
        --surface:   #f4f6fb;
        --card:      #ffffff;
        --border:    #e8ecf4;
        --text:      #1a2035;
        --muted:     #8592a8;
        --shadow:    0 2px 12px rgba(30,40,80,.07);
        --shadow-md: 0 6px 24px rgba(30,40,80,.10);
    }

    * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
    .page-inner { background: var(--surface); min-height: 100vh; padding-bottom: 40px; }

    /* ── TOPBAR ──────────────────────────────────── */
    .topbar {
        display: flex; align-items: center;
        justify-content: space-between; flex-wrap: wrap;
        gap: 16px; padding: 32px 0 24px;
    }
    .topbar-title h3 {
        font-size: 1.45rem; font-weight: 800;
        color: var(--text); margin: 0 0 2px; letter-spacing: -.4px;
    }
    .topbar-title p { font-size: .78rem; color: var(--muted); margin: 0; }

    /* ── FILTER CARD ─────────────────────────────── */
    .filter-card {
        background: var(--card); border-radius: 16px;
        border: 1px solid var(--border); box-shadow: var(--shadow);
        padding: 16px 22px; margin-bottom: 24px;
        display: flex; align-items: flex-end; gap: 14px; flex-wrap: wrap;
    }
    .filter-group { display: flex; flex-direction: column; gap: 5px; }
    .filter-group label {
        font-size: .65rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .6px; color: var(--muted);
    }
    .filter-group select,
    .filter-group input[type="month"] {
        border: 1.5px solid var(--border); border-radius: 10px;
        padding: 8px 12px; font-size: .82rem; font-weight: 600;
        color: var(--text); background: var(--surface);
        font-family: 'Plus Jakarta Sans', sans-serif;
        outline: none; transition: border-color .2s; min-width: 170px;
    }
    .filter-group select:focus,
    .filter-group input[type="month"]:focus { border-color: var(--blue); background: #fff; }

    .btn-filter {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 18px; border-radius: 10px;
        font-size: .8rem; font-weight: 700; border: none; cursor: pointer;
        font-family: 'Plus Jakarta Sans', sans-serif; transition: all .2s;
    }
    .btn-filter:hover { transform: translateY(-1px); box-shadow: var(--shadow-md); }
    .btn-filter.update { background: var(--blue); color: #fff; }

    /* ── STAT MINI PILLS ─────────────────────────── */
    .stat-pills {
        display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px;
    }
    .stat-pill {
        display: flex; align-items: center; gap: 10px;
        background: var(--card); border: 1px solid var(--border);
        border-radius: 14px; padding: 12px 18px;
        box-shadow: var(--shadow); flex: 1; min-width: 130px;
        transition: all .22s;
    }
    .stat-pill:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
    .sp-icon {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: .88rem; flex-shrink: 0;
    }
    .sp-label {
        font-size: .63rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .6px; color: var(--muted); margin-bottom: 3px;
    }
    .sp-value { font-size: 1.4rem; font-weight: 800; color: var(--text); line-height: 1; }

    /* ── CHART CARD ──────────────────────────────── */
    .chart-card {
        background: var(--card); border-radius: 16px;
        border: 1px solid var(--border); box-shadow: var(--shadow);
        overflow: hidden;
    }
    .chart-card-header {
        background: linear-gradient(135deg, #1e2540 0%, #2d3660 100%);
        padding: 18px 26px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 12px;
        position: relative; overflow: hidden;
    }
    .chart-card-header::before {
        content: ''; position: absolute;
        width: 220px; height: 220px; border-radius: 50%;
        border: 42px solid rgba(255,255,255,.04);
        top: -80px; right: -60px; pointer-events: none;
    }
    .chart-card-header::after {
        content: ''; position: absolute;
        width: 120px; height: 120px; border-radius: 50%;
        border: 24px solid rgba(255,255,255,.03);
        bottom: -50px; left: 35%; pointer-events: none;
    }
    .chart-header-left {
        display: flex; align-items: center; gap: 12px;
        position: relative; z-index: 1;
    }
    .ch-icon {
        width: 38px; height: 38px; border-radius: 10px;
        background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.1);
        color: #fff; display: flex; align-items: center; justify-content: center;
        font-size: .9rem;
    }
    .chart-card-header h5 {
        color: #fff; font-size: .9rem; font-weight: 700; margin: 0;
    }
    .chart-card-header small {
        color: rgba(255,255,255,.5); font-size: .72rem; display: block; margin-top: 2px;
    }

    /* Legend pills on right */
    .chart-legend {
        display: flex; gap: 8px; flex-wrap: wrap;
        position: relative; z-index: 1;
    }
    .cl-pill {
        display: flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.12);
        border-radius: 20px; padding: 4px 12px;
        font-size: .7rem; font-weight: 700; color: rgba(255,255,255,.85);
    }
    .cl-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }

    /* Chart body */
    .chart-card-body { padding: 24px 20px 16px; }
    .chart-container {
        position: relative; height: 380px; width: 100%;
    }
</style>

<div class="page-inner">
    <div class="container-fluid" style="max-width: 1300px; margin: 0 auto; padding: 0 20px;">

        {{-- TOPBAR --}}
        <div class="topbar">
            <div class="topbar-title">
                <h3>Chart Laporan Harian</h3>
                <p><i class="fas fa-chart-line me-2"></i>Trend laporan per tanggal dalam sebulan</p>
            </div>
        </div>

        {{-- FILTER --}}
        <form method="GET" action="{{ route('admin.reports.chart') }}">
            <div class="filter-card">
                <div class="filter-group">
                    <label>Plat Nomor</label>
                    <select name="user_id">
                        <option value="">Semua User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                                {{ $user->plat_nomor ?? $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label>Bulan</label>
                    <input type="month" name="month" value="{{ $month }}" />
                </div>
                <button class="btn-filter update" type="submit">
                    <i class="fas fa-sync"></i> Update Chart
                </button>
            </div>
        </form>

        {{-- STAT PILLS --}}
        @php
            $okTotal  = array_sum($okData);
            $ngTotal  = array_sum($ngData);
            $unkTotal = array_sum($unknownData);
            $allTotal = $okTotal + $ngTotal + $unkTotal;
        @endphp
        <div class="stat-pills">
            <div class="stat-pill">
                <div class="sp-icon" style="background:#d3f9d8; color:#12b886;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="sp-label">Total OK</div>
                    <div class="sp-value">{{ $okTotal }}</div>
                </div>
            </div>
            <div class="stat-pill">
                <div class="sp-icon" style="background:#ffe3e3; color:#e03131;">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div>
                    <div class="sp-label">Total NG</div>
                    <div class="sp-value">{{ $ngTotal }}</div>
                </div>
            </div>
            <div class="stat-pill">
                <div class="sp-icon" style="background:#f1f3f5; color:#8592a8;">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div>
                    <div class="sp-label">Belum Dicek</div>
                    <div class="sp-value">{{ $unkTotal }}</div>
                </div>
            </div>
            <div class="stat-pill">
                <div class="sp-icon" style="background:#e8edff; color:#3b5bdb;">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div>
                    <div class="sp-label">Total Laporan</div>
                    <div class="sp-value">{{ $allTotal }}</div>
                </div>
            </div>
        </div>

        {{-- CHART CARD --}}
        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-header-left">
                    <div class="ch-icon"><i class="fas fa-chart-line"></i></div>
                    <div>
                        <h5>Trend Laporan — {{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}</h5>
                        <small>Jumlah laporan OK / NG / Belum Dicek per hari</small>
                    </div>
                </div>
                <div class="chart-legend">
                    <div class="cl-pill"><span class="cl-dot" style="background:#12b886;"></span> OK</div>
                    <div class="cl-pill"><span class="cl-dot" style="background:#e03131;"></span> NG</div>
                    <div class="cl-pill"><span class="cl-dot" style="background:#adb5bd;"></span> Belum Dicek</div>
                </div>
            </div>
            <div class="chart-card-body">
                <div class="chart-container">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var canvas = document.getElementById("lineChart");
    if (!canvas) return;

    var ctx = canvas.getContext('2d');

    // Gradient helpers
    function makeGradient(ctx, colorTop, colorBot) {
        var g = ctx.createLinearGradient(0, 0, 0, 380);
        g.addColorStop(0, colorTop);
        g.addColorStop(1, colorBot);
        return g;
    }

    var gradOk  = makeGradient(ctx, 'rgba(18,184,134,.22)',  'rgba(18,184,134,0)');
    var gradNg  = makeGradient(ctx, 'rgba(224,49,49,.18)',   'rgba(224,49,49,0)');
    var gradUnk = makeGradient(ctx, 'rgba(173,181,189,.15)', 'rgba(173,181,189,0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dates) !!},
            datasets: [
                {
                    label: 'OK',
                    borderColor: '#12b886',
                    backgroundColor: gradOk,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#12b886',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4,
                    data: {!! json_encode($okData) !!}
                },
                {
                    label: 'NG',
                    borderColor: '#e03131',
                    backgroundColor: gradNg,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#e03131',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4,
                    data: {!! json_encode($ngData) !!}
                },
                {
                    label: 'Belum Dicek',
                    borderColor: '#adb5bd',
                    backgroundColor: gradUnk,
                    borderWidth: 2,
                    pointBackgroundColor: '#adb5bd',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4,
                    data: {!! json_encode($unknownData) !!}
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false }, // custom legend in header
                tooltip: {
                    backgroundColor: '#1e2540',
                    titleColor: 'rgba(255,255,255,.7)',
                    bodyColor: '#fff',
                    padding: 12,
                    borderColor: 'rgba(255,255,255,.08)',
                    borderWidth: 1,
                    titleFont: { family: "'Plus Jakarta Sans', sans-serif", weight: '700', size: 12 },
                    bodyFont:  { family: "'Plus Jakarta Sans', sans-serif", size: 12 },
                    callbacks: {
                        title: function(items) { return 'Tanggal ' + items[0].label; }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(232,236,244,.6)', drawBorder: false },
                    ticks: {
                        font: { family: "'Plus Jakarta Sans', sans-serif", size: 11, weight: '600' },
                        color: '#8592a8'
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(232,236,244,.6)', drawBorder: false },
                    ticks: {
                        stepSize: 5,
                        font: { family: "'Plus Jakarta Sans', sans-serif", size: 11, weight: '600' },
                        color: '#8592a8'
                    }
                }
            }
        }
    });
});
</script>
@endsection
