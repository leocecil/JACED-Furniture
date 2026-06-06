@extends('layouts.app')

@section('title', 'Inventory')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Studio</a></li>
    <li class="breadcrumb-item active">Inventory</li>
@endsection

@section('page-title', 'Inventory Ledger')

@section('page-actions')
    <button type="button"
            class="btn btn-sm px-4 py-2 d-flex align-items-center gap-2"
            style="background:#1e1c18; color:#f5f2ee; border:none; border-radius:8px; font-size:13px; font-weight:600;"
            data-bs-toggle="modal" data-bs-target="#addItemModal">
        <i class="bi bi-plus-lg"></i> Add New Item
    </button>
@endsection

@push('styles')
<style>
    :root {
        --jaced-white: #F9F9F7;
        --jaced-cream: #F2EDE6;
        --jaced-card: #FAF7F2;
        --jaced-brown-dark: #272E1D;
        --jaced-dark: #1C1C1A;
        --jaced-brown: #5A4D47;
        --jaced-caramel: #C99A6B;
        --jaced-sage: #5A6B5B;
        --jaced-input: #DDD6CE;
        --jaced-muted: #8A857D;
        --jaced-caramel-bg: #F5EBE0;
    }

    body {
        font-family: 'Lexend', sans-serif !important;
        color: var(--jaced-brown-dark) !important;
    }

    body, h1, h2, h3, h4, h5, h6, p, a, span, div, 
    input, button, select, textarea, label, td, th, li {
        font-family: 'Lexend', sans-serif !important;
    }

    /* ══ PERBAIKAN: Penanda Horizontal Scrollable Area untuk Kategori ══ */
    .category-wrapper { 
        position: relative;
        /* Efek Fade Out di sisi kanan sebagai isyarat bahwa teks masih berlanjut dan bisa digeser */
        mask-image: linear-gradient(to right, black 85%, transparent 100%); 
        -webkit-mask-image: linear-gradient(to right, black 85%, transparent 100%); 
    }
    
    .category-scroll { 
        scroll-behavior: smooth; 
        -ms-overflow-style: none;  /* 🌟 FIX: Hilangkan scrollbar di Internet Explorer & Edge lama */
        scrollbar-width: none;     /* 🌟 FIX: Hilangkan scrollbar di Firefox */
    }
    
    /* 🌟 FIX: Hilangkan scrollbar horizontal di Chrome, Safari, Opera, dan Edge Chromium */
    .category-scroll::-webkit-scrollbar { 
        display: none !important;
        height: 0px !important; 
    }
    
    /* 🌟 FIX: Mengubah tampilan kategori pasif (Font Cokelat, Background Putih Lingkaran Sempurna) */
    .btn-category-inactive { 
        color: var(--jaced-brown) !important; 
        background-color: #ffffff !important; 
        opacity: 0.85; 
        border-radius: 50px !important;
        font-weight: 500 !important;
        border: 1px solid #e2ddd8 !important;
        transition: all 0.2s ease; 
    }
    
    /* Efek hover saat kursor mendekati tombol kategori */
    .btn-category-inactive:hover { 
        opacity: 1; 
        background-color: var(--jaced-cream) !important;
        color: var(--jaced-brown-dark) !important;
    }
    
    .btn-add-category { color: var(--jaced-sage) !important; font-weight: 600 !important; }
    .dropdown-item { transition: all 0.2s; }
    .dropdown-item:hover { background-color: #f0eeeb !important; }
    .dropdown-item.active { background-color: var(--jaced-caramel) !important; color: white !important; }
    .dropdown-toggle::after { display: none !important; }
    .btn-close:focus { box-shadow: none !important; }

    /* Modal */
    .modal-section-title {
        font-size: 11px; font-weight: 700; letter-spacing: 0.1em;
        text-transform: uppercase; color: var(--jaced-muted);
        margin: 20px 0 12px; padding-bottom: 6px;
        border-bottom: 1px solid #f0eeeb;
    }
    .modal-section-title:first-child { margin-top: 0; }

    .form-label { font-size: 12px; font-weight: 600; color: var(--jaced-brown-dark); margin-bottom: 5px; }
    .form-control, .form-select {
        border: 1px solid var(--jaced-input); border-radius: 8px;
        font-size: 13px; background: var(--jaced-white); box-shadow: none !important;
    }
    .form-control:focus, .form-select:focus { border-color: var(--jaced-caramel); background: #fff; }
    .input-group .form-control { border-radius: 0 8px 8px 0 !important; }
    .input-group-text {
        background: #f0eeeb; border: 1px solid var(--jaced-input);
        border-right: none; border-radius: 8px 0 0 8px !important;
        font-size: 12px; font-weight: 600; color: var(--jaced-muted);
    }

    /* Switch */
    .form-check-input:checked { background-color: var(--jaced-caramel) !important; border-color: var(--jaced-caramel) !important; }
    .switch-row { display: flex; align-items: center; gap: 10px; padding: 10px 14px; background: var(--jaced-white); border-radius: 8px; border: 1px solid var(--jaced-input); }
    .switch-row label { font-size: 13px; font-weight: 600; color: var(--jaced-brown-dark); margin: 0; cursor: pointer; }
    .switch-row small { font-size: 11px; color: var(--jaced-muted); }

    /* Image upload */
    .image-upload-area {
        border: 2px dashed var(--jaced-input); border-radius: 10px;
        padding: 20px; text-align: center; background: var(--jaced-white);
        cursor: pointer; transition: border-color 0.2s, background 0.2s;
        position: relative;
    }
    .image-upload-area:hover { border-color: var(--jaced-caramel); background: #fdf8f3; }
    .image-upload-area input[type="file"] {
        position: absolute; inset: 0; opacity: 0;
        cursor: pointer; width: 100%; height: 100%;
    }
    .image-preview-wrap { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
    .image-preview-item {
        position: relative; width: 72px; height: 72px;
        border-radius: 8px; overflow: hidden;
        border: 1px solid var(--jaced-input); flex-shrink: 0;
    }
    .image-preview-item img { width: 100%; height: 100%; object-fit: cover; }
    .image-preview-item .remove-img {
        position: absolute; top: 2px; right: 2px;
        width: 18px; height: 18px; border-radius: 50%;
        background: rgba(0,0,0,0.65); color: white;
        border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; line-height: 1;
    }

    /* Category chips */
    .cat-chip {
        display: inline-flex; align-items: center; gap: 6px;
        background: #f0eeeb; border-radius: 20px;
        padding: 5px 12px; font-size: 12px; font-weight: 600; color: var(--jaced-brown-dark);
    }
    .cat-chip .remove-cat {
        background: none; border: none; cursor: pointer;
        color: var(--jaced-muted); font-size: 15px; padding: 0; line-height: 1;
    }
    .cat-chip .remove-cat:hover { color: #c0392b; }

    .pagination {
        margin: 0 !important;
        gap: 4px !important;
    }
    
    .pagination .page-item .page-link {
        color: var(--jaced-muted) !important;
        border: 1px solid var(--jaced-input) !important;
        background-color: white !important;
        padding: 8px 16px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        border-radius: 8px !important;
        box-shadow: none !important;
        transition: all 0.2s ease !important;
    }

    .pagination .page-item .page-link:hover {
        background-color: var(--jaced-white) !important;
        color: var(--jaced-brown-dark) !important;
        border-color: var(--jaced-sage) !important;
    }

    .pagination .page-item.active .page-link {
        background-color: var(--jaced-caramel) !important;
        border-color: var(--jaced-caramel) !important;
        color: white !important;
    }

    .pagination .page-item.disabled .page-link {
        opacity: 0.5 !important;
        background-color: var(--jaced-cream) !important;
        color: var(--jaced-muted) !important;
    }

    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        font-size: 0px !important;
    }
    .pagination .page-item:first-child .page-link::before {
        content: "<" !important;
        font-size: 14px !important;
        font-weight: bold !important;
    }
    .pagination .page-item:last-child .page-link::before {
        content: ">" !important;
        font-size: 14px !important;
        font-weight: bold !important;
    }
</style>
@endpush

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show small fw-medium py-2.5 mb-3 border-0 shadow-sm" role="alert" style="border-radius: 8px;">
        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close" style="padding: 0.9rem 1rem;"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show small fw-medium py-2.5 mb-3 border-0 shadow-sm" role="alert" style="border-radius: 8px; background-color: #fdecea; color: #c0392b;">
        <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close" style="padding: 0.9rem 1rem;"></button>
    </div>
@endif

<div class="container-fluid">
     
     {{-- 🌟 Header Atas Adaptif Mobile --}}
     <div class="d-flex flex-row justify-content-between align-items-center mb-4 gap-2">
        <div style="min-width: 0; flex: 1;">
            <h2 class="fw-bold mb-1" style="font-size: calc(1.3rem + 0.6vw);">Inventory Ledger</h2>
            <p class="text-jaced-muted small m-0 d-none d-sm-block">Manage your premium stock items, monitor material availability, and track upcoming shipments.</p>
        </div>
        <div class="flex-shrink-0">
            <button type="button"
                    class="btn btn-sm px-3 px-sm-4 py-1.5 py-sm-2 d-flex align-items-center gap-2"
                    style="background:#1e1c18; color:#f5f2ee; border:none; border-radius:8px; font-size:12px; font-weight:600; white-space: nowrap;"
                    data-bs-toggle="modal" data-bs-target="#addItemModal">
                <i class="bi bi-plus-lg"></i> 
                <span>Add Item</span>
            </button>
        </div>
     </div>

    {{-- Filter Bar --}}
    <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center gap-3 mb-3 pb-2 border-bottom">
        
        {{-- SISI KIRI: Slider Kategori & Tombol Add Category --}}
        <div class="d-flex align-items-center justify-content-between justify-content-md-start gap-2" style="flex: 1; min-width: 0;">
            
            {{-- Wrapper dengan Isyarat Gradasi Geser --}}
            <div class="category-wrapper" style="max-width: calc(100% - 130px); overflow: hidden;">
                <style>
                    @media (min-width: 768px) {
                        .category-wrapper { max-width: 500px !important; }
                    }
                </style>
                
                <div class="d-flex gap-2 overflow-auto category-scroll flex-nowrap py-1" id="categoryFilterList" title="Swipe left/right to view more categories">
                    <button class="btn btn-sm rounded-pill px-3 px-md-4 py-1.5 py-md-2 fw-bold flex-shrink-0"
                            id="cat-all"
                            style="background: #c4a882; color: white; border-radius: 50px !important;"
                            onclick="filterByCategory(null, this)">All Collections</button>
                    @foreach($categories as $cat)
                        <button class="btn btn-sm btn-category-inactive text-capitalize px-3 px-md-4 py-1.5 py-md-2 flex-shrink-0 border-0"
                                data-cat-id="{{ $cat->id }}"
                                style="border-radius: 50px !important;"
                                onclick="filterByCategory({{ $cat->id }}, this)">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Tombol Add Category --}}
            <button class="btn btn-sm btn-add-category flex-shrink-0 border-0 bg-transparent ms-md-2"
                    style="font-size: 13px;"
                    data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="bi bi-plus-circle-fill me-1"></i> Add Category
            </button>
        </div>

        {{-- SISI KANAN: Opsi Pengurutan (SORT BY) & Toggle Layout View --}}
        <div class="d-flex align-items-center justify-content-between justify-content-md-end gap-3 ms-md-3">
            <div class="dropdown">
                <button class="btn btn-sm border-0 p-0 dropdown-toggle fw-bold d-flex align-items-center text-muted"
                        type="button" data-bs-toggle="dropdown" style="font-size: 0.85rem;">
                    <i class="bi bi-filter-left fs-5 me-1"></i>
                    SORT BY: <span class="ms-1 text-dark" id="sortLabel">NEWEST</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-2"
                    style="background:#fff; border-radius:12px; min-width:180px; z-index: 1050;">
                    <li><a class="dropdown-item rounded-2 small py-2 fw-medium active" href="#" onclick="sortBy('newest','NEWEST',this)">Newest</a></li>
                    <li><a class="dropdown-item rounded-2 small py-2 fw-medium" href="#" onclick="sortBy('oldest','OLDEST',this)">Oldest</a></li>
                    <li><a class="dropdown-item rounded-2 small py-2 fw-medium" href="#" onclick="sortBy('price_high','PRICE ↓',this)">Price: High to Low</a></li>
                    <li><a class="dropdown-item rounded-2 small py-2 fw-medium" href="#" onclick="sortBy('price_low','PRICE ↑',this)">Price: Low to High</a></li>
                    <li><a class="dropdown-item rounded-2 small py-2 fw-medium" href="#" onclick="sortBy('stock_low','STOCK ↑',this)">Stock: Low to High</a></li>
                    <li><hr class="dropdown-divider my-1" style="border-color: #f0eeeb;"></li>
                    <li><a class="dropdown-item rounded-2 small py-2 fw-medium text-danger" href="#" onclick="sortBy('inactive','INACTIVE 🚫',this)">Inactive Items</a></li>
                </ul>
            </div>

            {{-- Penampung Sakelar Grid/List Tombol --}}
            <div id="togglePlaceholder" class="flex-shrink-0"></div>
        </div>

    </div>

    {{-- Inventory Grid --}}
    @include('pages.inventory.item-grid')

    <div class="d-flex flex-column align-items-left justify-content-center mt-5">
        <div class="jaced-pagination-wrap">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@push('modals')
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
 
            <div class="modal-header border-0 pt-4 px-4 pb-0">
                <h5 class="modal-title fw-bold" id="addItemModalLabel">Add New Product</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
 
            <form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4 pb-2" style="max-height: 70vh; overflow-y: auto;">
 
                    {{-- 1. BASIC INFORMATION --}}
                    <div class="modal-section-title">Basic Information</div>
 
                    <div class="mb-3">
                        <label class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name"
                               placeholder="e.g., Sculptural Lounge Chair" required>
                    </div>
 
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"
                                  placeholder="Describe the product, materials, and craftsmanship..."
                                  style="resize: none;"></textarea>
                    </div>
 
                    {{-- 2. DIMENSIONS --}}
                    <div class="modal-section-title">Dimensions</div>
 
                    <div class="row g-3 mb-3">
                        <div class="col-4">
                            <label class="form-label">Length <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">L</span>
                                <input type="number" class="form-control" name="length"
                                       placeholder="0" min="0" step="0.1" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <label class="form-label">Width <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">W</span>
                                <input type="number" class="form-control" name="width"
                                       placeholder="0" min="0" step="0.1" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <label class="form-label">Height <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">H</span>
                                <input type="number" class="form-control" name="height"
                                       placeholder="0" min="0" step="0.1" required>
                            </div>
                        </div>
                    </div>
 
                    <div class="mb-3">
                        <label class="form-label">Unit (Satuan) <span class="text-danger">*</span></label>
                        <select class="form-select" name="unit" required>
                            <option value="cm" selected>cm — Centimeter</option>
                            <option value="m">m — Meter</option>
                            <option value="inch">inch — Inch</option>
                        </select>
                    </div>
 
                    {{-- 3. PRICING & STOCK --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                    
                                <input type="text"
                                    class="form-control"
                                    id="priceDisplay"
                                    placeholder="0"
                                    inputmode="numeric"
                                    autocomplete="off"
                                    oninput="formatRupiah(this, 'priceRaw')"
                                    required>
                                <input type="hidden" name="price" id="priceRaw">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Stock <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-box-seam" style="font-size:12px;"></i>
                                </span>
                                <input type="number" class="form-control" name="stock"
                                       placeholder="0" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">
                                Low Stock Alert
                                <span class="text-muted fw-normal">(threshold)</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-exclamation-triangle" style="font-size:12px;"></i>
                                </span>
                                <input type="number" class="form-control" name="low_stock"
                                       placeholder="5" min="0" value="5">
                            </div>
                            <div class="form-text" style="font-size:11px;">
                                Warn when stock drops below this number
                            </div>
                        </div>
                    </div>
 
                    {{-- 4. CATEGORY & LABEL --}}
                    <div class="modal-section-title">Category & Label</div>
 
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select text-capitalize" name="category_id" id="categorySelect" required>
                                <option value="" selected disabled>Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                Label
                                <span class="text-muted fw-normal">(tag produk)</span>
                            </label>
                            <input type="text" class="form-control" name="label"
                                   placeholder="e.g., Bestseller, New Arrival, Featured">
                        </div>
                    </div>
 
                    {{-- 5. PRODUCT IMAGES --}}
                    <div class="modal-section-title">Product Images <span class="text-danger">*</span></div>

                    <div class="image-upload-area">
                        <input type="file" name="images[]" id="imageInput"
                               accept="image/*" multiple onchange="previewImages(this)" required>
                        <i class="bi bi-cloud-upload fs-3 text-muted d-block mb-2"></i>
                        <div style="font-size:13px; font-weight:600; color:#3a3a36;">
                            Click or drag & drop images here
                        </div>
                        <div style="font-size:11px; color:#9c9890; margin-top:4px;">
                            JPG, PNG, WEBP — max 2MB each — multiple images allowed
                        </div>
                        <div style="font-size:11px; color:#c4a882; font-weight:600; margin-top:4px;">
                            First image will be set as main image
                        </div>
                    </div>
                    <div class="image-preview-wrap" id="imagePreviewWrap"></div>
 
                </div>
 
                <div class="modal-footer border-0 pb-4 px-4 pt-3 d-flex gap-2">
                    <button type="button"
                            class="btn btn-sm flex-grow-1 py-2 rounded-3"
                            data-bs-dismiss="modal"
                            style="background:#f0eeeb; color:#1a1a18; border:none;">
                        Cancel
                    </button>
                    <button type="submit"
                            class="btn btn-sm flex-grow-1 py-2 rounded-3 fw-bold"
                            style="background:#c4a882; color:white; border:none;">
                        <i class="bi bi-check-lg me-1"></i> Save Product
                    </button>
                </div>
            </form>
 
        </div>
    </div>
</div>

<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
            <div class="modal-header border-0 pt-4 px-4 pb-2">
                <h5 class="modal-title fw-bold">Manage Categories</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-2">
                <label class="form-label">New Category Name</label>
                <div class="input-group mb-1">
                    <input type="text" class="form-control" id="newCategoryInput" placeholder="e.g., Outdoor, Bedroom..." maxlength="255" style="border-radius: 8px 0 0 8px !important;" onkeydown="if(event.key==='Enter'){event.preventDefault(); saveCategory();}">
                    <button type="button" class="btn px-3 fw-bold" style="background:#1e1c18; color:#f5f2ee; border:none; border-radius:0 8px 8px 0;" onclick="saveCategory()"><i class="bi bi-plus-lg"></i> Add</button>
                </div>
                <div id="catErrorMsg" class="mb-3" style="font-size:11px; min-height:16px;"></div>
                <div style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:#9c9890; margin-bottom:10px;">Current Categories</div>
                <div class="d-flex flex-wrap gap-2 pb-2" id="categoryChips">
                    @foreach($categories as $cat)
                        <span class="cat-chip text-capitalize" data-cat-id="{{ $cat->id }}">{{ $cat->name }}<button class="remove-cat" onclick="deleteCategory({{ $cat->id }}, this)" title="Remove">×</button></span>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer border-0 pb-4 px-4 pt-2">
                <button type="button" class="btn btn-sm w-100 py-2 rounded-3 fw-bold" data-bs-dismiss="modal" style="background:#1e1c18; color:#f5f2ee; border:none;">Done</button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleWrap  = document.getElementById('inv-toggle-wrap');
        const placeholder = document.getElementById('togglePlaceholder');
        if (toggleWrap && placeholder) placeholder.replaceWith(toggleWrap);

        const urlParams = new URLSearchParams(window.location.search);
        const currentSort = urlParams.get('sort');
        const currentCat  = urlParams.get('category_id');

        if (currentSort) {
            const activeItem = document.querySelector(`.dropdown-menu .dropdown-item[onclick*="'${currentSort}'"]`);
            if (activeItem) {
                document.querySelectorAll('.dropdown-menu .dropdown-item').forEach(i => i.classList.remove('active'));
                activeItem.classList.add('active');

                const onclickAttr = activeItem.getAttribute('onclick');
                const match = onclickAttr.match(/sortBy\('[^']+'\s*,\s*'([^']+)'/);
                if (match && match[1]) {
                    document.getElementById('sortLabel').textContent = match[1];
                }
            }
        }

        if (currentCat) {
            const activeCatBtn = document.querySelector(`#categoryFilterList button[data-cat-id="${currentCat}"]`);
            if (activeCatBtn) {
                document.querySelectorAll('#categoryFilterList .btn').forEach(b => {
                    b.classList.add('btn-category-inactive');
                    b.classList.remove('fw-bold');
                    b.style.background = '#ffffff';
                    b.style.color = 'var(--jaced-brown)';
                    b.style.borderRadius = '50px';
                });
                activeCatBtn.classList.remove('btn-category-inactive');
                activeCatBtn.classList.add('fw-bold');
                activeCatBtn.style.background = '#c4a882';
                activeCatBtn.style.color = 'white';
                activeCatBtn.style.borderRadius = '50px';
            }
        } else {
            const catAll = document.getElementById('cat-all');
            if (catAll) {
                document.querySelectorAll('#categoryFilterList .btn').forEach(b => {
                    b.classList.add('btn-category-inactive');
                    b.classList.remove('fw-bold');
                    b.style.background = '#ffffff';
                    b.style.color = 'var(--jaced-brown)';
                    b.style.borderRadius = '50px';
                });
                catAll.classList.remove('btn-category-inactive');
                catAll.classList.add('fw-bold');
                catAll.style.background = '#c4a882';
                catAll.style.color = 'white';
                catAll.style.borderRadius = '50px';
            }
        }
    });

    function filterByCategory(catId, btn) {
        document.querySelectorAll('#categoryFilterList .btn').forEach(b => {
            b.classList.add('btn-category-inactive');
            b.classList.remove('fw-bold');
            b.style.background = '#ffffff';
            b.style.color = 'var(--jaced-brown)';
            b.style.borderRadius = '50px'; 
        });
        btn.classList.remove('btn-category-inactive');
        btn.classList.add('fw-bold');
        btn.style.background = '#c4a882'; 
        btn.style.color = 'white';
        btn.style.borderRadius = '50px'; 

        const url = new URL(window.location.href);
        catId ? url.searchParams.set('category_id', catId) : url.searchParams.delete('category_id');
        window.location.href = url.toString();
    }

    function sortBy(value, label, el) {
        document.querySelectorAll('.dropdown-menu .dropdown-item').forEach(i => i.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('sortLabel').textContent = label;
        const url = new URL(window.location.href);
        url.searchParams.set('sort', value);
        window.location.href = url.toString();
    }

    function saveCategory() {
        const input  = document.getElementById('newCategoryInput');
        const errMsg = document.getElementById('catErrorMsg');
        const val    = input.value.trim();
        errMsg.textContent = '';
        if (!val) return;
        if (!/^[a-zA-Z\s\-]+$/.test(val)) {
            errMsg.style.color = '#c0392b';
            errMsg.textContent = '⚠ Only letters, spaces, and hyphens are allowed.';
            return;
        }

        fetch('{{ route('categories.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ name: val }),
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                errMsg.style.color = '#c0392b';
                errMsg.textContent = '⚠ ' + (data.message ?? 'Error.');
                return;
            }
            const cat   = data.category;
            const chips = document.getElementById('categoryChips');
            const span  = document.createElement('span');
            
            span.className     = 'cat-chip text-capitalize';
            span.dataset.catId = cat.id;
            span.innerHTML     = `${cat.name} <button class="remove-cat" onclick="deleteCategory(${cat.id}, this)">×</button>`;
            chips.appendChild(span);

            const filterList = document.getElementById('categoryFilterList');
            const btn        = document.createElement('button');
            
            btn.className     = 'btn btn-sm btn-category-inactive text-capitalize px-4 py-2 flex-shrink-0 border-0';
            btn.style.borderRadius = '50px';
            btn.dataset.catId = cat.id;
            btn.textContent   = cat.name;
            btn.onclick       = function () { filterByCategory(cat.id, this); };
            filterList.appendChild(btn);

            const sel = document.getElementById('categorySelect');
            const opt = new Option(cat.name, cat.id);
            opt.className = 'text-capitalize';
            sel.appendChild(opt);
            
            input.value = '';
            input.focus();
        })
        .catch(() => {
            errMsg.style.color = '#c0392b';
            errMsg.textContent = '⚠ Something went wrong. Please try again.';
        });
    }

    function deleteCategory(id, btn) {
        if (!confirm('Are you sure you want to delete this category?')) return;
        fetch(`/admin/categories/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok || !data.success) {
                alert('⚠ ' + (data.message || 'Failed to delete category.'));
                return;
            }
            btn.closest('.cat-chip').remove();
            document.querySelectorAll(`#categoryFilterList [data-cat-id="${id}"]`).forEach(el => el.remove());
            document.querySelectorAll(`#categorySelect option[value="${id}"]`).forEach(el => el.remove());
        })
        .catch(error => {
            console.error('Error:', error);
            alert('⚠ System error. Could not complete the request.');
        });
    }

    function formatRupiah(input, rawId) {
        const cursorPos  = input.selectionStart;
        const prevLength = input.value.length;

        const raw       = input.value.replace(/\D/g, '');
        const formatted = raw.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        input.value = formatted;

        const diff      = input.value.length - prevLength;
        const newCursor = Math.max(0, cursorPos + diff);
        input.setSelectionRange(newCursor, newCursor);

        const hiddenEl = document.getElementById(rawId);
        if (hiddenEl) hiddenEl.value = raw;
    }
</script>
@endpush