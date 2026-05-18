@extends('base.base')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
@endpush

@section('content')

<form action="{{ route('checkout.store') }}" method="POST">
    @csrf

    <div class="jaced-page">
        <div style="max-width: 1280px; margin: 0 auto;">

            <h1 class="fw-bold text-jaced-dark mb-5" style="font-size: 2.8rem; font-weight: 400; letter-spacing: 0.05em;">Checkout</h1>

            <div class="row g-4 align-items-start">

                {{-- LEFT SIDE --}}
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

                    {{-- Shipping Address (LOGIKA ALA SHOPEE) --}}
                    <div class="mb-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="fw-bold text-jaced-sage mb-0" style="font-size: 1.5rem; font-weight: 400;">Shipping Address</h2>
                            
                            {{-- Tombol Ubah Alamat muncul jika user terdeteksi sudah punya data alamat tersimpan --}}
                            @if(isset($savedAddresses) && $savedAddresses->isNotEmpty())
                                <button type="button" class="btn btn-sm btn-outline-dark" style="font-family: 'Lexend', sans-serif; font-size: 0.85rem;" data-bs-toggle="modal" data-bs-target="#changeAddressModal">
                                    Ubah Alamat
                                </button>
                            @endif
                        </div>

                        {{-- KONDISI 1: JIKA USER SUDAH PUNYA ALAMAT TERSEDIA --}}
                        @if(isset($defaultAddress) && $defaultAddress)
                            <div class="jaced-card p-4" style="border: 1px solid #d1cbbf; background-color: #faf9f6;">
                                <div class="d-flex align-items-start gap-2 mb-2">
                                    <span class="badge bg-secondary text-uppercase" style="font-size: 10px; font-weight: 500; letter-spacing: 0.05em;">Utama</span>
                                    <div class="fw-bold text-jaced-dark" style="font-size: 1.1rem;">{{ $defaultAddress->first_name }} {{ $defaultAddress->last_name }}</div>
                                </div>
                                <div class="text-jaced-dark mb-1">{{ $defaultAddress->street }}</div>
                                <div class="text-jaced-muted" style="font-size: 14px;">
                                    {{ $defaultAddress->city_name }}, {{ $defaultAddress->province_name }}, {{ $defaultAddress->zip }}
                                </div>
                                
                                {{-- Input hidden untuk mengirim ID alamat terpilih ke controller --}}
                                <input type="hidden" name="address_id" value="{{ $defaultAddress->id }}">
                            </div>

                        {{-- KONDISI 2: JIKA BELUM ADA DATA ALAMAT (TAMPILKAN FORM KOSONG SEPERTI SEMULA) --}}
                        @else
                            <div class="jaced-card p-4">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label>First Name</label>
                                        <input type="text" name="first_name" class="input-jaced" placeholder="Jane" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label>Last Name</label>
                                        <input type="text" name="last_name" class="input-jaced" placeholder="Doe" required>
                                    </div>
                                    <div class="col-12">
                                        <label>Street Address</label>
                                        <input type="text" name="street" class="input-jaced" placeholder="123 Artisan Way" required>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label>Provinsi</label>
                                        <select name="province" id="provinceSelect" class="input-jaced" onchange="loadCities(this.value)" required>
                                            <option value="">Pilih Provinsi</option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->code }}">{{ $province->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label>Kota / Kabupaten</label>
                                        <select name="city" id="citySelect" class="input-jaced" disabled required>
                                            <option value="">Pilih Kota</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label>ZIP Code</label>
                                        <input type="text" name="zip" class="input-jaced" placeholder="10001" required>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>

                {{-- RIGHT SIDE: Summary --}}
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

                            <select name="payment_method" class="input-jaced" id="paymentMethod" onchange="handlePaymentChange(this.value)" required>
                                <option value="">Choose Payment Method</option>
                                @foreach ($paymentMethods as $method)
                                    <option value="{{ $method['value'] }}">{{ $method['label'] }}</option>
                                @endforeach
                            </select>

                            {{-- Dropdown bank --}}
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

                        <button type="submit" class="btn-jaced">
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
</form>
@endsection

@push('scripts')
    <script>
        function handlePaymentChange(value) {
            const bankDropdown = document.getElementById('bankDropdown');
            bankDropdown.style.display = value === 'virtual_account' ? 'block' : 'none';
        }

        function loadCities(provinceCode) {
            const citySelect = document.getElementById('citySelect');
            citySelect.innerHTML = '<option value="">Pilih Kota</option>';
            citySelect.disabled = true;

            if (!provinceCode) return;

            fetch(`/api/cities?province_code=${provinceCode}`)
                .then(res => res.json())
                .then(cities => {
                    cities.forEach(c => {
                        citySelect.innerHTML += `<option value="${c.code}">${c.name}</option>`;
                    });
                    citySelect.disabled = false;
                })
                .catch(() => {
                    citySelect.innerHTML = '<option value="">Gagal load kota</option>';
                });
        }
    </script>
@endpush