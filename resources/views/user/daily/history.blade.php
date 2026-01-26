@extends('dashboard.layouts.index')

@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Riwayat Laporan Harian</h3>
            <h6 class="op-7 mb-2">Daftar laporan yang sudah diisi</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <form method="GET" action="{{ route('user.daily.history') }}" class="row g-2 align-items-end">
                <div class="col-auto">
                    <label class="form-label small mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="form-control" style="min-width: 150px" />
                </div>
                <div class="col-auto d-flex gap-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <button class="btn btn-success" name="export" value="1" type="submit">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-head-bg-success">
                        <tr>
                            <th>Tanggal</th>
                            <th>Tipe</th>
                            <th>Nama Driver</th>
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
                                    {{ $report->driver_name ?? '-' }}
                                </td>
                                <td>
                                    @if($report->subject)
                                        @if($report->subject->car?->plat_nomor)
                                            <div class="badge bg-primary mb-1">{{ $report->subject->car->plat_nomor }}</div>
                                        @elseif(Auth::user()->plat_nomor)
                                            <div class="badge bg-primary mb-1">{{ Auth::user()->plat_nomor }}</div>
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
                                <td>{{ $report->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada laporan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- <div class="mt-3">
                {{ $reports->links() }}
            </div> --}}

            @if($reports->hasPages())
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
    </div>
</div>
@endsection
