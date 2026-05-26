@extends('base.base')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
<style>
    .loyalty-page {
        background-color: var(--jaced-caramel-bg);
        padding: 40px 24px;
        min-height: 100vh;
    }

    /* PREMIUM DYNAMIC GRADIENT TIER CARDS */
    .premium-tier-card {
        border-radius: 24px;
        padding: 40px;
        position: relative;
        overflow: hidden;
        color: white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    /* Efek kilau eksklusif di background */
    .premium-tier-card::before {
        content: '';
        position: absolute;
        top: -50%; right: -30%;
        width: 300px; height: 300px;
        background: rgba(255, 255, 255, 0.06);
        border-radius: 50%;
        filter: blur(40px);
    }

    /* Warna Gradasi Tiap Stage */
    .tier-gradient-bronze { background: linear-gradient(135deg, var(--jaced-caramel), #6E4524); }
    .tier-gradient-silver { background: linear-gradient(135deg, #8A95A5, #4A5361); }
    .tier-gradient-gold { background: linear-gradient(135deg, #DFBA73, #A17B30); }
    .tier-gradient-platinum { background: linear-gradient(135deg, #2D3748, #1A202C); }

    /* OVERLAY JIKA PREVIEW TIER YANG TERKUNCI */
    .tier-locked-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.45);
        backdrop-filter: blur(6px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 30;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.4s ease;
    }
    .premium-tier-card.is-preview-locked .tier-locked-overlay {
        opacity: 1;
        pointer-events: auto;
    }

    .tier-badge {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(6px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.05em;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .tier-title {
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.02em;
        margin-top: 15px;
        margin-bottom: 5px;
    }

    /* PROGRESS BAR BANNER */
    .tier-progress-wrap {
        max-width: 350px;
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
        transition: all 0.2s;
    }
    .btn-banner-primary:hover { background: var(--jaced-white); transform: translateY(-1px); }
    
    .btn-banner-outline {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        font-weight: 600;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 10px 22px;
        border-radius: 10px;
        backdrop-filter: blur(4px);
        transition: all 0.2s;
    }
    .btn-banner-outline:hover { background: rgba(255, 255, 255, 0.2); color: white; }

    /* STAGE TABS UNDER BANNER */
    .stage-tabs-container {
        display: flex;
        gap: 10px;
        background: var(--jaced-cream);
        padding: 6px;
        border-radius: 14px;
        margin-top: -15px;
        margin-bottom: 35px;
        position: relative;
        z-index: 40;
        max-width: 450px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
    .stage-tab-new {
        flex: 1;
        text-align: center;
        padding: 8px 12px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 600;
        color: var(--jaced-muted);
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        background: transparent;
    }
    .stage-tab-new.active-tab {
        background: white;
        color: var(--jaced-dark);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .stage-tab-new:hover:not(.active-tab) {
        background: rgba(255, 255, 255, 0.4);
        color: var(--jaced-dark);
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
        transition: all 0.2s;
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
</style>
@endpush

@section('content')
<div class="loyalty-page">
    <div style="max-width: 1100px; margin: 0 auto;">
        
        {{-- BACK BUTTON --}}
        <a href="{{ route('profile') }}" class="back-link mb-4 d-inline-flex align-items-center gap-2 text-decoration-none text-jaced-muted small fw-semibold">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back to Profile</span>
        </a>

        {{-- PAGE HEADER TITLE --}}
        <div class="mb-4 mt-2">
            <h1 class="fw-bold tracking-tight" style="color: var(--jaced-brown-dark); font-size: 2.25rem;">Reward Center</h1>
            <p class="text-jaced-muted small">Accumulate points on every furniture purchase and tier up for VIP rewards.</p>
        </div>

        {{-- MAIN BANNER OVERHAUL (DENGAN IDENTITAS ASLI & OVERLAY PREVIEW) --}}
        <div id="mainTierCard" class="premium-tier-card tier-gradient-{{ strtolower($stage) }} mb-4">
            
            {{-- BLURRED LOCK OVERLAY --}}
            <div class="tier-locked-overlay">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="mb-2 text-white-75"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                <h5 class="fw-bold mb-1 text-white" id="lockOverlayTitle">Silver Stage Locked</h5>
                <p class="small text-white-50 mb-3" id="lockOverlayDesc">This tier will unlock automatically once points criteria met.</p>
                <button class="btn btn-sm btn-light fw-bold px-3 py-1.5" style="border-radius: 8px; font-size: 11px;" onclick="resetToCurrentTier()">Return to My Current Stage</button>
            </div>

            <div class="row align-items-center g-4">
                
                {{-- Sisi Kiri: Member Level & Progress --}}
                <div class="col-12 col-md-7">
                    <div class="tier-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                        <span id="tierLabelContext">CURRENT MEMBERSHIP STAGE</span>
                    </div>
                    
                    <h2 class="tier-title" id="tierCardTitle">{{ strtoupper($stage) }} MEMBER</h2>
                    <p class="small text-white-75 mb-0">Active Tier Period: Valid until 31 Dec 2026</p>

                    {{-- Dinamis Progress Bar --}}
                    <div class="tier-progress-wrap">
                        <div class="custom-progress">
                            <div id="tierProgressBar" class="custom-progress-bar" style="width: {{ $stage == 'Bronze' ? '35%' : ($stage == 'Silver' ? '65%' : '100%') }};"></div>
                        </div>
                        <div class="d-flex justify-content-between text-white-75 small mt-2">
                            <span>Benefits Status: Active</span>
                            <span id="tierPointsInfo" class="fw-semibold">
                                @if($stage == 'Bronze')
                                    180 Pts to Silver
                                @elseif($stage == 'Silver')
                                    500 Pts to Gold
                                @else
                                    Maximum Tier Reached
                                @endif
                            </span>
                        </div>
                    </div>
                    {{-- Perks aktif user --}}
                    @php $activeStage = $stages->firstWhere('name', $stage); @endphp
                    @if($activeStage && $activeStage->additional_perks)
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            @foreach($activeStage->additional_perks as $perk)
                                <span class="tier-badge" style="font-size: 10px;">✓ {{ $perk }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Sisi Kanan: Poin Angka Besar --}}
                <div class="col-12 col-md-5 text-md-end border-start-md border-white-opacity-25 ps-md-5">
                    <p class="text-white-75 small text-uppercase tracking-wider mb-1" style="font-size: 11px; font-weight: 600;">Available Points</p>
                    <div class="d-flex align-items-baseline justify-content-md-end gap-2 mb-1">
                        <h1 class="display-4 fw-bold mb-0 text-white" style="line-height: 1;">{{ number_format($currentPoints) }}</h1>
                        <span class="h5 mb-0 text-white-75">Pts</span>
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

        {{-- STAGE TABS SELECTOR LOOK --}}
        <div class="stage-tabs-container">
            <button id="tab-Bronze" class="stage-tab-new {{ $stage == 'Bronze' ? 'active-tab' : '' }}" onclick="switchTierPreview('Bronze', false)">Bronze</button>
            <button id="tab-Silver" class="stage-tab-new {{ $stage == 'Silver' ? 'active-tab' : '' }}" onclick="switchTierPreview('Silver', {{ $stage == 'Bronze' ? 'true' : 'false' }})">Silver</button>
            <button id="tab-Gold" class="stage-tab-new {{ $stage == 'Gold' ? 'active-tab' : '' }}" onclick="switchTierPreview('Gold', {{ ($stage == 'Bronze' || $stage == 'Silver') ? 'true' : 'false' }})">Gold</button>
            <button id="tab-Platinum" class="stage-tab-new {{ $stage == 'Platinum' ? 'active-tab' : '' }}" onclick="switchTierPreview('Platinum', {{ $stage != 'Platinum' ? 'true' : 'false' }})">Platinum</button>
        </div>

        {{-- BOTTOM CONTROLLER LAYOUT --}}
        <div class="row g-4 align-items-start">
            
            {{-- LAYOUT KIRI: POINT HISTORY --}}
            <div class="col-12 col-lg-5">
                <div class="premium-box">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center gap-2">
                            <h3 class="section-title-new mb-0">Point History</h3>
                            <span class="badge" style="background: var(--jaced-caramel-bg); color: var(--jaced-sage); font-size: 11px;">Last 30 Days</span>
                        </div>
                    </div>

                    <div class="history-list mb-3">
                        @forelse ($pointHistoryItems as $item)
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
                                    <span class="fw-bold {{ ($item['type'] ?? 'earned') === 'redeemed' ? 'text-danger' : 'text-success' }}">
                                        {{ ($item['type'] ?? 'earned') === 'redeemed' ? '-' : '+' }}{{ $item['points'] }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-jaced-muted text-center py-4 small">No point transaction recorded recently.</p>
                        @endforelse
                    </div>

                    <a href="{{ route('point-history') }}" class="text-decoration-none fw-semibold small text-jaced-dark" style="border-bottom: 1px solid var(--jaced-dark);">View All History Details →</a>
                </div>
            </div>

            {{-- LAYOUT KANAN: REDEEM GOALS --}}
            <div class="col-12 col-lg-7">
                <div class="premium-box">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h3 class="section-title-new mb-0">Available Redeem Goals</h3>
                        <a href="{{ route('redeem-point') }}" class="text-decoration-none text-jaced-muted small fw-medium">See All Goals</a>
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
                                                <span class="badge" style="background-color: var(--jaced-caramel-bg); color: var(--jaced-sage); font-size: 10px;">🚚 Gratis Ongkir</span>
                                            @else
                                                <span class="badge" style="background-color: #fcf5f3; color: #bd654e; font-size: 10px;">🏷️ Diskon Produk</span>
                                            @endif
                                        </div>
                                        <h5 class="fw-bold mb-2 text-jaced-dark" style="font-size: 13px; min-height: 38px; line-height: 1.4;">{{ $goal->name }}</h5>
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
<div class="jaced-modal-overlay" id="goalDetailModal" style="display:none; opacity:0;">
    <div class="jaced-modal-box" style="max-width: 380px; width: 90%; text-align: left; padding: 0; overflow: hidden;">
        <img id="goalDetailImg" src="" alt="" style="width: 100%; height: 160px; object-fit: cover;">
        <div style="padding: 24px;">
            <div class="mb-2" id="goalDetailBadge"></div>
            <h3 style="font-size: 16px; font-weight: 700; color: var(--jaced-dark); margin: 0 0 12px;" id="goalDetailName"></h3>
            
            <div style="font-size: 13px; color: var(--jaced-muted); margin-bottom: 20px;">
                <div class="d-flex justify-content-between mb-2">
                    <span>Persentase Diskon</span>
                    <strong id="goalDetailPct" style="color: var(--jaced-dark);"></strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Maksimal Potongan</span>
                    <strong id="goalDetailMax" style="color: var(--jaced-dark);"></strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Point Dibutuhkan</span>
                    <strong id="goalDetailPts" style="color: var(--jaced-caramel);"></strong>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button class="btn-modal-secondary" onclick="closeGoalDetail()" style="background: transparent; border: 1px solid var(--jaced-input); border-radius: 8px; padding: 10px 16px; font-size: 13px; color: var(--jaced-brown-dark);">Tutup</button>
                <div id="goalDetailAction" style="flex: 1;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Inisialisasi data asli dari backend Laravel
    const userRealStage = "{{ $stage }}";
    const initialProgress = "{{ $stage == 'Bronze' ? '35%' : ($stage == 'Silver' ? '65%' : '100%') }}";
    
    const tierDetails = {
        'Bronze': { pct: '35%', info: '180 Pts to Silver' },
        'Silver': { pct: '65%', info: '500 Pts to Gold' },
        'Gold': { pct: '100%', info: 'Maximum Tier Reached' },
        'Platinum': { pct: '100%', info: 'Maximum Tier Reached' }
    };

    const cardEl = document.getElementById('mainTierCard');
    const barEl = document.getElementById('tierProgressBar');
    const titleEl = document.getElementById('tierCardTitle');
    const infoEl = document.getElementById('tierPointsInfo');
    const contextEl = document.getElementById('tierLabelContext');
    const overlayTitle = document.getElementById('lockOverlayTitle');

    function switchTierPreview(targetTier, isLocked) {
        // Atur status active class pada tab menu bawah
        document.querySelectorAll('.stage-tab-new').forEach(btn => btn.classList.remove('active-tab'));
        document.getElementById(`tab-${targetTier}`).classList.add('active-tab');

        // Ganti class gradasi warna background card utama
        cardEl.className = `premium-tier-card tier-gradient-${targetTier.toLowerCase()} mb-4`;
        
        // Update teks & visual bar pendukung di dalam card
        titleEl.innerText = `${targetTier.toUpperCase()} MEMBER`;
        barEl.style.width = tierDetails[targetTier].pct;
        infoEl.innerText = tierDetails[targetTier].info;

        if (isLocked) {
            contextEl.innerText = "PREVIEW MEMBERSHIP STAGE";
            overlayTitle.innerText = `${targetTier} Stage Locked`;
            cardEl.classList.add('is-preview-locked');
        } else {
            contextEl.innerText = targetTier === userRealStage ? "CURRENT MEMBERSHIP STAGE" : "UNLOCKED MEMBERSHIP STAGE";
            cardEl.classList.remove('is-preview-locked');
        }
    }

    function resetToCurrentTier() {
        switchTierPreview(userRealStage, false);
    }

    // Script Modal Detail Voucher
    const goalModal = document.getElementById('goalDetailModal');

    function openGoalDetail(name, usedFor, pct, maxDiscount, pointCost, voucherTypeId, imgSrc, isEnough) {
        document.getElementById('goalDetailImg').src = imgSrc;
        document.getElementById('goalDetailName').innerText = name;
        document.getElementById('goalDetailPct').innerText = pct + '%';
        document.getElementById('goalDetailMax').innerText = 'Rp ' + maxDiscount.toLocaleString('id-ID');
        document.getElementById('goalDetailPts').innerText = pointCost.toLocaleString('id-ID') + ' Points';
        
        const badgeEl = document.getElementById('goalDetailBadge');
        if(usedFor === 'delivery') {
            badgeEl.innerHTML = '<span class="badge" style="background-color: var(--jaced-caramel-bg); color: var(--jaced-sage); font-size: 11px;">🚚 Gratis Ongkir</span>';
        } else {
            badgeEl.innerHTML = '<span class="badge" style="background-color: #fcf5f3; color: #bd654e; font-size: 11px;">🏷️ Diskon Produk</span>';
        }

        const actionEl = document.getElementById('goalDetailAction');
        if(isEnough) {
            actionEl.innerHTML = `
                <form action="{{ route('redeem-point') }}" method="POST">
                    @csrf
                    <input type="hidden" name="voucher_type_id" value="${voucherTypeId}">
                    <button type="submit" class="btn-redeem-now">Redeem Now</button>
                </form>
            `;
        } else {
            actionEl.innerHTML = '<button class="btn-redeem-locked" disabled>Points Insufficient</button>';
        }

        goalModal.style.display = 'flex';
        setTimeout(() => { goalModal.style.opacity = '1'; }, 10);
    }

    function closeGoalDetail() {
        goalModal.style.opacity = '0';
        setTimeout(() => { goalModal.style.display = 'none'; }, 300);
    }
</script>
@endpush