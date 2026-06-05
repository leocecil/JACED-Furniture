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
    }

    body { font-family: 'Lexend', sans-serif !important; color: var(--jaced-brown-dark) !important; }
    body, h1, h2, h3, h4, h5, h6, p, a, span, div,
    input, button, select, textarea, label, td, th, li {
        font-family: 'Lexend', sans-serif !important;
    }

    /* ── Tier & table cards ── */
    .tier-card { background-color: white !important; border-radius: 12px !important; border: none !important; transition: transform 0.2s, box-shadow 0.2s; }
    .tier-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.06) !important; }
    .tier-badge { font-size: 10px; letter-spacing: 0.05em; padding: 6px 12px; font-weight: 700; }
    .tier-bronze   { border-top: 4px solid #CD7F32 !important; }
    .tier-silver   { border-top: 4px solid #A6A6A6 !important; }
    .tier-gold     { border-top: 4px solid #D4AF37 !important; }
    .tier-platinum { border-top: 4px solid #708090 !important; }
    
    .table-custom th { font-size: 11px; letter-spacing: 0.05em; color: var(--jaced-muted) !important; }
    .btn-jaced-export { background-color: var(--jaced-dark) !important; color: white !important; padding: 10px 24px; border-radius: 8px; font-weight: 500; border: none; transition: all 0.2s; cursor: pointer; }
    .btn-jaced-export:hover { background-color: var(--jaced-sage) !important; }

    .container-fluid {
        padding: 0 !important;
        margin: 0 !important;
        max-width: 100% !important;
    }

    /* Custom styles untuk filter dropdown */
    .filter-select {
        font-size: 13px;
        font-weight: 500;
        color: var(--jaced-brown-dark);
        border: 1.5px solid #e2ddd8;
        border-radius: 10px;
        background-color: #faf9f7;
        padding: 8px 36px 8px 12px;
        outline: none;
        transition: all 0.2s;
        cursor: pointer;
    }
    .filter-select:focus {
        border-color: var(--jaced-caramel);
        background-color: #fff;
    }

    /* ── 🌟 STICKY PANEL & INTERNAL SCROLL SYSTEM ── */
    .sticky-ledger-card {
        background-color: white; 
        border-radius: 14px; 
        border: 1px solid #e2ddd8;
        max-height: 620px; 
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .ledger-sticky-header {
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
        padding: 24px 24px 16px 24px;
        border-bottom: 1px solid #f0eeeb;
    }
    .ledger-scrollable-body {
        overflow-y: auto;
        flex: 1;
        padding: 0 24px 24px 24px;
    }
    .ledger-scrollable-body::-webkit-scrollbar { width: 5px; }
    .ledger-scrollable-body::-webkit-scrollbar-thumb { background: #e2ddd8; border-radius: 4px; }
    
    /* Memastikan th tabel ikut mengunci saat di-scroll */
    .table-custom thead th {
        position: sticky;
        top: 0;
        background: #faf9f7;
        z-index: 5;
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-4">
    
    {{-- Header Utama --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
        <a href="{{ url()->previous() }}" class="btn-back" style="
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 4px 16px; border-radius: 10px; background: #f2ede6; border: none; 
            cursor: pointer; color: #272E1D; text-decoration: none; font-size: 14px; font-weight: 600;
        ">
            <i class="bi bi-arrow-left" style="font-size: 16px; line-height: 1;"></i>
            <span>Back</span>
        </a>
    </div>

    {{-- ── 3. MASTER TABLE LIST CUSTOMER ── --}}
    <div class="row flex-grow-1 min-height-0 h-100">
        <div class="col-12 h-100 d-flex flex-column">
            <div class="sticky-ledger-card shadow-sm">
                
                {{-- Bagian Header Controls (Stay Terkunci di Atas) --}}
                <div class="ledger-sticky-header">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <div class="d-flex align-items-center">
                                <h5 class="fw-bold m-0 mb-1" style="color: var(--jaced-brown-dark);">Customer Database Master</h5>
                                @php
                                    $onlyCustomers = collect($allCustomers ?? [])->filter(fn($c) => !($c->is_admin ?? false));
                                @endphp
                                <span class="badge bg-light text-dark ms-2 border" style="font-size: 11px;" id="visibleCountBadge">{{ $onlyCustomers->count() }} customers</span>
                            </div>
                            <p class="text-muted m-0 small">Kelola, filter, dan telusuri profil seluruh member Jaced Furniture terdaftar.</p>
                        </div>
                        
                        {{-- Container Grup Search --}}
                        <div class="position-relative" style="max-width: 360px; width: 100%;">
                            <i class="bi bi-search position-absolute" style="left: 14px; top: 50%; transform: translateY(-50%); color: #9c9890; font-size: 13px;"></i>
                            <input type="text" 
                                   class="form-control" 
                                   id="mainCustomerSearchInput" 
                                   placeholder="Cari nama atau email member..." 
                                   oninput="filterMasterCustomers(this.value)"
                                   style="padding-left: 38px; border-radius: 10px; font-size: 13px; background: #faf9f7;"
                                   autocomplete="off">
                        </div>
                    </div>
                </div>

                {{-- Area Konten Data yang Dapat Di-scroll Secara Internal --}}
                <div class="ledger-scrollable-body">
                    <div class="table-responsive rounded-3 border mt-3" style="border-color: #e2ddd8 !important;">
                        <table class="table table-custom align-middle m-0" style="--bs-table-bg: transparent; font-size: 13px;">
                            <thead>
                                <tr class="text-uppercase small" style="font-size: 11px; letter-spacing: 0.05em; color: var(--jaced-muted);">
                                    <th class="py-3 ps-3">Customer Info</th>
                                    <th class="py-3">Customer ID</th>
                                    <th class="py-3 text-center">Membership Tier</th>
                                    <th class="py-3 text-end">Total Spend</th>
                                    <th class="py-3 text-center pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="masterCustomerTableBody">
                                @php
                                    $sortedCustomers = $onlyCustomers->sortBy(fn($c) => strtolower($c->name));
                                @endphp

                                @forelse($sortedCustomers as $customer)
                                    @php
                                        $pts = $customer->accumulated_points ?? 0;
                                        if ($pts < 500)       { $t = 'BRONZE';   $b = '#CD7F32'; }
                                        elseif ($pts < 1500)  { $t = 'SILVER';   $b = '#A6A6A6'; }
                                        elseif ($pts < 3500)  { $t = 'GOLD';     $b = '#D4AF37'; }
                                        else                  { $t = 'PLATINUM'; $b = '#708090'; }

                                        $totalSpendFormatted = 'Rp ' . number_format($customer->total_spend ?? 0, 0, ',', '.');
                                    @endphp
                                    
                                    <tr class="master-customer-row" 
                                        data-name="{{ strtolower($customer->name) }}" 
                                        data-email="{{ strtolower($customer->email) }}">
                                        
                                        <td class="py-3 ps-3">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold me-3"
                                                     style="width: 36px; height: 36px; background-color: var(--jaced-input); color: var(--jaced-brown-dark); font-size: 11px; flex-shrink: 0; overflow:hidden; border: 1px solid #e2ddd8;">
                                                    @if(!empty($customer->avatar))
                                                        <img src="{{ asset($customer->avatar) }}" style="width:100%; height:100%; object-fit:cover;">
                                                    @else
                                                        {{ strtoupper(substr($customer->name, 0, 2)) }}
                                                    @endif
                                                </div>
                                                <div class="ps-1">
                                                    <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                                        {{ $customer->name }}
                                                    </div>
                                                    <div class="text-muted" style="font-size: 11px; margin-top: 1px;">{{ $customer->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="fw-semibold text-muted small">JCF-{{ $customer->id }}</td>
                                        
                                        <td class="text-center">
                                            <span class="badge rounded-pill px-3 py-1.5 fw-bold"
                                                  style="background-color: {{ $b }}20; color: {{ $b }}; font-size: 9px; letter-spacing: 0.03em;">
                                                {{ $t }}
                                            </span>
                                        </td>
                                        
                                        <td class="fw-bold small text-end text-dark">
                                            {{ $totalSpendFormatted }}
                                        </td>
                                        
                                        <td class="text-center pe-3">
                                            <button type="button" 
                                                    class="btn btn-sm" 
                                                    style="width: 32px; height: 32px; border-radius: 6px; border: 1px solid #e2ddd8; background: #fff; display: inline-flex; align-items: center; justify-content: center; color: var(--jaced-brown);"
                                                    title="Quick View Detail"
                                                    onclick="triggerCustomerModal(
                                                        '{{ addslashes($customer->name) }}',
                                                        '{{ $customer->email }}',
                                                        '{{ $customer->avatar ? asset($customer->avatar) : '' }}',
                                                        '{{ number_format($pts) . ' pts' }}',
                                                        '{{ $t . ' TIER' }}',
                                                        '{{ $b }}'
                                                    )">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="bi bi-people d-block fs-2 opacity-25 mb-2"></i>
                                            Belum ada data kustomer terdaftar.
                                        </td>
                                    </tr>
                                @endforelse

                                <tr id="masterCustomerEmptyRow" style="display: none;">
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-search d-block fs-3 opacity-25 mb-2"></i>
                                        Tidak ada kustomer yang cocok dengan kriteria pencarian.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@push('modals')
<div class="modal fade" id="customerQuickViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">

            <div class="modal-header border-0 pt-3 pe-3 pb-0">
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center px-4 pb-4 pt-0">
                <div class="mx-auto shadow-sm rounded-circle d-flex align-items-center justify-content-center fw-bold text-dark mb-3"
                     id="modalCustAvatar"
                     style="width:80px;height:80px;background:#e2ddd8;font-size:24px;overflow:hidden;">
                </div>

                <h5 class="fw-bold mb-1 text-dark" id="modalCustName"></h5>
                <p class="text-muted small mb-3" id="modalCustEmail"></p>

                <hr style="border-color:#f0eeeb;">

                <div class="row g-2 mt-2">
                    <div class="col-6 border-end">
                        <small class="text-muted d-block mb-1" style="font-size:11px;font-weight:600;letter-spacing:0.02em;">CURRENT POINTS</small>
                        <h5 class="fw-bold m-0 text-dark" id="modalCustPoints">0</h5>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block mb-1" style="font-size:11px;font-weight:600;letter-spacing:0.02em;">MEMBERSHIP TIER</small>
                        <span class="badge rounded-pill px-3 fw-bold" id="modalCustTier" style="font-size:10px;letter-spacing:0.05em;">BRONZE</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
// Filter Gabungan Real-time (Teks Pencarian + Opsi Dropdown Peran)
function filterMasterCustomers(query) {
    const q = query.trim().toLowerCase();
    const rows = document.querySelectorAll('#masterCustomerTableBody .master-customer-row');
    const emptyRow = document.getElementById('masterCustomerEmptyRow');
    const countBadge = document.getElementById('visibleCountBadge');
    
    let totalVisible = 0;

    rows.forEach(row => {
        const name = row.getAttribute('data-name') || '';
        const email = row.getAttribute('data-email') || '';
        
        // Memeriksa kecocokan nama atau email kustomer dengan kata kunci
        const match = !q || name.includes(q) || email.includes(q);

        if (match) {
            row.style.display = '';
            totalVisible++;
        } else {
            row.style.display = 'none';
        }
    });

    if (emptyRow) emptyRow.style.display = totalVisible === 0 ? 'table-row' : 'none';
    if (countBadge) countBadge.textContent = totalVisible + ' customers';
}

// Trigger modal detail customer
function triggerCustomerModal(name, email, avatarUrl, points, tier, badgeColor) {
    document.getElementById('modalCustName').textContent   = name;
    document.getElementById('modalCustEmail').textContent  = email;
    document.getElementById('modalCustPoints').textContent = points;

    const tierBadge = document.getElementById('modalCustTier');
    tierBadge.textContent              = tier;
    tierBadge.style.backgroundColor    = badgeColor + '20';
    tierBadge.style.color              = badgeColor;

    const avatarEl = document.getElementById('modalCustAvatar');
    if (avatarUrl && avatarUrl !== '') {
        avatarEl.innerHTML = `<img src="${avatarUrl}" style="width:100%;height:100%;object-fit:cover;">`;
    } else {
        avatarEl.innerHTML = name.substring(0, 2).toUpperCase();
    }

    const modal = bootstrap.Modal.getInstance(document.getElementById('customerQuickViewModal'))
                  || new bootstrap.Modal(document.getElementById('customerQuickViewModal'));
    modal.show();
}
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    // Regions donut chart
    new Chart(document.getElementById('regionsChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($regionsLabels) !!},
            datasets: [{ data: {!! json_encode($regionsData) !!}, backgroundColor: ['#272E1D','#5F7568','#C99A6B','#DDD6CE'], borderWidth: 0, hoverOffset: 4 }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, cutout: '75%' }
    });

    // Trend chart
    new Chart(document.getElementById('trendChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($trendLabels) !!},
            datasets: [
                { type: 'line', label: 'Transactions Count', data: {!! json_encode($trendOrders) !!}, borderColor: '#C99A6B', backgroundColor: '#C99A6B', borderWidth: 2, tension: 0.3, yAxisID: 'y1' },
                { label: 'Revenue (Rp)', data: {!! json_encode($trendRevenue) !!}, backgroundColor: '#272E1D', borderRadius: 4, yAxisID: 'y' }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'top', labels: { boxWidth: 12, font: { family: 'Lexend' } } } },
            scales: {
                y:  { type: 'linear', position: 'left',  grid: { color: '#EBEBEB' }, ticks: { font: { family: 'Lexend', size: 10 } } },
                y1: { type: 'linear', position: 'right', grid: { drawOnChartArea: false }, ticks: { font: { family: 'Lexend', size: 10 } } },
                x:  { ticks: { font: { family: 'Lexend', size: 10 } }, grid: { display: false } }
            }
        }
    });
});
</script>
@endpush