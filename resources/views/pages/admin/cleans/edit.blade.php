@extends('layouts.template')

@section('title', 'Edit Clean')

@push('style')
    <!-- CSS Libraries -->
    <style>
        .image-preview {
            margin-top: 15px;
            max-width: 300px;
            max-height: 200px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid #eee;
        }
    </style>
@endpush

@section('main')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Edit Clean</h3>
        <h6 class="op-7 mb-2">Ubah data berikut untuk memperbarui Clean.</h6>
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

                <form action="{{ route('admin.cleans.update', $clean->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="title">Judul</label>
                        <input type="text" name="title" id="title" class="form-control"
                            value="{{ old('title', $clean->title) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="slug">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control"
                            value="{{ old('slug', $clean->slug) }}">
                        <small class="text-muted">Slug digunakan untuk URL unik (misal: clean-ramah-lingkungan)</small>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Deskripsi</label>
                        <textarea name="description" id="description" rows="4" class="form-control"
                            placeholder="Tulis deskripsi singkat...">{{ old('description', $clean->description) }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="image">Gambar</label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/*">
                        <small class="text-muted d-block mt-1">Kosongkan jika tidak ingin mengganti gambar.</small>

                        {{-- Preview Gambar Lama --}}
                        @if ($clean->image)
                            <div class="mt-3">
                                <p class="mb-1"><strong>Gambar Saat Ini:</strong></p>
                                <img src="{{ asset('storage/' . $clean->image) }}" alt="Gambar Lama" class="image-preview">
                            </div>
                        @endif

                        {{-- Preview Gambar Baru --}}
                        <img id="preview-image" class="image-preview mt-3" alt="Preview Gambar Baru" style="display: none;">
                    </div>

                    <div class="form-group mb-3">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="active" {{ old('status', $clean->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', $clean->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                    <div class="form-group text-end mt-4">
                        <button type="submit" class="btn btn-primary btn-round">Perbarui</button>
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

    // Preview gambar baru jika diupload
    const imageInput = document.getElementById('image');
    const previewImage = document.getElementById('preview-image');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
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
