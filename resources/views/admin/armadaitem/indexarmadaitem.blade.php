@extends('dashboard.layouts.index')

@section('content')
    <div class="page-inner">

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="card-title mb-0">Armada Items</h4>
                    <small class="text-muted">Armada Checks</small>
                </div>

                {{-- BUTTON TAMBAH DATA --}}
                <a href="{{ route('armada-items.create') }}" class="btn btn-success btn-sm d-flex align-items-center gap-2 px-3 shadow-sm">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Data</span>
                </a>

            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-head-bg-success">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 60px;">No</th>
                                <th>Safety Items</th>
                                <th>Standard Items</th>
                                <th>Date</th>
                                <th style="width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($armadaItems as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + ($armadaItems->currentPage()-1)*$armadaItems->perPage() }}</td>
                                    
                                    <td class="text-center text-wrap text-break">{{ $item->safety_items ?: '-' }}</td>
                                    <td class="text-center text-wrap text-break">{{ $item->standard_items ?: '-' }}</td>
                                    <td class="text-center">{{ $item->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center" style="min-height: 38px;">
                                            <a href="{{ route('armada-items.edit', $item->id) }}" class="btn btn-sm btn-primary flex-grow-1 d-flex align-items-center justify-content-center p-0">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('armada-items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus armada item ini?')" class="flex-grow-1" style="margin: 0; padding: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger w-100 h-100 d-flex align-items-center justify-content-center p-0" style="margin: 0;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada armada item.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($armadaItems->hasPages())
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Menampilkan {{ $armadaItems->firstItem() }} - {{ $armadaItems->lastItem() }} dari {{ $armadaItems->total() }} armada item
                        </div>
                        <div>
                            {{ $armadaItems->onEachSide(1)->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
