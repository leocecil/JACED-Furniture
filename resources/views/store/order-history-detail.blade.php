@extends('base.base')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
    .status-badge.shipped    { color: var(--jaced-sage);    background: #E8EDE8; }
    .status-badge.processing { color: var(--jaced-caramel); background: var(--jaced-caramel-bg); }
    .status-badge.completed  { color: #4a7c59;              background: #e4f0e8; }
    .status-badge.cancelled  { color: #a33d3d;              background: #f5e4e4; }
    .status-badge.unpaid     { color: #7a6a3a;              background: #f5f0e0; }
    .status-badge.returns    { color: #5a5a8a;              background: #eeeef5; }

    .section-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--jaced-muted);
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
    .tracking-steps { display: flex; align-items: center; }
    .step { display: flex; flex-direction: column; align-items: center; }
    .step-circle {
        width: 30px; height: 30px;
        border-radius: 50%;
        border: 2px solid var(--jaced-input);
        background: white;
        display: flex; align-items: center; justify-content: center;
        position: relative; z-index: 1;
    }
    .step-circle.done   { background: var(--jaced-dark); border-color: var(--jaced-dark); color: white; }
    .step-circle.active { background: white; border-color: var(--jaced-sage); color: var(--jaced-sage); }
    .step-circle.pending { color: var(--jaced-muted); }
    .step-label { font-size: 10px; color: var(--jaced-muted); margin-top: 5px; white-space: nowrap; }
    .step-connector { flex: 1; height: 2px; background: var(--jaced-input); margin: 0 -1px; position: relative; top: -9px; }
    .step-connector.done { background: var(--jaced-dark); }

    /* SUMMARY */
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        margin-bottom: 8px;
    }
    .summary-row:last-child { margin-bottom: 0; }

    /* TIMELINE */
    .tl-wrapper { position: relative; padding-left: 24px; }
    .tl-line {
        position: absolute;
        left: 8px; top: 6px; bottom: 6px;
        width: 1px;
        background: var(--jaced-input);
    }
    .tl-item { position: relative; margin-bottom: 16px; }
    .tl-item:last-child { margin-bottom: 0; }
    .tl-dot {
        position: absolute;
        left: -20px; top: 4px;
        width: 9px; height: 9px;
        border-radius: 50%;
        background: var(--jaced-input);
        border: 2px solid white;
    }
    .tl-dot.green   { background: var(--jaced-sage); }
    .tl-dot.caramel { background: var(--jaced-caramel); }

    /* BUTTONS */
    .btn-invoice {
        background: var(--jaced-dark);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        width: 100%;
        margin-top: 16px;
        transition: background .2s;
    }
    .btn-invoice:hover { background: #333; }

    .btn-return {
        background: transparent;
        color: var(--jaced-caramel);
        border: 1px solid var(--jaced-caramel);
        border-radius: 8px;
        padding: 9px 20px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        width: 100%;
        margin-top: 8px;
        transition: all .2s;
    }
    .btn-return:hover { background: var(--jaced-caramel-bg); }

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
    .btn-back:hover { color: var(--jaced-brown-dark); }
    .btn-back svg { flex-shrink: 0; }
</style>
@endpush

@section('content')
@php
    $firstDetail = $order->orderDetails->first();
    $productName = $firstDetail?->product?->name ?? 'Order #' . $order->id;
    $extraCount  = $order->orderDetails->count() - 1;

    $statusLabel = [
        'unpaid'    => 'Unpaid',
        'packed'    => 'Packed',
        'delivered' => 'Delivered',
        'arrived'   => 'Arrived',
        'cancelled' => 'Cancelled',
    ];

    $allSteps = [
        ['label' => 'Confirmed',  'statuses' => ['unpaid', 'packed', 'delivered', 'arrived', 'cancelled']],
        ['label' => 'Packed',     'statuses' => ['packed', 'delivered', 'arrived']],
        ['label' => 'Delivered',  'statuses' => ['delivered', 'arrived']],
        ['label' => 'Arrived',    'statuses' => ['arrived']],
    ];

    $statusOrder = ['unpaid', 'packed', 'delivered', 'arrived'];
    $currentIndex = array_search($order->status, $statusOrder);

    $steps = collect($allSteps)->map(function ($step, $i) use ($currentIndex) {
        if ($i < $currentIndex) $state = 'done';
        elseif ($i === $currentIndex) $state = 'active';
        else $state = 'pending';
        return ['label' => $step['label'], 'state' => $state];
    })->toArray();

    $updates = [];
    if ($order->arrived_at)
        $updates[] = ['dot' => 'green',   'time' => $order->arrived_at->format('M d, Y · h:i A'),   'title' => 'Order Arrived',   'desc' => 'Your order has arrived at its destination.'];
    if ($order->delivered_at)
        $updates[] = ['dot' => 'green',   'time' => $order->delivered_at->format('M d, Y · h:i A'), 'title' => 'Order Delivered', 'desc' => 'Your order has been handed off to the courier.'];
    if ($order->packed_at)
        $updates[] = ['dot' => 'caramel', 'time' => $order->packed_at->format('M d, Y · h:i A'),    'title' => 'Order Packed',    'desc' => 'Your order has been packed and is ready for shipping.'];

    $updates[] = ['dot' => '', 'time' => $order->created_at->format('M d, Y · h:i A'), 'title' => 'Order Confirmed', 'desc' => 'Your order has been received and is being processed.'];
@endphp

<div class="jaced-page">
    <div style="max-width: 1000px; margin: 0 auto;">

        {{-- BACK --}}
        <button class="btn-back" onclick="history.back()">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
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
                                $img = $detail->product?->images?->where('is_main', 1)->first()?->image_path
                                    ?? $detail->product?->images?->first()?->image_path;
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
                        <p class="fw-semibold text-jaced-dark mb-0" style="font-size: 15px;">{{ $order['eta'] }}</p>

                        <div class="tracking-box">
                            <div class="tracking-steps">
                                @foreach ($steps as $index => $step)
                                    @if ($index > 0)
                                        <div class="step-connector {{ $step['state'] === 'done' || $steps[$index-1]['state'] === 'done' ? 'done' : '' }}"></div>
                                    @endif
                                    <div class="step">
                                        <div class="step-circle {{ $step['state'] }}">
                                            @if ($step['state'] === 'done')
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                            @elseif ($step['state'] === 'active')
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
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
                            <p class="fw-semibold text-jaced-dark mb-1" style="font-size: 13px;">
                                {{ $order->shippingAddress->receiver_name }}
                            </p>
                            <p class="text-jaced-muted mb-0" style="font-size: 13px; line-height: 1.7;">
                                {{ $order->shippingAddress->address_line1 }}<br>
                                {{ $order->shippingAddress->district_name }}, {{ $order->shippingAddress->city_name }}<br>
                                {{ $order->shippingAddress->province_name }} {{ $order->shippingAddress->postal_code }}
                            </p>
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
                                Rp {{ number_format($order->total_price - $order->delivery_fee - $order->service_tax + $order->discount_amount, 0, ',', '.') }}
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

                        <a href="{{ route('store.orderhistory.invoice', $order->id) }}"
                        target="_blank"
                        class="btn-invoice">
                            Download Invoice
                        </a>

                        @if ($order->status === 'arrived')
                            <button class="btn-return">Request Return</button>
                        @endif
                    </div>

                    {{-- Seller Update --}}
                    <div class="jaced-card p-4">
                        <p class="section-label">Seller update</p>

                        <div class="tl-wrapper">
                            <div class="tl-line"></div>
                            @foreach ($updates as $update)
                                <div class="tl-item">
                                    <div class="tl-dot {{ $update['dot'] }}"></div>
                                    <p class="field-label mb-1">{{ $update['time'] }}</p>
                                    <p class="fw-semibold text-jaced-dark mb-1" style="font-size: 13px;">{{ $update['title'] }}</p>
                                    <p class="text-jaced-muted mb-0" style="font-size: 12px; line-height: 1.5;">{{ $update['desc'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection