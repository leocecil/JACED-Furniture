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
    /* Filter bar */
    .category-wrapper { mask-image: linear-gradient(to right, black 85%, transparent 100%); -webkit-mask-image: linear-gradient(to right, black 85%, transparent 100%); }
    .category-scroll { scroll-behavior: smooth; -ms-overflow-style: none; scrollbar-width: none; }
    .category-scroll::-webkit-scrollbar { display: none; }
    .btn-category-inactive { color: #6b6860 !important; opacity: 0.7; transition: all 0.2s ease; background: transparent; }
    .btn-category-inactive:hover { opacity: 1; background-color: rgba(0,0,0,0.05) !important; border-radius: 50px; }
    .btn-add-category { color: #6b8f71 !important; font-weight: 600 !important; }
    .dropdown-item { transition: all 0.2s; }
    .dropdown-item:hover { background-color: #f0eeeb !important; }
    .dropdown-item.active { background-color: #c4a882 !important; color: white !important; }
    .dropdown-toggle::after { display: none !important; }
    .btn-close:focus { box-shadow: none !important; }

    /* Modal */
    .modal-section-title {
        font-size: 11px; font-weight: 700; letter-spacing: 0.1em;
        text-transform: uppercase; color: #9c9890;
        margin: 20px 0 12px; padding-bottom: 6px;
        border-bottom: 1px solid #f0eeeb;
    }
    .modal-section-title:first-child { margin-top: 0; }

    .form-label { font-size: 12px; font-weight: 600; color: #3a3a36; margin-bottom: 5px; }
    .form-control, .form-select {
        border: 1px solid #e2ddd8; border-radius: 8px;
        font-size: 13px; background: #faf9f7; box-shadow: none !important;
    }
    .form-control:focus, .form-select:focus { border-color: #c4a882; background: #fff; }
    .input-group .form-control { border-radius: 0 8px 8px 0 !important; }
    .input-group-text {
        background: #f0eeeb; border: 1px solid #e2ddd8;
        border-right: none; border-radius: 8px 0 0 8px !important;
        font-size: 12px; font-weight: 600; color: #6b6860;
    }

    /* Switch */
    .form-check-input:checked { background-color: #c4a882 !important; border-color: #c4a882 !important; }
    .switch-row { display: flex; align-items: center; gap: 10px; padding: 10px 14px; background: #faf9f7; border-radius: 8px; border: 1px solid #e2ddd8; }
    .switch-row label { font-size: 13px; font-weight: 600; color: #3a3a36; margin: 0; cursor: pointer; }
    .switch-row small { font-size: 11px; color: #9c9890; }

    /* Image upload */
    .image-upload-area {
        border: 2px dashed #e2ddd8; border-radius: 10px;
        padding: 20px; text-align: center; background: #faf9f7;
        cursor: pointer; transition: border-color 0.2s, background 0.2s;
        position: relative;
    }
    .image-upload-area:hover { border-color: #c4a882; background: #fdf8f3; }
    .image-upload-area input[type="file"] {
        position: absolute; inset: 0; opacity: 0;
        cursor: pointer; width: 100%; height: 100%;
    }
    .image-preview-wrap { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
    .image-preview-item {
        position: relative; width: 72px; height: 72px;
        border-radius: 8px; overflow: hidden;
        border: 1px solid #e2ddd8; flex-shrink: 0;
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
        padding: 5px 12px; font-size: 12px; font-weight: 600; color: #3a3a36;
    }
    .cat-chip .remove-cat {
        background: none; border: none; cursor: pointer;
        color: #9c9890; font-size: 15px; padding: 0; line-height: 1;
    }
    .cat-chip .remove-cat:hover { color: #c0392b; }

    /* Customisasi Tampilan Tombol Pagination Jaced Premium (Override Bootstrap) */
    .pagination {
        margin: 0 !important;
        gap: 4px;
    }
    .pagination .page-item .page-link {
        color: #6b6860 !important;
        border: 1px solid #e2ddd8 !important;
        background-color: #fff !important;
        padding: 8px 16px !important;
        font-size: 13px;
        font-weight: 600;
        border-radius: 8px !important;
        box-shadow: none !important;
        transition: all 0.2s ease;
    }
    .pagination .page-item.active .page-link {
        background-color: #c4a882 !important;
        border-color: #c4a882 !important;
        color: #fff !important;
    }
    .pagination .page-item.disabled .page-link {
        opacity: 0.5;
        background-color: #faf9f7 !important;
    }

    /* Mengubah Teks & Simbol Menjadi < dan > secara bersih */
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        font-size: 0px !important;
    }
    .pagination .page-item:first-child .page-link::before {
        content: "<";
        font-size: 14px;
        font-weight: bold;
    }
    .pagination .page-item:last-child .page-link::before {
        content: ">";
        font-size: 14px;
        font-weight: bold;
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
     <div class="d-flex justify-content-between align-items-start mb-4">
       <div>
            <h2 class="fw-bold mb-1">Inventory Ledger</h2>
            <p class="text-jaced-muted small">Manage your premium stock items, monitor material availability, and track upcoming shipments.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-jaced-primary px-4 py-2" data-bs-toggle="modal" data-bs-target="#addItemModal">
            <i class="bi bi-plus-lg me-2"></i> Add New Item
            </button>
        </div>
     </div>

    {{-- Filter Bar --}}
    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
        <div class="d-flex align-items-center gap-2" style="flex: 1; min-width: 0;">
            <div class="category-wrapper" style="max-width: 500px; overflow: hidden;">
                <div class="d-flex gap-2 overflow-auto category-scroll flex-nowrap py-1" id="categoryFilterList">
                    <button class="btn btn-sm rounded-pill px-4 py-2 fw-bold flex-shrink-0"
                            id="cat-all"
                            style="background: #c4a882; color: white;"
                            onclick="filterByCategory(null, this)">All Collections</button>
                    @foreach($categories as $cat)
                        <button class="btn btn-sm btn-category-inactive px-4 py-2 flex-shrink-0 border-0"
                                data-cat-id="{{ $cat->id }}"
                                onclick="filterByCategory({{ $cat->id }}, this)">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            </div>
            <button class="btn btn-sm btn-add-category flex-shrink-0 border-0 bg-transparent ms-2"
                    data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="bi bi-plus-circle-fill me-1"></i> Add Category
            </button>
        </div>

        <div class="d-flex align-items-center gap-3 ms-3">
            <div class="dropdown">
                <button class="btn btn-sm border-0 p-0 dropdown-toggle fw-bold d-flex align-items-center text-muted"
                        type="button" data-bs-toggle="dropdown" style="font-size: 0.85rem;">
                    <i class="bi bi-filter-left fs-5 me-1"></i>
                    SORT BY: <span class="ms-1 text-dark" id="sortLabel">NEWEST</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-2"
                    style="background:#fff; border-radius:12px; min-width:160px;">
                    <li><a class="dropdown-item rounded-2 small py-2 fw-medium active" href="#" onclick="sortBy('newest','NEWEST',this)">Newest</a></li>
                    <li><a class="dropdown-item rounded-2 small py-2 fw-medium" href="#" onclick="sortBy('oldest','OLDEST',this)">Oldest</a></li>
                    <li><a class="dropdown-item rounded-2 small py-2 fw-medium" href="#" onclick="sortBy('price_high','PRICE ↓',this)">Price: High to Low</a></li>
                    <li><a class="dropdown-item rounded-2 small py-2 fw-medium" href="#" onclick="sortBy('price_low','PRICE ↑',this)">Price: Low to High</a></li>
                    <li><a class="dropdown-item rounded-2 small py-2 fw-medium" href="#" onclick="sortBy('stock_low','STOCK ↑',this)">Stock: Low to High</a></li>
                </ul>
            </div>
            <div id="togglePlaceholder"></div>
        </div>
    </div>

    {{-- Inventory Grid --}}
    @include('pages.inventory.item-grid')

    {{-- PERBAIKAN: Kontainer navigasi halaman yang bersih sejajar tengah --}}
    <div class="d-flex flex-column align-items-left justify-content-center mt-5">
        <div class="jaced-pagination-wrap">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleWrap  = document.getElementById('inv-toggle-wrap');
        const placeholder = document.getElementById('togglePlaceholder');
        if (toggleWrap && placeholder) placeholder.replaceWith(toggleWrap);

        // ── AUTO SINKRONISASI FILTER WARNA CARAMEL BERDASARKAN URL SAAT PAGE LOAD ──
        const urlParams = new URLSearchParams(window.location.search);
        const currentSort = urlParams.get('sort');
        const currentCat  = urlParams.get('category_id');

        // 1. Sinkronisasi Dropdown Sort
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

        // 2. Sinkronisasi Tombol Kategori Aktif (Memindahkan warna orange #c4a882)
        if (currentCat) {
            const activeCatBtn = document.querySelector(`#categoryFilterList button[data-cat-id="${currentCat}"]`);
            if (activeCatBtn) {
                document.querySelectorAll('#categoryFilterList .btn').forEach(b => {
                    b.classList.add('btn-category-inactive');
                    b.classList.remove('fw-bold');
                    b.style.background = 'transparent';
                    b.style.color = '#6b6860';
                });
                activeCatBtn.classList.remove('btn-category-inactive');
                activeCatBtn.classList.add('fw-bold');
                activeCatBtn.style.background = '#c4a882';
                activeCatBtn.style.color = 'white';
            }
        } else {
            const catAll = document.getElementById('cat-all');
            if (catAll) {
                document.querySelectorAll('#categoryFilterList .btn').forEach(b => {
                    b.classList.add('btn-category-inactive');
                    b.classList.remove('fw-bold');
                    b.style.background = 'transparent';
                    b.style.color = '#6b6860';
                });
                catAll.classList.remove('btn-category-inactive');
                catAll.classList.add('fw-bold');
                catAll.style.background = '#c4a882';
                catAll.style.color = 'white';
            }
        }
    });

    function filterByCategory(catId, btn) {
        document.querySelectorAll('#categoryFilterList .btn').forEach(b => {
            b.classList.add('btn-category-inactive');
            b.classList.remove('fw-bold');
            b.style.background = 'transparent';
            b.style.color = '#6b6860';
        });
        btn.classList.remove('btn-category-inactive');
        btn.classList.add('fw-bold');
        btn.style.background = '#c4a882';
        btn.style.color = 'white';

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
</script>
@endpush

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
                    <div class="modal-section-title">Pricing & Stock</div>
 
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" name="price"
                                       placeholder="0" min="0" required>
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
                            <select class="form-select" name="category_id" id="categorySelect" required>
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
                    <div class="modal-section-title">Product Images</div>
 
                    <div class="image-upload-area">
                        <input type="file" name="images[]" id="imageInput"
                               accept="image/*" multiple onchange="previewImages(this)">
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
 
                </div>{{-- end modal-body --}}
 
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
                        <span class="cat-chip" data-cat-id="{{ $cat->id }}">{{ $cat->name }}<button class="remove-cat" onclick="deleteCategory({{ $cat->id }}, this)" title="Remove">×</button></span>
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
function autoSlug(val) {
    document.getElementById('slugInput').value = val.toLowerCase().replace(/[^a-z0-9\s-]/g, '').trim().replace(/\s+/g, '-');
}

function previewImages(input) {
    const wrap = document.getElementById('imagePreviewWrap');
    Array.from(input.files).forEach(file => {
        if (!file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'image-preview-item';
            div.innerHTML = `<img src="${e.target.result}" alt=""><button type="button" class="remove-img" onclick="this.closest('.image-preview-item').remove()">×</button>`;
            wrap.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

function saveCategory() {
    const input  = document.getElementById('newCategoryInput');
    const errMsg = document.getElementById('catErrorMsg');
    const val    = input.value.trim();
    errMsg.textContent = '';
    if (!val) return;
    if (!/^[a-zA-Z\s\-]+$/.test(val)) { errMsg.style.color = '#c0392b'; errMsg.textContent = '⚠ Only letters, spaces, and hyphens are allowed.'; return; }

    fetch('{{ route('categories.store') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ name: val }),
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) { errMsg.style.color = '#c0392b'; errMsg.textContent = '⚠ ' + (data.message ?? 'Error.'); return; }
        const cat = data.category;
        const chips = document.getElementById('categoryChips');
        const span  = document.createElement('span');
        span.className = 'cat-chip'; span.dataset.catId = cat.id;
        span.innerHTML = `${cat.name} <button class="remove-cat" onclick="deleteCategory(${cat.id}, this)">×</button>`;
        chips.appendChild(span);

        const filterList = document.getElementById('categoryFilterList');
        const btn = document.createElement('button');
        btn.className = 'btn btn-sm btn-category-inactive px-4 py-2 flex-shrink-0 border-0';
        btn.dataset.catId = cat.id; btn.textContent = cat.name;
        btn.onclick = function () { filterByCategory(cat.id, this); };
        filterList.appendChild(btn);

        const sel = document.getElementById('categorySelect');
        sel.appendChild(new Option(cat.name, cat.id));
        input.value = ''; input.focus();
    })
    .catch(() => { errMsg.style.color = '#c0392b'; errMsg.textContent = '⚠ Something went wrong. Please try again.'; });
}

function deleteCategory(id, btn) {
    if (!confirm('Delete this category?')) return;
    fetch(`/categories/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) { alert(data.message); return; }
        btn.closest('.cat-chip').remove();
        document.querySelectorAll(`#categoryFilterList [data-cat-id="${id}"]`).forEach(el => el.remove());
        document.querySelectorAll(`#categorySelect option[value="${id}"]`).forEach(el => el.remove());
    });
}
</script>
@endpush