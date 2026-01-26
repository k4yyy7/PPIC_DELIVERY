@extends('dashboard.layouts.index')

@section('content')
<div class="page-inner">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h4 class="card-title mb-0">Tambah User</h4>
                <small class="text-muted">Form untuk menambahkan user baru</small>
            </div>
            <a href="{{ route('datauser.index') }}" class="btn btn-success btn-sm">Kembali</a>
        </div>

        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('datauser.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Plat Nomor (opsional)</label>
                    <select name="plat_nomor" class="form-select">
                        <option value="">-- Pilih Plat Nomor --</option>
                        @foreach($cars as $car)
                            <option value="{{ $car->plat_nomor }}" {{ old('plat_nomor') == $car->plat_nomor ? 'selected' : '' }}>
                                {{ $car->plat_nomor }} - {{ $car->nama_mobil }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button class="btn btn-success">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection
