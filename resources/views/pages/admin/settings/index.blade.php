@extends('layouts.template')

@section('title', 'Setting')

@section('main')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.settings.update', $setting->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- =========================
                    INFO UMUM
                ========================== --}}
                <h5 class="mb-3"><i class="bi bi-gear me-2"></i> Informasi Umum</h5>

                {{-- Nama Aplikasi --}}
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-card-text me-1"></i> Nama Aplikasi</label>
                    <input type="text" name="nama" value="{{ old('nama', $setting->nama) }}" class="form-control" required>
                </div>

                {{-- Title --}}
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-type me-1"></i> Judul (Title)</label>
                    <input type="text" name="title" value="{{ old('title', $setting->title) }}" class="form-control">
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-text-left me-1"></i> Deskripsi</label>
                    <input type="text" name="description" value="{{ old('description', $setting->description) }}" class="form-control">
                </div>

                {{-- Keywords --}}
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-tags me-1"></i> Keywords</label>
                    <input type="text" name="keywords" value="{{ old('keywords', $setting->keywords) }}" class="form-control">
                </div>

                {{-- =========================
                    LOGO & FAVICON
                ========================== --}}
                <h5 class="mt-4 mb-3"><i class="bi bi-image me-2"></i> Logo dan Favicon</h5>

                {{-- Logo --}}
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-image me-1"></i> Logo</label>
                    <input type="file" name="logo" class="form-control" id="logoInput" accept="image/*">
                    <div id="logoPreview" class="mt-3">
                        @if ($setting->logo)
                            <img src="{{ asset($setting->logo) }}" alt="Logo Sekarang" class="rounded border" height="80">
                        @endif
                    </div>
                </div>

                {{-- Favicon --}}
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-image me-1"></i> Favicon</label>
                    <input type="file" name="favicon" class="form-control" id="faviconInput" accept="image/*">
                    <div id="faviconPreview" class="mt-3">
                        @if ($setting->favicon)
                            <img src="{{ asset($setting->favicon) }}" alt="Favicon Sekarang" class="rounded border" height="50">
                        @endif
                    </div>
                </div>

                {{-- =========================
                    KONTAK & MEDIA SOSIAL
                ========================== --}}
                <h5 class="mt-4 mb-3"><i class="bi bi-person-lines-fill me-2"></i> Kontak & Media Sosial</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-geo-alt me-1"></i> Alamat</label>
                        <input type="text" name="address" value="{{ old('address', $setting->address) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-telephone me-1"></i> Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $setting->phone) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-envelope me-1"></i> Email</label>
                        <input type="email" name="email" value="{{ old('email', $setting->email) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-geo me-1"></i> Google Map (Embed Link)</label>
                        <input type="text" name="google_map" value="{{ old('google_map', $setting->google_map) }}" class="form-control">
                    </div>
                </div>

                {{-- Sosial Media --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-whatsapp me-1"></i> WhatsApp</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $setting->whatsapp) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-instagram me-1"></i> Instagram</label>
                        <input type="text" name="instagram" value="{{ old('instagram', $setting->instagram) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-twitter me-1"></i> Twitter</label>
                        <input type="text" name="twitter" value="{{ old('twitter', $setting->twitter) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-youtube me-1"></i> YouTube</label>
                        <input type="text" name="youtube" value="{{ old('youtube', $setting->youtube) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-tiktok me-1"></i> TikTok</label>
                        <input type="text" name="tiktok" value="{{ old('tiktok', $setting->tiktok) }}" class="form-control">
                    </div>
                </div>

                {{-- =========================
                    PENGATURAN TAMBAHAN
                ========================== --}}
                <h5 class="mt-4 mb-3"><i class="bi bi-cash me-2"></i> Pengaturan Tambahan</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-arrow-down-circle me-1"></i> Minimal Penarikan (Rp)</label>
                        <input type="number" name="min_penarikan" value="{{ old('min_penarikan', $setting->min_penarikan) }}" class="form-control" step="0.01">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-arrow-up-circle me-1"></i> Maksimal Penarikan (Rp)</label>
                        <input type="number" name="max_penarikan" value="{{ old('max_penarikan', $setting->max_penarikan) }}" class="form-control" step="0.01">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-whatsapp me-1"></i> Nomor WA Notifikasi</label>
                        <input type="text" name="no_notifikasi" value="{{ old('no_notifikasi', $setting->no_notifikasi) }}" class="form-control">
                    </div>
                </div>

                {{-- =========================
                    BUTTON SIMPAN
                ========================== --}}
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script untuk preview logo & favicon --}}
@push('scripts')
<script>
    function previewImage(inputId, previewId, height = 80) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        input.addEventListener('change', function(event) {
            preview.innerHTML = '';
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('rounded', 'border', 'mt-2');
                    img.style.height = height + 'px';
                    preview.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        });
    }

    previewImage('logoInput', 'logoPreview', 80);
    previewImage('faviconInput', 'faviconPreview', 50);
</script>
@endpush
@endsection
