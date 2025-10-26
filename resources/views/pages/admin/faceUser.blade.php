@extends('layouts.template')

@section('title', 'Anggota Tim')

@push('style')
    <style>
        .avatar-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
        }

        /* Warna teks dalam tabel */
        table.table td {
            color: #000; /* hitam */
            vertical-align: middle;
        }

        table.table th {
            color: #000;
        }

        /* Hover efek */
        table.table tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Spinner overlay */
        #loading-spinner {
            display: none;
            margin-bottom: 15px;
        }
    </style>
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Anggota Tim</h3>
            <h6 class="op-7 mb-2">Anda dapat mengelola semua anggota tim, seperti menambah, mengedit, dan menghapus data.</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <div class="section-header-button">
                <a href="{{ route('admin.time.create') }}" class="btn btn-primary btn-round">Tambah Anggota Tim Manual</a>
            </div>
        </div>
    </div>

    {{-- ALERT MESSAGE --}}
    <div id="alert-message"></div>

    {{-- FORM GENERATE USER FACE --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5>Tambah User Face Secara Otomatis</h5>

            {{-- Spinner --}}
            <div id="loading-spinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <span>Proses generate user...</span>
            </div>

            <form id="form-add-user-face">
                <div class="mb-3">
                    <label for="jumlah" class="form-label">Jumlah User</label>
                    <input type="number" id="jumlah" name="jumlah" class="form-control" min="1" value="1" required>
                </div>
                <button type="submit" class="btn btn-success">Generate User</button>
            </form>
        </div>
    </div>

    {{-- HASIL USER BARU --}}
    <div id="result-users" class="mt-4"></div>

    {{-- TABEL ANGGOTA TIM --}}
    <div class="card">
        <div class="card-body">
            <h5>Daftar Anggota Tim</h5>
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-head-bg-primary">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Avatar</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="table-users">
                        {{-- Data user bisa di-load dari controller atau Ajax --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#form-add-user-face').on('submit', function(e) {
            e.preventDefault();

            let jumlah = $('#jumlah').val();

            if (jumlah < 1) {
                alert('Jumlah user harus lebih dari 0');
                return;
            }

            // Tampilkan spinner
            $('#loading-spinner').show();

            $.ajax({
                url: '/api/user-face/create?jumlah=' + jumlah,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(res) {
                    // Sembunyikan spinner
                    $('#loading-spinner').hide();

                    if (res.success) {
                        $('#alert-message').html(`
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                ${res.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `);

                        // Tampilkan daftar user yang baru dibuat
                        let html = '<ul class="list-group">';
                        res.data.forEach(user => {
                            html += `<li class="list-group-item">${user.name} (${user.email})</li>`;

                            // Update tabel anggota tim
                            let avatar = user.foto ? user.foto : 'default-avatar.png';
                            $('#table-users').append(`
                                <tr>
                                    <td>${user.id}</td>
                                    <td><img src="/storage/${avatar}" class="avatar-img" alt="Avatar"></td>
                                    <td>${user.name}</td>
                                    <td>${user.email}</td>
                                    <td>${user.username}</td>
                                    <td>${user.status}</td>
                                </tr>
                            `);
                        });
                        html += '</ul>';

                        $('#result-users').html(html);

                    } else {
                        $('#alert-message').html(`
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                ${res.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `);
                    }
                },
                error: function(xhr) {
                    // Sembunyikan spinner
                    $('#loading-spinner').hide();

                    let errMsg = xhr.responseJSON?.message || 'Terjadi kesalahan sistem';
                    $('#alert-message').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ${errMsg}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                }
            });
        });
    });
</script>
@endpush
