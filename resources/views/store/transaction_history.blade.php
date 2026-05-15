@extends('base.base')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
<style>
    .page-title {
        font-family: 'DM Serif Display', serif;
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
    }
    .nav-pills .nav-link:hover {
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
    $orders = [
        [
            'name'   => 'The Kyoto Lounge Chair',
            'id'     => 'CL-8924',
            'date'   => 'Oct 20, 2024',
            'status' => 'shipped',
            'eta'    => 'Thursday, Oct 26',
            'image'  => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=400&h=400&fit=crop',
            'total'  => 'Rp 18.500.000',
        ],
        [
            'name'   => 'Oka Dining Table',
            'id'     => 'CL-8801',
            'date'   => 'Oct 10, 2024',
            'status' => 'completed',
            'eta'    => null,
            'image'  => 'https://images.unsplash.com/photo-1577140917170-285929fb55b7?w=400&h=400&fit=crop',
            'total'  => 'Rp 31.200.000',
        ],
        [
            'name'   => 'Washi Side Cabinet',
            'id'     => 'CL-8750',
            'date'   => 'Sep 28, 2024',
            'status' => 'cancelled',
            'eta'    => null,
            'image'  => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=400&h=400&fit=crop',
            'total'  => 'Rp 9.800.000',
        ],
    ];

    $statusLabel = [
        'shipped'    => 'Shipped',
        'processing' => 'Processing',
        'completed'  => 'Completed',
        'cancelled'  => 'Cancelled',
        'unpaid'     => 'Unpaid',
        'returns'    => 'Returns',
    ];
@endphp

<div class="jaced-page">
    <div style="max-width: 860px; margin: 0 auto;">

        {{-- PAGE HEADER --}}
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
                    <button class="nav-link {{ $filter === $activeFilter ? 'active' : '' }}">
                        {{ $filter }}
                    </button>
                </li>
            @endforeach
        </ul>

        {{-- ORDER LIST --}}
        <div class="jaced-card">
            @foreach ($orders as $order)

                <div class="d-flex align-items-center gap-4 p-4">

                    {{-- Product Image --}}
                    <img src="{{ $order['image'] }}" alt="{{ $order['name'] }}" class="order-product-img">

                    {{-- Order Info --}}
                    <div class="flex-grow-1">
                        <h2 class="fw-bold mb-1" style=" font-size: 18px; font-weight: 400;">
                            {{ $order['name'] }}
                        </h2>
                        <p class="order-id-label mb-2">ORDER {{ $order['id'] }} &nbsp;·&nbsp; {{ $order['date'] }}</p>
                        <span class="status-badge {{ $order['status'] }}">
                            {{ $statusLabel[$order['status']] }}
                        </span>
                        @if ($order['eta'])
                            <p class="text-jaced-muted mb-0 mt-2" style="font-size: 12px;">
                                Arriving by <span class="text-jaced-dark fw-semibold">{{ $order['eta'] }}</span>
                            </p>
                        @endif
                    </div>

                    {{-- Right: total + CTA --}}
                    <div class="d-flex flex-column align-items-end gap-3 flex-shrink-0">
                        <p class="fw-bold text-jaced-dark mb-0" style="font-size: 16px;">{{ $order['total'] }}</p>
                        <a href="{{ route('store.transactionhistory_detail.show', $order['id']) }}" class="btn-order-details">Order Details</a>
                    </div>

                </div>

                @if (!$loop->last)
                    <hr class="order-divider">
                @endif

            @endforeach
        </div>

    </div>
</div>

@endsection