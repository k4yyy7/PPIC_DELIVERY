@extends('dashboard.layouts.index')

@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Pilih Driver Aktif Hari Ini</h3>
            <h6 class="op-7 mb-2">Tanggal: {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h6>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-8 col-lg-6">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('user.driver.active.store', ['date' => $date]) }}" class="row g-2 align-items-end">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">
                <div class="col-12 mb-2">
                    <label class="form-label small mb-1 fw-bold">Pilih Driver</label>
                    <select name="driver_id" class="form-select" required style="min-width: 200px">
                        <option value="">-- Pilih Driver --</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" @if(optional($active)->driver_id == $driver->id) selected @endif>
                                {{ $driver->name }}@if($driver->license_no) (SIM: {{ $driver->license_no }})@endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto d-flex gap-2">
                    <button class="btn btn-success" type="submit">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
            @if($active && $active->driver)
                <div class="mt-4 alert alert-info">
                    <strong>Driver aktif hari ini:</strong><br>
                    {{ $active->driver->name }}<br>
                    @if($active->driver->license_no)
                        SIM: {{ $active->driver->license_no }}<br>
                    @endif
                    @if($active->driver->phone)
                        No. HP: {{ $active->driver->phone }}
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
