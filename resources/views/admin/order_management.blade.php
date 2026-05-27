@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">

<style>
    .panel-section-title {
        font-size: 11px; font-weight: 700; letter-spacing: 1px;
        text-transform: uppercase; color: var(--jaced-sage);
        margin-bottom: 10px;
    }
    .panel-label  { font-size: 11px; color: var(--jaced-muted); margin-bottom: 2px; }
    .panel-value  { font-size: 13px; font-weight: 500; color: var(--jaced-brown-dark); margin-bottom: 10px; }

    .order-row-trigger:hover { background-color: var(--jaced-caramel-bg) !important; }
    .order-row:last-child    { border-bottom: none !important; }

    .filter-bar {
        display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end;
        padding: 16px; border-bottom: 1px solid var(--jaced-input); background: #FDFBF8;
    }
    .filter-group { display: flex; flex-direction: column; gap: 4px; }
    .filter-label { font-size: 10px; font-weight: 600; letter-spacing: .7px; text-transform: uppercase; color: var(--jaced-muted); }
    .filter-input, .filter-select {
        font-size: 13px; border: 1px solid var(--jaced-input); border-radius: 8px;
        padding: 8px 12px; color: var(--jaced-brown-dark); background: white;
        outline: none; transition: border-color .15s; height: 38px;
    }
    .filter-input:focus, .filter-select:focus { border-color: var(--jaced-caramel); }
    .filter-input  { min-width: 200px; }
    .filter-select { min-width: 140px; }
    .btn-clear { background: white; color: var(--jaced-muted); border: 1px solid var(--jaced-input); border-radius: 8px; padding: 8px 14px; font-size: 13px; font-weight: 500; cursor: pointer; height: 38px; transition: background .15s; }
    .btn-clear:hover { background: var(--jaced-caramel-bg); }

    .status-modal-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.45); z-index: 9999;
        align-items: center; justify-content: center;
    }
    .status-modal-overlay.open { display: flex; }
    .status-modal {
        background: white; border-radius: 20px; padding: 32px;
        max-width: 400px; width: 90%;
        box-shadow: 0 20px 60px rgba(0,0,0,.2);
        animation: modalIn .2s ease;
    }
    @keyframes modalIn {
        from { opacity: 0; transform: translateY(16px) scale(.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .modal-status-arrow {
        display: flex; align-items: center; justify-content: center;
        gap: 16px; margin: 24px 0;
    }
    .modal-status-chip {
        padding: 8px 20px; border-radius: 99px;
        font-size: 13px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .5px;
    }
    .btn-confirm {
        width: 100%; padding: 13px;
        background: var(--jaced-brown-dark); color: white;
        border: none; border-radius: 12px;
        font-size: 14px; font-weight: 700;
        cursor: pointer; transition: background .15s; margin-bottom: 10px;
    }
    .btn-confirm:hover { background: #3D2B1A; }
    .btn-cancel-modal {
        width: 100%; padding: 11px; background: none;
        color: var(--jaced-muted); border: 1px solid var(--jaced-input);
        border-radius: 12px; font-size: 13px; font-weight: 500;
        cursor: pointer; transition: background .15s;
    }
    .btn-cancel-modal:hover { background: var(--jaced-caramel-bg); }

    .toast-msg {
        position: fixed; bottom: 24px; right: 24px;
        background: #1A1714; color: white;
        padding: 12px 20px; border-radius: 10px;
        font-size: 13px; font-weight: 500;
        box-shadow: 0 8px 24px rgba(0,0,0,.2);
        z-index: 99999; opacity: 0; transform: translateY(8px);
        transition: opacity .25s, transform .25s; pointer-events: none;
    }
    .toast-msg.show { opacity: 1; transform: translateY(0); }

    .pagination { display:flex; align-items:center; gap:4px; margin:0; padding:0; }
    .pagination .page-item .page-link {
        display:flex; align-items:center; justify-content:center;
        min-width:32px; height:32px; padding:0 8px;
        border:1px solid var(--jaced-input); border-radius:6px !important;
        font-size:13px; font-weight:500;
        color:var(--jaced-brown-dark); background:white; transition:background .15s;
    }
    .pagination .page-item.active .page-link { background:var(--jaced-brown-dark); border-color:var(--jaced-brown-dark); color:white; }
    .pagination .page-item.disabled .page-link { color:var(--jaced-muted); background:#f9f9f9; }
    .pagination .page-item:not(.active):not(.disabled) .page-link:hover { background:var(--jaced-caramel-bg); }
</style>

@php
use Carbon\Carbon;

$statusStyles = [
    'unpaid'     => ['bg' => '#FFF3E0', 'color' => '#E65100', 'label' => 'Unpaid'],
    'on_process' => ['bg' => '#E8EAF6', 'color' => '#283593', 'label' => 'On Process'],
    'packed'     => ['bg' => '#E3F2FD', 'color' => '#1565C0', 'label' => 'Packed'],
    'delivered'  => ['bg' => '#F3E5F5', 'color' => '#6A1B9A', 'label' => 'Delivered'],
    'shipped'    => ['bg' => '#E0F7FA', 'color' => '#00695C', 'label' => 'Shipped'],
    'arrived'    => ['bg' => '#E8F5E9', 'color' => '#2E7D32', 'label' => 'Arrived'],
    'cancelled'  => ['bg' => '#FFEBEE', 'color' => '#C62828', 'label' => 'Cancelled'],
];

$avatarColors = [
    '#5A6B5B','#C99A6B','#8A6D5A','#C0776A',
    '#7B68A0','#4A7B8A','#7A8A5B','#5A4D7A',
];

// Admin transitions only — unpaid is intentionally excluded
$transitions = [
    'on_process' => ['next' => 'packed',    'label' => 'Mark as Packed'],
    'packed'     => ['next' => 'delivered', 'label' => 'Mark as Delivered'],
    'delivered'  => ['next' => 'shipped',   'label' => 'Mark as Shipped'],
];

// Full timeline steps in order
$timelineSteps = [
    ['key' => 'unpaid',     'label' => 'Order Placed',          'col' => 'created_at'],
    ['key' => 'on_process', 'label' => 'Payment Confirmed',     'col' => 'on_process_at'],
    ['key' => 'packed',     'label' => 'Packed',                'col' => 'packed_at'],
    ['key' => 'delivered',  'label' => 'Handed to Courier',     'col' => 'delivered_at'],
    ['key' => 'shipped',    'label' => 'Arrived at Destination','col' => 'shipped_at'],
    ['key' => 'arrived',    'label' => 'Arrived',               'col' => 'arrived_at'],
];

$statusOrder = ['unpaid', 'on_process', 'packed', 'delivered', 'shipped', 'arrived'];
@endphp

<div class="container-fluid">

    {{-- ── Page Header ── --}}
    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-4">
        <div>
            <h1 class="font-serif-jaced text-jaced-dark mb-1"
                style="font-size:clamp(1.4rem,4vw,1.9rem); font-weight:700; letter-spacing:-0.5px;">
                Order Management
            </h1>
            <p style="font-size:12px; color:var(--jaced-muted); margin:0;">
                Manage and track all customer orders
            </p>
        </div>
    </div>

    {{-- ── Stat Cards ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="jaced-card p-3 p-md-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div style="background:var(--jaced-caramel-bg); border-radius:8px; padding:8px;">
                        <i class="bi bi-clipboard-check" style="font-size:18px; color:var(--jaced-caramel);"></i>
                    </div>
                    <span style="background:#FFF3E0; color:#E65100; font-size:11px; font-weight:600; padding:3px 8px; border-radius:99px;">Unpaid</span>
                </div>
                <p class="text-jaced-muted mb-1" style="font-size:11px; font-weight:600; letter-spacing:.8px; text-transform:uppercase;">Awaiting Payment</p>
                <p class="text-jaced-dark mb-0" style="font-size:2rem; font-weight:700; line-height:1;">{{ $stats['unpaid'] }}</p>
            </div>
        </div>

        <div class="col-6 col-md-4">
            <div class="jaced-card p-3 p-md-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div style="background:#E3F2FD; border-radius:8px; padding:8px;">
                        <i class="bi bi-truck" style="font-size:18px; color:#1565C0;"></i>
                    </div>
                    <span style="background:#E3F2FD; color:#1565C0; font-size:11px; font-weight:600; padding:3px 8px; border-radius:99px;">Active</span>
                </div>
                <p class="text-jaced-muted mb-1" style="font-size:11px; font-weight:600; letter-spacing:.8px; text-transform:uppercase;">Out for Delivery</p>
                <p class="text-jaced-dark mb-0" style="font-size:2rem; font-weight:700; line-height:1;">{{ $stats['delivered'] }}</p>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="p-3 p-md-4 h-100"
                style="background:var(--jaced-brown-dark); border-radius:12px; box-shadow:0 4px 16px rgba(0,0,0,.15);">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div style="background:rgba(255,255,255,.1); border-radius:8px; padding:8px;">
                        <i class="bi bi-credit-card" style="font-size:18px; color:white;"></i>
                    </div>
                    <span style="background:rgba(255,255,255,.15); color:white; font-size:11px; font-weight:600; padding:3px 8px; border-radius:99px;">This Week</span>
                </div>
                <p style="color:rgba(255,255,255,.6); font-size:11px; font-weight:600; letter-spacing:.8px; text-transform:uppercase; margin-bottom:4px;">Weekly Revenue</p>
                <p style="color:white; font-size:clamp(1.1rem,3vw,1.6rem); font-weight:700; line-height:1; margin:0;">
                    Rp {{ number_format($stats['weekly_revenue'], 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    {{-- ── Orders Table Card ── --}}
    <div class="jaced-card" style="overflow:hidden;">

        {{-- Filter Bar — no Apply button, all filters auto-refresh --}}
        <div class="filter-bar">
            <div class="filter-group" style="flex:1; min-width:180px;">
                <span class="filter-label">Search</span>
                <input type="text" id="searchInput" class="filter-input"
                    placeholder="Customer name or order ID...">
            </div>
            <div class="filter-group">
                <span class="filter-label">Status</span>
                <select id="filterStatus" class="filter-select" onchange="fetchOrders(1)">
                    <option value="all">All Status</option>
                    <option value="unpaid">Unpaid</option>
                    <option value="on_process">On Process</option>
                    <option value="packed">Packed</option>
                    <option value="delivered">Delivered</option>
                    <option value="shipped">Shipped</option>
                    <option value="arrived">Arrived</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="filter-group">
                <span class="filter-label">Payment</span>
                <select id="filterPayment" class="filter-select" onchange="fetchOrders(1)">
                    <option value="all">All Methods</option>
                    <option value="qris">QRIS</option>
                    <option value="virtual_account">Virtual Account</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="ovo">OVO</option>
                    <option value="dana">DANA</option>
                </select>
            </div>
            <div class="filter-group">
                <span class="filter-label">From</span>
                <input type="date" id="filterDateFrom" class="filter-input" style="min-width:unset; width:145px;" onchange="fetchOrders(1)">
            </div>
            <div class="filter-group">
                <span class="filter-label">To</span>
                <input type="date" id="filterDateTo" class="filter-input" style="min-width:unset; width:145px;" onchange="fetchOrders(1)">
            </div>
            <button class="btn-clear" onclick="clearFilters()">Clear</button>
        </div>

        {{-- Table Header (desktop) --}}
        <div class="d-none d-md-block px-4 py-2" style="border-bottom:1px solid var(--jaced-input);">
            <div style="display:flex; align-items:center; font-size:11px; font-weight:600; letter-spacing:.7px; text-transform:uppercase; color:var(--jaced-muted);">
                <div style="width:40px; flex-shrink:0;"></div>
                <div style="flex:0 0 12%;">Order ID</div>
                <div style="flex:0 0 25%;">Customer</div>
                <div style="flex:0 0 15%;">Date</div>
                <div style="flex:0 0 15%;">Status</div>
                <div style="flex:0 0 15%;">Payment</div>
                <div style="flex:1; text-align:right;">Amount</div>
                <div style="width:32px;"></div>
            </div>
        </div>

        {{-- ── Order Rows ── --}}
        <div id="orderTableBody">
        @forelse($orders as $order)
        @php
            $st       = $statusStyles[$order->status] ?? ['bg'=>'#F5F5F5','color'=>'#616161','label'=>ucfirst($order->status)];
            $initials = collect(explode(' ', $order->customer_name))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->implode('');
            $avatarBg = $avatarColors[$order->id % count($avatarColors)];
            $trans    = $transitions[$order->status] ?? null;

            $details = DB::table('order_details')
                ->join('products','order_details.product_id','=','products.id')
                ->where('order_details.order_id', $order->id)
                ->select('products.name','order_details.quantity','order_details.subtotal')
                ->get();

            $currentIdx = array_search($order->status, $statusOrder);
            if ($order->status === 'cancelled') $currentIdx = -1;
        @endphp

        <div class="order-row" style="border-bottom:1px solid var(--jaced-input);">

            {{-- Desktop Row --}}
            <div class="d-none d-md-flex align-items-center px-4 py-3 order-row-trigger"
                style="cursor:pointer; transition:background .15s; gap:0;"
                onclick="togglePanel({{ $order->id }})">
                <div style="width:40px; flex-shrink:0;">
                    <input type="checkbox" class="order-checkbox"
                        style="width:16px; height:16px; accent-color:var(--jaced-sage); cursor:pointer;"
                        onclick="event.stopPropagation()">
                </div>
                <div style="flex:0 0 12%; font-size:13px; font-weight:600; color:var(--jaced-brown-dark);">
                    #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                </div>
                <div style="flex:0 0 25%; display:flex; align-items:center; gap:10px;">
                    <div style="width:34px; height:34px; border-radius:50%; background:{{ $avatarBg }};
                        display:flex; align-items:center; justify-content:center;
                        font-size:11px; font-weight:700; color:white; flex-shrink:0;">
                        {{ $initials }}
                    </div>
                    <div style="min-width:0;">
                        <p class="mb-0" style="font-size:13px; font-weight:600; color:var(--jaced-brown-dark); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $order->customer_name }}</p>
                        <p class="mb-0" style="font-size:11px; color:var(--jaced-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $order->customer_email }}</p>
                    </div>
                </div>
                <div style="flex:0 0 15%; font-size:13px; color:var(--jaced-muted);">
                    {{ Carbon::parse($order->created_at)->format('d M Y') }}
                </div>
                <div style="flex:0 0 15%;">
                    <span style="background:{{ $st['bg'] }}; color:{{ $st['color'] }}; font-size:11px; font-weight:700; padding:4px 10px; border-radius:99px; text-transform:uppercase; letter-spacing:.5px;">
                        {{ $st['label'] }}
                    </span>
                </div>
                <div style="flex:0 0 15%; font-size:13px; color:var(--jaced-muted); text-transform:capitalize;">
                    {{ str_replace('_',' ', $order->payment_method) }}
                </div>
                <div style="flex:1; text-align:right; font-size:13px; font-weight:600; color:var(--jaced-brown-dark);">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </div>
                <div style="width:32px; text-align:right; flex-shrink:0;">
                    <span id="chev-{{ $order->id }}" style="color:var(--jaced-muted); font-size:16px; display:inline-block; transition:transform .25s;">▾</span>
                </div>
            </div>

            {{-- Mobile Row --}}
            <div class="d-flex d-md-none align-items-center gap-3 px-3 py-3 order-row-trigger"
                style="cursor:pointer; transition:background .15s;"
                onclick="togglePanel({{ $order->id }})">
                <div style="width:38px; height:38px; border-radius:50%; background:{{ $avatarBg }};
                    display:flex; align-items:center; justify-content:center;
                    font-size:12px; font-weight:700; color:white; flex-shrink:0;">
                    {{ $initials }}
                </div>
                <div class="flex-grow-1" style="min-width:0;">
                    <p class="mb-0" style="font-size:13px; font-weight:600; color:var(--jaced-brown-dark);">{{ $order->customer_name }}</p>
                    <p class="mb-0" style="font-size:11px; color:var(--jaced-muted);">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }} · {{ Carbon::parse($order->created_at)->format('d M Y') }}</p>
                </div>
                <div class="text-end flex-shrink-0">
                    <p class="mb-1">
                        <span style="background:{{ $st['bg'] }}; color:{{ $st['color'] }}; font-size:10px; font-weight:700; padding:3px 8px; border-radius:99px; text-transform:uppercase;">{{ $st['label'] }}</span>
                    </p>
                    <p class="mb-0" style="font-size:12px; font-weight:600; color:var(--jaced-brown-dark);">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
                <span id="chev-mob-{{ $order->id }}" style="color:var(--jaced-muted); font-size:16px; flex-shrink:0; transition:transform .25s;">▾</span>
            </div>

            {{-- ── Expand Panel ── --}}
            <div id="panel-{{ $order->id }}" style="display:none; background:#FDFBF8; border-top:1px solid var(--jaced-input);">
                <div class="px-3 px-md-4 py-4">

                    {{-- Unpaid: show payment pending message only --}}
                    @if($order->status === 'unpaid')
                    <div style="text-align:center; padding:24px 0;">
                        <div style="width:56px; height:56px; border-radius:50%; background:#FFF3E0; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                            <i class="bi bi-clock" style="font-size:24px; color:#E65100;"></i>
                        </div>
                        <p style="font-size:15px; font-weight:700; color:var(--jaced-brown-dark); margin:0 0 6px;">Waiting for Payment</p>
                        <p style="font-size:13px; color:var(--jaced-muted); margin:0 0 4px;">This order has not been paid yet by the customer.</p>
                        <p style="font-size:12px; color:var(--jaced-muted); margin:0;">
                            Order placed: {{ Carbon::parse($order->created_at)->format('d M Y, H:i') }}
                            · Auto-cancels after 24 hours.
                        </p>
                    </div>

                    @else
                    <div class="row g-4">

                        {{-- Col 1: Customer + Payment + Address --}}
                        <div class="col-12 col-md-4">
                            <p class="panel-section-title">Customer</p>
                            <p class="panel-label">Full Name</p>   <p class="panel-value">{{ $order->customer_name }}</p>
                            <p class="panel-label">Email</p>       <p class="panel-value">{{ $order->customer_email }}</p>
                            <p class="panel-label">Phone</p>       <p class="panel-value">{{ $order->customer_phone }}</p>

                            <p class="panel-section-title mt-2">Payment</p>
                            <p class="panel-label">Method</p>
                            <p class="panel-value" style="text-transform:capitalize;">{{ str_replace('_',' ', $order->payment_method) }}</p>

                            <p class="panel-section-title mt-2">Shipping Address</p>
                            <p class="panel-label">Receiver</p>
                            <p class="panel-value">{{ $order->receiver_name }} · {{ $order->receiver_phone }}</p>
                            <p class="panel-label">Address</p>
                            <p class="panel-value">{{ $order->address_line1 }}, {{ $order->city_name }}, {{ $order->province_name }} {{ $order->postal_code }}</p>
                        </div>

                        {{-- Col 2: Items + Pricing --}}
                        <div class="col-12 col-md-4">
                            <p class="panel-section-title">Order Items</p>
                            @foreach($details as $item)
                            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:10px; padding-bottom:10px; border-bottom:1px solid var(--jaced-input);">
                                <div>
                                    <p class="mb-0" style="font-size:13px; font-weight:600; color:var(--jaced-brown-dark);">{{ $item->name }}</p>
                                    <p class="mb-0" style="font-size:11px; color:var(--jaced-muted);">Qty: {{ $item->quantity }}</p>
                                </div>
                                <span style="font-size:13px; font-weight:600; color:var(--jaced-brown-dark);">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                            @endforeach

                            <div style="margin-top:8px;">
                                <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                                    <span style="font-size:12px; color:var(--jaced-muted);">Delivery Fee</span>
                                    <span style="font-size:12px;">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
                                </div>
                                <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                                    <span style="font-size:12px; color:var(--jaced-muted);">Admin Fee</span>
                                    <span style="font-size:12px;">Rp {{ number_format($order->service_tax, 0, ',', '.') }}</span>
                                </div>
                                @if($order->discount_amount > 0)
                                <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                                    <span style="font-size:12px; color:var(--jaced-muted);">Discount</span>
                                    <span style="font-size:12px; color:#2E7D32;">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                <div style="display:flex; justify-content:space-between; margin-top:10px; padding-top:10px; border-top:2px solid var(--jaced-input);">
                                    <span style="font-size:13px; font-weight:700; color:var(--jaced-brown-dark);">Total</span>
                                    <span style="font-size:13px; font-weight:700; color:var(--jaced-brown-dark);">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Col 3: Timeline + Action --}}
                        <div class="col-12 col-md-4">
                            <p class="panel-section-title">Status Timeline</p>
                            <div style="position:relative; padding-left:20px;">
                                @foreach($timelineSteps as $i => $step)
                                @php
                                    $stepIdx   = array_search($step['key'], $statusOrder);
                                    $isDone    = $currentIdx !== -1 && $currentIdx >= $stepIdx;
                                    $isCurrent = $currentIdx === $stepIdx;
                                    $dotColor  = $isDone ? '#B87333' : '#DDD8CF';
                                    $lineColor = $isDone ? '#B87333' : '#DDD8CF';
                                    $timeVal   = $order->{$step['col']} ?? null;
                                @endphp
                                <div style="position:relative; {{ $i < count($timelineSteps)-1 ? 'padding-bottom:18px;' : '' }}">
                                    @if($i < count($timelineSteps) - 1)
                                    <div style="position:absolute; left:-12px; top:10px; width:2px; height:100%; background:{{ $lineColor }};"></div>
                                    @endif
                                    <div style="position:absolute; left:-16px; top:4px; width:8px; height:8px; border-radius:50%; background:{{ $dotColor }};
                                        {{ $isCurrent ? 'box-shadow:0 0 0 3px rgba(184,115,51,0.2);' : '' }}"></div>
                                    <div>
                                        <p style="font-size:12px; font-weight:{{ $isDone ? '600' : '400' }}; color:{{ $isDone ? 'var(--jaced-brown-dark)' : 'var(--jaced-muted)' }}; margin:0;">{{ $step['label'] }}</p>
                                        <p style="font-size:11px; color:var(--jaced-muted); margin:0;">
                                            {{ $timeVal ? Carbon::parse($timeVal)->format('d M Y, H:i') : '—' }}
                                        </p>
                                    </div>
                                </div>
                                @endforeach

                                @if($order->status === 'cancelled')
                                <div style="position:relative; padding-top:10px;">
                                    <div style="position:absolute; left:-16px; top:14px; width:8px; height:8px; border-radius:50%; background:#C62828;"></div>
                                    <p style="font-size:12px; font-weight:600; color:#C62828; margin:0;">Cancelled</p>
                                    <p style="font-size:11px; color:var(--jaced-muted); margin:0;">
                                        {{ $order->cancelled_at ? Carbon::parse($order->cancelled_at)->format('d M Y, H:i') : '—' }}
                                    </p>
                                </div>
                                @endif
                            </div>

                            {{-- Action area --}}
                            <div style="margin-top:24px;">
                                @if($trans)
                                <button onclick="openStatusModal({{ $order->id }}, '{{ $order->status }}', '{{ $trans['next'] }}', '{{ $trans['label'] }}')"
                                    style="width:100%; background:var(--jaced-brown-dark); color:white; border:none;
                                        border-radius:10px; padding:11px 16px; font-size:13px; font-weight:600;
                                        cursor:pointer; transition:background .15s; display:flex; align-items:center; justify-content:center; gap:8px;"
                                    onmouseover="this.style.background='#3D2B1A'"
                                    onmouseout="this.style.background='var(--jaced-brown-dark)'">
                                    <i class="bi bi-arrow-up-circle"></i> {{ $trans['label'] }}
                                </button>

                                @elseif($order->status === 'shipped')
                                <div style="background:#E0F7FA; border-radius:10px; padding:12px 14px;">
                                    <p style="font-size:12px; color:#00695C; font-weight:600; margin:0 0 2px;">
                                        <i class="bi bi-clock-history"></i> Waiting for Customer Confirmation
                                    </p>
                                    <p style="font-size:11px; color:var(--jaced-muted); margin:0;">
                                        Customer needs to confirm arrival.
                                        Auto-arrives {{ $order->shipped_at ? Carbon::parse($order->shipped_at)->addDays(7)->format('d M Y') : 'after 7 days' }}.
                                    </p>
                                </div>

                                @elseif($order->status === 'arrived')
                                <div style="background:#E8F5E9; border-radius:10px; padding:12px 14px;">
                                    <p style="font-size:12px; color:#2E7D32; font-weight:600; margin:0;">
                                        <i class="bi bi-check-circle"></i> Order Completed
                                    </p>
                                </div>

                                @elseif($order->status === 'cancelled')
                                <div style="background:#FFEBEE; border-radius:10px; padding:12px 14px;">
                                    <p style="font-size:12px; color:#C62828; font-weight:600; margin:0 0 4px;">
                                        <i class="bi bi-x-circle"></i> Order Cancelled
                                    </p>
                                    @if($order->cancellation_reason)
                                    <p style="font-size:11px; color:var(--jaced-muted); margin:0;">
                                        {{ $order->cancellation_reason }}
                                    </p>
                                    @endif
                                </div>
                                @endif
                            </div>

                        </div>
                    </div>
                    @endif

                </div>
            </div>

        </div>
        @empty
        <div style="padding:48px; text-align:center; color:var(--jaced-muted); font-size:14px;">No orders found.</div>
        @endforelse
        </div>

        {{-- Pagination --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 px-3 px-md-4 py-3"
            style="border-top:1px solid var(--jaced-input);">
            <span id="paginationInfo" style="font-size:12px; color:var(--jaced-muted);">
                @if($orders->total() > 0)
                    Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }} orders
                @else
                    No orders found
                @endif
            </span>
            <div id="paginationLinksContainer">
                {{ $orders->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>

    </div>

</div>

{{-- ── Status Modal ── --}}
<div class="status-modal-overlay" id="statusModalOverlay">
    <div class="status-modal">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:4px;">
            <h5 style="font-size:17px; font-weight:700; color:var(--jaced-brown-dark); margin:0;">Update Order Status</h5>
            <button onclick="closeStatusModal()" style="background:none; border:none; font-size:22px; color:var(--jaced-muted); cursor:pointer; line-height:1;">×</button>
        </div>
        <p id="modalOrderId" style="font-size:12px; color:var(--jaced-muted); margin:0;"></p>

        <div class="modal-status-arrow">
            <div class="text-center">
                <p style="font-size:10px; color:var(--jaced-muted); margin-bottom:6px; letter-spacing:.5px; text-transform:uppercase;">Current</p>
                <span class="modal-status-chip" id="modalCurrentChip"></span>
            </div>
            <i class="bi bi-arrow-right" style="font-size:22px; color:var(--jaced-caramel);"></i>
            <div class="text-center">
                <p style="font-size:10px; color:var(--jaced-muted); margin-bottom:6px; letter-spacing:.5px; text-transform:uppercase;">New</p>
                <span class="modal-status-chip" id="modalNextChip"></span>
            </div>
        </div>

        <p style="font-size:12px; color:var(--jaced-muted); text-align:center; margin-bottom:20px;">
            This action cannot be undone. The order status will be permanently updated.
        </p>

        <button class="btn-confirm" id="modalConfirmBtn" onclick="confirmStatusUpdate()">
            <span id="modalConfirmLabel"></span>
        </button>
        <button class="btn-cancel-modal" onclick="closeStatusModal()">Cancel</button>
    </div>
</div>

{{-- ── Toast ── --}}
<div class="toast-msg" id="toastMsg"></div>

@push('scripts')
<script>
    let pendingOrderId    = null;
    let pendingNextStatus = null;
    let searchTimer       = null;

    const statusColors = {
        unpaid:     { bg: '#FFF3E0', color: '#E65100' },
        on_process: { bg: '#E8EAF6', color: '#283593' },
        packed:     { bg: '#E3F2FD', color: '#1565C0' },
        delivered:  { bg: '#F3E5F5', color: '#6A1B9A' },
        shipped:    { bg: '#E0F7FA', color: '#00695C' },
        arrived:    { bg: '#E8F5E9', color: '#2E7D32' },
        cancelled:  { bg: '#FFEBEE', color: '#C62828' },
    };

    // ── Live search (debounced) ───────────────────────────────────────
    document.getElementById('searchInput').addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => fetchOrders(1), 400);
    });

    // ── Intercept pagination clicks ───────────────────────────────────
    document.addEventListener('click', function (e) {
        const pageLink = e.target.closest('.pagination .page-link');
        if (pageLink) {
            e.preventDefault();
            const urlString = pageLink.getAttribute('href');
            if (urlString) {
                try {
                    const url  = new URL(urlString, window.location.origin);
                    const page = url.searchParams.get('page');
                    if (page) fetchOrders(page);
                } catch (err) {
                    console.error('Pagination error:', err);
                }
            }
        }
    });

    // ── Clear filters ─────────────────────────────────────────────────
    function clearFilters() {
        ['searchInput','filterDateFrom','filterDateTo'].forEach(id => document.getElementById(id).value = '');
        ['filterStatus','filterPayment'].forEach(id => document.getElementById(id).value = 'all');
        fetchOrders(1);
    }

    // ── AJAX fetch orders ─────────────────────────────────────────────
    function fetchOrders(page = 1) {
        const params = new URLSearchParams({
            search:    document.getElementById('searchInput').value,
            status:    document.getElementById('filterStatus').value,
            payment:   document.getElementById('filterPayment').value,
            date_from: document.getElementById('filterDateFrom').value,
            date_to:   document.getElementById('filterDateTo').value,
            page,
        });

        fetch(`{{ route('admin.order_management.search') }}?${params}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('orderTableBody').innerHTML = data.html;
            if (data.pagination) {
                document.getElementById('paginationLinksContainer').innerHTML = data.pagination;
            }
            const info = document.getElementById('paginationInfo');
            info.textContent = data.total > 0
                ? `Showing ${data.from}–${data.to} of ${data.total} orders`
                : 'No orders found';
        });
    }

    // ── Toggle panel — only one open at a time ────────────────────────
    function togglePanel(id) {
        const panel   = document.getElementById('panel-' + id);
        const chev    = document.getElementById('chev-' + id);
        const chevMob = document.getElementById('chev-mob-' + id);
        const isOpen  = panel.style.display !== 'none';

        // Close all other open panels first
        document.querySelectorAll('[id^="panel-"]').forEach(p => {
            if (p.id !== 'panel-' + id && p.style.display !== 'none') {
                const otherId = p.id.replace('panel-', '');
                p.style.display = 'none';
                const oc  = document.getElementById('chev-' + otherId);
                const ocm = document.getElementById('chev-mob-' + otherId);
                if (oc)  oc.style.transform  = '';
                if (ocm) ocm.style.transform = '';
            }
        });

        // Toggle the clicked panel
        panel.style.display = isOpen ? 'none' : 'block';
        if (chev)    chev.style.transform    = isOpen ? '' : 'rotate(180deg)';
        if (chevMob) chevMob.style.transform = isOpen ? '' : 'rotate(180deg)';
    }

    // ── Status modal ──────────────────────────────────────────────────
    function openStatusModal(orderId, currentStatus, nextStatus, label) {
        pendingOrderId    = orderId;
        pendingNextStatus = nextStatus;

        const curr = statusColors[currentStatus] || { bg:'#F5F5F5', color:'#616161' };
        const next = statusColors[nextStatus]    || { bg:'#F5F5F5', color:'#616161' };

        document.getElementById('modalOrderId').textContent = '#ORD-' + String(orderId).padStart(4, '0');

        const cc = document.getElementById('modalCurrentChip');
        cc.textContent      = currentStatus.replace('_',' ').replace(/\b\w/g, c => c.toUpperCase());
        cc.style.background = curr.bg;
        cc.style.color      = curr.color;

        const nc = document.getElementById('modalNextChip');
        nc.textContent      = nextStatus.replace('_',' ').replace(/\b\w/g, c => c.toUpperCase());
        nc.style.background = next.bg;
        nc.style.color      = next.color;

        document.getElementById('modalConfirmLabel').textContent = label;
        document.getElementById('statusModalOverlay').classList.add('open');
    }

    function closeStatusModal() {
        document.getElementById('statusModalOverlay').classList.remove('open');
        pendingOrderId = null; pendingNextStatus = null;
    }

    document.getElementById('statusModalOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeStatusModal();
    });

    function confirmStatusUpdate() {
        if (!pendingOrderId) return;
        const btn = document.getElementById('modalConfirmBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Updating...';

        fetch(`{{ url('admin/orders') }}/${pendingOrderId}/status`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        })
        .then(r => r.json())
        .then(data => {
            closeStatusModal();
            btn.disabled = false;
            btn.innerHTML = '<span id="modalConfirmLabel"></span>';
            if (data.success) {
                showToast('✓ ' + data.message);
                fetchOrders(1);
            } else {
                showToast('⚠ ' + (data.error || 'Something went wrong.'));
            }
        })
        .catch(() => {
            btn.disabled = false;
            showToast('Network error. Please try again.');
        });
    }

    function showToast(msg) {
        const t = document.getElementById('toastMsg');
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3000);
    }
</script>
@endpush

@endsection