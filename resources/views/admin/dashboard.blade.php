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

        /* Colorblind-safe accent palette (no red/green confusion)   */
        /* Uses Blue / Orange / Teal / Purple                        */
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

    /* ── Base ────────────────────────────────────────────────────── */
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
    .dash-header .date-pill {
        background: var(--cream-2);
        border: 1px solid var(--border);
        border-radius: 99px;
        padding: 6px 16px;
        font-size: 13px;
        color: var(--ink-2);
        font-weight: 500;
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

    /* ── Traffic progress bars ───────────────────────────────────── */
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

    /* ── Best Selling Products ───────────────────────────────────── */
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

    /* Status badges – distinct shapes for colorblind friendliness */
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
    .status-onsite    { background: var(--teal-soft);   color: var(--teal);   }
    .status-onsite::before    { background: var(--teal);   }
    .status-processing{ background: var(--amber-soft);  color: var(--amber);  }
    .status-processing::before{ background: var(--amber);  }
    .status-shipped   { background: var(--blue-soft);   color: var(--blue);   }
    .status-shipped::before   { background: var(--blue);   }

    /* action button */
    .btn-dots {
        background: none; border: none; padding: 4px 8px;
        color: var(--ink-muted); cursor: pointer; border-radius: 6px;
        transition: background .1s;
    }
    .btn-dots:hover { background: var(--cream-2); }

    /* ── View All link ───────────────────────────────────────────── */
    .view-all {
        font-size: 12px; font-weight: 600;
        color: var(--accent);
        text-decoration: none;
        letter-spacing: .02em;
    }
    .view-all:hover { text-decoration: underline; }

    /* ── Filter button ───────────────────────────────────────────── */
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
</style>

<div class="dashboard-wrap container-fluid px-4 py-4">

    {{-- ── Page Header ── --}}
    <div class="dash-header">
        <div>
            <p class="label mb-1">Overview</p>
            <h1>Dashboard</h1>
        </div>
        <span class="date-pill">October 2023</span>
    </div>

    {{-- ── Row 1: Stat Cards ── --}}
    <div class="row g-3 mb-3">

        <div class="col-6 col-md-3">
            <div class="d-card stat-card accent-border">
                <div class="top-row">
                    <div class="icon-wrap" style="background:var(--accent-soft); color:var(--accent);">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <span class="pill pill-amber">+12.5%</span>
                </div>
                <p class="label">Total Revenue</p>
                <p class="value">$128,430</p>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="d-card stat-card">
                <div class="top-row">
                    <div class="icon-wrap" style="background:var(--blue-soft); color:var(--blue);">
                        <i class="bi bi-basket"></i>
                    </div>
                    <span class="pill pill-blue">Monthly</span>
                </div>
                <p class="label">Total Orders</p>
                <p class="value">1,240</p>
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
                <p class="value">45</p>
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
                <p class="value">12 items</p>
            </div>
        </div>

    </div>

    {{-- ── Row 2: Sales Analytics | Monthly Target | Customer Traffic ── --}}
    <div class="row g-3 mb-3">

        {{-- Sales Analytics --}}
        <div class="col-12 col-md-5">
            <div class="d-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="section-title">Sales Analytics</span>
                    <span class="section-meta">Last 6 Months</span>
                </div>
                <div class="chart-outer">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Monthly Target --}}
        <div class="col-12 col-md-3">
            <div class="d-card p-3 h-100 text-center">
                <p class="section-title mb-3">Monthly Target</p>
                <div class="donut-wrap">
                    <canvas id="targetChart"></canvas>
                    <div class="donut-label">78%</div>
                </div>
                <p class="section-meta mb-1">$17,000 / $125k</p>
                <p class="section-meta mb-0">
                    Remaining: <span style="color:var(--accent); font-weight:600;">$30,000</span>
                </p>
            </div>
        </div>

        {{-- Customer Traffic --}}
        <div class="col-12 col-md-4">
            <div class="d-card p-3 h-100">
                <p class="section-title mb-3">Customer Traffic</p>

                <div class="traffic-row">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="section-meta">Organic Search</span>
                        <span style="font-size:13px; font-weight:600;">45%</span>
                    </div>
                    <div class="traffic-bar-track">
                        <div class="traffic-bar-fill" style="width:45%; background:var(--accent);"></div>
                    </div>
                </div>

                <div class="traffic-row">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="section-meta">Social Media</span>
                        <span style="font-size:13px; font-weight:600;">30%</span>
                    </div>
                    <div class="traffic-bar-track">
                        <div class="traffic-bar-fill" style="width:30%; background:var(--blue);"></div>
                    </div>
                </div>

                <div class="traffic-row">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="section-meta">Referral</span>
                        <span style="font-size:13px; font-weight:600;">8%</span>
                    </div>
                    <div class="traffic-bar-track">
                        <div class="traffic-bar-fill" style="width:8%; background:var(--teal);"></div>
                    </div>
                </div>

                <div class="traffic-row">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="section-meta">Direct</span>
                        <span style="font-size:13px; font-weight:600;">17%</span>
                    </div>
                    <div class="traffic-bar-track">
                        <div class="traffic-bar-fill" style="width:17%; background:var(--purple);"></div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- ── Row 3: Best Selling Products ── --}}
    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="d-card p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="section-title">Best Selling Products</span>
                    <a href="#" class="view-all">View All →</a>
                </div>
                <div class="row g-3">

                    <div class="col-12 col-md-4">
                        <div class="product-card">
                            <div class="product-avatar">WD</div>
                            <div>
                                <p class="product-name">Walnut Dining Table</p>
                                <p class="product-sub">Craftsman Series · 24 Units</p>
                                <p class="product-price">$2,800</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="product-card">
                            <div class="product-avatar">EC</div>
                            <div>
                                <p class="product-name">Eames-style Chair</p>
                                <p class="product-sub">Executive Lounge · 18 Units</p>
                                <p class="product-price">$1,450</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="product-card">
                            <div class="product-avatar">OS</div>
                            <div>
                                <p class="product-name">Oak Sideboard</p>
                                <p class="product-sub">Studio Minimal · 14 Units</p>
                                <p class="product-price">$1,200</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- ── Row 4: Recent Orders ── --}}
    <div class="row g-3">
        <div class="col-12">
            <div class="d-card p-3">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="section-title">Recent Orders</span>
                    <div class="dropdown">
                        <button class="btn-filter dropdown-toggle border-0"
                            type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-sliders2"></i> Filter By
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item small" href="#">All Orders</a></li>
                            <li><a class="dropdown-item small" href="#">On Site</a></li>
                            <li><a class="dropdown-item small" href="#">Processing</a></li>
                            <li><a class="dropdown-item small" href="#">Shipped</a></li>
                        </ul>
                    </div>
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

                            <tr>
                                <td><span class="order-id">#ORD-8821</span></td>
                                <td>Jonathan Reed</td>
                                <td style="color:var(--ink-muted);">Oct 11, 2023</td>
                                <td><span class="order-amt">$4,250.00</span></td>
                                <td><span class="status-badge status-onsite">On Site</span></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn-dots" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item small" href="#">View Detail</a></li>
                                            <li><a class="dropdown-item small" href="#">Edit</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item small text-danger" href="#">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td><span class="order-id">#ORD-8820</span></td>
                                <td>Sarah Jenkins</td>
                                <td style="color:var(--ink-muted);">Oct 11, 2023</td>
                                <td><span class="order-amt">$1,800.00</span></td>
                                <td><span class="status-badge status-processing">Processing</span></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn-dots" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item small" href="#">View Detail</a></li>
                                            <li><a class="dropdown-item small" href="#">Edit</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item small text-danger" href="#">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td><span class="order-id">#ORD-8819</span></td>
                                <td>Holloway Design Co.</td>
                                <td style="color:var(--ink-muted);">Oct 11, 2023</td>
                                <td><span class="order-amt">$12,600.00</span></td>
                                <td><span class="status-badge status-shipped">Shipped</span></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn-dots" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item small" href="#">View Detail</a></li>
                                            <li><a class="dropdown-item small" href="#">Edit</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item small text-danger" href="#">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</div>

{{-- ── Charts (Chart.js) ── --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Sales Line Chart ──────────────────────────────────────────
    const salesCtx = document.getElementById('salesChart').getContext('2d');

    const gradient = salesCtx.createLinearGradient(0, 0, 0, 190);
    gradient.addColorStop(0,   'rgba(184,115,51,0.18)');
    gradient.addColorStop(1,   'rgba(184,115,51,0)');

    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: ['May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
            datasets: [{
                data: [41000, 58000, 69000, 65000, 63000, 87000],
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
                    beginAtZero: false,
                    min: 35000,
                    ticks: {
                        callback: v => '$' + (v/1000) + 'k',
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

    // ── Donut Chart ───────────────────────────────────────────────
    const targetCtx = document.getElementById('targetChart').getContext('2d');
    new Chart(targetCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [78, 22],
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