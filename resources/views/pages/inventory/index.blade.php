@extends('layouts.app')

@section('title', 'Inventory')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Studio</a></li>
    <li class="breadcrumb-item active">Inventory</li>
@endsection

@section('page-title', 'Inventory Ledger')

@section('page-actions')
    <button type="button"
            class="btn btn-sm px-4 py-2 d-flex align-items-center gap-2"
            style="background:#1e1c18; color:#f5f2ee; border:none; border-radius:8px; font-size:13px; font-weight:600;"
            data-bs-toggle="modal"
            data-bs-target="#addItemModal">
        <i class="bi bi-plus-lg"></i> Add New Item
    </button>
@endsection

@push('styles')
<style>
    .category-wrapper {
        mask-image: linear-gradient(to right, black 85%, transparent 100%);
        -webkit-mask-image: linear-gradient(to right, black 85%, transparent 100%);
    }
    .category-scroll {
        scroll-behavior: smooth;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .category-scroll::-webkit-scrollbar { display: none; }

    .btn-category-inactive {
        color: var(--jaced-brown, #6b6860) !important;
        opacity: 0.7; transition: all 0.2s ease;
    }
    .btn-category-inactive:hover {
        opacity: 1;
        background-color: rgba(0,0,0,0.05);
        border-radius: 50px;
    }
    .btn-add-category { color: #6b8f71 !important; font-weight: 600 !important; }

    .dropdown-item { transition: all 0.2s; }
    .dropdown-item:hover { background-color: #f0eeeb !important; }
    .dropdown-item.active { background-color: #c4a882 !important; color: white !important; }
    .dropdown-toggle::after { display: none !important; }

    .active-view { background-color: #f0eeeb; color: #1a1a18; }
    .btn-close:focus { box-shadow: none !important; }
</style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- Subtitle --}}
    <p class="text-muted small mb-4">
        Manage your premium stock items, monitor material availability, and track upcoming shipments.
    </p>

    {{-- Filter Bar --}}
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">

        <div class="d-flex align-items-center gap-2" style="flex: 1; min-width: 0;">
            <div class="category-wrapper" style="max-width: 500px; overflow: hidden;">
                <div class="d-flex gap-2 overflow-auto category-scroll flex-nowrap py-1">
                    <button class="btn btn-sm rounded-pill px-4 py-2 fw-bold flex-shrink-0"
                            style="background: #c4a882; color: white;">All Collections</button>
                    <button class="btn btn-sm btn-category-inactive px-4 py-2 flex-shrink-0 border-0">Seating</button>
                    <button class="btn btn-sm btn-category-inactive px-4 py-2 flex-shrink-0 border-0">Tables</button>
                    <button class="btn btn-sm btn-category-inactive px-4 py-2 flex-shrink-0 border-0">Storage</button>
                    <button class="btn btn-sm btn-category-inactive px-4 py-2 flex-shrink-0 border-0">Lighting</button>
                </div>
            </div>
            <button class="btn btn-sm btn-add-category flex-shrink-0 border-0 bg-transparent ms-2">
                <i class="bi bi-plus-circle-fill me-1"></i> Add Category
            </button>
        </div>

        <div class="d-flex align-items-center gap-3 ms-3">
            <div class="dropdown">
                <button class="btn btn-sm border-0 p-0 dropdown-toggle fw-bold d-flex align-items-center text-muted"
                        type="button" id="sortDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false"
                        style="font-size: 0.85rem;">
                    <i class="bi bi-filter-left fs-5 me-1"></i>
                    SORT BY: <span class="ms-1 text-dark">NEWEST</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-2"
                    style="background-color: #fff; border-radius: 12px; min-width: 160px;">
                    <li><a class="dropdown-item rounded-2 small py-2 fw-medium active" href="#">Newest</a></li>
                    <li><a class="dropdown-item rounded-2 small py-2 fw-medium" href="#">Oldest</a></li>
                    <li><a class="dropdown-item rounded-2 small py-2 fw-medium" href="#">Price: High to Low</a></li>
                    <li><a class="dropdown-item rounded-2 small py-2 fw-medium" href="#">Price: Low to High</a></li>
                    <li><a class="dropdown-item rounded-2 small py-2 fw-medium" href="#">Stock: Low to High</a></li>
                </ul>
            </div>

            <div class="bg-white rounded-3 p-1 shadow-sm d-flex gap-1" style="border: 1px solid #e2ddd8;">
                <button class="btn btn-sm p-1 px-2 border-0 active-view" style="border-radius: 6px;">
                    <i class="bi bi-grid-fill"></i>
                </button>
                <button class="btn btn-sm p-1 px-2 border-0 text-muted" style="border-radius: 6px;">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </div>

    </div>

    {{-- Inventory Grid --}}
    <div class="row g-4">
        @include('pages.inventory.item-grid')
    </div>

    {{-- Load More --}}
    <div class="text-center mt-5">
        <p class="text-muted small mb-3">Showing 6 of 124 furniture items</p>
        <button class="btn btn-outline-secondary px-5" style="border-color: #e2ddd8; color: #1a1a18;">
            Load More Stock
        </button>
    </div>

</div>
@endsection


{{-- ══════════════════════════════════════════
     MODAL: dirender lewat @stack('modals')
     di app.blade.php — di luar .app-shell
     sehingga tidak terpotong overflow
══════════════════════════════════════════ --}}
@push('modals')
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">

            <div class="modal-header border-0 pt-4 px-4 pb-2">
                <h5 class="modal-title fw-bold" id="addItemModalLabel">Add New Product</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>

            <form action="#" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4">

                    <div class="mb-3">
                        <label for="name" class="form-label fw-medium small">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                               placeholder="e.g., Sculptural Lounge" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label fw-medium small">Price ($)</label>
                            <input type="text" class="form-control" id="price" name="price"
                                   placeholder="e.g., 4,250" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="available_units" class="form-label fw-medium small">Available Units</label>
                            <input type="number" class="form-control" id="available_units" name="available_units"
                                   placeholder="e.g., 12" min="0" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label fw-medium small">Category</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="" selected disabled>Select Category</option>
                                <option value="SEATING">Seating</option>
                                <option value="TABLES">Tables</option>
                                <option value="STORAGE">Storage</option>
                                <option value="LIGHTING">Lighting</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="material" class="form-label fw-medium small">Material</label>
                            <input type="text" class="form-control" id="material" name="material"
                                   placeholder="e.g., Premium Walnut" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label fw-medium small">Product Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>

                </div>

                <div class="modal-footer border-0 pb-4 px-4 pt-2 d-flex gap-2">
                    <button type="button"
                            class="btn btn-sm flex-grow-1 py-2 rounded-3"
                            data-bs-dismiss="modal"
                            style="background-color: #f0eeeb; color: #1a1a18; border: none;">
                        Cancel
                    </button>
                    <button type="submit"
                            class="btn btn-sm flex-grow-1 py-2 rounded-3"
                            style="background: #c4a882; color: white; border: none;">
                        Save Product
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endpush