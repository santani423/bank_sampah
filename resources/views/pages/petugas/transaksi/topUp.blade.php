@extends('layouts.template')

@section('title', 'Top Up Saldo')

@push('style')
    <style>
        .card {
            max-width: 500px;
            margin: 30px auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
            background-color: #fff;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .amount-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 10px;
        }

        .amount-button {
            flex: 1 1 calc(50% - 10px);
            background-color: #f1f1f1;
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            border-radius: 5px;
            transition: all 0.2s;
        }

        .amount-button:hover,
        .amount-button.selected {
            background-color: #6777ef;
            color: #fff;
            border-color: #6777ef;
        }

        button[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        .optional-label {
            font-size: 0.85rem;
            color: #888;
            margin-top: 5px;
        }
    </style>
@endpush

@section('main')
    <div class="card">
        <form action="{{ route('bayar.proses') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="nama">Nama Lengkap:</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', 'Contoh Nama') }}" required maxlength="255"
                    placeholder="Masukkan nama lengkap">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email', 'user@example.com') }}" required maxlength="255"
                    placeholder="contoh@email.com">
            </div>

            <div class="form-group">
                <label>Pilih Jumlah Pembayaran:</label>
                <div class="amount-buttons">
                    <div class="amount-button" onclick="setAmount(10000)">Rp 10.000</div>
                    <div class="amount-button" onclick="setAmount(20000)">Rp 20.000</div>
                    <div class="amount-button" onclick="setAmount(50000)">Rp 50.000</div>
                    <div class="amount-button" onclick="setAmount(100000)">Rp 100.000</div>
                </div>
                <span class="optional-label">Atau masukkan jumlah manual di bawah:</span>
            </div>

            <div class="form-group">
                <label for="jumlah">Jumlah Pembayaran (Rp):</label>
                <input type="number" id="jumlah" name="jumlah" value="{{ old('jumlah', 15000) }}" required min="1000"
                    placeholder="Minimal Rp 1.000">
            </div>

            <button type="submit">Bayar Sekarang</button>
        </form>
    </div>

    <script>
        function setAmount(value) {
            document.getElementById('jumlah').value = value;

            // Tambahkan kelas 'selected' ke tombol yang diklik
            document.querySelectorAll('.amount-button').forEach(button => {
                button.classList.remove('selected');
            });

            // Tambahkan ke tombol yang sesuai
            event.target.classList.add('selected');
        }
    </script>
@endsection
