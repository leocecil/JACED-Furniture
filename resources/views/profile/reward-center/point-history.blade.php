@extends('base.base')

@push('styles')
<style>
    .history-page { padding: 32px 20px; min-height: 100vh; }

    /* 2-col layout */
    .two-col-layout {
        display: grid;
        grid-template-columns: 40% 1fr;
        gap: 20px;
        align-items: stretch;
    }

    /* Stats card — sticky */
    .stats-card {
        background: white;
        border-radius: 14px;
        padding: 22px;
        border: 1px solid #E8E0D8;
        position: sticky;
        top: 20px;
        max-height: calc(100vh - 40px);
        overflow-y: auto;
    }
    .stat-label { font-size: 10px; font-weight: 600; color: #A89880; text-transform: uppercase; letter-spacing: .08em; margin-bottom: 5px; }
    .stat-balance { font-size: 28px; font-weight: 700; color: #3D2B1F; line-height: 1; margin-bottom: 8px; }
    .stat-balance .pts-number { color: #C17F4A; }
    .stat-balance small { font-size: 13px; font-weight: 500; color: #7A6552; margin-left: 4px; }
    .balance-badge {
        display: inline-flex; align-items: center; gap: 4px;
        background: #FEF6EC; border: 1px solid #F3D9B1;
        border-radius: 999px; padding: 3px 10px;
        font-size: 11px; font-weight: 600; color: #A0622A;
        margin-bottom: 16px;
    }
    .stat-divider { height: 1px; background: #F0EBE4; margin: 14px 0; }
    .stat-mini-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .stat-val { font-size: 15px; font-weight: 600; margin-top: 3px; }
    .stat-val.earned  { color: #4A7C59; }
    .stat-val.redeemed { color: #8B5E3C; }

    /* Activity card */
    .activity-card {
        background: white;
        border-radius: 14px;
        border: 1px solid #E8E0D8;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 40px);
    }
    .activity-header {
        padding: 14px 18px 12px;
        border-bottom: 1px solid #F0EBE4;
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
    }

    /* Ledger scroll */
    .ledger-scroll {
        overflow-y: auto;
        flex: 1;
        min-height: 0;
    }
    .ledger-scroll::-webkit-scrollbar { width: 4px; }
    .ledger-scroll::-webkit-scrollbar-track { background: transparent; }
    .ledger-scroll::-webkit-scrollbar-thumb { background: #E0D8CF; border-radius: 4px; }

    /* Filter pills */
    .filter-pills-wrap { display: flex; gap: 5px; overflow-x: auto; scrollbar-width: none; }
    .filter-pills-wrap::-webkit-scrollbar { display: none; }
    .history-pill {
        background: transparent; border: 1px solid #E0D8CF;
        padding: 6px 14px; border-radius: 999px;
        font-size: 12px; font-weight: 600; color: #9A8676;
        cursor: pointer; transition: all .18s; white-space: nowrap;
    }
    .history-pill.active { background: #3D2B1F; color: white; border-color: #3D2B1F; }
    .history-pill:hover:not(.active) { background: #FAF7F3; color: #3D2B1F; border-color: #C8BEB4; }

    /* Ledger rows */
    .ledger-row { border-bottom: 1px solid #F0EBE4; transition: background .15s; }
    .ledger-row:last-child { border-bottom: none; }
    .ledger-row:hover { background: #FDFAF7; }
    .ledger-main-info {
        padding: 13px 18px;
        display: flex; align-items: center; gap: 12px;
        cursor: pointer;
    }
    .icon-status-frame {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .icon-status-frame.earned   { background: #EDF4EF; color: #4A7C59; }
    .icon-status-frame.redeemed { background: #FBF0E8; color: #8B5E3C; }

    .row-info { flex: 1; min-width: 0; }
    .row-title-wrap { display: flex; align-items: center; gap: 6px; margin-bottom: 3px; }
    .row-title { font-size: 13px; font-weight: 600; color: #3D2B1F; }
    .new-badge {
        display: inline-flex; align-items: center; gap: 3px;
        background: #FEF6EC; border: 1px solid #F3D9B1;
        border-radius: 999px; padding: 1px 7px;
        font-size: 10px; font-weight: 700; color: #A0622A;
        flex-shrink: 0; animation: pulse-badge 2s ease-in-out infinite;
    }
    @keyframes pulse-badge { 0%,100%{opacity:1} 50%{opacity:.55} }
    @keyframes shimmer { 0%,100%{opacity:1} 50%{opacity:.4} }

    .row-sub { font-size: 11px; color: #A89880; }
    .source-badge {
        display: inline-block; background: #F5F0EB; border: 1px solid #E8E0D8;
        border-radius: 5px; padding: 1px 6px; font-size: 11px; color: #7A6552; font-weight: 500;
    }
    .row-right { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }
    .points-display { font-size: 14px; font-weight: 700; }
    .points-display.earned   { color: #4A7C59; }
    .points-display.redeemed { color: #b81d1d; }
    .row-chevron { color: #C8BEB4; transition: transform .25s ease; flex-shrink: 0; }
    .ledger-row.is-open .row-chevron { transform: rotate(180deg); }

    /* Accordion */
    .ledger-detail-panel { max-height: 0; overflow: hidden; transition: max-height .28s ease-out; background: #FAF8F5; }
    .ledger-detail-content { padding: 0 18px 16px 66px; border-top: 1px dashed #E8E0D8; font-size: 12px; }
    .detail-grid { display: grid; grid-template-columns: auto 1fr; gap: 5px 16px; padding-top: 12px; }
    .dk { color: #A89880; font-weight: 500; }
    .dv { color: #3D2B1F; font-weight: 600; }
    .pts-tag { display: inline-flex; padding: 2px 8px; border-radius: 999px; font-size: 11px; font-weight: 600; width: fit-content;}
    .pts-tag.earned   { background: #EDF4EF; color: #3A6647; }
    .pts-tag.redeemed { background: #FDEAEA; color: #b81d1d; }

    /* Empty */
    .empty-state-wrap { padding: 48px 20px; text-align: center; }
    .empty-title { font-size: 13px; font-weight: 600; color: #3D2B1F; margin: 14px 0 4px; }
    .empty-sub { font-size: 11px; color: #A89880; }

    @media (max-width: 720px) {
        .two-col-layout {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .stats-card {
            position: static;
            max-height: none;
            overflow-y: visible;
        }

        .activity-card {
            max-height: none;
            overflow: hidden;  /* ← balik ke hidden, bukan visible */
            display: block;
        }

        .activity-header {
            position: static;
        }

        .ledger-scroll {
            flex: none;
            overflow-y: visible;
            min-height: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="history-page">
    <div style="max-width: 1100px; margin: 0 auto;">

        {{-- BACK --}}
        <a href="{{ route('reward') }}" class="back-link" style="margin-bottom: 20px; display: inline-flex;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
            </svg>
            <span>Back to Reward Center</span>
        </a>

        {{-- PAGE TITLE --}}
        <div style="margin-bottom: 16px;">
            <h1 style="font-size:30px;font-weight:700;color: var(--jaced-dark);margin:0 0 4px;">Point History</h1>
            <p style="font-size:12px;color:#A89880;margin:0;">History of point earnings and redemptions</p>
        </div>

        {{-- 2-COLUMN LAYOUT --}}
        <div class="two-col-layout">

            {{-- ═══ LEFT: STATS ═══ --}}
            <div class="stats-card">

                <p class="stat-label">Total Active Balance</p>
                <div class="stat-balance">
                    <span class="pts-number">{{ number_format($currentPoints) }}</span>
                    <small>Points</small>
                </div>
                <div class="balance-badge">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/></svg>
                    Active &amp; ready to use
                </div>

                <div class="stat-divider"></div>

                <div class="stat-mini-grid">
                    <div>
                        <p class="stat-label">Earned in {{ $selectedYear }}</p>
                        <p class="stat-val earned">+{{ number_format($earnedThisYear) }} Pts</p>
                    </div>
                    <div>
                        <p class="stat-label">Used in {{ $selectedYear }}</p>
                        <p class="stat-val redeemed">{{ number_format(abs($totalRedeemed)) }} Pts</p>
                    </div>
                </div>

                <div class="stat-divider"></div>

                {{-- Progress bar --}}
                @php
                    $pct = $earnedThisYear > 0 ? min(round((abs($totalRedeemed) / $earnedThisYear) * 100), 100) : 0;
                @endphp
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:7px;">
                    <span class="stat-label" style="margin:0;">Points used this year</span>
                    <span style="font-size:11px;font-weight:700;color:#C17F4A;">{{ $pct }}%</span>
                </div>
                <div style="height:7px;background:#F0EBE4;border-radius:999px;overflow:hidden;margin-bottom:5px;">
                    <div id="progressFill" style="height:100%;border-radius:999px;background:linear-gradient(90deg,#C17F4A,#D4956A);width:0%;transition:width 1.1s cubic-bezier(.4,0,.2,1);"></div>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:10px;color:#B8A898;">
                    <span>{{ number_format(abs($totalRedeemed)) }} redeemed</span>
                    <span>{{ number_format(abs($totalRedeemed)) }} / {{ number_format($earnedThisYear) }} earned</span>
                </div>

                {{-- Mini bar chart --}}
                @php
                    $monthlyData = [];
                    for ($m = 1; $m <= 12; $m++) {
                        $monthlyData[$m] = ['earned' => 0, 'redeemed' => 0];
                    }
                    foreach ($histories as $h) {
                        $mo = \Carbon\Carbon::parse($h->created_at)->month;
                        if ($h->type === 'earned')   $monthlyData[$mo]['earned']   += abs($h->points);
                        if (in_array($h->type, ['redeemed', 'expired'])) $monthlyData[$mo]['redeemed'] += abs($h->points);
                    }
                    $maxVal = max(array_merge(
                        array_column($monthlyData, 'earned'),
                        array_column($monthlyData, 'redeemed'),
                        [1]
                    ));
                    $monthLabels = ['J','F','M','A','M','J','J','A','S','O','N','D'];
                @endphp
                <div style="margin-top:16px;">
                    <p class="stat-label" style="margin-bottom:10px;">Monthly activity ({{ $selectedYear }})</p>
                    <div style="display:flex;align-items:flex-end;gap:3px;height:50px;">
                        @foreach($monthLabels as $i => $label)
                            @php
                                $mo  = $i + 1;
                                $eH  = max(round(($monthlyData[$mo]['earned']   / $maxVal) * 40), $monthlyData[$mo]['earned']   > 0 ? 4 : 2);
                                $rH  = max(round(($monthlyData[$mo]['redeemed'] / $maxVal) * 40), $monthlyData[$mo]['redeemed'] > 0 ? 4 : 2);
                                $eOp = $monthlyData[$mo]['earned']   > 0 ? '1' : '.18';
                                $rOp = $monthlyData[$mo]['redeemed'] > 0 ? '1' : '.18';
                            @endphp
                            <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:3px;">
                                <div style="display:flex;gap:2px;align-items:flex-end;height:42px;">
                                    <div style="width:7px;height:{{ $eH }}px;background:#7BB897;border-radius:3px 3px 0 0;opacity:{{ $eOp }};"></div>
                                    <div style="width:7px;height:{{ $rH }}px;background:#D4956A;border-radius:3px 3px 0 0;opacity:{{ $rOp }};"></div>
                                </div>
                                <span style="font-size:9px;color:#B8A898;">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div style="display:flex;gap:12px;margin-top:8px;">
                        <span style="display:flex;align-items:center;gap:4px;font-size:10px;color:#A89880;">
                            <span style="width:10px;height:10px;border-radius:2px;background:#7BB897;display:inline-block;"></span>Earned
                        </span>
                        <span style="display:flex;align-items:center;gap:4px;font-size:10px;color:#A89880;">
                            <span style="width:10px;height:10px;border-radius:2px;background:#D4956A;display:inline-block;"></span>Redeemed
                        </span>
                    </div>
                </div>

            </div>

            {{-- ═══ RIGHT: ACTIVITY ═══ --}}
            <div class="activity-card">

                {{-- Sticky header filter --}}
                <div class="activity-header">
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;">
                        <div class="filter-pills-wrap">
                            <button class="history-pill active" data-filter="all">All Activity</button>
                            <button class="history-pill" data-filter="earned">Earned</button>
                            <button class="history-pill" data-filter="redeemed">Redeemed</button>
                            <button class="history-pill" data-filter="expired">Expired</button>
                        </div>
                        <select class="input-jaced" id="yearFilter"
                            style="padding:6px 28px 6px 10px !important;font-size:12px !important;font-weight:600 !important;width:auto;background:white !important;border-radius:8px !important;flex-shrink:0;">
                            @forelse($availableYears as $year)
                                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                    Year: {{ $year }}
                                </option>
                            @empty
                                <option value="{{ $currentYear }}">Year: {{ $currentYear }}</option>
                            @endforelse
                        </select>
                    </div>
                </div>

                {{-- Skeleton --}}
                <div id="skeletonLoader" style="display:none;">
                    @for($s = 0; $s < 5; $s++)
                        <div style="padding:13px 18px;display:flex;align-items:center;gap:12px;border-bottom:1px solid #F0EBE4;">
                            <div style="width:36px;height:36px;border-radius:50%;background:#F0EBE4;flex-shrink:0;animation:shimmer 1.4s ease-in-out infinite;"></div>
                            <div style="flex:1;display:flex;flex-direction:column;gap:6px;">
                                <div style="height:10px;width:55%;border-radius:6px;background:#F0EBE4;animation:shimmer 1.4s ease-in-out infinite;"></div>
                                <div style="height:10px;width:35%;border-radius:6px;background:#F0EBE4;animation:shimmer 1.4s ease-in-out infinite;"></div>
                            </div>
                            <div style="width:60px;height:13px;border-radius:6px;background:#F0EBE4;animation:shimmer 1.4s ease-in-out infinite;"></div>
                        </div>
                    @endfor
                </div>

                {{-- Ledger scroll --}}
                <div class="ledger-scroll" id="ledgerList">
                    @forelse($histories as $history)
                        <div class="ledger-row" data-type="{{ $history->type }}">
                            <div class="ledger-main-info" onclick="toggleRow(this)">
                                <div class="icon-status-frame {{ in_array($history->type, ['redeemed','expired']) ? 'redeemed' : 'earned' }}">
                                    @if($history->type === 'earned')
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                                    @elseif($history->type === 'redeemed')
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><rect width="18" height="12" x="3" y="8" rx="2"/><path d="M12 8V3"/><path d="M16 7A4 4 0 1 0 8 7"/></svg>
                                    @else
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                        </svg>
                                    @endif
                                </div>

                                <div class="row-info">
                                    <div class="row-title-wrap">
                                        <span class="row-title">
                                            {{ $history->type === 'earned' ? 'Purchase Reward' : ($history->type === 'redeemed' ? 'Redeemed Voucher' : 'Points Expired') }}
                                        </span>
                                        @if($loop->first)
                                            <span class="new-badge">✦ New</span>
                                        @endif
                                    </div>
                                    <div class="row-sub">
                                        <span class="source-badge">{{ ucfirst(str_replace('_', ' ', $history->source)) }}</span>
                                        &nbsp;{{ \Carbon\Carbon::parse($history->created_at)->format('d M Y') }}
                                        @if($history->type === 'earned' && $history->expired_at)
                                            @php $daysLeft = now()->diffInDays($history->expired_at, false); @endphp
                                            @if($daysLeft <= 7 && $daysLeft > 0)
                                                <span style="color:#C17F4A;font-size:10px;font-weight:600;">· Expires in {{ $daysLeft }}d</span>
                                            @elseif($daysLeft <= 0)
                                                <span style="color:#E05252;font-size:10px;font-weight:600;">· Expired</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                <div class="row-right">
                                    <span class="points-display {{ in_array($history->type, ['redeemed','expired']) ? 'redeemed' : 'earned' }}">
                                        {{ $history->type === 'earned' ? '+' : '-' }}{{ number_format(abs($history->points)) }} Pts
                                    </span>
                                    <svg class="row-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                        <polyline points="6 9 12 15 18 9"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="ledger-detail-panel">
                                <div class="ledger-detail-content">
                                    <div class="detail-grid">
                                        <span class="dk">Type</span>
                                        <span class="pts-tag {{ in_array($history->type, ['redeemed','expired']) ? 'redeemed' : 'earned' }}">
                                            {{ ucfirst($history->type) }}
                                        </span>
                                        <span class="dk">Source</span>
                                        <span class="dv">{{ ucfirst(str_replace('_', ' ', $history->source)) }}</span>
                                        <span class="dk">Points</span>
                                        <span class="dv">{{ $history->type === 'earned' ? '+' : '-' }}{{ number_format(abs($history->points)) }} Pts</span>
                                        <span class="dk">Date</span>
                                        <span class="dv">{{ \Carbon\Carbon::parse($history->created_at)->format('d M Y, H:i') }}</span>
                                        @if($history->order_id && $history->type !== 'expired')
                                            <span class="dk">Order ID</span>
                                            <span class="dv">#{{ $history->order_id }}</span>
                                        @endif
                                        @if($history->expired_at && $history->type === 'earned')
                                            <span class="dk">Expires</span>
                                            <span class="dv" style="color: #C17F4A;">
                                                {{ \Carbon\Carbon::parse($history->expired_at)->format('d M Y') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state-wrap">
                            <svg width="52" height="52" viewBox="0 0 52 52" fill="none">
                                <circle cx="26" cy="26" r="22" fill="#F5F0EB" stroke="#E8E0D8" stroke-width="1.5"/>
                                <path d="M18 26h16M26 18v16" stroke="#D4C5B5" stroke-width="2" stroke-linecap="round"/>
                                <circle cx="26" cy="26" r="5" fill="#EDE3D8"/>
                                <path d="M22 22l8 8M30 22l-8 8" stroke="#C8BAA8" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                            <p class="empty-title">Tidak ada riwayat di {{ $selectedYear }}</p>
                            <p class="empty-sub">Coba pilih tahun lain dari dropdown.</p>
                        </div>
                    @endforelse

                    <div id="filterEmptyState" style="display:none;">
                        <div class="empty-state-wrap">
                            <svg width="52" height="52" viewBox="0 0 52 52" fill="none">
                                <circle cx="26" cy="26" r="22" fill="#F5F0EB" stroke="#E8E0D8" stroke-width="1.5"/>
                                <path d="M18 26h16M26 18v16" stroke="#D4C5B5" stroke-width="2" stroke-linecap="round"/>
                                <circle cx="26" cy="26" r="5" fill="#EDE3D8"/>
                                <path d="M22 22l8 8M30 22l-8 8" stroke="#C8BAA8" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                            <p class="empty-title">Tidak ada transaksi di kategori ini</p>
                            <p class="empty-sub">Coba filter "All Activity".</p>
                        </div>
                    </div>
                </div>

                {{-- Pagination --}}
                @if($histories->hasPages())
                    <div style="padding:12px 18px;border-top:1px solid #F0EBE4;display:flex;justify-content:center;">
                        {{ $histories->appends(['year' => $selectedYear])->links() }}
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    /* progress bar */
    document.addEventListener('DOMContentLoaded', function () {
        const fill = document.getElementById('progressFill');
        if (fill) setTimeout(() => { fill.style.width = '{{ $pct }}%'; }, 300);
    });

    /* accordion */
    function toggleRow(el) {
        const row   = el.parentElement;
        const panel = row.querySelector('.ledger-detail-panel');
        const isOpen = row.classList.contains('is-open');
        document.querySelectorAll('.ledger-row.is-open').forEach(r => {
            r.classList.remove('is-open');
            r.querySelector('.ledger-detail-panel').style.maxHeight = null;
        });
        if (!isOpen) {
            row.classList.add('is-open');
            panel.style.maxHeight = panel.scrollHeight + 'px';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const pills      = document.querySelectorAll('.history-pill');
        const yearSelect = document.getElementById('yearFilter');
        const rows       = document.querySelectorAll('.ledger-row');
        const emptyState = document.getElementById('filterEmptyState');

        yearSelect.addEventListener('change', function () {
            document.getElementById('ledgerList').style.display   = 'none';
            document.getElementById('skeletonLoader').style.display = 'block';
            const url = new URL(window.location.href);
            url.searchParams.set('year', this.value);
            window.location.href = url.toString();
        });

        pills.forEach(pill => {
            pill.addEventListener('click', function () {
                pills.forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                applyTypeFilter(this.getAttribute('data-filter'));
            });
        });

        function applyTypeFilter(type) {
            let visible = 0;
            rows.forEach(row => {
                const match = type === 'all' || row.getAttribute('data-type') === type;
                row.style.display = match ? '' : 'none';
                if (!match) {
                    row.classList.remove('is-open');
                    const panel = row.querySelector('.ledger-detail-panel');
                    if (panel) panel.style.maxHeight = null;
                }
                if (match) visible++;
            });
            if (rows.length === 0) {
                emptyState.style.display = 'none';
            } else {
                emptyState.style.display = visible === 0 ? '' : 'none';
            }
        }

        applyTypeFilter('all');

        /* konfetti — hanya muncul kalau transaksi pertama adalah earned */
        @if($histories->isNotEmpty() && $histories->first()->type === 'earned')
        (function () {
            const canvas = document.createElement('canvas');
            canvas.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;pointer-events:none;z-index:9999;';
            document.body.appendChild(canvas);
            const ctx = canvas.getContext('2d');
            canvas.width  = window.innerWidth;
            canvas.height = window.innerHeight;
            const colors = ['#C17F4A','#4A7C59','#D4956A','#7BB897','#F3D9B1','#C8DFC6'];
            let pts = [];
            function spawn() {
                for (let i = 0; i < 80; i++) pts.push({
                    x: Math.random() * canvas.width, y: -10,
                    vx: (Math.random() - .5) * 3, vy: Math.random() * 3 + 2,
                    sz: Math.random() * 6 + 3,
                    color: colors[Math.floor(Math.random() * colors.length)],
                    rot: Math.random() * 360, rspd: (Math.random() - .5) * 6,
                    alpha: 1, life: 0
                });
            }
            function draw() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                pts = pts.filter(p => p.alpha > 0);
                pts.forEach(p => {
                    p.x += p.vx; p.y += p.vy; p.rot += p.rspd; p.life++;
                    if (p.life > 60) p.alpha = Math.max(0, p.alpha - .022);
                    ctx.save(); ctx.translate(p.x, p.y); ctx.rotate(p.rot * Math.PI / 180);
                    ctx.globalAlpha = p.alpha; ctx.fillStyle = p.color;
                    ctx.fillRect(-p.sz / 2, -p.sz / 2, p.sz, p.sz * .5);
                    ctx.restore();
                });
                if (pts.length > 0) requestAnimationFrame(draw);
                else { ctx.clearRect(0, 0, canvas.width, canvas.height); canvas.remove(); }
            }
            setTimeout(() => { spawn(); draw(); }, 700);
        })();
        @endif
    });
</script>
@endpush