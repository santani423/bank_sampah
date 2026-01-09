<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>PDF Pencairan Lapak</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .info {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #eee;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Pencairan Saldo Lapak</h2>
        <p>Tanggal: {{ $tanggal ?? date('d-m-Y') }}</p>
    </div>
    <div class="info">
        <strong>Nama Lapak:</strong> {{ $lapak->nama_lapak ?? '-' }}<br>
        <strong>Alamat:</strong> {{ $lapak->alamat ?? '-' }}<br>
        <strong>No. Rekening:</strong> {{ $lapak->nomor_rekening ?? '-' }}<br>
        <strong>Bank:</strong> {{ $bank->nama ?? '-' }}
    </div>
    @foreach ($pengiriman->detailPengirimanLapaks ?? [] as $index => $detailPengirimanLapaks)
        <span style="color: #0a0a0a;">{{ $detailPengirimanLapaks->transaksiLapak->kode_transaksi ?? '-' }}</span>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Sampah</th>
                    <th>Berat Awal (kg)</th>
                    <th>Berat Terupdate (kg)</th>
                    <th>Harga per Kg</th>
                    <th>Subtotal</th>
                    <th>Susut</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detailPengirimanLapaks->transaksiLapak->detailTransaksiLapak ?? [] as $idx => $item)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td>{{ $item->sampah->nama_sampah ?? '-' }}</td>
                        <td>{{ number_format($item->berat_kg, 2, ',', '.') }}</td>
                        <td>
                            {{ $item->berat_terupdate ?? $item->berat_kg }}
                        </td>
                        <td>Rp {{ number_format($item->harga_per_kg, 0, ',', '.') }}</td>
                        <td>
                            <span id="subtotal-{{ $detailPengirimanLapaks->id }}-{{ $item->id }}">
                                Rp
                                {{ number_format(($item->berat_terupdate ?? $item->berat_kg) * $item->harga_per_kg, 0, ',', '.') }}
                            </span>
                        </td>
                        <td>
                            <span id="susut-{{ $detailPengirimanLapaks->id }}-{{ $item->id }}">
                                {{ number_format($item->berat_kg - ($item->berat_terupdate ?? $item->berat_kg), 2, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
    <br>
    <strong>Total Pencairan: Rp {{ number_format($total ?? 0, 0, ',', '.') }}</strong>
</body>

</html>
