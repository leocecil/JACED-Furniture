<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #2a2318; margin: 0; padding: 40px; }

    .inv-header { display: table; width: 100%; padding-bottom: 20px; border-bottom: 2px solid #e8e4de; margin-bottom: 28px; }
    .inv-header-left { display: table-cell; vertical-align: top; }
    .inv-header-right { display: table-cell; vertical-align: top; text-align: right; }

    .inv-brand { font-size: 22px; font-weight: 800; color: #2a2318; }
    .inv-brand-sub { font-size: 10px; color: #999; margin-top: 2px; letter-spacing: .05em; }
    .inv-title { font-size: 20px; font-weight: 700; }
    .inv-id { font-size: 10px; color: #999; margin-top: 4px; letter-spacing: .08em; text-transform: uppercase; }
    .inv-date { font-size: 11px; color: #888; margin-top: 2px; }

    .inv-section-label { font-size: 9px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #aaa; margin-bottom: 6px; }

    .inv-grid { display: table; width: 100%; margin-bottom: 24px; }
    .inv-grid-cell { display: table-cell; width: 50%; vertical-align: top; font-size: 12px; line-height: 1.7; padding-right: 16px; }
    .name { font-weight: 700; font-size: 13px; }

    .payment-section { margin-bottom: 28px; font-size: 13px; }

    table.items { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    table.items thead th { font-size: 9px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #aaa; padding: 0 0 10px; border-bottom: 1px solid #e8e4de; text-align: left; }
    table.items thead th.center { text-align: center; }
    table.items thead th.right { text-align: right; }
    table.items tbody td { padding: 12px 0; border-bottom: 1px solid #f0ece6; font-size: 12px; }
    table.items tbody td.center { text-align: center; color: #555; }
    table.items tbody td.right { text-align: right; font-weight: 700; }

    table.totals { width: 280px; float: right; font-size: 12px; border-collapse: collapse; margin-top: 8px; }
    table.totals td { padding: 5px 0; color: #555; }
    table.totals td.right { text-align: right; }
    table.totals tr.grand td { font-size: 14px; font-weight: 700; color: #2a2318; padding-top: 10px; }
    table.totals tr.discount td { color: #4a7c59; }
    .divider { border: none; border-top: 1px solid #e8e4de; margin: 8px 0; }

    .footer { margin-top: 60px; padding-top: 16px; border-top: 1px solid #e8e4de; font-size: 11px; color: #aaa; line-height: 1.7; }
</style>
</head>
<body>

{{-- HEADER --}}
<div class="inv-header">
    <div class="inv-header-left">
        <img src="{{ $logoPath }}" style="width:50px; height:50px; object-fit:contain; display:block; margin-bottom:6px;">
        <div class="inv-brand">Jaced Furniture</div>
        <div class="inv-brand-sub">Artisan Furniture</div>
    </div>
    <div class="inv-header-right">
        <div class="inv-title">Invoice</div>
        <div class="inv-id">Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</div>
        <div class="inv-date">{{ $order->created_at->format('F d, Y') }}</div>
    </div>
</div>

{{-- BILLED TO & SHIP TO --}}
<div class="inv-grid">
    <div class="inv-grid-cell">
        <div class="inv-section-label">Billed To</div>
        <div class="name">{{ $order->user->name }}</div>
        <div>{{ $order->user->email }}</div>
        @if($order->user->phone_number)
            <div>{{ $order->user->phone_number }}</div>
        @endif
    </div>
    <div class="inv-grid-cell">
        <div class="inv-section-label">Ship To</div>
        @if($order->shippingAddress)
            <p class="name">{{ $order->shippingAddress->receiver_name }}</p>
            <p>{{ $order->shippingAddress->address_line1 }}</p>
            <p>{{ $order->shippingAddress->village_name }}, {{ $order->shippingAddress->district_name }}</p>
            <p>{{ $order->shippingAddress->city_name }}, {{ $order->shippingAddress->province_name }} {{ $order->shippingAddress->postal_code }}</p>
        @else
            <div style="color:#999;">—</div>
        @endif
    </div>
</div>

{{-- PAYMENT METHOD --}}
<div class="payment-section">
    <div class="inv-section-label">Payment Method</div>
    <div style="font-weight:600; margin-top:4px;">
        {{ ucwords(str_replace('_', ' ', $order->paymentMethod->name ?? '-')) }}
        @if($order->vaBank) - {{ strtoupper($order->vaBank->name) }} @endif
    </div>
</div>

{{-- ITEMS --}}
<table class="items">
    <thead>
        <tr>
            <th>Item</th>
            <th class="center">Qty</th>
            <th class="right">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->orderDetails as $detail)
        <tr>
            <td style="font-weight:600;">{{ $detail->product?->name ?? '—' }}</td>
            <td class="center">{{ $detail->quantity }}</td>
            <td class="right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- TOTALS --}}
<table class="totals">
    <tr>
        <td>Subtotal</td>
        <td class="right">Rp {{ number_format($order->orderDetails->sum('subtotal'), 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td>Delivery fee</td>
        <td class="right">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td>Service tax</td>
        <td class="right">Rp {{ number_format($order->service_tax, 0, ',', '.') }}</td>
    </tr>
    @if($order->discount_amount > 0)
    <tr class="discount">
        <td>Discount</td>
        <td class="right">− Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
    </tr>
    @endif
    <tr><td colspan="2"><hr class="divider"></td></tr>
    <tr class="grand">
        <td>Total</td>
        <td class="right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
    </tr>
</table>

{{-- FOOTER --}}
<div style="clear:both;"></div>
<div class="footer">
    Thank you for your purchase. For questions, contact us at ptdiahloka2006@gmail.com<br>
    <strong style="color:#2a2318;">Jaced Artisan Furniture</strong> · Surabaya, Indonesia
</div>

</body>
</html>