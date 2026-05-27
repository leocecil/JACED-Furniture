<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background:#f5f0e8; margin:0; padding:20px; }
        .container { max-width:600px; margin:0 auto; background:white; border-radius:12px; overflow:hidden; }
        .header { background:#3D2B1A; padding:24px; text-align:center; }
        .header h1 { color:white; margin:0; font-size:22px; }
        .body { padding:32px; }
        .order-id { font-size:13px; color:#888; margin-bottom:24px; }
        .section-title { font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#3D2B1A; margin:20px 0 10px; }
        .footer { background:#f5f0e8; padding:16px; text-align:center; font-size:11px; color:#888; }
        .btn { display:inline-block; background:#B87333; color:white; padding:12px 24px; border-radius:8px; text-decoration:none; font-weight:700; font-size:13px; margin-top:16px; }
        .item-row { padding: 8px 0; border-bottom: 1px solid #f0ebe3; font-size: 13px; clear: both; overflow: hidden; }
        .total-row { padding: 12px 0; font-size: 15px; font-weight: 700; color: #000000; clear: both; overflow: hidden; }
        .text-left { float: left; }
        .text-right { float: right; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Jaced Furniture</h1>
        </div>
        <div class="body">
            <p style="font-size:16px; font-weight:700; color:#3D2B1A;">Thank you for your order! 🎉</p>
            <p class="order-id">Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }} · {{ $order->created_at->format('d M Y') }}</p>

            <p class="section-title">Order Details</p>
            @foreach($order->orderDetails as $detail)
            <div class="item-row">
                <span class="text-left" style="color: #555;">{{ $detail->product?->name }} (x{{ $detail->quantity }})</span>
                <span class="text-right" style="font-weight: 600; color: #000000;">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
            </div>
            @endforeach

            <div class="item-row">
                <span class="text-left" style="color: #777;">Delivery fee</span>
                <span class="text-right" style="font-weight: 600; color: #000000;">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
            </div>
            <div class="item-row">
                <span class="text-left" style="color: #777;">Service tax</span>
                <span class="text-right" style="font-weight: 600; color: #000000;">Rp {{ number_format($order->service_tax, 0, ',', '.') }}</span>
            </div>
            @if($order->discount_amount > 0)
            <div class="item-row">
                <span class="text-left" style="color: #777;">Discount</span>
                <span class="text-right" style="color: #2ecc71; font-weight: 600;">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="total-row">
                <span class="text-left">Total</span>
                <span class="text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>

            <div style="clear: both;"></div>

            <p class="section-title">Shipping Address</p>
            <p style="font-size:13px; color:#444; line-height:1.7;">
                <strong>{{ $order->shippingAddress?->receiver_name }}</strong><br>
                {{ $order->shippingAddress?->address_line1 }}<br>
                {{ $order->shippingAddress?->city_name }}, {{ $order->shippingAddress?->province_name }}
            </p>

            <center>
                <a href="{{ route('store.orderhistory_detail.show', $order->id) }}" class="btn" style="color: white !important;">
                    View Order Details
                </a>
            </center>
        </div>
        <div class="footer">
            © {{ date('Y') }} Jaced Furniture. All rights reserved.
        </div>
    </div>
</body>
</html>