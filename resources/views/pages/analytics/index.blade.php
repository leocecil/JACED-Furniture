@extends('layouts.app')

@section('title', 'Customer Analytics')

@push('styles')
<style>
    :root {
        --jaced-white: #F9F9F7;
        --jaced-cream: #F2EDE6;
        --jaced-card: #FAF7F2;
        --jaced-brown-dark: #272E1D;
        --jaced-dark: #1C1C1A;
        --jaced-brown: #5A4D47;
        --jaced-caramel: #C99A6B;
        --jaced-sage: #5A6B5B;
        --jaced-input: #DDD6CE;
        --jaced-muted: #8A857D;
        --jaced-caramel-bg: #F5EBE0;
    }

    body {
        font-family: 'Lexend', sans-serif !important;
        background-color: var(--jaced-caramel-bg) !important;
        color: var(--jaced-brown-dark) !important;
    }

    body, h1, h2, h3, h4, h5, h6, p, a, span, div,
    input, button, select, textarea, label, td, th, li {
        font-family: 'Lexend', sans-serif !important;
    }

    .tier-card {
        background-color: white !important;
        border-radius: 12px !important;
        border: none !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .tier-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.06) !important;
    }
    .tier-badge {
        font-size: 10px;
        letter-spacing: 0.05em;
        padding: 6px 12px;
        font-weight: 700;
    }
    .tier-bronze   { border-top: 4px solid #CD7F32 !important; }
    .tier-silver   { border-top: 4px solid #A6A6A6 !important; }
    .tier-gold     { border-top: 4px solid #D4AF37 !important; }
    .tier-platinum { border-top: 4px solid #708090 !important; }

    .table-custom th {
        font-size: 11px;
        letter-spacing: 0.05em;
        color: var(--jaced-muted) !important;
    }

    .btn-jaced-export {
        background-color: var(--jaced-dark) !important;
        color: white !important;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-jaced-export:hover { background-color: var(--jaced-sage) !important; }

    /* ── All Customers Modal ── */
    #allCustomersModal .modal-content { border-radius: 16px; border: none; }

    .customer-search-wrap {
        padding: 12px 20px 10px;
        border-bottom: 1px solid #f0eeeb;
        position: relative;
    }
    .customer-search-icon {
        position: absolute;
        left: 32px;
        top: 50%;
        transform: translateY(-50%);
        color: #9c9890;
        font-size: 14px;
        pointer-events: none;
    }
    .customer-search-input {
        width: 100%;
        padding: 10px 14px 10px 38px;
        border: 1.5px solid #e2ddd8;
        border-radius: 10px;
        font-size: 13px;
        background: #faf9f7;
        color: #1a1a18;
        outline: none;
        transition: border-color 0.2s;
    }
    .customer-search-input:focus { border-color: var(--jaced-caramel); background: #fff; }

    .customer-list { overflow-y: auto; max-height: 400px; }
    .customer-list::-webkit-scrollbar { width: 4px; }
    .customer-list::-webkit-scrollbar-thumb { background: #e2ddd8; border-radius: 4px; }

    .customer-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 20px;
        transition: background 0.12s;
    }
    .customer-item:hover { background: #faf9f7; }

    .customer-avatar {
        width: 36px; height: 36px; border-radius: 50%;
        background: var(--jaced-input);
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 700;
        color: var(--jaced-brown-dark);
        flex-shrink: 0; overflow: hidden;
    }
    .customer-avatar img { width: 100%; height: 100%; object-fit: cover; }

    .customer-info { flex: 1; min-width: 0; }
    .customer-name {
        font-size: 13px; font-weight: 600; color: #1a1a18;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .customer-name mark {
        background: #fef3e2; color: #b87c2a;
        border-radius: 2px; padding: 0 1px;
    }
    .customer-email { font-size: 11px; color: #9c9890; }
    .customer-orders {
        font-size: 11px; font-weight: 600; color: #9c9890;
        white-space: nowrap; text-align: right; flex-shrink: 0;
    }

    .customer-empty {
        display: none; text-align: center;
        padding: 36px 20px; color: #9c9890; font-size: 13px;
    }
    .customer-empty i { font-size: 2rem; opacity: 0.25; display: block; margin-bottom: 10px; }

    .customer-count-badge {
        font-size: 11px; font-weight: 700;
        background: #f0eeeb; color: #6b6860;
        padding: 2px 8px; border-radius: 20px; margin-left: 8px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--jaced-brown-dark);">Customer Analytics</h2>
            <p class="text-jaced-muted small">Monitor member distribution, regional performance, and value tiers.</p>
        </div>
        <button class="btn-jaced-export">
            <i class="bi bi-download me-2"></i> Export Report
        </button>
    </div>

    {{-- Tier Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="tier-card tier-bronze shadow-sm p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <span class="badge rounded-pill tier-badge text-dark bg-opacity-10 mb-2" style="background-color: #CD7F32;">BRONZE TIER</span>
                    <h3 class="fw-bold m-0 mt-1" style="color: var(--jaced-brown-dark);">{{ number_format($tiers->bronze) }}</h3>
                </div>
                <small class="text-jaced-muted mt-3 d-block"><i class="bi bi-people-fill"></i> Registered Members</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="tier-card tier-silver shadow-sm p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <span class="badge rounded-pill tier-badge text-dark bg-opacity-10 mb-2" style="background-color: #A6A6A6;">SILVER TIER</span>
                    <h3 class="fw-bold m-0 mt-1" style="color: var(--jaced-brown-dark);">{{ number_format($tiers->silver) }}</h3>
                </div>
                <small class="text-jaced-muted mt-3 d-block"><i class="bi bi-people-fill"></i> Registered Members</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="tier-card tier-gold shadow-sm p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <span class="badge rounded-pill tier-badge text-dark bg-opacity-10 mb-2" style="background-color: #D4AF37;">GOLD TIER</span>
                    <h3 class="fw-bold m-0 mt-1" style="color: var(--jaced-brown-dark);">{{ number_format($tiers->gold) }}</h3>
                </div>
                <small class="text-jaced-muted mt-3 d-block"><i class="bi bi-people-fill"></i> Registered Members</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="tier-card tier-platinum shadow-sm p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <span class="badge rounded-pill tier-badge text-dark bg-opacity-10 mb-2" style="background-color: #708090;">PLATINUM TIER</span>
                    <h3 class="fw-bold m-0 mt-1" style="color: var(--jaced-brown-dark);">{{ number_format($tiers->platinum) }}</h3>
                </div>
                <small class="text-jaced-muted mt-3 d-block"><i class="bi bi-people-fill"></i> Registered Members</small>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-8">
            <div class="jaced-card shadow-sm p-4 h-100" style="background-color: white;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold m-0" style="color: var(--jaced-brown-dark);">Revenue & Transactions Trend</h5>
                    <span class="badge bg-white text-dark border px-3 py-2 small" style="border-color: var(--jaced-input) !important;">IDR (Rp)</span>
                </div>
                <div style="position: relative; height: 320px; width: 100%;">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="jaced-card shadow-sm p-4 h-100" style="background-color: white;">
                <h5 class="fw-bold mb-4" style="color: var(--jaced-brown-dark);">Top Regions</h5>
                <div class="d-flex flex-column align-items-center justify-content-center" style="position: relative; height: 260px;">
                    <canvas id="regionsChart"></canvas>
                </div>
                <div class="d-flex justify-content-center gap-3 mt-3 flex-wrap small">
                    @foreach($regionsLabels as $index => $label)
                        @php $colors = ['#272E1D', '#5F7568', '#C99A6B', '#DDD6CE']; $color = $colors[$index] ?? '#DDD6CE'; @endphp
                        <span class="fw-medium" style="color: var(--jaced-brown-dark);">
                            <i class="bi bi-circle-fill me-1" style="color: {{ $color }};"></i> {{ $label }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Top Spenders Table --}}
    <div class="row">
        <div class="col-12">
            <div class="jaced-card shadow-sm p-4" style="background-color: white;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold m-0" style="color: var(--jaced-brown-dark);">Top 5 VIP Spenders</h5>
                    <a href="#"
                       class="text-decoration-none small fw-bold"
                       style="color: var(--jaced-caramel);"
                       data-bs-toggle="modal"
                       data-bs-target="#allCustomersModal">
                        View All Customers
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-custom align-middle m-0" style="--bs-table-bg: transparent;">
                        <thead>
                            <tr class="text-uppercase small border-bottom" style="border-color: var(--jaced-input) !important;">
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
                                            <div class="fw-bold small" style="color: var(--jaced-brown-dark);">{{ $spender->name }}</div>
                                            <div class="text-jaced-muted" style="font-size: 11px;">{{ $spender->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="small fw-semibold text-jaced-muted">JCF-{{ $spender->id }}</td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-1.5 fw-bold"
                                          style="background-color: {{ $spender->badge }}20; color: {{ $spender->badge }}; font-size: 9px;">
                                        {{ $spender->tier }}
                                    </span>
                                </td>
                                <td class="fw-bold small text-end pe-0" style="color: var(--jaced-brown-dark);">Rp {{ number_format($spender->total_spend, 0, ',', '.') }}</td>
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
        // 1. Regions donut chart
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
                plugins: { legend: { display: false } },
                cutout: '75%'
            }
        });

        // 2. Revenue & transactions trend chart
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
                        label: 'Revenue (Rp)',
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
                        type: 'linear', display: true, position: 'left',
                        grid: { color: '#EBEBEB' },
                        ticks: { font: { family: 'Lexend', size: 11 } }
                    },
                    y1: {
                        type: 'linear', display: true, position: 'right',
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

{{-- MODAL ALL CUSTOMERS --}}
@push('modals')
<div class="modal fade" id="allCustomersModal" tabindex="-1" aria-labelledby="allCustomersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">

            <div class="modal-header border-0 pt-4 px-4 pb-2">
                <div>
                    <h5 class="modal-title fw-bold mb-0" id="allCustomersModalLabel">
                        All Customers
                        <span class="customer-count-badge" id="customerCountBadge">
                            {{ count($allCustomers ?? []) }}
                        </span>
                    </h5>
                    <div style="font-size:11px; color:#9c9890; margin-top:2px;">Sorted A–Z</div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>

            <div class="customer-search-wrap">
                <i class="bi bi-search customer-search-icon"></i>
                <input type="text"
                       class="customer-search-input"
                       id="customerSearchInput"
                       placeholder="Search by name or email..."
                       oninput="filterCustomers(this.value)"
                       autocomplete="off">
            </div>

           <div class="customer-list" id="customerList">
                @php
                    $sortedCustomers = collect($allCustomers ?? [])->sortBy(fn($c) => strtolower($c->name));
                @endphp

                @forelse($sortedCustomers as $customer)
                    {{-- Pastikan semua atribut data- di bawah ini tertulis lengkap & lowercase --}}
                    <div class="customer-item"
                         data-name="{{ strtolower($customer->name) }}"
                         data-original-name="{{ $customer->name }}"
                         data-email="{{ strtolower($customer->email) }}">

                        <div class="customer-avatar">
                            @if(!empty($customer->avatar))
                                <img src="{{ asset($customer->avatar) }}" alt="">
                            @else
                                {{ strtoupper(substr($customer->name, 0, 2)) }}
                            @endif
                        </div>

                        <div class="customer-info">
                            <div class="customer-name">{{ $customer->name }}</div>
                            <div class="customer-email">{{ $customer->email }}</div>
                        </div>

                        @if(isset($customer->orders_count))
                            <div class="customer-orders">
                                {{ $customer->orders_count }}
                                <div style="font-size:10px; font-weight:400;">orders</div>
                            </div>
                        @endif

                    </div>
                @empty
                    <div class="customer-empty" style="display:block;">
                        <i class="bi bi-people"></i>
                        No customers found.
                    </div>
                @endforelse

                {{-- Empty search state --}}
                <div class="customer-empty" id="customerEmptySearch">
                    <i class="bi bi-search"></i>
                    No customers match your search.
                </div>
            </div>

            <div class="modal-footer border-0 pb-4 px-4 pt-2">
                <button type="button"
                        class="btn btn-sm w-100 py-2 rounded-3 fw-bold"
                        data-bs-dismiss="modal"
                        style="background:#272E1D; color:#f5f2ee; border:none;">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

<script>
function filterCustomers(query) {
    // 1. Ambil query, hapus spasi kosong, dan ubah ke huruf kecil
    const q = query.trim().toLowerCase();
    const items = document.querySelectorAll('#customerList .customer-item');
    const emptyEl = document.getElementById('customerEmptySearch');
    const badge = document.getElementById('customerCountBadge');

    let visibleCount = 0;

    items.forEach(item => {
        // 2. Ambil data pencarian dari atribut HTML
        const nameLowerCase = (item.dataset.name ?? '').trim();
        const emailLowerCase = (item.dataset.email ?? '').trim();
        const originalName = item.dataset.originalName ?? ''; 

        // 3. Cek kecocokan secara sensitif
        const match = !q || nameLowerCase.includes(q) || emailLowerCase.includes(q);

        if (match) {
            // Paksa tampilkan menggunakan properti CSS display flex bawaan layout item
            item.setProperty ? item.style.setProperty('display', 'flex', 'important') : item.style.display = 'flex';
            visibleCount++;
            
            const nameEl = item.querySelector('.customer-name');
            if (q && nameLowerCase.includes(q)) {
                const escapedQuery = q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                const regex = new RegExp(`(${escapQuery})`, 'gi');
                nameEl.innerHTML = originalName.replace(regex, '<mark>$1</mark>');
            } else {
                nameEl.textContent = originalName; 
            }
        } else {
            // Paksa sembunyikan nama yang tidak mengandung kata kunci "dimas"
            item.setProperty ? item.style.setProperty('display', 'none', 'important') : item.style.display = 'none';
        }
    });

    // 4. Update counter jumlah customer yang lolos filter pencarian
    if (badge) badge.textContent = visibleCount;
    if (emptyEl) emptyEl.style.display = visibleCount === 0 ? 'block' : 'none';
}

// Reset pencarian secara total saat modal ditutup kembali oleh admin
document.getElementById('allCustomersModal').addEventListener('hidden.bs.modal', function () {
    const searchInput = document.getElementById('customerSearchInput');
    if (searchInput) {
        searchInput.value = '';
        filterCustomers('');
    }
});

// Auto-focus langsung ke kolom input ketika modal ditekan terbuka
document.getElementById('allCustomersModal').addEventListener('shown.bs.modal', function () {
    const searchInput = document.getElementById('customerSearchInput');
    if (searchInput) searchInput.focus();
});
</script>
@endpush