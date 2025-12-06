@extends('layouts.template')
@section('title', 'Approval Setoran Lapak')
@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Approval Setoran Lapak</h3>
            <h6 class="op-7 mb-2">Daftar setoran lapak yang menunggu approval.
            </h6>
        </div>
    </div>


    {{-- ✅ ALERT MESSAGE --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">


            <div class="card">
                <div class="card-body">
                    <div class="clearfix mb-3"></div>

                    <div class="table-responsive">
                        <table class="table table-striped align-middle" style="color:#000;">
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
                </div>
            </div>
        </div>
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
                                detailHtml = '<ul style="padding-left:16px;color:#000">';
                                trx.detail_transaksi.forEach(d => {
                                    detailHtml +=
                                        `<li>Berat: ${d.berat_kg} kg, Harga/kg: Rp${parseInt(d.harga_per_kg).toLocaleString()}, Total: Rp${parseInt(d.total_harga).toLocaleString()}</li>`;
                                });
                                detailHtml += '</ul>';
                            } else {
                                detailHtml = '-';
                            }
                            tbody.innerHTML += `
                        <tr>
                            <td style="color:#000">${trx.kode_transaksi ?? '-'}</td>
                            <td style="color:#000">${trx.tanggal_transaksi ?? '-'}</td>
                            <td style="color:#000">Rp${parseInt(trx.total_transaksi).toLocaleString()}</td>
                            <td style="color:#000">${trx.status ?? '-'}</td>
                            <td style="color:#000">${detailHtml}</td>
                            <td>
                                <a href="/admin/setor-lapak/${trx.kode_transaksi ?? ''}" class="btn btn-info btn-sm">Detail</a>
                              
                                                            
                            </td>
                        </tr>
                    `;
                        });
                    } else {
                        tbody.innerHTML =
                            '<tr><td colspan="6" style="color:#000">Tidak ada data transaksi.</td></tr>';
                    }
                })
                .catch(() => {
                    document.getElementById('lapak-table-body').innerHTML =
                        '<tr><td colspan="3" style="color:#000">Gagal memuat data.</td></tr>';
                });
        });
    </script>
@endpush
