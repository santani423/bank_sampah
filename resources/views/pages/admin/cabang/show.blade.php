@extends('layouts.template')

@section('title', 'Detail Cabang')

@push('style')
    <style>
        .card-container {
            display: flex;
            gap: 20px;
            justify-content: space-between;
        }

        .drag-card {
            width: 100%;
            min-height: 300px;
            border: 2px dashed #ccc;
            border-radius: 10px;
            padding: 15px;
            background-color: #f9f9f9;
            flex: 1;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .drag-card h5 {
            margin-bottom: 15px;
            font-weight: bold;
            color: #333;
        }

        .drag-item {
            padding: 12px 18px;
            margin-bottom: 10px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            cursor: grab;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        .drag-item:hover {
            background-color: #f0f8ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .drag-item.dragging {
            opacity: 0.6;
            border-color: #007bff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            cursor: grabbing;
        }

        .drag-over {
            background-color: #e0f7fa !important;
            border-color: #007bff !important;
        }

        #drag-status-message {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #eee;
            background-color: #f0f0f0;
            border-radius: 5px;
            color: #555;
            text-align: center;
        }
    </style>
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-2">
        <div>
            <h3 class="fw-bold mb-1">Detail Collation Center</h3>
            <p class="text-gray-600 mb-0">{{ $cabang->nama_cabang }} ({{ $cabang->kode_cabang }})</p>
        </div>
    </div>

    <!-- Detail Cabang -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 160px;">Kode  Collation Center</th>
                                <td>{{ $cabang->kode_cabang }}</td>
                            </tr>
                            <tr>
                                <th>Nama  Collation Center</th>
                                <td>{{ $cabang->nama_cabang }}</td>
                            </tr>
                            <tr>
                                <th>Telepon</th>
                                <td>{{ $cabang->telepon ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Berdiri</th>
                                <td>
                                    @if ($cabang->tanggal_berdiri)
                                        {{ optional($cabang->tanggal_berdiri)->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 160px;">Alamat</th>
                                <td>{{ $cabang->alamat }}</td>
                            </tr>
                            <tr>
                                <th>Kota</th>
                                <td>{{ $cabang->kota }}</td>
                            </tr>
                            <tr>
                                <th>Provinsi</th>
                                <td>{{ $cabang->provinsi }}</td>
                            </tr>
                            <tr>
                                <th>Kode Pos</th>
                                <td>{{ $cabang->kode_pos ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-3">
                <span class="badge {{ $cabang->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}">Status: {{ ucfirst($cabang->status) }}</span>
                <span class="badge bg-primary">Anggota: {{ $anggotaCabang->count() }}</span>
                <span class="badge bg-outline-secondary border">Di luar: {{ $anggotaLuar->count() }}</span>
                <span class="badge bg-light text-muted">Dibuat: {{ optional($cabang->created_at)->format('d M Y H:i') }}</span>
                <span class="badge bg-light text-muted">Diperbarui: {{ optional($cabang->updated_at)->format('d M Y H:i') }}</span>
            </div>

            <div class="mt-3">
                <a href="{{ route('admin.cabang.edit', $cabang->id) }}" class="btn btn-sm btn-main">Edit Cabang</a>
            </div>
        </div>
    </div>

    <!-- Nasabah di Cabang Ini -->
    <div class="card mb-4" id="nasabah-cabang-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Nasabah di Cabang Ini</h5>
                <div class="d-flex gap-2">
                    <input type="text" id="nasabah-search" class="form-control form-control-sm" placeholder="Cari nama / no registrasi" style="min-width: 260px;">
                    <select id="nasabah-per-page" class="form-select form-select-sm" style="width: 100px;">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-sm mb-0">
                    <thead>
                        <tr>
                            <th style="width:70px;">No</th>
                            <th>No. Registrasi</th>
                            <th>Nama</th>
                            <th>No HP</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="nasabah-tbody">
                        <tr>
                            <td colspan="5" class="text-center text-muted">Memuat data…</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted small" id="nasabah-summary"></div>
                <nav>
                    <ul class="pagination pagination-sm mb-0" id="nasabah-pagination"></ul>
                </nav>
            </div>
        </div>
    </div>

    <div class="card-container">
        <!-- Anggota Luar Cabang -->
        <div class="drag-card" id="anggota-luar">
            <h5>Anggota Luar Cabang</h5>
            @foreach ($anggotaLuar as $anggota)
                <div class="drag-item" id="anggota-luar-{{ $anggota->id }}" draggable="true" data-id="{{ $anggota->id }}">
                    {{ $anggota->nama }}
                </div>
            @endforeach
        </div>

        <!-- Anggota Cabang -->
        <div class="drag-card" id="anggota-cabang">
            <h5>Anggota Cabang</h5>
            @foreach ($anggotaCabang as $anggota)
                <div class="drag-item" id="anggota-cabang-{{ $anggota->id }}" draggable="true"
                    data-id="{{ $anggota->id }}">
                    {{ $anggota->nama }}
                </div>
            @endforeach
        </div>
    </div>

    <p id="drag-status-message"></p>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ========= NASABAH CABANG (API + Pagination) =========
            const cabangId = "{{ $id }}";
            const apiBase = `{{ url('/api/cabangs') }}/${cabangId}/nasabah`;
            const tbody = document.getElementById('nasabah-tbody');
            const summary = document.getElementById('nasabah-summary');
            const pagination = document.getElementById('nasabah-pagination');
            const searchInput = document.getElementById('nasabah-search');
            const perPageSelect = document.getElementById('nasabah-per-page');

            let state = {
                page: 1,
                per_page: parseInt(perPageSelect ? perPageSelect.value : 10, 10) || 10,
                search: ''
            };

            function renderRows(items, meta) {
                if (!items || items.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted">Belum ada data nasabah di cabang ini.</td></tr>`;
                    return;
                }
                const startNumber = (meta.current_page - 1) * meta.per_page;
                tbody.innerHTML = items.map((item, index) => `
                    <tr>
                        <td>${startNumber + index + 1}</td>
                        <td>${item.no_registrasi ?? '-'}</td>
                        <td>${item.nama_lengkap ?? '-'}</td>
                        <td>${item.no_hp ?? '-'}</td>
                        <td>
                            <span class="badge ${item.status === 'aktif' ? 'bg-success' : 'bg-secondary'}">${(item.status||'-').toString().charAt(0).toUpperCase() + (item.status||'-').toString().slice(1)}</span>
                        </td>
                    </tr>
                `).join('');
            }

            function renderPagination(meta) {
                // meta: current_page, last_page, total, per_page, from, to
                pagination.innerHTML = '';
                const { current_page, last_page } = meta;
                const createPageItem = (label, page, disabled = false, active = false) => `
                    <li class="page-item ${disabled ? 'disabled' : ''} ${active ? 'active' : ''}">
                        <a href="#" class="page-link" data-page="${page}">${label}</a>
                    </li>`;

                // Prev
                pagination.insertAdjacentHTML('beforeend', createPageItem('&laquo;', current_page - 1, current_page <= 1));

                // Page numbers (limit window)
                const windowSize = 5;
                let start = Math.max(1, current_page - Math.floor(windowSize / 2));
                let end = Math.min(last_page, start + windowSize - 1);
                if (end - start + 1 < windowSize) {
                    start = Math.max(1, end - windowSize + 1);
                }
                for (let p = start; p <= end; p++) {
                    pagination.insertAdjacentHTML('beforeend', createPageItem(p, p, false, p === current_page));
                }

                // Next
                pagination.insertAdjacentHTML('beforeend', createPageItem('&raquo;', current_page + 1, current_page >= last_page));
            }

            function renderSummary(meta) {
                const from = meta.from ?? 0;
                const to = meta.to ?? 0;
                const total = meta.total ?? 0;
                summary.textContent = `Menampilkan ${from}-${to} dari ${total} nasabah`;
            }

            async function loadNasabah(page = 1) {
                state.page = page;
                const params = new URLSearchParams({
                    page: state.page,
                    per_page: state.per_page
                });
                if (state.search) params.append('search', state.search);
                try {
                    tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted">Memuat data…</td></tr>`;
                    const res = await fetch(`${apiBase}?${params.toString()}`);
                    const data = await res.json();
                    renderRows(data.data || [], data);
                    renderPagination(data);
                    renderSummary(data);
                } catch (e) {
                    console.error(e);
                    tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Gagal memuat data.</td></tr>`;
                    pagination.innerHTML = '';
                    summary.textContent = '';
                }
            }

            // Events
            if (pagination) {
                pagination.addEventListener('click', (ev) => {
                    const link = ev.target.closest('a.page-link');
                    if (!link) return;
                    ev.preventDefault();
                    const targetPage = parseInt(link.dataset.page, 10);
                    if (!isNaN(targetPage)) loadNasabah(targetPage);
                });
            }
            if (perPageSelect) {
                perPageSelect.addEventListener('change', () => {
                    state.per_page = parseInt(perPageSelect.value, 10) || 10;
                    loadNasabah(1);
                });
            }
            if (searchInput) {
                let timer;
                searchInput.addEventListener('input', () => {
                    clearTimeout(timer);
                    timer = setTimeout(() => {
                        state.search = searchInput.value.trim();
                        loadNasabah(1);
                    }, 350);
                });
            }

            // Initial load
            loadNasabah(1);

            const dragStatusMessage = document.getElementById('drag-status-message');

            function drag(ev) {
                ev.dataTransfer.setData("text/plain", ev.target.id);
                ev.dataTransfer.effectAllowed = "move";
                ev.target.classList.add('dragging');
                if (dragStatusMessage) {
                    dragStatusMessage.innerHTML =
                        `Mulai menyeret: <strong>${ev.target.innerText}</strong>. Lepaskan untuk memindahkan.`;
                }
            }

            function allowDrop(ev) {
                ev.preventDefault();
                ev.dataTransfer.dropEffect = "move";
                ev.currentTarget.classList.add('drag-over');
            }

            function dragLeave(ev) {
                ev.currentTarget.classList.remove('drag-over');
            }

            function dragEnd(ev) {
                ev.target.classList.remove('dragging');
                if (dragStatusMessage && ev.dataTransfer.dropEffect === "none") {
                    dragStatusMessage.innerHTML =
                        `Seretan dibatalkan untuk <strong>${ev.target.innerText}</strong>.`;
                }
            }

            function drop(ev) {
                ev.preventDefault();

                const id = ev.dataTransfer.getData("text/plain");
                const draggableElement = document.getElementById(id);
                const dropZone = ev.currentTarget;

                if (draggableElement && dropZone !== draggableElement.parentNode) {
                    dropZone.appendChild(draggableElement);
                    // Ambil informasi anggota
                    const anggotaId = draggableElement.getAttribute('data-id');
                    const anggotaNama = draggableElement.innerText;
                    const targetArea = dropZone.querySelector('h5').innerText;

                    // Log ke konsol
                    console.log(`Dipindahkan: ID=${anggotaId}, Nama=${anggotaNama}, Ke=${targetArea}`);
                    // Kirim AJAX ke server untuk update status anggota
                    fetch("{{ route('admin.cabang.updateAanggotaCabang') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                anggota_id: anggotaId,
                                cabang_id: "{{$id}}",
                                target: targetArea === 'Anggota Cabang' ? true : false
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log("updateAanggotaCabang",data);
                            
                            // if (data.success) {
                            //     dragStatusMessage.innerHTML +=
                            //         "<br><span style='color:green'>Status anggota berhasil diperbarui.</span>";
                            // } else {
                            //     dragStatusMessage.innerHTML +=
                            //         "<br><span style='color:red'>Gagal memperbarui status anggota.</span>";
                            // }
                        })
                        .catch(error => {
                            dragStatusMessage.innerHTML +=
                                "<br><span style='color:red'>Terjadi kesalahan AJAX.</span>";
                            console.error(error);
                        });

                    if (dragStatusMessage) {
                        console.log("draggableElement", draggableElement);

                        dragStatusMessage.innerHTML =
                            `<strong>${draggableElement.innerText}</strong> berhasil dipindahkan ke <strong>${dropZone.querySelector('h5').innerText}</strong>.`;
                    }
                } else if (draggableElement && dragStatusMessage) {
                    dragStatusMessage.innerHTML =
                        `<strong>${draggableElement.innerText}</strong> dijatuhkan di lokasi yang sama.`;
                }

                draggableElement.classList.remove('dragging');
                dropZone.classList.remove('drag-over');
            }

            function refreshListeners() {
                document.querySelectorAll('.drag-item').forEach(item => {
                    item.removeEventListener('dragstart', drag);
                    item.removeEventListener('dragend', dragEnd);
                    item.addEventListener('dragstart', drag);
                    item.addEventListener('dragend', dragEnd);
                });
            }

            // Inisialisasi
            document.querySelectorAll('.drag-item').forEach(item => {
                item.addEventListener('dragstart', drag);
                item.addEventListener('dragend', dragEnd);
            });

            document.querySelectorAll('.drag-card').forEach(card => {
                card.addEventListener('dragover', allowDrop);
                card.addEventListener('drop', function(ev) {
                    drop(ev);
                    refreshListeners(); // Pastikan listener tetap aktif setelah elemen dipindahkan
                });
                card.addEventListener('dragleave', dragLeave);
            });
        });
    </script>
@endsection
