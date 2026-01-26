@extends('dashboard.layouts.index')

@section('content')
<div class="page-inner">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h4 class="card-title mb-0">Tambah Mobil</h4>
                <small class="text-muted">Form untuk menambahkan mobil baru</small>
            </div>
            <a href="{{ route('cars.index') }}" class="btn btn-success btn-sm">Kembali</a>
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

            <form action="{{ route('cars.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Mobil</label>
                    <input type="text" name="nama_mobil" class="form-control" value="{{ old('nama_mobil') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Plat Nomor</label>
                    <input type="text" name="plat_nomor" class="form-control" value="{{ old('plat_nomor') }}" required>
                </div>
                <button class="btn btn-success">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection
