@extends('base.base')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
<style>
    /* Styling Alamat Ringkas ala Shopee */
    .shopee-address-wrapper {
        border: 1px solid #e2dcd0;
        border-radius: 8px;
        background-color: #fff;
        overflow: hidden;
    }
    .shopee-address-item {
        position: relative;
        padding: 16px 20px;
        border-bottom: 1px solid #f3f0e9;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }
    .shopee-address-item:last-child {
        border-bottom: none;
    }
    .shopee-address-item:hover {
        background-color: #faf9f6;
    }
    .shopee-address-item:has(.address-selector-radio:checked) {
        background-color: #f6f5f0;
    }
    .shopee-address-item:has(.address-selector-radio:checked)::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background-color: #5c695d;
    }
    .address-selector-radio {
        width: 18px;
        height: 18px;
        accent-color: #5c695d;
        cursor: pointer;
    }
    .btn-add-address-shopee {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 12px;
        background-color: #fff;
        border: 1px dashed #5c695d;
        color: #5c695d;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .btn-add-address-shopee:hover {
        background-color: #f3f1eb;
    }
    /* Tombol Ubah / Edit teks */
    .btn-edit-address {
        background: none;
        border: none;
        color: #8c7e6c;
        font-size: 13px;
        text-decoration: none;
        padding: 0 4px;
    }
    .btn-edit-address:hover {
        color: #5c695d;
        text-decoration: underline;
    }
</style>
@endpush

@section('content')

<form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
    @csrf

    <div class="jaced-page">
        <div style="max-width: 1280px; margin: 0 auto;">

            <h1 class="fw-bold text-jaced-dark mb-4" style="font-size: 2.2rem; font-weight: 400; letter-spacing: 0.04em;">Checkout</h1>

            <div class="row g-4 align-items-start">

                {{-- LEFT SIDE --}}
                <div class="col-12 col-lg-8">

                    {{-- Review Order --}}
                    <div class="mb-4">
                        <h2 class="fw-bold text-jaced-sage mb-3" style="font-size: 1.3rem; font-weight: 400;">Review Order</h2>
                        <div class="order-items-wrapper">
                            <div class="order-items-scroll">
                                @forelse ($items as $item)
                                    <div class="jaced-item-card py-2">
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 6px;">
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold text-jaced-dark" style="font-size: 14px;">{{ $item['name'] }}</div>
                                            <div class="text-jaced-muted small">{{ $item['variant'] }} • Qty: {{ $item['qty'] }}</div>
                                        </div>
                                        <div class="fw-semibold text-jaced-dark" style="font-size: 14px;">Rp {{ number_format($item['price'], 2) }}</div>
                                    </div>
                                @empty
                                    <p class="text-jaced-muted">Your cart is empty.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Shipping Address Selection Section --}}
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="fw-bold text-jaced-sage m-0" style="font-size: 1.3rem; font-weight: 400;">Delivery Address</h2>
                        </div>
                        
                        @if(isset($savedAddresses) && $savedAddresses->isNotEmpty())
                            <div class="shopee-address-wrapper mb-3">
                                @foreach ($savedAddresses as $addr)
                                    <div class="shopee-address-item d-flex align-items-start gap-3 w-100 m-0">
                                        <div class="pt-1">
                                            <input type="radio" name="address_id" value="{{ $addr->id }}" class="address-selector-radio" 
                                                   data-is-new="false"
                                                   data-city="{{ $addr->city_name }}"
                                                   data-village="{{ $addr->village_name }}"
                                                   {{ 
                                                        (isset($defaultAddress) 
                                                            ? $defaultAddress->id == $addr->id 
                                                            : $loop->first
                                                        ) ? 'checked' : '' 
                                                    }}>
                                        </div>
                                        
                                        {{-- Sisi Kiri: Informasi Alamat --}}
                                        <div class="flex-grow-1" onclick="this.parentElement.querySelector('.address-selector-radio').click()">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <span class="fw-bold text-jaced-dark" style="font-size: 14px;">{{ $addr->receiver_name }}</span>
                                                <span class="text-jaced-muted small">|</span>
                                                <span class="text-jaced-muted small">{{ $addr->receiver_phone }}</span>
                                                
                                                @if(isset($defaultAddress) && $defaultAddress->id == $addr->id)
                                                    <span class="badge bg-transparent text-danger border border-danger ms-2" style="font-size: 9px; padding: 2px 4px;">Utama</span>
                                                @endif
                                            </div>
                                            <div class="text-jaced-dark small mb-1">{{ $addr->address_line1 }}</div>
                                            <div class="text-jaced-muted tiny" style="font-size: 12px;">
                                                {{ $addr->village_name }}, {{ $addr->district_name }}, {{ $addr->city_name }}, {{ $addr->province_name }}, {{ $addr->postal_code }}
                                            </div>
                                        </div>

                                        {{-- Sisi Kanan: Tombol Ubah ala Shopee --}}
                                        <div class="pt-1">
                                            <button type="button" class="btn-edit-address" 
                                                    onclick="openEditAddressModal({{ json_encode($addr) }})">
                                                Ubah
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" class="btn-add-address-shopee" onclick="openAddAddressModal()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 14 14"><path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"/></svg>
                                Add New Address
                            </button>
                        @else
                            <div class="p-4 text-center border rounded bg-light">
                                <p class="text-jaced-muted small">You haven't saved any addresses yet.</p>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="openAddAddressModal()">
                                    + Create First Address
                                </button>
                            </div>
                        @endif

                        {{-- SELEKSI KURIR --}}
                        <div class="jaced-card p-3 mt-4" style="border: 1px solid #d1cbbf;">
                            <label class="fw-bold text-jaced-dark mb-2" style="font-size: 14px;">Delivery Method</label>
                            <div id="shippingOptions" style="max-height: 200px; overflow-y: auto;">
                                <p class="text-jaced-muted small">Please select an address first.</p>
                            </div>
                            <input type="hidden" name="delivery_fee" id="deliveryFeeInput" value="0">
                        </div>
                    </div>

                </div>

                {{-- RIGHT SIDE: Summary --}}
                <div class="col-12 col-lg-4">
                    <div class="summary-card p-3" style="background-color: #faf9f6; border: 1px solid #e2dcd0; border-radius: 8px;">
                        <h2 class="fw-bold text-jaced-dark mb-3" style="font-size: 1.3rem; font-weight: 400;">Order Summary</h2>

                        <div class="d-flex justify-content-between mb-2" style="font-size: 13px;">
                            <span class="text-jaced-muted">Subtotal</span>
                            {{-- Tambahkan id="summary-subtotal" dan data-raw agar JS bisa membaca angka aslinya --}}
                            <span class="text-jaced-dark fw-medium" id="summary-subtotal" data-raw="{{ $subtotal }}">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2" style="font-size: 13px;">
                            <span class="text-jaced-muted">Shipping Fee</span>
                            <span class="text-jaced-dark fw-medium" id="deliveryFeeDisplay">Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2" style="font-size: 13px;">
                            <span class="text-jaced-muted">Service Tax</span>
                            {{-- Tambahkan id="summary-tax" dan data-raw --}}
                            <span class="text-jaced-dark fw-medium" id="summary-tax" data-raw="{{ $tax }}">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                        </div>

                        {{-- BARIS DISKON VOUCHER (ZONA POTONGAN) --}}
                        <div class="d-flex justify-content-between text-danger d-none" id="row-discount-delivery">
                            <span>Total Discount Shipping</span>
                            <span class="fw-bold" id="summary-discount-delivery">-Rp 0</span>
                        </div>
                        
                        <div class="d-flex justify-content-between text-danger d-none" id="row-discount-product">
                            <span>Discount Voucher</span>
                            <span class="fw-bold" id="summary-discount-product">-Rp 0</span>
                        </div>

                        {{-- BARIS TIER DISCOUNT --}}
                        @if($tierDiscountAmount > 0)
                        <div class="d-flex justify-content-between mb-2" style="font-size: 13px;">
                            <span class="text-jaced-muted">
                                Member Discount
                                @if($userStage)
                                    <span style="font-size: 11px; color: var(--jaced-sage);">({{ $userStage->discount_percentage }}% · {{ $userStage->name }})</span>
                                @endif
                            </span>
                            <span class="fw-medium" style="color: var(--jaced-sage);">- Rp {{ number_format($tierDiscountAmount, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        {{-- COMPONENT PILIH VOUCHER ALA SHOPEE --}}
                        {{-- Perbaikan typo !important --}}
                        <div class="my-3 p-2 border rounded d-flex justify-content-between align-items-center bg-white" 
                            style="cursor: pointer; border-color: #e2dcd0 !important;" 
                            data-bs-toggle="modal" data-bs-target="#voucherModal">
                            <div class="d-flex align-items-center gap-2">
                                {{-- Icon Tiket Voucher --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#5c695d" class="bi bi-ticket-perforated" viewBox="0 0 16 16">
                                    <path d="M4.5 5.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5Zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5Zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5Z"/>
                                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5ZM1 4v3.5a.5.5 0 0 0 .5.5.5.5 0 0 1 0 1 .5.5 0 0 0-.5.5V14a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-3.5a.5.5 0 0 0-.5-.5.5.5 0 0 1 0-1 .5.5 0 0 0 .5-.5V4a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1Z"/>
                                </svg>
                                <span class="fw-medium text-jaced-dark mb-0" id="selectedVoucherText" style="font-size: 13px;">Voucher Jaced</span>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <span class="badge bg-danger d-none" id="voucherActiveBadge" style="font-size: 10px;">1 Applied</span>
                                <span class="text-muted small">❯</span>
                            </div>
                        </div>

                        {{-- Payment Method --}}
                        <div class="payment-section mt-3 pt-3 border-top">
                            <span class="field-label small mb-2 d-block fw-medium text-jaced-dark">Payment Method</span>
                            <select name="payment_method" class="form-select form-select-sm" id="paymentMethod" onchange="handlePaymentChange(this.value)" required style="border-color: #d1cbbf;">
                                <option value="">Choose Payment Method</option>
                                @foreach ($paymentMethods as $method)
                                    <option value="{{ $method['value'] }}">{{ $method['label'] }}</option>
                                @endforeach
                            </select>

                            <div id="bankDropdown" style="display: none; margin-top: 10px;">
                                <span class="field-label small mb-1 d-block text-jaced-muted">Pilih Bank</span>
                                <select name="bank" class="form-select form-select-sm" style="border-color: #d1cbbf;">
                                    <option value="">Pilih Bank</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank['value'] }}">{{ $bank['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="my-3" style="border-color: #e2dcd0;">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold text-jaced-dark" style="font-size: 16px;">Grand Total</span>
                            {{-- Bersihkan desimal (.00) dari backend agar sinkron saat JS melakukan kalkulasi matematika --}}
                            <span class="fw-bold text-jaced-sage" style="font-size: 22px;" id="totalDisplay" data-raw="{{ $total }}">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        {{-- Hidden Input untuk mengirim ID Voucher yang dipilih ke Controller backend --}}
                        <input type="hidden" name="applied_voucher_id" id="applied-voucher-id" value="">
                        <input type="hidden" name="discount_amount" id="applied-discount-amount" value="0">

                        <button type="submit" class="btn-jaced w-100 py-2" style="font-size: 15px;">
                            Make Order
                        </button>
                    </div>
                </div>


                {{-- MODAL POP-UP PILIH VOUCHER (ALA SHOPEE) --}}
                <div class="modal fade" id="voucherModal" tabindex="-1" aria-labelledby="voucherModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-md">
                        <div class="modal-content" style="border-radius: 12px;">
                            <div class="modal-header" style="background-color: #faf9f6; border-bottom: 1px solid #f3f0e9;">
                                <h5 class="modal-title fw-bold text-jaced-dark" id="voucherModalLabel" style="font-size: 1.1rem;">Pilih Voucher Jaced</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-3" style="background-color: #fcfbfa; max-height: 400px; overflow-y: auto;">
                                
                                @if(isset($myVouchers) && $myVouchers->isNotEmpty())
                                    <div class="d-flex flex-column gap-3">
                                        @foreach($myVouchers as $v)
                                            @php
                                                $isShipping = $v->used_for === 'delivery';
                                                $themeColor = $isShipping ? '#5c695d' : '#bd654e';
                                                $themeBg = $isShipping ? '#f1f4f2' : '#fcf5f3';
                                            @endphp

                                            <label class="d-flex align-items-center p-3 bg-white border rounded shadow-sm position-relative m-0" 
                                                style="cursor: pointer; border-color: #e2dcd0 !important;">
                                                
                                                <div class="pe-3 border-end d-flex flex-column justify-content-center text-center align-items-center" 
                                                    style="width: 105px; border-color: #f3f0e9 !important;">
                                                    <span class="fw-bold px-2 py-1 rounded" style="font-size: 11px; color: {{ $themeColor }}; background: {{ $themeBg }};">
                                                        {{ $isShipping ? 'Gratis Ongkir' : 'Diskon' }}
                                                    </span>
                                                    @if(!$isShipping && isset($v->discount_percentage))
                                                        <span class="fw-bold mt-1 text-muted" style="font-size: 14px;">{{ $v->discount_percentage }}% Off</span>
                                                    @endif
                                                </div>

                                                <div class="ps-3 flex-grow-1">
                                                    <h6 class="fw-bold text-jaced-dark mb-1" style="font-size: 13px;">{{ $v->name }}</h6>
                                                    <p class="text-muted tiny mb-1" style="font-size: 11px; line-height: 1.3;">{{ $v->description }}</p>
                                                    <span class="text-danger tiny d-block" style="font-size: 10px;">
                                                        Berlaku s.d {{ \Carbon\Carbon::parse($v->expiry_date)->format('d.m.Y') }}
                                                    </span>
                                                </div>

                                                <div class="ps-2">
                                                    {{-- Menggunakan data-discount dari objek $v secara aman --}}
                                                    <input type="radio" name="voucher_select_radio" value="{{ $v->id }}" class="voucher-radio-input"
                                                        data-name="{{ $v->name }}"
                                                        data-used-for="{{ $v->used_for }}"
                                                        data-discount="{{ $v->max_discount ?? 0 }}"
                                                        data-discount-percentage="{{ $v->discount_percentage ?? 0 }}"
                                                        style="accent-color: {{ $themeColor }}; width: 18px; height: 18px;">
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <p class="text-muted small m-0">Kamu belum memiliki voucher yang siap digunakan.</p>
                                    </div>
                                @endif

                            </div>
                            <div class="modal-footer" style="background-color: #faf9f6; border-top: 1px solid #f3f0e9;">
                                <div id="voucherPreview" class="w-100 mb-2 d-none">
                                    <div class="p-2 rounded" style="background-color: #f1f4f2; font-size: 12px;">
                                        <span id="voucherPreviewText"></span>
                                    </div>
                                </div>
                                <button type="button" id="btn-apply-voucher" class="btn btn-sm text-white" style="background-color: #5c695d;" data-bs-dismiss="modal">Oke</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{-- Wadah input hidden --}}
    <div id="hiddenAddressMutationContainer"></div>
</form>

{{-- MODAL POP-UP TAMBAH / EDIT ALAMAT --}}
<div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header" style="border-bottom: 1px solid #f3f0e9; background-color: #faf9f6;">
                <h5 class="modal-title fw-bold text-jaced-dark" id="addressModalLabel">Detail Alamat</h5>
                <button type="button" class="btn-close" data-bs-shadow="none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                {{-- Input Hidden Penanda Mode: 'add' atau 'edit' --}}
                <input type="hidden" id="modal_action_mode" value="add">
                <input type="hidden" id="modal_address_id" value="">

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label small text-jaced-dark fw-medium">Nama Penerima</label>
                        <input type="text" id="modal_receiver_name" class="form-control form-control-sm" placeholder="Nama Lengkap">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label small text-jaced-dark fw-medium">Nomor Telepon</label>
                        <input type="text" id="modal_receiver_phone" class="form-control form-control-sm" placeholder="Contoh: 08123456789">
                    </div>
                    <div class="col-12">
                        <label class="form-label small text-jaced-dark fw-medium">Alamat Lengkap (Jalan, No Rumah, Blok)</label>
                        <input type="text" id="modal_address_line1" class="form-control form-control-sm" placeholder="Jalan Raya Jaced No 1">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label small text-jaced-dark fw-medium">Provinsi</label>
                        <select id="modalProvinceSelect" class="form-select form-select-sm">
                            <option value="">Pilih Provinsi</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province->code }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label small text-jaced-dark fw-medium">Kota / Kabupaten</label>
                        <select id="modalCitySelect" class="form-select form-select-sm" onchange="loadDistricts(this.value)" disabled>
                            <option value="">Pilih Kota</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label small text-jaced-dark fw-medium">Kecamatan</label>
                        <select id="modalDistrictSelect" class="form-select form-select-sm" onchange="loadVillages(this.value)" disabled>
                            <option value="">Pilih Kecamatan</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label small text-jaced-dark fw-medium">Kelurahan / Desa</label>
                        <select id="modalVillageSelect" class="form-select form-select-sm" disabled>
                            <option value="">Pilih Kelurahan</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label small text-jaced-dark fw-medium">Kode Pos</label>
                        <div id="modal_postal_wrapper">
                            <input type="text" id="modal_postal_code" name="postal_code" 
                                class="form-control form-control-sm" placeholder="10001">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #f3f0e9; background-color: #faf9f6;">
                <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm text-white" style="background-color: #5c695d;" onclick="saveModalAddress()">Simpan Alamat</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        let bsModal;
        let provinceTS; // ← pindah ke global scope
        let currentDeliveryFee = 0;
        let voucherApplied = false;

        // Toggle radio voucher - klik lagi untuk untick
        document.addEventListener('DOMContentLoaded', function () {
            let lastChecked = null;

            // Toggle radio voucher
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('voucher-radio-input')) {
                    if (e.target === lastChecked) {
                        e.target.checked = false;
                        lastChecked = null;
                        document.getElementById('voucherPreview').classList.add('d-none');
                    } else {
                        lastChecked = e.target;
                        const usedFor  = e.target.getAttribute('data-used-for');
                        const name     = e.target.getAttribute('data-name');
                        const discount = e.target.getAttribute('data-discount');
                        const preview  = document.getElementById('voucherPreview');
                        const text     = document.getElementById('voucherPreviewText');

                        if (usedFor === 'delivery') {
                            text.innerHTML = `✅ <strong>${name}</strong> — Gratis ongkir hingga Rp ${parseFloat(discount).toLocaleString('id-ID')}`;
                            preview.querySelector('div').style.backgroundColor = '#f1f4f2';
                            preview.querySelector('div').style.color = '#5c695d';
                        } else {
                            text.innerHTML = `✅ <strong>${name}</strong> — Diskon hingga Rp ${parseFloat(discount).toLocaleString('id-ID')}`;
                            preview.querySelector('div').style.backgroundColor = '#fcf5f3';
                            preview.querySelector('div').style.color = '#bd654e';
                        }
                        preview.classList.remove('d-none');
                    }
                }
            });

            // Modal & address
            bsModal = new bootstrap.Modal(document.getElementById('addressModal'));
            provinceTS = new TomSelect('#modalProvinceSelect', {
                allowEmptyOption: false,
                selectOnTab: true,
                closeAfterSelect: true,
                maxOptions: null,
                onItemAdd: function(value) {
                    this.blur(); // ← hilangkan cursor aneh setelah pilih
                    loadCities(value, true);
                },
                onChange: function(value) {
                    if (value) loadCities(value, false); // ← fallback untuk klik mouse
                }
            });
            const addressRadios = document.querySelectorAll('.address-selector-radio');
            addressRadios.forEach(radio => {
                radio.addEventListener('change', handleAddressChange);
            });
            handleAddressChange();

            const btnApplyVoucher = document.getElementById('btn-apply-voucher');
            if (btnApplyVoucher) {
                btnApplyVoucher.addEventListener('click', function() {
                    applyVoucherVisual();
                    
                    let selectedVoucher = document.querySelector('.voucher-radio-input:checked');
                    let container = document.getElementById('active-voucher-container');
                    let placeholderText = document.getElementById('placeholder-voucher-text');
                
                    if (container) {
                        const oldBadge = container.querySelector('.badge-voucher-active');
                        if (oldBadge) oldBadge.remove();
                    }
                    
                    if (placeholderText) placeholderText.style.display = 'inline';

                    if (selectedVoucher && selectedVoucher.value !== "") {
                        let voucherName = selectedVoucher.getAttribute('data-name');
                        let usedFor = selectedVoucher.getAttribute('data-used-for');

                        if (placeholderText) placeholderText.style.display = 'none';

                        let badgeHtml = '';
                        if (usedFor === 'delivery') {
                            badgeHtml = `<span class="badge-voucher-active px-2 py-1 rounded text-white fw-bold" 
                                        style="background-color: #5c695d; font-size: 11px; border: 1px solid #4a544b;">
                                            Gratis Ongkir
                                        </span>`;
                        } else {
                            badgeHtml = `<span class="badge-voucher-active px-2 py-1 rounded fw-bold" 
                                        style="background-color: #fcf5f3; color: #bd654e; border: 1px solid #bd654e; font-size: 11px;">
                                            ${voucherName}
                                        </span>`;
                        }
                        if (container) container.insertAdjacentHTML('afterbegin', badgeHtml);
                    }
                });
            }

            // Auto-apply pending voucher dari session
            @if(isset($pendingVoucher) && $pendingVoucher)
                window.pendingVoucherData = {
                    id: '{{ $pendingVoucher->id }}',
                    usedFor: '{{ $pendingVoucher->used_for }}',
                    maxDiscount: {{ (float) $pendingVoucher->max_discount }},
                    discountPercentage: {{ (float) $pendingVoucher->discount_percentage }},
                };
                document.getElementById('applied-voucher-id').value = '{{ $pendingVoucher->id }}';
                
                const textDisplay = document.getElementById('selectedVoucherText');
                @if($pendingVoucher->used_for === 'delivery')
                    textDisplay.innerHTML = `
                        <span class="px-2 py-1 rounded fw-bold" style="background-color: #f1f4f2; color: #5c695d; border: 1px solid #5c695d; font-size: 11px;">🚚 Gratis Ongkir</span>
                        <span class="ms-1" style="font-size: 13px;">{{ $pendingVoucher->name }}</span>
                    `;
                @else
                    textDisplay.innerHTML = `
                        <span class="px-2 py-1 rounded fw-bold" style="background-color: #fcf5f3; color: #bd654e; border: 1px solid #bd654e; font-size: 11px;">🏷️ Diskon Produk</span>
                        <span class="ms-1" style="font-size: 13px;">{{ $pendingVoucher->name }}</span>
                    `;
                @endif

                document.getElementById('voucherActiveBadge').classList.remove('d-none');
                voucherApplied = true;
                calculateGrandTotal();

                fetch('{{ route("voucher.clear-session") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
            @endif
        });

        function applyVoucherVisual() {
            const selectedVoucher = document.querySelector('.voucher-radio-input:checked');
            
            if (!selectedVoucher || selectedVoucher.value === "") {
                clearSelectedVoucher();
                return;
            }

            const voucherId   = selectedVoucher.value;
            const voucherName = selectedVoucher.getAttribute('data-name');
            const usedFor     = selectedVoucher.getAttribute('data-used-for');

            document.getElementById('applied-voucher-id').value = voucherId;

            const textDisplay = document.getElementById('selectedVoucherText');
            const activeBadge = document.getElementById('voucherActiveBadge');

            // Warna badge sesuai jenis voucher
            if (usedFor === 'delivery') {
                textDisplay.innerHTML = `
                    <span class="px-2 py-1 rounded fw-bold" 
                        style="background-color: #f1f4f2; color: #5c695d; border: 1px solid #5c695d; font-size: 11px;">
                        🚚 Gratis Ongkir
                    </span>
                    <span class="ms-1" style="font-size: 13px;">${voucherName}</span>
                `;
            } else {
                textDisplay.innerHTML = `
                    <span class="px-2 py-1 rounded fw-bold" 
                        style="background-color: #fcf5f3; color: #bd654e; border: 1px solid #bd654e; font-size: 11px;">
                        🏷️ Diskon Produk
                    </span>
                    <span class="ms-1" style="font-size: 13px;">${voucherName}</span>
                `;
            }

            if(activeBadge) activeBadge.classList.remove('d-none');

            voucherApplied = true;
            calculateGrandTotal();
        }

        function handleAddressChange() {
            const selectedRadio = document.querySelector('.address-selector-radio:checked');
            if (!selectedRadio) return;

            const cityName = selectedRadio.getAttribute('data-city');
            const villageName = selectedRadio.getAttribute('data-village');
            if (cityName && villageName) {
                fetchShippingCost(cityName, villageName);
            }
        }

        function handlePaymentChange(value) {
            const bankDropdown = document.getElementById('bankDropdown');
            if (bankDropdown) {
                bankDropdown.style.display = value === 'virtual_account' ? 'block' : 'none';
            }
        }

        /* Handler Buka Modal Mode: Tambah Alamat Baru */
        function openAddAddressModal() {
            document.getElementById('addressModalLabel').innerText = "Detail Alamat Baru";
            document.getElementById('modal_action_mode').value = "add";
            document.getElementById('modal_address_id').value = "";
            
            // Reset isi inputan form modal
            document.getElementById('modal_receiver_name').value = "";
            document.getElementById('modal_receiver_phone').value = "";
            document.getElementById('modal_address_line1').value = "";
            document.getElementById('modal_postal_code').value = "";
            provinceTS.setValue('', true);
            
            // Destroy TomSelect instances lama sebelum reset HTML
            ['modalCitySelect', 'modalDistrictSelect', 'modalVillageSelect'].forEach(id => {
                if (window[id + '_ts']) {
                    window[id + '_ts'].destroy();
                    window[id + '_ts'] = null;
                }
            });

            document.getElementById('modalCitySelect').innerHTML = '<option value="">Pilih Kota</option>';
            document.getElementById('modalDistrictSelect').innerHTML = '<option value="">Pilih Kecamatan</option>';
            document.getElementById('modalVillageSelect').innerHTML = '<option value="">Pilih Kelurahan</option>';
            document.getElementById('modalCitySelect').disabled = true;
            document.getElementById('modalDistrictSelect').disabled = true;
            document.getElementById('modalVillageSelect').disabled = true;

            bsModal.show();
        }

        /* Handler Buka Modal Mode: EDIT Alamat Lama */
        function openEditAddressModal(addressObj) {
            document.getElementById('addressModalLabel').innerText = "Ubah Alamat Pengiriman";
            document.getElementById('modal_action_mode').value = "edit";
            document.getElementById('modal_address_id').value = addressObj.id;

            // Isi data yang sudah ada
            document.getElementById('modal_receiver_name').value = addressObj.receiver_name;
            document.getElementById('modal_receiver_phone').value = addressObj.receiver_phone;
            document.getElementById('modal_address_line1').value = addressObj.address_line1;
            document.getElementById('modal_postal_code').value = addressObj.postal_code || '';

            // Otomatis set provinsi lama jika ada relasinya/code nya
            if (addressObj.province_code) {
                // Pakai TomSelect API agar UI ikut terupdate
                provinceTS.setValue(addressObj.province_code, true);

                // City
                const citySelect = document.getElementById('modalCitySelect');
                citySelect.innerHTML = `<option value="${addressObj.city_code || ''}">${addressObj.city_name}</option>`;
                citySelect.disabled = false;
                initOrRefreshTS('modalCitySelect', function(value) { loadDistricts(value); });
                if (window['modalCitySelect_ts']) window['modalCitySelect_ts'].setValue(addressObj.city_code || '', true);

                // District
                const distSelect = document.getElementById('modalDistrictSelect');
                distSelect.innerHTML = `<option value="${addressObj.district_code || ''}">${addressObj.district_name}</option>`;
                distSelect.disabled = false;
                initOrRefreshTS('modalDistrictSelect', function(value) { loadVillages(value); });
                if (window['modalDistrictSelect_ts']) window['modalDistrictSelect_ts'].setValue(addressObj.district_code || '', true);

                // Village
                const villSelect = document.getElementById('modalVillageSelect');
                villSelect.innerHTML = `<option value="${addressObj.village_code || ''}">${addressObj.village_name}</option>`;
                villSelect.disabled = false;
                initOrRefreshTS('modalVillageSelect', function(value) {});
                if (window['modalVillageSelect_ts']) window['modalVillageSelect_ts'].setValue(addressObj.village_code || '', true);
            }

            bsModal.show();
        }

        // City, District, Village — karena dynamic (diisi via JS), pakai cara ini
        let cityTS, districtTS, villageTS;

        function initOrRefreshTS(id, onChangeCb) {
            if (window[id + '_ts']) {
                window[id + '_ts'].destroy();
                window[id + '_ts'] = null;
            }
            
            let selectedViaKeyboard = false; // ← tracker lokal per instance
            
            window[id + '_ts'] = new TomSelect('#' + id, {
                allowEmptyOption: false,
                selectOnTab: true,
                closeAfterSelect: true,
                maxOptions: null,
                onItemAdd: function(value) {
                    selectedViaKeyboard = true; // ← Enter/Tab
                    this.blur();
                    if (onChangeCb) onChangeCb(value, true); // ← kirim flag keyboard=true
                },
                onChange: function(value) {
                    if (value && onChangeCb) {
                        onChangeCb(value, selectedViaKeyboard);
                        selectedViaKeyboard = false; // ← reset setelah dipakai
                    }
                }
            });
            return window[id + '_ts'];
        }

        /* Dropdown Wilayah Bertingkat */
        function loadCities(provinceCode) {
            // Tambah variable tracker di atas, sejajar let currentDeliveryFee
            let lastInteractionWasKeyboard = false;

            // Tambah listener ini di dalam DOMContentLoaded
            document.addEventListener('keydown', () => lastInteractionWasKeyboard = true);
            document.addEventListener('mousedown', () => lastInteractionWasKeyboard = false);
            const citySelect = document.getElementById('modalCitySelect');
            const districtSelect = document.getElementById('modalDistrictSelect');
            const villageSelect = document.getElementById('modalVillageSelect');

            citySelect.innerHTML = '<option value="">Pilih Kota</option>';
            districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            villageSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            citySelect.disabled = districtSelect.disabled = villageSelect.disabled = true;
            document.getElementById('modal_postal_code').value = '';

            if (!provinceCode) return;

            fetch(`/api/cities?province_code=${provinceCode}`)
                .then(res => res.json())
                .then(cities => {
                    cities.forEach(c => {
                        citySelect.innerHTML += `<option value="${c.code}">${c.name}</option>`;
                    });
                    citySelect.disabled = false;
                    initOrRefreshTS('modalCitySelect', function(value, isKeyboard) {
                        loadDistricts(value, isKeyboard);
                    });
                    if (lastInteractionWasKeyboard) {
                        setTimeout(() => window['modalCitySelect_ts']?.open(), 100);
                    }
                });
        }

        function loadDistricts(cityCode, isKeyboard = false) {
            // Tambah variable tracker di atas, sejajar let currentDeliveryFee
            let lastInteractionWasKeyboard = false;

            // Tambah listener ini di dalam DOMContentLoaded
            document.addEventListener('keydown', () => lastInteractionWasKeyboard = true);
            document.addEventListener('mousedown', () => lastInteractionWasKeyboard = false);
            const districtSelect = document.getElementById('modalDistrictSelect');
            const villageSelect = document.getElementById('modalVillageSelect');

            districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            villageSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            districtSelect.disabled = villageSelect.disabled = true;
            document.getElementById('modal_postal_code').value = '';

            if (!cityCode) return;

            fetch(`/api/districts?city_code=${cityCode}`)
                .then(res => res.json())
                .then(districts => {
                    districts.forEach(d => {
                        districtSelect.innerHTML += `<option value="${d.code}">${d.name}</option>`;
                    });
                    districtSelect.disabled = false;
                    initOrRefreshTS('modalDistrictSelect', function(value, kb) {
                        loadVillages(value, kb);
                    });
                    if (isKeyboard) {
                        setTimeout(() => window['modalDistrictSelect_ts']?.open(), 100);
                    }
                });
        }

        function loadVillages(districtCode, isKeyboard = false) {
            let lastInteractionWasKeyboard = false;

            // Tambah listener ini di dalam DOMContentLoaded
            document.addEventListener('keydown', () => lastInteractionWasKeyboard = true);
            document.addEventListener('mousedown', () => lastInteractionWasKeyboard = false);
            const villageSelect = document.getElementById('modalVillageSelect');
            villageSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            villageSelect.disabled = true;
            document.getElementById('modal_postal_code').value = '';

            if (!districtCode) return;

            fetch(`/api/villages?district_code=${districtCode}`)
                .then(res => res.json())
                .then(villages => {
                    villages.forEach(v => {
                        villageSelect.innerHTML += `<option value="${v.code}" data-id="${v.id}">${v.name}</option>`;
                    });
                    villageSelect.disabled = false;

                    initOrRefreshTS('modalVillageSelect', function(value) {
                        // Postal code auto-fill via Tom Select onChange
                        const originalSelect = document.getElementById('modalVillageSelect');
                        const selectedOption = originalSelect.querySelector(`option[value="${value}"]`);
                        const villageId = selectedOption?.getAttribute('data-id');
                        const postalWrapper = document.getElementById('modal_postal_wrapper');

                        postalWrapper.innerHTML = `<input type="text" id="modal_postal_code" 
                            class="form-control form-control-sm" placeholder="10001">`;

                        if (!villageId) return;

                        fetch(`/api/postal-code?village_id=${villageId}`)
                            .then(res => res.json())
                            .then(codes => {
                                if (codes.length === 1) {
                                    document.getElementById('modal_postal_code').value = codes[0];
                                } else if (codes.length > 1) {
                                    let options = codes.map(c => `<option value="${c}">${c}</option>`).join('');
                                    postalWrapper.innerHTML = `
                                        <select id="modal_postal_code" class="form-select form-select-sm">
                                            <option value="">Pilih Kode Pos</option>
                                            ${options}
                                        </select>`;
                                }
                            });
                    });
                    if (isKeyboard) {
                        setTimeout(() => window['modalVillageSelect_ts']?.open(), 100);
                    }
                });
        }


        /* Aksi Simpan Perubahan / Penambahan Data Alamat */
        function saveModalAddress() {
            const actionMode = document.getElementById('modal_action_mode').value;
            const addressId = document.getElementById('modal_address_id').value;

            const receiverName = document.getElementById('modal_receiver_name').value;
            const receiverPhone = document.getElementById('modal_receiver_phone').value;
            const addressLine = document.getElementById('modal_address_line1').value;
            const postalCode = document.getElementById('modal_postal_code').value;

            const pSel = document.getElementById('modalProvinceSelect');
            const cSel = document.getElementById('modalCitySelect');
            const dSel = document.getElementById('modalDistrictSelect');
            const vSel = document.getElementById('modalVillageSelect');

            if(!receiverName || !receiverPhone || !addressLine || !cSel.value) {
                alert('Harap lengkapi informasi alamat Anda.');
                return;
            }

            // Baca via TomSelect instance jika ada, fallback ke native select
            const provinceName = pSel.options[pSel.selectedIndex]?.text || '';
            const cityTS_val   = window['modalCitySelect_ts'];
            const distTS_val   = window['modalDistrictSelect_ts'];
            const villTS_val   = window['modalVillageSelect_ts'];

            const cityName     = cityTS_val     ? cityTS_val.getOption(cityTS_val.getValue())?.textContent?.trim()     : (cSel.options[cSel.selectedIndex]?.text || '');
            const districtName = distTS_val     ? distTS_val.getOption(distTS_val.getValue())?.textContent?.trim()     : (dSel.options[dSel.selectedIndex]?.text || '');
            const villageName  = villTS_val     ? villTS_val.getOption(villTS_val.getValue())?.textContent?.trim()     : (vSel.options[vSel.selectedIndex]?.text || '');

            const container = document.getElementById('hiddenAddressMutationContainer');
            let wrapper = document.querySelector('.shopee-address-wrapper');
            if (!wrapper) {
                wrapper = document.createElement('div');
                wrapper.className = 'shopee-address-wrapper mb-3';
                
                const addBtn = document.querySelector('.btn-add-address-shopee') 
                            || document.querySelector('.btn-outline-secondary');
                
                if (addBtn) {
                    const target = addBtn.closest('.p-4') || addBtn;
                    target.parentNode.insertBefore(wrapper, target);
                } else {
                    document.querySelector('.mb-4').appendChild(wrapper);
                }
            }
            
            if (actionMode === 'add') {
                container.innerHTML = `
                    <input type="hidden" name="address_action" value="create">
                    <input type="hidden" name="receiver_name" value="${receiverName}">
                    <input type="hidden" name="receiver_phone" value="${receiverPhone}">
                    <input type="hidden" name="address_line1" value="${addressLine}">
                    <input type="hidden" name="province_code" value="${pSel.value}">
                    <input type="hidden" name="city_name" value="${cityName}">
                    <input type="hidden" name="village_name" value="${villageName}">
                    <input type="hidden" name="postal_code" value="${postalCode}">
                `;

                document.querySelectorAll('.address-selector-radio').forEach(r => r.checked = false);

                const newAddressHTML = `
                    <div class="shopee-address-item d-flex align-items-start gap-3 w-100 m-0" id="temp_lbl">
                        <div class="pt-1">
                            <input type="radio" name="address_id" value="new" class="address-selector-radio" checked data-is-new="true" data-city="${cityName}" data-village="${villageName}">
                        </div>
                        <div class="flex-grow-1" onclick="this.parentElement.querySelector('.address-selector-radio').click()">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="fw-bold text-jaced-dark visual-name" style="font-size: 14px;">${receiverName}</span>
                                <span class="text-jaced-muted small">|</span>
                                <span class="text-jaced-muted small visual-phone">${receiverPhone}</span>
                                <span class="badge bg-dark text-white ms-2" style="font-size: 9px; padding: 2px 4px;">Baru</span>
                            </div>
                            <div class="text-jaced-dark small mb-1 visual-line1">${addressLine}</div>
                            <div class="text-jaced-muted tiny visual-full" style="font-size: 12px;">
                                ${villageName}, ${districtName}, ${cityName}, ${provinceName}, ${postalCode}
                            </div>
                        </div>
                        <div class="pt-1">
                            <button type="button" class="btn-edit-address" onclick="alert('Alamat baru belum disimpan ke database. Selesaikan checkout untuk menyimpan.')">Ubah</button>
                        </div>
                    </div>
                `;
                
                const oldTemp = document.getElementById('temp_lbl');
                if(oldTemp) oldTemp.remove();

                wrapper.insertAdjacentHTML('beforeend', newAddressHTML);
                const newRadio = document.querySelector('#temp_lbl .address-selector-radio');
                if (newRadio) newRadio.addEventListener('change', handleAddressChange);

            } else {
                container.innerHTML = `
                    <input type="hidden" name="address_action" value="update">
                    <input type="hidden" name="edit_address_id" value="${addressId}">
                    <input type="hidden" name="receiver_name" value="${receiverName}">
                    <input type="hidden" name="receiver_phone" value="${receiverPhone}">
                    <input type="hidden" name="address_line1" value="${addressLine}">
                    <input type="hidden" name="province_code" value="${pSel.value}">
                    <input type="hidden" name="city_name" value="${cityName}">
                    <input type="hidden" name="village_name" value="${villageName}">
                    <input type="hidden" name="postal_code" value="${postalCode}">
                `;

                const targetRadio = document.querySelector(`.address-selector-radio[value="${addressId}"]`);
                if (targetRadio) {
                    targetRadio.checked = true;
                    targetRadio.setAttribute('data-city', cityName);
                    targetRadio.setAttribute('data-village', villageName);

                    const rowItem = targetRadio.closest('.shopee-address-item');
                    if (rowItem) {
                        rowItem.querySelector('.flex-grow-1').innerHTML = `
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="fw-bold text-jaced-dark" style="font-size: 14px;">${receiverName}</span>
                                <span class="text-jaced-muted small">|</span>
                                <span class="text-jaced-muted small">${receiverPhone}</span>
                            </div>
                            <div class="text-jaced-dark small mb-1">${addressLine}</div>
                            <div class="text-jaced-muted tiny" style="font-size: 12px;">
                                ${villageName}, ${districtName}, ${cityName}, ${provinceName}, ${postalCode}
                            </div>
                        `;
                    }
                }
            }

            bsModal.hide();
            fetchShippingCost(cityName, villageName);
        }

        function fetchShippingCost(cityName, villageName) {
            const shippingSection = document.getElementById('shippingOptions');
            if (!shippingSection) return;
            
            shippingSection.innerHTML = '<p class="text-jaced-muted small">Menghitung ongkir...</p>';

            // Setelah diubah
            fetch(`/api/shipping-cost?village_name=${encodeURIComponent(villageName)}&city_name=${encodeURIComponent(cityName)}&weight={{ $totalWeight }}`)
                .then(res => res.json())
                .then(costs => {
                    if (costs.error || !costs.length) {
                        shippingSection.innerHTML = `<p class="text-danger small">Kurir tidak tersedia untuk wilayah ini.</p>`;
                        return;
                    }

                    let html = '<div class="row g-2 mt-1">';
                    costs.forEach((item, index) => {
                        html += `
                        <div class="col-12">
                            <label class="d-flex justify-content-between align-items-center p-2"
                                style="border: 1px solid #e2dcd0; border-radius: 6px; cursor: pointer; font-size: 13px; background-color: #fff;">
                                <div class="d-flex align-items-center gap-2">
                                    <input type="radio" name="selected_courier" value="${item.cost}"
                                        data-cost="${item.cost}" onchange="updateDeliveryFee(${item.cost})" ${index === 0 ? 'checked' : ''}>
                                    <div>
                                        <div class="fw-semibold">${item.name} (${item.service})</div>
                                        <div class="text-jaced-muted tiny" style="font-size: 11px;">${item.etd}</div>
                                    </div>
                                </div>
                                <span class="fw-semibold text-jaced-dark">Rp ${item.cost.toLocaleString('id-ID')}</span>
                            </label>
                        </div>`;
                    });
                    html += '</div>';

                    shippingSection.innerHTML = html;
                    updateDeliveryFee(costs[0].cost);
                })
                .catch(() => {
                    shippingSection.innerHTML = '<p class="text-danger small">Gagal menghitung ongkir.</p>';
                });
        }

        function updateDeliveryFee(cost) {
            currentDeliveryFee = cost;
            
            const display = document.getElementById('deliveryFeeDisplay');
            const input = document.getElementById('deliveryFeeInput');
            
            if(display) display.innerText = 'Rp ' + cost.toLocaleString('id-ID');
            if(input) input.value = cost;

            calculateGrandTotal();
        }


        function clearSelectedVoucher() {
            voucherApplied = false;
            document.getElementById('applied-voucher-id').value = "";
            document.getElementById('selectedVoucherText').innerText = "Voucher Jaced";
            document.getElementById('selectedVoucherText').classList.remove('fw-bold', 'text-success');
            document.getElementById('voucherActiveBadge').classList.add('d-none');
            
            const checkedRadio = document.querySelector('.voucher-radio-input:checked');
            if(checkedRadio) checkedRadio.checked = false;

            const rdDelivery = document.getElementById('row-discount-delivery');
            const rdProduct  = document.getElementById('row-discount-product');
            if (rdDelivery) rdDelivery.classList.add('d-none');
            if (rdProduct)  rdProduct.classList.add('d-none');

            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            const subtotal = {{ $subtotal ?? 0 }};
            const tax = {{ $tax ?? 0 }};
            const tierDiscount = {{ $tierDiscountAmount ?? 0 }};
            
            let discountValue = 0;

            const rowDelivery = document.getElementById('row-discount-delivery');
            const rowProduct  = document.getElementById('row-discount-product');
            if(rowDelivery) rowDelivery.classList.add('d-none');
            if(rowProduct)  rowProduct.classList.add('d-none');
            
            if (voucherApplied) {
                // Prioritas: radio yang diceklis, fallback ke pendingVoucherData
                const selectedVoucher = document.querySelector('.voucher-radio-input:checked');
                
                let usedFor, maxDiscount, discountPercentage;

                if (selectedVoucher) {
                    usedFor = selectedVoucher.getAttribute('data-used-for');
                    maxDiscount = parseFloat(selectedVoucher.getAttribute('data-discount')) || 0;
                    discountPercentage = parseFloat(selectedVoucher.getAttribute('data-discount-percentage')) || 0;
                } else if (window.pendingVoucherData) {
                    usedFor = window.pendingVoucherData.usedFor;
                    maxDiscount = window.pendingVoucherData.maxDiscount;
                    discountPercentage = window.pendingVoucherData.discountPercentage;
                }

                if (usedFor === 'delivery') {
                    if (currentDeliveryFee === 0) {
                        // Kurir belum dipilih, diskon belum bisa dihitung
                        if(rowDelivery) rowDelivery.classList.add('d-none');
                    } else {
                        discountValue = Math.min(currentDeliveryFee, maxDiscount);
                        if(rowDelivery) rowDelivery.classList.remove('d-none');
                        document.querySelectorAll('#summary-discount-delivery').forEach(el => {
                            el.innerText = '-Rp ' + discountValue.toLocaleString('id-ID');
                        });
                    }
                } else if (usedFor) {
                    discountValue = Math.min(subtotal * (discountPercentage / 100), maxDiscount);
                    if(rowProduct) rowProduct.classList.remove('d-none');
                    document.querySelectorAll('#summary-discount-product').forEach(el => {
                        el.innerText = '-Rp ' + discountValue.toLocaleString('id-ID');
                    });
                }
            }

            const finalTotal = (subtotal + tax + currentDeliveryFee) - discountValue - tierDiscount; // ✅ kurangkan di sini
            document.querySelectorAll('#totalDisplay').forEach(el => {
                el.innerText = 'Rp ' + finalTotal.toLocaleString('id-ID');
            });
            const totalDiscountForBackend = discountValue + tierDiscount;
            const discountInput = document.getElementById('applied-discount-amount');
            if (discountInput) discountInput.value = totalDiscountForBackend;
        }

        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            const deliveryFee = parseInt(document.getElementById('deliveryFeeInput').value) || 0;
            const courierSelected = document.querySelector('input[name="selected_courier"]:checked');
            const paymentMethod = document.getElementById('paymentMethod').value;

            if (!courierSelected || deliveryFee === 0) {
                e.preventDefault();
                alert('Silakan pilih metode pengiriman terlebih dahulu.');
                return;
            }

            if (!paymentMethod) {
                e.preventDefault();
                alert('Silakan pilih metode pembayaran.');
                return;
            }
        });

        // Saat user pilih payment method
        document.querySelectorAll('input[name="payment_method"]').forEach(input => {
            input.addEventListener('change', function () {
                const total = parseInt('{{ $total }}'); // dari blade
                const isQris = this.value === 'qris';
                
                if (isQris && total > 10000000) {
                    // disable tombol checkout atau tampilkan warning
                    document.getElementById('qris-warning').classList.remove('d-none');
                    document.getElementById('btn-checkout').disabled = true;
                } else {
                    document.getElementById('qris-warning').classList.add('d-none');
                    document.getElementById('btn-checkout').disabled = false;
                }
            });
        });
    </script>
@endpush