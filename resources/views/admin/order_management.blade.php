@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">

@php
    $stats = [
        'processing'       => 24,
        'in_production'    => 12,
        'out_for_delivery' => 8,
        'weekly_revenue'   => 124500,
    ];

    $orders = [
        ['id' => '8821', 'customer_name' => 'Eleanor Hemingway',  'customer_email' => 'eleanor@studio.com',        'avatar_color' => '#5A6B5B', 'order_date' => 'Oct 14, 2023', 'status' => 'processing', 'total_amount' => 4850.00],
        ['id' => '8819', 'customer_name' => 'Arthur Miller',      'customer_email' => 'arthur.m@designhouse.co',   'avatar_color' => '#C99A6B', 'order_date' => 'Oct 12, 2023', 'status' => 'delivered',  'total_amount' => 12200.00],
        ['id' => '8790', 'customer_name' => 'Soren Kierkegaard',  'customer_email' => 'existential@athens.gr',     'avatar_color' => '#8A6D5A', 'order_date' => 'Oct 11, 2023', 'status' => 'shipped',    'total_amount' => 2100.00],
        ['id' => '8785', 'customer_name' => 'Frank Lloyd',        'customer_email' => 'frank@prairie.com',         'avatar_color' => '#C0776A', 'order_date' => 'Oct 10, 2023', 'status' => 'delayed',    'total_amount' => 18400.00],
        ['id' => '8772', 'customer_name' => 'Virginia Woolf',     'customer_email' => 'virginia@bloomsbury.co',    'avatar_color' => '#7B68A0', 'order_date' => 'Oct 09, 2023', 'status' => 'processing', 'total_amount' => 6750.00],
        ['id' => '8760', 'customer_name' => 'James Baldwin',      'customer_email' => 'james.b@harlem.com',        'avatar_color' => '#4A7B8A', 'order_date' => 'Oct 08, 2023', 'status' => 'delivered',  'total_amount' => 9320.00],
        ['id' => '8751', 'customer_name' => 'Maya Angelou',       'customer_email' => 'maya@letters.org',          'avatar_color' => '#7A8A5B', 'order_date' => 'Oct 07, 2023', 'status' => 'pending',    'total_amount' => 3200.00],
        ['id' => '8744', 'customer_name' => 'Oscar Wilde',        'customer_email' => 'oscar@aesthetic.ie',        'avatar_color' => '#5A4D7A', 'order_date' => 'Oct 06, 2023', 'status' => 'shipped',    'total_amount' => 7890.00],
    ];

    $statusStyles = [
        'processing'    => 'background:#FFF3E0; color:#E65100;',
        'delivered'     => 'background:#E8F5E9; color:#2E7D32;',
        'shipped'       => 'background:#FFF9C4; color:#F57F17;',
        'delayed'       => 'background:#FFEBEE; color:#C62828;',
        'pending'       => 'background:#F3E5F5; color:#6A1B9A;',
        'in_production' => 'background:#E3F2FD; color:#1565C0;',
    ];

    $currentPage = 1;
    $totalOrders = 248;
    $totalPages  = 25;
@endphp

<div class="jaced-page" style="min-height: 100vh;">
    <div style="max-width: 1100px; margin: 0 auto;">

        {{-- ===== PAGE HEADER ===== --}}
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-4">
            <div>
                <h1 class="font-serif-jaced text-jaced-dark mb-1"
                    style="font-size: clamp(1.4rem, 4vw, 1.9rem); font-weight: 700; letter-spacing: -0.5px;">
                    Order Management
                </h1>
                <nav style="font-size: 12px; color: var(--jaced-muted); font-weight: 500; letter-spacing: 0.5px;">
                    <span>STUDIO</span>
                    <span class="mx-2">›</span>
                    <span style="color: var(--jaced-brown-dark);">ACTIVE ORDERS</span>
                </nav>
            </div>
            {{-- Buttons: wrap naturally, full-width on xs --}}
            <div class="d-flex flex-wrap gap-2">
                <button class="btn d-flex align-items-center gap-2 flex-grow-1 flex-sm-grow-0"
                    style="background:white; border:1px solid var(--jaced-input); border-radius:8px; font-size:13px; font-weight:500; color:var(--jaced-brown-dark); padding:9px 16px; white-space:nowrap;">
                    <i class="bi bi-sliders"></i>
                    <span>Advanced Filters</span>
                </button>
                <button class="btn d-flex align-items-center gap-2 flex-grow-1 flex-sm-grow-0"
                    style="background:white; border:1px solid var(--jaced-input); border-radius:8px; font-size:13px; font-weight:500; color:var(--jaced-brown-dark); padding:9px 16px; white-space:nowrap;">
                    <i class="bi bi-download"></i>
                    <span>Export Manifest</span>
                </button>
            </div>
        </div>

        {{-- ===== STAT CARDS ===== --}}
        <div class="row g-3 mb-4">

            {{-- Processing --}}
            <div class="col-6 col-md-4">
                <div class="jaced-card p-3 p-md-4 h-100" style="box-shadow:0 1px 4px rgba(0,0,0,.05);">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div style="background:var(--jaced-caramel-bg); border-radius:8px; padding:8px;">
                            <i class="bi bi-clipboard-check" style="font-size:18px; color:var(--jaced-caramel);"></i>
                        </div>
                        <span style="background:#FFF3E0; color:#E65100; font-size:11px; font-weight:600; padding:3px 8px; border-radius:99px; white-space:nowrap;">+4%</span>
                    </div>
                    <p class="text-jaced-muted mb-1" style="font-size:11px; font-weight:600; letter-spacing:.8px; text-transform:uppercase;">Processing</p>
                    <p class="text-jaced-dark mb-0" style="font-size:2rem; font-weight:700; line-height:1;">{{ $stats['processing'] }}</p>
                </div>
            </div>

            {{-- Out for Delivery --}}
            <div class="col-6 col-md-4">
                <div class="jaced-card p-3 p-md-4 h-100" style="box-shadow:0 1px 4px rgba(0,0,0,.05);">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div style="background:#E3F2FD; border-radius:8px; padding:8px;">
                            <i class="bi bi-truck" style="font-size:18px; color:#1565C0;"></i>
                        </div>
                        <span style="background:#E3F2FD; color:#1565C0; font-size:11px; font-weight:600; padding:3px 8px; border-radius:99px; white-space:nowrap;">Active</span>
                    </div>
                    <p class="text-jaced-muted mb-1" style="font-size:11px; font-weight:600; letter-spacing:.8px; text-transform:uppercase;">Out for Delivery</p>
                    <p class="text-jaced-dark mb-0" style="font-size:2rem; font-weight:700; line-height:1;">{{ $stats['out_for_delivery'] }}</p>
                </div>
            </div>

            {{-- Weekly Revenue — full width on mobile so it stands out as the hero metric --}}
            <div class="col-12 col-md-4">
                <div class="p-3 p-md-4 h-100" style="background:var(--jaced-brown-dark); border-radius:12px; box-shadow:0 4px 16px rgba(0,0,0,.15);">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div style="background:rgba(255,255,255,.1); border-radius:8px; padding:8px;">
                            <i class="bi bi-credit-card" style="font-size:18px; color:white;"></i>
                        </div>
                        <span style="background:rgba(255,255,255,.15); color:white; font-size:11px; font-weight:600; padding:3px 8px; border-radius:99px; white-space:nowrap;">Goal Met</span>
                    </div>
                    <p style="color:rgba(255,255,255,.6); font-size:11px; font-weight:600; letter-spacing:.8px; text-transform:uppercase; margin-bottom:4px;">Weekly Revenue</p>
                    <p style="color:white; font-size:clamp(1.25rem,3vw,1.75rem); font-weight:700; line-height:1; margin:0;">
                        ${{ number_format($stats['weekly_revenue']) }}
                    </p>
                </div>
            </div>

        </div>

        {{-- ===== ORDERS TABLE ===== --}}
        <div class="jaced-card" style="box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden;">

            {{-- Toolbar --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 px-3 px-md-4 py-3"
                style="border-bottom:1px solid var(--jaced-input);">
                <div class="d-flex align-items-center gap-2">
                    <input type="checkbox" id="selectAll"
                        style="width:16px; height:16px; accent-color:var(--jaced-sage); cursor:pointer;">
                    <label for="selectAll" class="mb-0" style="font-size:13px; font-weight:500; cursor:pointer;">
                        <span id="selectedCount">0</span> selected
                    </label>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2 gap-md-3">
                    <a href="#" style="font-size:13px; font-weight:500; color:var(--jaced-brown-dark); text-decoration:none; white-space:nowrap;">Mark Shipped</a>
                    <span class="d-none d-sm-inline" style="color:var(--jaced-input);">|</span>
                    <a href="#" style="font-size:13px; font-weight:500; color:var(--jaced-brown-dark); text-decoration:none; white-space:nowrap;">Download Invoice</a>
                    <span class="d-none d-sm-inline" style="color:var(--jaced-input);">|</span>
                    <a href="#" style="font-size:13px; font-weight:600; color:var(--jaced-caramel); text-decoration:none; white-space:nowrap;">Archive</a>
                </div>
            </div>

            {{-- Table Header — hidden on mobile, shown md+ --}}
            <div class="d-none d-md-block px-4 py-2" style="border-bottom:1px solid var(--jaced-input);">
                <div class="row align-items-center g-0"
                    style="font-size:11px; font-weight:600; letter-spacing:.7px; text-transform:uppercase; color:var(--jaced-muted);">
                    <div class="col-auto" style="width:40px;"></div>
                    <div class="col-2">Order ID</div>
                    <div class="col-3">Customer</div>
                    <div class="col-2">Order Date</div>
                    <div class="col-2">Status</div>
                    <div class="col-2 text-end">Amount</div>
                    <div class="col-1"></div>
                </div>
            </div>

            {{-- Table Rows --}}
            @foreach($orders as $order)
            @php
                $initials = collect(explode(' ', $order['customer_name']))
                    ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                    ->take(2)->implode('');
                $style = $statusStyles[strtolower($order['status'])] ?? 'background:#F5F5F5; color:#616161;';

                $logs = [
                    ['label' => 'Order Received',     'time' => $order['order_date'] . ' 09:10 AM', 'color' => '#4CAF50'],
                    ['label' => 'Payment Confirmed',  'time' => $order['order_date'] . ' 09:45 AM', 'color' => '#4CAF50'],
                    ['label' => 'Sent to Production', 'time' => $order['order_date'] . ' 11:00 AM', 'color' => '#FF9800'],
                    ['label' => 'Awaiting Dispatch',  'time' => 'Pending',                          'color' => '#BDBDBD'],
                ];
            @endphp

            <div class="order-row" style="border-bottom:1px solid var(--jaced-input);">

                {{-- ── Desktop Row (md+) ── --}}
                <div class="d-none d-md-block px-4 py-3 order-row-trigger"
                    style="cursor:pointer; transition:background .15s;"
                    onclick="toggleOrderPanel('panel-{{ $order['id'] }}','chev-{{ $order['id'] }}')">
                    <div class="row align-items-center g-0">
                        <div class="col-auto" style="width:40px;">
                            <input type="checkbox" class="order-checkbox"
                                style="width:16px; height:16px; accent-color:var(--jaced-sage); cursor:pointer;"
                                onclick="event.stopPropagation()">
                        </div>
                        <div class="col-2">
                            <span style="font-size:13px; font-weight:600; color:var(--jaced-brown-dark);">#ORD-{{ $order['id'] }}</span>
                        </div>
                        <div class="col-3 d-flex align-items-center gap-2">
                            <div style="width:34px; height:34px; border-radius:50%; background:{{ $order['avatar_color'] }};
                                display:flex; align-items:center; justify-content:center;
                                font-size:11px; font-weight:700; color:white; flex-shrink:0;">
                                {{ $initials }}
                            </div>
                            <div>
                                <p class="mb-0" style="font-size:13px; font-weight:600; color:var(--jaced-brown-dark);">{{ $order['customer_name'] }}</p>
                                <p class="mb-0 text-jaced-muted" style="font-size:11px;">{{ $order['customer_email'] }}</p>
                            </div>
                        </div>
                        <div class="col-2">
                            <span style="font-size:13px; color:var(--jaced-muted);">{{ $order['order_date'] }}</span>
                        </div>
                        <div class="col-2">
                            <span style="{{ $style }} font-size:11px; font-weight:700; padding:4px 10px; border-radius:99px; text-transform:uppercase; letter-spacing:.5px;">
                                {{ ucfirst($order['status']) }}
                            </span>
                        </div>
                        <div class="col-2 text-end">
                            <span style="font-size:13px; font-weight:600; color:var(--jaced-brown-dark);">${{ number_format($order['total_amount'], 2) }}</span>
                        </div>
                        <div class="col-1 text-end">
                            <span id="chev-{{ $order['id'] }}"
                                style="color:var(--jaced-muted); font-size:16px; display:inline-block; transition:transform .25s; line-height:1;">▾</span>
                        </div>
                    </div>
                </div>

                {{-- ── Mobile Row (< md) ── --}}
                <div class="d-flex d-md-none align-items-center gap-3 px-3 py-3 order-row-trigger"
                    style="cursor:pointer; transition:background .15s;"
                    onclick="toggleOrderPanel('panel-{{ $order['id'] }}','chev-mob-{{ $order['id'] }}')">
                    {{-- Checkbox --}}
                    <input type="checkbox" class="order-checkbox"
                        style="width:16px; height:16px; flex-shrink:0; accent-color:var(--jaced-sage); cursor:pointer;"
                        onclick="event.stopPropagation()">
                    {{-- Avatar --}}
                    <div style="width:38px; height:38px; border-radius:50%; background:{{ $order['avatar_color'] }};
                        display:flex; align-items:center; justify-content:center;
                        font-size:12px; font-weight:700; color:white; flex-shrink:0;">
                        {{ $initials }}
                    </div>
                    {{-- Name + ID --}}
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="mb-0" style="font-size:13px; font-weight:600; color:var(--jaced-brown-dark); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $order['customer_name'] }}
                        </p>
                        <p class="mb-0" style="font-size:11px; color:var(--jaced-muted);">#ORD-{{ $order['id'] }} · {{ $order['order_date'] }}</p>
                    </div>
                    {{-- Status + Amount stacked --}}
                    <div class="text-end flex-shrink-0">
                        <p class="mb-1">
                            <span style="{{ $style }} font-size:10px; font-weight:700; padding:3px 8px; border-radius:99px; text-transform:uppercase; letter-spacing:.5px;">
                                {{ ucfirst($order['status']) }}
                            </span>
                        </p>
                        <p class="mb-0" style="font-size:12px; font-weight:600; color:var(--jaced-brown-dark);">${{ number_format($order['total_amount'], 2) }}</p>
                    </div>
                    {{-- Chevron --}}
                    <span id="chev-mob-{{ $order['id'] }}"
                        style="color:var(--jaced-muted); font-size:16px; flex-shrink:0; transition:transform .25s; line-height:1;">▾</span>
                </div>

                {{-- ===== EXPAND PANEL ===== --}}
                <div id="panel-{{ $order['id'] }}"
                    style="display:none; background:#FDFBF8; border-top:1px solid var(--jaced-input);">
                    {{-- Responsive padding: tight on mobile, indented on md+ --}}
                    <div class="px-3 px-md-4 py-4" style="padding-left: clamp(1rem, 4vw, 4.25rem) !important;">
                        <div class="row g-4">

                            {{-- Payment & Recipient --}}
                            <div class="col-12 col-md-4">
                                <p style="font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:var(--jaced-sage); margin-bottom:12px;">Payment Method</p>
                                <p style="font-size:11px; color:var(--jaced-muted); margin-bottom:2px;">Transaction Type</p>
                                <p style="font-size:13px; font-weight:500; color:var(--jaced-brown-dark); margin-bottom:10px;">Bank Transfer</p>
                                <p style="font-size:11px; color:var(--jaced-muted); margin-bottom:2px;">Reference ID</p>
                                <p style="font-size:13px; font-weight:500; color:var(--jaced-brown-dark); margin-bottom:20px;">#TXN-{{ $order['id'] }}0042</p>

                                <p style="font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:var(--jaced-sage); margin-bottom:12px;">Recipient Profile</p>
                                <p style="font-size:11px; color:var(--jaced-muted); margin-bottom:2px;">Full Name</p>
                                <p style="font-size:13px; font-weight:500; color:var(--jaced-brown-dark); margin-bottom:10px;">{{ $order['customer_name'] }}</p>
                                <p style="font-size:11px; color:var(--jaced-muted); margin-bottom:2px;">Email</p>
                                <p style="font-size:13px; font-weight:500; color:var(--jaced-brown-dark); margin-bottom:0;">{{ $order['customer_email'] }}</p>
                            </div>

                            {{-- Log --}}
                            <div class="col-12 col-sm-6 col-md-4">
                                <p style="font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:var(--jaced-sage); margin-bottom:12px;">Log</p>
                                @foreach($logs as $log)
                                <div class="d-flex align-items-start gap-2 mb-3">
                                    <div style="width:8px; height:8px; border-radius:50%; background:{{ $log['color'] }}; margin-top:4px; flex-shrink:0;"></div>
                                    <div>
                                        <p style="font-size:12px; font-weight:600; color:var(--jaced-brown-dark); margin:0;">{{ $log['label'] }}</p>
                                        <p style="font-size:11px; color:var(--jaced-muted); margin:0;">{{ $log['time'] }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            {{-- Update Status --}}
                            <div class="col-12 col-sm-6 col-md-4">
                                <p style="font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:var(--jaced-sage); margin-bottom:12px;">Update Status</p>

                                <select id="status-select-{{ $order['id'] }}"
                                    style="width:100%; font-size:13px; border:1px solid var(--jaced-input); border-radius:8px; padding:9px 10px; color:var(--jaced-brown-dark); background:white; margin-bottom:10px; cursor:pointer;">
                                    @foreach(['processing' => 'Processing', 'in_production' => 'In Production', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'delayed' => 'Delayed', 'pending' => 'Pending'] as $val => $label)
                                    <option value="{{ $val }}" {{ $order['status'] === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>

                                <button onclick="saveOrderStatus('{{ $order['id'] }}')"
                                    style="width:100%; background:var(--jaced-brown-dark); color:white; border:none; border-radius:8px; padding:10px; font-size:13px; font-weight:600; cursor:pointer; margin-bottom:8px; transition:background .15s;"
                                    onmouseover="this.style.background='#4A3020'"
                                    onmouseout="this.style.background='var(--jaced-brown-dark)'">
                                    Save Changes
                                </button>

                                <div id="saved-msg-{{ $order['id'] }}"
                                    style="display:none; font-size:12px; color:#2E7D32; font-weight:600; margin-bottom:4px;">
                                    ✓ Status updated successfully
                                </div>

                                <div style="margin-top:16px; padding-top:16px; border-top:1px solid var(--jaced-input);">
                                    <p style="font-size:11px; color:var(--jaced-muted); margin-bottom:6px;">Internal Note</p>
                                    <textarea
                                        style="width:100%; font-size:12px; border:1px solid var(--jaced-input); border-radius:8px; padding:9px; color:var(--jaced-brown-dark); resize:none; height:64px; background:white;"
                                        placeholder="Add a note for this order..."></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                {{-- END EXPAND PANEL --}}

            </div>
            @endforeach

            {{-- Pagination --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 px-3 px-md-4 py-3">
                <span style="font-size:12px; color:var(--jaced-muted);">
                    Showing 1–{{ count($orders) }} of {{ $totalOrders }} orders
                </span>
                <div class="d-flex align-items-center gap-1">
                    <a href="#" class="jaced-page-btn">‹</a>
                    @for($p = 1; $p <= 3; $p++)
                    <a href="#" class="jaced-page-btn {{ $p === $currentPage ? 'active' : '' }}">{{ $p }}</a>
                    @endfor
                    <span style="color:var(--jaced-muted); font-size:13px; padding:0 4px;">…</span>
                    <a href="#" class="jaced-page-btn">{{ $totalPages }}</a>
                    <a href="#" class="jaced-page-btn">›</a>
                </div>
            </div>

        </div>
        {{-- END TABLE CARD --}}

    </div>
</div>

<style>
    .order-row-trigger:hover  { background-color: var(--jaced-caramel-bg) !important; }
    .order-row:last-child     { border-bottom: none !important; }

    /* Pagination buttons */
    .jaced-page-btn {
        width: 32px; height: 32px;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid var(--jaced-input);
        border-radius: 6px;
        color: var(--jaced-brown-dark);
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
    }
    .jaced-page-btn.active {
        background: var(--jaced-brown-dark);
        color: white;
        border-color: var(--jaced-brown-dark);
    }
    .jaced-page-btn:not(.active):hover {
        background: var(--jaced-caramel-bg);
    }
</style>

<script>
    // ===== Select All =====
    const selectAll = document.getElementById('selectAll');
    const count     = document.getElementById('selectedCount');

    function updateCount() {
        count.textContent = document.querySelectorAll('.order-checkbox:checked').length;
    }

    selectAll.addEventListener('change', function () {
        document.querySelectorAll('.order-checkbox').forEach(cb => cb.checked = this.checked);
        updateCount();
    });

    document.querySelectorAll('.order-checkbox').forEach(cb => cb.addEventListener('change', updateCount));

    // ===== Expand / Collapse =====
    function toggleOrderPanel(panelId, chevId) {
        const panel  = document.getElementById(panelId);
        const isOpen = panel.style.display !== 'none';

        panel.style.display = isOpen ? 'none' : 'block';

        // Rotate BOTH chevrons (desktop + mobile share the same panel)
        ['chev-', 'chev-mob-'].forEach(prefix => {
            const id = chevId.replace(/^chev(-mob)?-/, prefix);
            const el = document.getElementById(id);
            if (el) el.style.transform = isOpen ? '' : 'rotate(180deg)';
        });
    }

    // ===== Save Status =====
    function saveOrderStatus(orderId) {
        const msg = document.getElementById('saved-msg-' + orderId);
        msg.style.display = 'block';
        setTimeout(() => msg.style.display = 'none', 2500);
    }
</script>

@endsection