@extends('dashboard.layouts.index')

@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Laporan Harian Environment</h3>
            <h6 class="op-7 mb-2">Tanggal: {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <form method="GET" action="{{ route('user.daily.environment') }}" class="d-flex gap-2">
                <input type="date" name="date" value="{{ $date }}" class="form-control" style="max-width: 200px" />
                <button class="btn btn-primary">Pilih</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Environment</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success py-2 mb-3" style="font-size: 0.9rem;">
                            {{ session('success') }}
                        </div>
                    @endif

                    @forelse($environments as $index => $item)
                        @if($index === 0)
                            <form id="bulk-environment-report" action="{{ route('user.daily.store-multiple') }}" method="POST" enctype="multipart/form-data" class="bulk-report-form">
                                @csrf
                                <input type="hidden" name="subject_type" value="environment" />
                                <input type="hidden" name="date" value="{{ $date }}" />
                                <input type="hidden" name="scroll_y" value="0" class="scroll-pos-input" />
                        @endif

                        @php $key = App\Models\Environment::class . '_' . $item->id; $report = $reports[$key] ?? null; @endphp
                        
                        <div class="border rounded p-3 mb-3 environment-item-section" data-item-id="{{ $item->id }}">
                            <div class="mb-3">
                                <h5 class="mb-2"><span class="badge bg-primary me-2">{{ $loop->iteration }}</span>Environment Item</h5>
                                
                                <div class="alert alert-info py-2 mb-2">
                                    <div><strong>Safety Items:</strong> {{ $item->safety_items ?? '-' }}</div>
                                    <div><strong>Standard Items:</strong> {{ $item->standard_items ?? '-' }}</div>
                                </div>
                            </div>

                            @php $currentStatus = optional($report)->status ?? 'UNKNOWN'; @endphp
                            <input type="hidden" name="items[{{ $index }}][subject_id]" value="{{ $item->id }}" />

                            <div class="mb-2">
                                <label class="form-label fw-bold d-flex align-items-center gap-2">Status Laporan Anda
                                    <span class="badge rounded-pill status-badge-{{ $item->id }} {{ $currentStatus === 'OK' ? 'bg-success' : ($currentStatus === 'NG' ? 'bg-danger' : 'bg-secondary') }}">{{ $currentStatus }}</span>
                                </label>
                                <select name="items[{{ $index }}][status]" class="form-select {{ $currentStatus === 'OK' ? 'is-valid' : ($currentStatus === 'NG' ? 'is-invalid' : '') }} select-status" data-item-id="{{ $item->id }}">
                                    <option value="UNKNOWN" {{ optional($report)->status==='UNKNOWN' || !$report ? 'selected' : '' }}>? (Belum Dicek)</option>
                                    <option value="OK" {{ optional($report)->status==='OK' ? 'selected' : '' }}>OK</option>
                                    <option value="NG" {{ optional($report)->status==='NG' ? 'selected' : '' }}>NG</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Foto (opsional)</label>
                                
                                <!-- Tombol Kamera dan Choose File -->
                                <div class="d-flex gap-2 mb-3">
                                    <button type="button" class="btn btn-primary flex-fill open-camera-btn" data-index="{{ $index }}">
                                        <i class="fas fa-camera me-1"></i> Ambil Foto
                                    </button>
                                    <label class="btn btn-outline-secondary flex-fill mb-0" style="cursor: pointer;">
                                        <i class="fas fa-folder-open me-1"></i> Pilih File
                                        <input type="file" name="items[{{ $index }}][image]" accept="image/*" class="d-none file-input-{{ $index }}" data-index="{{ $index }}" />
                                    </label>
                                </div>

                                <!-- Camera Preview -->
                                <div class="camera-container-{{ $index }} d-none">
                                    <div class="card border-primary mb-3">
                                        <div class="card-body p-2">
                                            <video class="camera-preview-{{ $index }} w-100 rounded" autoplay playsinline style="max-height: 300px; object-fit: cover; background: #000;"></video>
                                            <div class="d-flex gap-2 mt-2">
                                                <button type="button" class="btn btn-success flex-fill capture-photo-btn" data-index="{{ $index }}">
                                                    <i class="fas fa-check-circle me-1"></i> Ambil
                                                </button>
                                                <button type="button" class="btn btn-danger flex-fill close-camera-btn" data-index="{{ $index }}">
                                                    <i class="fas fa-times me-1"></i> Batal
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image Preview -->
                                <div class="image-preview-{{ $index }}">
                                    @if(optional($report)->image_path)
                                        <div class="card border mb-2">
                                            <div class="card-body p-2">
                                                <img src="{{ asset('storage/'. $report->image_path) }}" alt="evidence" class="w-100 rounded" style="max-height: 250px; object-fit: contain; background: #f5f5f5;"/>
                                                <div class="text-center mt-2">
                                                    <small class="text-muted"><i class="fas fa-check-circle text-success"></i> Foto tersimpan</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Catatan</label>
                                <textarea name="items[{{ $index }}][notes]" class="form-control" rows="2">{{ optional($report)->notes }}</textarea>
                            </div>
                        </div>

                        @if($loop->last)
                            <div class="mt-4">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                            </div>
                            </form>
                        @endif
                    @empty
                        <p class="text-muted">Tidak ada environment.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Let browser handle fragment scroll naturally
        history.scrollRestoration = 'manual';

        let activeStreams = {}; // Store active camera streams

        function applyStatusStyles(selectEl) {
            if (!selectEl) return;
            const val = selectEl.value;
            const itemId = selectEl.getAttribute('data-item-id');
            const badge = document.querySelector('.status-badge-' + itemId);

            // Update select validation state
            selectEl.classList.remove('is-valid', 'is-invalid');
            if (val === 'OK') selectEl.classList.add('is-valid');
            else if (val === 'NG') selectEl.classList.add('is-invalid');

            // Update badge color classes
            if (badge) {
                badge.classList.remove('bg-success', 'bg-danger', 'bg-secondary');
                if (val === 'OK') badge.classList.add('bg-success');
                else if (val === 'NG') badge.classList.add('bg-danger');
                else badge.classList.add('bg-secondary');
                badge.textContent = val;
            }
        }

        // Initialize and bind listeners for all status selects
        document.querySelectorAll('.bulk-report-form select[name*="[status]"]').forEach(selectEl => {
            applyStatusStyles(selectEl);
            selectEl.addEventListener('change', () => applyStatusStyles(selectEl));
        });

        // Camera functionality
        document.querySelectorAll('.open-camera-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const index = this.getAttribute('data-index');
                const videoElement = document.querySelector(`.camera-preview-${index}`);
                const cameraContainer = document.querySelector(`.camera-container-${index}`);

                try {
                    // Request camera access
                    const stream = await navigator.mediaDevices.getUserMedia({ 
                        video: { facingMode: 'environment' }, // Use back camera on mobile
                        audio: false 
                    });
                    
                    videoElement.srcObject = stream;
                    activeStreams[index] = stream;
                    cameraContainer.classList.remove('d-none');
                } catch (error) {
                    console.error('Error accessing camera:', error);
                    alert('Tidak dapat mengakses kamera. Pastikan browser memiliki izin kamera.');
                }
            });
        });

        document.querySelectorAll('.close-camera-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const index = this.getAttribute('data-index');
                stopCamera(index);
            });
        });

        document.querySelectorAll('.capture-photo-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const index = this.getAttribute('data-index');
                const videoElement = document.querySelector(`.camera-preview-${index}`);
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');

                canvas.width = videoElement.videoWidth;
                canvas.height = videoElement.videoHeight;

                context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);

                canvas.toBlob(function(blob) {
                    const file = new File([blob], `camera-photo-${Date.now()}.jpg`, { type: 'image/jpeg' });
                    
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    
                    const fileInput = document.querySelector(`.file-input-${index}`);
                    fileInput.files = dataTransfer.files;

                    const previewContainer = document.querySelector(`.image-preview-${index}`);
                    previewContainer.innerHTML = `
                        <div class="card border-success mb-2">
                            <div class="card-body p-2">
                                <img src="${canvas.toDataURL('image/jpeg')}" alt="captured" class="w-100 rounded" style="max-height: 250px; object-fit: contain; background: #f5f5f5;"/>
                                <div class="text-center mt-2">
                                    <small class="text-success"><i class="fas fa-check-circle"></i> Foto berhasil diambil</small>
                                </div>
                            </div>
                        </div>
                    `;

                    stopCamera(index);
                }, 'image/jpeg', 0.9);
            });
        });

        document.querySelectorAll('[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const index = this.getAttribute('data-index');
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewContainer = document.querySelector(`.image-preview-${index}`);
                        previewContainer.innerHTML = `
                            <div class="card border-success mb-2">
                                <div class="card-body p-2">
                                    <img src="${e.target.result}" alt="preview" class="w-100 rounded" style="max-height: 250px; object-fit: contain; background: #f5f5f5;"/>
                                    <div class="text-center mt-2">
                                        <small class="text-success"><i class="fas fa-check-circle"></i> File berhasil dipilih</small>
                                    </div>
                                </div>
                            </div>
                        `;
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });

        function stopCamera(index) {
            const stream = activeStreams[index];
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                delete activeStreams[index];
            }
            const cameraContainer = document.querySelector(`.camera-container-${index}`);
            if (cameraContainer) {
                cameraContainer.classList.add('d-none');
            }
        }

        window.addEventListener('beforeunload', function() {
            Object.keys(activeStreams).forEach(index => stopCamera(index));
        });
    });
</script>
@endpush
