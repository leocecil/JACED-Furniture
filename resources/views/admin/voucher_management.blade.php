@extends('layouts.app')

@section('title', 'Voucher Management')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/voucher-management.css') }}">
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
            <input type="text" id="dMaxDiscount" class="form-input" placeholder="e.g. 150000" min="1000" step="1000" oninput="formatRupiah(this); updatePointPreview()">
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
    window.voucherConfig = {
        csrf: "{{ csrf_token() }}",
        storeUrl: "{{ route('admin.vouchers.store') }}",
        statsUrl: "{{ route('admin.vouchers.stats') }}",
        baseUrl: "{{ url('admin/vouchers') }}"
    };
</script>
<script src="{{ asset('js/admin/voucher-management.js') }}"></script>
@endpush

@endsection 