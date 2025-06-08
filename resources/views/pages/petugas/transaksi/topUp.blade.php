@extends('layouts.template')

@section('title', 'Top Up Saldo')

@push('style')
    <style>
        .topup-option {
            cursor: pointer;
            transition: box-shadow 0.2s;
        }

        .topup-option.selected {
            box-shadow: 0 0 0 2px #6777ef;
            border-color: #6777ef;
            background: #f0f4ff;
        }
    </style>
@endpush

@section('main')
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="mb-0">Top Up Saldo</h4>
                </div>
                <div class="card-body">
                    <form id="topupForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Pilih Nominal Top Up</label>
                            <div class="row g-2">
                                @foreach ([50000, 100000, 200000, 500000, 1000000] as $nominal)
                                    <div class="col-6 col-md-4">
                                        <div class="card topup-option" data-value="{{ $nominal }}">
                                            <div class="card-body text-center py-3">
                                                <span class="h5 mb-0">Rp {{ number_format($nominal, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="manual_nominal" class="form-label">Atau Masukkan Nominal Lain</label>
                            <input type="number" min="1000" class="form-control" id="manual_nominal"
                                name="manual_nominal" placeholder="Masukkan nominal top up">
                        </div>
                        <input type="hidden" name="nominal" id="nominal">
                        <button type="button" class="btn btn-primary w-100" id="pay-button">Bayar Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Midtrans Snap.js -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        // Pilih nominal dari kartu
        const options = document.querySelectorAll('.topup-option');
        const manualInput = document.getElementById('manual_nominal');
        const nominalField = document.getElementById('nominal');

        options.forEach(option => {
            option.addEventListener('click', function () {
                options.forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');

                const value = this.getAttribute('data-value');
                nominalField.value = value;

                manualInput.value = ''; // Reset input manual
            });
        });

        // Jika input manual diketik, reset pilihan kartu
        manualInput.addEventListener('input', function () {
            options.forEach(o => o.classList.remove('selected'));
            nominalField.value = this.value;
        });

        // Bayar Sekarang
        document.getElementById('pay-button').addEventListener('click', function () {
            const nominal = nominalField.value;

            if (!nominal || parseInt(nominal) < 1000) {
                alert('Silakan pilih atau isi nominal top up terlebih dahulu.');
                return;
            }

            fetch('/petugas/midtrans/token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ nominal: nominal })
                })
                .then(res => res.json())
                .then(data => {
                    snap.pay(data.snap_token, {
                        onSuccess: function (result) {
                            console.log('Success', result);
                            alert("Pembayaran berhasil!");
                            location.reload();
                        },
                        onPending: function (result) {
                            console.log('Pending', result);
                            alert("Menunggu pembayaran...");
                        },
                        onError: function (result) {
                            console.log('Error', result);
                            alert("Terjadi kesalahan!");
                        },
                        onClose: function () {
                            alert("Pembayaran dibatalkan.");
                        }
                    });
                });
        });
    </script>
@endsection
