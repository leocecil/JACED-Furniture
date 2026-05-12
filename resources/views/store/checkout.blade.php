@extends('base.base')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700&display=swap" rel="stylesheet">
@vite(['resources/css/jaced.css'])
@endpush

@section('content')
@php
    $items = [
        [
            'name'    => 'The Nora Lounge Chair',
            'variant' => 'Cream Boucle / Walnut',
            'qty'     => 1,
            'price'   => 1250.0,
            'image'   => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=200&h=200&fit=crop',
        ],
        [
            'name'    => 'Oka Dining Table',
            'variant' => 'Light Oak / 72in',
            'qty'     => 1,
            'price'   => 2100.0,
            'image'   => 'https://images.unsplash.com/photo-1577140917170-285929fb55b7?w=200&h=200&fit=crop',
        ],
    ];

    $subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $items));
    $shipping = 150.0;
    $tax      = $subtotal * 0.0836;
    $total    = $subtotal + $shipping + $tax;
@endphp

<div class="jaced-page">
    <div style="max-width: 1280px; margin: 0 auto;">

        <h1 class="font-serif-jaced text-jaced-dark mb-5" style="font-size: 2.8rem; font-weight: 400;">Checkout</h1>

        <div class="row g-4 align-items-start">

            {{-- LEFT --}}
            <div class="col-12 col-lg-8">

                {{-- Review Order --}}
                <div class="mb-5">
                    <h2 class="font-serif-jaced text-jaced-sage mb-4" style="font-size: 1.5rem; font-weight: 400;">Review Order</h2>
                    @foreach ($items as $item)
                        <div class="jaced-item-card">
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-jaced-dark">{{ $item['name'] }}</div>
                                <div class="text-jaced-muted" style="font-size: 13px;">{{ $item['variant'] }}</div>
                                <div class="text-jaced-muted" style="font-size: 13px;">Qty: {{ $item['qty'] }}</div>
                            </div>
                            <div class="fw-semibold text-jaced-dark">${{ number_format($item['price'], 2) }}</div>
                        </div>
                    @endforeach
                </div>

                {{-- Shipping Address --}}
                <div class="mb-5">
                    <h2 class="font-serif-jaced text-jaced-sage mb-4" style="font-size: 1.5rem; font-weight: 400;">Shipping Address</h2>
                    <div class="jaced-card p-4">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label>First Name</label>
                                <input type="text" name="first_name" class="input-jaced" placeholder="Jane">
                            </div>
                            <div class="col-12 col-md-6">
                                <label>Last Name</label>
                                <input type="text" name="last_name" class="input-jaced" placeholder="Doe">
                            </div>
                            <div class="col-12">
                                <label>Street Address</label>
                                <input type="text" name="street" class="input-jaced" placeholder="123 Artisan Way">
                            </div>
                            <div class="col-12 col-md-4">
                                <label>City</label>
                                <input type="text" name="city" class="input-jaced" placeholder="New York">
                            </div>
                            <div class="col-12 col-md-4">
                                <label>State</label>
                                <input type="text" name="state" class="input-jaced" placeholder="NY">
                            </div>
                            <div class="col-12 col-md-4">
                                <label>ZIP Code</label>
                                <input type="text" name="zip" class="input-jaced" placeholder="10001">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment --}}
                <div class="mb-5">
                    <h2 class="font-serif-jaced text-jaced-sage mb-4" style="font-size: 1.5rem; font-weight: 400;">Payment Method</h2>
                    <div class="jaced-card p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label>Card Number</label>
                                <div class="input-with-icon">
                                    <span class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                                    </span>
                                    <input type="text" name="card_number" class="input-jaced" placeholder="0000 0000 0000 0000">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label>Expiration Date</label>
                                <input type="text" name="exp" class="input-jaced" placeholder="MM/YY">
                            </div>
                            <div class="col-12 col-md-6">
                                <label>CVC</label>
                                <input type="text" name="cvc" class="input-jaced" placeholder="123">
                            </div>
                            <div class="col-12">
                                <label>Name on Card</label>
                                <input type="text" name="card_name" class="input-jaced" placeholder="Jane Doe">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT: Summary --}}
            <div class="col-12 col-lg-4">
                <div class="summary-card">
                    <h2 class="font-serif-jaced text-jaced-dark mb-4" style="font-size: 1.5rem; font-weight: 400;">Order Summary</h2>

                    <div class="d-flex justify-content-between mb-2" style="font-size: 14px;">
                        <span class="text-jaced-muted">Subtotal</span>
                        <span class="text-jaced-dark fw-medium">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2" style="font-size: 14px;">
                        <span class="text-jaced-muted">White Glove Delivery</span>
                        <span class="text-jaced-dark fw-medium">${{ number_format($shipping, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2" style="font-size: 14px;">
                        <span class="text-jaced-muted">Estimated Tax</span>
                        <span class="text-jaced-dark fw-medium">${{ number_format($tax, 2) }}</span>
                    </div>

                    <hr class="divider-jaced my-4">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-semibold text-jaced-dark" style="font-size: 16px;">Total</span>
                        <span class="font-serif-jaced text-jaced-sage" style="font-size: 2rem;">${{ number_format($total, 2) }}</span>
                    </div>

                    <button type="button" class="btn-jaced">
                        Place Order
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </button>

                    <div class="d-flex align-items-center justify-content-center gap-2 mt-3 text-jaced-muted" style="font-size: 12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <span>Secure encrypted checkout</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection