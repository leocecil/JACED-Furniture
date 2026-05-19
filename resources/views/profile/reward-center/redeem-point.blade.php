@extends('base.base')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
<style>
    .catalogue-page {
        background-color: var(--jaced-cream);
        padding: 40px 24px;
        min-height: 100vh;
    }
    
    /* STICKY POINTS BAR */
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

    /* FILTER SECTION */
    .filter-wrapper {
        margin-bottom: 32px;
    }
    .category-scroll {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 8px;
        scrollbar-width: none; /* Hide scrollbar for Firefox */
    }
    .category-scroll::-webkit-scrollbar {
        display: none; /* Hide scrollbar for Chrome/Safari */
    }
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
    
    /* UTILITY FILTERS (Sort & Toggle) */
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
    .toggle-container.active .toggle-switch {
        background: var(--jaced-sage);
    }
    .toggle-container.active .toggle-switch::after {
        transform: translateX(16px);
    }

    /* REWARDS GRID */
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
    }
    .reward-grid-card:hover {
        transform: translateY(-2px);
    }
    .reward-img-wrap {
        background: #f5f4f0;
        height: 180px;
        width: 100%;
        position: relative;
    }
    .reward-img {
        width: 100%;
        height: 180px;
        object-fit: cover;
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
        margin-bottom: 12px;
    }
    .reward-pts-val {
        color: var(--jaced-caramel);
        font-weight: 700;
        font-size: 18px;
    }
    .reward-action-btn {
        margin-top: auto; /* Push button to bottom */
    }

    /* REUSE STYLES FROM ORIGINAL */
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
    /* MODAL BASE OVERLAY */
    .jaced-modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(28, 28, 26, 0.6); /* --jaced-dark dengan opacity */
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
    .jaced-modal-overlay.show {
        opacity: 1;
        pointer-events: auto;
    }

    /* MODAL BOX COMPONENT */
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
    .jaced-modal-overlay.show .jaced-modal-box {
        transform: scale(1);
    }

    /* ICON WRAPPERS ACCENT */
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

    /* TYPOGRAPHY */
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

    /* MODAL BUTTONS SIGNATURE */
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
    .btn-modal-secondary:hover { background: var(--jaced-white); }
</style>
@endpush

@section('content')
@php
    // Simulasi total saldo poin user sekarang
    $userTotalPoints = 1250; 

    // Data komplit katalog produk penukaran hadiah
    $allRewards = [
        [
            'name'     => 'Artisan Scented Candle',
            'category' => 'candles',
            'points'   => 450,
            'image'    => 'https://images.unsplash.com/photo-1603905600016-2f0a09924a49?w=400&h=300&fit=crop',
        ],
        [
            'name'     => 'Asymmetric Vase',
            'category' => 'decor',
            'points'   => 850,
            'image'    => 'https://images.unsplash.com/photo-1612196808214-b8e1d6145a8c?w=400&h=300&fit=crop',
        ],
        [
            'name'     => 'Handwoven Wool Throw',
            'category' => 'fabric',
            'points'   => 2000,
            'image'    => 'https://images.unsplash.com/photo-1580301762395-21ce84d00bc6?w=400&h=300&fit=crop',
        ],
        [
            'name'     => 'Minimalist Ceramic Mug',
            'category' => 'decor',
            'points'   => 300,
            'image'    => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=400&h=300&fit=crop',
        ],
        [
            'name'     => 'Premium Linen Apron',
            'category' => 'fabric',
            'points'   => 1200,
            'image'    => 'https://images.unsplash.com/photo-1534620808146-d33bb39128b2?w=400&h=300&fit=crop',
        ],
        [
            'name'     => 'Chamber Scented Diffuser',
            'category' => 'candles',
            'points'   => 1600,
            'image'    => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=400&h=300&fit=crop',
        ]
    ];
@endphp

<div class="catalogue-page">
    <div style="max-width: 1000px; margin: 0 auto;">
        
        {{-- NAV BACK KE REWARD CENTER --}}
        <a href="#" class="back-link mb-3" onclick="window.history.back(); return false;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back to Reward Center</span>
        </a>

        {{-- STICKY BALANCE BAR --}}
        <div class="points-sticky-card">
            <div>
                <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--jaced-brown-dark); margin: 0;">Redeem Rewards</h1>
                <p style="font-size: 12px; color: var(--jaced-muted); margin: 0;">Exchange your hard-earned points with exclusive goods.</p>
            </div>
            <div class="text-end">
                <p style="font-size: 11px; font-weight: 600; color: var(--jaced-muted); text-transform: uppercase; margin: 0;">Your Balance</p>
                <p style="font-size: 24px; font-weight: 700; color: var(--jaced-brown-dark); margin: 0;">
                    <span style="color: var(--jaced-caramel);">{{ number_format($userTotalPoints) }}</span> <span style="font-size: 14px; font-weight: 500;">Points</span>
                </p>
            </div>
        </div>

        {{-- FILTER PANEL --}}
        <div class="filter-wrapper">
            <div class="category-scroll">
                <div class="filter-pill active" data-category="all">All Rewards</div>
                <div class="filter-pill" data-category="candles">Artisan Candles</div>
                <div class="filter-pill" data-category="decor">Home Decor & Ceramic</div>
                <div class="filter-pill" data-category="fabric">Fabric & Comfort</div>
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

        {{-- REWARDS GRID LIST --}}
        <div class="row g-3" id="rewardsContainer">
            @foreach ($allRewards as $reward)
                @php $isEnough = $userTotalPoints >= $reward['points']; @endphp
                
                <div class="col-12 col-sm-6 col-md-4 reward-item-card" 
                     data-category="{{ $reward['category'] }}" 
                     data-points="{{ $reward['points'] }}"
                     data-affordable="{{ $isEnough ? 'true' : 'false' }}">
                    
                    <div class="reward-grid-card">
                        <div class="reward-img-wrap">
                            <img src="{{ $reward['image'] }}" alt="{{ $reward['name'] }}" class="reward-img">
                        </div>
                        <div class="reward-body">
                            <p class="reward-title">{{ $reward['name'] }}</p>
                            
                            <p class="reward-pts">
                                <span class="reward-pts-val">{{ number_format($reward['points']) }}</span> Points
                            </p>

                            <div class="reward-action-btn">
                                @if ($isEnough)
                                    <button class="btn-redeem-active" onclick="confirmRedeem('{{ $reward['name'] }}', {{ $reward['points'] }})">Redeem Now</button>
                                @else
                                    <button class="btn-redeem-locked" disabled>Need {{ number_format($reward['points'] - $userTotalPoints) }} Pts</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>

{{-- JAVASCRIPT LOGIC FILTER & SORTING MURNI --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pills = document.querySelectorAll('.filter-pill');
    const affordableToggle = document.getElementById('affordableToggle');
    const sortSelect = document.getElementById('sortPoints');
    const container = document.getElementById('rewardsContainer');
    
    let currentCategory = 'all';
    let showAffordableOnly = false;

    // 1. FILTER KATEGORI (PILLS)
    pills.forEach(pill => {
        pill.addEventListener('click', function() {
            pills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            currentCategory = this.getAttribute('data-category');
            applyFilters();
        });
    });

    // 2. TOGGLE AFFORDABLE ONLY
    affordableToggle.addEventListener('click', function() {
        this.classList.toggle('active');
        showAffordableOnly = this.classList.contains('active');
        applyFilters();
    });

    // Fungsi Utama Menyaring Item
    function applyFilters() {
        const items = container.querySelectorAll('.reward-item-card');
        items.forEach(item => {
            const cat = item.getAttribute('data-category');
            const isAffordable = item.getAttribute('data-affordable') === 'true';

            let matchCat = (currentCategory === 'all' || cat === currentCategory);
            let matchAffordable = (!showAffordableOnly || isAffordable);

            if (matchCat && matchAffordable) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // 3. SORTING (PENGURUTAN POIN)
    sortSelect.addEventListener('change', function() {
        const items = Array.from(container.querySelectorAll('.reward-item-card'));
        const sortBy = this.value;

        if (sortBy === 'default') return;

        items.sort((a, b) => {
            const ptsA = parseInt(a.getAttribute('data-points'));
            const ptsB = parseInt(b.getAttribute('data-points'));
            return sortBy === 'low' ? ptsA - ptsB : ptsB - ptsA;
        });

        // Susun ulang posisi DOM HTML hasil sort
        items.forEach(item => container.appendChild(item));
    });
});

// Simulasi Klik Redeem Pop-up singkat
const modal = document.getElementById('redeemModal');
const confirmState = document.getElementById('modalConfirmState');
const successState = document.getElementById('modalSuccessState');
const btnConfirm = document.getElementById('btnConfirmAction');

// 1. Fungsi Membuka Pop-up & Suntik Data Hadiah
function confirmRedeem(name, points) {
    document.getElementById('modalRewardName').innerText = name;
    document.getElementById('modalRewardPoints').innerText = points.toLocaleString();
    
    // Reset state tampilan ke konfirmasi awal
    confirmState.style.display = 'block';
    successState.style.display = 'none';
    btnConfirm.innerText = "Yes, Redeem";
    btnConfirm.disabled = false;

    // Munculkan overlay modal
    modal.classList.add('show');
}

// 2. Simulasi Proses Pemotongan Poin (Loading Efek)
function processRedeem() {
    btnConfirm.innerText = "Processing...";
    btnConfirm.disabled = true;

    // Simulasi loading 1 detik (Biar seolah-olah sistem sedang kontak database backend)
    setTimeout(() => {
        // Alihkan tampilan ke State Sukses
        confirmState.style.display = 'none';
        successState.style.display = 'block';
    }, 1000);
}

// 3. Fungsi Menutup Pop-up
function closeRedeemModal() {
    modal.classList.remove('show');
}

// Tambahan: Menutup modal otomatis jika user klik area gelap di luar kotak putih
modal.addEventListener('click', function(e) {
    if (e.target === modal) {
        closeRedeemModal();
    }
});
</script>
@endsection