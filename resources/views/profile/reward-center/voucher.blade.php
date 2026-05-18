@extends('base.base')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
<style>
    .voucher-page {
        background-color: var(--jaced-cream);
        padding: 40px 24px;
        min-height: 100vh;
    }
    
    /* NAV TABS */
    .voucher-tabs {
        display: flex;
        gap: 12px;
        border-bottom: 2px solid var(--jaced-input);
        margin-bottom: 24px;
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

    /* VOUCHER TICKET CARD GAYA SOBEKAN */
    .ticket-card {
        background: white;
        border-radius: 14px;
        display: flex;
        overflow: hidden;
        border: 1px solid var(--jaced-input);
        position: relative;
        min-height: 110px;
        box-shadow: 0 2px 8px rgba(0,0,0,.02);
    }
    /* Sisi Kiri: Nilai Diskon/Gambar Mini */
    .ticket-left {
        background: var(--jaced-caramel-bg);
        color: var(--jaced-caramel);
        width: 100px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        border-right: 2px dashed var(--jaced-input);
        position: relative;
        flex-shrink: 0;
    }
    /* Bulatan Guntingan Tiket Klasik di Atas & Bawah */
    .ticket-left::before, .ticket-left::after {
        content: '';
        position: absolute;
        right: -7px;
        width: 12px;
        height: 12px;
        background: var(--jaced-cream);
        border: 1px solid var(--jaced-input);
        border-radius: 50%;
    }
    .ticket-left::before { top: -7px; }
    .ticket-left::after { bottom: -7px; }

    .ticket-right {
        padding: 16px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .ticket-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--jaced-brown-dark);
        margin: 0 0 4px;
    }
    .ticket-expiry {
        font-size: 11px;
        color: var(--jaced-muted);
        margin: 0;
    }

    /* KODE COPY AREA */
    .copy-code-btn {
        background: transparent;
        border: 1px dashed var(--jaced-sage);
        color: var(--jaced-sage);
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .copy-code-btn:hover {
        background: var(--jaced-sage);
        color: white;
    }

    /* UNTUK VOUCHER HISTORY / EXPIRED */
    .ticket-card.expired {
        opacity: 0.65;
    }
    .ticket-card.expired .ticket-left {
        background: #f1f1f1;
        color: #888;
    }
</style>
@endpush

@section('content')
@php
    // Data dummy voucher milik user
    $activeVouchers = [
        [
            'code'   => 'CANDLEFREE99',
            'title'  => 'Free Artisan Scented Candle',
            'value'  => 'FREE',
            'expiry' => 'Valid until 31 May 2026'
        ],
        [
            'code'   => 'JACED10KOFF',
            'title'  => 'Potongan Langsung Rp 10.000',
            'value'  => 'Rp 10k',
            'expiry' => 'Valid until 15 Jun 2026'
        ]
    ];

    $historyVouchers = [
        [
            'code'   => 'BRONZEBBDAY',
            'title'  => 'Birthday Reward - Free Gift',
            'value'  => 'USED',
            'expiry' => 'Used on 12 May 2026'
        ]
    ];
@endphp

<div class="voucher-page">
    <div style="max-width: 800px; margin: 0 auto;">
        
        {{-- BACK & TITLE --}}
        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('reward') }}" class="text-decoration-none" style="color: var(--jaced-brown-dark);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            </a>
            <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--jaced-brown-dark); margin: 0;">My Vouchers</h1>
        </div>

        {{-- TABS --}}
        <div class="voucher-tabs">
            <button class="voucher-tab-btn active" onclick="switchTab('active-sec', this)">
                Active Vouchers ({{ count($activeVouchers) }})
            </button>
            <button class="voucher-tab-btn" onclick="switchTab('history-sec', this)">
                History
            </button>
        </div>

        {{-- SECTION 1: ACTIVE VOUCHERS --}}
        <div id="active-sec" class="voucher-section row g-3">
            @forelse($activeVouchers as $vouch)
                <div class="col-12 col-md-6">
                    <div class="ticket-card">
                        <div class="ticket-left">
                            <span style="font-size: 18px;">{{ $vouch['value'] }}</span>
                            <span style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em;">Voucher</span>
                        </div>
                        <div class="ticket-right">
                            <div>
                                <h3 class="ticket-title">{{ $vouch['title'] }}</h3>
                                <p class="ticket-expiry">{{ $vouch['expiry'] }}</p>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span style="font-family: monospace; font-weight: 700; color: var(--jaced-brown-dark); font-size: 13px;">
                                    {{ $vouch['code'] }}
                                </span>
                                <button class="copy-code-btn" onclick="copyCode('{{ $vouch['code'] }}', this)">
                                    Copy Code
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <p class="text-muted">Kamu belum memiliki voucher aktif.</p>
                </div>
            @endforelse
        </div>

        {{-- SECTION 2: HISTORY VOUCHERS --}}
        <div id="history-sec" class="voucher-section row g-3 d-none">
            @forelse($historyVouchers as $vouch)
                <div class="col-12 col-md-6">
                    <div class="ticket-card expired">
                        <div class="ticket-left">
                            <span style="font-size: 14px;">{{ $vouch['value'] }}</span>
                        </div>
                        <div class="ticket-right">
                            <div>
                                <h3 class="ticket-title">{{ $vouch['title'] }}</h3>
                                <p class="ticket-expiry text-danger">{{ $vouch['expiry'] }}</p>
                            </div>
                            <div class="d-flex align-items-center mt-2">
                                <span style="font-family: monospace; text-decoration: line-through; color: var(--jaced-muted); font-size: 13px;">
                                    {{ $vouch['code'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <p class="text-muted">Tidak ada riwayat voucher.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>

@push('scripts')
<script>
    // Script ganti tab halaman
    function switchTab(targetId, btn) {
        document.querySelectorAll('.voucher-section').forEach(sec => sec.classList.add('d-none'));
        document.querySelectorAll('.voucher-tab-btn').forEach(t => t.classList.remove('active'));
        
        document.getElementById(targetId).classList.remove('d-none');
        btn.classList.add('active');
    }

    // Fungsi salin kode voucher otomatis
    function copyCode(code, element) {
        navigator.clipboard.writeText(code).then(() => {
            const originalText = element.innerText;
            element.innerText = 'Copied! ✓';
            element.style.background = 'var(--jaced-sage)';
            element.style.color = 'white';
            
            setTimeout(() => {
                element.innerText = originalText;
                element.style.background = 'transparent';
                element.style.color = 'var(--jaced-sage)';
            }, 2000);
        });
    }
</script>
@endpush
@endsection