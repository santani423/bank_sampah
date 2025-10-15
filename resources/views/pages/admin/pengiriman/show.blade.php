@extends('layouts.template')

@section('title', 'Detail Pengiriman Sampah')

@push('style')
    <style>
        .preview-img {
            max-width: 150px;
            max-height: 150px;
            margin: 5px;
            border-radius: 5px;
            object-fit: cover;
            cursor: pointer;
            /* tanda bisa diklik */
            transition: transform 0.3s ease;
        }

        .file-link {
            display: block;
            margin: 5px 0;
            text-align: center;
        }

        /* Modal Styles */
        .img-modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            z-index: 1050;
            padding-top: 60px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
        }

        .img-modal-content {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 80%;
            transition: transform 0.3s ease;
        }

        .img-modal-close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }

        .zoom-btns {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
        }

        .zoom-btns button {
            padding: 5px 10px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Detail Pengiriman Sampah</h3>
            <h6 class="op-7 mb-2">Informasi lengkap mengenai pengiriman yang dilakukan.</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="{{ route('petugas.pengiriman.index') }}" class="btn btn-secondary btn-round">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-arrow-left me-1" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.498.498 0 0 1-.146-.354v-.004a.498.498 0 0 1 .146-.354l4-4a.5.5 0 1 1 .708.708L2.707 7.5H14.5A.5.5 0 0 1 15 8z" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold">
                    Informasi Pengiriman
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 40%">Kode Pengiriman</th>
                            <td>{{ $pengiriman->kode_pengiriman }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Pengiriman</th>
                            <td>{{ \Carbon\Carbon::parse($pengiriman->tanggal_pengiriman)->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <th>Cabang</th>
                            <td>{{ $pengiriman->cabang->nama_cabang ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Gudang</th>
                            <td>{{ $pengiriman->gudang->nama_gudang ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @switch($pengiriman->status_pengiriman)
                                    @case('draft')
                                        <span class="badge bg-secondary">Draft</span>
                                    @break

                                    @case('dikirim')
                                        <span class="badge bg-info text-white">Dikirim</span>
                                    @break

                                    @case('diterima')
                                        <span class="badge bg-success">Diterima</span>
                                    @break

                                    @case('batal')
                                        <span class="badge bg-danger">Batal</span>
                                    @break

                                    @default
                                        <span class="badge bg-light text-dark">Tidak Diketahui</span>
                                @endswitch
                            </td>
                        </tr>
                        <tr>
                            <th>Dibuat Pada</th>
                            <td>{{ \Carbon\Carbon::parse($pengiriman->created_at)->format('d-m-Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Catatan</th>
                            <td>{{ $pengiriman->catatan ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Sampah --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white fw-bold">
                    Detail Sampah Dikirim
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th style="width: 50px;">No</th>
                                    <th>Jenis Sampah</th>
                                    <th>Berat (Kg)</th>
                                    {{-- <th>Catatan</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pengiriman->detailPengiriman as $detail)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $detail->sampah->nama_sampah ?? '-' }}</td>
                                        <td class="text-end">{{ number_format($detail->berat_kg, 2) }}</td>
                                        {{-- <td>{{ $detail->catatan ?? '-' }}</td> --}}
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Tidak ada detail pengiriman
                                            sampah.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- File Pengiriman --}}
                    <div class="mt-4">
                        <h5 class="fw-bold">Dokumen & Gambar Pengiriman</h5>
                        <div class="d-flex flex-wrap gap-3">
                            @forelse($pengiriman->files as $file)
                                @php
                                    $ext = pathinfo($file->path_file, PATHINFO_EXTENSION);
                                @endphp
                                <div class="d-flex flex-column align-items-center" style="width: 150px;">
                                    @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']))
                                        <img src="{{ asset($file->path_file) }}" alt="{{ $file->nama_file }}"
                                            class="preview-img mb-1" onclick="openModal(this)">
                                        <span class="text-center small">{{ $file->refFile->nama_file }}</span>
                                    @else
                                        <a href="{{ asset($file->path_file) }}" target="_blank"
                                            class="file-link text-center small">
                                            {{ $file->refFile->nama_file }}
                                        </a>
                                    @endif
                                </div>
                            @empty
                                <p class="text-muted">Belum ada file pengiriman diupload.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-3 text-end">
                        <a href="{{ route('petugas.pengiriman.index') }}" class="btn btn-outline-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-arrow-left me-1" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M15 8a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.498.498 0 0 1-.146-.354v-.004a.498.498 0 0 1 .146-.354l4-4a.5.5 0 1 1 .708.708L2.707 7.5H14.5A.5.5 0 0 1 15 8z" />
                            </svg>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Image Viewer --}}
    <div id="imgModal" class="img-modal">
        <span class="img-modal-close" onclick="closeModal()">&times;</span>
        <img class="img-modal-content" id="modalImg">
        <div class="zoom-btns">
            <button onclick="zoomIn()">+</button>
            <button onclick="zoomOut()">-</button>
            <button onclick="resetZoom()">Reset</button>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentScale = 1;

        function openModal(img) {
            const modal = document.getElementById("imgModal");
            const modalImg = document.getElementById("modalImg");
            modal.style.display = "block";
            modalImg.src = img.src;
            currentScale = 1;
            modalImg.style.transform = `scale(${currentScale})`;
        }

        function closeModal() {
            document.getElementById("imgModal").style.display = "none";
        }

        function zoomIn() {
            currentScale += 0.2;
            document.getElementById("modalImg").style.transform = `scale(${currentScale})`;
        }

        function zoomOut() {
            if (currentScale > 0.4) {
                currentScale -= 0.2;
                document.getElementById("modalImg").style.transform = `scale(${currentScale})`;
            }
        }

        function resetZoom() {
            currentScale = 1;
            document.getElementById("modalImg").style.transform = `scale(${currentScale})`;
        }

        window.onclick = function(event) {
            const modal = document.getElementById("imgModal");
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
@endpush
