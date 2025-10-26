@extends('layouts.template')

@section('title', 'Edit Label')

@push('style')
<style>
    /* Agar teks input terlihat jelas */
    label {
        font-weight: 500;
    }
</style>
@endpush

@section('main')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Edit Label</h3>
        <h6 class="op-7 mb-2">Ubah data label sesuai kebutuhan.</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('admin.labels.index') }}" class="btn btn-secondary btn-round">Kembali ke List</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.labels.update', $label->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Label</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan nama label" value="{{ old('name', $label->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control" placeholder="Masukkan slug label" value="{{ old('slug', $label->slug) }}">
                    </div>

                    <div class="mb-3">
                        <label for="color" class="form-label">Warna</label>
                        <input type="color" name="color" id="color" class="form-control form-control-color" value="{{ old('color', $label->color ?? '#000000') }}">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Deskripsi label">{{ old('description', $label->description) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Label</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- JS Libraries jika ada -->
@endpush
