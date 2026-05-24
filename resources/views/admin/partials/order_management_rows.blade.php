@php
use Carbon\Carbon;

$statusStyles = [
    'unpaid'    => ['bg' => '#FFF3E0', 'color' => '#E65100',  'label' => 'Unpaid'],
    'packed'    => ['bg' => '#E3F2FD', 'color' => '#1565C0',  'label' => 'Packed'],
    'delivered' => ['bg' => '#F3E5F5', 'color' => '#6A1B9A',  'label' => 'Delivered'],
    'arrived'   => ['bg' => '#E8F5E9', 'color' => '#2E7D32',  'label' => 'Arrived'],
    'cancelled' => ['bg' => '#FFEBEE', 'color' => '#C62828',  'label' => 'Cancelled'],
];

$avatarColors = [
    '#5A6B5B','#C99A6B','#8A6D5A','#C0776A',
    '#7B68A0','#4A7B8A','#7A8A5B','#5A4D7A',
    '#A07060','#6A8070','#9A7050','#7A6090',
];

$transitions = [
    'unpaid' => ['next' => 'packed',    'label' => 'Mark as Packed',    'color' => '#1565C0'],
    'packed' => ['next' => 'delivered', 'label' => 'Mark as Delivered', 'color' => '#6A1B9A'],
];
@endphp

@forelse($orders as $order)
@php
    $st      = $statusStyles[$order->status] ?? ['bg' => '#F5F5F5', 'color' => '#616161', 'label' => ucfirst($order->status)];
    $initials = collect(explode(' ', $order->customer_name))
        ->map(fn($w) => strtoupper(substr($w, 0, 1)))
        ->take(2)->implode('');
    $avatarBg = $avatarColors[$order->id % count($avatarColors)];
    $trans    = $transitions[$order->status] ?? null;

    // Build order details
    $details = DB::table('order_details')
        ->join('products', 'order_details.product_id', '=', 'products.id')
        ->where('order_details.order_id', $order->id)
        ->select('products.name', 'order_details.quantity', 'order_details.subtotal')
        ->get();
@endphp

<div class="order-row" style="border-bottom:1px solid var(--jaced-input);">

    {{-- ── Desktop Row ── --}}
    <div class="d-none d-md-flex align-items-center px-4 py-3 order-row-trigger"
        style="cursor:pointer; transition:background .15s; gap:0;"
        onclick="togglePanel({{ $order->id }})">

        <div style="width:40px; flex-shrink:0;">
            <input type="checkbox" class="order-checkbox"
                style="width:16px; height:16px; accent-color:var(--jaced-sage); cursor:pointer;"
                onclick="event.stopPropagation()">
        </div>

        <div style="flex:0 0 12%; font-size:13px; font-weight:600; color:var(--jaced-brown-dark);">
            #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
        </div>

        <div style="flex:0 0 25%; display:flex; align-items:center; gap:10px;">
            <div style="width:34px; height:34px; border-radius:50%; background:{{ $avatarBg }};
                display:flex; align-items:center; justify-content:center;
                font-size:11px; font-weight:700; color:white; flex-shrink:0;">
                {{ $initials }}
            </div>
            <div style="min-width:0;">
                <p class="mb-0" style="font-size:13px; font-weight:600; color:var(--jaced-brown-dark); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $order->customer_name }}</p>
                <p class="mb-0" style="font-size:11px; color:var(--jaced-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $order->customer_email }}</p>
            </div>
        </div>

        <div style="flex:0 0 15%; font-size:13px; color:var(--jaced-muted);">
            {{ Carbon::parse($order->created_at)->format('d M Y') }}
        </div>

        <div style="flex:0 0 15%;">
            <span style="background:{{ $st['bg'] }}; color:{{ $st['color'] }}; font-size:11px; font-weight:700;
                padding:4px 10px; border-radius:99px; text-transform:uppercase; letter-spacing:.5px;">
                {{ $st['label'] }}
            </span>
        </div>

        <div style="flex:0 0 18%; font-size:13px; color:var(--jaced-muted); text-transform:capitalize;">
            {{ str_replace('_', ' ', $order->payment_method) }}
        </div>

        <div style="flex:1; text-align:right; font-size:13px; font-weight:600; color:var(--jaced-brown-dark);">
            Rp {{ number_format($order->total_price, 0, ',', '.') }}
        </div>

        <div style="width:32px; text-align:right; flex-shrink:0;">
            <span id="chev-{{ $order->id }}"
                style="color:var(--jaced-muted); font-size:16px; display:inline-block; transition:transform .25s;">▾</span>
        </div>
    </div>

    {{-- ── Mobile Row ── --}}
    <div class="d-flex d-md-none align-items-center gap-3 px-3 py-3 order-row-trigger"
        style="cursor:pointer; transition:background .15s;"
        onclick="togglePanel({{ $order->id }})">
        <input type="checkbox" class="order-checkbox"
            style="width:16px; height:16px; flex-shrink:0; accent-color:var(--jaced-sage); cursor:pointer;"
            onclick="event.stopPropagation()">
        <div style="width:38px; height:38px; border-radius:50%; background:{{ $avatarBg }};
            display:flex; align-items:center; justify-content:center;
            font-size:12px; font-weight:700; color:white; flex-shrink:0;">
            {{ $initials }}
        </div>
        <div class="flex-grow-1" style="min-width:0;">
            <p class="mb-0" style="font-size:13px; font-weight:600; color:var(--jaced-brown-dark);">{{ $order->customer_name }}</p>
            <p class="mb-0" style="font-size:11px; color:var(--jaced-muted);">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }} · {{ Carbon::parse($order->created_at)->format('d M Y') }}</p>
        </div>
        <div class="text-end flex-shrink-0">
            <p class="mb-1">
                <span style="background:{{ $st['bg'] }}; color:{{ $st['color'] }}; font-size:10px; font-weight:700; padding:3px 8px; border-radius:99px; text-transform:uppercase;">
                    {{ $st['label'] }}
                </span>
            </p>
            <p class="mb-0" style="font-size:12px; font-weight:600; color:var(--jaced-brown-dark);">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
        </div>
        <span id="chev-mob-{{ $order->id }}"
            style="color:var(--jaced-muted); font-size:16px; flex-shrink:0; transition:transform .25s;">▾</span>
    </div>

    {{-- ── Expand Panel ── --}}
    <div id="panel-{{ $order->id }}"
        style="display:none; background:#FDFBF8; border-top:1px solid var(--jaced-input);">
        <div class="px-3 px-md-4 py-4">
            <div class="row g-4">

                {{-- Customer & Payment --}}
                <div class="col-12 col-md-4">
                    <p class="panel-section-title">Customer</p>
                    <p class="panel-label">Full Name</p>
                    <p class="panel-value">{{ $order->customer_name }}</p>
                    <p class="panel-label">Email</p>
                    <p class="panel-value">{{ $order->customer_email }}</p>
                    <p class="panel-label">Phone</p>
                    <p class="panel-value">{{ $order->customer_phone }}</p>

                    <p class="panel-section-title mt-3">Payment</p>
                    <p class="panel-label">Method</p>
                    <p class="panel-value" style="text-transform:capitalize;">{{ str_replace('_', ' ', $order->payment_method) }}</p>

                    <p class="panel-section-title mt-3">Shipping Address</p>
                    <p class="panel-label">Receiver</p>
                    <p class="panel-value">{{ $order->receiver_name }} · {{ $order->receiver_phone }}</p>
                    <p class="panel-label">Address</p>
                    <p class="panel-value">{{ $order->address_line1 }}, {{ $order->city_name }}, {{ $order->province_name }} {{ $order->postal_code }}</p>
                </div>

                {{-- Order Items & Pricing --}}
                <div class="col-12 col-md-4">
                    <p class="panel-section-title">Order Items</p>
                    @foreach($details as $item)
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:10px; padding-bottom:10px; border-bottom:1px solid var(--jaced-input);">
                        <div>
                            <p class="mb-0" style="font-size:13px; font-weight:600; color:var(--jaced-brown-dark);">{{ $item->name }}</p>
                            <p class="mb-0" style="font-size:11px; color:var(--jaced-muted);">Qty: {{ $item->quantity }}</p>
                        </div>
                        <span style="font-size:13px; font-weight:600; color:var(--jaced-brown-dark);">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </span>
                    </div>
                    @endforeach

                    <div style="margin-top:12px; padding-top:12px;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                            <span style="font-size:12px; color:var(--jaced-muted);">Delivery Fee</span>
                            <span style="font-size:12px; color:var(--jaced-brown-dark);">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                            <span style="font-size:12px; color:var(--jaced-muted);">Service Tax (10%)</span>
                            <span style="font-size:12px; color:var(--jaced-brown-dark);">Rp {{ number_format($order->service_tax, 0, ',', '.') }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                        <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                            <span style="font-size:12px; color:var(--jaced-muted);">Discount</span>
                            <span style="font-size:12px; color:#2E7D32;">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div style="display:flex; justify-content:space-between; margin-top:10px; padding-top:10px; border-top:2px solid var(--jaced-input);">
                            <span style="font-size:13px; font-weight:700; color:var(--jaced-brown-dark);">Total</span>
                            <span style="font-size:13px; font-weight:700; color:var(--jaced-brown-dark);">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Status Timeline + Update --}}
                <div class="col-12 col-md-4">
                    <p class="panel-section-title">Status Timeline</p>

                    @php
                        $timeline = [
                            ['key' => 'unpaid',    'label' => 'Order Placed',    'time' => $order->created_at],
                            ['key' => 'packed',    'label' => 'Packed',          'time' => $order->packed_at],
                            ['key' => 'delivered', 'label' => 'Out for Delivery','time' => $order->delivered_at],
                            ['key' => 'arrived',   'label' => 'Arrived',         'time' => $order->arrived_at],
                        ];
                        $statusOrder = ['unpaid', 'packed', 'delivered', 'arrived'];
                        $currentIdx  = array_search($order->status, $statusOrder);
                        if ($order->status === 'cancelled') $currentIdx = -1;
                    @endphp

                    <div style="position:relative; padding-left:20px;">
                        @foreach($timeline as $i => $step)
                        @php
                            $isDone    = $currentIdx >= $i;
                            $isCurrent = $currentIdx === $i;
                            $dotColor  = $isDone ? '#B87333' : '#DDD8CF';
                            $lineColor = ($i < count($timeline) - 1) ? ($isDone ? '#B87333' : '#DDD8CF') : 'transparent';
                        @endphp
                        <div style="position:relative; padding-bottom:{{ $i < count($timeline)-1 ? '20px' : '0' }};">
                            {{-- Vertical line --}}
                            @if($i < count($timeline) - 1)
                            <div style="position:absolute; left:-12px; top:10px; width:2px; height:100%; background:{{ $lineColor }};"></div>
                            @endif
                            {{-- Dot --}}
                            <div style="position:absolute; left:-16px; top:4px; width:8px; height:8px; border-radius:50%; background:{{ $dotColor }};
                                {{ $isCurrent ? 'box-shadow:0 0 0 3px rgba(184,115,51,0.2);' : '' }}"></div>
                            <div>
                                <p style="font-size:12px; font-weight:{{ $isDone ? '600' : '400' }}; color:{{ $isDone ? 'var(--jaced-brown-dark)' : 'var(--jaced-muted)' }}; margin:0;">
                                    {{ $step['label'] }}
                                </p>
                                <p style="font-size:11px; color:var(--jaced-muted); margin:0;">
                                    {{ $step['time'] ? Carbon::parse($step['time'])->format('d M Y, H:i') : '—' }}
                                </p>
                            </div>
                        </div>
                        @endforeach

                        @if($order->status === 'cancelled')
                        <div style="position:relative; padding-top:8px;">
                            <div style="position:absolute; left:-16px; top:12px; width:8px; height:8px; border-radius:50%; background:#C62828;"></div>
                            <p style="font-size:12px; font-weight:600; color:#C62828; margin:0;">Cancelled</p>
                            <p style="font-size:11px; color:var(--jaced-muted); margin:0;">
                                {{ $order->cancelled_at ? Carbon::parse($order->cancelled_at)->format('d M Y, H:i') : '—' }}
                            </p>
                        </div>
                        @endif
                    </div>

                    {{-- Update Status Button --}}
                    @if($trans)
                    <div style="margin-top:24px;">
                        <button
                            onclick="openStatusModal({{ $order->id }}, '{{ $order->status }}', '{{ $trans['next'] }}', '{{ $trans['label'] }}')"
                            style="width:100%; background:var(--jaced-brown-dark); color:white; border:none;
                                border-radius:10px; padding:11px 16px; font-size:13px; font-weight:600;
                                cursor:pointer; transition:background .15s; display:flex; align-items:center; justify-content:center; gap:8px;"
                            onmouseover="this.style.background='#3D2B1A'"
                            onmouseout="this.style.background='var(--jaced-brown-dark)'">
                            <i class="bi bi-arrow-up-circle"></i>
                            {{ $trans['label'] }}
                        </button>
                    </div>
                    @elseif($order->status === 'delivered')
                    <div style="margin-top:24px; background:#F3E5F5; border-radius:10px; padding:12px 14px;">
                        <p style="font-size:12px; color:#6A1B9A; font-weight:600; margin:0 0 2px;">
                            <i class="bi bi-clock-history"></i> Awaiting Confirmation
                        </p>
                        <p style="font-size:11px; color:var(--jaced-muted); margin:0;">
                            Customer will confirm arrival, or it auto-arrives 1 week after delivery.
                        </p>
                    </div>
                    @elseif($order->status === 'arrived')
                    <div style="margin-top:24px; background:#E8F5E9; border-radius:10px; padding:12px 14px;">
                        <p style="font-size:12px; color:#2E7D32; font-weight:600; margin:0;">
                            <i class="bi bi-check-circle"></i> Order Completed
                        </p>
                    </div>
                    @elseif($order->status === 'cancelled')
                    <div style="margin-top:24px; background:#FFEBEE; border-radius:10px; padding:12px 14px;">
                        <p style="font-size:12px; color:#C62828; font-weight:600; margin:0;">
                            <i class="bi bi-x-circle"></i> Order Cancelled by Customer
                        </p>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

</div>
@empty
<div style="padding:48px; text-align:center; color:var(--jaced-muted); font-size:14px;">
    No orders found.
</div>
@endforelse