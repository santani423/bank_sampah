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

        table.table td {
            color: #000;
            vertical-align: middle;
        }

        table.table th {
            color: #000;
        }

        table.table tbody tr:hover {
            background-color: #f8f9fa;
        }

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
    </div>

    <div id="alert-message"></div>

    <div class="card mb-4">
        <div class="card-body">
            <h5>Tambah User Face Secara Otomatis</h5>

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

    <div id="result-users" class="mt-4"></div>

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
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#form-add-user-face').on('submit', async function(e) {
        e.preventDefault();

        let jumlah = parseInt($('#jumlah').val());
        if (jumlah < 1) {
            alert('Jumlah user harus lebih dari 0');
            return;
        }

        $('#loading-spinner').show();
        $('#alert-message').html('');
        $('#result-users').html('');

        const batchSize = 10; // maksimal 10 user per hit
        const totalBatches = Math.ceil(jumlah / batchSize);
        let allUsers = [];

        try {
            for (let i = 0; i < totalBatches; i++) {
                const jumlahBatch = (i === totalBatches - 1) ? jumlah - (i * batchSize) : batchSize;

                // Hit API per batch
                const res = await $.ajax({
                    url: '/api/user-face/create?jumlah=' + jumlahBatch,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (res.success) {
                    allUsers = allUsers.concat(res.data);

                    // update tabel realtime
                    res.data.forEach(user => {
                        const avatar = user.foto ? user.foto : 'default-avatar.png';
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
                } else {
                    throw new Error(res.message);
                }
            }

            // tampilkan alert sukses
            $('#alert-message').html(`
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Berhasil generate ${allUsers.length} user.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);

            // tampilkan list user baru
            let html = '<ul class="list-group">';
            allUsers.forEach(u => {
                html += `<li class="list-group-item">${u.name} (${u.email})</li>`;
            });
            html += '</ul>';
            $('#result-users').html(html);

        } catch (error) {
            console.error(error);
            $('#alert-message').html(`
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Terjadi kesalahan: ${error.message || 'Gagal memproses data.'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
        } finally {
            $('#loading-spinner').hide();
        }
    });
});
</script>
@endpush
