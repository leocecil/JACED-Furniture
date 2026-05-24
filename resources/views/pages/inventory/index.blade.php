@extends('layouts.app')

@section('title', 'Inventory')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Studio</a></li>
    <li class="breadcrumb-item active">Inventory</li>
@endsection

{{-- 1. MENGIRIM DATA JUDUL AGAR HEADER DI LAYOUT UTAMA MUNCUL --}}
@section('page-title', 'Inventory Ledger')

{{-- 2. MENGIRIM ACTION BUTTON AGAR SEJAJAR DENGAN JUDUL UTAMA --}}
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
    .category-wrapper { mask-image: linear-gradient(to right, black 85%, transparent 100%); -webkit-mask-image: linear-gradient(to right, black 85%, transparent 100%); }
    .category-scroll { scroll-behavior: smooth; -ms-overflow-style: none; scrollbar-width: none; }
    .category-scroll::-webkit-scrollbar { display: none; }
    .btn-category-inactive { color: #6b6860 !important; opacity: 0.7; transition: all 0.2s ease; }
    .btn-category-inactive:hover { opacity: 1; background-color: rgba(0,0,0,0.05); border-radius: 50px; }
    .btn-add-category { color: #6b8f71 !important; font-weight: 600 !important; }
    .dropdown-item { transition: all 0.2s; }
    .dropdown-item:hover { background-color: #f0eeeb !important; }
    .dropdown-item.active { background-color: #c4a882 !important; color: white !important; }
    .dropdown-toggle::after { display: none !important; }
    .btn-close:focus { box-shadow: none !important; }

    /* Modal */
    .modal-section-title { font-size: 11px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #9c9890; margin-bottom: 12px; padding-bottom: 6px; border-bottom: 1px solid #f0eeeb; }
    .form-label { font-size: 12px; font-weight: 600; color: #3a3a36; margin-bottom: 5px; }
    .form-control, .form-select { border: 1px solid #e2ddd8; border-radius: 8px; font-size: 13px; background: #faf9f7; box-shadow: none !important; }
    .form-control:focus, .form-select:focus { border-color: #c4a882; background: #fff; }
    .input-group .form-control { border-radius: 0 8px 8px 0 !important; }
    .input-group-text { background: #f0eeeb; border: 1px solid #e2ddd8; border-right: none; border-radius: 8px 0 0 8px !important; font-size: 12px; font-weight: 600; color: #6b6860; }

    /* Image upload */
    .image-upload-area {
        border: 2px dashed #e2ddd8; border-radius: 10px; padding: 24px;
        text-align: center; background: #faf9f7; cursor: pointer;
        transition: border-color 0.2s; position: relative;
    }
    .image-upload-area:hover { border-color: #c4a882; background: #fdf8f3; }
    .image-upload-area input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
    .image-preview-wrap { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; }
    .image-preview-item {
        position: relative; width: 80px; height: 80px;
        border-radius: 8px; overflow: hidden;
        border: 2px solid #e2ddd8; flex-shrink: 0; cursor: pointer;
        transition: border-color 0.15s;
    }
    .image-preview-item img { width: 100%; height: 100%; object-fit: cover; }
    .image-preview-item .remove-img { position: absolute; top: 2px; right: 2px; width: 18px; height: 18px; border-radius: 50%; background: rgba(0,0,0,0.6); color: white; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 11px; z-index: 2; }
    .image-preview-item.is-main { border-color: #c4a882; }
    .image-preview-item .main-badge { position: absolute; bottom: 0; left: 0; right: 0; background: #c4a882; color: white; font-size: 9px; font-weight: 700; text-align: center; padding: 2px; text-transform: uppercase; letter-spacing: .05em; }

    /* Category chips */
    .cat-chip { display: inline-flex; align-items: center; gap: 6px; background: #f0eeeb; border-radius: 20px; padding: 5px 12px; font-size: 12px; font-weight: 600; color: #3a3a36; }
    .cat-chip .remove-cat { background: none; border: none; cursor: pointer; color: #9c9890; font-size: 14px; padding: 0; line-height: 1; display: flex; align-items: center; }
    .cat-chip .remove-cat:hover { color: #c0392b; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
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
                                onclick="filterByCategory({{ $cat->id }}, this)">{{ $cat->name }}</button>
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
                    style="background-color: #fff; border-radius: 12px; min-width: 160px;">
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

    {{-- Inventory items --}}
    @include('pages.inventory.item-grid')

    {{-- Pagination --}}
    <div class="text-center mt-5">
        <p class="text-muted small mb-3">
            Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} products
        </p>
        {{ $products->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection


{{-- ═══════ MODAL: ADD PRODUCT ═══════ --}}
@push('modals')
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">

            <div class="modal-header border-0 pt-4 px-4 pb-2">
                <h5 class="modal-title fw-bold" id="addItemModalLabel">Add New Product</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <form action="#" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4 pb-2" style="max-height: 68vh; overflow-y: auto;">

                    {{-- Basic Info --}}
                    <div class="modal-section-title">Basic Information</div>
                    <div class="mb-3">
                        <label class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name"
                               placeholder="e.g., Sculptural Lounge Chair" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Short Description</label>
                        <input type="text" class="form-control" name="short_description"
                               placeholder="Brief summary shown on product card" maxlength="500">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Full Description</label>
                        <textarea class="form-control" name="description" rows="3"
                                  placeholder="Detailed product description..." style="resize:none;"></textarea>
                    </div>

                    {{-- Pricing & Stock --}}
                    <div class="modal-section-title mt-4">Pricing & Stock</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" name="price"
                                       placeholder="0.00" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Old Price <span class="text-muted fw-normal">(optional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" name="old_price"
                                       placeholder="0.00" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Stock <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-box-seam" style="font-size:12px;"></i></span>
                                <input type="number" class="form-control" name="stock"
                                       placeholder="0" min="0" required>
                            </div>
                        </div>
                    </div>

                    {{-- Category & Badge --}}
                    <div class="modal-section-title mt-4">Category & Badge</div>
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
                            <label class="form-label">Badge <span class="text-muted fw-normal">(e.g. New, Sale)</span></label>
                            <input type="text" class="form-control" name="badge"
                                   placeholder="e.g., New Arrival, Best Seller">
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" checked>
                                <label class="form-check-label fw-semibold" for="isActive" style="font-size:13px;">
                                    Active <span class="text-muted fw-normal">(visible to customers)</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_recommended" id="isRecommended" value="1">
                                <label class="form-check-label fw-semibold" for="isRecommended" style="font-size:13px;">
                                    Recommended <span class="text-muted fw-normal">(featured)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Images --}}
                    <div class="modal-section-title mt-4">Product Images</div>
                    <p style="font-size:12px; color:#9c9890; margin-bottom:10px;">
                        Upload multiple images. <strong>Click a preview</strong> to set it as the main image.
                    </p>
                    <div class="image-upload-area">
                        <input type="file" name="images[]" id="imageInput"
                               accept="image/*" multiple onchange="previewImages(this)">
                        <i class="bi bi-cloud-upload fs-3 text-muted d-block mb-2"></i>
                        <div style="font-size:13px; font-weight:600; color:#3a3a36;">Click or drag & drop images here</div>
                        <div style="font-size:11px; color:#9c9890; margin-top:4px;">JPG, PNG, WEBP — max 2MB each</div>
                    </div>
                    <div class="image-preview-wrap" id="imagePreviewWrap"></div>
                    <input type="hidden" name="main_image_index" id="mainImageIndex" value="0">

                </div>

                <div class="modal-footer border-0 pb-4 px-4 pt-3 d-flex gap-2">
                    <button type="button" class="btn btn-sm flex-grow-1 py-2 rounded-3"
                            data-bs-dismiss="modal" style="background:#f0eeeb; color:#1a1a18; border:none;">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-sm flex-grow-1 py-2 rounded-3 fw-bold"
                            style="background:#c4a882; color:white; border:none;">
                        <i class="bi bi-check-lg me-1"></i> Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ═══════ MODAL: ADD CATEGORY ═══════ --}}
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
                    <input type="text" class="form-control" id="newCategoryInput"
                           placeholder="e.g., Bedroom, Accent..." maxlength="255"
                           style="border-radius: 8px 0 0 8px !important;"
                           onkeydown="if(event.key==='Enter'){event.preventDefault(); saveCategory();}">
                    <button type="button" class="btn px-3 fw-bold"
                            style="background:#1e1c18; color:#f5f2ee; border:none; border-radius:0 8px 8px 0;"
                            onclick="saveCategory()">
                        <i class="bi bi-plus-lg"></i> Add
                    </button>
                </div>
                <div class="form-text mb-3" style="font-size:11px; color:#c0392b;" id="catErrorMsg"></div>

                <div style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:#9c9890; margin-bottom:10px;">
                    Current Categories
                </div>
                <div class="d-flex flex-wrap gap-2 pb-2" id="categoryChips">
                    @foreach($categories as $cat)
                        <span class="cat-chip" data-cat-id="{{ $cat->id }}">
                            {{ $cat->name }}
                            <button class="remove-cat" onclick="deleteCategory({{ $cat->id }}, this)">×</button>
                        </span>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer border-0 pb-4 px-4 pt-2">
                <button type="button" class="btn btn-sm w-100 py-2 rounded-3 fw-bold"
                        data-bs-dismiss="modal" style="background:#1e1c18; color:#f5f2ee; border:none;">Done</button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Pindahkan tombol grid view jika placeholder ada
        const toggleWrap  = document.getElementById('inv-toggle-wrap');
        const placeholder = document.getElementById('togglePlaceholder');
        if (toggleWrap && placeholder) placeholder.replaceWith(toggleWrap);

        // ── AUTO SINKRONISASI STATE DATA BERDASARKAN URL SAAT PAGE RELOAD ──
        const urlParams = new URLSearchParams(window.location.search);
        const currentSort = urlParams.get('sort');
        const currentCat  = urlParams.get('category_id');

        // 1. Ambil state Dropdown Sort dari URL
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

        // 2. Ambil state Tombol Kategori Aktif dari URL
        if (currentCat) {
            const activeCatBtn = document.querySelector(`#categoryFilterList button[data-cat-id="${currentCat}"]`);
            if (activeCatBtn) {
                document.querySelectorAll('#categoryFilterList .btn').forEach(b => {
                    b.classList.add('btn-category-inactive');
                    b.classList.remove('fw-bold');
                    b.style.background = '';
                    b.style.color = '';
                });
                activeCatBtn.classList.remove('btn-category-inactive');
                activeCatBtn.classList.add('fw-bold');
                activeCatBtn.style.background = '#c4a882';
                activeCatBtn.style.color = 'white';
            }
        }
    });

    // ── REDIRECT REFRESH DENGAN QUERY STRING URL ──
    function filterByCategory(catId, btn) {
        const url = new URL(window.location.href);
        catId ? url.searchParams.set('category_id', catId) : url.searchParams.delete('category_id');
        window.location.href = url.toString();
    }

    function sortBy(value, label, el) {
        const url = new URL(window.location.href);
        url.searchParams.set('sort', value);
        window.location.href = url.toString();
    }

    /* ── IMAGE PREVIEW + SET MAIN ── */
    let previewCount = 0;

    function previewImages(input) {
        const wrap = document.getElementById('imagePreviewWrap');
        Array.from(input.files).forEach((file, idx) => {
            if (!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            const currentIdx = previewCount++;
            reader.onload = e => {
                const div = document.createElement('div');
                div.className = 'image-preview-item' + (currentIdx === 0 && wrap.children.length === 0 ? ' is-main' : '');
                div.dataset.index = currentIdx;
                div.innerHTML = `
                    <img src="${e.target.result}" alt="">
                    <button type="button" class="remove-img" onclick="removePreview(this)">×</button>
                    ${currentIdx === 0 && wrap.children.length === 0 ? '<div class="main-badge">Main</div>' : ''}
                `;
                div.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-img')) return;
                    setMainImage(div);
                });
                wrap.appendChild(div);
                if (wrap.children.length === 1) setMainImage(div);
            };
            reader.readAsDataURL(file);
        });
    }

    function setMainImage(div) {
        document.querySelectorAll('.image-preview-item').forEach(el => {
            el.classList.remove('is-main');
            const badge = el.querySelector('.main-badge');
            if (badge) badge.remove();
        });
        div.classList.add('is-main');
        div.insertAdjacentHTML('beforeend', '<div class="main-badge">Main</div>');
        document.getElementById('mainImageIndex').value = div.dataset.index;
    }

    function removePreview(btn) {
        const item = btn.closest('.image-preview-item');
        const wasMain = item.classList.contains('is-main');
        item.remove();
        if (wasMain) {
            const first = document.querySelector('.image-preview-item');
            if (first) setMainImage(first);
        }
    }

    /* ── ADD CATEGORY (AJAX) ── */
    function saveCategory() {
        const input  = document.getElementById('newCategoryInput');
        const errMsg = document.getElementById('catErrorMsg');
        const val    = input.value.trim();
        errMsg.textContent = '';

        if (!val) return;
        if (!/^[a-zA-Z\s\-]+$/.test(val)) {
            errMsg.textContent = '⚠ Only letters, spaces, and hyphens are allowed.';
            return;
        }

        fetch('#', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ name: val }),
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                errMsg.textContent = '⚠ ' + (data.errors?.name?.[0] ?? data.message ?? 'Error.');
                return;
            }
            const cat = data.category;

            const chips = document.getElementById('categoryChips');
            const span  = document.createElement('span');
            span.className = 'cat-chip';
            span.dataset.catId = cat.id;
            span.innerHTML = `${cat.name} <button class="remove-cat" onclick="deleteCategory(${cat.id}, this)">×</button>`;
            chips.appendChild(span);

            const filterList = document.getElementById('categoryFilterList');
            const btn = document.createElement('button');
            btn.className = 'btn btn-sm btn-category-inactive px-4 py-2 flex-shrink-0 border-0';
            btn.dataset.catId = cat.id;
            btn.textContent = cat.name;
            btn.onclick = function () { filterByCategory(cat.id, this); };
            filterList.appendChild(btn);

            const sel = document.getElementById('categorySelect');
            const opt = document.createElement('option');
            opt.value = cat.id;
            opt.textContent = cat.name;
            sel.appendChild(opt);

            input.value = '';
            input.focus();
        })
        .catch(() => { errMsg.textContent = '⚠ Something went wrong.'; });
    }

    /* ── DELETE CATEGORY (AJAX) ── */
    function deleteCategory(id, btn) {
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