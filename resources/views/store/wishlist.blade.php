@extends('base.base')

@section('title', 'Wishlist — JACED Furniture')

@section('content')

<div class="wishlist-page">
    <div class="container">

        {{-- ===== HEADER ===== --}}
        <div class="wishlist-header">
            <div class="wishlist-header-left">
                <h1 class="wishlist-title">Wishlist <span class="wishlist-count" id="wishlistCount">(0)</span></h1>
            </div>
            <div class="wishlist-header-right">
                <a href="{{ route('shop') }}" class="wishlist-browse-btn">
                    <i class="fas fa-plus me-2"></i> Add More
                </a>
                <button class="wishlist-clear-all-btn" id="clearAllBtn">
                    <i class="fas fa-trash-alt me-2"></i> Clear All
                </button>
            </div>
        </div>

        {{-- ===== TOOLBAR: SEARCH + SORT + FILTER ===== --}}
        <div class="wishlist-toolbar" id="wishlistToolbar" style="display: none;">
            <div class="wishlist-search-wrap">
                <i class="fas fa-search wishlist-search-icon"></i>
                <input type="text" id="wishlistSearch" class="wishlist-search-input"
                       placeholder="Search saved items...">
                <button class="wishlist-search-clear" id="searchClearBtn" style="display:none;">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="wishlist-sort-wrap">
                <div class="wishlist-sort-trigger" id="sortTrigger">
                    <span class="wishlist-sort-label">Sort</span>
                    <span class="wishlist-sort-value" id="sortLabel">Default</span>
                    <i class="fas fa-chevron-down wishlist-sort-chevron"></i>
                </div>
                <div class="wishlist-sort-menu" id="sortMenu">
                    <button class="wishlist-sort-option active" data-sort="default">Default</button>
                    <button class="wishlist-sort-option" data-sort="name_asc">Name A–Z</button>
                    <button class="wishlist-sort-option" data-sort="name_desc">Name Z–A</button>
                    <button class="wishlist-sort-option" data-sort="price_asc">Price: Low to High</button>
                    <button class="wishlist-sort-option" data-sort="price_desc">Price: High to Low</button>
                    <button class="wishlist-sort-option" data-sort="category">By Category</button>
                </div>
            </div>

            <div class="wishlist-filter-wrap">
                <div class="wishlist-sort-trigger" id="filterTrigger">
                    <i class="fas fa-sliders-h me-2"></i>
                    <span class="wishlist-sort-label">Category</span>
                    <span class="wishlist-sort-value" id="filterLabel">All</span>
                    <i class="fas fa-chevron-down wishlist-sort-chevron"></i>
                </div>
                <div class="wishlist-sort-menu" id="filterMenu">
                    <button class="wishlist-sort-option active" data-filter="all">All</button>
                </div>
            </div>
        </div>

        {{-- ===== ACTIVE FILTERS ===== --}}
        <div class="wishlist-active-filters" id="activeFilters" style="display: none;">
            <span class="wishlist-active-label">Filters:</span>
            <div id="activeFilterChips"></div>
        </div>

        {{-- ===== EMPTY STATE ===== --}}
        <div class="wishlist-empty" id="wishlistEmpty">
            <div class="wishlist-empty-icon">
                <i class="far fa-heart"></i>
            </div>
            <h3 class="wishlist-empty-title">Your wishlist is empty</h3>
            <p class="wishlist-empty-desc">Save pieces you love while browsing the collection.</p>
            <a href="{{ route('shop') }}" class="wishlist-empty-btn">
                Browse Collection <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>

        {{-- ===== NO RESULTS ===== --}}
        <div class="wishlist-no-results" id="wishlistNoResults" style="display: none;">
            <i class="fas fa-search"></i>
            <p>No items match your search.</p>
        </div>

        {{-- ===== PRODUCT GRID ===== --}}
        <div class="row g-3" id="wishlistGrid"></div>

        {{-- ===== LOAD MORE ===== --}}
        <div class="wishlist-load-more-wrap" id="loadMoreWrap" style="display: none;">
            <button class="wishlist-load-more-btn" id="loadMoreBtn">
                <i class="fas fa-plus"></i>
                Load More
                <span class="wishlist-load-more-count" id="loadMoreCount"></span>
            </button>
        </div>

    </div>
</div>

{{-- TOAST --}}
{{-- CONFIRM POPUP --}}
<div class="wl-confirm-backdrop" id="wlConfirmBackdrop">
    <div class="wl-confirm-modal">
        <div class="wl-confirm-icon"><i class="fas fa-heart-crack"></i></div>
        <h4 class="wl-confirm-title">Remove from Wishlist?</h4>
        <p class="wl-confirm-msg" id="wlConfirmMsg"></p>
        <div class="wl-confirm-actions">
            <button class="wl-confirm-cancel" id="wlConfirmCancel">Keep it</button>
            <button class="wl-confirm-ok" id="wlConfirmOk">Remove</button>
        </div>
    </div>
</div>

<div class="wish-toast" id="wishToast">
    <i class="fas fa-check-circle"></i>
    <span id="wishToastText"></span>
</div>

<style>
    body { background-color: var(--jaced-caramel-bg) !important; }

    .wishlist-page {
        padding: 120px 0 80px;
        min-height: 100vh;
    }
    .wishlist-page .container { max-width: 1280px; }

    .wishlist-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 28px;
        padding-bottom: 24px;
        border-bottom: 1px solid var(--jaced-input);
    }
    .wishlist-title {
        font-size: clamp(1.8rem, 3vw, 2.5rem);
        font-weight: 700;
        letter-spacing: -0.03em;
        color: var(--jaced-brown-dark);
        margin: 0;
    }
    .wishlist-count { color: var(--jaced-muted); font-weight: 400; }
    .wishlist-header-right { display: flex; gap: 12px; align-items: center; }

    .wishlist-browse-btn {
        display: inline-flex; align-items: center;
        background: var(--jaced-brown-dark); color: var(--jaced-cream);
        padding: 10px 20px; border-radius: 999px;
        font-size: 13px; font-weight: 600; text-decoration: none;
        transition: background 0.3s ease;
    }
    .wishlist-browse-btn:hover { background: var(--jaced-caramel); color: var(--jaced-cream); }

    .wishlist-clear-all-btn {
        display: inline-flex; align-items: center;
        background: transparent; border: 1px solid var(--jaced-input);
        color: var(--jaced-muted); padding: 10px 20px;
        border-radius: 999px; font-size: 13px; font-weight: 500;
        cursor: pointer; transition: all 0.3s ease;
    }
    .wishlist-clear-all-btn:hover { background: #9c3535; border-color: #9c3535; color: white; }

    .wishlist-toolbar {
        display: flex; gap: 12px; margin-bottom: 16px;
        flex-wrap: wrap; align-items: center;
    }
    .wishlist-search-wrap {
        flex: 1; min-width: 240px; position: relative;
        background: var(--jaced-card); border-radius: 999px;
        border: 1px solid var(--jaced-input); transition: border 0.3s ease;
    }
    .wishlist-search-wrap:focus-within { border-color: var(--jaced-brown-dark); }
    .wishlist-search-icon {
        position: absolute; left: 18px; top: 50%;
        transform: translateY(-50%); color: var(--jaced-muted); font-size: 13px;
    }
    .wishlist-search-input {
        background: transparent; border: none; width: 100%;
        padding: 12px 40px 12px 44px; font-size: 14px;
        color: var(--jaced-brown-dark); outline: none;
    }
    .wishlist-search-input::placeholder { color: var(--jaced-muted); }
    .wishlist-search-clear {
        position: absolute; right: 14px; top: 50%;
        transform: translateY(-50%); background: transparent;
        border: none; color: var(--jaced-muted); cursor: pointer; font-size: 12px;
    }
    .wishlist-search-clear:hover { color: var(--jaced-brown-dark); }

    .wishlist-sort-wrap, .wishlist-filter-wrap { position: relative; }
    .wishlist-sort-trigger {
        display: flex; align-items: center; gap: 8px;
        background: var(--jaced-card); border: 1px solid var(--jaced-input);
        border-radius: 999px; padding: 10px 18px; cursor: pointer;
        transition: border 0.25s ease; white-space: nowrap; user-select: none;
    }
    .wishlist-sort-trigger:hover { border-color: var(--jaced-brown-dark); }
    .wishlist-sort-label { font-size: 11px; color: var(--jaced-muted); text-transform: uppercase; letter-spacing: 0.15em; }
    .wishlist-sort-value { font-size: 13px; font-weight: 600; color: var(--jaced-brown-dark); }
    .wishlist-sort-chevron { font-size: 10px; color: var(--jaced-muted); transition: transform 0.3s ease; }
    .wishlist-sort-wrap.open .wishlist-sort-chevron,
    .wishlist-filter-wrap.open .wishlist-sort-chevron { transform: rotate(180deg); }

    .wishlist-sort-menu {
        position: absolute; top: calc(100% + 8px); right: 0;
        min-width: 200px; background: var(--jaced-card);
        border: 1px solid var(--jaced-input); border-radius: 16px;
        padding: 8px; box-shadow: 0 16px 40px rgba(39,46,29,0.12);
        opacity: 0; visibility: hidden; transform: translateY(-8px);
        transition: opacity 0.25s ease, transform 0.25s ease, visibility 0.25s; z-index: 100;
    }
    .wishlist-sort-wrap.open .wishlist-sort-menu,
    .wishlist-filter-wrap.open .wishlist-sort-menu {
        opacity: 1; visibility: visible; transform: translateY(0);
    }
    .wishlist-sort-option {
        display: flex; align-items: center; justify-content: space-between;
        width: 100%; background: transparent; border: none; text-align: left;
        padding: 10px 14px; font-size: 13px; color: var(--jaced-brown-dark);
        cursor: pointer; border-radius: 10px; transition: background 0.2s ease;
    }
    .wishlist-sort-option:hover { background: rgba(201,154,107,0.1); }
    .wishlist-sort-option.active { color: var(--jaced-caramel); font-weight: 600; }

    .wishlist-active-filters {
        display: flex; align-items: center; flex-wrap: wrap; gap: 8px;
        margin-bottom: 20px; padding: 12px 16px;
        background: var(--jaced-card); border-radius: 12px;
    }
    .wishlist-active-label { font-size: 11px; color: var(--jaced-muted); text-transform: uppercase; letter-spacing: 0.15em; }
    .wishlist-chip {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 12px; background: var(--jaced-brown-dark);
        color: var(--jaced-cream); border-radius: 999px; font-size: 12px; font-weight: 500;
    }
    .wishlist-chip button {
        background: transparent; border: none; color: var(--jaced-cream);
        opacity: 0.7; cursor: pointer; font-size: 10px; padding: 0; line-height: 1;
    }
    .wishlist-chip button:hover { opacity: 1; }

    .wishlist-empty {
        text-align: center; padding: 100px 24px;
    }
    .wishlist-empty-icon { font-size: 48px; color: var(--jaced-input); margin-bottom: 20px; }
    .wishlist-empty-title { font-size: 22px; font-weight: 600; color: var(--jaced-brown-dark); margin-bottom: 8px; }
    .wishlist-empty-desc { color: var(--jaced-muted); margin-bottom: 28px; }
    .wishlist-empty-btn {
        display: inline-flex; align-items: center;
        background: var(--jaced-brown-dark); color: var(--jaced-cream);
        padding: 14px 32px; border-radius: 999px; text-decoration: none;
        font-size: 13px; font-weight: 600; transition: background 0.3s ease;
    }
    .wishlist-empty-btn:hover { background: var(--jaced-caramel); color: var(--jaced-cream); }

    .wishlist-no-results { text-align: center; padding: 60px 24px; color: var(--jaced-muted); }
    .wishlist-no-results i { font-size: 32px; margin-bottom: 12px; display: block; }

    .wl-card {
        background: var(--jaced-card); border-radius: 16px; overflow: hidden;
        transition: transform 0.4s cubic-bezier(0.22,1,0.36,1), box-shadow 0.4s ease;
        position: relative;
    }
    .wl-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(39,46,29,0.1); }
    .wl-card-img-wrap { position: relative; aspect-ratio: 1; overflow: hidden; background: var(--jaced-input); }
    .wl-card-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s cubic-bezier(0.22,1,0.36,1); }
    .wl-card:hover .wl-card-img { transform: scale(1.06); }

    .wl-remove-btn i { transition: all 0.2s ease; }
    .wl-remove-btn:hover i {
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
    }
    .wl-remove-btn:hover { background: rgba(156, 53, 53, 0.1); color: #9c3535; }

    .wl-card-body { padding: 16px; }
    .wl-card-cat { font-size: 10px; text-transform: uppercase; letter-spacing: 0.2em; color: var(--jaced-caramel); font-weight: 600; margin-bottom: 4px; display: block; }
    .wl-card-name { font-size: 15px; font-weight: 600; color: var(--jaced-brown-dark); margin-bottom: 4px; letter-spacing: -0.01em; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .wl-card-price { font-size: 15px; font-weight: 700; color: var(--jaced-brown-dark); margin-bottom: 12px; display: block; }
    .wl-card-actions { display: flex; gap: 8px; }

    .wl-atc-btn {
        flex: 1; display: flex; align-items: center; justify-content: center;
        background: var(--jaced-brown-dark); color: var(--jaced-cream);
        padding: 10px 16px; border-radius: 999px; font-size: 12px; font-weight: 600;
        border: none; cursor: pointer; transition: background 0.3s ease; gap: 6px; width: 100%;
    }
    .wl-atc-btn:hover { background: var(--jaced-caramel); color: var(--jaced-cream); }
    .wl-atc-btn.added { background: #4a7c59; }
    .wl-atc-btn:disabled { opacity: 0.7; cursor: not-allowed; }

    .wl-confirm-backdrop {
        position: fixed; inset: 0;
        background: rgba(28, 28, 26, 0.5);
        backdrop-filter: blur(4px);
        z-index: 1300;
        display: flex; align-items: center; justify-content: center;
        opacity: 0; visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s;
    }
    .wl-confirm-backdrop.show {
        opacity: 1; visibility: visible;
    }
    .wl-confirm-modal {
        background: var(--jaced-caramel-bg);
        border-radius: 24px;
        padding: 40px 36px;
        max-width: 380px;
        width: 90%;
        text-align: center;
        transform: scale(0.92) translateY(12px);
        transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1);
        box-shadow: 0 24px 60px rgba(0,0,0,0.15);
    }
    .wl-confirm-backdrop.show .wl-confirm-modal {
        transform: scale(1) translateY(0);
    }
    .wl-confirm-icon {
        font-size: 32px;
        color: #9c3535;
        margin-bottom: 16px;
    }
    .wl-confirm-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--jaced-brown-dark);
        margin-bottom: 8px;
        letter-spacing: -0.02em;
    }
    .wl-confirm-msg {
        font-size: 14px;
        color: var(--jaced-muted);
        margin-bottom: 28px;
        line-height: 1.5;
    }
    .wl-confirm-actions {
        display: flex;
        gap: 12px;
    }
    .wl-confirm-cancel {
        flex: 1;
        background: transparent;
        border: 1px solid var(--jaced-input);
        color: var(--jaced-brown-dark);
        padding: 12px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .wl-confirm-cancel:hover {
        background: var(--jaced-card);
    }
    .wl-confirm-ok {
        flex: 1;
        background: #9c3535;
        border: none;
        color: white;
        padding: 12px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .wl-confirm-ok:hover { background: #7a2828; }

    .wl-remove-btn {
        position: absolute; top: 12px; right: 12px;
        width: 34px; height: 34px; border-radius: 50%;
        background: rgba(242, 237, 230, 0.95);
        backdrop-filter: blur(8px);
        border: none; display: flex; align-items: center; justify-content: center;
        color: var(--jaced-brown-dark); font-size: 13px; cursor: pointer; z-index: 3;
        transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
    }
    .wl-remove-btn .fa-heart::before { content: "\f004"; }
    .wl-remove-btn:hover .fa-heart::before { content: "\f7a9"; }
    .wl-remove-btn:hover {
        background: rgba(156, 53, 53, 0.12);
        color: #9c3535;
        transform: scale(1.1);
    }

    .wishlist-load-more-wrap { display: flex; justify-content: center; margin-top: 40px; }
    .wishlist-load-more-btn {
        display: inline-flex; align-items: center; gap: 10px;
        background: transparent; border: 1px solid var(--jaced-input);
        color: var(--jaced-brown-dark); padding: 14px 36px; border-radius: 999px;
        font-size: 13px; font-weight: 600; cursor: pointer;
        transition: all 0.3s ease; letter-spacing: 0.05em;
    }
    .wishlist-load-more-btn:hover { background: var(--jaced-brown-dark); color: var(--jaced-cream); border-color: var(--jaced-brown-dark); }
    .wishlist-load-more-count { font-size: 11px; color: var(--jaced-muted); font-weight: 400; }

    .wish-toast {
        position: fixed; bottom: 28px; left: 50%;
        transform: translateX(-50%) translateY(80px);
        background: var(--jaced-brown-dark); color: var(--jaced-cream);
        padding: 14px 26px; border-radius: 999px; font-size: 14px; font-weight: 500;
        display: flex; align-items: center; gap: 10px; z-index: 1200; opacity: 0;
        transition: transform 0.4s cubic-bezier(0.22,1,0.36,1), opacity 0.4s ease;
        box-shadow: 0 12px 32px rgba(0,0,0,0.2); white-space: nowrap;
    }
    .wish-toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }
    .wish-toast i { color: #6fae6f; }

    @media (max-width: 576px) {
        .wishlist-toolbar { flex-direction: column; }
        .wishlist-search-wrap { min-width: 100%; }
        .wishlist-header { flex-direction: column; align-items: flex-start; }
    }
</style>

<script>
(function () {
    const grid = document.getElementById('wishlistGrid');
    const emptyEl = document.getElementById('wishlistEmpty');
    const noResultsEl = document.getElementById('wishlistNoResults');
    const toolbarEl = document.getElementById('wishlistToolbar');
    const countEl = document.getElementById('wishlistCount');
    const toast = document.getElementById('wishToast');
    const toastText = document.getElementById('wishToastText');
    const activeFiltersEl = document.getElementById('activeFilters');
    const activeChipsEl = document.getElementById('activeFilterChips');
    const loadMoreWrap = document.getElementById('loadMoreWrap');
    const loadMoreCount = document.getElementById('loadMoreCount');
    const PER_PAGE = 12;

    let visibleCount = PER_PAGE;
    let currentSort = 'default';
    let currentFilter = 'all';
    let currentSearch = '';
    let toastTimer = null;
    let allItems = [];

    // =========================================
    // API HELPERS
    // =========================================

    async function fetchWishlist() {
        try {
            const response = await fetch('/wishlist/items');

            if (!response.ok) {
                throw new Error('Failed');
            }

            return await response.json();

        } catch (e) {
            console.error(e);
            return [];
        }
    }

    async function removeWishlist(id) {
        return fetch(`/wishlist/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
    }

    async function clearWishlist() {
        return fetch('/wishlist-clear', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
    }

    // =========================================
    // UTILITIES
    // =========================================

    function showToast(msg) {
        toastText.textContent = msg;
        toast.classList.add('show');

        clearTimeout(toastTimer);

        toastTimer = setTimeout(() => {
            toast.classList.remove('show');
        }, 2500);
    }

    let confirmCallback = null;

    function showConfirm(msg, onConfirm) {
        document.getElementById('wlConfirmMsg').textContent = msg;
        document.getElementById('wlConfirmBackdrop').classList.add('show');
        confirmCallback = onConfirm;
    }

    document.getElementById('wlConfirmOk').addEventListener('click', () => {
        document.getElementById('wlConfirmBackdrop').classList.remove('show');

        if (confirmCallback) {
            confirmCallback();
            confirmCallback = null;
        }
    });

    document.getElementById('wlConfirmCancel').addEventListener('click', () => {
        document.getElementById('wlConfirmBackdrop').classList.remove('show');
        confirmCallback = null;
    });

    document.getElementById('wlConfirmBackdrop').addEventListener('click', (e) => {
        if (e.target === e.currentTarget) {
            document.getElementById('wlConfirmBackdrop').classList.remove('show');
            confirmCallback = null;
        }
    });

    function parsePrice(str) {
        if (!str) return 0;
        return parseInt(String(str).replace(/\D/g, '')) || 0;
    }

    // =========================================
    // FILTER + SEARCH + SORT
    // =========================================

    function getFiltered() {

        let items = [...allItems];

        // SEARCH
        if (currentSearch) {

            const q = currentSearch.toLowerCase();

            items = items.filter(x =>
                (x.product?.name || '').toLowerCase().includes(q) ||
                (x.product?.category?.name || '').toLowerCase().includes(q)
            );
        }

        // FILTER
        if (currentFilter !== 'all') {

            items = items.filter(x =>
                (x.product?.category?.name || '').toLowerCase() === currentFilter.toLowerCase()
            );
        }

        // SORT
        switch (currentSort) {
            case 'name_asc':
                items.sort((a, b) => (a.product?.name || '').localeCompare(b.product?.name || ''));
                break;
            case 'name_desc':
                items.sort((a, b) => (b.product?.name || '').localeCompare(a.product?.name || ''));
                break;
            case 'price_asc':
                items.sort((a, b) => parsePrice(a.product?.price) - parsePrice(b.product?.price));
                break;
            case 'price_desc':
                items.sort((a, b) => parsePrice(b.product?.price) - parsePrice(a.product?.price));
                break;
            case 'category':
                items.sort((a, b) => (a.product?.category?.name || '').localeCompare(b.product?.category?.name || ''));
                break;
        }

        return items;
    }

    // =========================================
    // RENDER
    // =========================================

    async function render() {

        allItems = await fetchWishlist();

        const filtered = getFiltered();

        countEl.textContent = `(${allItems.length})`;

        // EMPTY STATE
        if (allItems.length === 0) {

            emptyEl.style.display = 'block';
            noResultsEl.style.display = 'none';
            toolbarEl.style.display = 'none';
            loadMoreWrap.style.display = 'none';
            activeFiltersEl.style.display = 'none';

            grid.innerHTML = '';

            return;
        }

        emptyEl.style.display = 'none';
        toolbarEl.style.display = 'flex';

        // NO RESULTS
        if (filtered.length === 0) {

            noResultsEl.style.display = 'block';
            loadMoreWrap.style.display = 'none';

            grid.innerHTML = '';

            renderActiveFilters();

            return;
        }

        noResultsEl.style.display = 'none';

        const visibleItems = filtered.slice(0, visibleCount);

        const remaining = filtered.length - visibleCount;

        grid.innerHTML = visibleItems.map((item, i) => `

            <div class="col-6 col-md-4 col-lg-3"
                 style="opacity:0; transform:translateY(24px); transition: opacity 0.4s ease ${i * 0.05}s, transform 0.4s ease ${i * 0.05}s;"
                 data-wishcard>

                <div class="wl-card">

                    <a href="/product/${item.product.slug}" style="text-decoration:none; color:inherit;">

                        <div class="wl-card-img-wrap">

                            <img src="${item.product.main_image.image_path}"
                                 alt="${item.product.name}"
                                 class="wl-card-img">

                            <button class="wl-remove-btn"
                                    data-id="${item.product.id}"
                                    data-name="${item.product.name}"
                                    title="Remove from wishlist">

                                <i class="fas fa-heart"></i>

                            </button>

                        </div>

                        <div class="wl-card-body">

                            <small class="wl-card-cat">
                                ${item.product.category?.name || 'Furniture'}
                            </small>

                            <h5 class="wl-card-name">
                                ${item.product.name}
                            </h5>

                            <span class="wl-card-price">
                                Rp ${Number(item.product.price).toLocaleString('id-ID')}
                            </span>

                        </div>

                    </a>

                    <div class="wl-card-actions px-3 pb-3">

                        <a href="/product/${item.product.slug}"
                        class="wl-atc-btn"
                        style="text-decoration:none;">
                            <i class="fas fa-arrow-right"></i>
                            See details
                        </a>

                    </div>

                </div>

            </div>

        `).join('');

        // ANIMATION
        requestAnimationFrame(() => {

            grid.querySelectorAll('[data-wishcard]').forEach(el => {

                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            });
        });

        // LOAD MORE
        if (remaining > 0) {

            loadMoreWrap.style.display = 'flex';
            loadMoreCount.textContent = `(${remaining} more)`;

        } else {

            loadMoreWrap.style.display = 'none';
        }

        // =========================================
        // REMOVE ITEM
        // =========================================

        grid.querySelectorAll('.wl-remove-btn').forEach(btn => {

            btn.addEventListener('click', (e) => {

                e.preventDefault();
                e.stopPropagation();

                const id = btn.dataset.id;
                const name = btn.dataset.name;

                showConfirm(
                    `Remove "${name}" from wishlist?`,
                    async () => {

                        await removeWishlist(id);

                        showToast(name + ' removed');

                        render();
                    }
                );
            });
        });        

        updateCategoryOptions(allItems);
        renderActiveFilters();
    }

    // CATEGORY OPTIONS
    function updateCategoryOptions(list) {

        const cats = [...new Set(
            list.map(x => x.product.category?.name).filter(Boolean)
        )];

        const filterMenu = document.getElementById('filterMenu');

        filterMenu.querySelectorAll(
            '.wishlist-sort-option:not([data-filter="all"])'
        ).forEach(el => el.remove());

        cats.forEach(cat => {
            const btn = document.createElement('button');
            btn.className =
                'wishlist-sort-option' +
                (currentFilter === cat.toLowerCase() ? ' active' : '');
            btn.setAttribute('data-filter', cat.toLowerCase());
            btn.textContent = cat;
            btn.addEventListener('click', () => {
                currentFilter = cat.toLowerCase();
                document.getElementById('filterLabel').textContent = cat;
                filterMenu.querySelectorAll('.wishlist-sort-option')
                    .forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                document.querySelector('.wishlist-filter-wrap')
                    .classList.remove('open');
                visibleCount = PER_PAGE;
                render();
            });
            filterMenu.appendChild(btn);
        });
    }
    // ACTIVE FILTERS
    function renderActiveFilters() {
        const chips = [];
        if (currentFilter !== 'all') {
            chips.push({
                label: `Category: ${currentFilter}`,
                clear: () => {
                    currentFilter = 'all';
                    document.getElementById('filterLabel').textContent = 'All';
                    render();
                }
            });
        }

        if (currentSearch) {
            chips.push({
                label: `"${currentSearch}"`,
                clear: () => {
                    currentSearch = '';
                    document.getElementById('wishlistSearch').value = '';
                    document.getElementById('searchClearBtn').style.display = 'none';
                    render();
                }
            });
        }

        if (chips.length > 0) {

            activeFiltersEl.style.display = 'flex';

            activeChipsEl.innerHTML = chips.map((c, i) => `
                <span class="wishlist-chip">
                    ${c.label}
                    <button data-chip="${i}">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
            `).join('');

            activeChipsEl.querySelectorAll('button[data-chip]')
                .forEach(btn => {
                    btn.addEventListener('click', () => {
                        chips[parseInt(btn.dataset.chip)].clear();
                    });
                });

        } else {
            activeFiltersEl.style.display = 'none';
        }
    }

    // SEARCH
    const searchInput = document.getElementById('wishlistSearch');
    const searchClear = document.getElementById('searchClearBtn');
    let searchTimer;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        currentSearch = searchInput.value;
        searchClear.style.display =
            currentSearch ? 'block' : 'none';
        searchTimer = setTimeout(() => {
            visibleCount = PER_PAGE;
            render();
        }, 300);
    });

    searchClear.addEventListener('click', () => {
        currentSearch = '';
        searchInput.value = '';
        searchClear.style.display = 'none';
        render();
    });

    // LOAD MORE
    document.getElementById('loadMoreBtn')
        .addEventListener('click', () => {
            visibleCount += PER_PAGE;
            render();
        });

    // CLEAR ALL
    document.getElementById('clearAllBtn')
        .addEventListener('click', async () => {
            if (!allItems.length) return;
            showConfirm(
                'Clear your entire wishlist?',
                async () => {
                    await clearWishlist();
                    showToast('Wishlist cleared');
                    render();
                }
            );
        });

    // SORT
    const sortWrap = document.querySelector('.wishlist-sort-wrap');
    document.getElementById('sortTrigger')
        .addEventListener('click', (e) => {
            e.stopPropagation();
            sortWrap.classList.toggle('open');
        });

    document.querySelectorAll('#sortMenu .wishlist-sort-option')
        .forEach(btn => {
            btn.addEventListener('click', () => {
                currentSort = btn.dataset.sort;
                document.getElementById('sortLabel')
                    .textContent = btn.textContent.trim();
                document.querySelectorAll('#sortMenu .wishlist-sort-option')
                    .forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                sortWrap.classList.remove('open');
                render();
            });
        });

    // FILTER
    const filterWrap = document.querySelector('.wishlist-filter-wrap');
    document.getElementById('filterTrigger')
        .addEventListener('click', (e) => {
            e.stopPropagation();
            filterWrap.classList.toggle('open');
        });

    document.addEventListener('click', (e) => {
        if (!sortWrap.contains(e.target)) {
            sortWrap.classList.remove('open');
        }
        if (!filterWrap.contains(e.target)) {
            filterWrap.classList.remove('open');
        }
    });

    // INITIAL
    render();

})();
</script>