@extends('base.base')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
<style>
    .loyalty-page {
        background-color: var(--jaced-cream);
        padding: 40px 24px;
        min-height: 100vh;
    }

    /* HEADER CARD */
    .header-card {
        background: white;
        border-radius: 16px;
        padding: 28px 32px;
        border: 1px solid var(--jaced-input);
    }
    .header-vdivider {
        width: 1px;
        background: var(--jaced-input);
        align-self: stretch;
        margin: 4px 0;
    }

    /* STAGE */
    .stage-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--jaced-caramel-bg);
        border-radius: 999px;
        padding: 4px 12px;
        margin-bottom: 10px;
    }
    .stage-badge-text {
        font-size: 12px;
        font-weight: 600;
        color: var(--jaced-caramel);
    }
    .stage-name {
        font-size: 28px;
        font-weight: 700;
        color: var(--jaced-brown-dark);
        margin: 0 0 6px;
    }
    .stage-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .stage-valid {
        font-size: 12px;
        color: var(--jaced-muted);
        margin: 0;
    }
    .benefit-link {
        font-size: 12px;
        color: var(--jaced-caramel);
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: opacity .2s;
    }
    .benefit-link:hover { opacity: .7; }

    /* STAGE TABS */
    .stage-tabs {
        display: flex;
        gap: 6px;
    }
    .stage-tab {
        flex: 1;
        text-align: center;
        padding: 7px 4px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid var(--jaced-input);
        color: var(--jaced-muted);
        background: var(--jaced-cream);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        transition: all .15s;
    }
    .stage-tab.current {
        background: var(--jaced-caramel-bg);
        border-color: var(--jaced-caramel);
        color: var(--jaced-caramel);
    }
    .stage-tab.locked { opacity: 0.55; cursor: pointer; }
    .stage-tab.locked:hover { opacity: 0.8; }

    /* POPOVER */
    .popover-wrap { position: relative; }
    .stage-popover {
        display: none;
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        width: 260px;
        background: white;
        border: 1px solid var(--jaced-input);
        border-radius: 12px;
        padding: 14px 16px;
        /* Naikkan z-index menjadi 999 agar berada di lapisan paling atas */
        z-index: 999; 
        box-shadow: 0 4px 24px rgba(0,0,0,.15);
    }
    .stage-popover.open { display: block; }
    .popover-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--jaced-brown-dark);
        margin: 0 0 10px;
    }
    .benefit-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 7px 0;
        border-bottom: 1px solid var(--jaced-input);
        font-size: 13px;
        color: var(--jaced-brown-dark);
    }
    .benefit-item:last-of-type { border-bottom: none; }
    .benefit-item.locked { opacity: 0.4; }
    .benefit-item svg { flex-shrink: 0; color: var(--jaced-muted); }
    .how-to-link {
        font-size: 12px;
        color: var(--jaced-caramel);
        text-decoration: none;
        display: block;
        margin-top: 10px;
        font-weight: 500;
    }
    .how-to-link:hover { text-decoration: underline; color: var(--jaced-caramel); }

    /* POINTS + BUTTONS */
    .pts-label {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--jaced-muted);
        margin: 0 0 4px;
    }
    .pts-val {
        font-size: 32px;
        font-weight: 700;
        color: var(--jaced-brown-dark);
        margin: 0;
        line-height: 1;
    }
    .pts-unit {
        font-size: 13px;
        color: var(--jaced-caramel);
        font-weight: 500;
        margin: 0 0 20px;
    }
    .btn-redeem {
        background: var(--jaced-caramel);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 9px 16px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
        margin-bottom: 8px;
        transition: background .2s;
    }
    .btn-redeem:hover { background: #b8854f; }
    .btn-voucher {
        background: transparent;
        color: var(--jaced-sage);
        border: 1.5px solid var(--jaced-sage);
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all .2s;
    }
    .btn-voucher:hover { background: var(--jaced-sage); color: white; }

    /* STATS */
    .stat-pill {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-width: 60px;
    }
    .stat-val {
        font-size: 20px;
        font-weight: 700;
        color: var(--jaced-brown-dark);
        line-height: 1;
        margin: 0 0 3px;
    }
    .stat-label {
        font-size: 10px;
        color: var(--jaced-muted);
        text-align: center;
        margin: 0;
    }
    .stat-divider {
        width: 1px;
        height: 32px;
        background: var(--jaced-input);
    }

    /* SECTION TITLES */
    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--jaced-brown-dark);
        margin: 0 0 16px;
    }
    .section-badge {
        font-size: 11px;
        font-weight: 600;
        color: #3da353;
        background: #ddf4e0;
        border-radius: 999px;
        padding: 3px 10px;
        margin-left: 8px;
    }

    /* EXPIRING CARD */
    .point-history-card {
        background: white;
        border-radius: 14px;
        padding: 15px 24px;
        padding-top: 5px;
        display: block !important;
        height: auto !important;
        min-height: 0 !important;
    }
    .point-history-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 0;
        border-bottom: 1px solid var(--jaced-input);
    }
    .point-history-item:last-child { border-bottom: none; }
    .point-history-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: var(--jaced-caramel-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--jaced-caramel);
    }
    .point-history-pts {
        font-size: 14px;
        font-weight: 700;
        color: var(--jaced-brown-dark);
        margin: 0 0 2px;
    }
    .point-history-src {
        font-size: 12px;
        color: var(--jaced-muted);
        margin: 0;
    }
    .point-history-date {
        font-size: 12px;
        font-weight: 600;
        color: #3da353;
        margin: 0;
        white-space: nowrap;
    }
    .point-history-days-label {
        font-size: 11px;
        color: var(--jaced-muted);
        margin: 0;
    }
    .view-all-link {
        font-size: 13px;
        color: var(--jaced-caramel);
        text-decoration: none;
        font-weight: 500;
        display: inline-block;
        margin-top: 14px;
    }
    .view-all-link:hover { text-decoration: underline; color: var(--jaced-caramel); }

    /* REDEEM GOALS */
    .redeem-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }
    .favorites-btn {
        background: none;
        border: 1px solid var(--jaced-input);
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 12px;
        color: var(--jaced-muted);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all .2s;
    }
    .favorites-btn:hover { border-color: var(--jaced-sage); color: var(--jaced-sage); }

    .redeem-card {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        border: none;
    }
    .redeem-card-img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        display: block;
    }
    .redeem-card-body {
        padding: 16px;
    }
    .redeem-card-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--jaced-brown-dark);
        margin: 0 0 12px;
    }
    .progress-label {
        display: flex;
        justify-content: space-between;
        font-size: 11px;
        color: var(--jaced-muted);
        margin-bottom: 6px;
    }
    .progress-bar-wrap {
        background: var(--jaced-input);
        border-radius: 999px;
        height: 6px;
        margin-bottom: 14px;
        overflow: hidden;
    }
    .progress-bar-fill {
        height: 100%;
        border-radius: 999px;
        background: var(--jaced-caramel);
    }
    .goal-status {
        font-size: 11px;
        font-weight: 600;
        color: var(--jaced-sage);
        margin: 0 0 8px;
    }
    .btn-redeem-now {
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
    .btn-redeem-now:hover { background: #4a5d4b; }
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
    .heart-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 1px 4px rgba(0,0,0,.1);
    }
</style>
@endpush

@section('content')
<div class="loyalty-page">
    <div style="max-width: 1000px; margin: 0 auto;">
        {{-- BACK --}}
        <a href="{{ route('profile') }}" class="back-link">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back</span>
        </a>

        {{-- PAGE TITLE --}}
        <div class="mb-4">
            <h1 style="font-size: 2rem; font-weight: 700; color: var(--jaced-brown-dark); margin: 0 0 4px;">Reward Center</h1>
            <p style="font-size: 13px; color: var(--jaced-muted); margin: 0;">Earn points, redeem rewards, and enjoy exclusive perks.</p>
        </div>

        {{-- HEADER CARD --}}
        <div class="header-card mb-4">
            <div class="row align-items-center g-0">

                {{-- Col 1: Stage --}}
                <div class="col-12 col-md-7 pe-md-4">
                    <div class="stage-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                        <span class="stage-badge-text">Current stage</span>
                    </div>

                    {{-- FIX DOSEN: Teks Stage Dinamis --}}
                    <p class="stage-name">{{ $stage }}</p>

                    <div class="stage-meta">
                        <span class="stage-valid">Active until 31 Dec 2026</span>
                        <span style="color: var(--jaced-input);">·</span>
                        <div class="popover-wrap">
                            <button class="benefit-link" onclick="togglePopover('bronze-pop')">
                                See benefits
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                            </button>
                            <div class="stage-popover" id="bronze-pop">
                                <p class="popover-title">{{ $stage }} benefits</p>
                                <div class="benefit-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                    Benefit active for {{ $stage }} member
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Stage Tabs dengan Highlight Class Current Otomatis --}}
                    <div class="stage-tabs">
                        <div class="popover-wrap" style="flex: 1; position: relative;">
                            <div class="stage-tab {{ $stage == 'Bronze' ? 'current' : '' }}" onclick="togglePopover(event, 'bronze-tab-pop')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                                Bronze
                            </div>
                        </div>

                        <div class="popover-wrap" style="flex: 1; position: relative;">
                            <div class="stage-tab {{ $stage == 'Silver' ? 'current' : 'locked' }} w-100" onclick="togglePopover(event, 'silver-pop')">
                                @if($stage == 'Bronze')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                @endif
                                Silver
                            </div>
                        </div>

                        <div class="popover-wrap" style="flex: 1; position: relative;">
                            <div class="stage-tab {{ $stage == 'Gold' ? 'current' : 'locked' }} w-100" onclick="togglePopover(event, 'gold-pop')">
                                @if($stage != 'Gold')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                @endif
                                Gold
                            </div>
                        </div>
                    </div>
                </div>

                <div class="header-vdivider d-none d-md-block mx-4"></div>

                {{-- Col 2: Points Display --}}
                <div class="col-12 col-md-4 py-3 py-md-0">
                    <p class="pts-label">Points Available to Redeem</p>
                    <div class="d-flex align-items-baseline gap-2 mb-1">
                        {{-- FIX DOSEN: Tampilkan Current Points --}}
                        <p class="pts-val mb-0">{{ number_format($currentPoints) }}</p>
                        <p class="pts-unit mb-0">Points</p>
                    </div>
                    
                    {{-- FIX DOSEN: Tambahkan Info Akumulasi Poin Di Sini --}}
                    <p class="text-muted mb-4" style="font-size: 12px;">
                        Lifetime Accumulation: <strong>{{ number_format($accumulatedPoints) }} Pts</strong>
                    </p>

                    <div class="d-flex gap-2">
                        <a href="{{ route('redeem-point') }}" style="flex: 1; text-decoration: none;">
                            <button class="btn-redeem mb-0" style="width: 100%; white-space: nowrap;">
                                Redeem Now
                            </button>
                        </a>
                        <a href="{{ route('voucher') }}" class="btn-voucher text-decoration-none" style="flex: 1; white-space: nowrap;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/>
                                <path d="M13 5v2"/>
                                <path d="M13 17v2"/>
                                <path d="M13 11v2"/>
                            </svg>
                            My Vouchers
                        </a>
                    </div>
                </div>

            </div>
        </div>

        {{-- BOTTOM SECTION --}}
        <div class="row g-4 align-items-start">

            {{-- LEFT: Point History --}}
            <div class="col-12 col-lg-4">
                <div class="d-flex align-items-center mb-3">
                    <p class="section-title mb-0">Point History</p>
                    <span class="section-badge">Last 30 days</span>
                </div>

                <div class="point-history-card">
                    @foreach ($pointHistoryItems as $item)
                        <div class="point-history-item d-flex align-items-center">
                            <div class="point-history-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            
                            <div class="flex-grow-1 ms-2">
                                <p class="point-history-pts mb-0 {{ ($item['type'] ?? 'earned') === 'redeemed' ? 'text-danger' : '' }}">
                                    {{ ($item['type'] ?? 'earned') === 'redeemed' ? '-' : '+' }} {{ $item['points'] }}
                                </p>
                                <p class="point-history-src mb-0">{{ $item['source'] }}</p>
                            </div>

                            <div class="text-end">
                                @if(($item['type'] ?? 'earned') === 'redeemed')
                                    <span class="badge mb-1" style="background-color: #fce8e6; color: #c5221f; font-size: 0.7rem; font-weight: 700;">
                                        Redeemed
                                    </span>
                                @else
                                    <span class="badge mb-1" style="background-color: #e6f4ea; color: #137333; font-size: 0.7rem; font-weight: 700;">
                                        Earned
                                    </span>
                                @endif
                                <p class="point-history-days mb-0 text-muted" style="font-size: 0.75rem;">{{ $item['date'] }}</p>
                            </div>
                        </div>
                    @endforeach

                    <a href="{{ route('point-history') }}" class="view-all-link">View All History Details</a>
                </div>
            </div>

            {{-- RIGHT: Redeem Goals --}}
            <div class="col-12 col-lg-8">
                <div class="redeem-header mb-3" style="min-height: 34px; display: flex; align-items: center; justify-content: space-between;">
                    <p class="section-title mb-0">Redeem Goals</p>
                    <a href="{{ route('redeem-point') }}" class="favorites-btn text-decoration-none">
                        See All
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                </div>

                <div class="row g-3">
                    @foreach ($redeemGoals as $goal)
                        @php 
                            $isEnough = $currentPoints >= $goal->point_cost; 
                            $voucherImage = match(true) {
                                $goal->used_for === 'delivery'       => 'disc-ongkir.png',
                                $goal->discount_percentage === 100     => 'disc-100.png',
                                $goal->discount_percentage === 90     => 'disc-90.png',
                                $goal->discount_percentage === 80     => 'disc-80.png',
                                $goal->discount_percentage === 70     => 'disc-70.png',
                                $goal->discount_percentage === 60     => 'disc-60.png',
                                $goal->discount_percentage === 50     => 'disc-50.png',
                                $goal->discount_percentage === 40     => 'disc-40.png',
                                $goal->discount_percentage === 30     => 'disc-30.png',
                                $goal->discount_percentage === 20     => 'disc-20.png',
                                $goal->discount_percentage === 10     => 'disc-10.png',
                                default                               => 'disc-product-default.png',
                            };
                        @endphp
                        <div class="col-12 col-sm-6">
                            <div class="redeem-card position-relative">
                                <img src="{{ asset('image/vouchers/' . $voucherImage) }}"
                                    alt="{{ $goal->name }}"
                                    style="width: 100%; height: 120px; object-fit: cover;">
                                <div class="redeem-card-body">
                                    <p class="redeem-card-name mb-2">{{ $goal->name }}</p>
                                    <p class="mb-1" style="font-size: 13px;">
                                        <span style="color: var(--jaced-caramel); font-weight: 700; font-size: 18px;">
                                            {{ number_format($goal->point_cost) }}
                                        </span> Points
                                    </p>
                                    <p class="text-muted mb-2" style="font-size: 12px;">
                                        Diskon {{ $goal->discount_percentage }}% • Max Rp {{ number_format($goal->max_discount, 0, ',', '.') }}
                                    </p>

                                    <div class="mb-3">
                                        @if($isEnough)
                                            <span class="badge" style="background-color: #e6f4ea; color: #137333;">🎉 Enough Points!</span>
                                        @else
                                            <span class="badge" style="background-color: #fff3cd; color: #856404;">
                                                🔒 Need {{ number_format($goal->point_cost - $currentPoints) }} Pts
                                            </span>
                                        @endif
                                    </div>

                                    @if($isEnough)
                                        <form action="{{ route('reward.redeem') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="voucher_type_id" value="{{ $goal->id }}">
                                            <button type="submit" class="btn-redeem-now">Redeem Now</button>
                                        </form>
                                    @else
                                        <button class="btn-redeem-locked" disabled>Redeem Now</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePopover(event, id) {
        event.stopPropagation(); 
        
        const targetPopover = document.getElementById(id);
        if (!targetPopover) return;

        const isOpen = targetPopover.classList.contains('open');

        document.querySelectorAll('.stage-popover').forEach(p => {
            p.classList.remove('open');
        });

        if (!isOpen) {
            targetPopover.classList.add('open');
        }
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.stage-popover')) {
            document.querySelectorAll('.stage-popover').forEach(p => {
                p.classList.remove('open');
            });
        }
    });
</script>
@endpush

@endsection