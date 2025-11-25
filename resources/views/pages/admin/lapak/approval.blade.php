@extends('layouts.template')
@section('main')
<div class="container">
    <h1>Approval Setoran Lapak</h1>
    <p>Daftar setoran lapak yang menunggu approval.</p>
    <table class="table">
        <thead>
            <tr>
                <th>Kode Transaksi</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
                <th>Detail Transaksi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="lapak-table-body">
              <!-- Data akan diisi oleh JS -->
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/lapak/transaksi')
        .then(response => response.json())
        .then(res => {
            const tbody = document.getElementById('lapak-table-body');
            tbody.innerHTML = '';
            if (res.data && res.data.length > 0) {
                res.data.forEach(trx => {
                    let detailHtml = '';
                    if (trx.detail_transaksi && trx.detail_transaksi.length > 0) {
                        detailHtml = '<ul style="padding-left:16px">';
                        trx.detail_transaksi.forEach(d => {
                            detailHtml += `<li>Berat: ${d.berat_kg} kg, Harga/kg: Rp${parseInt(d.harga_per_kg).toLocaleString()}, Total: Rp${parseInt(d.total_harga).toLocaleString()}</li>`;
                        });
                        detailHtml += '</ul>';
                    } else {
                        detailHtml = '-';
                    }
                    tbody.innerHTML += `
                        <tr>
                            <td>${trx.kode_transaksi ?? '-'}</td>
                            <td>${trx.tanggal_transaksi ?? '-'}</td>
                            <td>Rp${parseInt(trx.total_transaksi).toLocaleString()}</td>
                            <td>${trx.status ?? '-'}</td>
                            <td>${detailHtml}</td>
                            <td>
                                <a href="/admin/lapak/${trx.lapak_id ?? ''}" class="btn btn-info btn-sm">Detail</a>
                                ${trx.status === 'pending' ? `
                                    <form action="/admin/data-lapak/${trx.lapak_id ?? ''}/approve" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                    <form action="/admin/data-lapak/${trx.lapak_id ?? ''}/reject" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                    </form>
                                ` : ''}
                            </td>
                        </tr>
                    `;
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="6">Tidak ada data transaksi.</td></tr>';
            }
        })
        .catch(() => {
            document.getElementById('lapak-table-body').innerHTML = '<tr><td colspan="3">Gagal memuat data.</td></tr>';
        });
});
</script>
@endpush