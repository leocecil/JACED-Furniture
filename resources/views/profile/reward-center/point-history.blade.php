@extends('base.base')

@push('styles')
<style>
    .history-page {
        padding: 40px 20px;
        min-height: 100vh;
    }

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
    .ledger-row:last-child { border-bottom: none; }
    .ledger-row:hover { background-color: #FAF8F5; }

    .ledger-main-info {
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .icon-status-frame {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .icon-status-frame.earned   { background-color: #EAF0EB; color: var(--jaced-sage); }
    .icon-status-frame.redeemed { background-color: #F5EBE0; color: var(--jaced-brown); }

    .points-display { font-weight: 700; font-size: 15px; }
    .points-display.earned   { color: var(--jaced-sage); }
    .points-display.redeemed { color: var(--jaced-brown-dark); }

    .ledger-detail-panel {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.25s ease-out;
        background-color: #FAF9F6;
    }
    .ledger-detail-content {
        padding: 0 20px 20px 72px;
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

<div class="history-page">
    <div style="max-width: 800px; margin: 0 auto;">

        {{-- BACK --}}
        <a href="{{ route('reward') }}" class="back-link">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back to Reward Center</span>
        </a>

        {{-- STATS CARD --}}
        <div class="stats-breakdown-card">
            <div class="row align-items-center text-center text-sm-start">
                <div class="col-12 col-sm-5 mb-3 mb-sm-0">
                    <p style="font-size: 11px; font-weight: 600; color: var(--jaced-muted); text-transform: uppercase; margin-bottom: 4px;">Total Active Balance</p>
                    <h2 style="font-size: 28px; font-weight: 700; color: var(--jaced-brown-dark); margin: 0;">
                        <span style="color: var(--jaced-caramel);">{{ number_format($currentPoints) }}</span>
                        <span style="font-size: 14px; font-weight: 500; color: var(--jaced-brown-dark);">Points</span>
                    </h2>
                </div>

                <div class="col-sm-1 d-none d-sm-block"><div class="stat-divider"></div></div>

                <div class="col-6 col-sm-3">
                    <p style="font-size: 11px; font-weight: 600; color: var(--jaced-muted); margin-bottom: 2px;">Earned in {{ $currentYear }}</p>
                    <p style="font-size: 15px; font-weight: 600; color: var(--jaced-sage); margin: 0;">+{{ number_format($earnedThisYear) }} Pts</p>
                </div>

                <div class="col-6 col-sm-3">
                    <p style="font-size: 11px; font-weight: 600; color: var(--jaced-muted); margin-bottom: 2px;">Total Redeemed</p>
                    @php
                        $totalRedeemed = $histories->where('type', 'redeemed')->sum('points');
                    @endphp
                    <p style="font-size: 15px; font-weight: 600; color: var(--jaced-brown); margin: 0;">{{ number_format(abs($totalRedeemed)) }} Pts</p>
                </div>
            </div>
        </div>

        {{-- FILTER BAR --}}
        <div class="history-filter-bar">
            <div class="filter-pills-wrap">
                <button class="history-pill active" data-filter="all">All Activity</button>
                <button class="history-pill" data-filter="earned">Points Earned</button>
                <button class="history-pill" data-filter="redeemed">Redeemed</button>
            </div>

            <div>
                <select class="input-jaced" id="yearFilter"
                    style="padding: 6px 36px 6px 12px !important; font-size: 13px !important; width: auto; background-color: white !important;">
                    @forelse($availableYears as $year)
                        <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                            Year: {{ $year }}
                        </option>
                    @empty
                        <option value="{{ $currentYear }}">Year: {{ $currentYear }}</option>
                    @endforelse
                </select>
            </div>
        </div>

        {{-- LEDGER --}}
        <div class="ledger-container" id="ledgerList">
            @forelse($histories as $history)
                <div class="ledger-row"
                     data-type="{{ $history->type }}"
                     data-year="{{ \Carbon\Carbon::parse($history->created_at)->year }}">

                    <div class="ledger-main-info" onclick="toggleDetailPanel(this)">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-status-frame {{ $history->type }}">
                                @if($history->type == 'earned')
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                                @else
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="18" height="12" x="3" y="8" rx="2"/><path d="M12 8V3"/><path d="M16 7A4 4 0 1 0 8 7"/></svg>
                                @endif
                            </div>

                            <div>
                                <p style="margin: 0; font-size: 14px; font-weight: 600; color: var(--jaced-brown-dark);">
                                    {{ $history->type === 'earned' ? 'Purchase Reward' : 'Redeemed Voucher' }}
                                </p>
                                <p style="margin: 2px 0 0; font-size: 12px; color: var(--jaced-muted);">
                                    {{ ucfirst($history->source) }} &bull;
                                    {{ \Carbon\Carbon::parse($history->created_at)->format('d M Y') }}
                                </p>
                            </div>
                        </div>

                        <div class="points-display {{ $history->type }}">
                            {{ $history->type === 'earned' ? '+' : '-' }}{{ number_format(abs($history->points)) }} Pts
                        </div>
                    </div>

                    {{-- Detail accordion --}}
                    <div class="ledger-detail-panel">
                        <div class="ledger-detail-content">
                            <div class="detail-grid">
                                <span style="color: var(--jaced-muted); font-weight: 500;">Type</span>
                                <span style="color: var(--jaced-brown-dark); font-weight: 600;">{{ ucfirst($history->type) }}</span>

                                <span style="color: var(--jaced-muted); font-weight: 500;">Source</span>
                                <span style="color: var(--jaced-brown-dark); font-weight: 600;">{{ ucfirst(str_replace('_', ' ', $history->source)) }}</span>

                                <span style="color: var(--jaced-muted); font-weight: 500;">Points</span>
                                <span style="color: var(--jaced-brown-dark); font-weight: 600;">
                                    {{ $history->type === 'earned' ? '+' : '-' }}{{ number_format(abs($history->points)) }} Pts
                                </span>

                                <span style="color: var(--jaced-muted); font-weight: 500;">Date</span>
                                <span style="color: var(--jaced-brown-dark); font-weight: 600;">
                                    {{ \Carbon\Carbon::parse($history->created_at)->format('d M Y, H:i') }}
                                </span>

                                @if($history->order_id)
                                    <span style="color: var(--jaced-muted); font-weight: 500;">Order ID</span>
                                    <span style="color: var(--jaced-brown-dark); font-weight: 600;">#{{ $history->order_id }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            @empty
                <div class="p-5 text-center">
                    <p class="text-muted" style="font-size: 13px;">Belum ada riwayat poin.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>

@push('scripts')
<script>
    function toggleDetailPanel(element) {
        const row = element.parentElement;
        const panel = row.querySelector('.ledger-detail-panel');

        document.querySelectorAll('.ledger-detail-panel').forEach(p => {
            if (p !== panel) p.style.maxHeight = null;
        });

        panel.style.maxHeight = panel.style.maxHeight ? null : panel.scrollHeight + "px";
    }

    document.addEventListener('DOMContentLoaded', function () {
        const pills = document.querySelectorAll('.history-pill');
        const yearSelect = document.getElementById('yearFilter');
        const rows = document.querySelectorAll('.ledger-row');

        let activeType = 'all';
        let activeYear = yearSelect.value;

        pills.forEach(pill => {
            pill.addEventListener('click', function () {
                pills.forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                activeType = this.getAttribute('data-filter');
                applyActiveFilters();
            });
        });

        yearSelect.addEventListener('change', function () {
            activeYear = this.value;
            applyActiveFilters();
        });

        function applyActiveFilters() {
            rows.forEach(row => {
                const type = row.getAttribute('data-type');
                const year = row.getAttribute('data-year');
                const matchType = (activeType === 'all' || type === activeType);
                const matchYear = (year === activeYear);

                if (matchType && matchYear) {
                    row.style.display = 'block';
                } else {
                    row.style.display = 'none';
                    row.querySelector('.ledger-detail-panel').style.maxHeight = null;
                }
            });
        }

        applyActiveFilters();
    });
</script>
@endpush

@endsection