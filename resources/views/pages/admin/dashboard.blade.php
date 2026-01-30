@extends('layouts.template')

@section('title', 'Dashboard')

@push('style')
<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
    /* ====== Dashboard Card Styling ====== */
    .dashboard-title {
        font-weight: 700;
        font-size: 28px;
        color: #2d3436;
    }

    .dashboard-subtitle {
        color: #636e72;
        font-size: 15px;
    }

    .card-stats {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #ffffff, #f8f9fa);
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }

    .card-stats:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .icon-big {
        font-size: 2rem;
        border-radius: 50%;
        padding: 15px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .icon-primary { background: linear-gradient(135deg, #4e73df, #224abe); color: #fff; }
    .icon-info { background: linear-gradient(135deg, #36b9cc, #258fa6); color: #fff; }
    .icon-success { background: linear-gradient(135deg, #1cc88a, #13855c); color: #fff; }
    .icon-warning { background: linear-gradient(135deg, #f6c23e, #dda20a); color: #fff; }
    .icon-danger { background: linear-gradient(135deg, #e74a3b, #be2617); color: #fff; }
    .icon-secondary { background: linear-gradient(135deg, #858796, #5a5c69); color: #fff; }
    .icon-dark { background: linear-gradient(135deg, #343a40, #1d2124); color: #fff; }
    .icon-light { background: linear-gradient(135deg, #f8f9fc, #e9ecef); color: #6c757d; }

    .numbers p {
        margin-bottom: 4px;
        font-weight: 500;
        color: #7a7a7a;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 0.5px;
    }

    .numbers h4 {
        font-weight: 700;
        color: #2d3436;
        font-size: 22px;
    }

    /* Responsive */
    @media (max-width: 767px) {
        .card-stats {
            margin-bottom: 15px;
        }
    }
</style>
@endpush

@section('main')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="dashboard-title mb-3">Dashboard</h3>
        <h6 class="dashboard-subtitle">Rincian Data dan Transaksi Bank Sampah </h6>
    </div>
</div>

{{-- ====== Row 1 ====== --}}
<div class="row">
    @php
        $stats = [
            ['icon' => 'people-fill', 'color' => 'primary', 'label' => 'Total Nasabah', 'value' => $totalNasabah],
            ['icon' => 'person-badge-fill', 'color' => 'info', 'label' => 'Total Petugas', 'value' => $totalPetugas],
            ['icon' => 'recycle', 'color' => 'warning', 'label' => 'Total Sampah', 'value' => $totalSampahTerkumpul . " Kg"],
            ['icon' => 'receipt', 'color' => 'success', 'label' => 'Total Transaksi Setoran', 'value' => $totalTransaksiSetoran],
        ];
    @endphp

    @foreach ($stats as $stat)
        <div class="col-sm-6 col-md-3 mb-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-{{ $stat['color'] }}">
                                <i class="bi bi-{{ $stat['icon'] }}"></i>
                            </div>
                        </div>
                        <div class="col ms-3">
                            <div class="numbers">
                                <p class="card-category">{{ $stat['label'] }}</p>
                                <h4 class="card-title">{{ $stat['value'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- ====== Row 2 ====== --}}
<div class="row">
    @php
        $stats2 = [
            ['icon' => 'wallet2', 'color' => 'info', 'label' => 'Total Saldo Nasabah', 'value' => 'Rp ' . number_format($totalSaldoNasabah, 0, ',', '.')],
            ['icon' => 'cash-coin', 'color' => 'secondary', 'label' => 'Permintaan Tarik Saldo', 'value' => $totalPermintaanPencairan],
            ['icon' => 'chat-dots-fill', 'color' => 'dark', 'label' => 'Total Feedback Masuk', 'value' => $totalFeedbackMasuk],
            ['icon' => 'newspaper', 'color' => 'light', 'label' => 'Total Artikel', 'value' => $totalArtikel],
        ];
    @endphp

    @foreach ($stats2 as $stat)
        <div class="col-sm-6 col-md-3 mb-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-{{ $stat['color'] }}">
                                <i class="bi bi-{{ $stat['icon'] }}"></i>
                            </div>
                        </div>
                        <div class="col ms-3">
                            <div class="numbers">
                                <p class="card-category">{{ $stat['label'] }}</p>
                                <h4 class="card-title">{{ $stat['value'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- ====== Row 3 ====== --}}
<div class="row">
    @php
        $stats3 = [
            ['icon' => 'boxes', 'color' => 'danger', 'label' => 'Total Stok Sampah', 'value' => $totalStokSampah . " Kg"],
            ['icon' => 'truck', 'color' => 'primary', 'label' => 'Total Sampah Terkirim', 'value' => $totalSampahTerkirim . " Kg"],
            ['icon' => 'graph-up-arrow', 'color' => 'success', 'label' => 'Keuntungan Bank Sampah', 'value' => 'Rp ' . number_format($totalKeuntungan, 0, ',', '.')],
        ];
    @endphp

    @foreach ($stats3 as $stat)
        <div class="col-sm-6 col-md-{{ $loop->last ? 6 : 3 }} mb-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-{{ $stat['color'] }}">
                                <i class="bi bi-{{ $stat['icon'] }}"></i>
                            </div>
                        </div>
                        <div class="col ms-3">
                            <div class="numbers">
                                <p class="card-category">{{ $stat['label'] }}</p>
                                <h4 class="card-title">{{ $stat['value'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@push('scripts')
<script>
    // Fade-in animation for the cards
    document.addEventListener("DOMContentLoaded", () => {
        const cards = document.querySelectorAll(".card-stats");
        cards.forEach((card, i) => {
            card.style.opacity = 0;
            card.style.transform = "translateY(20px)";
            setTimeout(() => {
                card.style.transition = "all 0.6s ease";
                card.style.opacity = 1;
                card.style.transform = "translateY(0)";
            }, 150 * i);
        });
    });
</script>
@endpush
