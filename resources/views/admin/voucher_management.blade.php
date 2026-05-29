@extends('layouts.app')

@section('title', 'Voucher Management')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
<style>
    /* ── Stat cards ───────────────────────────────────── */
    .stat-card {
        background: white; border-radius: 12px;
        padding: 20px 24px; border: 1px solid #EDE8E1; flex: 1;
    }
    .stat-label {
        font-size: 11px; font-weight: 600; letter-spacing: .08em;
        text-transform: uppercase; color: var(--jaced-muted);
        margin-bottom: 12px; display: flex; align-items: center; gap: 8px;
    }
    .stat-value { font-size: 32px; font-weight: 700; color: var(--jaced-brown-dark); line-height: 1; margin-bottom: 8px; }
    .stat-sub   { font-size: 12px; color: var(--jaced-muted); }
    .stat-sub.positive { color: #4a7c59; }

    /* ── Table wrapper ────────────────────────────────── */
    .voucher-table-wrapper {
        background: white; border-radius: 12px;
        border: 1px solid #EDE8E1; overflow: hidden; margin-top: 24px;
    }
    .voucher-table-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 20px 24px; border-bottom: 1px solid #EDE8E1;
        flex-wrap: wrap; gap: 12px;
    }
    .voucher-table-header h2 { font-size: 18px; font-weight: 700; color: var(--jaced-brown-dark); margin: 0; }

    /* ── Buttons ──────────────────────────────────────── */
    .btn-outline-jaced {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 16px; border-radius: 8px; border: 1px solid #DDD6CE;
        background: white; font-size: 13px; font-weight: 500;
        color: var(--jaced-brown-dark); cursor: pointer; transition: all .2s; text-decoration: none;
    }
    .btn-outline-jaced:hover { border-color: var(--jaced-sage); color: var(--jaced-sage); }
    .btn-dark-jaced {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 10px 18px; border-radius: 8px; background: var(--jaced-brown-dark);
        border: none; font-size: 13px; font-weight: 600; color: white;
        cursor: pointer; transition: all .2s; text-decoration: none;
    }
    .btn-dark-jaced:hover { background: #3a2a1a; color: white; }

    /* ── Filter bar ───────────────────────────────────── */
    .filter-bar {
        display: none; padding: 16px 24px;
        border-bottom: 1px solid #EDE8E1; background: #FDFBF8;
        gap: 16px; flex-wrap: wrap; align-items: flex-end;
    }
    .filter-bar.open { display: flex; }
    .filter-group { display: flex; flex-direction: column; gap: 4px; }
    .filter-label-sm { font-size: 10px; font-weight: 600; letter-spacing: .7px; text-transform: uppercase; color: var(--jaced-muted); }
    .filter-select-sm {
        font-size: 13px; border: 1px solid #DDD6CE; border-radius: 8px;
        padding: 7px 12px; color: var(--jaced-brown-dark); background: white;
        outline: none; height: 36px; min-width: 130px;
    }
    .filter-select-sm:focus { border-color: var(--jaced-caramel); }

    /* Discount range slider */
    .range-wrap { display: flex; flex-direction: column; gap: 6px; min-width: 200px; }
    .range-inputs { display: flex; align-items: center; gap: 8px; }
    .range-field {
        width: 90px; font-size: 12px; border: 1px solid #DDD6CE;
        border-radius: 6px; padding: 5px 8px; color: var(--jaced-brown-dark);
        background: white; outline: none; height: 32px;
    }
    .range-field:focus { border-color: var(--jaced-caramel); }
    .range-sep { font-size: 12px; color: var(--jaced-muted); }

    .btn-apply-sm {
        background: var(--jaced-brown-dark); color: white; border: none;
        border-radius: 8px; padding: 7px 16px; font-size: 13px; font-weight: 600;
        cursor: pointer; height: 36px; transition: background .15s;
    }
    .btn-apply-sm:hover { background: #3a2a1a; }
    .btn-clear-sm {
        background: white; color: var(--jaced-muted); border: 1px solid #DDD6CE;
        border-radius: 8px; padding: 7px 12px; font-size: 13px; font-weight: 500;
        cursor: pointer; height: 36px;
    }
    .btn-clear-sm:hover { background: #f5f0eb; }

    /* ── Table ────────────────────────────────────────── */
    table.jaced-table { width: 100%; border-collapse: collapse; }
    table.jaced-table thead tr { background: #FAFAF8; border-bottom: 1px solid #EDE8E1; }
    table.jaced-table thead th { padding: 12px 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; color: var(--jaced-muted); text-align: left; }
    table.jaced-table tbody tr { border-bottom: 1px solid #F0EBE4; transition: background .15s; }
    table.jaced-table tbody tr:last-child { border-bottom: none; }
    table.jaced-table tbody tr:hover { background: #FDFBF8; }
    table.jaced-table tbody td { padding: 16px 20px; font-size: 14px; color: var(--jaced-brown-dark); vertical-align: middle; }

    .voucher-name { font-weight: 700; font-size: 14px; color: var(--jaced-brown-dark); }
    .voucher-desc { font-size: 12px; color: var(--jaced-muted); margin-top: 2px; }

    /* Status badges */
    .badge-status { display: inline-block; padding: 4px 12px; border-radius: 99px; font-size: 11px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; }
    .badge-active   { background: #E8F5EC; color: #2e7d47; border: 1px solid #b6dfc4; }
    .badge-inactive { background: #F0F0F0; color: #666;    border: 1px solid #D8D8D8; }

    /* Usage bar */
    .usage-bar-wrap { display: flex; flex-direction: column; gap: 5px; min-width: 120px; }
    .usage-bar-top  { display: flex; justify-content: space-between; font-size: 12px; color: var(--jaced-muted); }
    .usage-bar-top span:first-child { color: var(--jaced-brown-dark); font-weight: 500; }
    .usage-bar      { height: 5px; background: #EDE8E1; border-radius: 99px; overflow: hidden; }
    .usage-bar-fill { height: 100%; border-radius: 99px; background: var(--jaced-brown-dark); transition: width .4s ease; }
    .usage-bar-fill.warn { background: #e67e22; }
    .usage-bar-fill.full { background: #c0392b; }

    /* Action buttons */
    .action-btns { display: flex; gap: 6px; }
    .action-btn {
        width: 32px; height: 32px; border-radius: 7px; border: 1px solid #EDE8E1;
        background: white; display: flex; align-items: center; justify-content: center;
        cursor: pointer; color: var(--jaced-muted); transition: all .2s; text-decoration: none;
        font-size: 13px;
    }
    .action-btn:hover { border-color: var(--jaced-sage); color: var(--jaced-sage); background: #f0f4f1; }
    .action-btn.danger:hover { border-color: #e74c3c; color: #e74c3c; background: #FDECEA; }
    .action-btn.toggle-btn.is-inactive { border-color: #e67e22; color: #e67e22; }

    /* Pagination */
    .pagination-wrap { display: flex; align-items: center; justify-content: space-between; padding: 16px 24px; border-top: 1px solid #EDE8E1; flex-wrap: wrap; gap: 8px; }
    .pagination-wrap .showing-text { font-size: 13px; color: var(--jaced-muted); }
    .pagination-btns { display: flex; align-items: center; gap: 4px; }
    .page-btn {
        width: 34px; height: 34px; border-radius: 7px; border: 1px solid #EDE8E1;
        background: white; font-size: 13px; color: var(--jaced-brown-dark); font-weight: 500;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: all .2s; text-decoration: none;
    }
    .page-btn:hover { border-color: var(--jaced-sage); color: var(--jaced-sage); }
    .page-btn.active { background: var(--jaced-brown-dark); color: white; border-color: var(--jaced-brown-dark); }
    .page-btn.disabled { opacity: .4; cursor: not-allowed; pointer-events: none; }

    /* ── Slide-in Drawer ──────────────────────────────── */
    .drawer-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.4); z-index: 9998;
    }
    .drawer-overlay.open { display: block; }
    .drawer {
        position: fixed; top: 0; right: -480px; width: 460px; max-width: 95vw;
        height: 100vh; background: white; z-index: 9999;
        box-shadow: -8px 0 32px rgba(0,0,0,.12);
        transition: right .3s cubic-bezier(.4,0,.2,1);
        display: flex; flex-direction: column;
        overflow: hidden;
    }
    .drawer.open { right: 0; }
    .drawer-head {
        padding: 24px 28px 20px; border-bottom: 1px solid #EDE8E1;
        display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;
    }
    .drawer-head h3 { font-size: 18px; font-weight: 700; color: var(--jaced-brown-dark); margin: 0; }
    .drawer-close {
        width: 32px; height: 32px; border-radius: 8px; border: 1px solid #EDE8E1;
        background: white; cursor: pointer; display: flex; align-items: center;
        justify-content: center; font-size: 18px; color: var(--jaced-muted); transition: all .15s;
    }
    .drawer-close:hover { background: #FDECEA; border-color: #e74c3c; color: #e74c3c; }
    .drawer-body { flex: 1; overflow-y: auto; padding: 24px 28px; }
    .drawer-footer { padding: 16px 28px; border-top: 1px solid #EDE8E1; flex-shrink: 0; display: flex; gap: 10px; }

    /* Drawer form fields */
    .form-group { margin-bottom: 20px; }
    .form-label { font-size: 11px; font-weight: 600; letter-spacing: .07em; text-transform: uppercase; color: var(--jaced-muted); margin-bottom: 6px; display: block; }
    .form-input, .form-select, .form-textarea {
        width: 100%; font-size: 13px; border: 1px solid #DDD6CE;
        border-radius: 8px; padding: 10px 12px; color: var(--jaced-brown-dark);
        background: white; outline: none; transition: border-color .15s;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--jaced-caramel); }
    .form-textarea { resize: none; height: 80px; }
    .form-hint { font-size: 11px; color: var(--jaced-muted); margin-top: 4px; }

    /* Point cost preview */
    .point-preview {
        background: #FDFBF8; border: 1px solid #EDE8E1; border-radius: 8px;
        padding: 12px 14px; margin-top: 16px; font-size: 13px; color: var(--jaced-brown-dark);
        display: flex; justify-content: space-between; align-items: center;
    }
    .point-preview span { font-weight: 700; color: var(--jaced-caramel); font-size: 15px; }

    /* Type selector */
    .type-selector { display: flex; gap: 10px; }
    .type-option { flex: 1; }
    .type-option input[type="radio"] { display: none; }
    .type-option label {
        display: flex; flex-direction: column; align-items: center; gap: 6px;
        padding: 14px 10px; border: 2px solid #DDD6CE; border-radius: 10px;
        cursor: pointer; transition: all .2s; font-size: 12px; font-weight: 600;
        color: var(--jaced-muted); text-align: center;
    }
    .type-option label i { font-size: 20px; }
    .type-option input:checked + label { border-color: var(--jaced-brown-dark); color: var(--jaced-brown-dark); background: #FDFBF8; }

    /* Toast */
    .toast-msg {
        position: fixed; bottom: 24px; right: 24px;
        background: #1A1714; color: white; padding: 12px 20px;
        border-radius: 10px; font-size: 13px; font-weight: 500;
        box-shadow: 0 8px 24px rgba(0,0,0,.2); z-index: 99999;
        opacity: 0; transform: translateY(8px);
        transition: opacity .25s, transform .25s; pointer-events: none;
    }
    .toast-msg.show { opacity: 1; transform: translateY(0); }
</style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- ── Page Header ── --}}
    <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:32px; flex-wrap:wrap; gap:12px;">
        <div>
            <h1 style="font-size:32px; font-weight:800; color:var(--jaced-brown-dark); margin:0 0 6px;">
                Voucher Management
            </h1>
            <p style="font-size:14px; color:var(--jaced-muted); margin:0;">
                Create and manage promotional vouchers for your customers.
            </p>
        </div>
        <button class="btn-dark-jaced" onclick="openDrawer()">
            <i class="bi bi-plus-circle"></i> Create New Voucher
        </button>
    </div>

    {{-- ── Stat Cards ── --}}
    <div style="display:flex; gap:16px; margin-bottom:8px; flex-wrap:wrap;">

        <div class="stat-card">
            <div class="stat-label">
                <i class="bi bi-ticket-perforated"></i> Total Voucher Types
            </div>
            <div class="stat-value" id="statTotalTypes">{{ number_format($totalTypes) }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">
                <i class="bi bi-check-circle"></i> Active Types
            </div>
            <div class="stat-value" id="statActiveTypes">{{ number_format($activeTypes) }}</div>
            <div class="stat-sub" id="statInactiveTypes">{{ $totalTypes - $activeTypes }} inactive</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">
                <i class="bi bi-arrow-repeat"></i> Total Redeemed
            </div>
            <div class="stat-value" id="statTotalRedeemed">{{ number_format($totalRedeemed) }}</div>
            <div class="stat-sub">
                @if($totalTypes > 0)
                    {{ round(($totalRedeemed / $totalTypes) * 100) }}% redemption rate
                @else
                    No vouchers yet
                @endif
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">
                <i class="bi bi-currency-dollar"></i> Total Discount Given
            </div>
            <div class="stat-value" id="statTotalDiscount">Rp {{ number_format($totalDiscount, 0, ',', '.') }}</div>
        </div>

    </div>

    {{-- ── Voucher Table ── --}}
    <div class="voucher-table-wrapper">

        <div class="voucher-table-header">
            <h2>All Voucher Types</h2>
            <div style="display:flex; gap:10px;">
                <button class="btn-outline-jaced" onclick="toggleFilter()">
                    <i class="bi bi-funnel"></i> Filter
                </button>
            </div>
        </div>

        {{-- Filter Bar --}}
        <div class="filter-bar" id="filterBar">
            <div class="filter-group">
                <span class="filter-label-sm">Type</span>
                <select class="filter-select-sm" id="fType">
                    <option value="all">All Types</option>
                    <option value="product"  {{ request('type') === 'product'  ? 'selected' : '' }}>Product</option>
                    <option value="delivery" {{ request('type') === 'delivery' ? 'selected' : '' }}>Delivery</option>
                </select>
            </div>
            <div class="filter-group">
                <span class="filter-label-sm">Status</span>
                <select class="filter-select-sm" id="fStatus">
                    <option value="all">All Status</option>
                    <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="filter-group">
                <span class="filter-label-sm">Max Discount Range (Rp)</span>
                <div class="range-inputs">
                    <input type="number" class="range-field" id="fMinDiscount" placeholder="Min" value="{{ request('min_discount') }}" step="10000">
                    <span class="range-sep">–</span>
                    <input type="number" class="range-field" id="fMaxDiscount" placeholder="Max" value="{{ request('max_discount') }}" step="10000">
                </div>
            </div>
            <button class="btn-apply-sm" onclick="applyFilters()">Apply</button>
            <button class="btn-clear-sm" onclick="clearFilters()">Clear</button>
        </div>

        {{-- Table --}}
        <table class="jaced-table">
            <thead>
                <tr>
                    <th>Voucher Name</th>
                    <th>Type</th>
                    <th>Discount</th>
                    <th>Max Discount</th>
                    <th>Point Cost</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paged as $vt)
                @php
                    $usedPct  = $vt->total_quantity > 0 ? round(($vt->redeemed_count / $vt->total_quantity) * 100) : 0;
                    $fillClass = $usedPct >= 100 ? 'full' : ($usedPct >= 75 ? 'warn' : '');
                @endphp
                <tr id="row-{{ $vt->id }}">
                    <td>
                        <div class="voucher-name">{{ $vt->name }}</div>
                        <div class="voucher-desc">{{ Str::limit($vt->description, 60) }}</div>
                    </td>
                    <td>
                        <span style="text-transform:capitalize; font-size:13px;">
                            {{ $vt->used_for === 'product' ? '🛍️ Product' : '🚚 Delivery' }}
                        </span>
                    </td>
                    <td><strong>{{ $vt->discount_percentage }}%</strong></td>
                    <td>Rp {{ number_format($vt->max_discount, 0, ',', '.') }}</td>
                    <td>{{ number_format($vt->point_cost) }} pts</td>
                    <td>
                        <div class="usage-bar-wrap">
                            <div class="usage-bar-top">
                                <span>{{ $vt->redeemed_count }}/{{ $vt->total_quantity }}</span>
                                <span>{{ $usedPct }}%</span>
                            </div>
                            <div class="usage-bar">
                                <div class="usage-bar-fill {{ $fillClass }}" style="width:{{ $usedPct }}%;"></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge-status {{ $vt->is_active ? 'badge-active' : 'badge-inactive' }}" id="badge-{{ $vt->id }}">
                            {{ $vt->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <button class="action-btn"
                            title="View Used Orders"
                            onclick="viewUsedOrders('{{ $vt->id }}', '{{ addslashes($vt->name) }}')">
                            <i class="bi bi-eye"></i>
                        </button>
                            {{-- Toggle active/inactive --}}
                            <button class="action-btn toggle-btn {{ !$vt->is_active ? 'is-inactive' : '' }}"
                                title="{{ $vt->is_active ? 'Deactivate' : 'Activate' }}"
                                onclick="toggleVoucher('{{ $vt->id }}', this)">
                                <i class="bi {{ $vt->is_active ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                            </button>
                            {{-- Delete --}}
                            <button class="action-btn danger" title="Delete"
                                onclick="deleteVoucher('{{ $vt->id }}', '{{ addslashes($vt->name) }}')">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:48px; color:var(--jaced-muted);">
                        No voucher types found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="pagination-wrap">
            <span class="showing-text">
                Showing {{ ($currentPage - 1) * $perPage + 1 }}–{{ min($currentPage * $perPage, $total) }} of {{ $total }} voucher types
            </span>
            <div class="pagination-btns">
                <a href="?page={{ max(1, $currentPage - 1) }}" class="page-btn {{ $currentPage <= 1 ? 'disabled' : '' }}">‹</a>
                @for($p = max(1, $currentPage - 2); $p <= min($lastPage, $currentPage + 2); $p++)
                    <a href="?page={{ $p }}" class="page-btn {{ $p === $currentPage ? 'active' : '' }}">{{ $p }}</a>
                @endfor
                @if($lastPage > $currentPage + 2)
                    <span class="page-btn" style="border:none; cursor:default;">…</span>
                    <a href="?page={{ $lastPage }}" class="page-btn">{{ $lastPage }}</a>
                @endif
                <a href="?page={{ min($lastPage, $currentPage + 1) }}" class="page-btn {{ $currentPage >= $lastPage ? 'disabled' : '' }}">›</a>
            </div>
        </div>

    </div>

</div>
</div>

{{-- ── Slide-in Drawer ── --}}
<div class="drawer-overlay" id="drawerOverlay" onclick="closeDrawer()"></div>
<div class="drawer" id="drawer">
    <div class="drawer-head">
        <h3>Create New Voucher</h3>
        <button class="drawer-close" onclick="closeDrawer()">×</button>
    </div>

    <div class="drawer-body">

        {{-- Type selector --}}
        <div class="form-group">
            <label class="form-label">Voucher Type</label>
            <div class="type-selector">
                <div class="type-option">
                    <input type="radio" name="used_for" id="typeProduct" value="product" checked>
                    <label for="typeProduct">
                        <i class="bi bi-bag-heart"></i>
                        Product Discount
                    </label>
                </div>
                <div class="type-option">
                    <input type="radio" name="used_for" id="typeDelivery" value="delivery">
                    <label for="typeDelivery">
                        <i class="bi bi-truck"></i>
                        Free Delivery
                    </label>
                </div>
            </div>
        </div>

        {{-- Name --}}
        <div class="form-group">
            <label class="form-label" for="dName">Voucher Name</label>
            <input type="text" id="dName" class="form-input" placeholder="e.g. Diskon 15% - Produk s.d. Rp150.000">
            <p class="form-hint">Vouchers with the same name will be grouped together in the table.</p>
        </div>

        {{-- Description --}}
        <div class="form-group">
            <label class="form-label" for="dDesc">Description</label>
            <textarea id="dDesc" class="form-textarea" placeholder="Describe what this voucher offers..."></textarea>
        </div>

        {{-- Discount % --}}
        <div class="form-group">
            <label class="form-label" for="dDiscountPct">Discount Percentage (%)</label>
            <input type="number" id="dDiscountPct" class="form-input" placeholder="e.g. 15" min="1" max="100" oninput="updatePointPreview()">
        </div>

        {{-- Max Discount --}}
        <div class="form-group">
            <label class="form-label" for="dMaxDiscount">Max Discount (Rp)</label>
            <input type="number" id="dMaxDiscount" class="form-input" placeholder="e.g. 150000" min="1000" step="1000" oninput="updatePointPreview()">
            <p class="form-hint">The maximum rupiah amount that will be discounted.</p>
        </div>

        {{-- Quantity --}}
        <div class="form-group">
            <label class="form-label" for="dQuantity">Quantity</label>
            <input type="number" id="dQuantity" class="form-input" placeholder="e.g. 5" min="1" max="100" value="1">
            <p class="form-hint">How many individual voucher codes to generate.</p>
        </div>

        {{-- Point cost preview --}}
        <div class="point-preview" id="pointPreview" style="display:none;">
            <span style="font-size:13px; color:var(--jaced-muted); font-weight:400;">Auto-calculated point cost</span>
            <span id="pointPreviewValue">0 pts</span>
        </div>

    </div>

    <div class="drawer-footer">
        <button class="btn-dark-jaced" style="flex:1; justify-content:center;" onclick="submitDrawer()">
            <i class="bi bi-plus-circle"></i> Create Voucher
        </button>
        <button class="btn-outline-jaced" onclick="closeDrawer()">Cancel</button>
    </div>
</div>

{{-- ── Toast ── --}}
<div class="toast-msg" id="toastMsg"></div>

{{-- Used Orders Modal --}}
<div class="drawer-overlay" id="usedOrdersOverlay" onclick="closeUsedOrdersModal()"></div>

<div class="drawer" id="usedOrdersDrawer">
    <div class="drawer-head">
        <h3 id="usedOrdersTitle">Used Orders</h3>
        <button class="drawer-close" onclick="closeUsedOrdersModal()">×</button>
    </div>

    <div class="drawer-body">
        <div id="usedOrdersContent">
            <p style="color:var(--jaced-muted);">Loading...</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // ── Filter ───────────────────────────────────────────────────────
    function toggleFilter() {
        document.getElementById('filterBar').classList.toggle('open');
    }
    function applyFilters() {
        const params = new URLSearchParams({
            type:         document.getElementById('fType').value,
            status:       document.getElementById('fStatus').value,
            min_discount: document.getElementById('fMinDiscount').value,
            max_discount: document.getElementById('fMaxDiscount').value,
            page: 1,
        });
        window.location.href = '?' + params.toString();
    }
    function clearFilters() {
        window.location.href = '?';
    }

    // ── Drawer ────────────────────────────────────────────────────────
    function openDrawer() {
        document.getElementById('drawer').classList.add('open');
        document.getElementById('drawerOverlay').classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeDrawer() {
        document.getElementById('drawer').classList.remove('open');
        document.getElementById('drawerOverlay').classList.remove('open');
        document.body.style.overflow = '';
    }

    // ── Point cost preview ────────────────────────────────────────────
    function updatePointPreview() {
        const maxDiscount = parseFloat(document.getElementById('dMaxDiscount').value);
        const preview     = document.getElementById('pointPreview');
        const previewVal  = document.getElementById('pointPreviewValue');

        if (maxDiscount > 0) {
            const pts = Math.round(maxDiscount / 250);
            previewVal.textContent = pts.toLocaleString('id-ID') + ' pts';
            preview.style.display = 'flex';
        } else {
            preview.style.display = 'none';
        }
    }

    // ── Submit new voucher ────────────────────────────────────────────
    function submitDrawer() {
        const usedFor  = document.querySelector('input[name="used_for"]:checked').value;
        const name     = document.getElementById('dName').value.trim();
        const desc     = document.getElementById('dDesc').value.trim();
        const discPct  = document.getElementById('dDiscountPct').value;
        const maxDisc  = document.getElementById('dMaxDiscount').value;
        const qty      = document.getElementById('dQuantity').value;

        if (!name || !desc || !discPct || !maxDisc || !qty) {
            showToast('⚠ Please fill in all fields.');
            return;
        }

        fetch('{{ route("admin.vouchers.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                used_for:             usedFor,
                name:                 name,
                description:          desc,
                discount_percentage:  discPct,
                max_discount:         maxDisc,
                quantity:             qty,
            }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                closeDrawer();
                showToast('✓ ' + data.message);
                refreshStats();
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showToast('Error: ' + (data.error || 'Something went wrong.'));
            }
        })
        .catch(() => showToast('Network error. Please try again.'));
    }

    // ── Toggle active/inactive ────────────────────────────────────────
    function toggleVoucher(id, btn) {
        fetch(`{{ url('admin/vouchers') }}/${id}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const badge = document.getElementById('badge-' + id);
                const icon  = btn.querySelector('i');

                if (data.is_active) {
                    badge.textContent = 'Active';
                    badge.className   = 'badge-status badge-active';
                    icon.className    = 'bi bi-toggle-on';
                    btn.classList.remove('is-inactive');
                    btn.title = 'Deactivate';
                } else {
                    badge.textContent = 'Inactive';
                    badge.className   = 'badge-status badge-inactive';
                    icon.className    = 'bi bi-toggle-off';
                    btn.classList.add('is-inactive');
                    btn.title = 'Activate';
                }
                showToast('✓ ' + data.message);
                refreshStats();
            } else {
                showToast('Error: ' + (data.error || 'Something went wrong.'));
            }
        });
    }

    // ── Delete ────────────────────────────────────────────────────────
    function deleteVoucher(id, name) {
        if (!confirm(`Delete all "${name}" vouchers?\n\nThis cannot be undone.`)) return;

        fetch(`{{ url('admin/vouchers') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast('✓ ' + data.message);
                refreshStats();
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showToast('⚠ ' + data.error);
            }
        });
    }

    // ── Toast ─────────────────────────────────────────────────────────
    function showToast(msg) {
        const t = document.getElementById('toastMsg');
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3000);
    }

    function refreshStats() {
        fetch('{{ route("admin.vouchers.stats") }}')
            .then(r => r.json())
            .then(data => {
                document.getElementById('statTotalTypes').textContent    = data.totalTypes;
                document.getElementById('statActiveTypes').textContent   = data.activeTypes;
                document.getElementById('statInactiveTypes').textContent = data.inactiveTypes + ' inactive';
                document.getElementById('statTotalRedeemed').textContent = data.totalRedeemed;
                document.getElementById('statTotalDiscount').textContent = data.totalDiscount;
                document.getElementById('statTotalDiscountFull').textContent = data.totalDiscountFull;
            });
    }

    function viewUsedOrders(id, voucherName) {

        document.getElementById('usedOrdersTitle').textContent =
            `Orders Using "${voucherName}"`;

        document.getElementById('usedOrdersContent').innerHTML =
            `<p style="color:var(--jaced-muted);">Loading...</p>`;

        document.getElementById('usedOrdersDrawer').classList.add('open');
        document.getElementById('usedOrdersOverlay').classList.add('open');

        fetch(`/admin/vouchers/${id}/used-orders`)
            .then(r => r.json())
            .then(data => {

                if (!data.success) {
                    document.getElementById('usedOrdersContent').innerHTML =
                        `<p style="color:#c0392b;">${data.error}</p>`;
                    return;
                }

                if (data.orders.length === 0) {
                    document.getElementById('usedOrdersContent').innerHTML = `
                        <p style="color:var(--jaced-muted);">
                            No orders have used this voucher yet.
                        </p>
                    `;
                    return;
                }

                let html = `
                    <table class="jaced-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                data.orders.forEach(order => {

                    const customer =
                        `${order.first_name ?? ''} ${order.last_name ?? ''}`;

                    html += `
                        <tr>
                            <td>#${order.id}</td>
                            <td>${customer}</td>
                            <td>Rp ${Number(order.total_price).toLocaleString('id-ID')}</td>
                            <td>${order.status}</td>
                            <td>
                                ${new Date(order.created_at).toLocaleDateString('id-ID')}
                            </td>
                        </tr>
                    `;
                });

                html += `</tbody></table>`;

                document.getElementById('usedOrdersContent').innerHTML = html;
            });
    }

    function closeUsedOrdersModal() {
        document.getElementById('usedOrdersDrawer').classList.remove('open');
        document.getElementById('usedOrdersOverlay').classList.remove('open');
    }
</script>
@endpush

@endsection 