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

    /* Sembunyikan district_name kosong */
    .ship-address p:empty { display: none; }

    /* Typography */
    .inv-brand       { font-family: 'DM Serif Display', serif; font-size: 26px; color: #2a2318; }
    .inv-brand-sub   { font-size: 11px; color: #999; margin-top: 3px; }
    .inv-title       { font-family: 'DM Serif Display', serif; font-size: 22px; color: #2a2318; }
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
    .inv-grid .name { font-weight: 600; font-size: 14px; }

    /* Table */
    .inv-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
    .inv-table thead th {
        font-size: 10px; font-weight: 700; letter-spacing: .08em;
        text-transform: uppercase; color: #aaa;
        padding: 0 0 10px; border-bottom: 1px solid #e8e4de; text-align: left;
    }
    .inv-table thead th:last-child { text-align: right; }
    .inv-table tbody td { padding: 14px 0; border-bottom: 1px solid #f0ece6; font-size: 13px; }
    .inv-table tbody td:nth-child(2) { text-align: center; color: #555; }
    .inv-table tbody td:last-child { text-align: right; }

    /* Totals */
    .inv-totals { margin-left: auto; width: 280px; }
    .inv-totals-row { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 8px; color: #555; }
    .inv-totals-row.grand { font-size: 16px; font-weight: 700; color: #2a2318; }
    .inv-totals-row.discount { color: #7a6a3a; }
    .inv-divider { border: none; border-top: 1px solid #e8e4de; margin: 12px 0; }

    /* Footer */
    .inv-footer {
        margin-top: 48px; padding-top: 20px; border-top: 1px solid #e8e4de;
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
        padding: 10px 24px; font-size: 13px; font-weight: 500; cursor: pointer;
    }
    .btn-back-inv {
        color: #888; font-size: 13px; text-decoration: none;
        border: 1px solid #ddd; border-radius: 8px; padding: 10px 18px;
        background: none; cursor: pointer;
    }

    /* Status badge */
    .status-badge { font-size: 10px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; padding: 4px 12px; border-radius: 999px; display: inline-block; margin-top: 8px; }
    .status-badge.unpaid    { color: #7a6a3a; background: #f5f0e0; }
    .status-badge.packed    { color: #8a6a2a; background: #f5ecd5; }
    .status-badge.delivered { color: #4a7c59; background: #e4f0e8; }
    .status-badge.arrived   { color: #a33d3d; background: #f5e4e4; }
    .status-badge.cancelled { color: #5a5a8a; background: #eeeef5; }

    @media print {
        .print-bar, nav, header, footer, .jaced-navbar, .jaced-footer { display: none !important; }
        .invoice-wrap { box-shadow: none; margin: 0; border-radius: 0; }
    }
</style>
@endpush

@section('content')
@php
    $statusLabel = ['unpaid'=>'Unpaid','packed'=>'Packed','delivered'=>'Delivered','arrived'=>'Arrived','cancelled'=>'Cancelled'];
    $subtotal = $order->orderDetails->sum('subtotal');
@endphp

<div class="print-bar">
    <a href="{{ route('store.orderhistory_detail.show', $order->id) }}" class="btn-back-inv">← Back to Order</a>
    <button class="btn-print" onclick="window.print()">Print / Save as PDF</button>
</div>

<div class="invoice-wrap">

    {{-- HEADER --}}
    <div class="inv-header">
        <div>
            <div class="inv-brand">Jaced</div>
            <div class="inv-brand-sub">Artisan Furniture</div>
        </div>
        <div style="text-align:right;">
            <div class="inv-title">Invoice</div>
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
                <p>{{ $order->shippingAddress->district_name }}, {{ $order->shippingAddress->city_name }}</p>
                <p>{{ $order->shippingAddress->province_name }} {{ $order->shippingAddress->postal_code }}</p>
            @else
                <p>—</p>
            @endif
        </div>
    </div>

    {{-- PAYMENT METHOD --}}
    <div style="margin-bottom: 28px;">
        <div class="inv-section-label">Payment Method</div>
        <p style="font-size:13px; color:#2a2318; margin-top:4px;">{{ $order->paymentMethod?->name ?? '—' }}</p>
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
                    <td><strong>{{ $detail->product?->name ?? '—' }}</strong></td>
                    <td>{{ $detail->quantity }}</td>
                    <td><strong>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TOTALS --}}
    <div class="inv-totals">
        <div class="inv-totals-row"><span>Subtotal</span><span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>
        <div class="inv-totals-row"><span>Delivery fee</span><span>Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span></div>
        <div class="inv-totals-row"><span>Service tax</span><span>Rp {{ number_format($order->service_tax, 0, ',', '.') }}</span></div>
        @if ($order->discount_amount > 0)
            <div class="inv-totals-row discount"><span>Discount</span><span>− Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span></div>
        @endif
        <hr class="inv-divider">
        <div class="inv-totals-row grand"><span>Total</span><span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></div>
    </div>

    {{-- FOOTER --}}
    <div class="inv-footer">
        <div class="inv-footer-note">
            Thank you for your purchase.<br>
            For any questions, contact us at hello@jaced.id
        </div>
        <div class="inv-footer-note" style="text-align:right;">
            Jaced Artisan Furniture<br>
            jaced.id
        </div>
    </div>

</div>
@endsection