@extends('base.base')

@push('styles')
<style>
    /* Wrapper invoice */
    .invoice-wrap {
        max-width: 800px;
        margin: 40px auto;
        background: white;
        border-radius: 16px;
        padding: 48px;
        box-shadow: 0 2px 20px rgba(0,0,0,.07);
    }

    /* Styling untuk pembungkus logo dan brand */
    .brand-wrapper {
        display: flex;
        align-items: center; /* Biar logo dan tulisan sejajar tengah */
        gap: 15px; /* Spasi antara logo dan tulisan */
    }

    /* Styling untuk gambar logo */
    .inv-logo {
        width: 60px; /* Atur lebar logo (misal 60px) */
        height: 60px; /* Atur tinggi logo (biar proporsional) */
        object-fit: contain; /* Biar gambar gak penyok */
        display: block; /* Mencegah spasi aneh di bawah gambar */
    }

    /* Sembunyikan district_name kosong */
    .ship-address p:empty { display: none; }

    /* Typography */
    .inv-brand       { font-family: 'DM Serif Display', Arial, serif; font-size: 28px; font-weight: 800; color: #2a2318; line-height: 1.1; }
    .inv-brand-sub   { font-size: 11px; color: #999; margin-top: 4px; letter-spacing: 0.05em; }
    .inv-title       { font-family: 'DM Serif Display', Arial, serif; font-size: 24px; color: #2a2318; font-weight: 600; }
    .inv-id          { font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #999; margin-top: 4px; }
    .inv-date        { font-size: 12px; color: #888; margin-top: 4px; }
    .inv-section-label {
        font-size: 10px; font-weight: 700; letter-spacing: .1em;
        text-transform: uppercase; color: #aaa; margin-bottom: 8px;
    }

    /* Header */
    .inv-header {
        display: flex; justify-content: space-between; align-items: flex-start;
        padding-bottom: 24px; border-bottom: 1px solid #e8e4de; margin-bottom: 32px;
    }

    /* Info grid */
    .inv-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 28px; }
    .inv-grid p { font-size: 13px; color: #2a2318; line-height: 1.7; margin: 0; }
    .inv-grid .name { font-weight: 700; font-size: 14px; margin-bottom: 2px; }

    /* Table */
    .inv-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
    .inv-table thead th {
        font-size: 10px; font-weight: 700; letter-spacing: .08em;
        text-transform: uppercase; color: #aaa;
        padding: 0 0 12px; border-bottom: 1px solid #e8e4de; text-align: left;
    }
    .inv-table thead th:nth-child(2) { text-align: center; }
    .inv-table thead th:last-child { text-align: right; padding-right: 4px; }
    
    .inv-table tbody td { padding: 16px 0; border-bottom: 1px solid #f0ece6; font-size: 13px; color: #2a2318; }
    .inv-table tbody td:nth-child(2) { text-align: center; color: #555; }
    .inv-table tbody td:last-child { text-align: right; font-weight: 700; padding-right: 4px; }

    /* Totals */
    .inv-totals { margin-left: auto; width: 300px; margin-top: 12px; }
    .inv-totals-row { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 10px; color: #555; padding-right: 4px; }
    .inv-totals-row.grand { font-size: 16px; font-weight: 700; color: #2a2318; margin-top: 4px; }
    .inv-totals-row.discount { color: #4a7c59; font-weight: 600; }
    .inv-divider { border: none; border-top: 1px solid #e8e4de; margin: 12px 0; }

    /* Footer */
    .inv-footer {
        margin-top: 64px; padding-top: 24px; border-top: 1px solid #e8e4de;
        display: flex; justify-content: space-between; align-items: center;
    }
    .inv-footer-note { font-size: 11px; color: #aaa; line-height: 1.7; }

    /* Print bar */
    .print-bar {
        max-width: 800px; margin: 24px auto 0;
        display: flex; justify-content: flex-end; gap: 10px; padding: 0 4px;
    }
    .btn-print {
        background: #2a2318; color: white; border: none; border-radius: 8px;
        padding: 10px 24px; font-size: 13px; font-weight: 600; cursor: pointer;
        transition: background 0.2s;
    }
    .btn-print:hover { background: #403525; }
    .btn-back-inv {
        color: #666; font-size: 13px; text-decoration: none; font-weight: 500;
        border: 1px solid #ddd; border-radius: 8px; padding: 10px 18px;
        background: white; cursor: pointer; transition: all 0.2s;
    }
    .btn-back-inv:hover { background: #f9f9f9; color: #2a2318; border-color: #bbb; }

    /* Status badge */
    .status-badge { font-size: 10px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; padding: 5px 14px; border-radius: 999px; display: inline-block; margin-top: 8px; }
    .status-badge.unpaid    { color: #7a6a3a; background: #f5f0e0; }
    .status-badge.packed    { color: #8a6a2a; background: #f5ecd5; }
    .status-badge.delivered { color: #4a7c59; background: #e4f0e8; }
    .status-badge.arrived   { color: #a33d3d; background: #f5e4e4; }
    .status-badge.cancelled { color: #777; background: #eee; }

    @media print {
        .print-bar, nav, header, footer,
        .jaced-navbar, .jaced-footer,
        .btn-print, .btn-back-inv,
        #chat-launcher, #chat-widget,
        .cursor-dot, .cursor-ring { display: none !important; }

        body { background: white !important; }

        .main-content, .container-fluid {
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
        }

        .invoice-wrap {
            box-shadow: none !important;
            margin: 0 auto !important;
            padding: 48px !important;
            border-radius: 0 !important;
            max-width: 800px !important;  /* ← pertahankan max-width asli */
            width: 100% !important;
        }

        /* Paksa scale 100%, jangan ada zoom */
        html {
            zoom: 1 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }

    @media (max-width: 767px) {
        .print-bar {
            flex-wrap: nowrap;
            gap: 8px;
            overflow-x: auto;
            justify-content: flex-start;
            padding: 0 20px;
            margin-bottom: 16px;
        }

        .btn-print,
        .btn-back-inv {
            font-size: 11px;
            padding: 8px 14px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .invoice-wrap {
            transform: scale(0.6);
            transform-origin: top center;
            width: 156%;      
            margin-left: -28%;
            margin-top: 0;
            margin-bottom: -280px;
        }
        
    }
</style>
@endpush

@section('content')
@php
    $statusLabel = ['unpaid'=>'Unpaid','packed'=>'Packed','delivered'=>'Delivered','arrived'=>'Arrived','cancelled'=>'Cancelled'];
    $subtotal = $order->orderDetails->sum('subtotal');
@endphp

<div class="print-bar" style="flex-direction: column; gap: 8px;">
    {{-- MOBILE: Back to Order di atas sendiri --}}
    <div class="d-md-none">
        <a href="{{ route('store.orderhistory_detail.show', $order->id) }}" class="btn-back-inv d-block text-center">
            ← Back to Order
        </a>
    </div>
    <div class="d-md-none d-flex gap-2">
        <form action="{{ route('store.orderhistory.invoice.send', $order->id) }}" method="POST" style="flex:1;">
            @csrf
            <button type="submit" class="btn-back-inv w-100" style="background:#f5f0e8; border-color:#c8b99a; color:#2a2318;">
                ✉ Send to Email
            </button>
        </form>
        <button class="btn-print" style="flex:1;" onclick="window.print()">Print / Save as PDF</button>
    </div>

    {{-- DESKTOP: 1 baris seperti semula --}}
    <div class="d-none d-md-flex gap-2 justify-content-end">
        <a href="{{ route('store.orderhistory_detail.show', $order->id) }}" class="btn-back-inv">← Back to Order</a>
        <form action="{{ route('store.orderhistory.invoice.send', $order->id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn-back-inv" style="background:#f5f0e8; border-color:#c8b99a; color:#2a2318;">
                ✉ Send to Email
            </button>
        </form>
        <button class="btn-print" onclick="window.print()">Print / Save as PDF</button>
    </div>
</div>

<div class="invoice-wrap">

    {{-- HEADER --}}
    <div class="inv-header">
        {{-- Pembungkus baru untuk logo dan teks brand --}}
        <div class="brand-wrapper">
            {{-- Panggil gambar logo kamu --}}
            <img src="{{ asset('image/jaced_logo1.png') }}" alt="Jaced Logo" class="inv-logo">
            
            <div>
                <div class="inv-brand">Jaced Furniture</div>
                <div class="inv-brand-sub">Artisan Furniture</div>
            </div>
        </div>
        <div style="text-align:right;">
            <div class="inv-title">Receipt</div>
            <div class="inv-id">Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</div>
            <div class="inv-date">{{ $order->created_at->format('F d, Y') }}</div>
            <span class="status-badge {{ $order->status }}">{{ $statusLabel[$order->status] ?? ucfirst($order->status) }}</span>
        </div>
    </div>

    {{-- BILLED TO & SHIP TO --}}
    <div class="inv-grid">
        <div>
            <div class="inv-section-label">Billed To</div>
            <p class="name">{{ $order->user->name }}</p>
            <p>{{ $order->user->email }}</p>
            @if ($order->user->phone_number)
                <p>{{ $order->user->phone_number }}</p>
            @endif
        </div>
        <div class="ship-address">
            <div class="inv-section-label">Ship To</div>
            @if ($order->shippingAddress)
                <p class="name">{{ $order->shippingAddress->receiver_name }}</p>
                <p>{{ $order->shippingAddress->address_line1 }}</p>
                <p>{{ $order->shippingAddress->village_name }}, {{ $order->shippingAddress->district_name }}</p>
                <p>{{ $order->shippingAddress->city_name }}, {{ $order->shippingAddress->province_name }} {{ $order->shippingAddress->postal_code }}</p>
            @else
                <p style="color: #999;">—</p>
            @endif
        </div>
    </div>

    {{-- PAYMENT METHOD --}}
    <div style="margin-bottom: 36px;">
        <div class="inv-section-label">Payment Method</div>
        <p style="font-size:13px; color:#2a2318; font-weight: 600; margin-top:4px;">{{ ucwords(str_replace('_', ' ', $order->paymentMethod->name ?? '-')) }}</p>
    </div>

    {{-- ITEMS --}}
    <table class="inv-table">
        <thead>
            <tr>
                <th>Item</th>
                <th style="text-align:center;">Qty</th>
                <th style="text-align:right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orderDetails as $detail)
                <tr>
                    <td><span style="font-weight: 600;">{{ $detail->product?->name ?? '—' }}</span></td>
                    <td>{{ $detail->quantity }}</td>
                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TOTALS --}}
    <div class="inv-totals">
        <div class="inv-totals-row">
            <span>Subtotal</span>
            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
        </div>
        <div class="inv-totals-row">
            <span>Delivery fee</span>
            <span>Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
        </div>
        <div class="inv-totals-row">
            <span>Service tax</span>
            <span>Rp {{ number_format($order->service_tax, 0, ',', '.') }}</span>
        </div>
        @if ($order->discount_amount > 0)
            <div class="inv-totals-row discount">
                <span>Discount</span>
                <span>− Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
            </div>
        @endif
        <hr class="inv-divider">
        <div class="inv-totals-row grand">
            <span>Total</span>
            <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="inv-footer">
        <div class="inv-footer-note">
            Thank you for your purchase.<br>
            For any questions, contact us at <a href="mailto:ptdiahloka2006@gmail.com" style="color: #2a2318; text-decoration: none; font-weight: 500;">ptdiahloka2006@gmail.com</a>
        </div>
        <div class="inv-footer-note" style="text-align:right;">
            <strong>Jaced Furniture</strong><br>
            <span style="color: #888;">Surabaya, Indonesia</span>
        </div>
    </div>

</div>
@endsection