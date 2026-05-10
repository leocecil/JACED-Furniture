{{-- TEMPORARY WRAPPER - hapus ini semua kalau base layout udah jadi --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout — Jaced Furniture</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --jaced-cream: #EDE8E3;
            --jaced-card: #FAF6F1;
            --jaced-brown-dark: #272E1D;
            --jaced-brown: #5A4D47;
            --jaced-caramel: #C99A6B;
            --jaced-sage: #5F7568;
            --jaced-input: #DDD6CE;
            --jaced-muted: #8A8278;
        }
        body { font-family: 'Inter', sans-serif; background-color: var(--jaced-cream); color: var(--jaced-brown-dark); }
        .font-serif-jaced { font-family: 'DM Serif Display', serif; }
        .bg-jaced-cream  { background-color: var(--jaced-cream); }
        .bg-jaced-card   { background-color: var(--jaced-card); }
        .text-jaced-dark { color: var(--jaced-brown-dark); }
        .text-jaced-muted{ color: var(--jaced-muted); }
        .text-jaced-sage { color: var(--jaced-sage); }
        .border-jaced    { border-color: var(--jaced-input); }
        .input-jaced {
            background-color: var(--jaced-input);
            border: none; border-radius: 8px; padding: 12px 16px;
            font-size: 14px; color: var(--jaced-brown-dark); width: 100%; outline: none; transition: all 0.2s;
        }
        .input-jaced:focus { box-shadow: 0 0 0 2px var(--jaced-sage); }
        .input-jaced::placeholder { color: var(--jaced-muted); }
        .btn-jaced {
            background-color: var(--jaced-sage); color: white; padding: 16px 32px;
            border-radius: 8px; font-weight: 500; width: 100%;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: all 0.2s; border: none; cursor: pointer;
        }
        .btn-jaced:hover { background-color: #4a5d52; }
    </style>
</head>
<body>
{{-- END TEMPORARY WRAPPER --}}


{{-- @extends('base.base') --}}

{{-- @push('styles') ... @endpush --}}
{{-- Nanti styles di atas dipindah ke sini kalau base udah ready --}}

{{-- @section('content') --}}

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

    $subtotal = array_sum(array_map(function($i) { return $i['price'] * $i['qty']; }, $items));
    $shipping = 150.0;
    $tax      = $subtotal * 0.0836;
    $total    = $subtotal + $shipping + $tax;
@endphp

<main class="bg-jaced-cream min-h-screen py-12 px-6 md:px-12 lg:px-20">
    <div class="max-w-7xl mx-auto">

        <h1 class="font-serif-jaced text-4xl md:text-5xl text-jaced-dark mb-12">Checkout</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            <div class="lg:col-span-2 space-y-12">

                <section>
                    <h2 class="font-serif-jaced text-2xl text-jaced-sage mb-5">Review Order</h2>
                    <div class="space-y-3">
                        @foreach ($items as $item)
                            <div class="bg-jaced-card rounded-xl p-4 flex items-center gap-4">
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-20 h-20 object-cover rounded-lg">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-jaced-dark">{{ $item['name'] }}</h3>
                                    <p class="text-sm text-jaced-muted">{{ $item['variant'] }}</p>
                                    <p class="text-sm text-jaced-muted">Qty: {{ $item['qty'] }}</p>
                                </div>
                                <div class="font-semibold text-jaced-dark">
                                    ${{ number_format($item['price'], 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section>
                    <h2 class="font-serif-jaced text-2xl text-jaced-sage mb-5">Shipping Address</h2>
                    <div class="bg-jaced-card rounded-xl p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-jaced-dark mb-2">First Name</label>
                                <input type="text" name="first_name" class="input-jaced" placeholder="Jane">
                            </div>
                            <div>
                                <label class="block text-sm text-jaced-dark mb-2">Last Name</label>
                                <input type="text" name="last_name" class="input-jaced" placeholder="Doe">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm text-jaced-dark mb-2">Street Address</label>
                            <input type="text" name="street" class="input-jaced" placeholder="123 Artisan Way">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm text-jaced-dark mb-2">City</label>
                                <input type="text" name="city" class="input-jaced" placeholder="New York">
                            </div>
                            <div>
                                <label class="block text-sm text-jaced-dark mb-2">State</label>
                                <input type="text" name="state" class="input-jaced" placeholder="NY">
                            </div>
                            <div>
                                <label class="block text-sm text-jaced-dark mb-2">ZIP Code</label>
                                <input type="text" name="zip" class="input-jaced" placeholder="10001">
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="font-serif-jaced text-2xl text-jaced-sage mb-5">Payment Method</h2>
                    <div class="bg-jaced-card rounded-xl p-6 space-y-4">
                        <div>
                            <label class="block text-sm text-jaced-dark mb-2">Card Number</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-jaced-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                                </span>
                                <input type="text" name="card_number" class="input-jaced pl-12" placeholder="0000 0000 0000 0000">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-jaced-dark mb-2">Expiration Date</label>
                                <input type="text" name="exp" class="input-jaced" placeholder="MM/YY">
                            </div>
                            <div>
                                <label class="block text-sm text-jaced-dark mb-2">CVC</label>
                                <input type="text" name="cvc" class="input-jaced" placeholder="123">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm text-jaced-dark mb-2">Name on Card</label>
                            <input type="text" name="card_name" class="input-jaced" placeholder="Jane Doe">
                        </div>
                    </div>
                </section>

            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl p-6 sticky top-6 shadow-sm">
                    <h2 class="font-serif-jaced text-2xl text-jaced-dark mb-5">Order Summary</h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-jaced-muted">Subtotal</span>
                            <span class="text-jaced-dark font-medium">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-jaced-muted">White Glove Delivery</span>
                            <span class="text-jaced-dark font-medium">${{ number_format($shipping, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-jaced-muted">Estimated Tax</span>
                            <span class="text-jaced-dark font-medium">${{ number_format($tax, 2) }}</span>
                        </div>
                    </div>
                    <hr class="my-5 border-jaced">
                    <div class="flex justify-between items-center mb-6">
                        <span class="font-semibold text-jaced-dark text-lg">Total</span>
                        <span class="font-serif-jaced text-3xl text-jaced-sage">${{ number_format($total, 2) }}</span>
                    </div>
                    <button type="button" class="btn-jaced">
                        Place Order
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </button>
                    <div class="flex items-center justify-center gap-2 mt-4 text-xs text-jaced-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <span>Secure encrypted checkout</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

{{-- @endsection --}}


{{-- TEMPORARY WRAPPER - hapus ini kalau base layout udah jadi --}}
</body>
</html>
{{-- END TEMPORARY WRAPPER --}}