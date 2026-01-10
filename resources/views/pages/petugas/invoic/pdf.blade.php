<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice Pengiriman Sampah ke Lapak</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #fff;
            color: #222;
            margin: 0;
            padding: 0;
        }
        .pdf-container {
            max-width: 700px;
            margin: 24px auto;
            padding: 24px 32px;
            border: 1px solid #bbb;
            border-radius: 8px;
            background: #fff;
        }
        .header {
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 12px;
            margin-bottom: 24px;
            text-align: center;
        }
        .header h2 {
            margin: 0;
            color: #4CAF50;
        }
        .info-table {
            width: 100%;
            margin-bottom: 24px;
        }
        .info-table td {
            padding: 4px 8px;
            vertical-align: top;
        }
        .section-title {
            font-weight: bold;
            margin-top: 24px;
            margin-bottom: 8px;
            color: #4CAF50;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .data-table th, .data-table td {
            border: 1px solid #bbb;
            padding: 6px 10px;
            text-align: center;
        }
        .data-table th {
            background: #e8f5e9;
            color: #222;
        }
        .footer {
            margin-top: 32px;
            text-align: right;
            font-size: 13px;
            color: #888;
        }
    </style>
</head>
<body>
<div class="pdf-container">
    <div class="header">
        <h2>Invoice Pengiriman Sampah ke Lapak</h2>
        <div style="font-size:14px; color:#555;">Tanggal: {{ $invoice->tanggal_pengiriman ?? '-' }}</div>
    </div>
    <table class="info-table">
        <tr>
            <td><b>Kode Pengiriman:</b> {{ $invoice->kode_pengiriman ?? '-' }}</td>
            <td><b>Petugas:</b> {{ $invoice->petugas->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td><b>Lapak Tujuan:</b> {{ $invoice->gudang->nama_gudang ?? '-' }}</td>
            <td><b>Alamat Lapak:</b> {{ $invoice->gudang->alamat ?? '-' }}</td>
        </tr>
    </table>
    @foreach ($invoice->detailPengirimanLapaks ?? [] as $i => $detail)
        <div class="section-title">Transaksi: {{ $detail->transaksiLapak->kode_transaksi ?? '-' }}</div>
        <table class="info-table">
            <tr>
                <td><b>Tanggal Transaksi:</b> {{ $detail->transaksiLapak->tanggal_transaksi ?? '-' }}</td>
                <td><b>Petugas:</b> {{ $invoice->petugas->nama ?? '-' }}</td>
            </tr>
        </table>
        <table class="data-table">
            <thead>
            <tr>
                <th>No</th>
                <th>Jenis Sampah</th>
                <th>Berat (kg)</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($detail->transaksiLapak->detailTransaksiLapak ?? [] as $j => $transaksiDetail)
                <tr>
                    <td>{{ $j + 1 }}</td>
                    <td style="text-align:left">{{ $transaksiDetail->sampah->nama_sampah ?? '-' }}</td>
                    <td>{{ $transaksiDetail->berat_kg ?? 0 }}</td>
                    <td>Rp {{ number_format($transaksiDetail->harga_per_kg ?? 0, 0, ',', '.') }}</td>
                    <td><b>Rp {{ number_format($transaksiDetail->total_harga ?? 0, 0, ',', '.') }}</b></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endforeach
    <div class="footer">
        Dicetak pada: {{ date('d-m-Y H:i') }}
    </div>
</div>
</body>
</html>
