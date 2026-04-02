@extends('dashboard.layouts.index')

@section('content')
    <style>
        .safety-card {
            transition: all 0.3s ease;
            border-left: 5px solid #6c757d;
            /* Default gray */
            background: #fff;
        }

        .safety-card.status-ok {
            border-left-color: #28a745;
        }

        .safety-card.status-ng {
            border-left-color: #dc3545;
        }

        .item-label-badge {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            margin-bottom: 5px;
            display: block;
            color: #888;
        }

        .standard-box {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 12px;
            border: 1px dashed #dee2e6;
        }

        .btn-camera-action {
            border-radius: 20px;
            padding: 8px 20px;
        }

        .preview-img-container {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .preview-img-container img {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>

    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-1">Laporan Harian Safety</h3>
                <p class="text-muted mb-0"><i class="far fa-calendar-alt me-1"></i>
                    {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</p>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <form method="GET" action="{{ route('user.daily.safety') }}" class="d-flex gap-2">
                    <input type="date" name="date" value="{{ $date }}" class="form-control shadow-sm"
                        style="border-radius: 8px;" />
                    <button class="btn btn-primary shadow-sm" style="border-radius: 8px;">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm d-flex align-items-center" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <div>
                    <strong>Laporan berhasil disimpan!</strong><br>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                @forelse($safetys as $index => $item)
                    @if ($index === 0)
                        <form id="bulk-safety-report" action="{{ route('user.daily.store-multiple') }}" method="POST"
                            enctype="multipart/form-data" class="bulk-report-form">
                            @csrf
                            <input type="hidden" name="subject_type" value="safety" />
                            <input type="hidden" name="date" value="{{ $date }}" />
                            <input type="hidden" name="scroll_y" value="0" class="scroll-pos-input" />
                    @endif

                    @php
                        $key = App\Models\Safety::class . '_' . $item->id;
                        $report = $reports[$key] ?? null;
                        $currentStatus = optional($report)->status ?? 'UNKNOWN';
                        $statusClass =
                            $currentStatus === 'OK' ? 'status-ok' : ($currentStatus === 'NG' ? 'status-ng' : '');
                    @endphp

                    <div class="card safety-card {{ $statusClass }} mb-4 shadow-sm" data-item-id="{{ $item->id }}">
                        <div class="card-body">
                            <div class="row align-items-center mb-3">
                                <div class="col-auto">
                                    <div class="avatar-sm">
                                        <span
                                            class="avatar-title rounded-circle {{ $currentStatus === 'OK' ? 'bg-success' : ($currentStatus === 'NG' ? 'bg-danger' : 'bg-primary') }}">
                                            {{ $loop->iteration }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col">
                                    <h5 class="fw-bold mb-0">Item Pemeriksaan</h5>
                                </div>
                                <div class="col-auto">
                                    <span
                                        class="badge rounded-pill status-badge-{{ $item->id }} {{ $currentStatus === 'OK' ? 'bg-success' : ($currentStatus === 'NG' ? 'bg-danger' : 'bg-secondary') }}">
                                        {{ $currentStatus }}
                                    </span>
                                </div>
                            </div>

                            <div class="standard-box mb-4">
                                <div class="row">
                                    <div class="col-md-6 mb-2 mb-md-0">
                                        <span class="item-label-badge text-danger"><i
                                                class="fas fa-exclamation-triangle"></i> Safety Item</span>
                                        <p class="mb-0 fw-bold text-dark">{{ $item->safety_items ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-6 border-start-md">
                                        <span class="item-label-badge text-primary"><i class="fas fa-info-circle"></i>
                                            Standard</span>
                                        <p class="mb-0 text-muted small">{{ $item->standard_items ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="items[{{ $index }}][subject_id]"
                                value="{{ $item->id }}" />

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Pilih Status</label>
                                    <select name="items[{{ $index }}][status]"
                                        class="form-select form-select-lg select-status border-2"
                                        data-item-id="{{ $item->id }}" style="border-radius: 10px;">
                                        <option value="UNKNOWN" {{ $currentStatus === 'UNKNOWN' ? 'selected' : '' }}>?
                                            Belum Dicek</option>
                                        <option value="OK" {{ $currentStatus === 'OK' ? 'selected' : '' }}>🟢 OK
                                        </option>
                                        <option value="NG" {{ $currentStatus === 'NG' ? 'selected' : '' }}>🔴 NG (Not
                                            Good)</option>
                                    </select>
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label fw-bold">Bukti Foto & Catatan</label>
                                    <div class="input-group mb-2">
                                        <button type="button" class="btn btn-outline-primary open-camera-btn"
                                            data-index="{{ $index }}">
                                            <i class="fas fa-camera"></i>
                                        </button>
                                        <label class="btn btn-outline-secondary mb-0" style="cursor: pointer;">
                                            <i class="fas fa-upload"></i>
                                            <input type="file" name="items[{{ $index }}][image]" accept="image/*"
                                                class="d-none file-input-{{ $index }}"
                                                data-index="{{ $index }}" />
                                        </label>
                                        <input type="text" name="items[{{ $index }}][notes]" class="form-control"
                                            placeholder="Tambahkan catatan di sini..."
                                            value="{{ optional($report)->notes }}">
                                    </div>

                                    <div class="camera-container-{{ $index }} d-none mt-2">
                                        <div class="position-relative bg-dark rounded overflow-hidden">
                                            <video class="camera-preview-{{ $index }} w-100"
                                                style="max-height: 250px; object-fit: cover;" autoplay playsinline></video>
                                            <div
                                                class="position-absolute bottom-0 start-0 end-0 p-2 d-flex gap-2 bg-dark-gradient">
                                                <button type="button"
                                                    class="btn btn-sm btn-success flex-fill capture-photo-btn"
                                                    data-index="{{ $index }}">Ambil</button>
                                                <button type="button"
                                                    class="btn btn-sm btn-light flex-fill close-camera-btn"
                                                    data-index="{{ $index }}">Batal</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="image-preview-{{ $index }} mt-2">
                                        @if (optional($report)->image_path)
                                            <div class="preview-img-container">
                                                <img src="{{ asset('storage/' . $report->image_path) }}"
                                                    class="img-fluid rounded border" style="max-height: 150px;" />
                                                <div class="mt-1 small text-success"><i class="fas fa-check"></i> Foto
                                                    Tersimpan</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($loop->last)
                        <div class="mt-5 pb-5 text-center">
                            <button type="submit" class="btn btn-success btn-lg px-5 shadow"
                                style="border-radius: 30px;">
                                <i class="fas fa-save me-2"></i> Simpan Seluruh Laporan
                            </button>
                        </div>
                        </form>
                    @endif
                @empty
                    <div class="card shadow-sm text-center py-5">
                        <div class="card-body">
                            <i class="fas fa-clipboard-check fa-4x text-muted mb-3"></i>
                            <p class="text-muted h5">Tidak ada item safety untuk tanggal ini.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let activeStreams = {};

            function applyStatusStyles(selectEl) {
                if (!selectEl) return;
                const val = selectEl.value;
                const itemId = selectEl.getAttribute('data-item-id');
                const card = selectEl.closest('.safety-card');
                const badge = document.querySelector('.status-badge-' + itemId);

                // Perbarui visual Card
                card.classList.remove('status-ok', 'status-ng');
                if (val === 'OK') card.classList.add('status-ok');
                else if (val === 'NG') card.classList.add('status-ng');

                // Perbarui Badge
                if (badge) {
                    badge.className = `badge rounded-pill status-badge-${itemId} `;
                    if (val === 'OK') badge.classList.add('bg-success');
                    else if (val === 'NG') badge.classList.add('bg-danger');
                    else badge.classList.add('bg-secondary');
                    badge.textContent = val;
                }
            }

            document.querySelectorAll('.select-status').forEach(selectEl => {
                selectEl.addEventListener('change', () => applyStatusStyles(selectEl));
            });

            // Fungsionalitas kamera (dipertahankan dari kode asli dengan perbaikan UI preview)
            document.querySelectorAll('.open-camera-btn').forEach(btn => {
                btn.addEventListener('click', async function() {
                    const index = this.getAttribute('data-index');
                    const videoElement = document.querySelector(`.camera-preview-${index}`);
                    const cameraContainer = document.querySelector(
                    `.camera-container-${index}`);

                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({
                            video: {
                                facingMode: 'environment'
                            },
                            audio: false
                        });
                        videoElement.srcObject = stream;
                        activeStreams[index] = stream;
                        cameraContainer.classList.remove('d-none');
                    } catch (error) {
                        alert('Gagal mengakses kamera.');
                    }
                });
            });

            document.querySelectorAll('.capture-photo-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = this.getAttribute('data-index');
                    const videoElement = document.querySelector(`.camera-preview-${index}`);
                    const canvas = document.createElement('canvas');
                    canvas.width = videoElement.videoWidth;
                    canvas.height = videoElement.videoHeight;
                    canvas.getContext('2d').drawImage(videoElement, 0, 0);

                    canvas.toBlob(blob => {
                        const file = new File([blob], `photo-${Date.now()}.jpg`, {
                            type: 'image/jpeg'
                        });
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        document.querySelector(`.file-input-${index}`).files = dataTransfer
                            .files;

                        const previewContainer = document.querySelector(
                            `.image-preview-${index}`);
                        previewContainer.innerHTML = `
                        <div class="preview-img-container">
                            <img src="${canvas.toDataURL('image/jpeg')}" class="img-fluid rounded border" style="max-height: 150px;"/>
                            <div class="mt-1 small text-primary"><i class="fas fa-camera"></i> Foto baru diambil</div>
                        </div>`;
                        stopCamera(index);
                    }, 'image/jpeg', 0.8);
                });
            });

            document.querySelectorAll('.close-camera-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    stopCamera(this.getAttribute('data-index'));
                });
            });

            function stopCamera(index) {
                if (activeStreams[index]) {
                    activeStreams[index].getTracks().forEach(track => track.stop());
                    delete activeStreams[index];
                }
                document.querySelector(`.camera-container-${index}`).classList.add('d-none');
            }

            // Preview untuk Choose File
            document.querySelectorAll('[type="file"]').forEach(input => {
                input.addEventListener('change', function() {
                    const index = this.getAttribute('data-index');
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = e => {
                            document.querySelector(`.image-preview-${index}`).innerHTML = `
                            <div class="preview-img-container">
                                <img src="${e.target.result}" class="img-fluid rounded border" style="max-height: 150px;"/>
                                <div class="mt-1 small text-primary"><i class="fas fa-file-upload"></i> File dipilih</div>
                            </div>`;
                        };
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            });

            // --- Validasi submit form ---
            const form = document.getElementById('bulk-safety-report');
            if (form) {
                const scrollInput = document.querySelector('.scroll-pos-input');

                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const selects = document.querySelectorAll('.select-status');
                    let itemKosong = null;
                    let jumlahKosong = 0;

                    selects.forEach((select) => {
                        const card = select.closest('.safety-card');
                        if (select.value === 'UNKNOWN') {
                            jumlahKosong++;
                            card.style.borderLeft = '10px solid #dc3545';
                            card.classList.add('shadow-danger');
                            if (!itemKosong) itemKosong = card;
                        } else {
                            card.style.borderLeft = '';
                            card.classList.remove('shadow-danger');
                        }
                    });

                    if (jumlahKosong > 0) {
                        // Tidak ada scroll otomatis ke item error
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Belum Lengkap! Tolong Lengkapi Dulu Ya..',
                                text: 'Masih ada item yang statusnya Belum Dicek.',
                                icon: 'warning',
                                confirmButtonText: 'Cek Lagi',
                                confirmButtonColor: '#3d5afe',
                                buttonsStyling: true,
                                showClass: {
                                    popup: 'animate__animated animate__fadeInDown animate__faster'
                                },
                                hideClass: {
                                    popup: 'animate__animated animate__fadeOutUp animate__faster'
                                },
                                customClass: {
                                    popup: 'rounded-4 shadow'
                                }
                            });
                        } else {
                            alert(`Masih ada ${jumlahKosong} item yang belum dicek!`);
                        }
                    } else {
                        // Jika semua sudah diisi, konfirmasi simpan
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Simpan Laporan?',
                                icon: 'success',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, Simpan!',
                                cancelButtonText: 'Cek Lagi',
                                confirmButtonColor: '#28a745',
                                cancelButtonColor: '#3d5afe',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Sebelum submit, simpan posisi ke hidden input agar bisa di-restore setelah reload
                                    if (scrollInput) scrollInput.value = window.scrollY;
                                    form.submit();
                                }
                            });
                        } else {
                            if (confirm('Simpan laporan?')) form.submit();
                        }
                    }
                });
            }
        });
    </script>
@endpush
