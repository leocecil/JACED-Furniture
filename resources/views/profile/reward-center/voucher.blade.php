@extends('base.base')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
<style>
    .voucher-page {
        background-color: var(--jaced-caramel-bg) !important;
        padding: 40px 24px;
        min-height: 100vh;
    }

    /* NAV TABS */
    .voucher-tabs {
        display: flex;
        gap: 12px;
        border-bottom: 2px solid var(--jaced-input);
        margin-bottom: 20px;
    }
    .voucher-tab-btn {
        background: none;
        border: none;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 600;
        color: var(--jaced-muted);
        cursor: pointer;
        position: relative;
        bottom: -2px;
        transition: all 0.2s;
    }
    .voucher-tab-btn.active {
        color: var(--jaced-brown-dark);
        border-bottom: 2px solid var(--jaced-caramel);
    }

    /* FILTER PILLS */
    .filter-pills {
        display: flex;
        gap: 8px;
        margin-bottom: 20px;
        flex-wrap: nowrap;          
        overflow-x: auto;          
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        padding-bottom: 2px;
    }
    .filter-pills::-webkit-scrollbar { display: none; }
    .filter-pill {
        background: white;
        border: 1px solid var(--jaced-input);
        border-radius: 999px;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 600;
        color: var(--jaced-muted);
        cursor: pointer;
        transition: all 0.18s;
        white-space: nowrap;        
        flex-shrink: 0; 
    }
    .filter-pill.active {
        background: var(--jaced-brown-dark);
        color: white;
        border-color: var(--jaced-brown-dark);
    }
    .filter-pill:hover:not(.active) {
        background: var(--jaced-caramel-bg);
        color: var(--jaced-brown-dark);
        border-color: var(--jaced-caramel);
    }

    /* VOUCHER CARD — PORTRAIT */
    .voucher-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid var(--jaced-input);
        transition: all 0.25s ease;
        cursor: pointer;
        height: 100%;
    }
    .voucher-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.07);
    }
    .voucher-card img {
        width: 100%;
        height: 140px;
        object-fit: cover;
    }
    .voucher-card-body {
        padding: 14px 16px;
        display: flex;
        flex-direction: column;
    }
    .voucher-card-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--jaced-brown-dark);
        margin: 0 0 6px;
        line-height: 1.4;
    }
    .voucher-card-meta {
        font-size: 11px;
        color: var(--jaced-muted);
        margin-bottom: 10px;
    }
    .voucher-expiry-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 999px;
        margin-bottom: 12px;
    }
    .voucher-expiry-badge.soon {
        background: #FEF6EC;
        color: #C17F4A;
        border: 1px solid #F3D9B1;
    }
    .voucher-expiry-badge.normal {
        background: #EDF4EF;
        color: #4A7C59;
        border: 1px solid #C8DFC6;
    }
    .voucher-expiry-badge.expired-badge {
        background: #FDEAEA;
        color: #C23B3B;
        border: 1px solid #F5C6C6;
    }
    .btn-use-now {
        width: 100%;
        background: var(--jaced-caramel);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 9px 14px;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.2s;
        cursor: pointer;
    }
    .btn-use-now:hover {
        background: var(--jaced-brown-dark);
        transform: translateY(-1px);
    }

    /* EXPIRED CARD */
    .voucher-card.is-expired {
        opacity: 0.6;
        cursor: default;
    }
    .voucher-card.is-expired:hover {
        transform: none;
        box-shadow: none;
    }

    /* MODAL */
    .v-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(28,28,26,0.55);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        transition: opacity 0.25s ease;
    }
    .v-modal-box {
        background: white;
        border-radius: 18px;
        max-width: 360px;
        width: 90%;
        overflow: hidden;
        box-shadow: 0 16px 40px rgba(0,0,0,0.12);
        transform: scale(0.95);
        transition: transform 0.25s ease, opacity 0.25s ease;
    }
    .v-modal-box img {
        width: 100%;
        height: 160px;
        object-fit: cover;
    }
    .v-modal-body { padding: 20px; }
    .v-modal-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid var(--jaced-card);
        font-size: 13px;
    }
    .v-modal-row:last-of-type { border-bottom: none; }
    .v-modal-key { color: var(--jaced-muted); }
    .v-modal-val { font-weight: 600; color: var(--jaced-brown-dark); }

    /* EMPTY STATE */
    .empty-voucher {
        text-align: center;
        padding: 60px 20px;
        grid-column: 1 / -1;
    }
    .empty-voucher-icon {
        width: 72px; height: 72px;
        background: white;
        border-radius: 18px;
        border: 1px solid var(--jaced-input);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .btn-details {
        background: transparent;
        border: 1px solid var(--jaced-input);
        border-radius: 8px;
        padding: 9px 12px;
        font-size: 12px;
        font-weight: 600;
        color: var(--jaced-brown-dark);
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s;
    }
    .btn-details:hover {
        background: var(--jaced-caramel-bg);
        border-color: var(--jaced-caramel);
        color: var(--jaced-caramel);
    }

    .voucher-tab-btn:hover:not(.active) {
        color: var(--jaced-brown-dark);
        border-bottom: 2px solid var(--jaced-caramel);
    }

    .btn-modal-close {
        background: transparent;
        border: 1px solid var(--jaced-input);
        border-radius: 8px;
        padding: 10px 16px;
        font-size: 13px;
        color: var(--jaced-brown-dark);
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-modal-close:hover {
        background: var(--jaced-caramel-bg);
        border-color: var(--jaced-caramel);
        color: var(--jaced-caramel);
    }

    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .voucher-card {
        animation: fadeSlideUp 0.35s ease both;
    }
    /* stagger per card */
    /* GANTI bagian stagger yang ada sekarang */
    .voucher-item:nth-child(1) .voucher-card { animation-delay: 0.05s; }
    .voucher-item:nth-child(2) .voucher-card { animation-delay: 0.10s; }
    .voucher-item:nth-child(3) .voucher-card { animation-delay: 0.15s; }
    .voucher-item:nth-child(4) .voucher-card { animation-delay: 0.20s; }
    .voucher-item:nth-child(5) .voucher-card { animation-delay: 0.25s; }
    .voucher-item:nth-child(6) .voucher-card { animation-delay: 0.30s; }
    .voucher-item:nth-child(7) .voucher-card { animation-delay: 0.35s; }
    .voucher-item:nth-child(8) .voucher-card { animation-delay: 0.40s; }
</style>
@endpush

@section('content')
<div class="voucher-page">
    <div style="max-width: 900px; margin: 0 auto;">
        {{-- BACK --}}
        <a href="{{ route('reward') }}" class="back-link" style="margin-bottom: 20px; display: inline-flex;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
            </svg>
            <span>Back to Reward Center</span>
        </a>

        {{-- TITLE --}}
        <div class="d-flex align-items-center gap-2 mb-4">
            <h1 style="font-size:30px;font-weight:700;color: var(--jaced-dark);margin:0 0 4px;">My Vouchers</h1>
        </div>

        {{-- TABS --}}
        <div class="voucher-tabs">
            <button class="voucher-tab-btn active" onclick="switchTab('active-sec', this)">
                Active Vouchers ({{ count($activeVouchers) }})
            </button>
            <button class="voucher-tab-btn" onclick="switchTab('history-sec', this)">
                History ({{ count($historyVouchers) }})
            </button>
        </div>

        {{-- FILTER PILLS --}}
        <div class="filter-pills" id="filterPills">
            <button class="filter-pill active" data-filter="all">All</button>
            <button class="filter-pill" data-filter="product">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="margin-right:3px;"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                Product Discount
            </button>
            <button class="filter-pill" data-filter="delivery">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="margin-right:3px;"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                Free Shipping
            </button>
        </div>

        {{-- ACTIVE VOUCHERS --}}
        <div id="active-sec" class="voucher-section">
            <div class="row g-3" id="activeGrid">
                @forelse($activeVouchers as $vouch)
                    @php
                        $daysLeft = (int) now()->diffInDays($vouch->expiry_date, false);
                        $voucherImage = match(true) {
                            $vouch->used_for === 'delivery'         => 'disc-ongkir.png',
                            $vouch->discount_percentage === 100     => 'disc-100.png',
                            $vouch->discount_percentage === 50      => 'disc-50.png',
                            default                                 => 'disc-product-default.png',
                        };
                    @endphp
                    <div class="col-6 col-md-4 col-lg-3 voucher-item" data-type="{{ $vouch->used_for === 'delivery' ? 'delivery' : 'product' }}">
                        <div class="voucher-card" onclick="openVoucherDetail(
                            '{{ $vouch->name }}',
                            '{{ $vouch->used_for }}',
                            {{ $vouch->discount_percentage }},
                            {{ $vouch->max_discount }},
                            '{{ \Carbon\Carbon::parse($vouch->expiry_date)->format('d M Y') }}',
                            {{ $vouch->id }},
                            '{{ asset('image/vouchers/' . $voucherImage) }}',
                            false,
                            '{{ addslashes($vouch->description ?? '') }}'
                        )">
                            <img src="{{ asset('image/vouchers/' . $voucherImage) }}" alt="{{ $vouch->name }}">
                            <div class="voucher-card-body">
                                {{-- Type badge --}}
                                @if($vouch->used_for === 'delivery')
                                    <span class="badge mb-2" style="background:var(--jaced-caramel-bg);color:var(--jaced-sage);font-size:10px;">Free Shipping</span>
                                @else
                                    <span class="badge mb-2" style="background:#fcf5f3;color:#bd654e;font-size:10px;">Product Discount</span>
                                @endif

                                <p class="voucher-card-title">{{ $vouch->name }}</p>
                                <p class="voucher-card-meta">Max Rp {{ number_format($vouch->max_discount, 0, ',', '.') }}</p>

                                {{-- Expiry badge --}}
                                @if($daysLeft > 7)
                                    <p style="font-size:11px;color:var(--jaced-muted);margin-bottom:12px;">
                                        Valid until {{ \Carbon\Carbon::parse($vouch->expiry_date)->format('d M Y') }}
                                    </p>
                                @elseif($daysLeft > 0)
                                    <span class="voucher-expiry-badge soon">Expires in {{ $daysLeft }}d</span>
                                @else
                                    <span class="voucher-expiry-badge expired-badge">Expired</span>
                                @endif

                                <div style="display:flex;gap:6px;">
                                    @if($daysLeft > 0)
                                        <button onclick="event.stopPropagation(); useVoucher({{ $vouch->id }}, this)"
                                            class="btn-use-now" style="flex:1;">
                                            Use Now →
                                        </button>
                                    @else
                                        <button disabled class="btn-use-now" style="flex:1;opacity:0.45;cursor:not-allowed;">
                                            Expired
                                        </button>
                                    @endif
                                    <button onclick="event.stopPropagation(); openVoucherDetail(
                                            '{{ $vouch->name }}',
                                            '{{ $vouch->used_for }}',
                                            {{ $vouch->discount_percentage }},
                                            {{ $vouch->max_discount }},
                                            '{{ \Carbon\Carbon::parse($vouch->expiry_date)->format('d M Y') }}',
                                            {{ $vouch->id }},
                                            '{{ asset('image/vouchers/' . $voucherImage) }}',
                                            false,
                                            '{{ addslashes($vouch->description ?? '') }}'
                                        )"
                                        class="btn-details">
                                        Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-voucher">
                            <div class="empty-voucher-icon">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--jaced-caramel)" stroke-width="1.5" stroke-linecap="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2"/><path d="M13 17v2"/><path d="M13 11v2"/></svg>
                            </div>
                            <h3 style="font-size:15px;font-weight:700;color:var(--jaced-brown-dark);margin-bottom:6px;">No Active Vouchers</h3>
                            <p style="font-size:12px;color:var(--jaced-muted);margin-bottom:20px;">Redeem your points to get exclusive vouchers!</p>
                            <a href="{{ route('redeem-point') }}" style="display:inline-flex;align-items:center;gap:6px;background:var(--jaced-caramel);color:white;padding:10px 20px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;">
                                Redeem Points Now
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- HISTORY VOUCHERS --}}
        <div id="history-sec" class="voucher-section d-none">
            <div class="row g-3">
                @forelse($historyVouchers as $vouch)
                    @php
                        $voucherImage = match(true) {
                            $vouch->used_for === 'delivery'     => 'disc-ongkir.png',
                            $vouch->discount_percentage === 100 => 'disc-100.png',
                            $vouch->discount_percentage === 50  => 'disc-50.png',
                            default                             => 'disc-product-default.png',
                        };
                    @endphp
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="voucher-card is-expired">
                            <img src="{{ asset('image/vouchers/' . $voucherImage) }}" alt="{{ $vouch->name }}">
                            <div class="voucher-card-body">
                                <p class="voucher-card-title">{{ $vouch->name }}</p>
                                <p class="voucher-card-meta">Max Rp {{ number_format($vouch->max_discount, 0, ',', '.') }}</p>
                                <span class="voucher-expiry-badge expired-badge">
                                    {{ $vouch->redeemed_at ? '✓ Used' : '✕ Expired' }}
                                    · {{ \Carbon\Carbon::parse($vouch->redeemed_at ?? $vouch->expiry_date)->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-voucher">
                            <div class="empty-voucher-icon">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--jaced-muted)" stroke-width="1.5" stroke-linecap="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/></svg>
                            </div>
                            <h3 style="font-size:15px;font-weight:700;color:var(--jaced-brown-dark);margin-bottom:6px;">No Voucher History</h3>
                            <p style="font-size:12px;color:var(--jaced-muted);">Vouchers you've used or expired will appear here.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

{{-- MODAL DETAIL --}}
<div class="v-modal-overlay" id="voucherModal" style="display:none;opacity:0;" onclick="handleVModalBackdrop(event)">
    <div class="v-modal-box">
        <img id="vModalImg" src="" alt="">
        <div class="v-modal-body">
            <div class="mb-2" id="vModalBadge"></div>
            <h3 style="font-size:15px;font-weight:700;color:var(--jaced-brown-dark);margin:0 0 14px;" id="vModalName"></h3>

            <div class="d-flex flex-column mb-3">
                <span style="font-size:12px;color:var(--jaced-muted);">Description</span>
                <span id="vModalDesc" style="font-size:13px;color:var(--jaced-brown-dark);font-weight:500;"></span>
            </div>
            <div id="vModalRows">
                <div class="v-modal-row">
                    <span class="v-modal-key">Discount</span>
                    <span class="v-modal-val" id="vModalPct"></span>
                </div>
                <div class="v-modal-row">
                    <span class="v-modal-key">Max Discount</span>
                    <span class="v-modal-val" id="vModalMax"></span>
                </div>
                <div class="v-modal-row">
                    <span class="v-modal-key">Valid Until</span>
                    <span class="v-modal-val" id="vModalExpiry"></span>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button onclick="closeVoucherModal()" class="btn-modal-close">Close</button>
                <div id="vModalAction" style="flex:1;"></div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI USE VOUCHER --}}
<div class="v-modal-overlay" id="confirmModal" style="display:none;opacity:0;" onclick="handleConfirmBackdrop(event)">
    <div class="v-modal-box" style="max-width:300px;">
        <div class="v-modal-body" style="padding:24px 20px;">
            <div style="width:44px;height:44px;background:#FEF6EC;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:14px;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#C17F4A" stroke-width="2" stroke-linecap="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2"/><path d="M13 17v2"/><path d="M13 11v2"/></svg>
            </div>
            <h3 style="font-size:15px;font-weight:700;color:var(--jaced-brown-dark);margin:0 0 6px;">Use This Voucher?</h3>
            <p style="font-size:12px;color:var(--jaced-muted);margin:0 0 20px;">Voucher will be applied to your next order and cannot be undone.</p>
            <div class="d-flex gap-2">
                <button onclick="closeConfirmModal()" class="btn-modal-close" style="flex:1;">Cancel</button>
                <button id="confirmUseBtn" class="btn-use-now" style="flex:1.5;">
                    Yes, Use Now →
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function switchTab(targetId, btn) {
        document.querySelectorAll('.voucher-section').forEach(s => s.classList.add('d-none'));
        document.querySelectorAll('.voucher-tab-btn').forEach(t => t.classList.remove('active'));
        document.getElementById(targetId).classList.remove('d-none');
        btn.classList.add('active');

        // sembunyiin filter pills kalau di history
        document.getElementById('filterPills').style.display = targetId === 'active-sec' ? 'flex' : 'none';
    }

    // Filter pills
    document.querySelectorAll('.filter-pill').forEach(pill => {
        pill.addEventListener('click', function () {
            document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            const filter = this.getAttribute('data-filter');
            document.querySelectorAll('.voucher-item').forEach(item => {
                item.style.display = (filter === 'all' || item.getAttribute('data-type') === filter) ? '' : 'none';
            });
        });
    });

    // Modal
    const vModal = document.getElementById('voucherModal');

    function openVoucherDetail(name, usedFor, pct, maxDiscount, expiry, voucherId, imgSrc, isExpired, description = '') {
        document.getElementById('vModalImg').src = imgSrc;
        document.getElementById('vModalName').innerText = name;
        document.getElementById('vModalDesc').innerText = description || '-';
        document.getElementById('vModalPct').innerText = pct + '%';
        document.getElementById('vModalMax').innerText = 'Rp ' + maxDiscount.toLocaleString('id-ID');
        document.getElementById('vModalExpiry').innerText = expiry;

        const badge = document.getElementById('vModalBadge');
        badge.innerHTML = usedFor === 'delivery'
            ? `<span class="badge" style="background:var(--jaced-caramel-bg);color:var(--jaced-sage);font-size:11px;">Free Shipping</span>`
            : `<span class="badge" style="background:#fcf5f3;color:#bd654e;font-size:11px;">Product Discount</span>`;

        const action = document.getElementById('vModalAction');
        action.innerHTML = isExpired
            ? '<button class="btn-use-now" disabled style="opacity:.45;cursor:not-allowed;">Expired</button>'
            : `<button class="btn-use-now" onclick="useVoucher(${voucherId}, this)">Use Now →</button>`;

        vModal.style.display = 'flex';
        setTimeout(() => {
            vModal.style.opacity = '1';
            vModal.querySelector('.v-modal-box').style.transform = 'scale(1)';
        }, 10);
    }

    function closeVoucherModal() {
        vModal.style.opacity = '0';
        vModal.querySelector('.v-modal-box').style.transform = 'scale(0.95)';
        setTimeout(() => vModal.style.display = 'none', 250);
    }

    function handleVModalBackdrop(e) {
        if (e.target === vModal) closeVoucherModal();
    }

    // GANTI fungsi useVoucher lama + tambah 3 fungsi baru ini

    let _pendingVoucherId = null;
    let _pendingBtn = null;

    function useVoucher(voucherId, btn) {
        _pendingVoucherId = voucherId;
        _pendingBtn = btn;

        // kalau dipanggil dari dalam modal detail, tutup dulu
        if (vModal.style.display !== 'none') closeVoucherModal();

        const cModal = document.getElementById('confirmModal');
        cModal.style.display = 'flex';
        setTimeout(() => {
            cModal.style.opacity = '1';
            cModal.querySelector('.v-modal-box').style.transform = 'scale(1)';
        }, 10);
    }

    function closeConfirmModal() {
        const cModal = document.getElementById('confirmModal');
        cModal.style.opacity = '0';
        cModal.querySelector('.v-modal-box').style.transform = 'scale(0.95)';
        setTimeout(() => cModal.style.display = 'none', 250);
        _pendingVoucherId = null;
        _pendingBtn = null;
    }

    function handleConfirmBackdrop(e) {
        if (e.target === document.getElementById('confirmModal')) closeConfirmModal();
    }

    document.getElementById('confirmUseBtn').addEventListener('click', function () {
        if (!_pendingVoucherId) return;
        this.disabled = true;
        this.innerText = 'Loading...';

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("reward.use-voucher") }}';
        const csrf = document.createElement('input');
        csrf.type = 'hidden'; csrf.name = '_token';
        csrf.value = document.querySelector('meta[name="csrf-token"]').content;
        const input = document.createElement('input');
        input.type = 'hidden'; input.name = 'voucher_id'; input.value = _pendingVoucherId;
        form.appendChild(csrf); form.appendChild(input);
        document.body.appendChild(form); form.submit();
    });
</script>
@endpush