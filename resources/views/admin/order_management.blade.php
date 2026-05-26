@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">

<style>
    /* ── Panel helpers ─────────────────────────────────── */
    .panel-section-title {
        font-size: 11px; font-weight: 700; letter-spacing: 1px;
        text-transform: uppercase; color: var(--jaced-sage);
        margin-bottom: 10px;
    }
    .panel-label  { font-size: 11px; color: var(--jaced-muted); margin-bottom: 2px; }
    .panel-value  { font-size: 13px; font-weight: 500; color: var(--jaced-brown-dark); margin-bottom: 10px; }

    /* ── Row hover ─────────────────────────────────────── */
    .order-row-trigger:hover { background-color: var(--jaced-caramel-bg) !important; }
    .order-row:last-child    { border-bottom: none !important; }

    /* ── Filter bar ────────────────────────────────────── */
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
    .btn-apply { background: var(--jaced-brown-dark); color: white; border: none; border-radius: 8px; padding: 8px 18px; font-size: 13px; font-weight: 600; cursor: pointer; height: 38px; transition: background .15s; }
    .btn-apply:hover { background: #3D2B1A; }
    .btn-clear { background: white; color: var(--jaced-muted); border: 1px solid var(--jaced-input); border-radius: 8px; padding: 8px 14px; font-size: 13px; font-weight: 500; cursor: pointer; height: 38px; transition: background .15s; }
    .btn-clear:hover { background: var(--jaced-caramel-bg); }

    /* ── Laravel pagination override ──────────────────── */
    .jaced-pagination { display: flex; align-items: center; gap: 4px; list-style: none; margin: 0; padding: 0; }
    .jaced-pagination li span,
    .jaced-pagination li a {
        display: flex; align-items: center; justify-content: center;
        min-width: 32px; height: 32px; padding: 0 8px;
        border: 1px solid var(--jaced-input); border-radius: 6px;
        font-size: 13px; font-weight: 500; text-decoration: none;
        color: var(--jaced-brown-dark); transition: background .15s;
    }
    .jaced-pagination li a:hover { background: var(--jaced-caramel-bg); }
    .jaced-pagination li.active span { background: var(--jaced-brown-dark); color: white; border-color: var(--jaced-brown-dark); }
    .jaced-pagination li.disabled span { color: var(--jaced-muted); background: #f9f9f9; cursor: not-allowed; }

    /* ── Status modal ──────────────────────────────────── */
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
        to   { opacity: 1; transform: translateY(0)    scale(1);   }
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

    /* ── Toast ─────────────────────────────────────────── */
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
</style>

@php
use Carbon\Carbon;

$statusStyles = [
    'unpaid'    => ['bg' => '#FFF3E0', 'color' => '#E65100', 'label' => 'Unpaid'],
    'packed'    => ['bg' => '#E3F2FD', 'color' => '#1565C0', 'label' => 'Packed'],
    'delivered' => ['bg' => '#F3E5F5', 'color' => '#6A1B9A', 'label' => 'Delivered'],
    'arrived'   => ['bg' => '#E8F5E9', 'color' => '#2E7D32', 'label' => 'Arrived'],
    'cancelled' => ['bg' => '#FFEBEE', 'color' => '#C62828', 'label' => 'Cancelled'],
];

$avatarColors = [
    '#5A6B5B','#C99A6B','#8A6D5A','#C0776A',
    '#7B68A0','#4A7B8A','#7A8A5B','#5A4D7A',
];

$transitions = [
    'unpaid' => ['next' => 'packed',    'label' => 'Mark as Packed'],
    'packed' => ['next' => 'delivered', 'label' => 'Mark as Delivered'],
];
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

        {{-- Filter Bar --}}
        <div class="filter-bar">
            <div class="filter-group" style="flex:1; min-width:180px;">
                <span class="filter-label">Search</span>
                <input type="text" id="searchInput" class="filter-input"
                    placeholder="Customer name or order ID...">
            </div>
            <div class="filter-group">
                <span class="filter-label">Status</span>
                <select id="filterStatus" class="filter-select">
                    <option value="all">All Status</option>
                    <option value="unpaid">Unpaid</option>
                    <option value="packed">Packed</option>
                    <option value="delivered">Delivered</option>
                    <option value="arrived">Arrived</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="filter-group">
                <span class="filter-label">Payment</span>
                <select id="filterPayment" class="filter-select">
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
                <input type="date" id="filterDateFrom" class="filter-input" style="min-width:unset; width:145px;">
            </div>
            <div class="filter-group">
                <span class="filter-label">To</span>
                <input type="date" id="filterDateTo" class="filter-input" style="min-width:unset; width:145px;">
            </div>
            <button class="btn-apply" onclick="applyFilters()">
                <i class="bi bi-funnel"></i> Apply
            </button>
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

            $statusOrder = ['unpaid','packed','delivered','arrived'];
            $currentIdx  = array_search($order->status, $statusOrder);
            if ($order->status === 'cancelled') $currentIdx = -1;

            $timeline = [
                ['label'=>'Order Placed',     'time'=>$order->created_at],
                ['label'=>'Packed',           'time'=>$order->packed_at],
                ['label'=>'Out for Delivery', 'time'=>$order->delivered_at],
                ['label'=>'Arrived',          'time'=>$order->arrived_at],
            ];
        @endphp

        <div class="order-row" style="border-bottom:1px solid var(--jaced-input);">

            {{-- Desktop Row --}}
            <div class="d-none d-md-flex align-items-center px-4 py-3 order-row-trigger"
                style="cursor:pointer; transition:background .15s; gap:0;"
                onclick="togglePanel({{ $order->id }})">
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
                <input type="checkbox" class="order-checkbox"
                    style="width:16px; height:16px; flex-shrink:0; accent-color:var(--jaced-sage); cursor:pointer;"
                    onclick="event.stopPropagation()">
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
                                    <span style="font-size:12px; color:var(--jaced-muted);">Admin Fee (0.5%)</span>
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

                        {{-- Col 3: Timeline + Update --}}
                        <div class="col-12 col-md-4">
                            <p class="panel-section-title">Status Timeline</p>
                            <div style="position:relative; padding-left:20px;">
                                @foreach($timeline as $i => $step)
                                @php
                                    $isDone    = $currentIdx >= $i && $currentIdx !== -1;
                                    $isCurrent = $currentIdx === $i;
                                    $dotColor  = $isDone ? '#B87333' : '#DDD8CF';
                                    $lineColor = $isDone ? '#B87333' : '#DDD8CF';
                                @endphp
                                <div style="position:relative; {{ $i < count($timeline)-1 ? 'padding-bottom:20px;' : '' }}">
                                    @if($i < count($timeline) - 1)
                                    <div style="position:absolute; left:-12px; top:10px; width:2px; height:100%; background:{{ $lineColor }};"></div>
                                    @endif
                                    <div style="position:absolute; left:-16px; top:4px; width:8px; height:8px; border-radius:50%; background:{{ $dotColor }};
                                        {{ $isCurrent ? 'box-shadow:0 0 0 3px rgba(184,115,51,0.2);' : '' }}"></div>
                                    <div>
                                        <p style="font-size:12px; font-weight:{{ $isDone ? '600' : '400' }}; color:{{ $isDone ? 'var(--jaced-brown-dark)' : 'var(--jaced-muted)' }}; margin:0;">{{ $step['label'] }}</p>
                                        <p style="font-size:11px; color:var(--jaced-muted); margin:0;">
                                            {{ $step['time'] ? Carbon::parse($step['time'])->format('d M Y, H:i') : '—' }}
                                        </p>
                                    </div>
                                </div>
                                @endforeach

                                @if($order->status === 'cancelled')
                                <div style="position:relative; padding-top:8px;">
                                    <div style="position:absolute; left:-16px; top:12px; width:8px; height:8px; border-radius:50%; background:#C62828;"></div>
                                    <p style="font-size:12px; font-weight:600; color:#C62828; margin:0;">Cancelled</p>
                                    <p style="font-size:11px; color:var(--jaced-muted); margin:0;">
                                        {{ $order->cancelled_at ? Carbon::parse($order->cancelled_at)->format('d M Y, H:i') : '—' }}
                                    </p>
                                </div>
                                @endif
                            </div>

                            {{-- Action area --}}
                            @if($trans)
                            <div style="margin-top:24px;">
                                <button onclick="openStatusModal({{ $order->id }}, '{{ $order->status }}', '{{ $trans['next'] }}', '{{ $trans['label'] }}')"
                                    style="width:100%; background:var(--jaced-brown-dark); color:white; border:none;
                                        border-radius:10px; padding:11px 16px; font-size:13px; font-weight:600;
                                        cursor:pointer; transition:background .15s; display:flex; align-items:center; justify-content:center; gap:8px;"
                                    onmouseover="this.style.background='#3D2B1A'"
                                    onmouseout="this.style.background='var(--jaced-brown-dark)'">
                                    <i class="bi bi-arrow-up-circle"></i> {{ $trans['label'] }}
                                </button>
                            </div>
                            @elseif($order->status === 'delivered')
                            <div style="margin-top:24px; background:#F3E5F5; border-radius:10px; padding:12px 14px;">
                                <p style="font-size:12px; color:#6A1B9A; font-weight:600; margin:0 0 2px;"><i class="bi bi-clock-history"></i> Awaiting Confirmation</p>
                                <p style="font-size:11px; color:var(--jaced-muted); margin:0;">Customer confirms arrival, or auto-arrives after 1 week.</p>
                            </div>
                            @elseif($order->status === 'arrived')
                            <div style="margin-top:24px; background:#E8F5E9; border-radius:10px; padding:12px 14px;">
                                <p style="font-size:12px; color:#2E7D32; font-weight:600; margin:0;"><i class="bi bi-check-circle"></i> Order Completed</p>
                            </div>
                            @elseif($order->status === 'cancelled')
                            <div style="margin-top:24px; background:#FFEBEE; border-radius:10px; padding:12px 14px;">
                                <p style="font-size:12px; color:#C62828; font-weight:600; margin:0;"><i class="bi bi-x-circle"></i> Cancelled by Customer</p>
                            </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

        </div>
        @empty
        <div style="padding:48px; text-align:center; color:var(--jaced-muted); font-size:14px;">No orders found.</div>
        @endforelse
        </div>
        {{-- End orderTableBody --}}

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
            
            {{-- WRAP THIS IN AN ID CONTAINER SO AJAX CAN SWAP IT OVER --}}
            <div id="paginationLinksContainer">
                {{ $orders->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>

    </div>
    {{-- End table card --}}

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
    // ── Override Bootstrap-5 pagination links to use jaced style ─────
    document.querySelectorAll('.pagination').forEach(el => {
        el.classList.add('jaced-pagination');
    });

    let pendingOrderId = null;
    let searchTimer    = null;

    const statusColors = {
        unpaid:    { bg: '#FFF3E0', color: '#E65100' },
        packed:    { bg: '#E3F2FD', color: '#1565C0' },
        delivered: { bg: '#F3E5F5', color: '#6A1B9A' },
        arrived:   { bg: '#E8F5E9', color: '#2E7D32' },
        cancelled: { bg: '#FFEBEE', color: '#C62828' },
    };

    // ── Live search ───────────────────────────────────────────────────
    document.getElementById('searchInput').addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => fetchOrders(1), 400);
    });

    // ── Intercept Pagination Clicks globally ──────────────────────────
    document.addEventListener('click', function (e) {
        // Intercept clicks coming from page elements
        const pageLink = e.target.closest('.pagination .page-link');
        
        if (pageLink) {
            e.preventDefault(); // Stop standard browser routing page load
            
            const urlString = pageLink.getAttribute('href');
            if (urlString) {
                try {
                    const url = new URL(urlString, window.location.origin);
                    const page = url.searchParams.get('page'); // Extract destination page integer
                    if (page) {
                        fetchOrders(page); // Execute search containing filters + requested page
                    }
                } catch (err) {
                    console.error('Error tracking pagination routing:', err);
                }
            }
        }
    });

    // ── Filters ───────────────────────────────────────────────────────
    function applyFilters() { fetchOrders(1); }

    function clearFilters() {
        ['searchInput','filterDateFrom','filterDateTo'].forEach(id => document.getElementById(id).value = '');
        ['filterStatus','filterPayment'].forEach(id => document.getElementById(id).value = 'all');
        fetchOrders(1);
    }

    // ── AJAX fetch ────────────────────────────────────────────────────
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
            // 1. Update the main order table body rows
            document.getElementById('orderTableBody').innerHTML = data.html;
            
            // 2. Update the pagination numeric blocks dynamically
            if (data.pagination) {
                document.getElementById('paginationLinksContainer').innerHTML = data.pagination;
            }

            // 3. Update the description info text tracking
            const info = document.getElementById('paginationInfo');
            if (data.total > 0) {
                info.textContent = `Showing ${data.from}–${data.to} of ${data.total} orders`;
            } else {
                info.textContent = 'No orders found';
            }

            // 4. Force override styles on dynamically freshly generated links
            document.querySelectorAll('.pagination').forEach(el => {
                el.classList.add('jaced-pagination');
            });
        });
    }

    // ── Expand panel ──────────────────────────────────────────────────
    function togglePanel(id) {
        const panel   = document.getElementById('panel-' + id);
        const chev    = document.getElementById('chev-' + id);
        const chevMob = document.getElementById('chev-mob-' + id);
        const isOpen  = panel.style.display !== 'none';
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
        cc.textContent = currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1);
        cc.style.background = curr.bg; cc.style.color = curr.color;

        const nc = document.getElementById('modalNextChip');
        nc.textContent = nextStatus.charAt(0).toUpperCase() + nextStatus.slice(1);
        nc.style.background = next.bg; nc.style.color = next.color;

        document.getElementById('modalConfirmLabel').textContent = label;
        document.getElementById('statusModalOverlay').classList.add('open');
    }

    function closeStatusModal() {
        document.getElementById('statusModalOverlay').classList.remove('open');
        pendingOrderId = null;
    }

    // Close on overlay click
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
                showToast('Error: ' + (data.error || 'Something went wrong.'));
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

<style>
    /* Override Bootstrap-5 pagination to match jaced style */
    .pagination { display:flex; align-items:center; gap:4px; margin:0; padding:0; }
    .pagination .page-item .page-link {
        display:flex; align-items:center; justify-content:center;
        min-width:32px; height:32px; padding:0 8px;
        border:1px solid var(--jaced-input); border-radius:6px !important;
        font-size:13px; font-weight:500;
        color:var(--jaced-brown-dark); background:white;
        transition:background .15s;
    }
    .pagination .page-item.active .page-link {
        background:var(--jaced-brown-dark);
        border-color:var(--jaced-brown-dark);
        color:white;
    }
    .pagination .page-item.disabled .page-link { color:var(--jaced-muted); background:#f9f9f9; }
    .pagination .page-item:not(.active):not(.disabled) .page-link:hover { background:var(--jaced-caramel-bg); }
</style>
@endpush

@endsection