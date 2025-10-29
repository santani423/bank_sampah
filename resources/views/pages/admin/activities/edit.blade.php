@extends('layouts.template')

@section('title', 'Edit Activity')

@push('style')
<style>
    .ck-editor__editable_inline {
        min-height: 200px;
    }
    #imagePreview {
        max-height: 200px;
        border-radius: 8px;
    }
</style>
@endpush

@section('main')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4>Edit Activity</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.activities.update', $activity->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" id="title" value="{{ old('title', $activity->title) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description', $activity->description) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea name="content" id="content" class="form-control">{{ old('content', $activity->content) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" id="start_date" value="{{ old('start_date', $activity->start_date) }}">
                    </div>

                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" id="end_date" value="{{ old('end_date', $activity->end_date) }}">
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" id="location" value="{{ old('location', $activity->location) }}">
                    </div>

                    <div class="mb-3">
                        <label for="label_id" class="form-label">Label</label>
                        <select name="label_id" id="label_id" class="form-select">
                            <option value="">-- Pilih Label --</option>
                            @foreach($labels as $label)
                                <option value="{{ $label->id }}" {{ old('label_id', $activity->label_id) == $label->id ? 'selected' : '' }}>
                                    {{ $label->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="active" {{ old('status', $activity->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $activity->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" id="image" accept="image/*">
                        <div class="mt-2">
                            @if($activity->image)
                                <img id="imagePreview" src="{{ asset($activity->image) }}" alt="Preview Gambar">
                            @else
                                <img id="imagePreview" src="#" alt="Preview Gambar" style="display:none;">
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- CKEditor 5 Classic -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    // ClassicEditor
    //     .create( document.querySelector( '#description' ) )
    //     .catch( error => { console.error( error ); } );

    ClassicEditor
        .create( document.querySelector( '#content' ) )
        .catch( error => { console.error( error ); } );

    // Preview gambar sebelum submit
    document.getElementById('image').addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            const preview = document.getElementById('imagePreview');
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    });
</script>
@endpush
