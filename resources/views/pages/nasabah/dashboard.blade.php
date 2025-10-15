@extends('layouts.template')

@section('title', 'Dashboard Nasabah')

@push('style')
    <!-- CSS Libraries -->
    <style>
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }

        .info-card {
            flex: 1 1 300px;
            background-color: #3B82F6;
            color: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            max-width: 350px;
        }

        .card-title {
            font-size: 1.25rem;         
            font-weight: 600;
        }

        .card-value {
            font-size: 2rem;
            /* margin-top: 0.5rem; */
        }
    </style>
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Dashboard</h3>
            {{-- <h6 class="op-7 mb-2">Rincian Data dan Transaksi Bank Sampah Rendole Pati</h6> --}}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-md-5">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Nasabah</p>
                                <h4 class="card-title">Rp {{ number_format($saldo->saldo ?? 0, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Petugas</p>
                                <h4 class="card-title">Rp {{ number_format($topup ?? 0, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-warning bubble-shadow-small">
                                <i class="fas fa-recycle"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Sampah</p>
                                <h4 class="card-title">{{ $totalBerat }} Kg</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
   
@endsection

@push('scripts')
    <!-- JS Libraries -->

    <!-- Page Specific JS File -->
@endpush
