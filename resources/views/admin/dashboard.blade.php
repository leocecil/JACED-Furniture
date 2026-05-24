@extends('layouts.app')

@section('content')

<style>
    /* ── Root tokens ──────────────────────────────────────────────── */
    :root {
        --cream:        #FAF8F4;
        --cream-2:      #F3F0EA;
        --cream-3:      #EAE5DB;
        --ink:          #1A1714;
        --ink-2:        #3D3830;
        --ink-muted:    #7A7369;
        --border:       #DDD8CF;
        --accent:       #B87333;
        --accent-soft:  #F5E6D3;
        --blue:         #2667CC;
        --blue-soft:    #DBE8FB;
        --teal:         #007A7A;
        --teal-soft:    #CCEAEA;
        --amber:        #C47B00;
        --amber-soft:   #FAF0D0;
        --danger:       #A0320A;
        --danger-soft:  #FAE0D3;
        --purple:       #5E3FA3;
        --purple-soft:  #E8E0F7;
        --green:        #1E7D45;
        --green-soft:   #D4EDDA;
    }

    body, .dashboard-wrap {
        background: var(--cream);
        font-family: var(--bs-font-sans-serif);
        color: var(--ink);
    }

    /* ── Cards ───────────────────────────────────────────────────── */
    .d-card {
        background: #FFFFFF;
        border: 1px solid var(--border);
        border-radius: 16px;
        box-shadow: 0 1px 4px rgba(0,0,0,.04), 0 2px 12px rgba(0,0,0,.04);
    }

    /* ── Page header ─────────────────────────────────────────────── */
    .dash-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 1.5rem;
    }
    .dash-header .label {
        font-size: 11px;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--ink-muted);
        font-weight: 500;
    }
    .dash-header h1 {
        font-size: 1.75rem;
        font-weight: 700;
        letter-spacing: -.02em;
        margin: 0;
    }

    /* ── Stat cards ──────────────────────────────────────────────── */
    .stat-card {
        padding: 20px;
        height: 100%;
    }
    .stat-card .icon-wrap {
        width: 44px; height: 44px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .stat-card .top-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }
    .stat-card .label {
        font-size: 11px;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--ink-muted);
        font-weight: 500;
        margin-bottom: 4px;
    }
    .stat-card .value {
        font-size: 1.6rem;
        font-weight: 700;
        letter-spacing: -.03em;
        line-height: 1;
        color: var(--ink);
    }
    .stat-card.accent-border { border-left: 4px solid var(--accent); }

    /* ── Pill badges ─────────────────────────────────────────────── */
    .pill {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 99px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .04em;
    }
    .pill-amber  { background: var(--amber-soft);  color: var(--amber);  }
    .pill-blue   { background: var(--blue-soft);   color: var(--blue);   }
    .pill-teal   { background: var(--teal-soft);   color: var(--teal);   }
    .pill-danger { background: var(--danger-soft); color: var(--danger); }
    .pill-purple { background: var(--purple-soft); color: var(--purple); }

    /* ── Section titles ──────────────────────────────────────────── */
    .section-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--ink);
        letter-spacing: -.01em;
    }
    .section-meta {
        font-size: 12px;
        color: var(--ink-muted);
    }

    /* ── Chart wrapper ───────────────────────────────────────────── */
    .chart-outer { position: relative; height: 190px; }

    /* ── Range selector ──────────────────────────────────────────── */
    .range-btn {
        background: none;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 500;
        color: var(--ink-muted);
        cursor: pointer;
        transition: all .15s;
    }
    .range-btn.active, .range-btn:hover {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
    }

    /* ── Monthly target donut ─────────────────────────────────────── */
    .donut-wrap {
        position: relative;
        width: 140px; height: 140px;
        margin: 0 auto 12px;
    }
    .donut-label {
        position: absolute;
        inset: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; font-weight: 700;
        letter-spacing: -.03em;
    }

    /* ── Best Selling Categories ──────────────────────────────────── */
    .category-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid var(--cream-2);
    }
    .category-row:last-child { border-bottom: none; }
    .category-rank {
        width: 24px; height: 24px;
        border-radius: 50%;
        background: var(--cream-2);
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 700;
        color: var(--ink-muted);
        flex-shrink: 0;
    }
    .category-rank.top { background: var(--accent-soft); color: var(--accent); }
    .category-name {
        font-size: 13px; font-weight: 600;
        color: var(--ink);
        text-transform: capitalize;
        flex: 1;
    }
    .category-units {
        font-size: 12px;
        color: var(--ink-muted);
    }
    .category-bar-track {
        height: 6px;
        border-radius: 99px;
        background: var(--cream-3);
        flex: 1;
        overflow: hidden;
    }
    .category-bar-fill {
        height: 100%;
        border-radius: 99px;
        background: var(--accent);
        transition: width .6s ease;
    }
    .category-revenue {
        font-size: 12px; font-weight: 600;
        color: var(--ink);
        min-width: 80px;
        text-align: right;
    }

    /* ── Orders table ─────────────────────────────────────────────── */
    .orders-table { width: 100%; border-collapse: collapse; }
    .orders-table thead th {
        font-size: 10px;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--ink-muted);
        font-weight: 500;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border);
    }
    .orders-table tbody td {
        padding: 14px 0;
        font-size: 13px;
        border-bottom: 1px solid var(--cream-2);
        vertical-align: middle;
    }
    .orders-table tbody tr:last-child td { border-bottom: none; }
    .order-id { font-family: var(--bs-font-monospace); font-size: 12px; font-weight: 500; color: var(--accent); }
    .order-amt { font-weight: 600; color: var(--ink); }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .04em;
    }
    .status-badge::before {
        content: '';
        width: 6px; height: 6px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .status-unpaid    { background: var(--amber-soft);  color: var(--amber);  }
    .status-unpaid::before    { background: var(--amber);  }
    .status-packed    { background: var(--blue-soft);   color: var(--blue);   }
    .status-packed::before    { background: var(--blue);   }
    .status-delivered { background: var(--purple-soft); color: var(--purple); }
    .status-delivered::before { background: var(--purple); }
    .status-arrived   { background: var(--teal-soft);   color: var(--teal);   }
    .status-arrived::before   { background: var(--teal);   }
    .status-cancelled { background: var(--danger-soft); color: var(--danger); }
    .status-cancelled::before { background: var(--danger); }

    .btn-dots {
        background: none; border: none; padding: 4px 8px;
        color: var(--ink-muted); cursor: pointer; border-radius: 6px;
        transition: background .1s;
    }
    .btn-dots:hover { background: var(--cream-2); }

    .view-all {
        font-size: 12px; font-weight: 600;
        color: var(--accent);
        text-decoration: none;
        letter-spacing: .02em;
    }
    .view-all:hover { text-decoration: underline; }

    .btn-filter {
        background: var(--cream-2);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 12px; font-weight: 500;
        color: var(--ink-2);
        cursor: pointer;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-filter:hover { background: var(--cream-3); }

    /* ── Monthly target set button ───────────────────────────────── */
    .btn-set-target {
        font-size: 11px;
        color: var(--accent);
        background: none;
        border: 1px dashed var(--accent);
        border-radius: 6px;
        padding: 3px 8px;
        cursor: pointer;
        font-weight: 500;
        transition: background .15s;
    }
    .btn-set-target:hover { background: var(--accent-soft); }
</style>

<div class="dashboard-wrap container-fluid px-4 py-4">

    {{-- ── Page Header ── --}}
    <div class="dash-header">
        <div>
            <p class="label mb-1">Overview</p>
            <h1>Dashboard</h1>
        </div>
        <span style="font-size:13px; color:var(--ink-muted);">
            {{ now()->format('d M Y') }}
        </span>
    </div>

    {{-- ── Row 1: Stat Cards ── --}}
    <div class="row g-3 mb-3">

        <div class="col-6 col-md-3">
            <div class="d-card stat-card accent-border">
                <div class="top-row">
                    <div class="icon-wrap" style="background:var(--accent-soft); color:var(--accent);">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <span class="pill pill-amber">All Time</span>
                </div>
                <p class="label">Total Revenue</p>
                <p class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="d-card stat-card">
                <div class="top-row">
                    <div class="icon-wrap" style="background:var(--blue-soft); color:var(--blue);">
                        <i class="bi bi-basket"></i>
                    </div>
                    <span class="pill pill-blue">All Time</span>
                </div>
                <p class="label">Total Orders</p>
                <p class="value">{{ number_format($totalOrders) }}</p>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="d-card stat-card">
                <div class="top-row">
                    <div class="icon-wrap" style="background:var(--teal-soft); color:var(--teal);">
                        <i class="bi bi-truck"></i>
                    </div>
                    <span class="pill pill-teal">In Transit</span>
                </div>
                <p class="label">In Delivery</p>
                <p class="value">{{ $inDelivery }}</p>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="d-card stat-card">
                <div class="top-row">
                    <div class="icon-wrap" style="background:var(--danger-soft); color:var(--danger);">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <span class="pill pill-danger">Urgent</span>
                </div>
                <p class="label">Low Stock</p>
                <p class="value">{{ $lowStockCount }} items</p>
            </div>
        </div>

    </div>

    {{-- ── Row 2: Sales Analytics | Monthly Target | Best Selling Categories ── --}}
    <div class="row g-3 mb-3">

        {{-- Sales Analytics --}}
        <div class="col-12 col-md-5">
            <div class="d-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="section-title">Sales Analytics</span>
                    <div class="d-flex gap-1">
                        @foreach([1 => '1M', 3 => '3M', 6 => '6M', 12 => '1Y'] as $val => $label)
                            <button class="range-btn {{ $months == $val ? 'active' : '' }}"
                                    data-months="{{ $val }}">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>
                <div class="chart-outer">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Monthly Target --}}
        <div class="col-12 col-md-3">
            <div class="d-card p-3 h-100 text-center">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p class="section-title mb-0">Monthly Target</p>
                    <button class="btn-set-target" data-bs-toggle="modal" data-bs-target="#targetModal">
                        Set Target
                    </button>
                </div>

                @php
                    $monthlyTarget  = session('monthly_target', 50000000);
                    $currentRevenue = \Illuminate\Support\Facades\DB::table('orders')
                        ->whereNotIn('status', ['cancelled', 'unpaid'])
                        ->whereYear('created_at', now()->year)
                        ->whereMonth('created_at', now()->month)
                        ->sum('total_price');
                    $pct = $monthlyTarget > 0 ? min(100, round(($currentRevenue / $monthlyTarget) * 100)) : 0;
                    $remaining = max(0, $monthlyTarget - $currentRevenue);
                @endphp

                <div class="donut-wrap">
                    <canvas id="targetChart"></canvas>
                    <div class="donut-label">{{ $pct }}%</div>
                </div>
                <p class="section-meta mb-1">
                    Rp {{ number_format($currentRevenue, 0, ',', '.') }}
                    / Rp {{ number_format($monthlyTarget, 0, ',', '.') }}
                </p>
                <p class="section-meta mb-0">
                    Remaining:
                    <span style="color:var(--accent); font-weight:600;">
                        Rp {{ number_format($remaining, 0, ',', '.') }}
                    </span>
                </p>
            </div>
        </div>

        {{-- Best Selling Categories --}}
        <div class="col-12 col-md-4">
            <div class="d-card p-3 h-100">
                <p class="section-title mb-3">Best Selling Categories</p>

                @php $maxUnits = $bestCategories->max('total_units') ?: 1; @endphp

                @forelse($bestCategories as $i => $cat)
                    <div class="category-row">
                        <div class="category-rank {{ $i === 0 ? 'top' : '' }}">{{ $i + 1 }}</div>
                        <div style="flex:1; min-width:0;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="category-name">{{ $cat->category_name }}</span>
                                <span class="category-units">{{ number_format($cat->total_units) }} units</span>
                            </div>
                            <div class="category-bar-track">
                                <div class="category-bar-fill"
                                     style="width: {{ round(($cat->total_units / $maxUnits) * 100) }}%">
                                </div>
                            </div>
                        </div>
                        <span class="category-revenue">
                            Rp {{ number_format($cat->total_revenue / 1000000, 1) }}M
                        </span>
                    </div>
                @empty
                    <p class="section-meta text-center py-3">No data yet.</p>
                @endforelse

            </div>
        </div>

    </div>

    {{-- ── Row 3: Recent Orders ── --}}
    <div class="row g-3">
        <div class="col-12">
            <div class="d-card p-3">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="section-title">Recent Orders</span>
                    <a href="{{ route('orders.index') }}" class="view-all">View All →</a>
                </div>

                <div class="table-responsive">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td><span class="order-id">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                                <td>{{ $order->customer_name }}</td>
                                <td style="color:var(--ink-muted);">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}
                                </td>
                                <td>
                                    <span class="order-amt">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $order->status }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn-dots" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item small"
                                                   href="#">
                                                   View Detail
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 section-meta">No orders yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</div>

{{-- ── Monthly Target Modal ── --}}
<div class="modal fade" id="targetModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; border:1px solid var(--border);">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-600">Set Monthly Target</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.dashboard.setTarget') }}">
                @csrf
                <div class="modal-body pt-2">
                    <label class="section-meta mb-1 d-block">Target Amount (Rp)</label>
                    <input type="number" name="monthly_target" class="form-control form-control-sm"
                           value="{{ session('monthly_target', 50000000) }}"
                           min="1000000" step="1000000" required>
                    <small class="section-meta">e.g. 50000000 = Rp 50.000.000</small>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-sm w-100"
                            style="background:var(--accent); color:#fff; border-radius:8px;">
                        Save Target
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── Charts (Chart.js) ── --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Sales data from server (initial render) ───────────────────
    let salesLabels = @json($salesData['labels']);
    let salesData   = @json($salesData['data']);
    let activeMonths = {{ $months }};

    // ── Sales Line Chart ──────────────────────────────────────────
    const salesCtx = document.getElementById('salesChart').getContext('2d');

    const makeGradient = () => {
        const g = salesCtx.createLinearGradient(0, 0, 0, 190);
        g.addColorStop(0,   'rgba(184,115,51,0.18)');
        g.addColorStop(1,   'rgba(184,115,51,0)');
        return g;
    };

    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: salesLabels,
            datasets: [{
                data: salesData,
                borderColor: '#B87333',
                borderWidth: 2.5,
                pointBackgroundColor: '#B87333',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                tension: 0.35,
                fill: true,
                backgroundColor: makeGradient(),
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        callback: v => 'Rp ' + (v / 1000000).toFixed(0) + 'M',
                        color: '#7A7369',
                        font: { size: 11 },
                        maxTicksLimit: 5,
                    },
                    grid: { color: '#EAE5DB', drawBorder: false },
                    border: { display: false }
                },
                x: {
                    ticks: { color: '#7A7369', font: { size: 11 } },
                    grid: { display: false },
                    border: { display: false }
                }
            }
        }
    });

    // ── Range buttons (AJAX) ──────────────────────────────────────
    document.querySelectorAll('.range-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const months = this.dataset.months;

            document.querySelectorAll('.range-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            fetch(`{{ route('admin.dashboard.salesChart') }}?months=${months}`)
                .then(r => r.json())
                .then(json => {
                    salesChart.data.labels              = json.labels;
                    salesChart.data.datasets[0].data    = json.data;
                    salesChart.data.datasets[0].backgroundColor = makeGradient();
                    salesChart.update();
                });
        });
    });

    // ── Donut Chart ───────────────────────────────────────────────
    const targetCtx = document.getElementById('targetChart').getContext('2d');
    const pct = {{ $pct }};
    new Chart(targetCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [pct, 100 - pct],
                backgroundColor: ['#B87333', '#EAE5DB'],
                borderWidth: 0,
                hoverOffset: 0,
            }]
        },
        options: {
            responsive: true,
            cutout: '76%',
            plugins: { legend: { display: false }, tooltip: { enabled: false } },
            animation: { animateRotate: true, duration: 900 }
        }
    });

});
</script>
@endpush

@endsection