@extends('dashboard.layouts.index')

@section('content')
    <div class="page-inner">

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="card-title mb-0">Data User</h4>
                    <small class="text-muted">Daftar user terdaftar</small>
                </div>

                {{-- BUTTON TAMBAH DATA --}}
                <a href="{{ route('datauser.create') }}" class="btn btn-success btn-sm d-flex align-items-center gap-2 px-3 shadow-sm">
                    <i class="fas fa-user-plus"></i>
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
                                <th>Name</th>
                                <th>Email</th>
                                <th>Plat Nomor</th>
                                <th style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + ($users->currentPage()-1)*$users->perPage() }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td class="text-center">{{ $user->plat_nomor ?? '-' }}</td>
                                    <td class="text-center">
                                        <div class="d-inline-flex gap-2">
                                            <a href="{{ route('datauser.edit', $user->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('datauser.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
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
                                    <td colspan="5" class="text-center">Tidak ada user.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>`
                </div>

                @if($users->hasPages())
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} users
                        </div>
                        <div>
                            {{ $users->onEachSide(1)->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
