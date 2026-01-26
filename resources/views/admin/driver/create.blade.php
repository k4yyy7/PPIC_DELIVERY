@extends('dashboard.layouts.index')

@section('content')
<div class="page-inner">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h4 class="card-title mb-0">Tambah Driver</h4>
                <small class="text-muted">Form untuk menambah driver baru</small>
            </div>
            <a href="{{ route('drivers.index') }}" class="btn btn-success btn-sm">Kembali</a>
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
            <form action="{{ route('drivers.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Driver <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required maxlength="100">
                </div>
                <div class="mb-3">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" maxlength="30">
                </div>
                <div class="mb-3">
                    <label class="form-label">No. SIM</label>
                    <input type="text" name="license_no" class="form-control" value="{{ old('license_no') }}" maxlength="50">
                </div>
                <button class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection
