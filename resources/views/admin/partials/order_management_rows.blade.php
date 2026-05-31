@php
use Carbon\Carbon;

$statusStyles = [
    'unpaid'     => ['bg' => '#FFF3E0', 'color' => '#E65100',  'label' => 'Unpaid'],
    'on_process' => ['bg' => '#E8EAF6', 'color' => '#283593',  'label' => 'On Process'],
    'packed'     => ['bg' => '#E3F2FD', 'color' => '#1565C0',  'label' => 'Packed'],
    'delivered'  => ['bg' => '#F3E5F5', 'color' => '#6A1B9A',  'label' => 'Delivered'],
    'shipped'    => ['bg' => '#E0F7FA', 'color' => '#00695C',  'label' => 'Shipped'],
    'arrived'    => ['bg' => '#E8F5E9', 'color' => '#2E7D32',  'label' => 'Arrived'],
    'cancelled'  => ['bg' => '#FFEBEE', 'color' => '#C62828',  'label' => 'Cancelled'],
];

$avatarColors = [
    '#5A6B5B','#C99A6B','#8A6D5A','#C0776A',
    '#7B68A0','#4A7B8A','#7A8A5B','#5A4D7A',
    '#A07060','#6A8070','#9A7050','#7A6090',
];

$transitions = [
    'on_process' => ['next' => 'packed',    'label' => 'Mark as Packed'],
    'packed'     => ['next' => 'delivered', 'label' => 'Mark as Delivered'],
    'delivered'  => ['next' => 'shipped',   'label' => 'Mark as Shipped'],
];

$timelineSteps = [
    ['key' => 'unpaid',     'label' => 'Order Placed',           'col' => 'created_at'],
    ['key' => 'on_process', 'label' => 'Payment Confirmed',      'col' => 'on_process_at'],
    ['key' => 'packed',     'label' => 'Packed',                 'col' => 'packed_at'],
    ['key' => 'delivered',  'label' => 'Handed to Courier',      'col' => 'delivered_at'],
    ['key' => 'shipped',    'label' => 'Arrived at Destination', 'col' => 'shipped_at'],
    ['key' => 'arrived',    'label' => 'Arrived',                'col' => 'arrived_at'],
];

$statusOrder = ['unpaid', 'on_process', 'packed', 'delivered', 'shipped', 'arrived'];

// Dispute timeline steps appended after 'Arrived'
$disputeTimelineSteps = [
    'processing' => ['label' => 'Processing Complaint', 'statuses' => ['open', 'negotiating']],
    'outcome'    => ['label' => 'Resolved',             'statuses' => ['resolved', 'rejected']],
];

$disputeTypeLabels = [
    'missing'    => 'Item Missing',
    'damaged'    => 'Item Damaged',
    'wrong_item' => 'Wrong Item Sent',
];
@endphp

@forelse($orders as $order)
@php
    $st       = $statusStyles[$order->status] ?? ['bg' => '#F5F5F5', 'color' => '#616161', 'label' => ucfirst($order->status)];
    $initials = collect(explode(' ', $order->customer_name))
        ->map(fn($w) => strtoupper(substr($w, 0, 1)))
        ->take(2)->implode('');
    $avatarBg = $avatarColors[$order->id % count($avatarColors)];
    $trans    = $transitions[$order->status] ?? null;

    $currentIdx = array_search($order->status, $statusOrder);
    if ($order->status === 'cancelled') $currentIdx = -1;

    $details = DB::table('order_details')
        ->join('products', 'order_details.product_id', '=', 'products.id')
        ->where('order_details.order_id', $order->id)
        ->select('products.name', 'order_details.quantity', 'order_details.subtotal')
        ->get();

    $hasDispute = !empty($order->dispute_id);
    if ($hasDispute) {
        $currentIdx = array_search('shipped', $statusOrder);
    }
@endphp

<div class="order-row" style="border-bottom:1px solid var(--jaced-input);">

    {{-- ── Desktop Row ── --}}
    <div class="d-none d-md-flex align-items-center px-4 py-3 order-row-trigger"
        style="cursor:pointer; transition:background .15s; gap:0;"
        onclick="togglePanel({{ $order->id }})">
        <div style="flex:0 0 12%; font-size:13px; font-weight:600; color:var(--jaced-brown-dark);">
            #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
            @if($hasDispute)
                <span style="display:inline-block; width:7px; height:7px; border-radius:50%; background:#E65100; margin-left:4px; vertical-align:middle;" title="Has active dispute"></span>
            @endif
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
        <div style="width:38px; height:38px; border-radius:50%; background:{{ $avatarBg }};
            display:flex; align-items:center; justify-content:center;
            font-size:12px; font-weight:700; color:white; flex-shrink:0;">
            {{ $initials }}
        </div>
        <div class="flex-grow-1" style="min-width:0;">
            <p class="mb-0" style="font-size:13px; font-weight:600; color:var(--jaced-brown-dark);">
                {{ $order->customer_name }}
                @if($hasDispute)<span style="display:inline-block; width:6px; height:6px; border-radius:50%; background:#E65100; margin-left:4px; vertical-align:middle;"></span>@endif
            </p>
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

            {{-- Unpaid: show waiting message only --}}
            @if($order->status === 'unpaid')
            <div style="text-align:center; padding:24px 0;">
                <div style="width:56px; height:56px; border-radius:50%; background:#FFF3E0; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                    <i class="bi bi-clock" style="font-size:24px; color:#E65100;"></i>
                </div>
                <p style="font-size:15px; font-weight:700; color:var(--jaced-brown-dark); margin:0 0 6px;">Waiting for Payment</p>
                <p style="font-size:13px; color:var(--jaced-muted); margin:0 0 4px;">This order has not been paid yet by the customer.</p>
                <p style="font-size:12px; color:var(--jaced-muted); margin:0;">
                    Order placed: {{ Carbon::parse($order->created_at)->format('d M Y, H:i') }}
                    · Auto-cancels after 24 hours.
                </p>
            </div>

            @else
            <div class="row g-4">

                {{-- ── Col 1: Customer + Payment + Address ── --}}
                <div class="col-12 col-md-4">
                    <p class="panel-section-title">Customer</p>
                    <p class="panel-label">Full Name</p>   <p class="panel-value">{{ $order->customer_name }}</p>
                    <p class="panel-label">Email</p>       <p class="panel-value">{{ $order->customer_email }}</p>
                    <p class="panel-label">Phone</p>       <p class="panel-value">{{ $order->customer_phone }}</p>

                    <p class="panel-section-title mt-3">Payment</p>
                    <p class="panel-label">Method</p>
                    <p class="panel-value" style="text-transform:capitalize;">{{ str_replace('_', ' ', $order->payment_method) }}</p>

                    <p class="panel-section-title mt-3">Shipping Address</p>
                    <p class="panel-label">Receiver</p>
                    <p class="panel-value">{{ $order->receiver_name }} · {{ $order->receiver_phone }}</p>
                    <p class="panel-label">Address</p>
                    <p class="panel-value">{{ $order->address_line1 }}, {{ $order->city_name }}, {{ $order->province_name }} {{ $order->postal_code }}</p>
                </div>

                {{-- ── Col 2: Items + Pricing ── --}}
                <div class="col-12 col-md-4">
                    <p class="panel-section-title">Order Items</p>
                    @foreach($details as $item)
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:10px; padding-bottom:10px; border-bottom:1px solid var(--jaced-input);">
                        <div>
                            <p class="mb-0" style="font-size:13px; font-weight:600; color:var(--jaced-brown-dark);">{{ $item->name }}</p>
                            <p class="mb-0" style="font-size:11px; color:var(--jaced-muted);">Qty: {{ $item->quantity }}</p>
                        </div>
                        <span style="font-size:13px; font-weight:600; color:var(--jaced-brown-dark);">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach

                    <div style="margin-top:8px;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                            <span style="font-size:12px; color:var(--jaced-muted);">Delivery Fee</span>
                            <span style="font-size:12px;">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                            <span style="font-size:12px; color:var(--jaced-muted);">Admin Fee</span>
                            <span style="font-size:12px;">Rp {{ number_format($order->service_tax, 0, ',', '.') }}</span>
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

                {{-- ── Col 3: Timeline + Action ── --}}
                <div class="col-12 col-md-4">
                    <p class="panel-section-title">Status Timeline</p>
                    <div style="position:relative; padding-left:20px;">

                        {{-- ── Standard steps (Order Placed → Arrived) ── --}}
                        @foreach($timelineSteps as $i => $step)
                        @php
                            $stepIdx   = array_search($step['key'], $statusOrder);

                            // When disputed: treat 'arrived' as not done (customer disputing arrival)
                            if ($hasDispute && $step['key'] === 'arrived') {
                                $isDone    = false;
                                $isCurrent = false;
                            } else {
                                $isDone    = $currentIdx !== -1 && $currentIdx >= $stepIdx;
                                $isCurrent = !$hasDispute && $currentIdx === $stepIdx;
                            }

                            $dotColor  = $isDone ? '#B87333' : '#DDD8CF';
                            if ($hasDispute && $step['key'] === 'arrived') {
                                $lineColor = '#B87333';
                            } else {
                                $lineColor = $isDone ? '#B87333' : '#DDD8CF';
                            }
                            $timeVal   = $order->{$step['col']} ?? null;

                            // When disputed, show '—' for arrived even if timestamp exists
                            if ($hasDispute && $step['key'] === 'arrived') $timeVal = null;

                            // Always draw the connector line — either to next standard step or to dispute steps
                            $hasNextStep = ($i < count($timelineSteps) - 1) || $hasDispute;
                        @endphp
                        <div style="position:relative; padding-bottom:18px;">
                            @if($hasNextStep)
                            <div style="position:absolute; left:-12px; top:10px; width:2px; height:100%; background:{{ $lineColor }};"></div>
                            @endif
                            <div style="position:absolute; left:-16px; top:4px; width:8px; height:8px; border-radius:50%; background:{{ $dotColor }};
                                {{ $isCurrent ? 'box-shadow:0 0 0 3px rgba(184,115,51,0.2);' : '' }}"></div>
                            <p style="font-size:12px; font-weight:{{ $isDone ? '600' : '400' }}; color:{{ $isDone ? 'var(--jaced-brown-dark)' : 'var(--jaced-muted)' }}; margin:0;">
                                {{ $step['label'] }}
                            </p>
                            <p style="font-size:11px; color:var(--jaced-muted); margin:0;">
                                {{ $timeVal ? Carbon::parse($timeVal)->format('d M Y, H:i') : '—' }}
                            </p>
                        </div>
                        @endforeach

                        {{-- ── Cancelled node ── --}}
                        @if($order->status === 'cancelled')
                        <div style="position:relative; padding-bottom:0;">
                            <div style="position:absolute; left:-16px; top:4px; width:8px; height:8px; border-radius:50%; background:#C62828;"></div>
                            <p style="font-size:12px; font-weight:600; color:#C62828; margin:0;">Cancelled</p>
                            <p style="font-size:11px; color:var(--jaced-muted); margin:0;">
                                {{ $order->cancelled_at ? Carbon::parse($order->cancelled_at)->format('d M Y, H:i') : '—' }}
                            </p>
                        </div>
                        @endif

                        {{-- ── Dispute extra steps (Processing Complaint → Resolved/Rejected) ── --}}
                        @if($hasDispute)
                        @php
                            $disputeStatus       = $order->dispute_status;
                            $processingDone      = in_array($disputeStatus, ['negotiating', 'resolved', 'rejected']);
                            $processingCurrent   = in_array($disputeStatus, ['open', 'negotiating']);
                            $outcomeDone         = in_array($disputeStatus, ['resolved', 'rejected']);

                            $processingDotColor  = $processingDone   ? '#B87333' : '#DDD8CF';
                            $processingLineColor = $processingDone   ? '#B87333' : '#DDD8CF';

                            $outcomeColor        = $disputeStatus === 'resolved' ? '#B87333' : ($disputeStatus === 'rejected' ? '#C62828' : '#DDD8CF');
                            $outcomeLabel        = $disputeStatus === 'resolved' ? 'Complaint Resolved' : ($disputeStatus === 'rejected' ? 'Complaint Rejected' : 'Outcome');
                            $outcomeTime         = $outcomeDone && $order->dispute_resolved_at
                                                    ? Carbon::parse($order->dispute_resolved_at)->format('d M Y, H:i')
                                                    : '—';
                        @endphp

                        {{-- Processing Complaint step --}}
                        <div style="position:relative; padding-bottom:18px;">
                            <div style="position:absolute; left:-12px; top:10px; width:2px; height:100%; background:{{ $processingLineColor }};"></div>
                            <div style="position:absolute; left:-16px; top:4px; width:8px; height:8px; border-radius:50%; background:{{ $processingDotColor }};
                                {{ $processingCurrent ? 'box-shadow:0 0 0 3px rgba(230,81,0,0.2);' : '' }}"></div>
                            <p style="font-size:12px; font-weight:{{ $processingDone ? '600' : '400' }}; color:{{ $processingDone ? 'var(--jaced-brown-dark)' : 'var(--jaced-muted)' }}; margin:0;">
                                Processing Complaint
                            </p>
                            <p style="font-size:11px; color:var(--jaced-muted); margin:0;">
                                {{ $processingDone ? '—' : '—' }}
                            </p>
                        </div>

                        {{-- Resolved / Rejected outcome step --}}
                        <div style="position:relative; padding-bottom:0;">
                            <div style="position:absolute; left:-16px; top:4px; width:8px; height:8px; border-radius:50%; background:{{ $outcomeColor }};
                                {{ $outcomeDone ? '' : '' }}"></div>
                            <p style="font-size:12px; font-weight:{{ $outcomeDone ? '600' : '400' }}; color:{{ $outcomeDone ? 'var(--jaced-brown-dark)' : 'var(--jaced-muted)' }}; margin:0;">
                                {{ $outcomeLabel }}
                            </p>
                            <p style="font-size:11px; color:var(--jaced-muted); margin:0;">{{ $outcomeTime }}</p>
                        </div>
                        @endif
                        {{-- End dispute steps --}}

                    </div>

                    {{-- Action area --}}
                    <div style="margin-top:24px;">
                        @if($trans)
                        <button onclick="openStatusModal({{ $order->id }}, '{{ $order->status }}', '{{ $trans['next'] }}', '{{ $trans['label'] }}')"
                            style="width:100%; background:var(--jaced-brown-dark); color:white; border:none;
                                border-radius:10px; padding:11px 16px; font-size:13px; font-weight:600;
                                cursor:pointer; transition:background .15s; display:flex; align-items:center; justify-content:center; gap:8px;"
                            onmouseover="this.style.background='#3D2B1A'"
                            onmouseout="this.style.background='var(--jaced-brown-dark)'">
                            <i class="bi bi-arrow-up-circle"></i> {{ $trans['label'] }}
                        </button>

                        @elseif($order->status === 'shipped')
                        <div style="background:#E0F7FA; border-radius:10px; padding:12px 14px;">
                            <p style="font-size:12px; color:#00695C; font-weight:600; margin:0 0 2px;">
                                <i class="bi bi-clock-history"></i> Waiting for Customer Confirmation
                            </p>
                            <p style="font-size:11px; color:var(--jaced-muted); margin:0;">
                                Auto-arrives {{ $order->shipped_at ? Carbon::parse($order->shipped_at)->addDays(7)->format('d M Y') : 'after 7 days' }}.
                            </p>
                        </div>

                        @elseif($order->status === 'arrived')
                        <div style="background:#E8F5E9; border-radius:10px; padding:12px 14px;">
                            <p style="font-size:12px; color:#2E7D32; font-weight:600; margin:0;">
                                <i class="bi bi-check-circle"></i> Order Completed
                            </p>
                        </div>

                        @elseif($order->status === 'cancelled')
                        <div style="background:#FFEBEE; border-radius:10px; padding:12px 14px;">
                            <p style="font-size:12px; color:#C62828; font-weight:600; margin:0 0 4px;">
                                <i class="bi bi-x-circle"></i> Order Cancelled
                            </p>
                            @if($order->cancellation_reason)
                            <p style="font-size:11px; color:var(--jaced-muted); margin:0;">{{ $order->cancellation_reason }}</p>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- ── Dispute Section (shown when order has active dispute) ── --}}
            @if($hasDispute)
            <div style="margin-top:24px; padding-top:24px; border-top:2px dashed #F0EBE4;">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px;">
                    <div style="background:#FFF3E0; border-radius:8px; padding:8px;">
                        <i class="bi bi-exclamation-triangle" style="font-size:16px; color:#E65100;"></i>
                    </div>
                    <div>
                        <p style="font-size:14px; font-weight:700; color:var(--jaced-brown-dark); margin:0;">Customer Dispute</p>
                        <p style="font-size:11px; color:var(--jaced-muted); margin:0; text-transform:capitalize;">
                            {{ $disputeTypeLabels[$order->dispute_reason] ?? ucfirst(str_replace('_',' ',$order->dispute_reason)) }}
                            ·
                            @if($order->dispute_status === 'open')
                                <span style="color:#E65100; font-weight:600;">Open — Awaiting Review</span>
                            @elseif($order->dispute_status === 'negotiating')
                                <span style="color:#283593; font-weight:600;">In Progress</span>
                            @elseif($order->dispute_status === 'resolved')
                                <span style="color:#2E7D32; font-weight:600;">Resolved</span>
                            @elseif($order->dispute_status === 'rejected')
                                <span style="color:#C62828; font-weight:600;">Rejected</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="row g-3">

                    {{-- Dispute details --}}
                    <div class="col-12 col-md-5">
                        <p class="panel-section-title">Dispute Details</p>
                        <p class="panel-label">Type</p>
                        <p class="panel-value" style="text-transform:capitalize;">
                            {{ $disputeTypeLabels[$order->dispute_reason] ?? ucfirst(str_replace('_',' ',$order->dispute_reason)) }}
                        </p>
                        <p class="panel-label">Customer Description</p>
                        <p class="panel-value">{{ $order->dispute_description ?? '—' }}</p>

                        @if($order->dispute_photo)
                        <p class="panel-label">Photo Evidence</p>
                        <a href="{{ asset('image/complaints/' . basename($order->dispute_photo)) }}" target="_blank">
                            <img src="{{ asset('image/complaints/' . basename($order->dispute_photo)) }}"
                                style="width:100%; max-width:200px; border-radius:8px; border:1px solid var(--jaced-input); cursor:pointer;"
                                alt="Dispute photo">
                        </a>
                        @endif

                        @if($order->dispute_admin_note)
                        <p class="panel-label mt-3">Admin Note</p>
                        <p class="panel-value">{{ $order->dispute_admin_note }}</p>
                        @endif

                        {{-- Exchange tracking info --}}
                        @if($order->dispute_resolution_type === 'exchange' && $order->dispute_status !== 'rejected')
                        <p class="panel-section-title mt-3">Exchange Tracking</p>
                        @if($order->dispute_replacement_tracking)
                        <p class="panel-label">Replacement Tracking No.</p>
                        <p class="panel-value">{{ $order->dispute_replacement_tracking }}</p>
                        <p class="panel-label">Shipped At</p>
                        <p class="panel-value">{{ $order->dispute_replacement_shipped_at ? Carbon::parse($order->dispute_replacement_shipped_at)->format('d M Y, H:i') : '—' }}</p>
                        @if($order->dispute_replacement_arrived_at)
                        <p class="panel-label">Replacement Arrived</p>
                        <p class="panel-value">{{ Carbon::parse($order->dispute_replacement_arrived_at)->format('d M Y, H:i') }}</p>
                        @endif
                        @else
                        <p style="font-size:12px; color:var(--jaced-muted);">No tracking number yet.</p>
                        @endif
                        @endif
                    </div>

                    {{-- Admin actions --}}
                    <div class="col-12 col-md-7">
                        @if($order->dispute_status === 'open')
                        <p class="panel-section-title">Take Action</p>
                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            <button onclick="openDisputeModal({{ $order->dispute_id }}, 'refund', {{ $order->total_price }}, {{ $order->delivery_fee }}, {{ $order->service_tax }}, {{ $order->discount_amount }})"
                                style="flex:1; min-width:120px; background:#1A237E; color:white; border:none; border-radius:8px; padding:9px 12px; font-size:12px; font-weight:600; cursor:pointer;"
                                onmouseover="this.style.background='#0D1257'" onmouseout="this.style.background='#1A237E'">
                                <i class="bi bi-cash-stack"></i> Refund
                            </button>
                            <button onclick="openDisputeModal({{ $order->dispute_id }}, 'exchange', {{ $order->total_price }}, {{ $order->delivery_fee }}, {{ $order->service_tax }}, {{ $order->discount_amount }})"
                                style="flex:1; min-width:120px; background:#004D40; color:white; border:none; border-radius:8px; padding:9px 12px; font-size:12px; font-weight:600; cursor:pointer;"
                                onmouseover="this.style.background='#00251A'" onmouseout="this.style.background='#004D40'">
                                <i class="bi bi-arrow-repeat"></i> Send Replacement
                            </button>
                            <button onclick="openDisputeModal({{ $order->dispute_id }}, 'reject', {{ $order->total_price }}, {{ $order->delivery_fee }}, {{ $order->service_tax }}, {{ $order->discount_amount }})"
                                style="flex:1; min-width:120px; background:#B71C1C; color:white; border:none; border-radius:8px; padding:9px 12px; font-size:12px; font-weight:600; cursor:pointer;"
                                onmouseover="this.style.background='#7F0000'" onmouseout="this.style.background='#B71C1C'">
                                <i class="bi bi-x-circle"></i> Reject
                            </button>
                        </div>

                        @elseif($order->dispute_status === 'negotiating')
                        {{-- Negotiating: show current resolution type, allow mark as resolved or update tracking --}}
                        <p class="panel-section-title">In Progress</p>

                        @if($order->dispute_resolution_type === 'exchange' && !$order->dispute_replacement_tracking)
                        <div style="background:white; border:1px solid var(--jaced-input); border-radius:12px; padding:16px; margin-bottom:12px;">
                            <p style="font-size:13px; font-weight:600; color:var(--jaced-brown-dark); margin:0 0 10px;">
                                <i class="bi bi-box-seam"></i> Add Replacement Tracking Number
                            </p>
                            <input type="text" id="tracking-update-{{ $order->dispute_id }}"
                                style="width:100%; font-size:13px; border:1px solid var(--jaced-input); border-radius:8px; padding:9px 12px; outline:none; margin-bottom:10px;"
                                placeholder="e.g. JNE123456789">
                            <button onclick="updateTracking({{ $order->dispute_id }})"
                                style="width:100%; background:var(--jaced-brown-dark); color:white; border:none; border-radius:8px; padding:9px; font-size:13px; font-weight:600; cursor:pointer;">
                                Save Tracking Number
                            </button>
                        </div>
                        @endif

                        <button onclick="openResolveModal({{ $order->dispute_id }}, '{{ $order->dispute_resolution_type }}')"
                            style="width:100%; background:#2E7D32; color:white; border:none; border-radius:10px; padding:11px 16px; font-size:13px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px;"
                            onmouseover="this.style.background='#1B5E20'"
                            onmouseout="this.style.background='#2E7D32'">
                            <i class="bi bi-check-circle"></i>
                            @if($order->dispute_resolution_type === 'refund')
                                Mark Refund as Done
                            @else
                                Mark Exchange as Completed
                            @endif
                        </button>

                        @elseif($order->dispute_status === 'resolved')
                        <div style="background:#E8F5E9; border-radius:10px; padding:14px;">
                            <p style="font-size:13px; font-weight:700; color:#2E7D32; margin:0 0 8px;">
                                <i class="bi bi-check-circle-fill"></i> Dispute Resolved
                            </p>

                            {{-- Resolution type --}}
                            <p style="font-size:11px; color:var(--jaced-muted); margin:0 0 4px;">Resolution</p>
                            <p style="font-size:13px; font-weight:600; color:var(--jaced-brown-dark); margin:0 0 10px; text-transform:capitalize;">
                                @if($order->dispute_resolution_type === 'refund')
                                    <i class="bi bi-cash-stack"></i> Refund
                                @elseif($order->dispute_resolution_type === 'exchange')
                                    <i class="bi bi-arrow-repeat"></i> Send Replacement
                                @else
                                    —
                                @endif
                            </p>

                            {{-- Admin note --}}
                            @if($order->dispute_admin_note)
                            <p style="font-size:11px; color:var(--jaced-muted); margin:0 0 4px;">Admin Note</p>
                            <p style="font-size:12px; color:var(--jaced-brown-dark); margin:0 0 10px;">{{ $order->dispute_admin_note }}</p>
                            @endif

                            {{-- Resolved at --}}
                            @if($order->dispute_resolved_at)
                            <p style="font-size:11px; color:var(--jaced-muted); margin:0;">
                                Resolved on {{ Carbon::parse($order->dispute_resolved_at)->format('d M Y, H:i') }}
                            </p>
                            @endif
                        </div>

                        @elseif($order->dispute_status === 'rejected')
                        <div style="background:#FFEBEE; border-radius:10px; padding:14px;">
                            <p style="font-size:13px; font-weight:700; color:#C62828; margin:0 0 8px;">
                                <i class="bi bi-x-circle-fill"></i> Dispute Rejected
                            </p>

                            {{-- Admin note --}}
                            @if($order->dispute_admin_note)
                            <p style="font-size:11px; color:var(--jaced-muted); margin:0 0 4px;">Admin Note</p>
                            <p style="font-size:12px; color:var(--jaced-brown-dark); margin:0 0 10px;">{{ $order->dispute_admin_note }}</p>
                            @endif

                            @if($order->dispute_resolved_at)
                            <p style="font-size:11px; color:var(--jaced-muted); margin:0;">
                                Rejected on {{ Carbon::parse($order->dispute_resolved_at)->format('d M Y, H:i') }}
                            </p>
                            @endif
                        </div>
                        @endif

                    </div>
                </div>
            </div>
            @endif
            {{-- End dispute section --}}

            @endif
            {{-- End unpaid check --}}
        </div>
    </div>

</div>
@empty
<div style="padding:48px; text-align:center; color:var(--jaced-muted); font-size:14px;">
    No orders found.
</div>
@endforelse

{{-- ── Resolve Dispute Confirmation Modal ── --}}
<div id="resolveModalOverlay"
    style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5);
           z-index:99999; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:20px; padding:32px; max-width:380px; width:90%;
                box-shadow:0 20px 60px rgba(0,0,0,.2); animation:modalIn .2s ease;">

        {{-- Icon --}}
        <div style="width:52px; height:52px; border-radius:50%; background:#E8F5E9;
                    display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
            <i class="bi bi-check-circle-fill" style="font-size:24px; color:#2E7D32;"></i>
        </div>

        {{-- Title & description --}}
        <h5 id="resolveModalTitle"
            style="font-size:17px; font-weight:700; color:var(--jaced-brown-dark);
                   text-align:center; margin:0 0 8px;"></h5>
        <p id="resolveModalDesc"
            style="font-size:13px; color:var(--jaced-muted); text-align:center; margin:0 0 24px;"></p>

        <p style="font-size:12px; color:var(--jaced-muted); text-align:center;
                  background:#F5F3EF; border-radius:8px; padding:10px 14px; margin:0 0 20px;">
            ⚠ This action cannot be undone.
        </p>

        {{-- Buttons --}}
        <button id="resolveModalConfirm"
            style="width:100%; padding:13px; background:#2E7D32; color:white; border:none;
                   border-radius:12px; font-size:14px; font-weight:700; cursor:pointer;
                   transition:background .15s; margin-bottom:10px;"
            onmouseover="this.style.background='#1B5E20'"
            onmouseout="this.style.background='#2E7D32'">
        </button>
        <button onclick="closeResolveModal()"
            style="width:100%; padding:11px; background:none; color:var(--jaced-muted);
                   border:1px solid var(--jaced-input); border-radius:12px;
                   font-size:13px; font-weight:500; cursor:pointer; transition:background .15s;"
            onmouseover="this.style.background='#F5F3EF'"
            onmouseout="this.style.background='none'">
            Cancel
        </button>
    </div>
</div>

{{-- Dispute Action Modal --}}
<div id="disputeModalOverlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:1050; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:16px; padding:28px; width:100%; max-width:440px; margin:16px; box-shadow:0 8px 32px rgba(0,0,0,0.18); position:relative;">

        {{-- Header --}}
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
            <p id="disputeModalTitle" style="font-size:15px; font-weight:700; color:var(--jaced-brown-dark); margin:0;"></p>
            <button onclick="closeDisputeModal()" style="background:none; border:none; cursor:pointer; color:var(--jaced-muted); font-size:18px; line-height:1; padding:0;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        {{-- Refund range (only for refund) --}}
        <div id="disputeRefundSection" style="display:none; margin-bottom:16px;">
            <label style="font-size:11px; font-weight:600; letter-spacing:.07em; text-transform:uppercase; color:var(--jaced-muted); display:block; margin-bottom:6px;">
                Refund Amount <span style="color:#C62828;">*</span>
            </label>
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:6px;">
                <input type="range" id="disputeRefundRange"
                    min="0" step="1000"
                    style="flex:1; accent-color:#1A237E;"
                    oninput="syncRefundInput(this.value)">
                <span style="font-size:13px; font-weight:700; color:#1A237E; white-space:nowrap;" id="disputeRefundDisplay">Rp 0</span>
            </div>
            <input type="number" id="disputeRefundInput"
                min="0" step="1000"
                style="width:100%; font-size:13px; border:1px solid var(--jaced-input); border-radius:8px; padding:9px 12px; outline:none;"
                placeholder="Or type amount manually"
                oninput="syncRefundRange(this.value)">
            <p id="disputeRefundErr" style="display:none; font-size:11px; color:#C62828; margin:4px 0 0; font-weight:600;">⚠ Please enter a refund amount.</p>
        </div>

        {{-- Tracking (only for exchange) --}}
        <div id="disputeTrackingSection" style="display:none; margin-bottom:16px;">
            <label style="font-size:11px; font-weight:600; letter-spacing:.07em; text-transform:uppercase; color:var(--jaced-muted); display:block; margin-bottom:6px;">
                Replacement Tracking Number <span style="color:#C62828;">*</span>
            </label>
            <input type="text" id="disputeTrackingInput"
                style="width:100%; font-size:13px; border:1px solid var(--jaced-input); border-radius:8px; padding:9px 12px; outline:none;"
                placeholder="e.g. JNE123456789">
            <p id="disputeTrackingErr" style="display:none; font-size:11px; color:#C62828; margin:4px 0 0; font-weight:600;">⚠ Tracking number is required.</p>
        </div>

        {{-- Admin note --}}
        <div style="margin-bottom:20px;">
            <label style="font-size:11px; font-weight:600; letter-spacing:.07em; text-transform:uppercase; color:var(--jaced-muted); display:block; margin-bottom:6px;">
                Admin Note <span style="color:#C62828;">*</span>
            </label>
            <textarea id="disputeNoteInput"
                style="width:100%; font-size:13px; border:1px solid var(--jaced-input); border-radius:8px; padding:9px 12px; resize:none; height:80px; outline:none;"
                placeholder="Write your explanation or note for the customer..."></textarea>
            <p id="disputeNoteErr" style="display:none; font-size:11px; color:#C62828; margin:4px 0 0; font-weight:600;">⚠ Admin note is required.</p>
        </div>

        {{-- Footer buttons --}}
        <div style="display:flex; gap:8px;">
            <button onclick="closeDisputeModal()"
                style="flex:1; background:#F5F5F5; color:var(--jaced-brown-dark); border:none; border-radius:8px; padding:10px; font-size:13px; font-weight:600; cursor:pointer;">
                Cancel
            </button>
            <button id="disputeModalConfirmBtn" onclick="submitDisputeAction()"
                style="flex:2; color:white; border:none; border-radius:8px; padding:10px; font-size:13px; font-weight:600; cursor:pointer;">
                Confirm
            </button>
        </div>

    </div>
</div>

<script>
function showExchangeTracking(disputeId) {
    const field = document.getElementById('exchange-tracking-field-' + disputeId);
    if (field) field.style.display = 'block';
}

function resolveDispute(disputeId, action) {
    const note     = document.getElementById('dispute-note-' + disputeId)?.value?.trim();
    const tracking = document.getElementById('tracking-input-' + disputeId)?.value?.trim();

    if (!note) {
        showToast('⚠ Please write an admin note before taking action.');
        return;
    }

    const payload = { action, admin_note: note };
    if (action === 'exchange' && tracking) payload.replacement_tracking_number = tracking;

    fetch(`{{ url('admin/disputes') }}/${disputeId}/resolve`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(payload),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('✓ ' + data.message);
            fetchOrders(1);
        } else {
            showToast('⚠ ' + (data.error || 'Something went wrong.'));
        }
    })
    .catch(() => showToast('Network error. Please try again.'));
}

function openResolveModal(disputeId, resolutionType) {
    const label = resolutionType === 'refund' ? 'Mark Refund as Done' : 'Mark Exchange as Completed';
    const desc  = resolutionType === 'refund'
        ? 'Confirm that the refund has been transferred to the customer.'
        : 'Confirm that the replacement item has been delivered and received.';

    document.getElementById('resolveModalTitle').textContent  = label;
    document.getElementById('resolveModalDesc').textContent   = desc;
    document.getElementById('resolveModalConfirm').textContent = label;

    // Store disputeId on the confirm button
    document.getElementById('resolveModalConfirm').dataset.disputeId = disputeId;

    const overlay = document.getElementById('resolveModalOverlay');
    overlay.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeResolveModal() {
    document.getElementById('resolveModalOverlay').style.display = 'none';
    document.body.style.overflow = '';
}

function markDisputeResolved(disputeId) {
    const btn = document.getElementById('resolveModalConfirm');
    btn.disabled = true;
    btn.textContent = 'Processing...';

    fetch(`{{ url('admin/disputes') }}/${disputeId}/resolved`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    })
    .then(r => r.json())
    .then(data => {
        closeResolveModal();
        btn.disabled = false;
        if (data.success) {
            showToast('✓ ' + data.message);
            fetchOrders(1);
        } else {
            showToast('⚠ ' + (data.error || 'Something went wrong.'));
        }
    })
    .catch(() => {
        closeResolveModal();
        btn.disabled = false;
        showToast('Network error. Please try again.');
    });
}

// Bind confirm button click
const confirmBtn = document.getElementById('resolveModalConfirm');

if (confirmBtn) {
    confirmBtn.addEventListener('click', function () {
        markDisputeResolved(this.dataset.disputeId);
    });
}

// Close on overlay click
const overlay = document.getElementById('resolveModalOverlay');

if (overlay) {
    overlay.addEventListener('click', function (e) {
        if (e.target === this) closeResolveModal();
    });
}

function updateTracking(disputeId) {
    const tracking = document.getElementById('tracking-update-' + disputeId)?.value?.trim();
    if (!tracking) {
        showToast('⚠ Please enter a tracking number.');
        return;
    }

    fetch(`{{ url('admin/disputes') }}/${disputeId}/tracking`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ replacement_tracking_number: tracking }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('✓ ' + data.message);
            fetchOrders(1);
        } else {
            showToast('⚠ ' + (data.error || 'Something went wrong.'));
        }
    });
}
</script>