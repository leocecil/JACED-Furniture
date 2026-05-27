@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">

<style>
    .panel-section-title {
        font-size: 11px; font-weight: 700; letter-spacing: 1px;
        text-transform: uppercase; color: var(--jaced-sage);
        margin-bottom: 10px;
    }
    .panel-label  { font-size: 11px; color: var(--jaced-muted); margin-bottom: 2px; }
    .panel-value  { font-size: 13px; font-weight: 500; color: var(--jaced-brown-dark); margin-bottom: 10px; }

    .order-row-trigger:hover { background-color: var(--jaced-caramel-bg) !important; }
    .order-row:last-child    { border-bottom: none !important; }

    .filter-bar {
        display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end;
        padding: 16px; border-bottom: 1px solid var(--jaced-input); background: #FDFBF8;
    }
    .filter-group { display: flex; flex-direction: column; gap: 4px; }
    .filter-label { font-size: 10px; font-weight: 600; letter-spacing: .7px; text-transform: uppercase; color: var(--jaced-muted); }
    .filter-input, .filter-select {
        font-size: 13px; border: 1px solid var(--jaced-input); border-radius: 8px;
        padding: 8px 12px; color: var(--jaced-brown-dark); background: white;
        outline: none; transition: border-color .15s; height: 38px;
    }
    .filter-input:focus, .filter-select:focus { border-color: var(--jaced-caramel); }
    .filter-input  { min-width: 200px; }
    .filter-select { min-width: 140px; }
    .btn-clear { background: white; color: var(--jaced-muted); border: 1px solid var(--jaced-input); border-radius: 8px; padding: 8px 14px; font-size: 13px; font-weight: 500; cursor: pointer; height: 38px; transition: background .15s; }
    .btn-clear:hover { background: var(--jaced-caramel-bg); }

    .status-modal-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.45); z-index: 9999;
        align-items: center; justify-content: center;
    }
    .status-modal-overlay.open { display: flex; }
    .status-modal {
        background: white; border-radius: 20px; padding: 32px;
        max-width: 400px; width: 90%;
        box-shadow: 0 20px 60px rgba(0,0,0,.2);
        animation: modalIn .2s ease;
    }
    @keyframes modalIn {
        from { opacity: 0; transform: translateY(16px) scale(.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .modal-status-arrow {
        display: flex; align-items: center; justify-content: center;
        gap: 16px; margin: 24px 0;
    }
    .modal-status-chip {
        padding: 8px 20px; border-radius: 99px;
        font-size: 13px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .5px;
    }
    .btn-confirm {
        width: 100%; padding: 13px;
        background: var(--jaced-brown-dark); color: white;
        border: none; border-radius: 12px;
        font-size: 14px; font-weight: 700;
        cursor: pointer; transition: background .15s; margin-bottom: 10px;
    }
    .btn-confirm:hover { background: #3D2B1A; }
    .btn-cancel-modal {
        width: 100%; padding: 11px; background: none;
        color: var(--jaced-muted); border: 1px solid var(--jaced-input);
        border-radius: 12px; font-size: 13px; font-weight: 500;
        cursor: pointer; transition: background .15s;
    }
    .btn-cancel-modal:hover { background: var(--jaced-caramel-bg); }

    .toast-msg {
        position: fixed; bottom: 24px; right: 24px;
        background: #1A1714; color: white;
        padding: 12px 20px; border-radius: 10px;
        font-size: 13px; font-weight: 500;
        box-shadow: 0 8px 24px rgba(0,0,0,.2);
        z-index: 99999; opacity: 0; transform: translateY(8px);
        transition: opacity .25s, transform .25s; pointer-events: none;
    }
    .toast-msg.show { opacity: 1; transform: translateY(0); }

    .pagination { display:flex; align-items:center; gap:4px; margin:0; padding:0; }
    .pagination .page-item .page-link {
        display:flex; align-items:center; justify-content:center;
        min-width:32px; height:32px; padding:0 8px;
        border:1px solid var(--jaced-input); border-radius:6px !important;
        font-size:13px; font-weight:500;
        color:var(--jaced-brown-dark); background:white; transition:background .15s;
    }
    .pagination .page-item.active .page-link { background:var(--jaced-brown-dark); border-color:var(--jaced-brown-dark); color:white; }
    .pagination .page-item.disabled .page-link { color:var(--jaced-muted); background:#f9f9f9; }
    .pagination .page-item:not(.active):not(.disabled) .page-link:hover { background:var(--jaced-caramel-bg); }
</style>

@php
use Carbon\Carbon;

$statusStyles = [
    'unpaid'     => ['bg' => '#FFF3E0', 'color' => '#E65100', 'label' => 'Unpaid'],
    'on_process' => ['bg' => '#E8EAF6', 'color' => '#283593', 'label' => 'On Process'],
    'packed'     => ['bg' => '#E3F2FD', 'color' => '#1565C0', 'label' => 'Packed'],
    'delivered'  => ['bg' => '#F3E5F5', 'color' => '#6A1B9A', 'label' => 'Delivered'],
    'shipped'    => ['bg' => '#E0F7FA', 'color' => '#00695C', 'label' => 'Shipped'],
    'arrived'    => ['bg' => '#E8F5E9', 'color' => '#2E7D32', 'label' => 'Arrived'],
    'cancelled'  => ['bg' => '#FFEBEE', 'color' => '#C62828', 'label' => 'Cancelled'],
];

$avatarColors = [
    '#5A6B5B','#C99A6B','#8A6D5A','#C0776A',
    '#7B68A0','#4A7B8A','#7A8A5B','#5A4D7A',
];

// Admin transitions only — unpaid is intentionally excluded
$transitions = [
    'on_process' => ['next' => 'packed',    'label' => 'Mark as Packed'],
    'packed'     => ['next' => 'delivered', 'label' => 'Mark as Delivered'],
    'delivered'  => ['next' => 'shipped',   'label' => 'Mark as Shipped'],
];

// Full timeline steps in order
$timelineSteps = [
    ['key' => 'unpaid',     'label' => 'Order Placed',          'col' => 'created_at'],
    ['key' => 'on_process', 'label' => 'Payment Confirmed',     'col' => 'on_process_at'],
    ['key' => 'packed',     'label' => 'Packed',                'col' => 'packed_at'],
    ['key' => 'delivered',  'label' => 'Handed to Courier',     'col' => 'delivered_at'],
    ['key' => 'shipped',    'label' => 'Arrived at Destination','col' => 'shipped_at'],
    ['key' => 'arrived',    'label' => 'Arrived',               'col' => 'arrived_at'],
];

$statusOrder = ['unpaid', 'on_process', 'packed', 'delivered', 'shipped', 'arrived'];
@endphp

<div class="container-fluid">

    {{-- ── Page Header ── --}}
    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-4">
        <div>
            <h1 class="font-serif-jaced text-jaced-dark mb-1"
                style="font-size:clamp(1.4rem,4vw,1.9rem); font-weight:700; letter-spacing:-0.5px;">
                Order Management
            </h1>
            <p style="font-size:12px; color:var(--jaced-muted); margin:0;">
                Manage and track all customer orders
            </p>
        </div>
    </div>

    {{-- ── Stat Cards ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="jaced-card p-3 p-md-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div style="background:var(--jaced-caramel-bg); border-radius:8px; padding:8px;">
                        <i class="bi bi-clipboard-check" style="font-size:18px; color:var(--jaced-caramel);"></i>
                    </div>
                    <span style="background:#FFF3E0; color:#E65100; font-size:11px; font-weight:600; padding:3px 8px; border-radius:99px;">Unpaid</span>
                </div>
                <p class="text-jaced-muted mb-1" style="font-size:11px; font-weight:600; letter-spacing:.8px; text-transform:uppercase;">Awaiting Payment</p>
                <p class="text-jaced-dark mb-0" style="font-size:2rem; font-weight:700; line-height:1;">{{ $stats['unpaid'] }}</p>
            </div>
        </div>

        <div class="col-6 col-md-4">
            <div class="jaced-card p-3 p-md-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div style="background:#E3F2FD; border-radius:8px; padding:8px;">
                        <i class="bi bi-truck" style="font-size:18px; color:#1565C0;"></i>
                    </div>
                    <span style="background:#E3F2FD; color:#1565C0; font-size:11px; font-weight:600; padding:3px 8px; border-radius:99px;">Active</span>
                </div>
                <p class="text-jaced-muted mb-1" style="font-size:11px; font-weight:600; letter-spacing:.8px; text-transform:uppercase;">Out for Delivery</p>
                <p class="text-jaced-dark mb-0" style="font-size:2rem; font-weight:700; line-height:1;">{{ $stats['delivered'] }}</p>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="p-3 p-md-4 h-100"
                style="background:var(--jaced-brown-dark); border-radius:12px; box-shadow:0 4px 16px rgba(0,0,0,.15);">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div style="background:rgba(255,255,255,.1); border-radius:8px; padding:8px;">
                        <i class="bi bi-credit-card" style="font-size:18px; color:white;"></i>
                    </div>
                    <span style="background:rgba(255,255,255,.15); color:white; font-size:11px; font-weight:600; padding:3px 8px; border-radius:99px;">This Week</span>
                </div>
                <p style="color:rgba(255,255,255,.6); font-size:11px; font-weight:600; letter-spacing:.8px; text-transform:uppercase; margin-bottom:4px;">Weekly Revenue</p>
                <p style="color:white; font-size:clamp(1.1rem,3vw,1.6rem); font-weight:700; line-height:1; margin:0;">
                    Rp {{ number_format($stats['weekly_revenue'], 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    {{-- ── Orders Table Card ── --}}
    <div class="jaced-card" style="overflow:hidden;">

        {{-- Filter Bar — no Apply button, all filters auto-refresh --}}
        <div class="filter-bar">
            <div class="filter-group" style="flex:1; min-width:180px;">
                <span class="filter-label">Search</span>
                <input type="text" id="searchInput" class="filter-input"
                    placeholder="Customer name or order ID...">
            </div>
            <div class="filter-group">
                <span class="filter-label">Status</span>
                <select id="filterStatus" class="filter-select" onchange="fetchOrders(1)">
                    <option value="all">All Status</option>
                    <option value="unpaid">Unpaid</option>
                    <option value="on_process">On Process</option>
                    <option value="packed">Packed</option>
                    <option value="delivered">Delivered</option>
                    <option value="shipped">Shipped</option>
                    <option value="arrived">Arrived</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="filter-group">
                <span class="filter-label">Payment</span>
                <select id="filterPayment" class="filter-select" onchange="fetchOrders(1)">
                    <option value="all">All Methods</option>
                    <option value="qris">QRIS</option>
                    <option value="virtual_account">Virtual Account</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="ovo">OVO</option>
                    <option value="dana">DANA</option>
                </select>
            </div>
            <div class="filter-group">
                <span class="filter-label">From</span>
                <input type="date" id="filterDateFrom" class="filter-input" style="min-width:unset; width:145px;" onchange="fetchOrders(1)">
            </div>
            <div class="filter-group">
                <span class="filter-label">To</span>
                <input type="date" id="filterDateTo" class="filter-input" style="min-width:unset; width:145px;" onchange="fetchOrders(1)">
            </div>
            <button class="btn-clear" onclick="clearFilters()">Clear</button>
        </div>

        {{-- Table Header (desktop) --}}
        <div class="d-none d-md-block px-4 py-2" style="border-bottom:1px solid var(--jaced-input);">
            <div style="display:flex; align-items:center; font-size:11px; font-weight:600; letter-spacing:.7px; text-transform:uppercase; color:var(--jaced-muted);">
                <div style="width:40px; flex-shrink:0;"></div>
                <div style="flex:0 0 12%;">Order ID</div>
                <div style="flex:0 0 25%;">Customer</div>
                <div style="flex:0 0 15%;">Date</div>
                <div style="flex:0 0 15%;">Status</div>
                <div style="flex:0 0 15%;">Payment</div>
                <div style="flex:1; text-align:right;">Amount</div>
                <div style="width:32px;"></div>
            </div>
        </div>

        {{-- ── Order Rows ── --}}
        <div id="orderTableBody">
            @include('admin.partials.order_management_rows', ['orders' => $orders])
        </div>

        {{-- Pagination --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 px-3 px-md-4 py-3"
            style="border-top:1px solid var(--jaced-input);">
            <span id="paginationInfo" style="font-size:12px; color:var(--jaced-muted);">
                @if($orders->total() > 0)
                    Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }} orders
                @else
                    No orders found
                @endif
            </span>
            <div id="paginationLinksContainer">
                {{ $orders->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>

    </div>

</div>

{{-- ── Status Modal ── --}}
<div class="status-modal-overlay" id="statusModalOverlay">
    <div class="status-modal">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:4px;">
            <h5 style="font-size:17px; font-weight:700; color:var(--jaced-brown-dark); margin:0;">Update Order Status</h5>
            <button onclick="closeStatusModal()" style="background:none; border:none; font-size:22px; color:var(--jaced-muted); cursor:pointer; line-height:1;">×</button>
        </div>
        <p id="modalOrderId" style="font-size:12px; color:var(--jaced-muted); margin:0;"></p>

        <div class="modal-status-arrow">
            <div class="text-center">
                <p style="font-size:10px; color:var(--jaced-muted); margin-bottom:6px; letter-spacing:.5px; text-transform:uppercase;">Current</p>
                <span class="modal-status-chip" id="modalCurrentChip"></span>
            </div>
            <i class="bi bi-arrow-right" style="font-size:22px; color:var(--jaced-caramel);"></i>
            <div class="text-center">
                <p style="font-size:10px; color:var(--jaced-muted); margin-bottom:6px; letter-spacing:.5px; text-transform:uppercase;">New</p>
                <span class="modal-status-chip" id="modalNextChip"></span>
            </div>
        </div>

        <p style="font-size:12px; color:var(--jaced-muted); text-align:center; margin-bottom:20px;">
            This action cannot be undone. The order status will be permanently updated.
        </p>

        <button class="btn-confirm" id="modalConfirmBtn" onclick="confirmStatusUpdate()">
            <span id="modalConfirmLabel"></span>
        </button>
        <button class="btn-cancel-modal" onclick="closeStatusModal()">Cancel</button>
    </div>
</div>

{{-- ── Toast ── --}}
<div class="toast-msg" id="toastMsg"></div>

@push('scripts')
<script>
    let pendingOrderId    = null;
    let pendingNextStatus = null;
    let searchTimer       = null;

    const statusColors = {
        unpaid:     { bg: '#FFF3E0', color: '#E65100' },
        on_process: { bg: '#E8EAF6', color: '#283593' },
        packed:     { bg: '#E3F2FD', color: '#1565C0' },
        delivered:  { bg: '#F3E5F5', color: '#6A1B9A' },
        shipped:    { bg: '#E0F7FA', color: '#00695C' },
        arrived:    { bg: '#E8F5E9', color: '#2E7D32' },
        cancelled:  { bg: '#FFEBEE', color: '#C62828' },
    };

    // ── Live search (debounced) ───────────────────────────────────────
    document.getElementById('searchInput').addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => fetchOrders(1), 400);
    });

    // ── Intercept pagination clicks ───────────────────────────────────
    document.addEventListener('click', function (e) {
        const pageLink = e.target.closest('.pagination .page-link');
        if (pageLink) {
            e.preventDefault();
            const urlString = pageLink.getAttribute('href');
            if (urlString) {
                try {
                    const url  = new URL(urlString, window.location.origin);
                    const page = url.searchParams.get('page');
                    if (page) fetchOrders(page);
                } catch (err) {
                    console.error('Pagination error:', err);
                }
            }
        }
    });

    // ── Clear filters ─────────────────────────────────────────────────
    function clearFilters() {
        ['searchInput','filterDateFrom','filterDateTo'].forEach(id => document.getElementById(id).value = '');
        ['filterStatus','filterPayment'].forEach(id => document.getElementById(id).value = 'all');
        fetchOrders(1);
    }

    // ── AJAX fetch orders ─────────────────────────────────────────────
    function fetchOrders(page = 1) {
        const params = new URLSearchParams({
            search:    document.getElementById('searchInput').value,
            status:    document.getElementById('filterStatus').value,
            payment:   document.getElementById('filterPayment').value,
            date_from: document.getElementById('filterDateFrom').value,
            date_to:   document.getElementById('filterDateTo').value,
            page,
        });

        fetch(`{{ route('admin.order_management.search') }}?${params}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('orderTableBody').innerHTML = data.html;
            if (data.pagination) {
                document.getElementById('paginationLinksContainer').innerHTML = data.pagination;
            }
            const info = document.getElementById('paginationInfo');
            info.textContent = data.total > 0
                ? `Showing ${data.from}–${data.to} of ${data.total} orders`
                : 'No orders found';
        });
    }

    // ── Toggle panel — only one open at a time ────────────────────────
    function togglePanel(id) {
        const panel   = document.getElementById('panel-' + id);
        const chev    = document.getElementById('chev-' + id);
        const chevMob = document.getElementById('chev-mob-' + id);
        const isOpen  = panel.style.display !== 'none';

        // Close all other open panels first
        document.querySelectorAll('[id^="panel-"]').forEach(p => {
            if (p.id !== 'panel-' + id && p.style.display !== 'none') {
                const otherId = p.id.replace('panel-', '');
                p.style.display = 'none';
                const oc  = document.getElementById('chev-' + otherId);
                const ocm = document.getElementById('chev-mob-' + otherId);
                if (oc)  oc.style.transform  = '';
                if (ocm) ocm.style.transform = '';
            }
        });

        // Toggle the clicked panel
        panel.style.display = isOpen ? 'none' : 'block';
        if (chev)    chev.style.transform    = isOpen ? '' : 'rotate(180deg)';
        if (chevMob) chevMob.style.transform = isOpen ? '' : 'rotate(180deg)';
    }

    // ── Status modal ──────────────────────────────────────────────────
    function openStatusModal(orderId, currentStatus, nextStatus, label) {
        pendingOrderId    = orderId;
        pendingNextStatus = nextStatus;

        const curr = statusColors[currentStatus] || { bg:'#F5F5F5', color:'#616161' };
        const next = statusColors[nextStatus]    || { bg:'#F5F5F5', color:'#616161' };

        document.getElementById('modalOrderId').textContent = '#ORD-' + String(orderId).padStart(4, '0');

        const cc = document.getElementById('modalCurrentChip');
        cc.textContent      = currentStatus.replace('_',' ').replace(/\b\w/g, c => c.toUpperCase());
        cc.style.background = curr.bg;
        cc.style.color      = curr.color;

        const nc = document.getElementById('modalNextChip');
        nc.textContent      = nextStatus.replace('_',' ').replace(/\b\w/g, c => c.toUpperCase());
        nc.style.background = next.bg;
        nc.style.color      = next.color;

        document.getElementById('modalConfirmLabel').textContent = label;
        document.getElementById('statusModalOverlay').classList.add('open');
    }

    function closeStatusModal() {
        document.getElementById('statusModalOverlay').classList.remove('open');
        pendingOrderId = null; pendingNextStatus = null;
    }

    document.getElementById('statusModalOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeStatusModal();
    });

    function confirmStatusUpdate() {
        if (!pendingOrderId) return;
        const btn = document.getElementById('modalConfirmBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Updating...';

        fetch(`{{ url('admin/orders') }}/${pendingOrderId}/status`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        })
        .then(r => r.json())
        .then(data => {
            closeStatusModal();
            btn.disabled = false;
            btn.innerHTML = '<span id="modalConfirmLabel"></span>';
            if (data.success) {
                showToast('✓ ' + data.message);
                fetchOrders(1);
            } else {
                showToast('⚠ ' + (data.error || 'Something went wrong.'));
            }
        })
        .catch(() => {
            btn.disabled = false;
            showToast('Network error. Please try again.');
        });
    }

    function showToast(msg) {
        const t = document.getElementById('toastMsg');
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3000);
    }
</script>
@endpush

@endsection