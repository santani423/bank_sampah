@extends('layouts.template')

@section('title', 'Tambah Clean')

@push('style')
    <!-- CSS Libraries -->
    <style>
        .image-preview {
            margin-top: 15px;
            max-width: 300px;
            max-height: 200px;
            border-radius: 10px;
            object-fit: cover;
            display: none;
            border: 2px solid #eee;
        }
    </style>
@endpush

@section('main')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Tambah Clean</h3>
        <h6 class="op-7 mb-2">Isi data berikut untuk menambahkan Clean baru.</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('admin.cleans.index') }}" class="btn btn-secondary btn-round">Kembali</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Terjadi kesalahan!</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.cleans.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="title">Judul</label>
                        <input type="text" name="title" id="title" class="form-control"
                            placeholder="Masukkan judul clean" value="{{ old('title') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="slug">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control"
                            placeholder="Slug otomatis atau manual" value="{{ old('slug') }}">
                        <small class="text-muted">Slug digunakan untuk URL unik (misal: clean-ramah-lingkungan)</small>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Deskripsi</label>
                        <textarea name="description" id="description" rows="4" class="form-control"
                            placeholder="Tulis deskripsi singkat...">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="image">Gambar</label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/*">
                        <small class="text-muted d-block mt-1">Format: JPG, PNG, JPEG, atau WEBP (maks 2MB)</small>

                        <!-- Preview Gambar -->
                        <img id="preview-image" class="image-preview mt-3" alt="Preview Gambar">
                    </div>

                    <div class="form-group mb-3">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                    <div class="form-group text-end mt-4">
                        <button type="submit" class="btn btn-primary btn-round">Simpan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-generate slug dari title
    document.getElementById('title').addEventListener('input', function() {
        let slug = this.value
            .toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-');
        document.getElementById('slug').value = slug;
    });

    // Preview gambar upload
    const imageInput = document.getElementById('image');
    const previewImage = document.getElementById('preview-image');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Validasi tipe file gambar
            if (!file.type.startsWith('image/')) {
                alert('File harus berupa gambar (jpg, png, jpeg, webp)');
                this.value = '';
                previewImage.style.display = 'none';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            previewImage.style.display = 'none';
        }
    });
</script>
@endpush
 