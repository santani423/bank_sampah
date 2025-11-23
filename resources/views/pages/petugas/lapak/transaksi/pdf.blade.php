<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Transaksi Lapak PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #333; padding: 6px; text-align: left; }
        .table th { background: #eee; }
        .section-title { font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Detail Transaksi Lapak</h2>
    </div>
    <div>
        <strong>Kode Transaksi:</strong> {{ $transaksi->kode_transaksi }}<br>
        <strong>Tanggal:</strong> {{ $transaksi->tanggal_transaksi }}<br>
        <strong>Status:</strong> {{ $transaksi->status }}<br>
        <strong>Jumlah Total:</strong> Rp {{ number_format($transaksi->total_transaksi,0,',','.') }}<br>
        <strong>Petugas:</strong> {{ $transaksi->petugas->nama ?? '-' }}<br>
    </div>
    <div class="section-title">Detail Item Transaksi</div>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Sampah</th>
                <th>Berat (kg)</th>
                <th>Harga per Kg</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi->detail_transaksi as $idx => $detail)
            <tr>
                <td>{{ $idx+1 }}</td>
                <td>{{ $detail->sampah->nama_sampah ?? '-' }}</td>
                <td>{{ $detail->berat_kg }}</td>
                <td>Rp {{ number_format($detail->harga_per_kg,0,',','.') }}</td>
                <td>Rp {{ number_format($detail->total_harga,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
