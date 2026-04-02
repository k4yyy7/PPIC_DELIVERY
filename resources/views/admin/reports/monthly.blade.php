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
        --yellow:    #f59f00;
        --yellow-lt: #fff3bf;
        --red:       #e03131;
        --red-lt:    #ffe3e3;
        --cyan:      #1098ad;
        --cyan-lt:   #d0ebff;
        --purple:    #7048e8;
        --purple-lt: #f3f0ff;
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

    /* ── TOPBAR ─────────────────────────────── */
    .rpt-topbar {
        display: flex; align-items: flex-start;
        justify-content: space-between; flex-wrap: wrap;
        gap: 16px; padding: 32px 0 24px;
    }
    .rpt-topbar-title h3 {
        font-size: 1.45rem; font-weight: 800;
        color: var(--text); margin: 0 0 2px; letter-spacing: -.4px;
    }
    .rpt-topbar-title p { font-size: .78rem; color: var(--muted); margin: 0; }

    /* ── FILTER CARD ────────────────────────── */
    .filter-card {
        background: var(--card); border-radius: 16px;
        border: 1px solid var(--border); box-shadow: var(--shadow);
        padding: 18px 22px; margin-bottom: 24px;
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
        outline: none; transition: border-color .2s;
        min-width: 170px;
    }
    .filter-group select:focus,
    .filter-group input[type="month"]:focus { border-color: var(--blue); background: #fff; }

    .btn-filter {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 18px; border-radius: 10px;
        font-size: .8rem; font-weight: 700; border: none; cursor: pointer;
        font-family: 'Plus Jakarta Sans', sans-serif; transition: all .2s;
        text-decoration: none;
    }
    .btn-filter:hover { transform: translateY(-1px); box-shadow: var(--shadow-md); }
    .btn-filter.search { background: var(--blue); color: #fff; }
    .btn-filter.export { background: var(--green); color: #fff; }

    /* ── STAT CARDS ─────────────────────────── */
    .stat-card {
        background: var(--card); border-radius: 16px; padding: 18px 20px;
        box-shadow: var(--shadow); border: 1px solid var(--border);
        transition: all .22s; height: 100%;
        display: flex; align-items: center; justify-content: space-between;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
    .sc-label { font-size: .66rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: var(--muted); margin-bottom: 5px; }
    .sc-value { font-size: 1.85rem; font-weight: 800; color: var(--text); line-height: 1; }
    .sc-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }

    /* ── TABLE CARD ─────────────────────────── */
    .table-card {
        background: var(--card); border-radius: 16px;
        border: 1px solid var(--border); box-shadow: var(--shadow);
        overflow: hidden; margin-top: 24px;
    }
    .table-card-header {
        background: linear-gradient(135deg, #1e2540 0%, #2d3660 100%);
        padding: 18px 24px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 12px;
        position: relative; overflow: hidden;
    }
    .table-card-header::before {
        content: ''; position: absolute;
        width: 200px; height: 200px; border-radius: 50%;
        border: 40px solid rgba(255,255,255,.04);
        top: -80px; right: -50px; pointer-events: none;
    }
    .table-card-header h4 {
        color: #fff; font-size: .95rem; font-weight: 700; margin: 0;
        position: relative; z-index: 1;
    }
    .table-card-header .row-count {
        position: relative; z-index: 1;
        font-size: .72rem; font-weight: 600;
        color: rgba(255,255,255,.55);
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 20px; padding: 4px 12px;
    }

    /* Table scroll container — fixed height, scrollable */
    .table-scroll-wrap {
        overflow-y: auto;
        overflow-x: auto;
        max-height: 520px; /* ~36 rows before scroll kicks in */
    }
    /* Custom scrollbar */
    .table-scroll-wrap::-webkit-scrollbar { width: 6px; height: 6px; }
    .table-scroll-wrap::-webkit-scrollbar-track { background: var(--surface); }
    .table-scroll-wrap::-webkit-scrollbar-thumb { background: #c8d0e4; border-radius: 10px; }
    .table-scroll-wrap::-webkit-scrollbar-thumb:hover { background: #9aa3be; }

    /* Sticky header */
    .rpt-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .rpt-table thead th {
        position: sticky; top: 0; z-index: 10;
        background: #f0f3fa;
        padding: 11px 14px;
        font-size: .67rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .6px; color: var(--muted);
        border-bottom: 2px solid var(--border);
        white-space: nowrap;
    }
    .rpt-table thead th:first-child { border-radius: 0; }

    .rpt-table tbody tr { transition: background .15s; }
    .rpt-table tbody tr:hover { background: #f6f8ff; }
    .rpt-table tbody tr:nth-child(even) { background: #fafbfd; }
    .rpt-table tbody tr:nth-child(even):hover { background: #f0f3ff; }

    .rpt-table td {
        padding: 11px 14px;
        font-size: .8rem; color: var(--text);
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
        white-space: nowrap;
    }
    .rpt-table td.wrap-col { white-space: normal; max-width: 180px; }

    /* Badges */
    .rbadge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 9px; border-radius: 20px;
        font-size: .67rem; font-weight: 700;
    }
    .rbadge.ok      { background: var(--green-lt); color: #087f5b; }
    .rbadge.ng      { background: var(--red-lt);   color: #c92a2a; }
    .rbadge.unknown { background: #f1f3f5;          color: var(--muted); }
    .rbadge.driver  { background: var(--blue-lt);  color: var(--blue); }
    .rbadge.armada  { background: var(--cyan-lt);  color: var(--cyan); }
    .rbadge.dokumen { background: #f1f3f5;          color: #495057; }
    .rbadge.env     { background: var(--green-lt); color: #087f5b; }
    .rbadge.safety  { background: var(--yellow-lt);color: #7c5a00; }

    .user-name { font-weight: 700; font-size: .82rem; color: var(--text); }
    .user-plat { font-size: .7rem; color: var(--muted); margin-top: 2px; }

    .img-thumb {
        width: 44px; height: 44px; border-radius: 8px;
        object-fit: cover; border: 1px solid var(--border);
        cursor: pointer; transition: transform .2s;
    }
    .img-thumb:hover { transform: scale(1.1); }

    /* Delete btn */
    .btn-del {
        width: 30px; height: 30px; border-radius: 8px;
        background: var(--red-lt); color: var(--red); border: none;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .75rem; cursor: pointer; transition: all .2s;
    }
    .btn-del:hover { background: var(--red); color: #fff; }

    /* Empty state */
    .empty-state {
        padding: 60px 20px; text-align: center;
    }
    .empty-state i { font-size: 2.5rem; color: #d0d8ea; margin-bottom: 12px; display: block; }
    .empty-state p { font-size: .85rem; color: var(--muted); margin: 0; }

    /* ── PAGINATION ──────────────────────────── */
    .pagination-bar {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 10px;
        padding: 14px 20px;
        border-top: 1px solid var(--border);
        background: var(--surface);
    }
    .pagination-info { font-size: .75rem; color: var(--muted); font-weight: 500; }
    .pagination-nav { display: flex; align-items: center; gap: 6px; }
    .pg-btn {
        width: 32px; height: 32px; border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .78rem; font-weight: 700; color: var(--muted);
        background: var(--card); border: 1px solid var(--border);
        text-decoration: none; transition: all .18s; cursor: pointer;
    }
    .pg-btn:hover { background: var(--blue-lt); color: var(--blue); border-color: #b8c8f8; }
    .pg-btn.active { background: var(--blue); color: #fff; border-color: var(--blue); }
    .pg-btn.disabled { opacity: .4; pointer-events: none; cursor: not-allowed; }
    .pg-label { font-size: .75rem; color: var(--muted); font-weight: 600; padding: 0 6px; }
</style>

<div class="page-inner">
    <div class="container-fluid" style="max-width: 1300px; margin: 0 auto; padding: 0 20px;">

        {{-- TOPBAR --}}
        <div class="rpt-topbar">
            <div class="rpt-topbar-title">
                <h3>Rekap Bulanan</h3>
                <p><i class="fas fa-calendar-alt me-2"></i>Rekapitulasi laporan harian semua user</p>
            </div>
        </div>

        {{-- FILTER --}}
        <form method="GET" action="{{ route('admin.reports.monthly') }}">
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
                <button class="btn-filter search" type="submit">
                    <i class="fas fa-search"></i> Cari
                </button>
                <button class="btn-filter export" name="export" value="1" type="submit">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
            </div>
        </form>

        {{-- STAT CARDS --}}
        <div class="row g-3 mb-2">
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div>
                        <div class="sc-label">OK</div>
                        <div class="sc-value">{{ $summary['OK'] ?? 0 }}</div>
                    </div>
                    <div class="sc-icon" style="background:#d3f9d8; color:#12b886;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div>
                        <div class="sc-label">NG</div>
                        <div class="sc-value">{{ $summary['NG'] ?? 0 }}</div>
                    </div>
                    <div class="sc-icon" style="background:#ffe3e3; color:#e03131;">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div>
                        <div class="sc-label">Unknown</div>
                        <div class="sc-value">{{ $summary['UNKNOWN'] ?? 0 }}</div>
                    </div>
                    <div class="sc-icon" style="background:#f1f3f5; color:#8592a8;">
                        <i class="fas fa-question-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div>
                        <div class="sc-label">Total Laporan</div>
                        <div class="sc-value">{{ $total }}</div>
                    </div>
                    <div class="sc-icon" style="background:#e8edff; color:#3b5bdb;">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLE CARD --}}
        <div class="table-card">

            {{-- Header --}}
            <div class="table-card-header">
                <h4><i class="fas fa-table me-2" style="opacity:.6;"></i>Detail Laporan Bulan {{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}</h4>
                <span class="row-count">{{ $total }} laporan</span>
            </div>

            {{-- Scrollable table --}}
            <div class="table-scroll-wrap">
                <table class="rpt-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>User</th>
                            <th>Driver</th>
                            <th>Tipe</th>
                            <th>Item</th>
                            <th>Status</th>
                            <th>Foto</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $i => $report)
                        <tr>
                            <td style="color:var(--muted); font-size:.72rem;">
                                {{ method_exists($reports, 'firstItem') ? $reports->firstItem() + $i : $i + 1 }}
                            </td>
                            <td style="font-weight:600;">
                                {{ \Carbon\Carbon::parse($report->date)->format('d M Y') }}
                            </td>
                            <td>
                                <div class="user-name">{{ $report->user->name ?? '-' }}</div>
                                @if($report->user && $report->user->plat_nomor)
                                    <div class="user-plat">{{ $report->user->plat_nomor }}</div>
                                @endif
                            </td>
                            <td style="font-weight:600;">{{ $report->driver_name ?? '-' }}</td>
                            <td>
                                @switch($report->subject_type)
                                    @case(App\Models\DriverItem::class)
                                        <span class="rbadge driver"><i class="fas fa-id-card"></i> Driver</span>@break
                                    @case(App\Models\ArmadaItem::class)
                                        <span class="rbadge armada"><i class="fas fa-truck"></i> Armada</span>@break
                                    @case(App\Models\Dokument::class)
                                        <span class="rbadge dokumen"><i class="fas fa-file-alt"></i> Dokumen</span>@break
                                    @case(App\Models\Environment::class)
                                        <span class="rbadge env"><i class="fas fa-leaf"></i> Environment</span>@break
                                    @case(App\Models\Safety::class)
                                        <span class="rbadge safety"><i class="fas fa-shield-alt"></i> Safety</span>@break
                                    @default
                                        <span class="rbadge unknown">Lainnya</span>
                                @endswitch
                            </td>
                            <td class="wrap-col">
                                @if($report->subject)
                                    @if($report->subject->car?->plat_nomor)
                                        <div class="rbadge driver" style="margin-bottom:4px; display:inline-flex;">{{ $report->subject->car->plat_nomor }}</div>
                                    @elseif($report->user && $report->user->plat_nomor)
                                        <div class="rbadge driver" style="margin-bottom:4px; display:inline-flex;">{{ $report->user->plat_nomor }}</div>
                                    @endif
                                    <div style="font-size:.7rem; color:var(--muted); margin-top:2px;">{{ Str::limit($report->subject->safety_items ?? '', 28) }}</div>
                                @else
                                    <span style="color:var(--muted); font-size:.75rem;">#{{ $report->subject_id }}</span>
                                @endif
                            </td>
                            <td>
                                @if($report->status === 'OK')
                                    <span class="rbadge ok"><i class="fas fa-check"></i> OK</span>
                                @elseif($report->status === 'NG')
                                    <span class="rbadge ng"><i class="fas fa-times"></i> NG</span>
                                @else
                                    <span class="rbadge unknown">?</span>
                                @endif
                            </td>
                            <td>
                                @if($report->image_path)
                                    <a href="{{ asset('storage/'.$report->image_path) }}" target="_blank">
                                        <img src="{{ asset('storage/'.$report->image_path) }}" alt="evidence" class="img-thumb">
                                    </a>
                                @else
                                    <span style="color:var(--muted); font-size:.75rem;">—</span>
                                @endif
                            </td>
                            <td class="wrap-col" style="font-size:.75rem; color:var(--muted);">
                                {{ Str::limit($report->notes ?? '—', 40) }}
                            </td>
                            <td>
                                <form action="{{ route('admin.reports.destroy', $report->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus laporan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-del" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>Tidak ada laporan untuk bulan ini</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if(method_exists($reports, 'hasPages') && $reports->hasPages())
            <div class="pagination-bar">
                <div class="pagination-info">
                    Menampilkan {{ $reports->firstItem() }}–{{ $reports->lastItem() }} dari {{ $reports->total() }} laporan
                </div>
                <div class="pagination-nav">
                    {{-- Prev --}}
                    @if($reports->onFirstPage())
                        <span class="pg-btn disabled"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a class="pg-btn" href="{{ $reports->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                    @endif

                    {{-- Page numbers --}}
                    @php
                        $cur  = $reports->currentPage();
                        $last = $reports->lastPage();
                        $range = collect(range(1, $last))->filter(fn($p) =>
                            $p == 1 || $p == $last || abs($p - $cur) <= 1
                        );
                    @endphp

                    @php $prev = null; @endphp
                    @foreach($range as $page)
                        @if($prev && $page - $prev > 1)
                            <span class="pg-label">…</span>
                        @endif
                        <a class="pg-btn {{ $page == $cur ? 'active' : '' }}"
                           href="{{ $reports->url($page) }}">{{ $page }}</a>
                        @php $prev = $page; @endphp
                    @endforeach

                    {{-- Next --}}
                    @if($reports->hasMorePages())
                        <a class="pg-btn" href="{{ $reports->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="pg-btn disabled"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
            @endif

        </div>{{-- /table-card --}}

    </div>
</div>
@endsection
