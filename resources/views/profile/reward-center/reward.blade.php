@extends('base.base')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
<style>
    .loyalty-page {
        background-color: var(--jaced-caramel-bg);
        padding: 40px 24px;
        min-height: 100vh;
    }

    @keyframes pageIn {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .page-enter {
        animation: pageIn 0.45s cubic-bezier(0.25, 1, 0.5, 1) both;
    }
    .page-enter-delay-1 { animation-delay: 0.08s; }
    .page-enter-delay-2 { animation-delay: 0.16s; }
    .page-enter-delay-3 { animation-delay: 0.24s; }

    /* PREMIUM DYNAMIC GRADIENT TIER CARDS */
    .premium-tier-card {
        border-radius: 24px;
        padding: 40px;
        position: relative;
        overflow: hidden;
        color: white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: box-shadow 0.3s ease;
    }

    /* Efek kilau eksklusif di background */
    .premium-tier-card > * { position: relative; z-index: 2; }

    .premium-tier-card .tier-badge,
    .premium-tier-card .small,
    .premium-tier-card .stage-tab-pill {
        text-shadow: 0 1px 4px rgba(0,0,0,0.2);
    }

    /* Warna Gradasi Tiap Stage */
    .tier-gradient-bronze { 
        /* background: linear-gradient(160deg, #e8a96a 0%, #c47a35 25%, #8B5E2A 55%, #6E4524 75%, #9d6b38 100%); */
    }
    .tier-gradient-silver { 
        /* background: linear-gradient(160deg, #d0d8e4 0%, #9aa5b4 25%, #5a6472 55%, #3a4250 75%, #7a8898 100%); */
    }
    .tier-gradient-gold { 
        /* background: linear-gradient(160deg, #f5e070 0%, #d4a825 25%, #9a7510 55%, #7a5c08 75%, #c49820 100%); */
    }
    .tier-gradient-platinum { 
        /* background: linear-gradient(160deg, #6b7280 0%, #374151 25%, #1a202c 55%, #0d1117 75%, #4a5568 100%); */
    }

    .tier-badge {
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.05em;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .tier-badge.status-current {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.4);
        color: white;
    }
    .tier-badge.status-unlocked {
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.25);
        color: rgba(255,255,255,0.8);
    }
    .tier-badge.status-locked {
        background: rgba(0,0,0,0.25);
        border: 1px solid rgba(255,255,255,0.15);
        color: rgba(255,255,255,0.6);
    }

    .premium-tier-card.is-locked .tier-title,
    .premium-tier-card.is-locked .tier-progress-wrap,
    .premium-tier-card.is-locked .stage-tab-pill:not(.active-pill),
    .premium-tier-card.is-locked p.small {
        opacity: 0.45;
    }

    .premium-tier-card.is-locked .tier-title {
        opacity: 0.5;
        filter: grayscale(0.3);
    }

    .tier-title {
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.02em;
        margin-top: 15px;
        margin-bottom: 5px;
        text-shadow: 0 1px 8px rgba(0,0,0,0.25);
    }

    /* PROGRESS BAR BANNER */
    .tier-progress-wrap {
        max-width: 420px;
        margin-top: 20px;
    }
    .custom-progress {
        height: 6px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 999px;
        overflow: hidden;
    }
    .custom-progress-bar {
        height: 100%;
        background: white;
        border-radius: 999px;
        transition: width 0.5s ease;
    }

    /* ACTION BUTTONS IN BANNER */
    .btn-banner-primary {
        background: white;
        color: var(--jaced-dark);
        font-weight: 600;
        border: none;
        padding: 10px 22px;
        border-radius: 10px;
        transition: all 0.2s ease;
    }
    .btn-banner-primary:hover { 
        background: var(--jaced-white); 
        transform: translateY(-2px); 
        box-shadow: 0 6px 16px rgba(0,0,0,0.12);
    }
    
    .btn-banner-outline {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        font-weight: 600;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 10px 22px;
        border-radius: 10px;
        backdrop-filter: blur(4px);
        transition: all 0.2s ease;
    }
    .btn-banner-outline:hover { 
        background: rgba(255,255,255,0.25);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        color: white;
    }

    .btn-view-all-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 600;
        color: var(--jaced-brown-dark);
        text-decoration: none !important;
        padding: 6px 0;
        transition: color 0.2s ease;
    }
    .btn-view-all-link:hover { 
        color: var(--jaced-caramel) !important; 
        text-decoration: none !important;
    }

    /* STAGE TABS UNDER BANNER */
    .stage-tab-pill {
        padding: 5px 14px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        border: 1px solid rgba(255,255,255,0.3);
        background: rgba(255,255,255,0.1);
        color: rgba(255,255,255,0.7);
        cursor: pointer;
        transition: all 0.2s ease;
        letter-spacing: 0.03em;
    }
    .stage-tab-pill:hover {
        background: rgba(255,255,255,0.2);
        color: white;
    }
    .stage-tab-pill.active-pill {
        background: white;
        color: var(--jaced-brown-dark);
        border-color: white;
    }

    /* LAYOUT CARDS BELOW */
    .premium-box {
        background: white;
        border-radius: 20px;
        padding: 28px;
        border: 1px solid var(--jaced-input);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.01);
    }

    .section-title-new {
        font-size: 18px;
        font-weight: 700;
        color: var(--jaced-brown-dark);
    }

    /* RENDER CLEAN HISTORY */
    .history-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 0;
        border-bottom: 1px solid var(--jaced-card);
        border-radius: 10px;
        transition: background 0.15s ease;
        padding: 14px 10px; /* kasih padding kiri kanan biar hover keliatan */
        cursor: pointer;
    }

    .history-row:hover {
        background: var(--jaced-caramel-bg);
    }
    .history-row:last-child { border-bottom: none; }

    /* REDEEM GOALS OVERHAUL */
    .goal-card-new {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid var(--jaced-input);
        transition: all 0.3s ease;
    }
    .goal-card-new:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.04);
    }
    .btn-view-details {
        background: transparent;
        color: var(--jaced-sage);
        border: 1px solid var(--jaced-sage);
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        width: 100%;
        transition: all 0.2s ease;
    }
    .btn-view-details:hover { background: var(--jaced-sage); color: white; }

    /* MODAL EXTRAS */
    .jaced-modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(28, 28, 26, 0.6);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        transition: opacity 0.3s ease;
    }
    .jaced-modal-box {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
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
    }
    .btn-redeem-locked {
        background: var(--jaced-card);
        color: var(--jaced-muted);
        border: none;
        border-radius: 8px;
        padding: 11px 20px;
        font-size: 13px;
        font-weight: 600;
        width: 100%;
        cursor: not-allowed;
    }

    @media (max-width: 767px) {
        .btn-banner-primary,
        .btn-banner-outline {
            flex: 1;
            text-align: center;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="loyalty-page">
    @if(session('success'))
        <div id="successToast" style="
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 99999;
            background: white;
            border-radius: 14px;
            padding: 16px 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            border-left: 4px solid #4a7c59;
            max-width: 340px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.3s ease;
        ">
            <div style="width: 36px; height: 36px; background: #edf7ed; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4a7c59" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div>
                <p class="mb-0 fw-bold" style="font-size: 13px; color: var(--jaced-brown-dark);">Success!</p>
                <p class="mb-0" style="font-size: 12px; color: var(--jaced-muted);">{{ session('success') }}</p>
            </div>
            <button onclick="document.getElementById('successToast').remove()" style="background: none; border: none; color: var(--jaced-muted); cursor: pointer; font-size: 16px; margin-left: auto;">×</button>
        </div>

        <style>
            @keyframes slideIn {
                from { transform: translateX(100px); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        </style>

        <script>
            setTimeout(() => {
                const toast = document.getElementById('successToast');
                if (toast) toast.remove();
            }, 4000);
        </script>
    @endif
    <div style="max-width: 1100px; margin: 0 auto;">
        
        {{-- BACK BUTTON --}}
        <a href="{{ route('home') }}" class="back-link mb-4 d-inline-flex align-items-center gap-2 text-decoration-none text-jaced-muted small fw-semibold">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back to Home</span>
        </a>

        {{-- PAGE HEADER TITLE --}}
        <div class="mb-4 mt-2 page-enter">
            <h1 class="fw-bold tracking-tight" style="color: var(--jaced-brown-dark); font-size: 2.25rem;">Reward Center</h1>
            <p class="text-jaced-muted small">Accumulate points on every furniture purchase and tier up for VIP rewards.</p>
        </div>

        {{-- MAIN BANNER OVERHAUL (DENGAN IDENTITAS ASLI & OVERLAY PREVIEW) --}}
        <div id="mainTierCard" class="premium-tier-card mb-4 page-enter">
            <div id="tierLayerA" style="position:absolute; inset:0; border-radius:24px; z-index:0; transition:opacity 0.5s ease; pointer-events:none;"></div>
            <div id="tierLayerB" style="position:absolute; inset:0; border-radius:24px; z-index:0; transition:opacity 0.5s ease; pointer-events:none; opacity:0;"></div>
            <div class="row align-items-center g-4">
                
                {{-- Sisi Kiri: Member Level & Progress --}}
                <div class="col-12 col-md-7">
                    <div class="tier-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                        <span id="tierLabelContext">CURRENT MEMBERSHIP STAGE</span>
                    </div>
                    
                    <h2 class="tier-title" id="tierCardTitle">{{ strtoupper($stage) }} MEMBER</h2>
                    <p class="small text-white-75 mb-0">Active Tier Period: Valid until 31 Dec 2026</p>
                    <div class="d-flex gap-2 flex-wrap mt-3">
                        <button id="tab-Bronze"   class="stage-tab-pill {{ $stage == 'Bronze'   ? 'active-pill' : '' }}" onclick="switchTierPreview('Bronze')">Bronze</button>
                        <button id="tab-Silver"   class="stage-tab-pill {{ $stage == 'Silver'   ? 'active-pill' : '' }}" onclick="switchTierPreview('Silver')">Silver</button>
                        <button id="tab-Gold"     class="stage-tab-pill {{ $stage == 'Gold'     ? 'active-pill' : '' }}" onclick="switchTierPreview('Gold')">Gold</button>
                        <button id="tab-Platinum" class="stage-tab-pill {{ $stage == 'Platinum' ? 'active-pill' : '' }}" onclick="switchTierPreview('Platinum')">Platinum</button>
                    </div>

                    {{-- Dinamis Progress Bar --}}
                    <div class="tier-progress-wrap">
                        <div class="custom-progress">
                            <div id="tierProgressBar" class="custom-progress-bar" style="width: 0%;"></div>
                        </div>
                        <div class="d-flex justify-content-between text-white-75 small mt-2" style="gap: 12px;">
                            <span id="benefitStatusLabel" style="white-space: nowrap;">Benefits Status: Active</span>
                            <span id="tierPointsInfo" class="fw-semibold" style="white-space: nowrap; text-align: right;"></span>
                        </div>
                    </div>
                </div>

                {{-- Sisi Kanan: Poin Angka Besar --}}
                <div class="col-12 col-md-5 text-md-end border-start-md border-white-opacity-25 ps-md-5">
                    <p class="text-white-75 small text-uppercase tracking-wider mb-1" style="font-size: 11px; font-weight: 600;">Available Points</p>
                    <div class="d-flex align-items-baseline justify-content-md-end gap-2 mb-1">
                        <h1 class="display-4 fw-bold mb-0 text-white" style="line-height: 1;">{{ number_format($currentPoints) }}</h1>
                        <span class="h5 mb-0 text-white-75">Pts</span>
                    </div>
                    <div id="tierBenefitBadge" class="mb-3">
                        <span style="background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.25); padding:5px 12px; border-radius:8px; font-size:11px; font-weight:700; color:white; letter-spacing:0.03em;"></span>
                    </div>
                    <p class="text-white-50 small mb-4">
                        Lifetime Accumulation: <strong>{{ number_format($accumulatedPoints) }} Pts</strong>
                    </p>

                    {{-- Actions Button --}}
                    <div class="d-flex justify-content-md-end gap-2">
                        <a href="{{ route('redeem-point') }}" class="text-decoration-none">
                            <button class="btn-banner-primary">Redeem Points</button>
                        </a>
                        <a href="{{ route('voucher') }}" class="btn-banner-outline text-decoration-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/>
                            </svg>
                            My Vouchers
                        </a>
                    </div>
                </div>

            </div>
        </div>

        {{-- BOTTOM CONTROLLER LAYOUT --}}
        <div class="row g-4 align-items-start page-enter page-enter-delay-2">
            
            {{-- LAYOUT KIRI: POINT HISTORY --}}
            <div class="col-12 col-lg-5" style="align-self:stretch;">
                <div class="premium-box d-flex flex-column" style="height:100%;">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center gap-2">
                            <h3 class="section-title-new mb-0">Point History</h3>
                            <span class="badge" style="background: var(--jaced-caramel-bg); color: var(--jaced-sage); font-size: 11px;">Last 30 Days</span>
                        </div>
                    </div>

                    <div class="history-list flex-grow-1" style="overflow-y:auto; overflow-x:hidden; max-height:320px;">
                        @forelse ($pointHistoryItems as $item)
                            <a href="{{ route('point-history') }}?open={{ $item['id'] }}" class="text-decoration-none">
                                <div class="history-row">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="point-history-icon" style="background: var(--jaced-cream); color: var(--jaced-brown-dark); width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-bold small text-jaced-dark">{{ $item['source'] }}</p>
                                            <p class="mb-0 text-jaced-muted" style="font-size: 11px;">{{ $item['date'] }}</p>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="fw-bold {{ in_array(($item['type'] ?? 'earned'), ['redeemed', 'expired']) ? 'text-danger' : 'text-success' }}">
                                            {{ in_array(($item['type'] ?? 'earned'), ['redeemed', 'expired']) ? '' : '+' }}{{ $item['points'] }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <p class="text-jaced-muted text-center py-4 small">No point transaction recorded recently.</p>
                        @endforelse
                    </div>

                    <div style="padding-top:14px; border-top:1px solid var(--jaced-card); margin-top:auto;">
                        <a href="{{ route('point-history') }}" class="btn-view-all-link">
                            View All History Details
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- LAYOUT KANAN: REDEEM GOALS --}}
            <div class="col-12 col-lg-7">
                <div class="premium-box">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h3 class="section-title-new mb-0">Available Redeem Goals</h3>
                        <a href="{{ route('redeem-point') }}" class="btn-view-all-link" style="font-size:12px; color:var(--jaced-muted);">See All Goals</a>
                    </div>

                    <div class="row g-3">
                        @foreach ($redeemGoals as $goal)
                            @php 
                                $isEnough = $currentPoints >= $goal->point_cost; 
                                $voucherImage = match(true) {
                                    $goal->used_for === 'delivery'         => 'disc-ongkir.png',
                                    $goal->discount_percentage === 100     => 'disc-100.png',
                                    $goal->discount_percentage === 50      => 'disc-50.png',
                                    default                                => 'disc-product-default.png',
                                };
                            @endphp
                            <div class="col-12 col-sm-6">
                                <div class="goal-card-new">
                                    <img src="{{ asset('image/vouchers/' . $voucherImage) }}" alt="{{ $goal->name }}" style="width: 100%; height: 130px; object-fit: cover;">
                                    <div class="p-3">
                                        <div class="mb-2">
                                            @if($goal->used_for === 'delivery')
                                                <span class="badge d-inline-flex align-items-center gap-1" style="background-color: var(--jaced-caramel-bg); color: var(--jaced-sage); font-size: 10px;">
                                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                                    Free Shipping
                                                </span>
                                            @else
                                                <span class="badge d-inline-flex align-items-center gap-1" style="background-color: #fcf5f3; color: #bd654e; font-size: 10px;">
                                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                                    Product Discount
                                                </span>
                                            @endif
                                        </div>
                                        <h5 class="fw-bold mb-2 text-jaced-dark" style="font-size: 13px; min-height: 38px; line-height: 1.4;">{{ $goal->name }}</h5>
                                        <p style="font-size: 11px; color: var(--jaced-muted); margin-bottom: 4px;">
                                            Available stock: {{ $goal->stock }} voucher{{ $goal->stock > 1 ? 's' : '' }}
                                        </p>
                                        <p class="mb-3 text-jaced-muted small">
                                            Cost: <span class="text-jaced-dark fw-bold" style="font-size: 15px;">{{ number_format($goal->point_cost) }}</span> Pts
                                        </p>

                                        <button class="btn-view-details"
                                            onclick="openGoalDetail(
                                                '{{ $goal->name }}',
                                                '{{ $goal->used_for }}',
                                                {{ $goal->discount_percentage }},
                                                {{ $goal->max_discount }},
                                                {{ $goal->point_cost }},
                                                '{{ $goal->id }}',
                                                '{{ asset('image/vouchers/' . $voucherImage) }}',
                                                '{{ addslashes($goal->description) }}',
                                                {{ $isEnough ? 'true' : 'false' }}
                                            )">
                                            View Details
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- MODAL DETAIL VOUCHER --}}
<div class="jaced-modal-overlay" id="goalDetailModal" style="display:none; opacity:0;" onclick="handleModalBackdropClick(event)">
    <div class="jaced-modal-box" style="max-width: 380px; width: 90%; text-align: left; padding: 0; overflow: hidden;">
        <img id="goalDetailImg" src="" alt="" style="width: 100%; height: 160px; object-fit: cover;">
        <div style="padding: 24px;">
            <div class="mb-2" id="goalDetailBadge"></div>
            <h3 style="font-size: 16px; font-weight: 700; color: var(--jaced-dark); margin: 0 0 12px;" id="goalDetailName"></h3>
      
            <div class="d-flex flex-column mb-2">
                <span style="font-size: 12px; color: var(--jaced-muted);">Description</span>
                <span id="goalDetailDesc" style="font-size: 13px; color: var(--jaced-dark);"></span>
            </div>
            <div style="font-size: 13px; color: var(--jaced-muted); margin-bottom: 20px;">
                <div class="d-flex justify-content-between mb-2">
                    <span>Discount Percentage</span>
                    <strong id="goalDetailPct" style="color: var(--jaced-dark);"></strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Maximum Discount</span>
                    <strong id="goalDetailMax" style="color: var(--jaced-dark);"></strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Points Required</span>
                    <strong id="goalDetailPts" style="color: var(--jaced-caramel);"></strong>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button class="btn-modal-secondary" onclick="closeGoalDetail()" style="background: transparent; border: 1px solid var(--jaced-input); border-radius: 8px; padding: 10px 16px; font-size: 13px; color: var(--jaced-brown-dark);">Close</button>
                <div id="goalDetailAction" style="flex: 1;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const stagesData = @json($stages->map(fn($s) => [
        'name' => $s->name,
        'min' => $s->min_points_accumulative
    ]));
    const userAccumulatedPoints = {{ $accumulatedPoints }};
    const userCurrentStage = "{{ $stage }}";
</script>
<script>
    const tierBgMap = {
        'bronze':   'linear-gradient(160deg, #e8a96a 0%, #c47a35 25%, #8B5E2A 55%, #6E4524 75%, #9d6b38 100%)',
        'silver':   'linear-gradient(160deg, #d0d8e4 0%, #9aa5b4 25%, #5a6472 55%, #3a4250 75%, #7a8898 100%)',
        'gold':     'linear-gradient(160deg, #f5e070 0%, #d4a825 25%, #9a7510 55%, #7a5c08 75%, #c49820 100%)',
        'platinum': 'linear-gradient(160deg, #6b7280 0%, #374151 25%, #1a202c 55%, #0d1117 75%, #4a5568 100%)',
    };

    const layerA = document.getElementById('tierLayerA');
    const layerB = document.getElementById('tierLayerB');
    let activeLayer = 'A';

    // Set initial background
    layerA.style.background = tierBgMap[userCurrentStage.toLowerCase()];
    layerA.style.opacity = '1';

    const cardEl    = document.getElementById('mainTierCard');
    const barEl     = document.getElementById('tierProgressBar');
    const titleEl   = document.getElementById('tierCardTitle');
    const infoEl    = document.getElementById('tierPointsInfo');

    function getProgressForTier(tierName) {
        const sorted = [...stagesData].sort((a, b) => a.min - b.min);
        const idx    = sorted.findIndex(s => s.name === tierName);
        const tier   = sorted[idx];
        const next   = sorted[idx + 1];

        // Sudah unlock tier ini → full
        if (userAccumulatedPoints >= tier.min) {
            if (!next) return { pct: 100, info: 'Maximum Tier Reached' };
            return { pct: 100, info: `${tierName} Unlocked` };
        }

        // Belum unlock → hitung dari 0 menuju threshold tier ini
        const pct       = Math.min(Math.round((userAccumulatedPoints / tier.min) * 100), 99);
        const remaining = tier.min - userAccumulatedPoints;

        return {
            pct,
            info: `${remaining.toLocaleString('id-ID')} Pts to unlock ${tierName}`
        };
    }

    function switchTierPreview(targetTier) {
        // Update active tab
        document.querySelectorAll('.stage-tab-pill').forEach(btn => btn.classList.remove('active-pill'));
        document.getElementById(`tab-${targetTier}`).classList.add('active-pill');

        // Ganti gradasi card
        const newGrad = targetTier.toLowerCase();
        const newBg = tierBgMap[newGrad];

        if (activeLayer === 'A') {
            layerB.style.background = newBg;
            layerB.style.opacity = '1';
            layerA.style.opacity = '0';
            activeLayer = 'B';
        } else {
            layerA.style.background = newBg;
            layerA.style.opacity = '1';
            layerB.style.opacity = '0';
            activeLayer = 'A';
        }

        // Hitung progress dinamis
        const tierData = stagesData.find(s => s.name === targetTier);
        const isLocked = userAccumulatedPoints < tierData.min;

        titleEl.style.transition = 'opacity 0.2s ease';
        titleEl.style.opacity = '0';
        setTimeout(() => {
            titleEl.innerText = `${targetTier.toUpperCase()} MEMBER`;
            titleEl.style.opacity = '1';
        }, 200);
        const { pct, info } = getProgressForTier(targetTier);
        barEl.style.width = pct + '%';
        infoEl.innerText  = info;

        cardEl.className = `premium-tier-card mb-4${isLocked ? ' is-locked' : ''}`;
        const benefitLabel = document.getElementById('benefitStatusLabel');
        benefitLabel.innerText = isLocked ? 'Benefits Status: Locked' : 'Benefits Status: Active';

        const benefitMap = {
            'Bronze':   'No discount benefit yet',
            'Silver':   '5% discount on every order',
            'Gold':     '10% discount on every order',
            'Platinum': '15% discount on every order',
        };
        const benefitBadge = document.querySelector('#tierBenefitBadge span');
        if (benefitBadge) benefitBadge.innerText = '✦ ' + (benefitMap[targetTier] || '');

        const badgeEl = document.querySelector('.tier-badge');
        badgeEl.classList.remove('status-current', 'status-unlocked', 'status-locked');

        const lockIcon = `<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>`;
        const checkIcon = `<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`;
        const starIcon = `<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>`;

        if (isLocked) {
            badgeEl.classList.add('status-locked');
            badgeEl.innerHTML = lockIcon + '<span>LOCKED — NOT YET UNLOCKED</span>';
        } else if (targetTier === userCurrentStage) {
            badgeEl.classList.add('status-current');
            badgeEl.innerHTML = starIcon + '<span>YOUR CURRENT STAGE</span>';
        } else {
            badgeEl.classList.add('status-unlocked');
            badgeEl.innerHTML = checkIcon + '<span>UNLOCKED STAGE</span>';
        }
    }


    // Modal logic (tidak berubah)
    const goalModal = document.getElementById('goalDetailModal');

    function openGoalDetail(name, usedFor, pct, maxDiscount, pointCost, voucherTypeId, imgSrc, description, isEnough) {
        document.getElementById('goalDetailImg').src = imgSrc;
        document.getElementById('goalDetailName').innerText = name;
        document.getElementById('goalDetailPct').innerText = pct + '%';
        document.getElementById('goalDetailMax').innerText = 'Rp ' + maxDiscount.toLocaleString('id-ID');
        document.getElementById('goalDetailPts').innerText = pointCost.toLocaleString('id-ID') + ' Points';
        document.getElementById('goalDetailDesc').innerText = description;

        const badgeEl = document.getElementById('goalDetailBadge');
        badgeEl.innerHTML = usedFor === 'delivery'
            ? `<span class="badge d-inline-flex align-items-center gap-1" style="background-color: var(--jaced-caramel-bg); color: var(--jaced-sage); font-size: 11px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg> Free Shipping</span>`
            : `<span class="badge d-inline-flex align-items-center gap-1" style="background-color: #fcf5f3; color: #bd654e; font-size: 11px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg> Product Discount</span>`;

        const actionEl = document.getElementById('goalDetailAction');
        actionEl.innerHTML = isEnough
            ? `<form action="{{ route('reward.redeem') }}" method="POST">
                @csrf
                <input type="hidden" name="voucher_type_id" value="${voucherTypeId}">
                <button type="submit" class="btn-redeem-now">Redeem Now</button>
            </form>`
            : '<button class="btn-redeem-locked" disabled>Points Insufficient</button>';

        goalModal.style.display = 'flex';
        setTimeout(() => { goalModal.style.opacity = '1'; }, 10);
    }

    function closeGoalDetail() {
        goalModal.style.opacity = '0';
        setTimeout(() => { goalModal.style.display = 'none'; }, 300);
    }

    function handleModalBackdropClick(e) {
        if (e.target === document.getElementById('goalDetailModal')) closeGoalDetail();
    }

    // Inisialisasi tampilan sesuai tier user saat ini
    switchTierPreview(userCurrentStage);
</script>
@endpush