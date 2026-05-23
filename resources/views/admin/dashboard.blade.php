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

        --accent:       #B87333;   /* warm copper/amber – primary     */
        --accent-soft:  #F5E6D3;   /* accent tint                     */
        --blue:         #2667CC;   /* info / shipped                  */
        --blue-soft:    #DBE8FB;
        --teal:         #007A7A;   /* on-site / success               */
        --teal-soft:    #CCEAEA;
        --amber:        #C47B00;   /* processing / warning            */
        --amber-soft:   #FAF0D0;
        --danger:       #A0320A;   /* low stock / urgent              */
        --danger-soft:  #FAE0D3;
        --purple:       #5E3FA3;
        --purple-soft:  #E8E0F7;
    }

    body, .dashboard-wrap {
        background: var(--cream);
        font-family: var(--bs-font-sans-serif);
        color: var(--ink);
    }

    .d-card {
        background: #FFFFFF;
        border: 1px solid var(--border);
        border-radius: 16px;
        box-shadow: 0 1px 4px rgba(0,0,0,.04), 0 2px 12px rgba(0,0,0,.04);
    }

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

    .chart-outer { position: relative; height: 190px; }

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

    .traffic-row { margin-bottom: 14px; }
    .traffic-row:last-child { margin-bottom: 0; }
    .traffic-bar-track {
        height: 8px;
        border-radius: 99px;
        background: var(--cream-3);
        overflow: hidden;
    }
    .traffic-bar-fill {
        height: 100%;
        border-radius: 99px;
        transition: width .6s ease;
    }

    .product-card {
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 14px;
        background: var(--cream-2);
        transition: box-shadow .15s;
    }
    .product-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); }
    .product-avatar {
        width: 52px; height: 52px;
        border-radius: 10px;
        background: var(--accent-soft);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 13px;
        color: var(--accent);
        flex-shrink: 0;
        letter-spacing: .02em;
    }
    .product-name { font-size: 14px; font-weight: 600; color: var(--ink); margin: 0 0 2px; }
    .product-sub  { font-size: 12px; color: var(--ink-muted); margin: 0 0 4px; }
    .product-price{ font-size: 14px; font-weight: 700; color: var(--accent); margin: 0; }

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
    
    /* Configured custom states */
    .status-onsite      { background: var(--teal-soft);   color: var(--teal);   }
    .status-onsite::before      { background: var(--teal);   }
    .status-processing  { background: var(--amber-soft);  color: var(--amber);  }
    .status-processing::before  { background: var(--amber);  }
    .status-shipped     { background: var(--blue-soft);   color: var(--blue);   }
    .status-shipped::before     { background: var(--blue);   }
    .status-cancelled   { background: var(--danger-soft); color: var(--danger); }
    .status-cancelled::before   { background: var(--danger); }

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
    .form-select-sm {
        font-size: 12px;
        border-radius: 8px;
        border: 1px solid var(--border);
        background-color: var(--cream-2);
    }
</style>

<div class="dashboard-wrap container-fluid px-4 py-4">

    {{-- ── Page Header ── --}}
    <div class="dash-header">
        <div>
            <p class="label mb-1">Overview</p>
            <h1>Dashboard</h1>
        </div>
    </div>

    {{-- ── Row 1: Dynamic Stat Cards ── --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-3">
            <div class="d-card stat-card accent-border">
                <div class="top-row">
                    <div class="icon-wrap" style="background:var(--accent-soft); color:var(--accent);">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
                <p class="label">Total Revenue</p>
                <p class="value">${{ number_format($totalRevenue, 2) }}</p>
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
                <p class="value">{{ number_format($totalOrdersCount) }}</p>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="d-card stat-card">
                <div class="top-row">
                    <div class="icon-wrap" style="background:var(--teal-soft); color:var(--teal);">
                        <i class="bi bi-truck"></i>
                    </div>
                    <span class="pill pill-teal">Active</span>
                </div>
                <p class="label">In Delivery</p>
                <p class="value">{{ $inDeliveryCount }}</p>
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

    {{-- ── Row 2: Sales Analytics | Monthly Target | Best Selling Category ── --}}
    <div class="row g-3 mb-3">
        
        {{-- Sales Analytics with Range Select Filter Toggle --}}
        <div class="col-12 col-md-5">
            <div class="d-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="section-title">Sales Analytics</span>
                    <select id="analyticsRange" class="form-select form-select-sm w-auto">
                        <option value="1m" {{ $range == '1m' ? 'selected' : '' }}>1 Month (6-Mo Trend)</option>
                        <option value="1y" {{ $range == '1y' ? 'selected' : '' }}>1 Year Trend</option>
                    </select>
                </div>
                <div class="chart-outer">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Monthly Target Panel with Action Settings Button --}}
        <div class="col-12 col-md-3">
            <div class="d-card p-3 h-100 text-center position-relative">
                <button class="btn btn-sm btn-link position-absolute top-0 end-0 m-2 text-decoration-none text-muted" 
                        data-bs-toggle="modal" data-bs-target="#setTargetModal" title="Change Monthly Target">
                    <i class="bi bi-gear-fill" style="font-size: 14px;"></i>
                </button>
                <p class="section-title mb-3">Monthly Target</p>
                <div class="donut-wrap">
                    <canvas id="targetChart"></canvas>
                    <div class="donut-label">{{ $targetPercentage }}%</div>
                </div>
                <p class="section-meta mb-1">${{ number_format($currentMonthRevenue / 1000, 1) }}k / ${{ number_format($monthlyTarget / 1000, 0) }}k</p>
                <p class="section-meta mb-0">
                    Remaining: <span style="color:var(--accent); font-weight:600;">${{ number_format($remainingTarget) }}</span>
                </p>
            </div>
        </div>

        {{-- Best Selling Category Block (Replaces Customer Traffic) --}}
        <div class="col-12 col-md-4">
            <div class="d-card p-3 h-100">
                <p class="section-title mb-3">Best Selling Categories</p>

                @php 
                    $colors = ['var(--accent)', 'var(--blue)', 'var(--teal)', 'var(--purple)'];
                @endphp

                @forelse ($bestSellingCategories as $index => $category)
                    <div class="traffic-row">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="section-meta">{{ $category->name }}</span>
                            <span style="font-size:13px; font-weight:600;">{{ $category->percentage }}%</span>
                        </div>
                        <div class="traffic-bar-track">
                            <div class="traffic-bar-fill" style="width: {{ $category->percentage }}%; background: {{ $colors[$index % 4] }};"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted small text-center my-4">No categories sold items yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── Row 3: Best Selling Products ── --}}
    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="d-card p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="section-title">Best Selling Products</span>
                </div>
                <div class="row g-3">
                    @forelse($bestSellingProducts as $product)
                        <div class="col-12 col-md-4">
                            <div class="product-card">
                                <div class="product-avatar">
                                    {{ strtoupper(substr($product->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="product-name">{{ $product->name }}</p>
                                    <p class="product-sub">Units Sold · {{ $product->units_sold ?? 0 }} Units</p>
                                    <p class="product-price">${{ number_format($product->price, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted py-2 small">No items ordered yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ── Row 4: Recent Orders (Filtered & Bound at 5 items) ── --}}
    <div class="row g-3">
        <div class="col-12">
            <div class="d-card p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="section-title">Recent Orders</span>
                    <form method="GET" action="{{ url()->current() }}" id="filterForm">
                        <input type="hidden" name="target" value="{{ $monthlyTarget }}">
                        <input type="hidden" name="range" value="{{ $range }}">
                        
                        <select name="status_filter" class="form-select form-select-sm btn-filter" onchange="document.getElementById('filterForm').submit();">
                            <option value="all" {{ request('status_filter') == 'all' || !request('status_filter') ? 'selected' : '' }}>All Orders</option>
                            <option value="onsite" {{ request('status_filter') == 'onsite' ? 'selected' : '' }}>On Site</option>
                            <option value="processing" {{ request('status_filter') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ request('status_filter') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="cancelled" {{ request('status_filter') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </form>
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
                                    <td><span class="order-id">#ORD-{{ $order->id }}</span></td>
                                    <td>{{ $order->user->name ?? 'Guest Customer' }}</td>
                                    <td style="color:var(--ink-muted);">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td><span class="order-amt">${{ number_format($order->total_price, 2) }}</span></td>
                                    <td>
                                        <span class="status-badge status-{{ strtolower($order->status) }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn-dots" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item small" href="#">View Detail</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted small">No recent orders match this filter criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Change Target Modal (Requirement 1) ── --}}
<div class="modal fade" id="setTargetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:14px;">
        <form method="GET" action="{{ url()->current() }}">
            <input type="hidden" name="status_filter" value="{{ request('status_filter', 'all') }}">
            <input type="hidden" name="range" value="{{ $range }}">
            
            <div class="modal-header border-0 pb-1">
                <h6 class="modal-title font-weight-bold">Set Monthly Target</h6>
                <button type="button" class="btn-close small" data-bs-submit="modal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-2">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted small">$</span>
                    <input type="number" name="target" class="form-control form-control-sm border-start-0" value="{{ $monthlyTarget }}" placeholder="e.g. 125000" min="1" required>
                </div>
            </div>
            <div class="modal-footer border-0 pt-1">
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-sm text-white" style="background:var(--accent);">Update Target</button>
            </div>
        </form>
        </div>
    </div>
</div>

{{-- ── Charts Integration (Chart.js) ── --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Sales Line Chart ──────────────────────────────────────────
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    let gradient = salesCtx.createLinearGradient(0, 0, 0, 190);
    gradient.addColorStop(0,   'rgba(184,115,51,0.18)');
    gradient.addColorStop(1,   'rgba(184,115,51,0)');

    let salesChartInstance = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($salesLabels) !!},
            datasets: [{
                data: {!! json_encode($salesData) !!},
                borderColor: '#B87333',
                borderWidth: 2.5,
                pointBackgroundColor: '#B87333',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                tension: 0.35,
                fill: true,
                backgroundColor: gradient,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: v => '$' + (v >= 1000 ? (v/1000) + 'k' : v),
                        color: '#7A7369',
                        font: { size: 11, family: 'DM Sans' },
                    },
                    grid: { color: '#EAE5DB', drawBorder: false },
                    border: { display: false }
                },
                x: {
                    ticks: { color: '#7A7369', font: { size: 11, family: 'DM Sans' } },
                    grid: { display: false },
                    border: { display: false }
                }
            }
        }
    });

    // Handle Sales Analytics Live Toggle Switch
    document.getElementById('analyticsRange').addEventListener('change', function() {
        const selectedRange = this.value;
        
        // Fetch values seamlessly using AJAX without changing pages completely
        fetch(`{{ url()->current() }}?range=${selectedRange}&target={{ $monthlyTarget }}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(res => {
            salesChartInstance.data.labels = res.labels;
            salesChartInstance.data.datasets[0].data = res.data;
            salesChartInstance.update();
        });
    });

    // ── Donut Chart ───────────────────────────────────────────────
    const targetCtx = document.getElementById('targetChart').getContext('2d');
    const computedPercentage = {{ min(100, $targetPercentage) }};
    const targetRemaining = 100 - computedPercentage;

    new Chart(targetCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [computedPercentage, targetRemaining],
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