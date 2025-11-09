<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi - {{ $transaksi->kode_transaksi }}</title>
    <style>
        :root {
            --primary: #1e88e5;
            --text: #222;
            --subtext: #555;
            --muted: #888;
            --border: #e5e7eb;
        }
        * { box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: var(--text);
            margin: 0; padding: 24px;
            background: #fff;
        }
        .sheet {
            max-width: 900px; margin: 0 auto; background: #fff;
            border: 1px solid var(--border); border-radius: 10px; overflow: hidden;
        }
        .header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 20px 24px; border-bottom: 1px solid var(--border);
        }
        .brand { font-weight: 700; font-size: 20px; color: var(--primary); }
        .meta { text-align: right; font-size: 12px; color: var(--subtext); }
        .section { padding: 16px 24px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .label { color: var(--muted); font-size: 12px; }
        .value { font-weight: 600; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid var(--border); padding: 8px 10px; font-size: 12px; }
        thead th { background: #f3f4f6; text-align: left; }
        tfoot td { font-weight: 700; }
        .total {
            display: flex; justify-content: flex-end; margin-top: 10px;
        }
        .total-box { width: 320px; border: 1px solid var(--border); border-radius: 8px; padding: 12px; }
        .total-row { display: flex; justify-content: space-between; margin: 6px 0; }
        .muted { color: var(--muted); }
        .footer { padding: 16px 24px; border-top: 1px solid var(--border); font-size: 12px; color: var(--subtext); text-align: center; }
        @media print {
            body { padding: 0; }
            .print-hide { display: none !important; }
            .sheet { border: none; }
            a[href]:after { content: ""; }
        }
        .actions { padding: 12px 24px; text-align: right; }
        .btn { padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; background: #fff; cursor: pointer; }
        .btn-primary { background: var(--primary); color: #fff; border-color: var(--primary); }
    </style>
</head>
<body>
    <div class="print-hide actions">
        <a href="{{ url()->previous() }}" class="btn">Kembali</a>
        <button onclick="window.print()" class="btn btn-primary">Cetak</button>
    </div>

    <div class="sheet">
        <div class="header">
            {{-- <div>
                <div class="brand">Bank Sampah Rendole Pati</div>
                <div class="muted" style="font-size: 12px;">Jl. ... No. ..., Pati | Telp: 08xx-xxxx-xxxx</div>
            </div> --}}
            <div class="meta">
                <div><strong>Kode:</strong> {{ $transaksi->kode_transaksi }}</div>
                <div><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y H:i') }} WIB</div>
            </div>
        </div>

        <div class="section grid">
            <div>
                <div class="label">Nasabah Badan</div>
                <div class="value">{{ $nasabahBadan->nama_badan }}</div>
                <div class="muted">{{ $nasabahBadan->jenisBadan->nama ?? '-' }}</div>
                <div class="muted">{{ $nasabahBadan->alamat_lengkap ?? '-' }}</div>
            </div>
            <div>
                <div class="label">Petugas</div>
                <div class="value">{{ $transaksi->petugas->nama ?? '-' }}</div>
                <div class="muted">{{ $transaksi->petugas->email ?? '-' }}</div>
            </div>
        </div>

        <div class="section">
            <div class="label">Rincian Sampah</div>
            <table>
                <thead>
                    <tr>
                        <th style="width:5%">No</th>
                        <th>Nama Sampah</th>
                        <th style="width:15%">Berat (kg)</th>
                        <th style="width:20%">Harga/Kg</th>
                        <th style="width:20%">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksi->detailTransaksi as $i => $d)
                        <tr>
                            <td style="text-align:center">{{ $i + 1 }}</td>
                            <td>{{ $d->sampah->nama_sampah ?? '-' }}</td>
                            <td style="text-align:center">{{ number_format($d->berat_kg, 2) }}</td>
                            <td style="text-align:right">Rp {{ number_format($d->harga_per_kg, 0, ',', '.') }}</td>
                            <td style="text-align:right">Rp {{ number_format($d->harga_total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; color:#888;">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($transaksi->detailTransaksi->count())
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align:right">Total</td>
                        <td style="text-align:center"><strong>{{ number_format($transaksi->detailTransaksi->sum('berat_kg'), 2) }}</strong></td>
                        <td></td>
                        <td style="text-align:right"><strong>Rp {{ number_format($transaksi->total_transaksi ?? 0, 0, ',', '.') }}</strong></td>
                    </tr>
                </tfoot>
                @endif
            </table>

            <div class="total">
                <div class="total-box">
                    <div class="total-row"><span>Subtotal</span><span>Rp {{ number_format($transaksi->detailTransaksi->sum('harga_total'), 0, ',', '.') }}</span></div>
                    <div class="total-row"><span class="muted">PPN</span><span class="muted">Rp 0</span></div>
                    <hr style="border:none; border-top:1px solid var(--border); margin:8px 0;">
                    <div class="total-row"><span><strong>Grand Total</strong></span><span><strong>Rp {{ number_format($transaksi->total_transaksi ?? 0, 0, ',', '.') }}</strong></span></div>
                </div>
            </div>
        </div>

        <div class="footer">
            Terima kasih telah berkontribusi dalam menjaga lingkungan. "Sampahmu, Masa Depan Kita".
        </div>
    </div>

    <script>
        // Otomatis cetak saat halaman dibuka (opsional)
        // window.print();
    </script>
</body>
</html>
