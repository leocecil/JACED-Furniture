@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">

<div class="container-fluid">

    {{-- ── Page Header ── --}}
    <div class="dash-header">
        <div>
            <h1>Dashboard</h1>
        </div>
        <span style="font-size:13px; color:var(--ink-muted);">
            {{ now()->format('d M Y') }}
        </span>
    </div>

    {{-- ── Stat Filter ── --}}
    <div class="d-flex gap-2 mb-3 flex-wrap">
        @foreach(['all' => 'All Time', 'week' => 'This Week', 'month' => 'This Month', '3m' => 'Last 3 Months', 'year' => 'This Year'] as $val => $label)
            <button class="card-range-btn stat-filter-btn {{ $val === 'all' ? 'active' : '' }}"
                    data-range="{{ $val }}">{{ $label }}</button>
        @endforeach
    </div>

    {{-- ── Row 1: Stat Cards ── --}}
    <div class="row g-3 mb-3">

        <div class="col-6 col-md-3">
            <div class="d-card stat-card accent-border"
                style="cursor:pointer;"
                onclick="window.location='{{ route('order_management') }}'">
                <div class="top-row">
                    <div class="icon-wrap" style="background:var(--accent-soft); color:var(--accent);">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <span class="pill pill-amber" id="revenue-pill">All Time</span>
                </div>
                <p class="label">Total Revenue</p>
                <p class="value" id="revenue-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="d-card stat-card"
                style="cursor:pointer;"
                onclick="window.location='{{ route('order_management') }}'">
                <div class="top-row">
                    <div class="icon-wrap" style="background:var(--blue-soft); color:var(--blue);">
                        <i class="bi bi-basket"></i>
                    </div>
                    <span class="pill pill-blue" id="orders-pill">All Time</span>
                </div>
                <p class="label">Total Orders</p>
                <p class="value" id="orders-value">{{ number_format($totalOrders) }}</p>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="d-card stat-card"
                style="cursor:pointer;"
                onclick="window.location='{{ route('order_management') }}?status=delivered'">
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
                        @foreach([3 => '3M', 6 => '6M', 12 => '1Y'] as $val => $label)
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
                    <button class="btn-set-target" onclick="openTargetModal()">
                        Set Target
                    </button>
                </div>

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
                    <a href="{{ route('order_management') }}" class="view-all">View All →</a>
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
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 section-meta">No orders yet.</td>
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
<div id="targetOverlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:99999; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:16px; border:1px solid var(--border); width:100%; max-width:320px; padding:24px; position:relative;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h6 style="margin:0; font-weight:700; font-size:15px; color:var(--ink);">Set Monthly Target</h6>
            <button onclick="closeTargetModal()" style="background:none; border:none; font-size:20px; color:var(--ink-muted); cursor:pointer; line-height:1;">×</button>
        </div>
        <form method="POST" action="{{ route('admin.dashboard.setTarget') }}">
            @csrf
            <label class="section-meta mb-1 d-block">Target Amount (Rp)</label>
            <input type="number" name="monthly_target" class="form-control form-control-sm mb-1"
                    value="{{ $monthlyTarget }}"
                    min="1000000" step="1000000" required>
            <small class="section-meta">e.g. 50000000 = Rp 50.000.000</small>
            <button type="submit" class="btn btn-sm w-100 mt-3"
                    style="background:var(--accent); color:#fff; border-radius:8px;">
                Save Target
            </button>
        </form>
    </div>
</div>

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

<script>
    window.dashboardData = {
        salesLabels: @json($salesData['labels']),
        salesData: @json($salesData['data']),
        pct: {{ $pct }},
        salesChartUrl: "{{ route('admin.dashboard.salesChart') }}",
        statFilterUrl: "{{ route('admin.dashboard.statCards') }}"
    };
</script>

<script src="{{ asset('js/admin/dashboard.js') }}"></script>

@endpush

@endsection