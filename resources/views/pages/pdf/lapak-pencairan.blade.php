<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Pencairan Lapak</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
            letter-spacing: 0.5px;
        }

        .header p {
            margin: 4px 0 0;
            font-size: 11px;
        }

        .info {
            margin-bottom: 18px;
            line-height: 1.6;
        }

        .info strong {
            width: 120px;
            display: inline-block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th, td {
            border: 1px solid #333;
            padding: 6px 8px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        td {
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .transaction-title {
            margin-top: 15px;
            font-weight: bold;
        }

        .summary {
            width: 40%;
            margin-left: auto; /* membuat blok rata kanan */
            margin-top: 15px;
        }

        .summary table {
            border: none;
        }

        .summary td {
            border: none;
            padding: 4px 0;
        }

        .summary .label {
            text-align: left;
        }

        .summary .value {
            text-align: right;
            font-weight: bold;
        }

        .summary .total {
            border-top: 1px solid #333;
            padding-top: 6px;
        }
    </style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <h2>PENCAIRAN SALDO LAPAK</h2>
    <p>Tanggal: {{ $tanggal ?? date('d-m-Y') }}</p>
</div>

<!-- INFO LAPAK -->
<div class="info">
    <div><strong>Nama Lapak</strong>: {{ $lapak->nama_lapak ?? '-' }}</div>
    <div><strong>Alamat</strong>: {{ $lapak->alamat ?? '-' }}</div>
    <div><strong>No. Rekening</strong>: {{ $lapak->nomor_rekening ?? '-' }}</div>
    <div><strong>Bank</strong>: {{ $bank->nama ?? '-' }}</div>
</div>

<!-- DETAIL TRANSAKSI -->
@foreach ($pengiriman->detailPengirimanLapaks ?? [] as $detail)
    <div class="transaction-title">
        Kode Transaksi: {{ $detail->transaksiLapak->kode_transaksi ?? '-' }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Nama Sampah</th>
                <th width="12%">Berat Awal (Kg)</th>
                <th width="12%">Berat Update (Kg)</th>
                <th width="16%">Harga / Kg</th>
                <th width="18%">Subtotal</th>
                <th width="12%">Susut (Kg)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail->transaksiLapak->detailTransaksiLapak ?? [] as $index => $item)
                @php
                    $beratUpdate = $item->berat_terupdate ?? $item->berat_kg;
                    $subtotal   = $beratUpdate * $item->harga_per_kg;
                    $susut      = $item->berat_kg - $beratUpdate;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->sampah->nama_sampah ?? '-' }}</td>
                    <td class="text-right">{{ number_format($item->berat_kg, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($beratUpdate, 2, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga_per_kg, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($susut, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endforeach

<!-- RINGKASAN TOTAL (RATA KANAN) -->
<div class="summary">
    <table>
        <tr>
            <td class="label">Total</td>
            <td class="value">
                Rp {{ number_format($pencairan->jumlah_pencairan ?? 0, 0, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td class="label">Admin</td>
            <td class="value">
                Rp {{ number_format($pencairan->fee_net ?? 0, 0, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td class="label total">Total Dicairkan</td>
            <td class="value total">
                Rp {{ number_format($pencairan->total_pencairan ?? 0, 0, ',', '.') }}
            </td>
        </tr>
    </table>
</div>

</body>
</html>
