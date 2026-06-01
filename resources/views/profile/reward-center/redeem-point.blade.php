@extends('base.base')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
<style>
    .catalogue-page {
        background-color: var(--jaced-caramel-bg) !important;
        padding: 40px 24px;
        min-height: 100vh;
    }
    .points-sticky-card {
        background: white;
        border-radius: 12px;
        padding: 16px 24px;
        border: 1px solid var(--jaced-input);
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .filter-wrapper { margin-bottom: 32px; }
    .category-scroll {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 8px;
        scrollbar-width: none;
    }
    .category-scroll::-webkit-scrollbar { display: none; }
    .filter-pill {
        background: white;
        border: 1px solid var(--jaced-input);
        padding: 8px 16px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 500;
        color: var(--jaced-muted);
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s;
    }
    .filter-pill.active {
        background: var(--jaced-caramel);
        color: white;
        border-color: var(--jaced-caramel);
    }
    .utility-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 16px;
    }
    .select-sort {
        background: white;
        border: 1px solid var(--jaced-input);
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 13px;
        color: var(--jaced-brown-dark);
        outline: none;
        cursor: pointer;
    }
    .toggle-container {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        user-select: none;
        font-size: 13px;
        color: var(--jaced-brown-dark);
        font-weight: 500;
    }
    .toggle-switch {
        position: relative;
        width: 36px;
        height: 20px;
        background: var(--jaced-input);
        border-radius: 999px;
        transition: background 0.2s;
    }
    .toggle-switch::after {
        content: '';
        position: absolute;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: white;
        top: 3px;
        left: 3px;
        transition: transform 0.2s;
    }
    .toggle-container.active .toggle-switch { background: var(--jaced-sage); }
    .toggle-container.active .toggle-switch::after { transform: translateX(16px); }

    .reward-grid-card {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: transform 0.2s;
        animation: fadeSlideUp 0.35s ease both;
    }
    .reward-grid-card:hover { 
        transform: translateY(-4px); 
        box-shadow: 0 12px 28px rgba(0,0,0,0.07);
    }

    .reward-grid-card.is-locked {
        opacity: 0.55;
        pointer-events: none;
    }
    .reward-grid-card.is-locked img {
        filter: grayscale(40%);
    }

    .reward-body {
        padding: 16px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .reward-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--jaced-brown-dark);
        margin: 0 0 8px;
    }
    .reward-pts {
        font-size: 13px;
        font-weight: 600;
        color: var(--jaced-brown-dark);
        margin-bottom: 4px;
    }
    .reward-pts-val {
        color: var(--jaced-caramel);
        font-weight: 700;
        font-size: 18px;
    }
    .reward-action-btn { margin-top: auto; }
    .btn-redeem-active {
        background: var(--jaced-sage);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 9px 16px;
        font-size: 13px;
        font-weight: 600;
        width: 100%;
        cursor: pointer;
        transition: background .2s;
    }
    .btn-redeem-active:hover { background: #4a5d4b; }
    .btn-redeem-locked {
        background: var(--jaced-input);
        color: var(--jaced-muted);
        border: none;
        border-radius: 8px;
        padding: 9px 16px;
        font-size: 13px;
        font-weight: 600;
        width: 100%;
        cursor: not-allowed;
    }

    .btn-redeem-now {
        background: var(--jaced-sage);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 11px 20px;
        font-size: 13px;
        font-weight: 600;
        width: 100%;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-redeem-now:hover { background: #4a5d4b; }

    .btn-view-details {
        background: transparent;
        color: var(--jaced-sage);
        border: 1px solid var(--jaced-sage);
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        width: 100%;
        transition: all 0.2s;
    }
    .btn-view-details:hover { background: var(--jaced-sage); color: white; }

    /* MODAL */
    .jaced-modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(28, 28, 26, 0.6);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
    .jaced-modal-overlay.show { opacity: 1; pointer-events: auto; }
    .jaced-modal-box {
        background: white;
        border-radius: 16px;
        padding: 32px 24px;
        max-width: 400px;
        width: 90%;
        text-align: center;
        box-shadow: 0 10px 30px rgba(39, 46, 29, 0.08);
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }
    .jaced-modal-overlay.show .jaced-modal-box { transform: scale(1); }
    .modal-icon-wrap {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    .modal-icon-wrap.confirmation { background: #FAF2EB; color: var(--jaced-caramel); }
    .modal-icon-wrap.success { background: #EAF0EB; color: var(--jaced-sage); }
    .modal-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--jaced-brown-dark);
        margin-bottom: 8px;
    }
    .modal-text {
        font-size: 13px;
        color: var(--jaced-muted);
        line-height: 1.5;
        margin: 0;
    }
    .btn-modal-primary {
        background: var(--jaced-sage);
        color: white !important;
        border: none;
        padding: 11px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        flex: 1;
        transition: background 0.2s;
    }
    .btn-modal-primary:hover { background: #4a5d4b; }
    .btn-modal-secondary {
        background: transparent;
        border: 1px solid var(--jaced-input);
        color: var(--jaced-brown-dark);
        padding: 11px 20px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 13px;
        cursor: pointer;
        flex: 1;
        transition: all 0.2s;
    }
    .btn-modal-secondary:hover { background: var(--jaced-cream); }

    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    
    .reward-item-card:nth-child(1) .reward-grid-card { animation-delay: 0.05s; }
    .reward-item-card:nth-child(2) .reward-grid-card { animation-delay: 0.10s; }
    .reward-item-card:nth-child(3) .reward-grid-card { animation-delay: 0.15s; }
    .reward-item-card:nth-child(4) .reward-grid-card { animation-delay: 0.20s; }
    .reward-item-card:nth-child(5) .reward-grid-card { animation-delay: 0.25s; }
    .reward-item-card:nth-child(6) .reward-grid-card { animation-delay: 0.30s; }
</style>
@endpush

@section('content')

<div class="catalogue-page">
    <div style="max-width: 1000px; margin: 0 auto;">

        {{-- BACK --}}
        <a href="{{ route('reward') }}" class="back-link mb-3">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back to Reward Center</span>
        </a>

        {{-- BALANCE BAR --}}
        <div class="points-sticky-card">
            <div>
                <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--jaced-brown-dark); margin: 0;">Redeem Rewards</h1>
                <p style="font-size: 12px; color: var(--jaced-muted); margin: 0;">Exchange your points for exclusive discount vouchers.</p>
            </div>
            <div class="text-end">
                <p style="font-size: 11px; font-weight: 600; color: var(--jaced-muted); text-transform: uppercase; margin: 0;">Your Balance</p>
                <p style="font-size: 24px; font-weight: 700; color: var(--jaced-brown-dark); margin: 0;">
                    <span style="color: var(--jaced-caramel);">{{ number_format($currentPoints) }}</span>
                    <span style="font-size: 14px; font-weight: 500;">Points</span>
                </p>
            </div>
        </div>

        {{-- FLASH MESSAGE --}}
        @if(session('success'))
            <div class="alert alert-success mb-3" style="font-size: 13px;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mb-3" style="font-size: 13px;">{{ session('error') }}</div>
        @endif

        {{-- FILTER PANEL --}}
        <div class="filter-wrapper">
            <div class="category-scroll">
                <div class="filter-pill active" data-category="all">All Vouchers</div>
                <div class="filter-pill" data-category="delivery">Free Shipping</div>
                <div class="filter-pill" data-category="product">Product Discount</div>
            </div>

            <div class="utility-bar">
                <select class="select-sort" id="sortPoints">
                    <option value="default">Sort: Featured</option>
                    <option value="low">Points: Low to High</option>
                    <option value="high">Points: High to Low</option>
                </select>

                <div class="toggle-container" id="affordableToggle">
                    <div class="toggle-switch"></div>
                    <span>Show Affordable Only</span>
                </div>
            </div>
        </div>

        {{-- REWARDS GRID --}}
        <div class="row g-3" id="rewardsContainer">
            @forelse ($redeemGoals as $reward)
                @php 
                    $isEnough = $currentPoints >= $reward->point_cost; 
                    $voucherImage = match(true) {
                        $reward->used_for === 'delivery'       => 'disc-ongkir.png',
                        $reward->discount_percentage === 100     => 'disc-100.png',
                        $reward->discount_percentage === 90     => 'disc-90.png',
                        $reward->discount_percentage === 80     => 'disc-80.png',
                        $reward->discount_percentage === 70     => 'disc-70.png',
                        $reward->discount_percentage === 60     => 'disc-60.png',
                        $reward->discount_percentage === 50     => 'disc-50.png',
                        $reward->discount_percentage === 40     => 'disc-40.png',
                        $reward->discount_percentage === 30     => 'disc-30.png',
                        $reward->discount_percentage === 20     => 'disc-20.png',
                        $reward->discount_percentage === 10     => 'disc-10.png',
                        default                               => 'disc-product-default.png',
                    };
                @endphp

                <div class="col-12 col-sm-6 col-md-4 reward-item-card"
                     data-category="{{ $reward->used_for }}"
                     data-points="{{ $reward->point_cost }}"
                     data-affordable="{{ $isEnough ? 'true' : 'false' }}">

                    <div class="reward-grid-card {{ $isEnough ? 'is-affordable' : 'is-locked' }}">
                        <img src="{{ asset('image/vouchers/' . $voucherImage) }}"
                            alt="{{ $reward->name }}"
                            style="width: 100%; height: 140px; object-fit: cover;">

                        <div class="reward-body">

                            {{-- Badge jenis voucher --}}
                            <div class="mb-2">
                                @if($reward->used_for === 'delivery')
                                    <span class="badge" style="background-color: #f1f4f2; color: #5c695d; font-size: 11px; display:inline-flex; align-items:center; gap:3px;">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                        Free Shipping
                                    </span>
                                @else
                                    <span class="badge" style="background-color: #fcf5f3; color: #bd654e; font-size: 11px; display:inline-flex; align-items:center; gap:3px;">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                        Product Discount
                                    </span>
                                @endif
                            </div>

                            <p class="reward-title">{{ $reward->name }}</p>

                            <div class="reward-pts">
                                <span class="reward-pts-val">{{ number_format($reward->point_cost) }}</span> Points
                                <p style="font-size: 11px; color: var(--jaced-muted); margin-bottom: 4px;">
                                    Available stock: {{ $reward->stock }} voucher{{ $reward->stock > 1 ? 's' : '' }}
                                </p>
                            </div>

                            <p class="text-muted mb-3" style="font-size: 12px;">
                                {{ $reward->discount_percentage }}% off &bull;
                                Max Rp {{ number_format($reward->max_discount, 0, ',', '.') }}
                            </p>

                            <div class="reward-action-btn">
                                @if($reward->stock > 0)
                                    <button class="btn-view-details" onclick="openGoalDetail(
                                        '{{ $reward->name }}',
                                        '{{ $reward->used_for }}',
                                        {{ $reward->discount_percentage }},
                                        {{ $reward->max_discount }},
                                        {{ $reward->point_cost }},
                                        '{{ $reward->id }}',
                                        '{{ asset('image/vouchers/' . $voucherImage) }}',
                                        '{{ addslashes($reward->description) }}',
                                        {{ $isEnough ? 'true' : 'false' }}
                                    )">
                                        View Details
                                    </button>
                                @else
                                    <button class="btn-redeem-locked" disabled>Out of Stock</button>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>

            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <p class="text-muted">There are no vouchers available to redeem.</p>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</div>

{{-- Taruh sebelum @push('scripts'), hapus modal redeemModal yang lama --}}
<div class="jaced-modal-overlay" id="goalDetailModal" style="display:none;">
    <div class="jaced-modal-box" style="max-width: 380px; width: 90%; text-align: left; padding: 0; overflow: hidden;">
        <img id="goalDetailImg" src="" alt="" style="width: 100%; height: 160px; object-fit: cover;">
        <div style="padding: 24px;">
            <div class="mb-2" id="goalDetailBadge"></div>
            <h3 style="font-size: 16px; font-weight: 700; color: var(--jaced-dark); margin: 0 0 12px;" id="goalDetailName"></h3>
            <div class="d-flex flex-column mb-3">
                <span style="font-size: 12px; color: var(--jaced-muted);">Description</span>
                <span id="goalDetailDesc" style="font-size: 13px; color: var(--jaced-dark);"></span>
            </div>
            <div style="font-size: 13px; color: var(--jaced-muted); margin-bottom: 20px;">
                <div class="d-flex justify-content-between mb-2">
                    <span>Discount</span>
                    <strong id="goalDetailPct" style="color: var(--jaced-dark);"></strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Max Discount</span>
                    <strong id="goalDetailMax" style="color: var(--jaced-dark);"></strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Points Required</span>
                    <strong id="goalDetailPts" style="color: var(--jaced-caramel);"></strong>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button onclick="closeGoalDetail()" style="background: transparent; border: 1px solid var(--jaced-input); border-radius: 8px; padding: 10px 16px; font-size: 13px; color: var(--jaced-brown-dark); cursor: pointer;">Close</button>
                <div id="goalDetailAction" style="flex: 1;"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // === FILTER & SORT ===
    document.addEventListener('DOMContentLoaded', function () {
        const pills = document.querySelectorAll('.filter-pill');
        const affordableToggle = document.getElementById('affordableToggle');
        const sortSelect = document.getElementById('sortPoints');
        const container = document.getElementById('rewardsContainer');

        let currentCategory = 'all';
        let showAffordableOnly = false;

        pills.forEach(pill => {
            pill.addEventListener('click', function () {
                pills.forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                sortSelect.value = 'default';
                currentCategory = this.getAttribute('data-category');
                applyFilters();
            });
        });

        affordableToggle.addEventListener('click', function () {
            this.classList.toggle('active');
            showAffordableOnly = this.classList.contains('active');
            applyFilters();
        });

        function applyFilters() {
            const items = container.querySelectorAll('.reward-item-card');
            items.forEach(item => {
                const cat = item.getAttribute('data-category');
                const isAffordable = item.getAttribute('data-affordable') === 'true';
                const matchCat = (currentCategory === 'all' || cat === currentCategory);
                const matchAffordable = (!showAffordableOnly || isAffordable);
                item.style.display = (matchCat && matchAffordable) ? 'block' : 'none';
            });
        }

        sortSelect.addEventListener('change', function () {
            const items = Array.from(container.querySelectorAll('.reward-item-card'));
            const sortBy = this.value;
            if (sortBy === 'default') return;
            items.sort((a, b) => {
                const ptsA = parseInt(a.getAttribute('data-points'));
                const ptsB = parseInt(b.getAttribute('data-points'));
                return sortBy === 'low' ? ptsA - ptsB : ptsB - ptsA;
            });
            items.forEach(item => container.appendChild(item));
        });
    });

    const goalModal = document.getElementById('goalDetailModal');
    function openGoalDetail(name, usedFor, pct, maxDiscount, pointCost, voucherTypeId, imgSrc, description, isEnough) {
        document.getElementById('goalDetailImg').src = imgSrc;
        document.getElementById('goalDetailName').innerText = name;
        document.getElementById('goalDetailPct').innerText = pct + '%';
        document.getElementById('goalDetailMax').innerText = 'Rp ' + maxDiscount.toLocaleString('id-ID');
        document.getElementById('goalDetailPts').innerText = pointCost.toLocaleString('id-ID') + ' Points';
        document.getElementById('goalDetailDesc').innerText = description || 'No description available.';

        const badgeEl = document.getElementById('goalDetailBadge');
        badgeEl.innerHTML = usedFor === 'delivery'
            ? `<span class="badge" style="background-color: var(--jaced-caramel-bg); color: var(--jaced-sage); font-size: 11px; display:inline-flex; align-items:center; gap:3px;">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                Free Shipping</span>`
            : `<span class="badge" style="background-color: #fcf5f3; color: #bd654e; font-size: 11px; display:inline-flex; align-items:center; gap:3px;">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                Product Discount</span>`;

        const actionEl = document.getElementById('goalDetailAction');
        actionEl.innerHTML = isEnough
            ? `<form action="{{ route('reward.redeem') }}" method="POST">
                @csrf
                <input type="hidden" name="voucher_type_id" value="${voucherTypeId}">
                <button type="submit" class="btn-redeem-now">Redeem Now</button>
            </form>`
            : '<button class="btn-redeem-locked" disabled>Points Insufficient</button>';

        goalModal.style.display = 'flex';
        setTimeout(() => goalModal.classList.add('show'), 10);
    }

    function closeGoalDetail() {
        goalModal.classList.remove('show');
        setTimeout(() => { goalModal.style.display = 'none'; }, 300);
    }
</script>
@endpush

@endsection