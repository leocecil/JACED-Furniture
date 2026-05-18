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
@php
    $pointHistoryItems = [
        [
            'points' => '200 Points', // Poin masuk
            'source' => 'Workshop Attendance',
            'date'   => '15 May 2026',
            'type'   => 'earned', // status earned
        ],
        [
            'points' => '450 Points', // Poin keluar (habis nge-redeem lilin misalnya)
            'source' => 'Redeem Candle',
            'date'   => '14 May 2026',
            'type'   => 'redeemed', // status redeemed
        ],
        [
            'points' => '50 Points',
            'source' => 'Social Share Post',
            'date'   => '12 May 2026',
            'type'   => 'earned',
        ],
        [
            'points' => '150 Points',
            'source' => 'Referral Reward',
            'date'   => '10 May 2026',
            'type'   => 'earned',
        ],
    ];

    $redeemGoals = [
        [
            'name'     => 'Artisan Scented Candle',
            'image'    => 'https://images.unsplash.com/photo-1603905600016-2f0a09924a49?w=400&h=300&fit=crop',
            'progress' => 1250,
            'goal'     => 450,
            'reached'  => true,
            'favorited'=> true,
        ],
        [
            'name'     => 'Asymmetric Vase',
            'image'    => 'https://images.unsplash.com/photo-1612196808214-b8e1d6145a8c?w=400&h=300&fit=crop',
            'progress' => 1250,
            'goal'     => 850,
            'reached'  => true,
            'favorited'=> false,
        ],
        [
            'name'     => 'Handwoven Wool Throw',
            'image'    => 'https://images.unsplash.com/photo-1580301762395-21ce84d00bc6?w=400&h=300&fit=crop',
            'progress' => 1250,
            'goal'     => 2000,
            'reached'  => false,
            'favorited'=> false,
        ],
    ];
@endphp

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

                    <p class="stage-name">Bronze</p>

                    <div class="stage-meta">
                        <span class="stage-valid">Active until 31 Dec 2025</span>
                        <span style="color: var(--jaced-input);">·</span>
                        <div class="popover-wrap">
                            <button class="benefit-link" onclick="togglePopover('bronze-pop')">
                                See benefits
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                            </button>
                            <div class="stage-popover" id="bronze-pop">
                                <p class="popover-title">Bronze benefits</p>
                                <div class="benefit-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                    5% point bonus on every purchase
                                </div>
                                <div class="benefit-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                                    Birthday reward — free artisan candle
                                </div>
                                <div class="benefit-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                    Early access to seasonal sales
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Stage Tabs --}}
                    <div class="stage-tabs">
                        <div class="popover-wrap" style="flex: 1; position: relative;">
                            <div class="stage-tab current" onclick="togglePopover(event, 'bronze-tab-pop')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                                Bronze
                            </div>
                            <div class="stage-popover" id="bronze-tab-pop">
                                <p class="popover-title">Bronze benefits</p>
                                <div class="benefit-item">5% point bonus on every purchase</div>
                                <div class="benefit-item">Birthday reward — free artisan candle</div>
                            </div>
                        </div>

                        <div class="popover-wrap" style="flex: 1; position: relative;">
                            <div class="stage-tab locked w-100" onclick="togglePopover(event, 'silver-pop')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                Silver
                            </div>
                            <div class="stage-popover" id="silver-pop">
                                <p class="popover-title">Silver benefits</p>
                                <div class="benefit-item locked">10% point bonus on every purchase</div>
                                <div class="benefit-item locked">Free standard delivery on all orders</div>
                                <a href="#" class="how-to-link">How to reach Silver stage →</a>
                            </div>
                        </div>

                        <div class="popover-wrap" style="flex: 1; position: relative;">
                            <div class="stage-tab locked w-100" onclick="togglePopover(event, 'gold-pop')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                Gold
                            </div>
                            <div class="stage-popover" id="gold-pop">
                                <p class="popover-title">Gold benefits</p>
                                <div class="benefit-item locked">15% point bonus + priority production</div>
                                <a href="#" class="how-to-link">How to reach Gold stage →</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="header-vdivider d-none d-md-block mx-4"></div>

                {{-- Col 2: Points + Buttons --}}
                <div class="col-12 col-md-4 py-3 py-md-0">
                    <p class="pts-label">Points Earned</p>
                    <div class="d-flex align-items-baseline gap-2 mb-4">
                        <p class="pts-val mb-0">1,250</p>
                        <p class="pts-unit mb-0">Points</p>
                    </div>
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
                                {{-- Poin otomatis berwarna merah jika redeemed, dan otomatis pasang simbol + atau - --}}
                                <p class="point-history-pts mb-0 {{ ($item['type'] ?? 'earned') === 'redeemed' ? 'text-danger' : '' }}">
                                    {{ ($item['type'] ?? 'earned') === 'redeemed' ? '-' : '+' }} {{ $item['points'] }}
                                </p>
                                <p class="point-history-src mb-0">{{ $item['source'] }}</p>
                            </div>

                            <div class="text-end">
                                {{-- Badge Status: Menggunakan style soft agar estetika desain premiumnya tetap terjaga --}}
                                @if(($item['type'] ?? 'earned') === 'redeemed')
                                    <span class="badge mb-1" style="background-color: #fce8e6; color: #c5221f; font-size: 0.7rem; font-weight: 700;">
                                        Redeemed
                                    </span>
                                @else
                                    <span class="badge mb-1" style="background-color: #e6f4ea; color: #137333; font-size: 0.7rem; font-weight: 700;">
                                        Earned
                                    </span>
                                @endif
                                
                                {{-- Menampilkan Tanggal Transaksi --}}
                                <p class="point-history-days mb-0 text-muted" style="font-size: 0.75rem;">{{ $item['date'] }}</p>
                            </div>
                        </div>
                    @endforeach

                    {{-- Mengubah teks tautan bawah karena fungsinya kini menjadi riwayat umum, bukan sekadar kedaluwarsa --}}
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
                            // Simulasi total poin user (1250)
                            $userTotalPoints = 1250; 
                            $isEnough = $userTotalPoints >= $goal['goal'];
                        @endphp

                        <div class="col-12 col-sm-4">
                            <div class="redeem-card position-relative">
                                {{-- TOMBOL HATI (FAVORIT) SUDAH DIHAPUS TOTAL --}}

                                {{-- Image Container --}}
                                <div style="background: #f5f4f0; height: 160px; width: 100%;">
                                    <img src="{{ $goal['image'] }}" alt="{{ $goal['name'] }}" class="redeem-card-img">
                                </div>

                                <div class="redeem-card-body">
                                    {{-- Nama Produk --}}
                                    <p class="redeem-card-name mb-2">{{ $goal['name'] }}</p>

                                    {{-- Menampilkan Harga Poin (Menggantikan Progress Bar) --}}
                                    <p class="mb-2" style="font-size: 13px; font-weight: 600; color: var(--jaced-brown-dark);">
                                        <span style="color: var(--jaced-caramel); font-weight: 700; font-size: 18px;">{{ number_format($goal['goal']) }} </span> Points
                                    </p>

                                    {{-- Tanda / Status Poin --}}
                                    <div class="mb-3">
                                        @if ($isEnough)
                                            <span class="badge" style="background-color: #e6f4ea; color: #137333; font-size: 0.7rem; font-weight: 700; padding: 4px 8px;">
                                                🎉 Enough Points!
                                            </span>
                                        @else
                                            <span class="badge" style="background-color: #fff3cd; color: #856404; font-size: 0.7rem; font-weight: 700; padding: 4px 8px;">
                                                🔒 Need {{ number_format($goal['goal'] - $userTotalPoints) }} Pts
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Tombol Redeem Kembali di Bawah Penuh Sesuai Code Awalmu --}}
                                    @if ($isEnough)
                                        <button class="btn-redeem-now">Redeem Now</button>
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
        // Menghentikan efek klik agar tidak merembet dan langsung menutup diri
        event.stopPropagation(); 
        
        const targetPopover = document.getElementById(id);
        if (!targetPopover) return;

        const isOpen = targetPopover.classList.contains('open');

        // Tutup semua popover yang sedang terbuka di halaman
        document.querySelectorAll('.stage-popover').forEach(p => {
            p.classList.remove('open');
        });

        // Jika popover yang diklik tadi posisinya tertutup, sekarang kita buka
        if (!isOpen) {
            targetPopover.classList.add('open');
        }
    }

    // Menutup popover hanya jika user melakukan klik di luar area luar dokumen
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