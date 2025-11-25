@extends('layouts.template')
@section('content')
<div class="container">
    <h1>Approval Setoran Lapak</h1>
    <p>Daftar setoran lapak yang menunggu approval.</p>
    <table class="table">
        <thead>
            <tr>
                <th>Kode Transaksi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="lapak-table-body">
              <!-- Data akan diisi oleh JS -->
        </tbody>
    </table>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const lapakId = 1; // Ganti dengan id lapak yang sesuai jika dinamis
    fetch(`/api/lapak/${lapakId}/transaksi`)
        .then(response => response.json())
        .then(res => {
            const tbody = document.getElementById('lapak-table-body');
            tbody.innerHTML = '';
            if (res.data && res.data.length > 0) {
                res.data.forEach(trx => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${trx.kode_transaksi ?? '-'}</td>
                            <td>${trx.status ?? '-'}</td>
                            <td>
                                <a href="/admin/lapak/${trx.lapak_id}" class="btn btn-info btn-sm">Detail</a>
                                ${trx.status === 'pending' ? `
                                    <form action="/admin/data-lapak/${trx.lapak_id}/approve" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                    <form action="/admin/data-lapak/${trx.lapak_id}/reject" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                    </form>
                                ` : ''}
                            </td>
                        </tr>
                    `;
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="3">Tidak ada data transaksi.</td></tr>';
            }
        })
        .catch(() => {
            document.getElementById('lapak-table-body').innerHTML = '<tr><td colspan="3">Gagal memuat data.</td></tr>';
        });
});
</script>
@endsection