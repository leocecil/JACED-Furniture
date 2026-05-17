@extends('base.base')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
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
@endphp --}}

<div class="jaced-page">
    <div style="max-width: 1280px; margin: 0 auto;">

        <h1 class="fw-bold text-jaced-dark mb-5" style="font-size: 2.8rem; font-weight: 400; letter-spacing: 0.05em;">Checkout</h1>

        <div class="row g-4 align-items-start">

            {{-- LEFT --}}
            <div class="col-12 col-lg-8">

                {{-- Review Order --}}
                <div class="mb-5">
                    <h2 class="fw-bold text-jaced-sage mb-4" style="font-size: 1.5rem; font-weight: 400;">Review Order</h2>
                    <div class="order-items-wrapper">
                        <div class="order-items-scroll">
                            @forelse ($items as $item)
                                <div class="jaced-item-card">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-jaced-dark">{{ $item['name'] }}</div>
                                        <div class="text-jaced-muted">{{ $item['variant'] }}</div>
                                        <div class="text-jaced-muted">Qty: {{ $item['qty'] }}</div>
                                    </div>
                                    <div class="fw-semibold text-jaced-dark">Rp {{ number_format($item['price'], 2) }}</div>
                                </div>
                            @empty
                                <p class="text-jaced-muted">Keranjang kamu kosong.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Shipping Address --}}
                <div class="mb-5">
                    <h2 class="fw-bold text-jaced-sage mb-4" style="font-size: 1.5rem; font-weight: 400;">Shipping Address</h2>
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
                                <label>Provinsi</label>
                                <select name="province" id="provinceSelect" class="input-jaced" onchange="loadCities(this.value)">
                                    <option value="">Pilih Provinsi</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province['code'] }}">{{ $province['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label>Kota / Kabupaten</label>
                                <select name="city" id="citySelect" class="input-jaced" disabled>
                                    <option value="">Pilih Kota</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label>ZIP Code</label>
                                <input type="text" name="zip" class="input-jaced" placeholder="10001">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT: Summary --}}
            <div class="col-12 col-lg-4">
                <div class="summary-card">
                    <h2 class="fw-bold text-jaced-dark mb-4" style="font-size: 1.5rem; font-weight: 400;">Order Summary</h2>

                    <div class="d-flex justify-content-between mb-2" style="font-size: 14px;">
                        <span class="text-jaced-muted">Subtotal</span>
                        <span class="text-jaced-dark fw-medium">Rp {{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2" style="font-size: 14px;">
                        <span class="text-jaced-muted">Delivery Fee</span>
                        <span class="text-jaced-dark fw-medium">Rp {{ number_format($shipping, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2" style="font-size: 14px;">
                        <span class="text-jaced-muted">Service Tax</span>
                        <span class="text-jaced-dark fw-medium">Rp {{ number_format($tax, 2) }}</span>
                    </div>

                    {{-- Payment Method --}}
                    <div class="payment-section">
                        <span class="field-label" style="margin-bottom: 8px;">Payment Method</span>

                        <select name="payment_method" class="input-jaced" id="paymentMethod" onchange="handlePaymentChange(this.value)">
                            <option value="">Choose Payment Method</option>
                            @foreach ($paymentMethods as $method)
                                <option value="{{ $method['value'] }}">{{ $method['label'] }}</option>
                            @endforeach
                        </select>

                        {{-- Dropdown bank, muncul kalau pilih Virtual Account --}}
                        <div id="bankDropdown" style="display: none; margin-top: 10px;">
                            <span class="field-label" style="margin-bottom: 8px;">Pilih Bank</span>
                            <select name="bank" class="input-jaced">
                                <option value="">Choose Bank</option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank['value'] }}">{{ $bank['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    

                    <hr class="divider-jaced my-4">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold text-jaced-dark" style="font-size: 24px;">Total</span>
                        <span class="fw-bold text-jaced-sage" style="font-size: 30px;">Rp {{ number_format($total, 2) }}</span>
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

@push('scripts')
    <script>
        function handlePaymentChange(value) {
            const bankDropdown = document.getElementById('bankDropdown');
            bankDropdown.style.display = value === 'virtual_account' ? 'block' : 'none';
        }

        // Load provinsi saat halaman dibuka
        function loadCities(provinceCode) {
        const citySelect = document.getElementById('citySelect');
        citySelect.innerHTML = '<option value="">Pilih Kota</option>';
        citySelect.disabled = true;

        if (!provinceCode) return;

        fetch(`https://dev.farizdotid.com/api/daerahindonesia/kota?id_provinsi=${provinceCode}`)
            .then(res => res.json())
            .then(cities => {
                cities.forEach(c => {
                    citySelect.innerHTML += `<option value="${c.id}">${c.name}</option>`;
                });
                citySelect.disabled = false;
            })
            .catch(() => {
                citySelect.innerHTML = '<option value="">Gagal load kota</option>';
            });

        }
    </script>
@endpush