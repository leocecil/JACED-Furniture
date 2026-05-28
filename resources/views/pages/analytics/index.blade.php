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
                    <h3 class="fw-bold m-0 mt-1">{{ number_format($tiers->bronze) }}</h3>
                </div>
                <small class="text-jaced-muted mt-3 d-block"><i class="bi bi-people-fill text-muted"></i> Registered Members</small>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="jaced-card tier-card tier-silver shadow-sm p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <span class="badge rounded-pill tier-badge text-dark bg-opacity-10 mb-2" style="background-color: #A6A6A6;">SILVER TIER</span>
                    <h3 class="fw-bold m-0 mt-1">{{ number_format($tiers->silver) }}</h3>
                </div>
                <small class="text-jaced-muted mt-3 d-block"><i class="bi bi-people-fill text-muted"></i> Registered Members</small>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="jaced-card tier-card tier-gold shadow-sm p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <span class="badge rounded-pill tier-badge text-dark bg-opacity-10 mb-2" style="background-color: #D4AF37;">GOLD TIER</span>
                    <h3 class="fw-bold m-0 mt-1">{{ number_format($tiers->gold) }}</h3>
                </div>
                <small class="text-jaced-muted mt-3 d-block"><i class="bi bi-people-fill text-muted"></i> Registered Members</small>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="jaced-card tier-card tier-platinum shadow-sm p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <span class="badge rounded-pill tier-badge text-dark bg-opacity-10 mb-2" style="background-color: #708090;">PLATINUM TIER</span>
                    <h3 class="fw-bold m-0 mt-1">{{ number_format($tiers->platinum) }}</h3>
                </div>
                <small class="text-jaced-muted mt-3 d-block"><i class="bi bi-people-fill text-muted"></i> Registered Members</small>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-8">
            <div class="jaced-card shadow-sm h-100">
               <div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold m-0">Revenue & Transactions Trend</h5>
    <span class="badge bg-white text-dark border px-3 py-2 small" style="border-color: var(--jaced-input) !important;">IDR (Rp)</span>
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
                    @foreach($regionsLabels as $index => $label)
                        @php
                            $colors = ['#272E1D', '#5F7568', '#C99A6B', '#DDD6CE'];
                            $color = $colors[$index] ?? '#DDD6CE';
                        @endphp
                        <span class="fw-medium">
                            <i class="bi bi-circle-fill me-1" style="color: {{ $color }};"></i> {{ $label }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="jaced-card shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold m-0">Top 5 VIP Spenders</h5>
                {{-- <a href="{{ route('analytics.customers.all') }}" class="text-decoration-none small fw-bold" style="color: var(--jaced-caramel);">View All Customers</a> --}}
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
                            @foreach($spenders as $spender)
                            <tr style="border-bottom: 1px solid var(--jaced-input);">
                                <td class="py-3 ps-0">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold me-2" 
                                             style="width: 32px; height: 32px; background-color: var(--jaced-input); color: var(--jaced-brown-dark); font-size: 11px;">
                                            {{ strtoupper(substr($spender->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold small">{{ $spender->name }}</div>
                                            <div class="text-jaced-muted" style="font-size: 11px;">{{ $spender->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="small fw-semibold text-jaced-muted">JCF-{{ $spender->id }}</td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background-color: {{ $spender->badge }}20; color: {{ $spender->badge }}; font-size: 9px;">
                                        {{ $spender->tier }}
                                    </span>
                                </td>
                                <td class="fw-bold small text-end pe-0">Rp {{ number_format($spender->total_spend, 0, ',', '.') }}</td>
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
        // 1. CONFIG: PROVINSI TERBANYAK BERBELANJA (Pie/Donut Chart Dinamis)
        const regionsCtx = document.getElementById('regionsChart').getContext('2d');
        new Chart(regionsCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($regionsLabels) !!},
                datasets: [{
                    data: {!! json_encode($regionsData) !!},
                    backgroundColor: ['#272E1D', '#5F7568', '#C99A6B', '#DDD6CE'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                cutout: '75%'
            }
        });

        // 2. CONFIG: REVENUE & TRANSACTIONS TREND CHART (Mixed Dinamis)
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        new Chart(trendCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($trendLabels) !!},
                datasets: [
                    {
                        type: 'line',
                        label: 'Transactions Count',
                        data: {!! json_encode($trendOrders) !!},
                        borderColor: '#C99A6B',
                        backgroundColor: '#C99A6B',
                        borderWidth: 2,
                        tension: 0.3,
                        yAxisID: 'y1',
                    },
                    {
    label: 'Revenue (Rp)', // ── Ganti teks label grafik di sini
    data: {!! json_encode($trendRevenue) !!},
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
                        grid: { drawOnChartArea: false },
                        ticks: { font: { family: 'Lexend', size: 11 } }
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