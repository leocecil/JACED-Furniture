@extends('base.base')

@push('styles')
<style>
    .history-page {
        padding: 40px 20px;
        min-height: 100vh;
    }

    /* BANNER ALARM EXPIRED TAHUNAN */
    .expiry-alert-banner {
        background-color: white;
        border-left: 4px solid var(--jaced-caramel);
        border-radius: 8px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.02);
    }
    .expiry-alert-icon {
        color: var(--jaced-caramel);
        background: #FFFDF9;
        padding: 8px;
        border-radius: 50%;
        display: flex;
        align-items: center;
    }

    /* SUMMARY STATS GRID */
    .stats-breakdown-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 32px;
        border: 1px solid var(--jaced-input);
    }
    .stat-divider {
        width: 1px;
        background-color: var(--jaced-input);
        height: 50px;
    }

    /* FILTER BAR */
    .history-filter-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
    }
    .filter-pills-wrap {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        scrollbar-width: none;
    }
    .filter-pills-wrap::-webkit-scrollbar { display: none; }
    
    .history-pill {
        background: transparent;
        border: 1px solid var(--jaced-input);
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 500;
        color: var(--jaced-muted);
        cursor: pointer;
        transition: all 0.2s;
    }
    .history-pill.active {
        background: var(--jaced-brown-dark);
        color: white;
        border-color: var(--jaced-brown-dark);
    }

    /* LEDGER LIST */
    .ledger-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--jaced-input);
    }
    .ledger-row {
        border-bottom: 1px solid var(--jaced-input);
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .ledger-row:last-child {
        border-bottom: none;
    }
    .ledger-row:hover {
        background-color: #FAF8F5;
    }
    .ledger-main-info {
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Ikon Indikator Status */
    .icon-status-frame {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .icon-status-frame.earned { background-color: #EAF0EB; color: var(--jaced-sage); }
    .icon-status-frame.redeemed { background-color: #F5EBE0; color: var(--jaced-brown); }
    .icon-status-frame.expired { background-color: #F1ECE6; color: var(--jaced-muted); }

    /* Nilai Angka Poin */
    .points-display {
        font-weight: 700;
        font-size: 15px;
    }
    .points-display.earned { color: var(--jaced-sage); }
    .points-display.redeemed { color: var(--jaced-brown-dark); }
    .points-display.expired { color: var(--jaced-muted); text-decoration: line-through; }

    /* ACCORDION EXPANDABLE DETAIL */
    .ledger-detail-panel {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.25s ease-out;
        background-color: #FAF9F6;
    }
    .ledger-detail-content {
        padding: 0 20px 20px 72px; /* Sejajar dengan teks judul di atas */
        font-size: 13px;
        color: var(--jaced-brown);
    }
    .detail-grid {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 8px 24px;
        border-top: 1px dashed var(--jaced-input);
        padding-top: 12px;
    }
</style>
@endpush

@section('content')
@php
    // Simulasi data dari backend Laravel Anda
    $currentYear = 2026;
    $userPointsBalance = 1250;
    $pointsExpiringThisYear = 250; 
    $expiryDate = 'Dec 31, 2026';

    $histories = [
        [
            'id' => 1,
            'type' => 'earned', 
            'title' => 'Purchase Reward',
            'meta' => 'Order #JC-98421',
            'date' => 'May 18, 2026',
            'points' => 150,
            'year' => 2026,
            'details' => ['Total Spent' => 'Rp 1.500.000', 'Multiplier' => '1.0x (Bronze Stage)', 'Est. Expiry' => 'Dec 31, 2027']
        ],
        [
            'id' => 2,
            'type' => 'redeemed',
            'title' => 'Redeemed Artisan Candle',
            'meta' => 'Reward Directory Catalog',
            'date' => 'May 12, 2026',
            'points' => 450,
            'year' => 2026,
            'details' => ['Status' => 'Ready to Pick-up', 'Voucher Code' => 'JCD-CANDLE-XYZ8', 'Redeemed Via' => 'Points Directory']
        ],
        [
            'id' => 3,
            'type' => 'earned',
            'title' => 'Birthday Bonus Points',
            'meta' => 'Annual Loyalty Perk',
            'date' => 'May 01, 2026',
            'points' => 200,
            'year' => 2026,
            'details' => ['Event' => 'Jaced Circle Member Birthday Gift', 'Note' => 'Valid for 1 year from issued date']
        ],
        [
            'id' => 4,
            'type' => 'expired',
            'title' => 'Points Expired',
            'meta' => 'Annual Points Reset Policy',
            'date' => 'Jan 01, 2026',
            'points' => 350,
            'year' => 2026,
            'details' => ['System Cleanup' => 'Unused points generated during 2024 have reached maximum retention period.', 'Policy Reference' => 'Clause 4B - Point Expiration Term']
        ],
        [
            'id' => 5,
            'type' => 'earned',
            'title' => 'Purchase Reward',
            'meta' => 'Order #JC-77102',
            'date' => 'Nov 24, 2025',
            'points' => 800,
            'year' => 2025,
            'details' => ['Total Spent' => 'Rp 8.000.000', 'Multiplier' => '1.0x']
        ]
    ];
@endphp

<div class="history-page">
    <div style="max-width: 800px; margin: 0 auto;">

        {{-- BACK TO REWARD CENTER --}}
        <a href="#" class="back-link" onclick="window.history.back(); return false;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back to Reward Center</span>
        </a>

        {{-- 1. ANNUAL EXPIRY BANNER WARNING --}}
        @if($pointsExpiringThisYear > 0)
            <div class="expiry-alert-banner">
                <div class="expiry-alert-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                <div>
                    <h5 style="margin: 0; font-size: 14px; font-weight: 700; color: var(--jaced-brown-dark);">Points Expiring Soon</h5>
                    <p style="margin: 2px 0 0; font-size: 12px; color: var(--jaced-muted);">
                        Ada <strong>{{ number_format($pointsExpiringThisYear) }} Pts</strong> yang akan hangus pada <strong>{{ $expiryDate }}</strong>. Jangan lupa ditukarkan!
                    </p>
                </div>
            </div>
        @endif

        {{-- 2. STATS ANALYTICS BREAKDOWN --}}
        <div class="stats-breakdown-card">
            <div class="row align-items-center text-center text-sm-start">
                <div class="col-12 col-sm-5 mb-3 mb-sm-0">
                    <p style="font-size: 11px; font-weight: 600; color: var(--jaced-muted); text-transform: uppercase; margin-bottom: 4px;">Total Active Balance</p>
                    <h2 style="font-size: 28px; font-weight: 700; color: var(--jaced-brown-dark); margin: 0;">
                        <span style="color: var(--jaced-caramel);">{{ number_format($userPointsBalance) }}</span> <span style="font-size: 14px; font-weight: 500; color: var(--jaced-brown-dark);">Points</span>
                    </h2>
                </div>
                
                <div class="col-sm-1 d-none d-sm-block"><div class="stat-divider"></div></div>

                <div class="col-6 col-sm-3">
                    <p style="font-size: 11px; font-weight: 600; color: var(--jaced-muted); margin-bottom: 2px;">Earned in {{ $currentYear }}</p>
                    <p style="font-size: 15px; font-weight: 600; color: var(--jaced-sage); margin: 0;">+350 Pts</p>
                </div>

                <div class="col-6 col-sm-3">
                    <p style="font-size: 11px; font-weight: 600; color: var(--jaced-muted); margin-bottom: 2px;">Carried From Last Year</p>
                    <p style="font-size: 15px; font-weight: 600; color: var(--jaced-brown); margin: 0;">{{ number_format($pointsExpiringThisYear) }} Pts</p>
                </div>
            </div>
        </div>

        {{-- 3. FILTER BAR (TYPE & YEAR) --}}
        <div class="history-filter-bar">
            <div class="filter-pills-wrap">
                <button class="history-pill active" data-filter="all">All Activity</button>
                <button class="history-pill" data-filter="earned">Points Earned</button>
                <button class="history-pill" data-filter="redeemed">Redeemed</button>
                <button class="history-pill" data-filter="expired">Expired</button>
            </div>

            <div>
                <select class="input-jaced" id="yearFilter" style="padding: 6px 36px 6px 12px !important; font-size: 13px !important; width: auto; background-color: white !important;">
                    <option value="2026">Year: 2026</option>
                    <option value="2025">Year: 2025</option>
                </select>
            </div>
        </div>

        {{-- 4. THE LEDGER CONTAINER --}}
        <div class="ledger-container" id="ledgerList">
            @foreach($histories as $history)
                <div class="ledger-row" data-type="{{ $history['type'] }}" data-year="{{ $history['year'] }}">
                    
                    <div class="ledger-main-info" onclick="toggleDetailPanel(this)">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-status-frame {{ $history['type'] }}">
                                @if($history['type'] == 'earned')
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                                @elseif($history['type'] == 'redeemed')
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="18" height="12" x="3" y="8" rx="2"/><path d="M12 8V3"/><path d="M16 7A4 4 0 1 0 8 7"/></svg>
                                @else
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                @endif
                            </div>

                            <div>
                                <p style="margin: 0; font-size: 14px; font-weight: 600; color: var(--jaced-brown-dark);">{{ $history['title'] }}</p>
                                <p style="margin: 2px 0 0; font-size: 12px; color: var(--jaced-muted);">{{ $history['meta'] }} • {{ $history['date'] }}</p>
                            </div>
                        </div>

                        <div class="points-display {{ $history['type'] }}">
                            @if($history['type'] == 'earned') +@elseif($history['type'] == 'redeemed' || $history['type'] == 'expired')-@endif{{ number_format($history['points']) }} Pts
                        </div>
                    </div>

                    <div class="ledger-detail-panel">
                        <div class="ledger-detail-content">
                            <div class="detail-grid">
                                @foreach($history['details'] as $key => $val)
                                    <span style="color: var(--jaced-muted); font-weight: 500;">{{ $key }}</span>
                                    <span style="color: var(--jaced-brown-dark); font-weight: 600;">{{ $val }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</div>

{{-- SCRIPT INTERAKTIF FILTER & ACCORDION MURNI --}}
<script>
    // 1. FUNGSI ACCORDION PANEL (CLICK TO EXPAND DETAIL)
    function toggleDetailPanel(element) {
        const row = element.parentElement;
        const panel = row.querySelector('.ledger-detail-panel');
        
        // Tutup baris lain yang sedang terbuka
        document.querySelectorAll('.ledger-detail-panel').forEach(p => {
            if (p !== panel) p.style.maxHeight = null;
        });

        // Buka / Tutup panel saat ini
        if (panel.style.maxHeight) {
            panel.style.maxHeight = null;
        } else {
            panel.style.maxHeight = panel.scrollHeight + "px";
        }
    }

    // 2. LOGIKA UTAMA FILTER JAVASCRIPT
    document.addEventListener('DOMContentLoaded', function() {
        const pills = document.querySelectorAll('.history-pill');
        const yearSelect = document.getElementById('yearFilter');
        const rows = document.querySelectorAll('.ledger-row');

        let activeType = 'all';
        let activeYear = yearSelect.value;

        // Trigger Klik Pada Pills Kategori
        pills.forEach(pill => {
            pill.addEventListener('click', function() {
                pills.forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                activeType = this.getAttribute('data-filter');
                applyActiveFilters();
            });
        });

        // Trigger Perubahan Dropdown Tahun
        yearSelect.addEventListener('change', function() {
            activeYear = this.value;
            applyActiveFilters();
        });

        // Fungsi Filter Integrasi
        function applyActiveFilters() {
            rows.forEach(row => {
                const type = row.getAttribute('data-type');
                const year = row.getAttribute('data-year');

                const matchType = (activeType === 'all' || type === activeType);
                const matchYear = (year === activeYear);

                if(matchType && matchYear) {
                    row.style.display = 'block';
                } else {
                    row.style.display = 'none';
                    // Amankan accordion tertutup jika elemen disembunyikan
                    row.querySelector('.ledger-detail-panel').style.maxHeight = null;
                }
            });
        }

        // Jalankan filter di awal load halaman
        applyActiveFilters();
    });
</script>
@endsection