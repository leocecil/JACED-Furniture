@extends('layouts.app')

@section('title', 'Customer Analytics')

@push('styles')
<style>
    /* Styling khusus kartu metrik tingkatan member */
    .tier-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .tier-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.04) !important;
    }
    .tier-badge {
        font-size: 10px;
        letter-spacing: 0.05em;
        padding: 6px 12px;
    }
    /* Warna aksen khusus tiap tier */
    .tier-bronze { border-top: 4px solid #CD7F32 !important; }
    .tier-silver { border-top: 4px solid #A6A6A6 !important; }
    .tier-gold { border-top: 4px solid #D4AF37 !important; }
    .tier-platinum { border-top: 4px solid #708090 !important; }
    
    .table-custom th {
        font-size: 11px;
        letter-spacing: 0.05em;
        color: var(--jaced-muted);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="fw-bold mb-1">Customer Analytics</h2>
            <p class="text-jaced-muted small">Monitor member distribution, regional performance, and value tiers.</p>
        </div>
        <button class="btn btn-jaced-primary px-4 py-2">
            <i class="bi bi-download me-2"></i> Export Report
        </button>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="jaced-card tier-card tier-bronze shadow-sm p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <span class="badge rounded-pill tier-badge text-dark bg-opacity-10 mb-2" style="background-color: #CD7F32;">BRONZE TIER</span>
                    <h3 class="fw-bold m-0 mt-1">1,240</h3>
                </div>
                <small class="text-jaced-muted mt-3 d-block"><i class="bi bi-arrow-up-short text-success"></i> 4.2% dari bulan lalu</small>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="jaced-card tier-card tier-silver shadow-sm p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <span class="badge rounded-pill tier-badge text-dark bg-opacity-10 mb-2" style="background-color: #A6A6A6;">SILVER TIER</span>
                    <h3 class="fw-bold m-0 mt-1">852</h3>
                </div>
                <small class="text-jaced-muted mt-3 d-block"><i class="bi bi-arrow-up-short text-success"></i> 8.1% dari bulan lalu</small>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="jaced-card tier-card tier-gold shadow-sm p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <span class="badge rounded-pill tier-badge text-dark bg-opacity-10 mb-2" style="background-color: #D4AF37;">GOLD TIER</span>
                    <h3 class="fw-bold m-0 mt-1">415</h3>
                </div>
                <small class="text-jaced-muted mt-3 d-block"><i class="bi bi-arrow-up-short text-success"></i> 2.5% dari bulan lalu</small>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="jaced-card tier-card tier-platinum shadow-sm p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <span class="badge rounded-pill tier-badge text-dark bg-opacity-10 mb-2" style="background-color: #708090;">PLATINUM TIER</span>
                    <h3 class="fw-bold m-0 mt-1">98</h3>
                </div>
                <small class="text-jaced-muted mt-3 d-block"><i class="bi bi-arrow-up-short text-success"></i> 12.0% dari bulan lalu</small>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-8">
            <div class="jaced-card shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold m-0">Revenue & Growth Trend</h5>
                    <span class="badge bg-white text-dark border px-3 py-2 small" style="border-color: var(--jaced-input) !important;">Tahun 2026</span>
                </div>
                <div style="position: relative; height: 320px; width: 100%;">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="jaced-card shadow-sm h-100">
                <h5 class="fw-bold mb-4">Top Regions</h5>
                <div class="d-flex flex-column align-items-center justify-content-center" style="position: relative; height: 260px;">
                    <canvas id="regionsChart"></canvas>
                </div>
                <div class="d-flex justify-content-center gap-3 mt-3 flex-wrap small">
                    <span class="fw-medium"><i class="bi bi-circle-fill me-1" style="color: #272E1D;"></i> DKI Jakarta</span>
                    <span class="fw-medium"><i class="bi bi-circle-fill me-1" style="color: #5F7568;"></i> Jawa Barat</span>
                    <span class="fw-medium"><i class="bi bi-circle-fill me-1" style="color: #C99A6B;"></i> Jawa Timur</span>
                    <span class="fw-medium"><i class="bi bi-circle-fill me-1" style="color: #DDD6CE;"></i> Lainnya</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="jaced-card shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold m-0">Top 5 VIP Spenders</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-custom align-middle m-0" style="--bs-table-bg: transparent;">
                        <thead>
                            <tr class="text-uppercase small border-bottom">
                                <th class="py-3 ps-0">Customer Info</th>
                                <th class="py-3">Customer ID</th>
                                <th class="py-3">Membership Tier</th>
                                <th class="py-3 text-end pe-0">Total Spend</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $spenders = [
                                    ['name' => 'Amira Kartika', 'email' => 'amira@designhouse.co', 'id' => 'JCF-9921', 'tier' => 'PLATINUM', 'badge' => '#708090', 'spend' => '$48,250.00'],
                                    ['name' => 'Bambang Utomo', 'email' => 'bambang.u@studio.com', 'id' => 'JCF-8812', 'tier' => 'GOLD', 'badge' => '#D4AF37', 'spend' => '$32,100.00'],
                                    ['name' => 'Clara Dian', 'email' => 'clara.dian@gmail.com', 'id' => 'JCF-7489', 'tier' => 'GOLD', 'badge' => '#D4AF37', 'spend' => '$28,450.00'],
                                    ['name' => 'Dimas Raditya', 'email' => 'dimas.radit@corporate.id', 'id' => 'JCF-8341', 'tier' => 'SILVER', 'badge' => '#A6A6A6', 'spend' => '$19,800.00'],
                                    ['name' => 'Eka Prasetya', 'email' => 'eka.p@jaced-furniture.test', 'id' => 'JCF-9021', 'tier' => 'BRONZE', 'badge' => '#CD7F32', 'spend' => '$14,250.00'],
                                ];
                            @endphp

                            @foreach($spenders as $spender)
                            <tr style="border-bottom: 1px solid var(--jaced-input);">
                                <td class="py-3 ps-0">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold me-2" 
                                             style="width: 32px; height: 32px; background-color: var(--jaced-input); color: var(--jaced-brown-dark); font-size: 11px;">
                                            {{ strtoupper(substr($spender['name'], 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold small">{{ $spender['name'] }}</div>
                                            <div class="text-jaced-muted" style="font-size: 11px;">{{ $spender['email'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="small fw-semibold text-jaced-muted">{{ $spender['id'] }}</td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background-color: {{ $spender['badge'] }}20; color: {{ $spender['badge'] }}; font-size: 9px;">
                                        {{ $spender['tier'] }}
                                    </span>
                                </td>
                                <td class="fw-bold small text-end pe-0">{{ $spender['spend'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. CONFIG: PROVINSI TERBANYAK BERBELANJA (Pie/Donut Chart)
        const regionsCtx = document.getElementById('regionsChart').getContext('2d');
        new Chart(regionsCtx, {
            type: 'doughnut',
            data: {
                labels: ['DKI Jakarta', 'Jawa Barat', 'Jawa Timur', 'Lainnya'],
                datasets: [{
                    data: [45, 25, 18, 12],
                    backgroundColor: ['#272E1D', '#5F7568', '#C99A6B', '#DDD6CE'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false } // Custom legend dibuat manual di bawah HTML
                },
                cutout: '75%' // Membuat ring lebih tipis dan mewah
            }
        });

        // 2. CONFIG: REVENUE & GROWTH TREND CHART (Ide Tambahan)
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        new Chart(trendCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [
                    {
                        type: 'line',
                        label: 'Growth Rate (%)',
                        data: [10, 15, 8, 22, 18, 25],
                        borderColor: '#C99A6B',
                        backgroundColor: '#C99A6B',
                        borderWidth: 2,
                        tension: 0.3,
                        yAxisID: 'y1',
                    },
                    {
                        label: 'Revenue ($)',
                        data: [12000, 19000, 15000, 25000, 22000, 30000],
                        backgroundColor: '#272E1D',
                        borderRadius: 4,
                        yAxisID: 'y',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { boxWidth: 12, font: { family: 'Lexend' } } }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        grid: { color: '#EBEBEB' },
                        ticks: { font: { family: 'Lexend', size: 11 } }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: { drawOnChartArea: false }, // Menghindari grid ganda bertumpuk
                        ticks: { font: { family: 'Lexend', size: 11 }, callback: value => value + '%' }
                    },
                    x: {
                        ticks: { font: { family: 'Lexend', size: 11 } },
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endpush