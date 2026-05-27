@extends('base.base')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
    <style>
        .page-title {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem;
            font-weight: 400;
            color: var(--jaced-brown-dark);
            margin: 0 0 4px;
        }

        .order-meta {
            font-size: 12px;
            color: var(--jaced-muted);
            margin: 0 0 24px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .status-badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: 3px 10px;
            border-radius: 999px;
        }

        .status-badge.shipped {
            color: var(--jaced-sage);
            background: #E8EDE8;
        }

        .status-badge.processing {
            color: var(--jaced-caramel);
            background: var(--jaced-caramel-bg);
        }

        .status-badge.completed {
            color: #4a7c59;
            background: #e4f0e8;
        }

        .status-badge.cancelled {
            color: #a33d3d;
            background: #f5e4e4;
        }

        .status-badge.unpaid {
            color: #7a6a3a;
            background: #f5f0e0;
        }

        .status-badge.returns {
            color: #5a5a8a;
            background: #eeeef5;
        }

        .section-label {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--jaced-brown-dark);
            margin: 0 0 14px;
        }

        /* PRODUCT */
        .product-img {
            width: 64px;
            height: 64px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
        }

        /* TRACKING */
        .tracking-box {
            background: var(--jaced-cream);
            border-radius: 10px;
            padding: 16px 18px;
            margin-top: 16px;
        }

        .tracking-steps {
            display: flex;
            align-items: center;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .step-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 2px solid var(--jaced-input);
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
        }

        .step-circle.done {
            background: var(--jaced-dark);
            border-color: var(--jaced-dark);
            color: white;
        }

        .step-circle.active {
            background: white;
            border-color: var(--jaced-sage);
            color: var(--jaced-sage);
        }

        .step-circle.pending {
            color: var(--jaced-muted);
        }

        .step-circle.cancelled {
            background: #FFEBEE;
            border-color: var(--jaced-muted);
            color: var(--jaced-muted);
        }

        .step-label {
            font-size: 10px;
            color: var(--jaced-muted);
            margin-top: 5px;
            white-space: nowrap;
        }

        .step-connector {
            flex: 1;
            height: 2px;
            background: var(--jaced-input);
            margin: 0 -1px;
            position: relative;
            top: -9px;
        }

        .step-connector.done {
            background: var(--jaced-dark);
        }

        /* SUMMARY */
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .summary-row:last-child {
            margin-bottom: 0;
        }

        /* TIMELINE */
        .tl-wrapper {
            position: relative;
            padding-left: 24px;
        }

        .tl-line {
            position: absolute;
            left: 8px;
            top: 6px;
            bottom: 6px;
            width: 1px;
            background: var(--jaced-input);
        }

        .tl-item {
            position: relative;
            margin-bottom: 16px;
        }

        .tl-item:last-child {
            margin-bottom: 0;
        }

        .tl-dot {
            position: absolute;
            left: -20px;
            top: 4px;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--jaced-input);
            border: 2px solid white;
        }

        .tl-dot.green {
            background: var(--jaced-sage);
        }

        .tl-dot.caramel {
            background: var(--jaced-caramel);
        }

        /* BUTTONS */
        .btn-invoice {
            background: var(--jaced-dark);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            margin-top: 16px;
            transition: background .2s;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-invoice:hover {
            background: #333;
        }

        .btn-return {
            background: transparent;
            color: var(--jaced-caramel);
            border: 1px solid var(--jaced-caramel);
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            margin-top: 8px;
            transition: all .2s;
        }

        .btn-return:hover {
            background: var(--jaced-caramel-bg);
        }

        .btn-back {
            background: none;
            border: none;
            font-size: 13px;
            color: var(--jaced-muted);
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 20px;
            transition: color .2s;
        }

        .btn-back:hover {
            color: var(--jaced-brown-dark);
        }

        .btn-back svg {
            flex-shrink: 0;
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: translateY(16px) scale(.97);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
    </style>
@endpush

@section('content')
    @php
        $firstDetail = $order->orderDetails->first();
        $productName = $firstDetail?->product?->name ?? 'Order #' . $order->id;
        $extraCount = $order->orderDetails->count() - 1;

        $statusLabel = [
            'unpaid' => 'Unpaid',
            'on_process' => 'On Process',
            'packed' => 'Packed',
            'delivered' => 'Delivered',
            'shipped' => 'Shipped',
            'arrived' => 'Arrived',
            'cancelled' => 'Cancelled',
            'disputed' => 'Disputed',
            'refunded' => 'Refunded',
            'reshipped' => 'Reshipped',
        ];

        $allSteps = [
            [
                'label' => 'Confirmed',
                'statuses' => ['unpaid', 'on_process', 'packed', 'delivered', 'shipped', 'arrived'],
            ],
            ['label' => 'On Process', 'statuses' => ['on_process', 'packed', 'delivered', 'shipped', 'arrived']],
            ['label' => 'Packed', 'statuses' => ['packed', 'delivered', 'shipped', 'arrived']],
            ['label' => 'Shipped', 'statuses' => ['shipped', 'arrived']],
            ['label' => 'Arrived', 'statuses' => ['arrived']],
        ];

        $statusOrder = ['unpaid', 'on_process', 'packed', 'delivered', 'shipped', 'arrived'];
        $currentIndex = array_search($order->status, $statusOrder);
        if ($currentIndex === false) $currentIndex = 0;

        // Kalau disputed, tampilkan tracking di posisi shipped
        if ($order->status === 'disputed') {
            if ($dispute && $dispute->resolution_type === 'exchange' && $dispute->replacement_arrived_at) {
                $currentIndex = array_search('arrived', $statusOrder);
            } else {
                $currentIndex = array_search('shipped', $statusOrder);
            }
        }

        $isCancelled = $order->status === 'cancelled';

        $steps = collect($allSteps)
            ->map(function ($step, $i) use ($currentIndex, $isCancelled) {
                if ($isCancelled) return ['label' => $step['label'], 'state' => 'cancelled'];
                if ($i < $currentIndex) $state = 'done';
                elseif ($i === $currentIndex) $state = 'active';
                else $state = 'pending';
                return ['label' => $step['label'], 'state' => $state];
            })
            ->toArray();

        $updates = [];
        if ($order->arrived_at && $order->status !== 'disputed')
            $updates[] = [
                'timestamp' => $order->arrived_at->timestamp,
                'dot'   => 'green',
                'time'  => $order->arrived_at->format('M d, Y · h:i A'),
                'title' => 'Order Arrived',
                'desc'  => 'Your order has been confirmed received.',
            ];

        if ($order->shipped_at)
            $updates[] = [
                'timestamp' => $order->shipped_at->timestamp,
                'dot'   => 'green',
                'time'  => $order->shipped_at->format('M d, Y · h:i A'),
                'title' => 'Out for Delivery',
                'desc'  => 'Courier has arrived at your location.',
            ];

        if ($order->delivered_at)
            $updates[] = [
                'timestamp' => $order->delivered_at->timestamp,
                'dot'   => 'green',
                'time'  => $order->delivered_at->format('M d, Y · h:i A'),
                'title' => 'Order Delivered',
                'desc'  => 'Your order has been handed off to the courier.',
            ];

        if ($order->packed_at)
            $updates[] = [
                'timestamp' => $order->packed_at->timestamp,
                'dot'   => 'caramel',
                'time'  => $order->packed_at->format('M d, Y · h:i A'),
                'title' => 'Order Packed',
                'desc'  => 'Your order has been packed and is ready for shipping.',
            ];

        if ($order->on_process_at)
            $updates[] = [
                'timestamp' => $order->on_process_at->timestamp,
                'dot'   => 'caramel',
                'time'  => $order->on_process_at->format('M d, Y · h:i A'),
                'title' => 'Order Confirmed by Admin',
                'desc'  => 'Your payment has been confirmed.',
            ];
        if ($order->disputed_at)
            $updates[] = [
                'timestamp' => $order->disputed_at->timestamp,
                'dot'       => 'caramel',
                'time'      => $order->disputed_at->format('M d, Y · h:i A'),
                'title'     => 'Complaint Filed',
                'desc'      => 'Your complaint is being reviewed by our team.',
            ];
        if ($order->cancelled_at)
            $updates[] = [
                'timestamp' => $order->cancelled_at->timestamp,
                'dot'       => 'caramel',
                'time'      => $order->cancelled_at->format('M d, Y · h:i A'),
                'title'     => 'Order Cancelled',
                'desc'      => $order->cancellation_reason 
                                ? 'Reason: ' . $order->cancellation_reason 
                                : 'Order has been cancelled.',
            ];

        if ($dispute && $dispute->resolution_type === 'exchange') {
            if ($dispute->replacement_arrived_at)
                $updates[] = [
                    'timestamp' => \Carbon\Carbon::parse($dispute->replacement_arrived_at)->timestamp,
                    'dot'       => 'green',
                    'time'      => \Carbon\Carbon::parse($dispute->replacement_arrived_at)->format('M d, Y · h:i A'),
                    'title'     => 'Replacement Item Arrived',
                    'desc'      => 'Your replacement item has been delivered successfully.',
                ];
            if ($dispute->replacement_shipped_at)
                $updates[] = [
                    'timestamp' => \Carbon\Carbon::parse($dispute->replacement_shipped_at)->timestamp,
                    'dot'       => 'green',
                    'time'      => \Carbon\Carbon::parse($dispute->replacement_shipped_at)->format('M d, Y · h:i A'),
                    'title'     => 'Replacement Item Shipped',
                    'desc'      => 'Your replacement item is on its way.',
                ];
            if ($dispute->resolved_at)
                $updates[] = [
                    'timestamp' => \Carbon\Carbon::parse($dispute->resolved_at)->timestamp,
                    'dot'       => 'caramel',
                    'time'      => \Carbon\Carbon::parse($dispute->resolved_at)->format('M d, Y · h:i A'),
                    'title'     => 'Exchange Approved',
                    'desc'      => 'Your complaint has been resolved. Replacement item will be sent shortly.',
                ];
        }

        $updates[] = [
            'timestamp' => $order->created_at->timestamp,
            'dot' => '',
            'time' => $order->created_at->format('M d, Y · h:i A'),
            'title' => 'Order Placed',
            'desc' => 'Your order has been received and is being processed.',
        ];
        usort($updates, fn($a, $b) => $b['timestamp'] - $a['timestamp']);
    @endphp

    @if(session('success'))
        <div style="background:#e4f0e8; color:#2E7D32; padding:12px 16px; border-radius:10px; margin-bottom:16px; font-size:13px; font-weight:600;">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#f5e4e4; color:#a33d3d; padding:12px 16px; border-radius:10px; margin-bottom:16px; font-size:13px; font-weight:600;">
            {{ session('error') }}
        </div>
    @endif

    <div class="jaced-page">
        <div style="max-width: 1000px; margin: 0 auto;">

            {{-- BACK --}}
            <button class="btn-back" onclick="history.back()">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12" />
                    <polyline points="12 19 5 12 12 5" />
                </svg>
                Back to My Orders
            </button>

            {{-- HEADER --}}
            <h1 class="page-title fw-bold">Order Details</h1>
            <p class="order-meta">
                ORDER #{{ $order->id }} &nbsp;·&nbsp; {{ $order->created_at->format('M d, Y') }}
                &nbsp;·&nbsp;
                <span class="status-badge {{ $order->status }}">
                    {{ $statusLabel[$order->status] ?? ucfirst($order->status) }}
                </span>
            </p>

            {{-- Status Info Banner --}}
            @if ($order->status === 'disputed')
                @if($dispute?->resolution_type === 'refund' && $dispute?->status === 'resolved')
                    <div style="background:#E0F7FA; border-radius:10px; padding:14px 18px; margin-bottom:24px; display:flex; align-items:center; gap:12px;">
                        <span style="font-size:22px;">💰</span>
                        <div>
                            <p style="font-size:13px; color:#006064; font-weight:600; margin:0 0 2px;">Refund Approved</p>
                            <p style="font-size:12px; color:var(--jaced-muted); margin:0;">
                                Refund sedang diproses. Estimasi 3-5 hari kerja.
                            </p>
                        </div>
                    </div>

                @elseif($dispute?->resolution_type === 'exchange' && $dispute?->status === 'resolved')
                    <div style="background:#F1F8E9; border-radius:10px; padding:14px 18px; margin-bottom:24px; display:flex; align-items:center; gap:12px;">
                        <span style="font-size:22px;">📦</span>
                        <div>
                            <p style="font-size:13px; color:#33691E; font-weight:600; margin:0 0 2px;">Replacement Item Being Sent</p>
                            <p style="font-size:12px; color:var(--jaced-muted); margin:0;">Barang pengganti sedang dalam proses pengiriman.</p>
                        </div>
                    </div>

                @elseif($dispute?->status === 'rejected')
                    <div style="background:#FFEBEE; border-radius:10px; padding:14px 18px; margin-bottom:24px; display:flex; align-items:center; gap:12px;">
                        <span style="font-size:22px;">❌</span>
                        <div>
                            <p style="font-size:13px; color:#C62828; font-weight:600; margin:0 0 2px;">Complaint Rejected</p>
                            <p style="font-size:12px; color:var(--jaced-muted); margin:0;">Komplain kamu tidak dapat diproses. Hubungi customer service untuk info lebih lanjut.</p>
                        </div>
                    </div>

                @else
                    {{-- Masih under review --}}
                    <div style="background:#FFF8E1; border-radius:10px; padding:14px 18px; margin-bottom:24px; display:flex; align-items:center; gap:12px;">
                        <span style="font-size:22px;">⏳</span>
                        <div>
                            <p style="font-size:13px; color:#F57F17; font-weight:600; margin:0 0 2px;">Complaint Under Review</p>
                            <p style="font-size:12px; color:var(--jaced-muted); margin:0;">Your complaint is being reviewed by our team. We'll notify you once a resolution has been made.</p>
                        </div>
                    </div>
                @endif
            @endif

            {{-- GRID --}}
            <div class="row g-4 align-items-start">

                {{-- LEFT --}}
                <div class="col-12 col-lg-7">
                    <div class="d-flex flex-column gap-4">

                        {{-- Product --}}
                        <div class="jaced-card p-4">
                            <p class="section-label">Product</p>
                            @foreach ($order->orderDetails as $detail)
                                @php
                                    $img =
                                        $detail->product?->images?->where('is_main', 1)->first()?->image_path ??
                                        $detail->product?->images?->first()?->image_path;
                                @endphp
                                <div class="d-flex align-items-center gap-3 {{ !$loop->last ? 'mb-3' : '' }}">
                                    <img src="{{ $img ? asset($img) : asset('image/placeholder.png') }}"
                                        alt="{{ $detail->product?->name }}" class="product-img">
                                    <div>
                                        <p class="fw-semibold text-jaced-dark mb-1" style="font-size: 14px;">
                                            {{ $detail->product?->name }}
                                        </p>
                                        <p class="text-jaced-muted mb-0" style="font-size: 12px;">
                                            Qty: {{ $detail->quantity }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Tracking --}}
                        <div class="jaced-card p-4">
                            <p class="section-label">Tracking</p>
                            <p class="field-label mb-1">Estimated arrival</p>
                            @if($order->status === 'cancelled')
                                <p class="text-jaced-muted mb-0" style="font-size: 13px;">Order has been cancelled.</p>
                            @elseif($order->shipped_at)
                                <p class="fw-semibold text-jaced-dark mb-0" style="font-size: 15px;">
                                    {{ $order->shipped_at->addDays(3)->format('d M Y') }} - {{ $order->shipped_at->addDays(7)->format('d M Y') }}
                                </p>
                            @else
                                <p class="text-jaced-muted mb-0" style="font-size: 13px;">Will be updated once shipped.</p>
                            @endif

                            <div class="tracking-box">
                                <div class="tracking-steps">
                                    @foreach ($steps as $index => $step)
                                        @if ($index > 0)
                                            <div
                                                class="step-connector {{ $step['state'] === 'done' || $steps[$index - 1]['state'] === 'done' ? 'done' : '' }}">
                                            </div>
                                        @endif
                                        <div class="step">
                                            <div class="step-circle {{ $step['state'] }}">
                                                @if ($step['state'] === 'done')
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="20 6 9 17 4 12" />
                                                    </svg>
                                                @elseif ($step['state'] === 'active')
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <rect x="1" y="3" width="15" height="13" /><polygon points="16 8 20 8 23 11 23 16 16 16 16 8" /><circle cx="5.5" cy="18.5" r="2.5" /><circle cx="18.5" cy="18.5" r="2.5" />
                                                    </svg>
                                                @elseif ($step['state'] === 'cancelled')
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" /><polyline points="9 22 9 12 15 12 15 22" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <span class="step-label">{{ $step['label'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Shipping Address --}}
                        <div class="jaced-card p-4">
                            <p class="section-label">Shipping address</p>
                            @if ($order->shippingAddress)
                                <div style="display:flex; gap:12px; align-items:flex-start;">
                                    {{-- Icon --}}
                                    <div style="width:36px; height:36px; background:var(--jaced-caramel-bg); border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--jaced-caramel)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="fw-semibold text-jaced-dark mb-1" style="font-size: 14px;">
                                            {{ $order->shippingAddress->receiver_name }}
                                        </p>
                                        <p class="text-jaced-muted mb-1" style="font-size: 12px;">
                                            📞 {{ $order->shippingAddress->receiver_phone }}
                                        </p>
                                        <p class="text-jaced-muted mb-0" style="font-size: 12px; line-height: 1.7;">
                                            {{ $order->shippingAddress->address_line1 }}<br>
                                            {{ $order->shippingAddress->district_name }}, {{ $order->shippingAddress->city_name }}<br>
                                            {{ $order->shippingAddress->province_name }} {{ $order->shippingAddress->postal_code }}
                                        </p>
                                    </div>
                                </div>
                            @else
                                <p class="text-jaced-muted mb-0" style="font-size: 13px;">No address on record.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- RIGHT --}}
                <div class="col-12 col-lg-5">
                    <div class="d-flex flex-column gap-4">

                        {{-- Order Summary --}}
                        <div class="jaced-card p-4">
                            <p class="section-label">Order summary</p>

                            <div class="summary-row">
                                <span class="text-jaced-muted">Subtotal</span>
                                <span class="fw-semibold text-jaced-dark">
                                    Rp
                                    {{ number_format($order->total_price - $order->delivery_fee - $order->service_tax + $order->discount_amount, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="summary-row">
                                <span class="text-jaced-muted">Delivery fee</span>
                                <span class="fw-semibold text-jaced-dark">
                                    Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="summary-row">
                                <span class="text-jaced-muted">Service tax</span>
                                <span class="fw-semibold text-jaced-dark">
                                    Rp {{ number_format($order->service_tax, 0, ',', '.') }}
                                </span>
                            </div>
                            @if ($order->discount_amount > 0)
                                <div class="summary-row">
                                    <span class="text-jaced-muted">Discount</span>
                                    <span class="fw-semibold" style="color: var(--jaced-sage);">
                                        - Rp {{ number_format($order->discount_amount, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endif
                            <div class="summary-row">
                                <span class="text-jaced-muted">Payment method</span>
                                <span class="fw-semibold text-jaced-dark">
                                    {{ $order->paymentMethod?->name ?? '-' }}
                                </span>
                            </div>

                            <hr class="divider-jaced my-3">

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semibold text-jaced-dark" style="font-size: 15px;">Total</span>
                                <span class="fw-semibold text-jaced-sage" style="font-size: 18px;">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="d-flex flex-column gap-2 mt-3">
                                {{-- Download Invoice --}}
                                @if (!in_array($order->status, ['unpaid', 'cancelled']))
                                    <a href="{{ route('store.orderhistory.invoice', $order->id) }}" target="_blank"
                                        class="btn-invoice" style="margin-top: 0;">
                                        Download Invoice
                                    </a>
                                @endif

                                {{-- Mark as Received --}}
                                @if ($order->status === 'shipped')
                                    <button type="button" class="btn-return"
                                        style="margin-top: 0; background: var(--jaced-sage); color: white; border: none; width: 100%;"
                                        onclick="document.getElementById('modal-confirm-received').style.display='flex'">
                                        ✓ Pesanan Diterima
                                    </button>

                                    <form id="form-received-{{ $order->id }}"
                                        action="{{ route('store.orderhistory.received', $order->id) }}" method="POST"
                                        style="display:none;">
                                        @csrf
                                        @method('PATCH')
                                    </form>
                                @endif

                                @if ($order->status === 'shipped' && $order->shipped_at)
                                    @php
                                        $daysLeft = ceil(now()->floatDiffInDays($order->shipped_at->addDays(7)));
                                    @endphp
                                    @if ($daysLeft <= 3)
                                        <div
                                            style="background:#FFF3E0; border-radius:10px; padding:12px 14px; margin-bottom:12px;">
                                            <p style="font-size:12px; color:#E65100; font-weight:600; margin:0;">
                                                ⏰ Pesanan akan otomatis dikonfirmasi dalam {{ $daysLeft }} hari lagi.
                                                Ajukan komplain sekarang jika ada masalah.
                                            </p>
                                        </div>
                                    @endif
                                @endif

                                {{-- Tombol Komplain --}}
                                @if ($order->status === 'shipped')
                                    <button class="btn-return" style="margin-top: 0; width: 100%;"
                                        onclick="document.getElementById('modal-complaint').style.display='flex'">
                                        Apply Return/Complaint
                                    </button>
                                @endif

                                {{-- Cancel Order --}}
                                @if (in_array($order->status, ['unpaid', 'on_process']))
                                    <form id="form-cancel-{{ $order->id }}" 
                                        action="{{ route('store.orderhistory.cancel', $order->id) }}" method="POST"
                                        style="display:none;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" id="hidden-cancel-reason-{{ $order->id }}" name="cancellation_reason">
                                        <input type="hidden" id="hidden-other-reason-{{ $order->id }}" name="other_reason">
                                    </form>
                                    <button type="button" class="btn-return"
                                        style="margin-top: 0; width: 100%; color: #a33d3d; border-color: #a33d3d;"
                                        onclick="document.getElementById('modal-cancel-{{ $order->status }}').style.display='flex'">
                                        Cancel Order
                                    </button>
                                @endif
                            </div>
                        </div>


                        {{-- FORM KOMPLAIN --}}
                        @if ($order->status === 'shipped')
                            <div id="complaint-form" class="d-none mt-3">
                                <div class="jaced-card p-4">
                                    <p class="section-label">Apply Complain</p>
                                    <form action="{{ route('store.orderhistory.complaint', $order->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label style="font-size: 13px; font-weight: 600;">Problem Description</label>
                                            <select name="type" class="form-select mt-1" style="font-size: 13px;">
                                                <option value="missing">Item Not Received / Lost</option>
                                                <option value="damaged">Item Damaged</option>
                                                <option value="wrong_item">Wrong Item Sent</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label style="font-size: 13px; font-weight: 600;">Description</label>
                                            <textarea name="description" class="form-control mt-1" rows="3"
                                                style="font-size: 13px;" placeholder="Explain your issue..."></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label style="font-size: 13px; font-weight: 600;">Proof Photo (Optional)</label>
                                            <input type="file" name="photo" class="form-control mt-1" accept="image/*">
                                        </div>
                                        <button type="submit" class="btn-invoice" style="margin-top: 0;">
                                            Submit Complain
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                        {{-- Seller Update --}}
                        <div class="jaced-card p-4">
                            <p class="section-label">Seller update</p>
                            <div style="max-height: 273px; overflow-y: auto; padding-right: 4px;">
                                <div class="tl-wrapper">
                                    <div class="tl-line"></div>
                                    @foreach ($updates as $update)
                                        <div class="tl-item">
                                            <div class="tl-dot {{ $update['dot'] }}"></div>
                                            <p class="field-label mb-1">{{ $update['time'] }}</p>
                                            <p class="fw-semibold text-jaced-dark mb-1" style="font-size: 13px;">
                                                {{ $update['title'] }}</p>
                                            <p class="text-jaced-muted mb-0" style="font-size: 12px; line-height: 1.5;">
                                                {{ $update['desc'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Terima Pesanan --}}
    <div id="modal-confirm-received"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:9999; align-items:center; justify-content:center;">
        <div
            style="background:white; border-radius:20px; padding:32px; max-width:420px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,.2); animation:modalIn .2s ease;">

            {{-- Icon --}}
            <div style="text-align:center; margin-bottom:16px;">
                <div
                    style="width:64px; height:64px; background:var(--jaced-caramel-bg); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                        fill="none" stroke="var(--jaced-caramel)" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M20 12V22H4V12" />
                        <path d="M22 7H2v5h20V7z" />
                        <path d="M12 22V7" />
                        <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z" />
                        <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z" />
                    </svg>
                </div>
            </div>

            {{-- Title --}}
            <h5
                style="font-size:18px; font-weight:700; color:var(--jaced-brown-dark); text-align:center; margin-bottom:8px;">
                Konfirmasi Pesanan Diterima
            </h5>
            <p style="font-size:13px; color:var(--jaced-muted); text-align:center; line-height:1.6; margin-bottom:8px;">
                Apakah kamu sudah menerima dan mengecek kondisi barang?
            </p>
            <p style="font-size:12px; color:var(--jaced-muted); text-align:center; line-height:1.6; margin-bottom:24px;">
                Jika ada masalah dengan barang, gunakan tombol <strong>"Ada Masalah?"</strong> sebagai gantinya. Setelah
                konfirmasi, komplain tidak bisa diajukan.
            </p>

            {{-- Buttons --}}
            <button onclick="document.getElementById('form-received-{{ $order->id }}').submit()"
                style="width:100%; padding:13px; background:var(--jaced-sage); color:white; border:none; border-radius:12px; font-size:14px; font-weight:700; cursor:pointer; margin-bottom:10px;">
                ✓ Ya, Barang Sudah Diterima dengan Baik
            </button>
            <button onclick="document.getElementById('modal-confirm-received').style.display='none'"
                style="width:100%; padding:11px; background:none; color:var(--jaced-muted); border:1px solid var(--jaced-input); border-radius:12px; font-size:13px; font-weight:500; cursor:pointer;">
                Batal
            </button>

        </div>
    </div>

    {{-- Modal Complaint --}}
    @if ($order->status === 'shipped')
    <div id="modal-complaint"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:9999; align-items:center; justify-content:center;"
        onclick="if(event.target===this) this.style.display='none'">
        <div style="background:white; border-radius:20px; padding:32px; max-width:480px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,.2); animation:modalIn .2s ease; max-height:90vh; overflow-y:auto;">
            
            {{-- Icon --}}
            <div style="text-align:center; margin-bottom:16px;">
                <div style="width:64px; height:64px; background:#FFF3E0; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#E65100" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                </div>
            </div>

            <h5 style="font-size:18px; font-weight:700; color:var(--jaced-brown-dark); text-align:center; margin-bottom:4px;">
                Apply Return / Complaint
            </h5>
            <p style="font-size:12px; color:var(--jaced-muted); text-align:center; margin-bottom:24px;">
                Describe your issue and we'll review it as soon as possible.
            </p>

            <form action="{{ route('store.orderhistory.complaint', $order->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Problem Type</label>
                    <select name="type" class="form-select" style="font-size:13px;" onchange="togglePhotoRequired(this.value)">
                        <option value="missing">Item Not Received / Lost</option>
                        <option value="damaged">Item Damaged</option>
                        <option value="wrong_item">Wrong Item Sent</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Description</label>
                    <textarea name="description" class="form-control" rows="4"
                        style="font-size:13px;" placeholder="Explain your issue in detail..."></textarea>
                </div>
                <div class="mb-4">
                    <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">
                        Proof Photo <span id="photo-required-label" style="color:#E65100; font-weight:400;">(Optional)</span>
                    </label>
                    <input type="file" name="photo" id="photo-input" class="form-control" accept="image/*" style="font-size:13px;">
                </div>

                <button type="submit"
                    style="width:100%; padding:13px; background:#E65100; color:white; border:none; border-radius:12px; font-size:14px; font-weight:700; cursor:pointer; margin-bottom:10px;">
                    Submit Complaint
                </button>
                <button type="button"
                    onclick="document.getElementById('modal-complaint').style.display='none'"
                    style="width:100%; padding:11px; background:none; color:var(--jaced-muted); border:1px solid var(--jaced-input); border-radius:12px; font-size:13px; font-weight:500; cursor:pointer;">
                    Cancel
                </button>
            </form>

        </div>
    </div>
    @endif

    {{-- Modal Cancel - Unpaid --}}
    @if ($order->status === 'unpaid')
    <div id="modal-cancel-unpaid"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:white; border-radius:20px; padding:32px; max-width:420px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,.2); animation:modalIn .2s ease;">
            <div style="text-align:center; margin-bottom:16px;">
                <div style="width:64px; height:64px; background:#FFEBEE; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#C62828" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                </div>
            </div>
            <h5 style="font-size:18px; font-weight:700; color:var(--jaced-brown-dark); text-align:center; margin-bottom:8px;">Cancel Order?</h5>
            <p style="font-size:13px; color:var(--jaced-muted); text-align:center; line-height:1.6; margin-bottom:16px;">
                Order kamu akan dibatalkan dan tidak bisa dikembalikan.
            </p>

            {{-- Reason --}}
            <div class="mb-3" style="text-align:left;">
                <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Alasan Pembatalan</label>
                <select id="cancel-reason-unpaid" class="form-select" style="font-size:13px;" onchange="toggleOtherReason('unpaid', this.value)">
                    <option value="wrong_address">Salah alamat pengiriman</option>
                    <option value="change_of_mind">Berubah pikiran</option>
                    <option value="found_cheaper">Menemukan harga lebih murah</option>
                    <option value="ordered_by_mistake">Pesanan tidak sengaja</option>
                    <option value="others">Lainnya...</option>
                </select>
                <textarea id="other-reason-unpaid" class="form-control mt-2" rows="2"
                    style="font-size:13px; display:none;" placeholder="Tulis alasan kamu..."></textarea>
            </div>

            <button onclick="submitCancel('{{ $order->id }}', 'unpaid')"
                style="width:100%; padding:13px; background:#C62828; color:white; border:none; border-radius:12px; font-size:14px; font-weight:700; cursor:pointer; margin-bottom:10px;">
                Ya, Batalkan Order
            </button>
            <button onclick="document.getElementById('modal-cancel-unpaid').style.display='none'"
                style="width:100%; padding:11px; background:none; color:var(--jaced-muted); border:1px solid var(--jaced-input); border-radius:12px; font-size:13px; font-weight:500; cursor:pointer;">
                Kembali
            </button>
        </div>
    </div>
    @endif

    {{-- Modal Cancel - On Process (sudah bayar) --}}
    @if ($order->status === 'on_process')
    <div id="modal-cancel-on_process"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:white; border-radius:20px; padding:32px; max-width:420px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,.2); animation:modalIn .2s ease;">
            <div style="text-align:center; margin-bottom:16px;">
                <div style="width:64px; height:64px; background:#FFEBEE; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#C62828" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                </div>
            </div>
            <h5 style="font-size:18px; font-weight:700; color:var(--jaced-brown-dark); text-align:center; margin-bottom:8px;">Cancel Order?</h5>
            <p style="font-size:13px; color:var(--jaced-muted); text-align:center; line-height:1.6; margin-bottom:8px;">
                Kamu sudah melakukan pembayaran untuk order ini.
            </p>
            <p style="font-size:12px; color:var(--jaced-muted); text-align:center; line-height:1.6; margin-bottom:16px;">
                Refund akan diproses secara manual oleh tim kami dalam <strong>3-5 hari kerja</strong>.
            </p>

            {{-- Reason --}}
            <div class="mb-3" style="text-align:left;">
                <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Alasan Pembatalan</label>
                <select id="cancel-reason-on_process" class="form-select" style="font-size:13px;" onchange="toggleOtherReason('on_process', this.value)">
                    <option value="wrong_address">Salah alamat pengiriman</option>
                    <option value="change_of_mind">Berubah pikiran</option>
                    <option value="found_cheaper">Menemukan harga lebih murah</option>
                    <option value="ordered_by_mistake">Pesanan tidak sengaja</option>
                    <option value="others">Lainnya...</option>
                </select>
                <textarea id="other-reason-on_process" class="form-control mt-2" rows="2"
                    style="font-size:13px; display:none;" placeholder="Tulis alasan kamu..."></textarea>
            </div>

            <button onclick="submitCancel('{{ $order->id }}', 'on_process')"
                style="width:100%; padding:13px; background:#C62828; color:white; border:none; border-radius:12px; font-size:14px; font-weight:700; cursor:pointer; margin-bottom:10px;">
                Ya, Batalkan & Ajukan Refund
            </button>
            <button onclick="document.getElementById('modal-cancel-on_process').style.display='none'"
                style="width:100%; padding:11px; background:none; color:var(--jaced-muted); border:1px solid var(--jaced-input); border-radius:12px; font-size:13px; font-weight:500; cursor:pointer;">
                Kembali
            </button>
        </div>
    </div>
    @endif

@endsection

@push('scripts')
    <script>
        function togglePhotoRequired(type) {
            const label = document.getElementById('photo-required-label');
            const input = document.getElementById('photo-input');
            if (type === 'missing') {
                label.textContent = '(Optional)';
                label.style.color = 'var(--jaced-muted)';
                input.removeAttribute('required');
            } else {
                label.textContent = '* Required';
                label.style.color = '#E65100';
                input.setAttribute('required', 'required');
            }
        }

        function toggleOtherReason(type, value) {
            const textarea = document.getElementById('other-reason-' + type);
            if (value === 'others') {
                textarea.style.display = 'block';
                textarea.setAttribute('required', 'required');
            } else {
                textarea.style.display = 'none';
                textarea.removeAttribute('required');
            }
        }

        function submitCancel(orderId, type) {
            const reason = document.getElementById('cancel-reason-' + type).value;
            const other = document.getElementById('other-reason-' + type).value;
            
            document.getElementById('hidden-cancel-reason-' + orderId).value = reason;
            document.getElementById('hidden-other-reason-' + orderId).value = other;
            
            document.getElementById('form-cancel-' + orderId).submit();
        }
    </script>
@endpush
