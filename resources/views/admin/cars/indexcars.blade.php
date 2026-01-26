@extends('dashboard.layouts.index')

@section('content')
    <div class="page-inner">

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="card-title mb-0">Data Mobil</h4>
                    <small class="text-muted">Daftar mobil terdaftar</small>
                </div>

                {{-- BUTTON TAMBAH DATA --}}
                <a href="{{ route('cars.create') }}" class="btn btn-success btn-sm d-flex align-items-center gap-2 px-3 shadow-sm">
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
                                <th>Nama Mobil</th>
                                <th>Plat Nomor</th>
                                <th style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cars as $car)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + ($cars->currentPage()-1)*$cars->perPage() }}</td>
                                    <td class="text-center">{{ $car->nama_mobil }}</td>
                                    <td class="text-center">{{ $car->plat_nomor }}</td>
                                    <td class="text-center">
                                        <div class="d-inline-flex gap-2">
                                            <a href="{{ route('cars.edit', $car->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('cars.destroy', $car->id) }}" method="POST" onsubmit="return confirm('Hapus mobil ini?')" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada mobil.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($cars->hasPages())
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Menampilkan {{ $cars->firstItem() }} - {{ $cars->lastItem() }} dari {{ $cars->total() }} mobil
                        </div>
                        <div>
                            {{ $cars->onEachSide(1)->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
