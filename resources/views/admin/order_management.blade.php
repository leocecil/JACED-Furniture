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
        [
            'id'             => '8821',
            'customer_name'  => 'Eleanor Hemingway',
            'customer_email' => 'eleanor@studio.com',
            'avatar_color'   => '#5A6B5B',
            'order_date'     => 'Oct 14, 2023',
            'status'         => 'processing',
            'total_amount'   => 4850.00,
        ],
        [
            'id'             => '8819',
            'customer_name'  => 'Arthur Miller',
            'customer_email' => 'arthur.m@designhouse.co',
            'avatar_color'   => '#C99A6B',
            'order_date'     => 'Oct 12, 2023',
            'status'         => 'delivered',
            'total_amount'   => 12200.00,
        ],
        [
            'id'             => '8790',
            'customer_name'  => 'Soren Kierkegaard',
            'customer_email' => 'existential@athens.gr',
            'avatar_color'   => '#8A6D5A',
            'order_date'     => 'Oct 11, 2023',
            'status'         => 'shipped',
            'total_amount'   => 2100.00,
        ],
        [
            'id'             => '8785',
            'customer_name'  => 'Frank Lloyd',
            'customer_email' => 'frank@prairie.com',
            'avatar_color'   => '#C0776A',
            'order_date'     => 'Oct 10, 2023',
            'status'         => 'delayed',
            'total_amount'   => 18400.00,
        ],
        [
            'id'             => '8772',
            'customer_name'  => 'Virginia Woolf',
            'customer_email' => 'virginia@bloomsbury.co',
            'avatar_color'   => '#7B68A0',
            'order_date'     => 'Oct 09, 2023',
            'status'         => 'processing',
            'total_amount'   => 6750.00,
        ],
        [
            'id'             => '8760',
            'customer_name'  => 'James Baldwin',
            'customer_email' => 'james.b@harlem.com',
            'avatar_color'   => '#4A7B8A',
            'order_date'     => 'Oct 08, 2023',
            'status'         => 'delivered',
            'total_amount'   => 9320.00,
        ],
        [
            'id'             => '8751',
            'customer_name'  => 'Maya Angelou',
            'customer_email' => 'maya@letters.org',
            'avatar_color'   => '#7A8A5B',
            'order_date'     => 'Oct 07, 2023',
            'status'         => 'pending',
            'total_amount'   => 3200.00,
        ],
        [
            'id'             => '8744',
            'customer_name'  => 'Oscar Wilde',
            'customer_email' => 'oscar@aesthetic.ie',
            'avatar_color'   => '#5A4D7A',
            'order_date'     => 'Oct 06, 2023',
            'status'         => 'shipped',
            'total_amount'   => 7890.00,
        ],
    ];

    $statusStyles = [
        'processing' => 'background:#FFF3E0; color:#E65100;',
        'delivered'  => 'background:#E8F5E9; color:#2E7D32;',
        'shipped'    => 'background:#FFF9C4; color:#F57F17;',
        'delayed'    => 'background:#FFEBEE; color:#C62828;',
        'pending'    => 'background:#F3E5F5; color:#6A1B9A;',
        'in_production' => 'background:#E3F2FD; color:#1565C0;',
    ];

    $currentPage   = 1;
    $totalOrders   = 248;
    $totalPages    = 25;
@endphp

<div class="jaced-page" style="min-height: 100vh;">
    <div style="max-width: 1100px; margin: 0 auto;">

        {{-- ===== PAGE HEADER ===== --}}
        <div class="d-flex align-items-start justify-content-between mb-4">
            <div>
                <h1 class="font-serif-jaced text-jaced-dark mb-1"
                    style="font-size: 1.9rem; font-weight: 700; letter-spacing: -0.5px;">
                    Order Management
                </h1>
                <nav style="font-size: 12px; color: var(--jaced-muted); font-weight: 500; letter-spacing: 0.5px;">
                    <span>STUDIO</span>
                    <span class="mx-2">›</span>
                    <span style="color: var(--jaced-brown-dark);">ACTIVE ORDERS</span>
                </nav>
            </div>
            <div class="d-flex gap-2 mt-1">
                <button class="btn d-flex align-items-center gap-2"
                    style="background: white; border: 1px solid var(--jaced-input); border-radius: 8px; font-size: 13px; font-weight: 500; color: var(--jaced-brown-dark); padding: 9px 16px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <line x1="4" y1="6" x2="20" y2="6" />
                        <line x1="8" y1="12" x2="20" y2="12" />
                        <line x1="12" y1="18" x2="20" y2="18" />
                    </svg>
                    Advanced Filters
                </button>
                <button class="btn d-flex align-items-center gap-2"
                    style="background: white; border: 1px solid var(--jaced-input); border-radius: 8px; font-size: 13px; font-weight: 500; color: var(--jaced-brown-dark); padding: 9px 16px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" y1="15" x2="12" y2="3" />
                    </svg>
                    Export Manifest
                </button>
            </div>
        </div>

        {{-- ===== STAT CARDS ===== --}}
        <div class="row g-3 mb-4">

            {{-- Processing --}}
            <div class="col-md-3">
                <div class="jaced-card p-4" style="box-shadow: 0 1px 4px rgba(0,0,0,0.05);">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div style="background: var(--jaced-caramel-bg); border-radius: 8px; padding: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
                                stroke="var(--jaced-caramel)" stroke-width="2">
                                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                                <rect x="9" y="3" width="6" height="4" rx="1" />
                            </svg>
                        </div>
                        <span style="background: #FFF3E0; color: #E65100; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 99px;">
                            +4%
                        </span>
                    </div>
                    <p class="text-jaced-muted mb-1" style="font-size: 11px; font-weight: 600; letter-spacing: 0.8px; text-transform: uppercase;">Processing</p>
                    <p class="text-jaced-dark mb-0" style="font-size: 2rem; font-weight: 700; line-height: 1;">
                        {{ $stats['processing'] }}
                    </p>
                </div>
            </div>

            {{-- In Production --}}
            <div class="col-md-3">
                <div class="jaced-card p-4" style="box-shadow: 0 1px 4px rgba(0,0,0,0.05);">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div style="background: #E8F5E9; border-radius: 8px; padding: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
                                stroke="#388E3C" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                        </div>
                        <span style="background: #E8F5E9; color: #2E7D32; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 99px;">
                            On Schedule
                        </span>
                    </div>
                    <p class="text-jaced-muted mb-1" style="font-size: 11px; font-weight: 600; letter-spacing: 0.8px; text-transform: uppercase;">In Production</p>
                    <p class="text-jaced-dark mb-0" style="font-size: 2rem; font-weight: 700; line-height: 1;">
                        {{ $stats['in_production'] }}
                    </p>
                </div>
            </div>

            {{-- Out for Delivery --}}
            <div class="col-md-3">
                <div class="jaced-card p-4" style="box-shadow: 0 1px 4px rgba(0,0,0,0.05);">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div style="background: #E3F2FD; border-radius: 8px; padding: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
                                stroke="#1565C0" stroke-width="2">
                                <rect x="1" y="3" width="15" height="13" rx="1" />
                                <path d="M16 8h4l3 5v3h-7V8z" />
                                <circle cx="5.5" cy="18.5" r="2.5" />
                                <circle cx="18.5" cy="18.5" r="2.5" />
                            </svg>
                        </div>
                        <span style="background: #E3F2FD; color: #1565C0; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 99px;">
                            Active
                        </span>
                    </div>
                    <p class="text-jaced-muted mb-1" style="font-size: 11px; font-weight: 600; letter-spacing: 0.8px; text-transform: uppercase;">Out for Delivery</p>
                    <p class="text-jaced-dark mb-0" style="font-size: 2rem; font-weight: 700; line-height: 1;">
                        {{ $stats['out_for_delivery'] }}
                    </p>
                </div>
            </div>

            {{-- Weekly Revenue --}}
            <div class="col-md-3">
                <div class="p-4" style="background: var(--jaced-brown-dark); border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.15);">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div style="background: rgba(255,255,255,0.1); border-radius: 8px; padding: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
                                stroke="white" stroke-width="2">
                                <rect x="2" y="5" width="20" height="14" rx="2" />
                                <line x1="2" y1="10" x2="22" y2="10" />
                            </svg>
                        </div>
                        <span style="background: rgba(255,255,255,0.15); color: white; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 99px;">
                            Goal Met
                        </span>
                    </div>
                    <p style="color: rgba(255,255,255,0.6); font-size: 11px; font-weight: 600; letter-spacing: 0.8px; text-transform: uppercase; margin-bottom: 4px;">
                        Weekly Revenue
                    </p>
                    <p style="color: white; font-size: 1.75rem; font-weight: 700; line-height: 1; margin: 0;">
                        ${{ number_format($stats['weekly_revenue']) }}
                    </p>
                </div>
            </div>

        </div>
        {{-- END STAT CARDS --}}

        {{-- ===== ORDERS TABLE ===== --}}
        <div class="jaced-card" style="box-shadow: 0 1px 4px rgba(0,0,0,0.05); overflow: hidden;">

            {{-- Toolbar --}}
            <div class="d-flex align-items-center justify-content-between px-4 py-3"
                style="border-bottom: 1px solid var(--jaced-input);">
                <div class="d-flex align-items-center gap-2">
                    <input type="checkbox" id="selectAll"
                        style="width: 16px; height: 16px; accent-color: var(--jaced-sage); cursor: pointer;">
                    <label for="selectAll" class="mb-0" style="font-size: 13px; font-weight: 500; cursor: pointer;">
                        <span id="selectedCount">0</span> Orders selected
                    </label>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <a href="#" style="font-size: 13px; font-weight: 500; color: var(--jaced-brown-dark); text-decoration: none;">
                        Mark Shipped
                    </a>
                    <span style="color: var(--jaced-input);">|</span>
                    <a href="#" style="font-size: 13px; font-weight: 500; color: var(--jaced-brown-dark); text-decoration: none;">
                        Download Invoice
                    </a>
                    <span style="color: var(--jaced-input);">|</span>
                    <a href="#" style="font-size: 13px; font-weight: 600; color: var(--jaced-caramel); text-decoration: none;">
                        Archive
                    </a>
                </div>
            </div>

            {{-- Table Header --}}
            <div class="px-4 py-2" style="border-bottom: 1px solid var(--jaced-input);">
                <div class="row align-items-center g-0"
                    style="font-size: 11px; font-weight: 600; letter-spacing: 0.7px; text-transform: uppercase; color: var(--jaced-muted);">
                    <div class="col-auto" style="width: 40px;"></div>
                    <div class="col-2">Order ID</div>
                    <div class="col-3">Customer</div>
                    <div class="col-2">Order Date</div>
                    <div class="col-2">Status</div>
                    <div class="col-2 text-end">Amount</div>
                    <div class="col-1 text-end"></div>
                </div>
            </div>

            {{-- Table Rows --}}
            @foreach($orders as $order)
            @php
                $initials = collect(explode(' ', $order['customer_name']))
                    ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                    ->take(2)
                    ->implode('');
                $style = $statusStyles[strtolower($order['status'])] ?? 'background:#F5F5F5; color:#616161;';

                $logs = [
                    ['label' => 'Order Received',     'time' => $order['order_date'] . ' 09:10 AM', 'color' => '#4CAF50'],
                    ['label' => 'Payment Confirmed',  'time' => $order['order_date'] . ' 09:45 AM', 'color' => '#4CAF50'],
                    ['label' => 'Sent to Production', 'time' => $order['order_date'] . ' 11:00 AM', 'color' => '#FF9800'],
                    ['label' => 'Awaiting Dispatch',  'time' => 'Pending',                          'color' => '#BDBDBD'],
                ];
            @endphp

            <div class="order-row" style="border-bottom: 1px solid var(--jaced-input);">

                {{-- Row Utama --}}
                <div class="px-4 py-3 order-row-trigger"
                    style="cursor: pointer; transition: background 0.15s;"
                    onclick="toggleOrderPanel('panel-{{ $order['id'] }}', 'chev-{{ $order['id'] }}')">
                    <div class="row align-items-center g-0">

                        {{-- Checkbox --}}
                        <div class="col-auto" style="width: 40px;">
                            <input type="checkbox" class="order-checkbox"
                                style="width: 16px; height: 16px; accent-color: var(--jaced-sage); cursor: pointer;"
                                onclick="event.stopPropagation()">
                        </div>

                        {{-- Order ID --}}
                        <div class="col-2">
                            <span style="font-size: 13px; font-weight: 600; color: var(--jaced-brown-dark);">
                                #ORD-{{ $order['id'] }}
                            </span>
                        </div>

                        {{-- Customer --}}
                        <div class="col-3 d-flex align-items-center gap-2">
                            <div style="
                                width: 34px; height: 34px; border-radius: 50%;
                                background: {{ $order['avatar_color'] }};
                                display: flex; align-items: center; justify-content: center;
                                font-size: 11px; font-weight: 700; color: white; flex-shrink: 0;">
                                {{ $initials }}
                            </div>
                            <div>
                                <p class="mb-0" style="font-size: 13px; font-weight: 600; color: var(--jaced-brown-dark);">
                                    {{ $order['customer_name'] }}
                                </p>
                                <p class="mb-0 text-jaced-muted" style="font-size: 11px;">
                                    {{ $order['customer_email'] }}
                                </p>
                            </div>
                        </div>

                        {{-- Order Date --}}
                        <div class="col-2">
                            <span style="font-size: 13px; color: var(--jaced-muted);">
                                {{ $order['order_date'] }}
                            </span>
                        </div>

                        {{-- Status --}}
                        <div class="col-2">
                            <span style="{{ $style }} font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 99px; text-transform: uppercase; letter-spacing: 0.5px;">
                                {{ ucfirst($order['status']) }}
                            </span>
                        </div>

                        {{-- Amount --}}
                        <div class="col-2 text-end">
                            <span style="font-size: 13px; font-weight: 600; color: var(--jaced-brown-dark);">
                                ${{ number_format($order['total_amount'], 2) }}
                            </span>
                        </div>

                        {{-- Chevron --}}
                        <div class="col-1 text-end">
                            <span id="chev-{{ $order['id'] }}"
                                style="color: var(--jaced-muted); font-size: 16px; display: inline-block; transition: transform 0.25s; line-height: 1;">
                                ▾
                            </span>
                        </div>

                    </div>
                </div>

                {{-- ===== EXPAND PANEL ===== --}}
                <div id="panel-{{ $order['id'] }}"
                    style="display: none; background: #FDFBF8; border-top: 1px solid var(--jaced-input); padding: 24px 24px 24px 68px;">

                    <div class="row g-4">

                        {{-- Kolom 1: Payment & Recipient --}}
                        <div class="col-md-4">
                            <p style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--jaced-sage); margin-bottom: 12px;">
                                Payment Method
                            </p>
                            <p style="font-size: 11px; color: var(--jaced-muted); margin-bottom: 2px;">Transaction Type</p>
                            <p style="font-size: 13px; font-weight: 500; color: var(--jaced-brown-dark); margin-bottom: 10px;">Bank Transfer</p>

                            <p style="font-size: 11px; color: var(--jaced-muted); margin-bottom: 2px;">Reference ID</p>
                            <p style="font-size: 13px; font-weight: 500; color: var(--jaced-brown-dark); margin-bottom: 20px;">#TXN-{{ $order['id'] }}0042</p>

                            <p style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--jaced-sage); margin-bottom: 12px;">
                                Recipient Profile
                            </p>
                            <p style="font-size: 11px; color: var(--jaced-muted); margin-bottom: 2px;">Full Name</p>
                            <p style="font-size: 13px; font-weight: 500; color: var(--jaced-brown-dark); margin-bottom: 10px;">{{ $order['customer_name'] }}</p>

                            <p style="font-size: 11px; color: var(--jaced-muted); margin-bottom: 2px;">Email</p>
                            <p style="font-size: 13px; font-weight: 500; color: var(--jaced-brown-dark); margin-bottom: 0;">{{ $order['customer_email'] }}</p>
                        </div>

                        {{-- Kolom 2: Log --}}
                        <div class="col-md-4">
                            <p style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--jaced-sage); margin-bottom: 12px;">
                                Log
                            </p>
                            @foreach($logs as $log)
                            <div class="d-flex align-items-start gap-2 mb-3">
                                <div style="width: 8px; height: 8px; border-radius: 50%; background: {{ $log['color'] }}; margin-top: 4px; flex-shrink: 0;"></div>
                                <div>
                                    <p style="font-size: 12px; font-weight: 600; color: var(--jaced-brown-dark); margin: 0;">{{ $log['label'] }}</p>
                                    <p style="font-size: 11px; color: var(--jaced-muted); margin: 0;">{{ $log['time'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Kolom 3: Edit Status --}}
                        <div class="col-md-4">
                            <p style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--jaced-sage); margin-bottom: 12px;">
                                Update Status
                            </p>

                            <select id="status-select-{{ $order['id'] }}"
                                style="width: 100%; font-size: 13px; border: 1px solid var(--jaced-input); border-radius: 8px; padding: 9px 10px; color: var(--jaced-brown-dark); background: white; margin-bottom: 10px; cursor: pointer;">
                                @foreach(['processing' => 'Processing', 'in_production' => 'In Production', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'delayed' => 'Delayed', 'pending' => 'Pending'] as $val => $label)
                                <option value="{{ $val }}" {{ $order['status'] === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>

                            <button
                                onclick="saveOrderStatus('{{ $order['id'] }}')"
                                style="width: 100%; background: var(--jaced-brown-dark); color: white; border: none; border-radius: 8px; padding: 10px; font-size: 13px; font-weight: 600; cursor: pointer; margin-bottom: 8px; transition: background 0.15s;"
                                onmouseover="this.style.background='#4A3020'"
                                onmouseout="this.style.background='var(--jaced-brown-dark)'">
                                Save Changes
                            </button>

                            <div id="saved-msg-{{ $order['id'] }}"
                                style="display: none; font-size: 12px; color: #2E7D32; font-weight: 600; margin-bottom: 4px;">
                                ✓ Status updated successfully
                            </div>

                            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--jaced-input);">
                                <p style="font-size: 11px; color: var(--jaced-muted); margin-bottom: 6px;">Internal Note</p>
                                <textarea
                                    style="width: 100%; font-size: 12px; border: 1px solid var(--jaced-input); border-radius: 8px; padding: 9px; color: var(--jaced-brown-dark); resize: none; height: 64px; background: white;"
                                    placeholder="Add a note for this order..."></textarea>
                            </div>
                        </div>

                    </div>
                </div>
                {{-- END EXPAND PANEL --}}

            </div>
            @endforeach

            {{-- Pagination --}}
            <div class="d-flex align-items-center justify-content-between px-4 py-3">
                <span style="font-size: 12px; color: var(--jaced-muted);">
                    Showing 1 to {{ count($orders) }} of {{ $totalOrders }} orders
                </span>
                <div class="d-flex align-items-center gap-1">
                    <a href="#" style="width:32px; height:32px; display:flex; align-items:center; justify-content:center; border:1px solid var(--jaced-input); border-radius:6px; color:var(--jaced-muted); text-decoration:none; font-size:13px;">
                        ‹
                    </a>
                    @for($p = 1; $p <= 3; $p++)
                    <a href="#" style="width:32px; height:32px; display:flex; align-items:center; justify-content:center; border-radius:6px; font-size:13px; font-weight:500; text-decoration:none;
                        {{ $p === $currentPage ? 'background:var(--jaced-brown-dark); color:white; border:1px solid var(--jaced-brown-dark);' : 'border:1px solid var(--jaced-input); color:var(--jaced-brown-dark);' }}">
                        {{ $p }}
                    </a>
                    @endfor
                    <span style="color:var(--jaced-muted); font-size:13px; padding: 0 4px;">...</span>
                    <a href="#" style="width:32px; height:32px; display:flex; align-items:center; justify-content:center; border:1px solid var(--jaced-input); border-radius:6px; color:var(--jaced-brown-dark); text-decoration:none; font-size:13px; font-weight:500;">
                        {{ $totalPages }}
                    </a>
                    <a href="#" style="width:32px; height:32px; display:flex; align-items:center; justify-content:center; border:1px solid var(--jaced-input); border-radius:6px; color:var(--jaced-muted); text-decoration:none; font-size:13px;">
                        ›
                    </a>
                </div>
            </div>

        </div>
        {{-- END TABLE CARD --}}

    </div>
</div>

<style>
    .order-row-trigger:hover { background-color: var(--jaced-caramel-bg) !important; }
    .order-row:last-child { border-bottom: none !important; }
</style>

<script>
    // ===== Select All Toggle =====
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.order-checkbox');
    const count = document.getElementById('selectedCount');

    function updateCount() {
        const checked = document.querySelectorAll('.order-checkbox:checked').length;
        count.textContent = checked;
    }

    selectAll.addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateCount();
    });

    checkboxes.forEach(cb => cb.addEventListener('change', updateCount));

    // ===== Expand / Collapse Row =====
    function toggleOrderPanel(panelId, chevId) {
        const panel = document.getElementById(panelId);
        const chev  = document.getElementById(chevId);
        const isOpen = panel.style.display !== 'none';

        panel.style.display  = isOpen ? 'none' : 'block';
        chev.style.transform = isOpen ? ''       : 'rotate(180deg)';
    }

    // ===== Save Status =====
    function saveOrderStatus(orderId) {
        const msg = document.getElementById('saved-msg-' + orderId);
        msg.style.display = 'block';
        setTimeout(() => msg.style.display = 'none', 2500);
    }
</script>

@endsection