@props([
    'name' => 'image',
    'label' => 'Foto (opsional)',
    'existingImage' => null,
    'required' => false
])

<div class="mb-2">
    <label class="form-label">{{ $label }}</label>
    
    <div class="input-group mb-2">
        <input 
            type="file" 
            name="{{ $name }}" 
            accept="image/*" 
            capture="environment"
            class="form-control image-input" 
            id="file-input-{{ $name }}"
            {{ $required ? 'required' : '' }}
        />
    </div>

    <div class="btn-group w-100" role="group" style="display: flex; gap: 5px; margin-bottom: 10px;">
        <button 
            type="button" 
            class="btn btn-sm btn-secondary flex-grow-1" 
            onclick="openCamera('{{ $name }}')"
            title="Ambil foto menggunakan kamera"
        >
            <i class="fas fa-camera"></i> Kamera
        </button>
        <button 
            type="button" 
            class="btn btn-sm btn-secondary flex-grow-1" 
            onclick="openGallery('{{ $name }}')"
            title="Pilih dari galeri"
        >
            <i class="fas fa-images"></i> Galeri
        </button>
    </div>

    @if($existingImage)
        <div class="mt-2">
            <img src="{{ asset('storage/'. $existingImage) }}" alt="preview" style="max-height: 150px; border-radius: 4px;"/>
        </div>
    @endif

    <div id="preview-{{ $name }}" class="mt-2"></div>
</div>

<script>
    function openCamera(fieldName) {
        const input = document.getElementById('file-input-' + fieldName);
        input.setAttribute('capture', 'environment');
        input.click();
    }

    function openGallery(fieldName) {
        const input = document.getElementById('file-input-' + fieldName);
        input.removeAttribute('capture');
        input.click();
    }

    // Preview image on change
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('image-input')) {
            const fieldName = e.target.id.replace('file-input-', '');
            const file = e.target.files[0];
            const previewContainer = document.getElementById('preview-' + fieldName);
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewContainer.innerHTML = `
                        <img src="${event.target.result}" alt="preview" style="max-height: 150px; border-radius: 4px;"/>
                    `;
                };
                reader.readAsDataURL(file);
            }
        }
    });
</script>

<style>
    .input-group {
        position: relative;
    }

    .image-input {
        display: none;
    }

    .btn-group button {
        white-space: nowrap;
    }
</style>
