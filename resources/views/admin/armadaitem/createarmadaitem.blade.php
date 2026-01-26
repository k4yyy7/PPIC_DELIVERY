@extends('dashboard.layouts.index')

@section('content')
<div class="page-inner">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h4 class="card-title mb-0">Tambah Armada Item</h4>
                <small class="text-muted">Form untuk menambahkan armada item baru</small>
            </div>
            <a href="{{ route('armada-items.index') }}" class="btn btn-success btn-sm">Kembali</a>
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

            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    <b>Success!</b>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('armada-items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Safety Items</label>
                    <textarea name="safety_items" class="form-control" rows="3">{{ old('safety_items') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Standard Items</label>
                    <textarea name="standard_items" class="form-control" rows="3">{{ old('standard_items') }}</textarea>
                </div>
                <button class="btn btn-success">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection
