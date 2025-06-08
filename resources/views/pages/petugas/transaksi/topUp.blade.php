@extends('layouts.template')

@section('title', 'Top Up Saldo')

@push('style')
    <!-- CSS Libraries -->
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
<div class="row  ">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header">
                <h4 class="mb-0">Top Up Saldo</h4>
            </div>
            <div class="card-body">
                <form action=" " method="POST" id="topupForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Pilih Nominal Top Up</label>
                        <div class="row g-2">
                            @foreach([50000, 100000, 200000, 500000, 1000000] as $nominal)
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
                        <input type="number" min="1000" class="form-control" id="manual_nominal" name="manual_nominal" placeholder="Masukkan nominal top up">
                    </div>
                    <input type="hidden" name="nominal" id="nominal">
                    <button type="submit" class="btn btn-primary w-100">Top Up</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const options = document.querySelectorAll('.topup-option');
            const nominalInput = document.getElementById('nominal');
            const manualInput = document.getElementById('manual_nominal');

            options.forEach(option => {
                option.addEventListener('click', function () {
                    options.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    nominalInput.value = this.getAttribute('data-value');
                    manualInput.value = '';
                });
            });

            manualInput.addEventListener('input', function () {
                options.forEach(opt => opt.classList.remove('selected'));
                nominalInput.value = this.value;
            });

            // Optional: Prevent submit if nominal is empty or invalid
            document.getElementById('topupForm').addEventListener('submit', function(e) {
                if (!nominalInput.value || nominalInput.value < 1000) {
                    e.preventDefault();
                    alert('Silakan pilih atau masukkan nominal top up yang valid.');
                }
            });
        });
    </script>
@endpush
