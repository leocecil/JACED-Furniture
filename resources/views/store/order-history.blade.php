@extends('base.base')

@push('styles')
<style>

    .nav-pills .nav-link::after,
    .nav-pills .nav-link::before {
        display: none !important;
        content: none !important;
    }

    .nav-pills a.nav-link {
        text-decoration: none !important;
    }

    .nav-pills a.nav-link:hover {
        text-decoration: none !important;
    }
    .page-title {
        font-size: 2.8rem;
        font-weight: 400;
        line-height: 1.1;
        color: var(--jaced-brown-dark);
    }

    .nav-pills .nav-link {
        border: 1px solid var(--jaced-input);
        border-radius: 999px;
        color: var(--jaced-brown-dark);
        font-size: 13px;
        font-weight: 500;
        padding: 7px 20px;
        background: transparent;
        transition: all .2s;
        text-decoration: none !important;
    }
    .nav-pills .nav-link:hover {
        border: 1px solid var(--jaced-sage) !important;
        border-color: var(--jaced-sage);
        color: var(--jaced-sage);
    }
    .nav-pills .nav-link.active {
        background-color: var(--jaced-caramel) !important;
        border-color: var(--jaced-caramel) !important;
        color: white !important;
    }

    .order-product-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 10px;
        flex-shrink: 0;
    }
    .order-id-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--jaced-muted);
    }
    .status-badge {
        display: inline-block;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .08em;
        text-transform: uppercase;
        padding: 4px 12px;
        border-radius: 999px;
    }
    .status-badge.shipped    { color: var(--jaced-sage);    background: #E8EDE8; }
    .status-badge.processing { color: var(--jaced-caramel); background: var(--jaced-caramel-bg); }
    .status-badge.completed  { color: #4a7c59;              background: #e4f0e8; }
    .status-badge.cancelled  { color: #a33d3d;              background: #f5e4e4; }
    .status-badge.unpaid     { color: #7a6a3a;              background: #f5f0e0; }
    .status-badge.returns    { color: #5a5a8a;              background: #eeeef5; }

    .btn-order-details {
        background: var(--jaced-dark);
        color: white;
        padding: 9px 22px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: background .2s;
        text-decoration: none;
        display: inline-block;
    }
    .btn-order-details:hover {
        background: #333;
        color: white;
    }

    .order-divider {
        border: none;
        border-top: 1px solid var(--jaced-input);
        margin: 0;
    }
</style>
@endpush

@section('content')
@php
    $statusLabel = [
        'unpaid'    => 'Unpaid',
        'packed'    => 'Packed',
        'delivered' => 'Delivered',
        'arrived'   => 'Arrived',
        'cancelled' => 'Cancelled',
    ];
    $statusClass = [
        'unpaid'    => 'unpaid',
        'packed'    => 'processing',
        'delivered' => 'shipped',
        'arrived'   => 'completed',
        'cancelled' => 'cancelled',
    ];
@endphp

<div class="jaced-page">
    <div style="max-width: 860px; margin: 0 auto;">

        <div class="mb-4">
            <h1 class="fw-bold mb-1">My Orders</h1>
            <p class="text-jaced-muted mb-0" style="font-size: 14px; line-height: 1.6;">
                Track your orders and manage their journey from our artisan workshops to your sanctuary.
            </p>
        </div>

        {{-- FILTER TABS --}}
        <ul class="nav nav-pills flex-wrap gap-2 mb-4">
            @foreach ($filters as $filter)
                <li class="nav-item">
                    <a href="{{ route('store.orderhistory', ['filter' => $filter]) }}"
                       class="nav-link text-decoration-none {{ $filter === $activeFilter ? 'active' : '' }}">
                        {{ $filter }}
                    </a>
                </li>
            @endforeach
        </ul>

        {{-- ORDER LIST --}}
        <div class="jaced-card">
            @forelse ($orders as $order)
                @php
                    $firstDetail = $order->orderDetails->first();
                    $productName = $firstDetail?->product?->name ?? 'Order #' . $order->id;
                    $productImage = $firstDetail?->product?->image
                        ? asset('storage/' . $firstDetail->product->image)
                        : asset('image/placeholder.png');
                    $extraCount = $order->orderDetails->count() - 1;
                @endphp

                <div class="d-flex align-items-center gap-4 p-4">

                    <img src="{{ $productImage }}"
                         alt="{{ $productName }}"
                         class="order-product-img">

                    <div class="flex-grow-1">
                        <h2 class="fw-bold mb-1" style="font-size: 18px; font-weight: 400;">
                            {{ $productName }}
                            @if ($extraCount > 0)
                                <span style="font-size: 13px; color: var(--jaced-muted); font-weight: 400;">
                                    +{{ $extraCount }} other item{{ $extraCount > 1 ? 's' : '' }}
                                </span>
                            @endif
                        </h2>

                        <p class="order-id-label mb-2">
                            ORDER #{{ $order->id }} &nbsp;·&nbsp; {{ $order->created_at->format('M d, Y') }}
                        </p>

                        <span class="status-badge {{ $statusClass[$order->status] ?? '' }}">
                            {{ $statusLabel[$order->status] ?? ucfirst($order->status) }}
                        </span>

                        @if ($order->status === 'delivered' && $order->delivered_at)
                            <p class="text-jaced-muted mb-0 mt-2" style="font-size: 12px;">
                                Delivered on
                                <span class="fw-semibold">
                                    {{ $order->delivered_at->format('l, M d') }}
                                </span>
                            </p>
                        @elseif ($order->status === 'packed' && $order->packed_at)
                            <p class="text-jaced-muted mb-0 mt-2" style="font-size: 12px;">
                                Packed on
                                <span class="fw-semibold">
                                    {{ $order->packed_at->format('l, M d') }}
                                </span>
                            </p>
                        @endif
                    </div>

                    <div class="d-flex flex-column align-items-end gap-3 flex-shrink-0">
                        <p class="fw-bold text-jaced-dark mb-0" style="font-size: 16px;">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </p>
                        <a href="{{ route('store.transactionhistory_detail.show', $order->id) }}"
                           class="btn-order-details">
                            Order Details
                        </a>
                    </div>

                </div>

                @if (!$loop->last)
                    <hr class="order-divider">
                @endif

            @empty
                <div class="p-5 text-center">
                    <p style="color: var(--jaced-muted); font-size: 14px; margin: 0;">
                        No orders found.
                    </p>
                </div>
            @endforelse
        </div>

    </div>
</div>

@endsection