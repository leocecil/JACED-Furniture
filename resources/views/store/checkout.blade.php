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

        /* Order Summary Redesign */
        .summary-card {
            position: sticky;
            top: 24px;
            z-index: 1;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            font-size: 13px;
            border-bottom: 1px solid #f3f0e9;
        }

        .summary-row:last-of-type {
            border-bottom: none;
        }

        .summary-row .label {
            color: #8c7e6c;
            font-weight: 400;
        }

        .summary-row .value {
            color: #2a2318;
            font-weight: 500;
        }

        .summary-row.discount-row .label,
        .summary-row.discount-row .value {
            color: #5c695d;
            font-weight: 600;
        }

        .summary-row.discount-row .value::before {
            content: '− ';
        }

        .summary-divider {
            border: none;
            border-top: 1.5px solid #e2dcd0;
            margin: 12px 0;
        }

        .summary-grand-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 4px 0;
        }

        .summary-grand-row .grand-label {
            font-size: 14px;
            font-weight: 600;
            color: #2a2318;
        }

        .summary-grand-row .grand-value {
            font-size: 20px;
            font-weight: 700;
            color: #5c695d;
            letter-spacing: -0.02em;
        }

        .voucher-trigger {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            border: 1px dashed #c8b99a;
            border-radius: 8px;
            background: #fdf9f4;
            cursor: pointer;
            transition: all 0.2s ease;
            margin: 4px 0 12px;
        }

        .voucher-trigger:hover {
            background: #f9f3ea;
            border-color: #a89070;
        }

        .voucher-trigger .voucher-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .voucher-trigger .voucher-label {
            font-size: 13px;
            font-weight: 500;
            color: #2a2318;
        }

        .qris-warning-box {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            padding: 10px 12px;
            background: #fdf6ec;
            border: 1px solid #e8c97a;
            border-radius: 8px;
            font-size: 12px;
            color: #7a5c1e;
            margin-bottom: 12px;
        }

        /* Voucher Modal Redesign */
        .voucher-card {
            position: relative;
            display: flex;
            align-items: stretch;
            background: #fff;
            border: 1px solid #e2dcd0;
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .voucher-card:hover {
            border-color: #b0a090;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        }

        .voucher-card.is-selected {
            border-color: #5c695d;
            box-shadow: 0 0 0 2px rgba(92, 105, 93, 0.15);
        }

        .voucher-card-left {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 16px 14px;
            min-width: 90px;
            text-align: center;
            gap: 4px;
        }

        .voucher-card-left.shipping {
            background: #f1f4f2;
            border-right: 1px dashed #b8cabb;
        }

        .voucher-card-left.product {
            background: #fcf5f3;
            border-right: 1px dashed #e8b8a8;
        }

        .voucher-type-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .voucher-type-label.shipping {
            color: #5c695d;
        }

        .voucher-type-label.product {
            color: #bd654e;
        }

        .voucher-pct {
            font-size: 20px;
            font-weight: 800;
            line-height: 1;
        }

        .voucher-pct.shipping {
            color: #5c695d;
        }

        .voucher-pct.product {
            color: #bd654e;
        }

        .voucher-card-body {
            flex: 1;
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 3px;
        }

        .voucher-card-body .v-name {
            font-size: 13px;
            font-weight: 700;
            color: #2a2318;
        }

        .voucher-card-body .v-desc {
            font-size: 11px;
            color: #8c7e6c;
            line-height: 1.4;
        }

        .voucher-card-body .v-expiry {
            font-size: 10px;
            color: #b0a090;
            margin-top: 2px;
        }

        .voucher-card-radio {
            display: flex;
            align-items: center;
            padding: 0 14px 0 0;
        }

        .voucher-radio-input {
            width: 17px;
            height: 17px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .voucher-empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            gap: 12px;
            color: #b0a090;
        }

        .voucher-empty-state p {
            font-size: 13px;
            margin: 0;
            text-align: center;
            line-height: 1.5;
        }

        .voucher-preview-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 12px;
        }

        .voucher-preview-bar.shipping {
            background: #f1f4f2;
            color: #5c695d;
            border: 1px solid #b8cabb;
        }

        .voucher-preview-bar.product {
            background: #fcf5f3;
            color: #bd654e;
            border: 1px solid #e8b8a8;
        }

        /* Address Modal Redesign */
        .addr-modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.13);
        }

        .addr-modal-header {
            padding: 20px 24px 16px;
            border-bottom: 1px solid #f0ece6;
            background: #faf9f6;
            border-radius: 16px 16px 0 0;
        }

        .addr-modal-body {
            padding: 24px;
            background: #fff;
        }

        .addr-modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #f0ece6;
            background: #faf9f6;
            border-radius: 0 0 16px 16px;
        }

        .addr-field-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .addr-field-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: #8c7e6c;
        }

        .addr-field-input {
            border: 1px solid #e2dcd0;
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 13px;
            color: #2a2318;
            background: #fff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            width: 100%;
        }

        .addr-field-input:focus {
            outline: none;
            border-color: #5c695d;
            box-shadow: 0 0 0 3px rgba(92, 105, 93, 0.1);
        }

        .addr-field-input.is-invalid {
            border-color: #c0392b;
            box-shadow: 0 0 0 3px rgba(192, 57, 43, 0.1);
        }

        .addr-field-input.is-valid {
            border-color: #5c695d;
        }

        .addr-field-error {
            font-size: 11px;
            color: #c0392b;
            display: none;
            margin-top: 2px;
        }

        .addr-field-error.visible {
            display: block;
        }

        .addr-section-divider {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #c8b99a;
            padding: 4px 0 8px;
            border-bottom: 1px solid #f3f0e9;
            margin-bottom: 4px;
        }

        /* Override TomSelect styling agar selaras */
        .ts-wrapper .ts-control {
            border: 1px solid #e2dcd0 !important;
            border-radius: 8px !important;
            padding: 7px 12px !important;
            font-size: 13px !important;
            box-shadow: none !important;
        }

        .ts-wrapper.focus .ts-control {
            border-color: #5c695d !important;
            box-shadow: 0 0 0 3px rgba(92, 105, 93, 0.1) !important;
        }

        .ts-wrapper.is-invalid .ts-control {
            border-color: #c0392b !important;
            box-shadow: 0 0 0 3px rgba(192, 57, 43, 0.1) !important;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 500;
            color: #5c695d;
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            text-decoration: none;
            margin-bottom: 12px;
        }
        .back-link:hover {
            color: var(--jaced-caramel);
        }

        /* ── ANIMATIONS & POLISH ── */

        /* Page entrance */
        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -400px 0;
            }

            100% {
                background-position: 400px 0;
            }
        }

        .jaced-page {
            animation: fadeIn 0.4s ease both;
        }

        /* Staggered entrance untuk left/right column */
        .col-12.col-lg-8 {
            animation: fadeSlideUp 0.45s ease 0.05s both;
        }

        .col-12.col-lg-4 {
            animation: fadeSlideUp 0.45s ease 0.15s both;
        }

        /* Address card hover polish */
        .shopee-address-item {
            transition: background-color 0.2s ease, transform 0.15s ease;
        }

        .shopee-address-item:hover {
            transform: translateX(2px);
        }

        .shopee-address-item:has(.address-selector-radio:checked) {
            background-color: #f6f5f0;
            transition: background-color 0.25s ease;
        }

        /* Voucher trigger pulse saat ada voucher applied */
        .voucher-trigger.has-voucher {
            border-style: solid;
            border-color: #5c695d;
            background: #f1f4f2;
        }

        .voucher-trigger.has-voucher .voucher-label {
            color: #5c695d;
            font-weight: 600;
        }

        /* Summary card polish */
        .summary-card {
            transition: box-shadow 0.3s ease;
        }

        .summary-card:hover {
            box-shadow: 0 4px 24px rgba(42, 35, 24, 0.08);
        }

        /* Summary row hover */
        .summary-row {
            transition: background-color 0.15s ease;
            border-radius: 4px;
            padding: 8px 4px;
        }

        .summary-row:hover {
            background-color: #f9f7f4;
        }

        /* Discount row appear animation */
        .summary-row.discount-row:not(.d-none) {
            animation: fadeSlideUp 0.3s ease both;
        }

        /* Grand total value pulse saat update */
        @keyframes totalPulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.04);
                color: #4a5c4b;
            }

            100% {
                transform: scale(1);
            }
        }

        .grand-value.updating {
            animation: totalPulse 0.35s ease;
        }

        /* Shipping option card */
        .shipping-option-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 14px;
            border: 1px solid #e2dcd0;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            background: #fff;
            transition: border-color 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease;
            width: 100%;
        }

        .shipping-option-label:hover {
            border-color: #b0a090;
            background: #faf9f6;
        }

        .shipping-option-label:has(input:checked) {
            border-color: #5c695d;
            background: #f1f4f2;
            box-shadow: 0 0 0 2px rgba(92, 105, 93, 0.12);
        }

        /* Shimmer loading state untuk shipping */
        .shipping-skeleton {
            height: 52px;
            border-radius: 8px;
            background: linear-gradient(90deg, #f3f0e9 25%, #e8e4dc 50%, #f3f0e9 75%);
            background-size: 400px 100%;
            animation: shimmer 1.2s infinite;
            margin-bottom: 8px;
        }

        /* Back button polish */
        .btn-back-inv {
            transition: all 0.2s ease;
        }

        .btn-back-inv:hover {
            transform: translateX(-3px);
        }

        /* Add address button */
        .btn-add-address-shopee {
            transition: all 0.25s ease;
        }

        .btn-add-address-shopee:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(92, 105, 93, 0.15);
        }

        /* Cancel modal */
        #cancelCheckoutModal .modal-content {
            animation: fadeSlideUp 0.3s ease both;
        }

        /* Submit button pulse on hover */
        .btn-jaced {
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .btn-jaced:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(42, 35, 24, 0.2);
        }

        .btn-jaced:active:not(:disabled) {
            transform: translateY(0);
        }

        /* Voucher card animation */
        .voucher-card {
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.15s ease;
        }

        .voucher-card:hover {
            transform: translateY(-1px);
        }

        .voucher-card.is-selected {
            transform: translateY(-1px);
        }

        /* Modal entrance */
        .modal.fade .modal-dialog {
            transition: transform 0.25s ease, opacity 0.25s ease;
            transform: translateY(12px);
        }

        .modal.show .modal-dialog {
            transform: translateY(0);
        }

        /* Form input focus animation */
        .addr-field-input {
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.1s ease;
        }

        .addr-field-input:focus {
            transform: translateY(-1px);
        }

        /* Validation shake */
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            20% {
                transform: translateX(-6px);
            }

            40% {
                transform: translateX(6px);
            }

            60% {
                transform: translateX(-4px);
            }

            80% {
                transform: translateX(4px);
            }
        }

        .addr-field-input.is-invalid {
            animation: shake 0.4s ease;
        }

        /* Inline error fade in */
        .addr-field-error {
            transition: opacity 0.2s ease;
            opacity: 0;
        }

        .addr-field-error.visible {
            opacity: 1;
        }
    </style>
@endpush

@section('content')

    <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
        @csrf

        {{-- Back to Cart Button
        <div style="max-width: 1280px; margin: 0 auto; padding: 16px 0 0;">
            
        </div> --}}

        {{-- Checkout Page --}}
        <div class="jaced-page">
            <div style="max-width: 1280px; margin: 0 auto;">

                <button type="button" id="btnBackToCart" class="back-link">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Back to Cart</span>
                </button>

                <h1 class="fw-bold text-jaced-dark mb-4"
                    style="font-size: 2.2rem; font-weight: 400; letter-spacing: 0.04em;">Checkout</h1>

                <div class="row g-4 align-items-start">

                    {{-- LEFT SIDE --}}
                    <div class="col-12 col-lg-8">

                        {{-- Review Order --}}
                        <div class="mb-4">
                            <h2 class="fw-bold text-jaced-sage mb-3" style="font-size: 1.3rem; font-weight: 400;">Review
                                Order</h2>
                            <div class="order-items-wrapper">
                                <div class="order-items-scroll">
                                    @forelse ($items as $item)
                                        <div class="jaced-item-card py-2">
                                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                                                style="width: 70px; height: 70px; object-fit: cover; border-radius: 6px;">
                                            <div class="flex-grow-1">
                                                <div class="fw-semibold text-jaced-dark" style="font-size: 14px;">
                                                    {{ $item['name'] }}</div>
                                                <div class="text-jaced-muted small">{{ $item['variant'] }} • Qty:
                                                    {{ $item['qty'] }}</div>
                                            </div>
                                            <div class="fw-semibold text-jaced-dark" style="font-size: 14px;">Rp
                                                {{ number_format($item['price'], 2) }}</div>
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
                                <h2 class="fw-bold text-jaced-sage m-0" style="font-size: 1.3rem; font-weight: 400;">
                                    Delivery Address</h2>
                            </div>

                            @if (isset($savedAddresses) && $savedAddresses->isNotEmpty())
                                <div class="shopee-address-wrapper mb-3">
                                    @foreach ($savedAddresses as $addr)
                                        <div class="shopee-address-item d-flex align-items-start gap-3 w-100 m-0">
                                            <div class="pt-1">
                                                <input type="radio" name="address_id" value="{{ $addr->id }}"
                                                    class="address-selector-radio" data-is-new="false"
                                                    data-city="{{ $addr->city_name }}"
                                                    data-village="{{ $addr->village_name }}"
                                                    {{ (isset($defaultAddress) ? $defaultAddress->id == $addr->id : $loop->first) ? 'checked' : '' }}>
                                            </div>

                                            {{-- Sisi Kiri: Informasi Alamat --}}
                                            <div class="flex-grow-1"
                                                onclick="this.parentElement.querySelector('.address-selector-radio').click()">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <span class="fw-bold text-jaced-dark"
                                                        style="font-size: 14px;">{{ $addr->receiver_name }}</span>
                                                    <span class="text-jaced-muted small">|</span>
                                                    <span class="text-jaced-muted small">{{ $addr->receiver_phone }}</span>

                                                    @if (isset($defaultAddress) && $defaultAddress->id == $addr->id)
                                                        <span
                                                            class="badge bg-transparent text-danger border border-danger ms-2"
                                                            style="font-size: 9px; padding: 2px 4px;">Default</span>
                                                    @endif
                                                </div>
                                                <div class="text-jaced-dark small mb-1">{{ $addr->address_line1 }}</div>
                                                <div class="text-jaced-muted tiny" style="font-size: 12px;">
                                                    {{ $addr->village_name }}, {{ $addr->district_name }},
                                                    {{ $addr->city_name }}, {{ $addr->province_name }},
                                                    {{ $addr->postal_code }}
                                                </div>
                                            </div>

                                            {{-- Sisi Kanan: Tombol Ubah ala Shopee --}}
                                            <div class="pt-1">
                                                <button type="button" class="btn-edit-address"
                                                    onclick="openEditAddressModal({{ json_encode($addr) }})">
                                                    Edit
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button type="button" class="btn-add-address-shopee" onclick="openAddAddressModal()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 14 14">
                                        <path fill-rule="evenodd"
                                            d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z" />
                                    </svg>
                                    Add New Address
                                </button>
                            @else
                                <div class="p-4 text-center border rounded bg-light">
                                    <p class="text-jaced-muted small">You haven't saved any addresses yet.</p>
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        onclick="openAddAddressModal()">
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
                        <div class="summary-card p-4"
                            style="background-color: #faf9f6; border: 1px solid #e2dcd0; border-radius: 12px;">

                            <h2
                                style="font-size: 1rem; font-weight: 700; color: #2a2318; letter-spacing: 0.04em; text-transform: uppercase; margin-bottom: 16px;">
                                Order Summary</h2>

                            {{-- Line items --}}
                            <div class="summary-row">
                                <span class="label">Subtotal</span>
                                <span class="value" id="summary-subtotal" data-raw="{{ $subtotal }}">Rp
                                    {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="summary-row">
                                <span class="label">Shipping Fee</span>
                                <span class="value" id="deliveryFeeDisplay">Rp
                                    {{ number_format($shipping, 0, ',', '.') }}</span>
                            </div>
                            <div class="summary-row">
                                <span class="label">Service Tax</span>
                                <span class="value" id="summary-tax" data-raw="{{ $tax }}">Rp
                                    {{ number_format($tax, 0, ',', '.') }}</span>
                            </div>

                            @if ($tierDiscountAmount > 0)
                                <div class="summary-row discount-row">
                                    <span class="label">
                                        Member Discount
                                        @if ($userStage)
                                            <span
                                                style="font-size: 11px; opacity: 0.8;">({{ $userStage->discount_percentage }}%
                                                · {{ $userStage->name }})</span>
                                        @endif
                                    </span>
                                    <span class="value">Rp {{ number_format($tierDiscountAmount, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            {{-- Voucher discount rows (hidden by default) --}}
                            <div class="summary-row discount-row d-none" id="row-discount-delivery">
                                <span class="label">Shipping Discount</span>
                                <span class="value" id="summary-discount-delivery">Rp 0</span>
                            </div>
                            <div class="summary-row discount-row d-none" id="row-discount-product">
                                <span class="label">Voucher Discount</span>
                                <span class="value" id="summary-discount-product">Rp 0</span>
                            </div>

                            {{-- Voucher trigger --}}
                            <div class="voucher-trigger" data-bs-toggle="modal" data-bs-target="#voucherModal">
                                <div class="voucher-left">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#8c7e6c"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5ZM1 4v3.5a.5.5 0 0 0 .5.5.5.5 0 0 1 0 1 .5.5 0 0 0-.5.5V14a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-3.5a.5.5 0 0 0-.5-.5.5.5 0 0 1 0-1 .5.5 0 0 0 .5-.5V4a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1Z" />
                                    </svg>
                                    <span class="voucher-label" id="selectedVoucherText">Use a Voucher</span>
                                </div>
                                <div style="display:flex; align-items:center; gap:6px;">
                                    <span class="badge d-none" id="voucherActiveBadge"
                                        style="background:#5c695d; font-size:10px; font-weight:600;">Applied</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#8c7e6c"
                                        viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                                    </svg>
                                </div>
                            </div>

                            {{-- Payment Method --}}
                            <div class="mb-3">
                                <label class="d-block mb-1"
                                    style="font-size: 12px; font-weight: 600; color: #8c7e6c; text-transform: uppercase; letter-spacing: 0.06em;">Payment
                                    Method</label>
                                <select name="payment_method" class="form-select form-select-sm" id="paymentMethod"
                                    required style="border-color: #d1cbbf; font-size: 13px;">
                                    <option value="">Choose Payment Method</option>
                                    @foreach ($paymentMethods as $method)
                                        <option value="{{ $method['value'] }}">{{ $method['label'] }}</option>
                                    @endforeach
                                </select>

                                <div id="bankDropdown" style="display: none; margin-top: 8px;">
                                    <label class="d-block mb-1"
                                        style="font-size: 12px; font-weight: 600; color: #8c7e6c; text-transform: uppercase; letter-spacing: 0.06em;">Select
                                        Bank</label>
                                    <select name="bank" class="form-select form-select-sm"
                                        style="border-color: #d1cbbf; font-size: 13px;">
                                        <option value="">Select Bank</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank['value'] }}">{{ $bank['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <hr class="summary-divider">

                            {{-- Grand Total --}}
                            <div class="summary-grand-row mb-3">
                                <span class="grand-label">Grand Total</span>
                                <span class="grand-value" id="totalDisplay" data-raw="{{ $total }}">Rp
                                    {{ number_format($total, 0, ',', '.') }}</span>
                            </div>

                            {{-- QRIS Warning --}}
                            <div id="qris-warning" class="qris-warning-box d-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#7a5c1e"
                                    viewBox="0 0 16 16" style="margin-top:1px; flex-shrink:0;">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                    <path
                                        d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z" />
                                </svg>
                                <span>QRIS is only available for transactions below Rp 10,000,000</span>
                            </div>

                            {{-- Hidden inputs --}}
                            <input type="hidden" name="applied_voucher_id" id="applied-voucher-id" value="">
                            <input type="hidden" name="discount_amount" id="applied-discount-amount" value="0">

                            <button type="submit" class="btn-jaced w-100 py-2"
                                style="font-size: 14px; font-weight: 600; letter-spacing: 0.03em;">
                                Place Order
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Wadah input hidden --}}
        <div id="hiddenAddressMutationContainer"></div>
    </form>

    {{-- VOUCHER MODAL --}}
    <div class="modal fade" id="voucherModal" tabindex="-1" aria-labelledby="voucherModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
            <div class="modal-content"
                style="border-radius: 14px; border: none; box-shadow: 0 12px 40px rgba(0,0,0,0.12);">

                {{-- Header --}}
                <div class="modal-header px-4 py-3"
                    style="border-bottom: 1px solid #f0ece6; background: #faf9f6; border-radius: 14px 14px 0 0;">
                    <div>
                        <h5 class="modal-title fw-bold text-jaced-dark mb-0" id="voucherModalLabel"
                            style="font-size: 15px;">Select a Voucher</h5>
                        <p class="mb-0" style="font-size: 11px; color: #8c7e6c; margin-top: 2px;">Choose one voucher to
                            apply to your order</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Body --}}
                <div class="modal-body px-4 py-3" style="background: #fcfbfa; max-height: 420px; overflow-y: auto;">

                    @if (isset($myVouchers) && $myVouchers->isNotEmpty())
                        <div class="d-flex flex-column gap-3">
                            @foreach ($myVouchers as $v)
                                @php
                                    $isShipping = $v->used_for === 'delivery';
                                    $typeClass = $isShipping ? 'shipping' : 'product';
                                @endphp

                                <div class="voucher-card" id="voucher-card-{{ $v->id }}"
                                    onclick="selectVoucherCard(this, '{{ $v->id }}')">

                                    {{-- Left color block --}}
                                    <div class="voucher-card-left {{ $typeClass }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="{{ $isShipping ? '#5c695d' : '#bd654e' }}" viewBox="0 0 16 16">
                                            <path
                                                d="M0 3.5A1.5 1.5 0 0 1 1.5 2h13A1.5 1.5 0 0 1 16 3.5v2a.5.5 0 0 1-.5.5c-.75 0-1.5.75-1.5 1.5S14.75 9 15.5 9a.5.5 0 0 1 .5.5v2a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 11.5v-2a.5.5 0 0 1 .5-.5C1.25 9 2 8.25 2 7.5S1.25 6 .5 6a.5.5 0 0 1-.5-.5v-2z" />
                                        </svg>
                                        @if (!$isShipping && isset($v->discount_percentage))
                                            <span
                                                class="voucher-pct {{ $typeClass }}">{{ $v->discount_percentage }}%</span>
                                        @endif
                                        <span
                                            class="voucher-type-label {{ $typeClass }}">{{ $isShipping ? 'Shipping' : 'Discount' }}</span>
                                    </div>

                                    {{-- Body --}}
                                    <div class="voucher-card-body">
                                        <div class="v-name">{{ $v->name }}</div>
                                        <div class="v-desc">{{ $v->description }}</div>
                                        <div class="v-expiry">
                                            Valid until {{ \Carbon\Carbon::parse($v->expiry_date)->format('d M Y') }}
                                        </div>
                                    </div>

                                    {{-- Radio --}}
                                    <div class="voucher-card-radio">
                                        <input type="radio" name="voucher_select_radio" value="{{ $v->id }}"
                                            class="voucher-radio-input" data-name="{{ $v->name }}"
                                            data-used-for="{{ $v->used_for }}"
                                            data-discount="{{ $v->max_discount ?? 0 }}"
                                            data-discount-percentage="{{ $v->discount_percentage ?? 0 }}"
                                            style="accent-color: {{ $isShipping ? '#5c695d' : '#bd654e' }};"
                                            onclick="event.stopPropagation();">
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="voucher-empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#d8cfc4"
                                viewBox="0 0 16 16">
                                <path
                                    d="M0 3.5A1.5 1.5 0 0 1 1.5 2h13A1.5 1.5 0 0 1 16 3.5v2a.5.5 0 0 1-.5.5c-.75 0-1.5.75-1.5 1.5S14.75 9 15.5 9a.5.5 0 0 1 .5.5v2a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 11.5v-2a.5.5 0 0 1 .5-.5C1.25 9 2 8.25 2 7.5S1.25 6 .5 6a.5.5 0 0 1-.5-.5v-2z" />
                            </svg>
                            <p>You don't have any vouchers available.<br>Complete purchases to earn vouchers.</p>
                        </div>
                    @endif

                </div>

                {{-- Footer --}}
                <div class="modal-footer px-4 py-3"
                    style="border-top: 1px solid #f0ece6; background: #faf9f6; border-radius: 0 0 14px 14px; flex-direction: column; align-items: stretch; gap: 0;">

                    {{-- Preview bar --}}
                    <div id="voucherPreview" class="d-none mb-3">
                        <div id="voucherPreviewBar" class="voucher-preview-bar shipping">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path
                                    d="M0 3.5A1.5 1.5 0 0 1 1.5 2h13A1.5 1.5 0 0 1 16 3.5v2a.5.5 0 0 1-.5.5c-.75 0-1.5.75-1.5 1.5S14.75 9 15.5 9a.5.5 0 0 1 .5.5v2a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 11.5v-2a.5.5 0 0 1 .5-.5C1.25 9 2 8.25 2 7.5S1.25 6 .5 6a.5.5 0 0 1-.5-.5v-2z" />
                            </svg>
                            <span id="voucherPreviewText"></span>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-light border flex-grow-1" data-bs-dismiss="modal"
                            style="border-radius: 8px; font-size: 13px; padding: 8px;">
                            Cancel
                        </button>
                        <button type="button" id="btn-apply-voucher" class="btn btn-sm text-white flex-grow-1"
                            style="background-color: #5c695d; border-radius: 8px; font-size: 13px; font-weight: 600; padding: 8px;"
                            data-bs-dismiss="modal">
                            Apply Voucher
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- ADDRESS MODAL --}}
    <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content addr-modal-content">

                {{-- Header --}}
                <div class="addr-modal-header d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="fw-bold text-jaced-dark mb-0" id="addressModalLabel" style="font-size: 15px;">Address
                            Details</h5>
                        <p class="mb-0" style="font-size: 11px; color: #8c7e6c; margin-top: 3px;">Fill in the delivery
                            address information</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Body --}}
                <div class="addr-modal-body">
                    <input type="hidden" id="modal_action_mode" value="add">
                    <input type="hidden" id="modal_address_id" value="">

                    {{-- Section: Recipient --}}
                    <div class="addr-section-divider">Recipient Info</div>
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <div class="addr-field-group">
                                <label class="addr-field-label">Recipient Name <span
                                        style="color:#c0392b;">*</span></label>
                                <input type="text" id="modal_receiver_name" class="addr-field-input"
                                    placeholder="Full name">
                                <span class="addr-field-error" id="err_receiver_name">Please enter recipient name</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="addr-field-group">
                                <label class="addr-field-label">Phone Number <span style="color:#c0392b;">*</span></label>
                                <input type="text" id="modal_receiver_phone" class="addr-field-input"
                                    placeholder="08xxxxxxxxxx" maxlength="15">
                                <span class="addr-field-error" id="err_receiver_phone">Enter a valid phone number (min. 9
                                    digits)</span>
                            </div>
                        </div>
                    </div>

                    {{-- Section: Address --}}
                    <div class="addr-section-divider">Address</div>
                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <div class="addr-field-group">
                                <label class="addr-field-label">Street Address <span
                                        style="color:#c0392b;">*</span></label>
                                <input type="text" id="modal_address_line1" class="addr-field-input"
                                    placeholder="Street name, house number, block, floor, etc.">
                                <span class="addr-field-error" id="err_address_line1">Please enter your street
                                    address</span>
                            </div>
                        </div>
                    </div>

                    {{-- Section: Region --}}
                    <div class="addr-section-divider">Region</div>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="addr-field-group">
                                <label class="addr-field-label">Province <span style="color:#c0392b;">*</span></label>
                                <select id="modalProvinceSelect" class="addr-field-input" style="appearance:auto;">
                                    <option value="">Select Province</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->code }}">{{ $province->name }}</option>
                                    @endforeach
                                </select>
                                <span class="addr-field-error" id="err_province">Please select a province</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="addr-field-group">
                                <label class="addr-field-label">City / Regency <span
                                        style="color:#c0392b;">*</span></label>
                                <select id="modalCitySelect" class="addr-field-input" style="appearance:auto;"
                                    onchange="loadDistricts(this.value)" disabled>
                                    <option value="">Select City</option>
                                </select>
                                <span class="addr-field-error" id="err_city">Please select a city</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="addr-field-group">
                                <label class="addr-field-label">District <span style="color:#c0392b;">*</span></label>
                                <select id="modalDistrictSelect" class="addr-field-input" style="appearance:auto;"
                                    onchange="loadVillages(this.value)" disabled>
                                    <option value="">Select District</option>
                                </select>
                                <span class="addr-field-error" id="err_district">Please select a district</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="addr-field-group">
                                <label class="addr-field-label">Village <span style="color:#c0392b;">*</span></label>
                                <select id="modalVillageSelect" class="addr-field-input" style="appearance:auto;"
                                    disabled>
                                    <option value="">Select Village</option>
                                </select>
                                <span class="addr-field-error" id="err_village">Please select a village</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="addr-field-group">
                                <label class="addr-field-label">Postal Code</label>
                                <div id="modal_postal_wrapper">
                                    <input type="text" id="modal_postal_code" name="postal_code"
                                        class="addr-field-input" placeholder="e.g. 60111">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="addr-modal-footer d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-light border px-4" data-bs-dismiss="modal"
                        style="border-radius: 8px; font-size: 13px; padding: 9px 20px;">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-sm text-white px-4"
                        style="background-color: #5c695d; border-radius: 8px; font-size: 13px; font-weight: 600; padding: 9px 20px;"
                        onclick="saveModalAddress()">
                        Save Address
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- Cancel Checkout Confirmation Modal --}}
    <div class="modal fade" id="cancelCheckoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content"
                style="border-radius: 14px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.12);">
                <div class="modal-body p-4 text-center">
                    <div
                        style="width: 48px; height: 48px; background: #f5f0e8; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#2a2318"
                            viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                            <path
                                d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z" />
                        </svg>
                    </div>
                    <h6 class="fw-bold text-jaced-dark mb-1" style="font-size: 16px;">Leave Checkout?</h6>
                    <p class="text-jaced-muted mb-4" style="font-size: 13px; line-height: 1.6;">
                        Your cart items will be kept. You can return to checkout anytime.
                    </p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-sm btn-light border px-4" data-bs-dismiss="modal"
                            style="border-radius: 8px; font-size: 13px;">
                            Stay
                        </button>
                        <a href="{{ route('shop') }}" class="btn btn-sm text-white px-4"
                            style="background-color: #2a2318; border-radius: 8px; font-size: 13px;"
                            onclick="localStorage.setItem('cartOpen', 'true')">
                            Yes, go back
                        </a>
                    </div>
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
        let lastInteractionWasKeyboard = false;

        // Toggle radio voucher - klik lagi untuk untick
        document.addEventListener('DOMContentLoaded', function() {

            // Fungsi pilih voucher card
            window.selectVoucherCard = function(cardEl, voucherId) {
                // Deselect semua card
                document.querySelectorAll('.voucher-card').forEach(c => c.classList.remove('is-selected'));

                const radio = cardEl.querySelector('.voucher-radio-input');
                const preview = document.getElementById('voucherPreview');
                const previewBar = document.getElementById('voucherPreviewBar');
                const text = document.getElementById('voucherPreviewText');

                // Toggle: klik card yang sama = deselect
                if (radio.checked) {
                    radio.checked = false;
                    preview.classList.add('d-none');
                    return;
                }

                // Select
                radio.checked = true;
                cardEl.classList.add('is-selected');

                const usedFor = radio.getAttribute('data-used-for');
                const name = radio.getAttribute('data-name');
                const discount = radio.getAttribute('data-discount');

                if (usedFor === 'delivery') {
                    previewBar.className = 'voucher-preview-bar shipping';
                    text.innerHTML =
                        `<strong>${name}</strong> — Free shipping up to Rp ${parseFloat(discount).toLocaleString('id-ID')}`;
                } else {
                    previewBar.className = 'voucher-preview-bar product';
                    text.innerHTML =
                        `<strong>${name}</strong> — Discount up to Rp ${parseFloat(discount).toLocaleString('id-ID')}`;
                }
                preview.classList.remove('d-none');
            };

            // Modal & address
            document.addEventListener('keydown', () => lastInteractionWasKeyboard = true);
            document.addEventListener('mousedown', () => lastInteractionWasKeyboard = false);
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
                                            Free Shipping
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
            @if (isset($pendingVoucher) && $pendingVoucher)
                window.pendingVoucherData = {
                    id: '{{ $pendingVoucher->id }}',
                    usedFor: '{{ $pendingVoucher->used_for }}',
                    maxDiscount: {{ (float) $pendingVoucher->max_discount }},
                    discountPercentage: {{ (float) $pendingVoucher->discount_percentage }},
                };
                document.getElementById('applied-voucher-id').value = '{{ $pendingVoucher->id }}';

                const textDisplay = document.getElementById('selectedVoucherText');
                @if ($pendingVoucher->used_for === 'delivery')
                    textDisplay.innerHTML = `
                        <span class="px-2 py-1 rounded fw-bold d-inline-flex align-items-center gap-1" style="background-color: #f1f4f2; color: #5c695d; border: 1px solid #5c695d; font-size: 11px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="#5c695d" viewBox="0 0 16 16">
                                <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h13A1.5 1.5 0 0 1 16 3.5v2a.5.5 0 0 1-.5.5c-.75 0-1.5.75-1.5 1.5S14.75 9 15.5 9a.5.5 0 0 1 .5.5v2a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 11.5v-2a.5.5 0 0 1 .5-.5C1.25 9 2 8.25 2 7.5S1.25 6 .5 6a.5.5 0 0 1-.5-.5v-2z"/>
                            </svg>
                            Free Shipping
                        </span>
                        <span class="ms-1" style="font-size: 13px;">{{ $pendingVoucher->name }}</span>
                    `;
                @else
                    textDisplay.innerHTML = `
                        <span class="px-2 py-1 rounded fw-bold d-inline-flex align-items-center gap-1" style="background-color: #fcf5f3; color: #bd654e; border: 1px solid #bd654e; font-size: 11px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="#bd654e" viewBox="0 0 16 16">
                                <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h13A1.5 1.5 0 0 1 16 3.5v2a.5.5 0 0 1-.5.5c-.75 0-1.5.75-1.5 1.5S14.75 9 15.5 9a.5.5 0 0 1 .5.5v2a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 11.5v-2a.5.5 0 0 1 .5-.5C1.25 9 2 8.25 2 7.5S1.25 6 .5 6a.5.5 0 0 1-.5-.5v-2z"/>
                            </svg>
                            Product Discount
                        </span>
                        <span class="ms-1" style="font-size: 13px;">{{ $pendingVoucher->name }}</span>
                    `;
                @endif

                document.getElementById('voucherActiveBadge').classList.remove('d-none');
                voucherApplied = true;
                document.querySelector('.voucher-trigger')?.classList.add('has-voucher');
                calculateGrandTotal();

                fetch('{{ route('voucher.clear-session') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
            @endif

            document.getElementById('btnBackToCart')?.addEventListener('click', function() {
                new bootstrap.Modal(document.getElementById('cancelCheckoutModal')).show();
            });
        });

        function applyVoucherVisual() {
            const selectedVoucher = document.querySelector('.voucher-radio-input:checked');

            if (!selectedVoucher || selectedVoucher.value === "") {
                clearSelectedVoucher();
                return;
            }

            const voucherId = selectedVoucher.value;
            const voucherName = selectedVoucher.getAttribute('data-name');
            const usedFor = selectedVoucher.getAttribute('data-used-for');

            document.getElementById('applied-voucher-id').value = voucherId;

            const textDisplay = document.getElementById('selectedVoucherText');
            const activeBadge = document.getElementById('voucherActiveBadge');

            // Warna badge sesuai jenis voucher
            if (usedFor === 'delivery') {
                textDisplay.innerHTML = `
                    <span class="px-2 py-1 rounded fw-bold d-inline-flex align-items-center gap-1" 
                        style="background-color: #f1f4f2; color: #5c695d; border: 1px solid #5c695d; font-size: 11px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="#5c695d" viewBox="0 0 16 16">
                            <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h13A1.5 1.5 0 0 1 16 3.5v2a.5.5 0 0 1-.5.5c-.75 0-1.5.75-1.5 1.5S14.75 9 15.5 9a.5.5 0 0 1 .5.5v2a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 11.5v-2a.5.5 0 0 1 .5-.5C1.25 9 2 8.25 2 7.5S1.25 6 .5 6a.5.5 0 0 1-.5-.5v-2z"/>
                        </svg>
                        Free Shipping
                    </span>
                    <span class="ms-1" style="font-size: 13px;">${voucherName}</span>
                `;
            } else {
                textDisplay.innerHTML = `
                    <span class="px-2 py-1 rounded fw-bold d-inline-flex align-items-center gap-1" 
                        style="background-color: #fcf5f3; color: #bd654e; border: 1px solid #bd654e; font-size: 11px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="#bd654e" viewBox="0 0 16 16">
                            <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h13A1.5 1.5 0 0 1 16 3.5v2a.5.5 0 0 1-.5.5c-.75 0-1.5.75-1.5 1.5S14.75 9 15.5 9a.5.5 0 0 1 .5.5v2a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 11.5v-2a.5.5 0 0 1 .5-.5C1.25 9 2 8.25 2 7.5S1.25 6 .5 6a.5.5 0 0 1-.5-.5v-2z"/>
                        </svg>
                        Product Discount
                    </span>
                    <span class="ms-1" style="font-size: 13px;">${voucherName}</span>
                `;
            }

            if (activeBadge) activeBadge.classList.remove('d-none');

            voucherApplied = true;
            document.querySelector('.voucher-trigger')?.classList.add('has-voucher');
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
            document.getElementById('addressModalLabel').innerText = "New Address Details";
            document.getElementById('modal_action_mode').value = "add";
            document.getElementById('modal_address_id').value = "";

            // Reset isi inputan form modal
            document.getElementById('modal_receiver_name').value = "";
            document.getElementById('modal_receiver_phone').value = "";
            document.getElementById('modal_address_line1').value = "";
            // Reset postal wrapper ke input kosong, bukan cuma value-nya
            document.getElementById('modal_postal_wrapper').innerHTML =
                '<input type="text" id="modal_postal_code" class="form-control form-control-sm" placeholder="10001">';
            provinceTS.setValue('', true);
            // Reset validation state
            ['modal_receiver_name', 'modal_receiver_phone', 'modal_address_line1'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.classList.remove('is-invalid', 'is-valid');
                }
            });
            ['err_receiver_name', 'err_receiver_phone', 'err_address_line1',
                'err_province', 'err_city', 'err_district', 'err_village'
            ].forEach(id => {
                document.getElementById(id)?.classList.remove('visible');
            });

            // Destroy TomSelect instances lama sebelum reset HTML
            ['modalCitySelect', 'modalDistrictSelect', 'modalVillageSelect'].forEach(id => {
                if (window[id + '_ts']) {
                    window[id + '_ts'].destroy();
                    window[id + '_ts'] = null;
                }
            });

            document.getElementById('modalCitySelect').innerHTML = '<option value="">Select City</option>';
            document.getElementById('modalDistrictSelect').innerHTML = '<option value="">Select District</option>';
            document.getElementById('modalVillageSelect').innerHTML = '<option value="">Select Village</option>';
            document.getElementById('modalCitySelect').disabled = true;
            document.getElementById('modalDistrictSelect').disabled = true;
            document.getElementById('modalVillageSelect').disabled = true;

            bsModal.show();
        }

        /* Handler Buka Modal Mode: EDIT Alamat Lama */
        function openEditAddressModal(addressObj) {
            document.getElementById('addressModalLabel').innerText = "Edit Delivery Address";
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
                initOrRefreshTS('modalCitySelect', function(value) {
                    loadDistricts(value);
                });
                if (window['modalCitySelect_ts']) window['modalCitySelect_ts'].setValue(addressObj.city_code || '', true);

                // District
                const distSelect = document.getElementById('modalDistrictSelect');
                distSelect.innerHTML =
                    `<option value="${addressObj.district_code || ''}">${addressObj.district_name}</option>`;
                distSelect.disabled = false;
                initOrRefreshTS('modalDistrictSelect', function(value) {
                    loadVillages(value);
                });
                if (window['modalDistrictSelect_ts']) window['modalDistrictSelect_ts'].setValue(addressObj.district_code ||
                    '', true);

                // Village
                const villSelect = document.getElementById('modalVillageSelect');
                villSelect.innerHTML =
                    `<option value="${addressObj.village_code || ''}">${addressObj.village_name}</option>`;
                villSelect.disabled = false;
                initOrRefreshTS('modalVillageSelect', function(value) {
                    const originalSelect = document.getElementById('modalVillageSelect');
                    const selectedOption = originalSelect.querySelector(`option[value="${value}"]`);
                    const villageId = selectedOption?.getAttribute('data-id');
                    const postalWrapper = document.getElementById('modal_postal_wrapper');

                    postalWrapper.innerHTML =
                        `<input type="text" id="modal_postal_code" class="form-control form-control-sm" placeholder="10001">`;

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
                if (window['modalVillageSelect_ts']) window['modalVillageSelect_ts'].setValue(addressObj.village_code || '',
                    true);
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
            const citySelect = document.getElementById('modalCitySelect');
            const districtSelect = document.getElementById('modalDistrictSelect');
            const villageSelect = document.getElementById('modalVillageSelect');

            citySelect.innerHTML = '<option value="">Select City</option>';
            districtSelect.innerHTML = '<option value="">Select District</option>';
            villageSelect.innerHTML = '<option value="">Select Village</option>';
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
            const districtSelect = document.getElementById('modalDistrictSelect');
            const villageSelect = document.getElementById('modalVillageSelect');

            districtSelect.innerHTML = '<option value="">Select District</option>';
            villageSelect.innerHTML = '<option value="">Select Village</option>';
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
            const villageSelect = document.getElementById('modalVillageSelect');
            villageSelect.innerHTML = '<option value="">Select Village</option>';
            villageSelect.disabled = true;
            document.getElementById('modal_postal_code').value = '';

            if (!districtCode) return;

            fetch(`/api/villages?district_code=${districtCode}`)
                .then(res => res.json())
                .then(villages => {
                    villages.forEach(v => {
                        villageSelect.innerHTML +=
                            `<option value="${v.code}" data-id="${v.id}">${v.name}</option>`;
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
                                    let options = codes.map(c => `<option value="${c}">${c}</option>`).join(
                                        '');
                                    postalWrapper.innerHTML = `
                                        <select id="modal_postal_code" class="form-select form-select-sm">
                                            <option value="">Select Postal Code</option>
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


        function clearFieldError(fieldId, errId) {
            const el = document.getElementById(fieldId);
            const err = document.getElementById(errId);
            if (el) el.classList.remove('is-invalid');
            if (err) err.classList.remove('visible');
        }

        function showFieldError(fieldId, errId) {
            const el = document.getElementById(fieldId);
            const err = document.getElementById(errId);
            if (el) el.classList.add('is-invalid');
            if (err) err.classList.add('visible');
        }

        function validatePhone(phone) {
            return /^(\+62|62|0)[0-9]{8,13}$/.test(phone.replace(/\s|-/g, ''));
        }

        function saveModalAddress() {
            const actionMode = document.getElementById('modal_action_mode').value;
            const addressId = document.getElementById('modal_address_id').value;

            const receiverName = document.getElementById('modal_receiver_name').value.trim();
            const receiverPhone = document.getElementById('modal_receiver_phone').value.trim();
            const addressLine = document.getElementById('modal_address_line1').value.trim();
            const postalCode = document.getElementById('modal_postal_code')?.value?.trim() || '';

            const pSel = document.getElementById('modalProvinceSelect');
            const cSel = document.getElementById('modalCitySelect');
            const dSel = document.getElementById('modalDistrictSelect');
            const vSel = document.getElementById('modalVillageSelect');

            // Reset semua error dulu
            ['modal_receiver_name', 'modal_receiver_phone', 'modal_address_line1'].forEach(id => {
                document.getElementById(id)?.classList.remove('is-invalid', 'is-valid');
            });
            ['err_receiver_name', 'err_receiver_phone', 'err_address_line1',
                'err_province', 'err_city', 'err_district', 'err_village'
            ].forEach(id => {
                document.getElementById(id)?.classList.remove('visible');
            });
            ['modalProvinceSelect', 'modalCitySelect', 'modalDistrictSelect', 'modalVillageSelect'].forEach(id => {
                document.getElementById(id)?.closest('.ts-wrapper')?.classList.remove('is-invalid');
            });

            let hasError = false;

            // Validasi name
            if (!receiverName) {
                showFieldError('modal_receiver_name', 'err_receiver_name');
                hasError = true;
            } else {
                document.getElementById('modal_receiver_name').classList.add('is-valid');
            }

            // Validasi phone
            if (!receiverPhone || !validatePhone(receiverPhone)) {
                showFieldError('modal_receiver_phone', 'err_receiver_phone');
                hasError = true;
            } else {
                document.getElementById('modal_receiver_phone').classList.add('is-valid');
            }

            // Validasi address line
            if (!addressLine) {
                showFieldError('modal_address_line1', 'err_address_line1');
                hasError = true;
            } else {
                document.getElementById('modal_address_line1').classList.add('is-valid');
            }

            // Validasi province
            const provinceVal = provinceTS?.getValue() || pSel.value;
            if (!provinceVal) {
                document.getElementById('err_province')?.classList.add('visible');
                document.getElementById('modalProvinceSelect')?.closest('.ts-wrapper')?.classList.add('is-invalid');
                hasError = true;
            }

            // Validasi city
            if (!cSel.value) {
                document.getElementById('err_city')?.classList.add('visible');
                document.getElementById('modalCitySelect')?.closest('.ts-wrapper')?.classList.add('is-invalid');
                hasError = true;
            }

            // Validasi district
            if (!dSel.value) {
                document.getElementById('err_district')?.classList.add('visible');
                document.getElementById('modalDistrictSelect')?.closest('.ts-wrapper')?.classList.add('is-invalid');
                hasError = true;
            }

            // Validasi village
            if (!vSel.value) {
                document.getElementById('err_village')?.classList.add('visible');
                document.getElementById('modalVillageSelect')?.closest('.ts-wrapper')?.classList.add('is-invalid');
                hasError = true;
            }

            if (hasError) return;

            // Baca nama-nama wilayah
            const provinceOpt = document.querySelector(`#modalProvinceSelect option[value="${provinceVal}"]`);
            const provinceName = provinceOpt?.text || '';
            const cityTS_val = window['modalCitySelect_ts'];
            const distTS_val = window['modalDistrictSelect_ts'];
            const villTS_val = window['modalVillageSelect_ts'];

            const cityName = cityTS_val ? cityTS_val.getOption(cityTS_val.getValue())?.textContent?.trim() : (cSel.options[
                cSel.selectedIndex]?.text || '');
            const districtName = distTS_val ? distTS_val.getOption(distTS_val.getValue())?.textContent?.trim() : (dSel
                .options[dSel.selectedIndex]?.text || '');
            const villageName = villTS_val ? villTS_val.getOption(villTS_val.getValue())?.textContent?.trim() : (vSel
                .options[vSel.selectedIndex]?.text || '');

            const container = document.getElementById('hiddenAddressMutationContainer');
            let wrapper = document.querySelector('.shopee-address-wrapper');
            if (!wrapper) {
                wrapper = document.createElement('div');
                wrapper.className = 'shopee-address-wrapper mb-3';
                const addBtn = document.querySelector('.btn-add-address-shopee') || document.querySelector(
                    '.btn-outline-secondary');
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
                    <input type="hidden" name="province_name" value="${provinceName}">
                    <input type="hidden" name="city_code" value="${cSel.value}">
                    <input type="hidden" name="city_name" value="${cityName}">
                    <input type="hidden" name="district_code" value="${dSel.value}">
                    <input type="hidden" name="district_name" value="${districtName}">
                    <input type="hidden" name="village_code" value="${vSel.value}">
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
                                <span class="fw-bold text-jaced-dark" style="font-size: 14px;">${receiverName}</span>
                                <span class="text-jaced-muted small">|</span>
                                <span class="text-jaced-muted small">${receiverPhone}</span>
                                <span class="badge bg-dark text-white ms-2" style="font-size: 9px; padding: 2px 4px;">New</span>
                            </div>
                            <div class="text-jaced-dark small mb-1">${addressLine}</div>
                            <div class="text-jaced-muted tiny" style="font-size: 12px;">
                                ${villageName}, ${districtName}, ${cityName}, ${provinceName}, ${postalCode}
                            </div>
                        </div>
                        <div class="pt-1">
                            <button type="button" class="btn-edit-address" onclick="alert('This address is temporary. Complete checkout to save it permanently.')">Edit</button>
                        </div>
                    </div>
                `;

                const oldTemp = document.getElementById('temp_lbl');
                if (oldTemp) oldTemp.remove();

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
                    <input type="hidden" name="province_name" value="${provinceName}">
                    <input type="hidden" name="city_code" value="${cSel.value}">
                    <input type="hidden" name="city_name" value="${cityName}">
                    <input type="hidden" name="district_code" value="${dSel.value}">
                    <input type="hidden" name="district_name" value="${districtName}">
                    <input type="hidden" name="village_code" value="${vSel.value}">
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

            shippingSection.innerHTML = `
                <div class="shipping-skeleton"></div>
                <div class="shipping-skeleton" style="width:75%;"></div>
                <div class="shipping-skeleton" style="width:55%;"></div>
            `;

            // Setelah diubah
            fetch(
                    `/api/shipping-cost?village_name=${encodeURIComponent(villageName)}&city_name=${encodeURIComponent(cityName)}&weight={{ $totalWeight }}`)
                .then(res => res.json())
                .then(costs => {
                    if (costs.error || !costs.length) {
                        shippingSection.innerHTML =
                            `<p class="text-danger small">No courier available for this area.</p>`;
                        return;
                    }

                    costs.sort((a, b) => a.cost - b.cost);

                    let html = '<div class="row g-2 mt-1">';
                    costs.forEach((item, index) => {
                        html += `
                        <div class="col-12">
                            <label class="shipping-option-label">
                                <div class="d-flex align-items-center gap-3">
                                    <input type="radio" name="selected_courier" value="${item.cost}"
                                        data-cost="${item.cost}" onchange="updateDeliveryFee(${item.cost})" ${index === 0 ? 'checked' : ''}
                                        style="accent-color:#5c695d; width:16px; height:16px; flex-shrink:0;">
                                    <div>
                                        <div class="fw-semibold" style="color:#2a2318;">${item.name} <span style="color:#8c7e6c; font-weight:400;">(${item.service})</span></div>
                                        <div style="font-size: 11px; color:#8c7e6c; margin-top:1px;">${item.etd}</div>
                                    </div>
                                </div>
                                <span style="font-weight:700; color:#2a2318; font-size:13px;">Rp ${item.cost.toLocaleString('id-ID')}</span>
                            </label>
                        </div>`;
                    });
                    html += '</div>';

                    shippingSection.innerHTML = html;
                    updateDeliveryFee(costs[0].cost);
                })
                .catch(() => {
                    shippingSection.innerHTML = '<p class="text-danger small">Failed to calculate shipping cost.</p>';
                });
        }

        function updateDeliveryFee(cost) {
            currentDeliveryFee = cost;

            const display = document.getElementById('deliveryFeeDisplay');
            const input = document.getElementById('deliveryFeeInput');

            if (display) display.innerText = 'Rp ' + cost.toLocaleString('id-ID');
            if (input) input.value = cost;

            calculateGrandTotal();
            checkQrisLimit();
        }


        function clearSelectedVoucher() {
            voucherApplied = false;
            document.querySelector('.voucher-trigger')?.classList.remove('has-voucher');
            document.getElementById('applied-voucher-id').value = "";
            document.getElementById('selectedVoucherText').innerText = "Use a Voucher";
            document.getElementById('selectedVoucherText').classList.remove('fw-bold', 'text-success');
            document.getElementById('voucherActiveBadge').classList.add('d-none');

            const checkedRadio = document.querySelector('.voucher-radio-input:checked');
            if (checkedRadio) checkedRadio.checked = false;
            document.querySelectorAll('.voucher-card').forEach(c => c.classList.remove('is-selected'));
            document.getElementById('voucherPreview')?.classList.add('d-none');

            const rdDelivery = document.getElementById('row-discount-delivery');
            const rdProduct = document.getElementById('row-discount-product');
            if (rdDelivery) rdDelivery.classList.add('d-none');
            if (rdProduct) rdProduct.classList.add('d-none');

            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            const subtotal = {{ $subtotal ?? 0 }};
            const tax = {{ $tax ?? 0 }};
            const tierDiscount = {{ $tierDiscountAmount ?? 0 }};

            let discountValue = 0;

            const rowDelivery = document.getElementById('row-discount-delivery');
            const rowProduct = document.getElementById('row-discount-product');
            if (rowDelivery) rowDelivery.classList.add('d-none');
            if (rowProduct) rowProduct.classList.add('d-none');

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
                        if (rowDelivery) rowDelivery.classList.add('d-none');
                    } else {
                        discountValue = Math.min(currentDeliveryFee, maxDiscount);
                        if (rowDelivery) rowDelivery.classList.remove('d-none');
                        document.querySelectorAll('#summary-discount-delivery').forEach(el => {
                            el.innerText = 'Rp ' + discountValue.toLocaleString('id-ID');
                        });
                    }
                } else if (usedFor) {
                    discountValue = Math.min(subtotal * (discountPercentage / 100), maxDiscount);
                    if (rowProduct) rowProduct.classList.remove('d-none');
                    document.querySelectorAll('#summary-discount-product').forEach(el => {
                        el.innerText = 'Rp ' + discountValue.toLocaleString('id-ID');
                    });
                }
            }

            const finalTotal = (subtotal + tax + currentDeliveryFee) - discountValue - tierDiscount; // ✅ kurangkan di sini
            document.querySelectorAll('#totalDisplay').forEach(el => {
                el.innerText = 'Rp ' + finalTotal.toLocaleString('id-ID');
                el.classList.remove('updating');
                void el.offsetWidth; // force reflow agar animasi re-trigger
                el.classList.add('updating');
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
                alert('Please select a shipping method first.');
                return;
            }

            if (!paymentMethod) {
                e.preventDefault();
                alert('Please select a payment method.');
                return;
            }
        });

        function checkQrisLimit() {
            const paymentMethod = document.getElementById('paymentMethod').value;
            const warning = document.getElementById('qris-warning');
            const submitBtn = document.querySelector('#checkoutForm button[type="submit"]');
            const subtotal = {{ $subtotal ?? 0 }};
            const tax = {{ $tax ?? 0 }};
            const tierDiscount = {{ $tierDiscountAmount ?? 0 }};

            const voucherDiscount = parseFloat(document.getElementById('applied-discount-amount').value) || 0;
            const currentTotal = subtotal + tax + currentDeliveryFee - tierDiscount - voucherDiscount;

            if (paymentMethod === 'qris' && currentTotal > 10000000) {
                if (warning) warning.classList.remove('d-none');
                if (submitBtn) submitBtn.disabled = true;
            } else {
                if (warning) warning.classList.add('d-none');
                if (submitBtn) submitBtn.disabled = false;
            }
        }

        document.getElementById('paymentMethod').addEventListener('change', function() {
            handlePaymentChange(this.value);
            checkQrisLimit();
        });
    </script>
@endpush
