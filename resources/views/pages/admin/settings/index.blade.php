@extends('layouts.template')

@section('title', 'Setting')

@section('main')
    <div class="container mt-4">
        <div class="card shadow-sm">
             
            <div class="card-body">
                <form action="{{ route('admin.settings.update', $setting->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Nama Aplikasi --}}
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-card-text me-1"></i> Nama Aplikasi</label>
                        <input type="text" name="nama" value="{{ old('nama', $setting->nama) }}" class="form-control"
                            required>
                    </div>

                    {{-- Logo --}}
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-image me-1"></i> Logo</label>
                        <input type="file" name="logo" class="form-control" id="logoInput" accept="image/*">

                        {{-- Preview Logo Baru --}}
                        <div id="logoPreview" class="mt-3">
                            @if ($setting->logo)
                                <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo Sekarang"
                                    class="rounded border" height="80">
                            @endif
                        </div>
                    </div>

                    {{-- Minimal Penarikan --}}
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-arrow-down-circle me-1"></i> Minimal Penarikan
                            (Rp)</label>
                        <input type="number" name="min_penarikan"
                            value="{{ old('min_penarikan', $setting->min_penarikan) }}" class="form-control" step="0.01">
                    </div>

                    {{-- Maksimal Penarikan --}}
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-arrow-up-circle me-1"></i> Maksimal Penarikan (Rp)</label>
                        <input type="number" name="max_penarikan"
                            value="{{ old('max_penarikan', $setting->max_penarikan) }}" class="form-control" step="0.01">
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script untuk preview logo --}}
    @push('scripts')
        <script>
            document.getElementById('logoInput').addEventListener('change', function(event) {
                const preview = document.getElementById('logoPreview');
                preview.innerHTML = ''; // Hapus preview sebelumnya

                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('rounded', 'border', 'mt-2');
                        img.style.height = '80px';
                        preview.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                }
            });
        </script>
    @endpush
@endsection
