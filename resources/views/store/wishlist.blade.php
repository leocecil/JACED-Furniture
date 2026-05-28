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

        {{-- ===== SEARCH (full width) ===== --}}
        <div class="wl-search-wrap" id="wlSearchWrap" style="display:none;">
            <i class="fas fa-search wl-search-icon"></i>
            <input type="text" id="wishlistSearch" class="wl-search-input" placeholder="Search saved items...">
            <button class="wl-search-clear" id="searchClearBtn" style="display:none;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- ===== FILTER PILLS ===== --}}
        <div class="wl-filters-row" id="wlFiltersRow" style="display:none;">

            {{-- SORT --}}
            <div class="wl-pill-wrap" id="wlSortWrap">
                <button type="button" class="wl-pill" id="wlSortTrigger">
                    <span class="pill-label">Sort</span>
                    <span class="pill-value" id="wlSortLabel">Recommended</span>
                    <i class="fas fa-chevron-down pill-chevron"></i>
                </button>
                <div class="wl-pill-dropdown" id="wlSortMenu">
                    <button class="wl-dd-opt active" data-sort="default">Recommended <i class="fas fa-check"></i></button>
                    <button class="wl-dd-opt" data-sort="name_asc">Name A–Z</button>
                    <button class="wl-dd-opt" data-sort="name_desc">Name Z–A</button>
                    <button class="wl-dd-opt" data-sort="price_asc">Price: Low to High</button>
                    <button class="wl-dd-opt" data-sort="price_desc">Price: High to Low</button>
                </div>
            </div>

            {{-- CATEGORY (populated by JS) --}}
            <div class="wl-pill-wrap" id="wlCatWrap">
                <button type="button" class="wl-pill" id="wlCatTrigger">
                    <span class="pill-label">Category</span>
                    <span class="pill-value" id="wlCatLabel">All</span>
                    <i class="fas fa-chevron-down pill-chevron"></i>
                </button>
                <div class="wl-pill-dropdown" id="wlCatMenu">
                    <button class="wl-dd-opt active" data-filter="all">All <i class="fas fa-check"></i></button>
                </div>
            </div>

            {{-- MATERIAL --}}
            <div class="wl-pill-wrap" id="wlMatWrap">
                <button type="button" class="wl-pill" id="wlMatTrigger">
                    <span class="pill-label">Material</span>
                    <span class="pill-value" id="wlMatLabel">All</span>
                    <i class="fas fa-chevron-down pill-chevron"></i>
                </button>
                <div class="wl-pill-dropdown" id="wlMatMenu">
                    <button class="wl-dd-opt active" data-mat="all">All <i class="fas fa-check"></i></button>
                    <button class="wl-dd-opt" data-mat="solid-wood">Solid Wood</button>
                    <button class="wl-dd-opt" data-mat="fabric">Fabric</button>
                    <button class="wl-dd-opt" data-mat="leather">Leather</button>
                    <button class="wl-dd-opt" data-mat="metal">Metal</button>
                </div>
            </div>

            {{-- SIZE --}}
            <div class="wl-pill-wrap" id="wlSizeWrap">
                <button type="button" class="wl-pill" id="wlSizeTrigger">
                    <span class="pill-label">Size</span>
                    <span class="pill-value" id="wlSizeLabel">All</span>
                    <i class="fas fa-chevron-down pill-chevron"></i>
                </button>
                <div class="wl-pill-dropdown" id="wlSizeMenu">
                    <button class="wl-dd-opt active" data-size="all">All <i class="fas fa-check"></i></button>
                    <button class="wl-dd-opt" data-size="small">Small</button>
                    <button class="wl-dd-opt" data-size="medium">Medium</button>
                    <button class="wl-dd-opt" data-size="large">Large</button>
                </div>
            </div>

            {{-- PRICE --}}
            <div class="wl-pill-wrap" id="wlPriceWrap">
                <button type="button" class="wl-pill" id="wlPriceTrigger">
                    <span class="pill-label">Price</span>
                    <span class="pill-value" id="wlPriceLabel">Any</span>
                    <i class="fas fa-chevron-down pill-chevron"></i>
                </button>
                <div class="wl-pill-dropdown" id="wlPriceMenu">
                    <button class="wl-dd-opt active" data-price="all">Any <i class="fas fa-check"></i></button>
                    <button class="wl-dd-opt" data-price="0-1000000">Under Rp 1.000.000</button>
                    <button class="wl-dd-opt" data-price="1000000-5000000">Rp 1.000.000 – 5.000.000</button>
                    <button class="wl-dd-opt" data-price="5000000-15000000">Rp 5.000.000 – 15.000.000</button>
                    <button class="wl-dd-opt" data-price="15000000-30000000">Rp 15.000.000 – 30.000.000</button>
                    <button class="wl-dd-opt" data-price="30000000-999999999">Above Rp 30.000.000</button>
                </div>
            </div>

            {{-- CLEAR ALL --}}
            <a href="#" class="wl-clear-btn" id="wlClearBtn" style="display:none;" onclick="wlClearAllFilters(); return false;">
                <i class="fas fa-times me-1"></i> Clear filters
            </a>

        </div>

        {{-- ===== ACTIVE CHIPS ===== --}}
        <div class="wl-active-filters" id="wlActiveFilters" style="display:none;">
            <span class="wl-active-label">Active:</span>
            <div id="wlActiveChips"></div>
        </div>

        {{-- ===== EMPTY STATE ===== --}}
        <div class="wishlist-empty" id="wishlistEmpty">
            <div class="wishlist-empty-icon"><i class="far fa-heart"></i></div>
            @guest
                <h3 class="wishlist-empty-title">Please login first</h3>
                <p class="wishlist-empty-desc">You need to be logged in to view your wishlist.</p>
                <a href="{{ route('login') }}" class="wishlist-empty-btn">Login <i class="fas fa-arrow-right ms-2"></i></a>
            @else
                <h3 class="wishlist-empty-title">Your wishlist is empty</h3>
                <p class="wishlist-empty-desc">Save pieces you love while browsing the collection.</p>
                <a href="{{ route('shop') }}" class="wishlist-empty-btn">Browse Collection <i class="fas fa-arrow-right ms-2"></i></a>
            @endguest
        </div>

        {{-- ===== NO RESULTS ===== --}}
        <div class="wl-no-results" id="wishlistNoResults" style="display:none;">
            <i class="fas fa-search"></i>
            <p>No items match your filters.</p>
        </div>

        {{-- ===== PRODUCT GRID ===== --}}
        <div class="row g-4" id="wishlistGrid"></div>

        {{-- ===== LOAD MORE ===== --}}
        <div class="wl-load-more-wrap" id="loadMoreWrap" style="display:none;">
            <button class="wl-load-more-btn" id="loadMoreBtn">
                <i class="fas fa-plus"></i> Load More
                <span id="loadMoreCount"></span>
            </button>
        </div>

    </div>
</div>

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
    .wishlist-page { padding: 120px 0 80px; min-height: 100vh; }
    .wishlist-page .container { max-width: 1320px; }

    /* HEADER */
    .wishlist-header { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px; margin-bottom:28px; padding-bottom:24px; border-bottom:1px solid var(--jaced-input); }
    .wishlist-title { font-size:clamp(1.8rem,3vw,2.5rem); font-weight:700; letter-spacing:-0.03em; color:var(--jaced-brown-dark); margin:0; }
    .wishlist-count { color:var(--jaced-muted); font-weight:400; }
    .wishlist-header-right { display:flex; gap:12px; align-items:center; }
    .wishlist-browse-btn { display:inline-flex; align-items:center; background:var(--jaced-brown-dark); color:var(--jaced-cream); padding:10px 20px; border-radius:999px; font-size:13px; font-weight:600; text-decoration:none; transition:background 0.3s ease; }
    .wishlist-browse-btn:hover { background:var(--jaced-caramel); color:var(--jaced-cream); }
    .wishlist-clear-all-btn { display:inline-flex; align-items:center; background:transparent; border:1px solid var(--jaced-input); color:var(--jaced-muted); padding:10px 20px; border-radius:999px; font-size:13px; font-weight:500; cursor:pointer; transition:all 0.3s ease; }
    .wishlist-clear-all-btn:hover { background:#9c3535; border-color:#9c3535; color:white; }

    /* SEARCH */
    .wl-search-wrap { position:relative; background:var(--jaced-card); border-radius:999px; border:1px solid var(--jaced-input); transition:border 0.3s ease; margin-bottom:14px; }
    .wl-search-wrap:focus-within { border-color:var(--jaced-brown-dark); }
    .wl-search-icon { position:absolute; left:22px; top:50%; transform:translateY(-50%); color:var(--jaced-muted); font-size:13px; }
    .wl-search-input { background:transparent; border:none; width:100%; padding:14px 44px 14px 48px; font-size:14px; color:var(--jaced-brown-dark); outline:none; }
    .wl-search-input::placeholder { color:var(--jaced-muted); }
    .wl-search-clear { position:absolute; right:16px; top:50%; transform:translateY(-50%); background:transparent; border:none; color:var(--jaced-muted); font-size:12px; cursor:pointer; }
    .wl-search-clear:hover { color:var(--jaced-brown-dark); }

    /* FILTER PILLS */
    .wl-filters-row { display:flex; gap:10px; flex-wrap:wrap; align-items:center; justify-content:center; margin-bottom:16px; }
    .wl-pill-wrap { position:relative; }
    .wl-pill { display:inline-flex; align-items:center; gap:8px; background:var(--jaced-card); border:1px solid var(--jaced-input); border-radius:999px; padding:9px 16px; cursor:pointer; transition:border 0.25s ease, box-shadow 0.25s ease; white-space:nowrap; font-size:13px; }
    .wl-pill:hover { border-color:var(--jaced-brown-dark); }
    .wl-pill.active-pill { background:var(--jaced-brown-dark); border-color:var(--jaced-brown-dark); }
    .wl-pill.active-pill .pill-label, .wl-pill.active-pill .pill-value { color:var(--jaced-cream); }
    .wl-pill.active-pill .pill-chevron { color:rgba(242,237,230,0.7); }
    .wl-pill-wrap.open .wl-pill { border-color:var(--jaced-brown-dark); box-shadow:0 4px 18px rgba(39,46,29,0.08); }
    .pill-label { font-size:10px; color:var(--jaced-muted); text-transform:uppercase; letter-spacing:0.15em; }
    .pill-value { font-size:13px; color:var(--jaced-brown-dark); font-weight:600; max-width:140px; overflow:hidden; text-overflow:ellipsis; }
    .pill-chevron { font-size:10px; color:var(--jaced-muted); transition:transform 0.3s ease; }
    .wl-pill-wrap.open .pill-chevron { transform:rotate(180deg); }

    .wl-pill-dropdown { position:absolute; top:calc(100% + 8px); left:0; min-width:210px; background:var(--jaced-card); border:1px solid var(--jaced-input); border-radius:16px; padding:8px; box-shadow:0 16px 40px rgba(39,46,29,0.12); opacity:0; visibility:hidden; transform:translateY(-8px); transition:opacity 0.25s ease, transform 0.25s ease, visibility 0.25s; z-index:200; }
    .wl-pill-wrap.open .wl-pill-dropdown { opacity:1; visibility:visible; transform:translateY(0); }
    .wl-dd-opt { display:flex; align-items:center; justify-content:space-between; width:100%; background:transparent; border:none; text-align:left; padding:10px 14px; font-size:13px; color:var(--jaced-brown-dark); cursor:pointer; border-radius:10px; transition:background 0.2s ease; }
    .wl-dd-opt:hover { background:rgba(201,154,107,0.1); }
    .wl-dd-opt.active { color:var(--jaced-caramel); font-weight:600; }
    .wl-dd-opt i { font-size:10px; color:var(--jaced-caramel); }

    .wl-clear-btn { display:inline-flex; align-items:center; padding:9px 16px; border-radius:999px; font-size:13px; font-weight:500; color:var(--jaced-muted); text-decoration:none; border:1px solid var(--jaced-input); transition:all 0.2s ease; }
    .wl-clear-btn:hover { background:#9c3535; border-color:#9c3535; color:white; }

    /* ACTIVE CHIPS */
    .wl-active-filters { display:flex; align-items:center; flex-wrap:wrap; gap:8px; padding:12px 16px; background:var(--jaced-card); border-radius:12px; margin-bottom:16px; }
    .wl-active-label { font-size:11px; color:var(--jaced-muted); text-transform:uppercase; letter-spacing:0.15em; }
    .wl-chip { display:inline-flex; align-items:center; gap:6px; padding:4px 12px; background:var(--jaced-brown-dark); color:var(--jaced-cream); border-radius:999px; font-size:12px; font-weight:500; }
    .wl-chip button { background:transparent; border:none; color:var(--jaced-cream); opacity:0.7; cursor:pointer; font-size:10px; padding:0; }
    .wl-chip button:hover { opacity:1; }

    /* EMPTY */
    .wishlist-empty { text-align:center; padding:100px 24px; }
    .wishlist-empty-icon { font-size:48px; color:var(--jaced-input); margin-bottom:20px; }
    .wishlist-empty-title { font-size:22px; font-weight:600; color:var(--jaced-brown-dark); margin-bottom:8px; }
    .wishlist-empty-desc { color:var(--jaced-muted); margin-bottom:28px; }
    .wishlist-empty-btn { display:inline-flex; align-items:center; background:var(--jaced-brown-dark); color:var(--jaced-cream); padding:14px 32px; border-radius:999px; text-decoration:none; font-size:13px; font-weight:600; transition:background 0.3s ease; }
    .wishlist-empty-btn:hover { background:var(--jaced-caramel); color:var(--jaced-cream); }
    .wl-no-results { text-align:center; padding:60px 24px; color:var(--jaced-muted); }
    .wl-no-results i { font-size:32px; margin-bottom:12px; display:block; }

    /* PRODUCT CARD - sama kayak shop */
    .wl-card {
        display:block; text-decoration:none; color:var(--jaced-brown-dark);
        transition:transform 0.4s cubic-bezier(0.22,1,0.36,1);
        position:relative; border-radius:20px;
    }
    .wl-card::before {
        content:''; position:absolute; inset:-6px; border-radius:24px;
        background:linear-gradient(135deg, var(--jaced-caramel), var(--jaced-brown-dark), var(--jaced-caramel));
        background-size:200% 200%; opacity:0; transition:opacity 0.4s ease;
        z-index:-1; animation:wlBorderRotate 3s linear infinite paused;
    }
    .wl-card:hover::before { opacity:1; animation-play-state:running; }
    @keyframes wlBorderRotate { 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
    .wl-card:hover { color:var(--jaced-brown-dark); transform:translateY(-6px); }

    .wl-card-img-wrap {
        position:relative; aspect-ratio:1; background:var(--jaced-card);
        border-radius:16px; overflow:hidden; margin-bottom:14px;
        border:2px solid transparent; transition:border-color 0.4s ease, box-shadow 0.4s ease;
    }
    .wl-card:hover .wl-card-img-wrap {
        border-color:var(--jaced-caramel);
        box-shadow:0 0 0 6px rgba(201,154,107,0.18), 0 16px 40px rgba(39,46,29,0.12);
    }
    .wl-card-img { width:100%; height:100%; object-fit:cover; transition:transform 0.6s cubic-bezier(0.22,1,0.36,1); }
    .wl-card:hover .wl-card-img { transform:scale(1.06); }

    /* Heart - orange */
    .wl-remove-btn {
        position:absolute; top:12px; right:12px;
        width:34px; height:34px; border-radius:50%;
        background:var(--jaced-caramel); border:none;
        display:flex; align-items:center; justify-content:center;
        color:var(--jaced-cream); font-size:13px; cursor:pointer; z-index:3;
        transition:background 0.2s ease, color 0.2s ease, transform 0.2s ease;
    }
    .wl-remove-btn .fa-heart::before { content:"\f004"; }
    .wl-remove-btn:hover .fa-heart::before { content:"\f7a9"; }
    .wl-remove-btn:hover { background:rgba(156,53,53,0.15); color:#9c3535; transform:scale(1.1); }

    /* Card info */
    .wl-card-info { padding:0 4px; }
    .wl-card-cat { display:block; font-size:10px; text-transform:uppercase; letter-spacing:0.2em; color:var(--jaced-caramel); font-weight:500; margin-bottom:5px; transition:color 0.4s ease; }
    .wl-card-name { font-size:15px; font-weight:600; letter-spacing:-0.01em; margin:0 0 8px; color:var(--jaced-brown-dark); line-height:1.3; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; transition:color 0.4s ease; }
    .wl-card-bottom { display:flex; justify-content:space-between; align-items:center; gap:8px; margin-bottom:12px; }
    .wl-card-price { font-size:14px; font-weight:600; color:var(--jaced-brown-dark); transition:color 0.4s ease; }
    .wl-card-dim { font-size:11px; color:var(--jaced-muted); font-weight:500; white-space:nowrap; transition:color 0.4s ease; }
    .wl-card:hover .wl-card-name { color:var(--jaced-cream); }
    .wl-card:hover .wl-card-cat { color:var(--jaced-caramel); }
    .wl-card:hover .wl-card-price { color:var(--jaced-cream); }
    .wl-card:hover .wl-card-dim { color:rgba(242,237,230,0.6); }

    /* See Details - brown-dark default, sweep putih ke orange pas hover */
    .wl-see-details {
        display:flex; align-items:center; justify-content:center; gap:6px;
        width:100%; padding:10px 16px; border-radius:999px;
        font-size:12px; font-weight:600; text-decoration:none;
        background:var(--jaced-caramel); color:var(--jaced-cream);
        position:relative; overflow:hidden;
        transition:color 0.4s ease;
    }
    .wl-see-details::before {   
        content:''; position:absolute; top:0; left:-100%; width:100%; height:100%;
        background:var(--jaced-cream);
        transition:left 0.4s cubic-bezier(0.22,1,0.36,1); z-index:0;
    }
    .wl-see-details:hover::before { left:0; }
    .wl-see-details:hover { color:var(--jaced-brown-dark); }
    .wl-see-details span, .wl-see-details i { position:relative; z-index:1; }

    /* LOAD MORE */
    .wl-load-more-wrap { display:flex; justify-content:center; margin-top:40px; }
    .wl-load-more-btn { display:inline-flex; align-items:center; gap:10px; background:transparent; border:1px solid var(--jaced-input); color:var(--jaced-brown-dark); padding:14px 36px; border-radius:999px; font-size:13px; font-weight:600; cursor:pointer; transition:all 0.3s ease; }
    .wl-load-more-btn:hover { background:var(--jaced-brown-dark); color:var(--jaced-cream); border-color:var(--jaced-brown-dark); }

    /* CONFIRM */
    .wl-confirm-backdrop { position:fixed; inset:0; background:rgba(28,28,26,0.5); backdrop-filter:blur(4px); z-index:1300; display:flex; align-items:center; justify-content:center; opacity:0; visibility:hidden; transition:opacity 0.3s ease, visibility 0.3s; }
    .wl-confirm-backdrop.show { opacity:1; visibility:visible; }
    .wl-confirm-modal { background:var(--jaced-caramel-bg); border-radius:24px; padding:40px 36px; max-width:380px; width:90%; text-align:center; transform:scale(0.92) translateY(12px); transition:transform 0.35s cubic-bezier(0.22,1,0.36,1); box-shadow:0 24px 60px rgba(0,0,0,0.15); }
    .wl-confirm-backdrop.show .wl-confirm-modal { transform:scale(1) translateY(0); }
    .wl-confirm-icon { font-size:32px; color:#9c3535; margin-bottom:16px; }
    .wl-confirm-title { font-size:18px; font-weight:700; color:var(--jaced-brown-dark); margin-bottom:8px; letter-spacing:-0.02em; }
    .wl-confirm-msg { font-size:14px; color:var(--jaced-muted); margin-bottom:28px; line-height:1.5; }
    .wl-confirm-actions { display:flex; gap:12px; }
    .wl-confirm-cancel { flex:1; background:transparent; border:1px solid var(--jaced-input); color:var(--jaced-brown-dark); padding:12px; border-radius:999px; font-size:13px; font-weight:600; cursor:pointer; transition:all 0.3s ease; }
    .wl-confirm-cancel:hover { background:var(--jaced-card); }
    .wl-confirm-ok { flex:1; background:#9c3535; border:none; color:white; padding:12px; border-radius:999px; font-size:13px; font-weight:600; cursor:pointer; transition:all 0.3s ease; }
    .wl-confirm-ok:hover { background:#7a2828; }

    /* TOAST */
    .wish-toast { position:fixed; bottom:28px; left:50%; transform:translateX(-50%) translateY(80px); background:var(--jaced-brown-dark); color:var(--jaced-cream); padding:14px 26px; border-radius:999px; font-size:14px; font-weight:500; display:flex; align-items:center; gap:10px; z-index:1200; opacity:0; transition:transform 0.4s cubic-bezier(0.22,1,0.36,1), opacity 0.4s ease; box-shadow:0 12px 32px rgba(0,0,0,0.2); white-space:nowrap; }
    .wish-toast.show { transform:translateX(-50%) translateY(0); opacity:1; }
    .wish-toast i { color:#6fae6f; }

    @media (max-width:576px) {
        .wishlist-header { flex-direction:column; align-items:flex-start; }
        .wl-filters-row { gap:8px; }
    }
</style>

<script>
(function () {
    const grid         = document.getElementById('wishlistGrid');
    const emptyEl      = document.getElementById('wishlistEmpty');
    const noResultsEl  = document.getElementById('wishlistNoResults');
    const searchWrap   = document.getElementById('wlSearchWrap');
    const filtersRow   = document.getElementById('wlFiltersRow');
    const countEl      = document.getElementById('wishlistCount');
    const toast        = document.getElementById('wishToast');
    const toastText    = document.getElementById('wishToastText');
    const activeFilEl  = document.getElementById('wlActiveFilters');
    const activeChips  = document.getElementById('wlActiveChips');
    const loadMoreWrap = document.getElementById('loadMoreWrap');
    const loadMoreCnt  = document.getElementById('loadMoreCount');
    const clearBtn     = document.getElementById('wlClearBtn');
    const PER_PAGE = 12;

    let visibleCount  = PER_PAGE;
    let currentSort   = 'default';
    let currentFilter = 'all';
    let currentMat    = 'all';
    let currentSize   = 'all';
    let currentPrice  = 'all';
    let currentSearch = '';
    let toastTimer    = null;
    let allItems      = [];

    // ===== API =====
    async function fetchWishlist() {
        try {
            @guest
            return [];
            @endguest
            const r = await fetch('/wishlist/items');
            if (!r.ok) throw new Error('Failed');
            return await r.json();
        } catch(e) { console.error(e); return []; }
    }

    async function removeWishlist(id) {
        return fetch(`/wishlist/${id}`, { method:'DELETE', headers:{ 'X-CSRF-TOKEN':'{{ csrf_token() }}', 'Accept':'application/json' } });
    }

    async function clearWishlist() {
        return fetch('/wishlist-clear', { method:'DELETE', headers:{ 'X-CSRF-TOKEN':'{{ csrf_token() }}', 'Accept':'application/json' } });
    }

    // ===== TOAST =====
    function showToast(msg) {
        toastText.textContent = msg;
        toast.classList.add('show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toast.classList.remove('show'), 2500);
    }

    // ===== CONFIRM =====
    let confirmCb = null;
    function showConfirm(msg, cb) {
        document.getElementById('wlConfirmMsg').textContent = msg;
        document.getElementById('wlConfirmBackdrop').classList.add('show');
        confirmCb = cb;
    }
    document.getElementById('wlConfirmOk').addEventListener('click', () => {
        document.getElementById('wlConfirmBackdrop').classList.remove('show');
        if (confirmCb) { confirmCb(); confirmCb = null; }
    });
    document.getElementById('wlConfirmCancel').addEventListener('click', () => {
        document.getElementById('wlConfirmBackdrop').classList.remove('show');
        confirmCb = null;
    });
    document.getElementById('wlConfirmBackdrop').addEventListener('click', e => {
        if (e.target === e.currentTarget) { document.getElementById('wlConfirmBackdrop').classList.remove('show'); confirmCb = null; }
    });

    function parsePrice(v) { return parseInt(String(v||'0').replace(/\D/g,''))||0; }

    function getSize(p) {
        const max = Math.max(parseFloat(p.length||0), parseFloat(p.width||0), parseFloat(p.height||0));
        if (max > 200) return 'large';
        if (max < 80) return 'small';
        return 'medium';
    }

    // ===== FILTER =====
    function getFiltered() {
        let items = [...allItems];
        if (currentSearch) {
            const q = currentSearch.toLowerCase();
            items = items.filter(x => (x.product?.name||'').toLowerCase().includes(q) || (x.product?.category?.name||'').toLowerCase().includes(q));
        }
        if (currentFilter !== 'all') {
            items = items.filter(x => (x.product?.category?.name||'').toLowerCase() === currentFilter.toLowerCase());
        }
        if (currentSize !== 'all') {
            items = items.filter(x => getSize(x.product) === currentSize);
        }
        if (currentPrice !== 'all') {
            const [mn, mx] = currentPrice.split('-').map(Number);
            items = items.filter(x => { const p = parsePrice(x.product?.price); return p >= mn && p <= mx; });
        }
        switch (currentSort) {
            case 'name_asc':   items.sort((a,b) => (a.product?.name||'').localeCompare(b.product?.name||'')); break;
            case 'name_desc':  items.sort((a,b) => (b.product?.name||'').localeCompare(a.product?.name||'')); break;
            case 'price_asc':  items.sort((a,b) => parsePrice(a.product?.price)-parsePrice(b.product?.price)); break;
            case 'price_desc': items.sort((a,b) => parsePrice(b.product?.price)-parsePrice(a.product?.price)); break;
        }
        return items;
    }

    // ===== RENDER =====
    async function render() {
        allItems = await fetchWishlist();
        const filtered = getFiltered();
        countEl.textContent = `(${allItems.length})`;

        if (allItems.length === 0) {
            emptyEl.style.display = 'block';
            noResultsEl.style.display = 'none';
            searchWrap.style.display = 'none';
            filtersRow.style.display = 'none';
            loadMoreWrap.style.display = 'none';
            activeFilEl.style.display = 'none';
            grid.innerHTML = '';
            return;
        }

        emptyEl.style.display = 'none';
        searchWrap.style.display = 'block';
        filtersRow.style.display = 'flex';

        if (filtered.length === 0) {
            noResultsEl.style.display = 'block';
            loadMoreWrap.style.display = 'none';
            grid.innerHTML = '';
            renderChips();
            return;
        }

        noResultsEl.style.display = 'none';
        const visible = filtered.slice(0, visibleCount);
        const remaining = filtered.length - visibleCount;

        grid.innerHTML = visible.map((item, i) => {
            const p = item.product;
            const imgPath = p.main_image?.image_path || '';
            const imgSrc = imgPath.startsWith('http') ? imgPath : '/' + imgPath;
            const dim = p.length && p.width ? `${p.length}×${p.width} ${p.unit||'cm'}` : '';
            const delay = i * 0.06;
            return `
            <div class="col-6 col-md-4 col-lg-3"
                 style="opacity:0; transform:translateY(24px); transition: opacity 0.45s ease ${delay}s, transform 0.45s cubic-bezier(0.22,1,0.36,1) ${delay}s;"
                 data-wishcard>
                <a href="/product/${p.slug}" class="wl-card">
                    <div class="wl-card-img-wrap">
                        <img src="${imgSrc}" alt="${p.name}" class="wl-card-img">
                        <button class="wl-remove-btn" data-id="${p.id}" data-name="${p.name}" title="Remove from wishlist">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                    <div class="wl-card-info">
                        <small class="wl-card-cat">${p.category?.name||'Furniture'}</small>
                        <h5 class="wl-card-name">${p.name}</h5>
                        <div class="wl-card-bottom">
                            <span class="wl-card-price">Rp ${Number(p.price).toLocaleString('id-ID')}</span>
                            ${dim ? `<span class="wl-card-dim">${dim}</span>` : ''}
                        </div>
                        <div class="wl-see-details">
                            <i class="fas fa-arrow-right"></i>
                            <span>See Details</span>
                        </div>
                    </div>
                </a>
            </div>`;
        }).join('');

        requestAnimationFrame(() => {
            grid.querySelectorAll('[data-wishcard]').forEach(el => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            });
        });

        loadMoreWrap.style.display = remaining > 0 ? 'flex' : 'none';
        if (remaining > 0) loadMoreCnt.textContent = `(${remaining} more)`;

        grid.querySelectorAll('.wl-remove-btn').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault(); e.stopPropagation();
                const id = btn.dataset.id, name = btn.dataset.name;
                showConfirm(`Remove "${name}" from wishlist?`, async () => {
                    await removeWishlist(id);
                    showToast(name + ' removed');
                    render();
                });
            });
        });

        updateCategoryOptions(allItems);
        renderChips();
    }

    // ===== CATEGORY OPTIONS =====
    function updateCategoryOptions(list) {
        const cats = [...new Set(list.map(x => x.product.category?.name).filter(Boolean))];
        const menu = document.getElementById('wlCatMenu');
        menu.querySelectorAll('.wl-dd-opt:not([data-filter="all"])').forEach(el => el.remove());
        cats.forEach(cat => {
            const btn = document.createElement('button');
            btn.className = 'wl-dd-opt' + (currentFilter === cat.toLowerCase() ? ' active' : '');
            btn.setAttribute('data-filter', cat.toLowerCase());
            btn.innerHTML = currentFilter === cat.toLowerCase() ? `${cat} <i class="fas fa-check"></i>` : cat;
            btn.addEventListener('click', () => {
                currentFilter = cat.toLowerCase();
                setPillValue('wlCatLabel', cat, 'wlCatWrap', true);
                document.getElementById('wlCatWrap').classList.remove('open');
                visibleCount = PER_PAGE;
                render();
            });
            menu.appendChild(btn);
        });
    }

    // ===== CHIPS =====
    function renderChips() {
        const hasFilter = currentFilter !== 'all' || currentSearch || currentSize !== 'all' || currentPrice !== 'all' || currentSort !== 'default';
        clearBtn.style.display = hasFilter ? 'inline-flex' : 'none';

        const chips = [];
        if (currentFilter !== 'all') chips.push({ label: `Category: ${currentFilter}`, clear: () => { currentFilter='all'; setPillValue('wlCatLabel','All','wlCatWrap',false); render(); }});
        if (currentSize !== 'all') chips.push({ label: `Size: ${currentSize}`, clear: () => { currentSize='all'; setPillValue('wlSizeLabel','All','wlSizeWrap',false); render(); }});
        if (currentPrice !== 'all') chips.push({ label: `Price filter active`, clear: () => { currentPrice='all'; setPillValue('wlPriceLabel','Any','wlPriceWrap',false); render(); }});
        if (currentSearch) chips.push({ label: `"${currentSearch}"`, clear: () => { currentSearch=''; document.getElementById('wishlistSearch').value=''; document.getElementById('searchClearBtn').style.display='none'; render(); }});

        if (chips.length > 0) {
            activeFilEl.style.display = 'flex';
            activeChips.innerHTML = chips.map((c,i) => `<span class="wl-chip">${c.label}<button data-chip="${i}"><i class="fas fa-times"></i></button></span>`).join('');
            activeChips.querySelectorAll('button[data-chip]').forEach(b => b.addEventListener('click', () => chips[parseInt(b.dataset.chip)].clear()));
        } else {
            activeFilEl.style.display = 'none';
        }
    }

    function setPillValue(labelId, value, wrapId, active) {
        document.getElementById(labelId).textContent = value;
        const pill = document.getElementById(wrapId)?.querySelector('.wl-pill');
        if (pill) pill.classList.toggle('active-pill', active);
    }

    window.wlClearAllFilters = function() {
        currentFilter='all'; currentMat='all'; currentSize='all'; currentPrice='all'; currentSearch='';
        setPillValue('wlCatLabel','All','wlCatWrap',false);
        setPillValue('wlMatLabel','All','wlMatWrap',false);
        setPillValue('wlSizeLabel','All','wlSizeWrap',false);
        setPillValue('wlPriceLabel','Any','wlPriceWrap',false);
        document.getElementById('wishlistSearch').value='';
        document.getElementById('searchClearBtn').style.display='none';
        render();
    };

    // ===== PILL DROPDOWNS =====
    const pillIds = ['wlSortWrap','wlCatWrap','wlMatWrap','wlSizeWrap','wlPriceWrap'];
    pillIds.forEach(id => {
        const wrap = document.getElementById(id);
        const trig = wrap?.querySelector('.wl-pill');
        if (!wrap || !trig) return;
        trig.addEventListener('click', e => {
            e.stopPropagation();
            const isOpen = wrap.classList.contains('open');
            pillIds.forEach(x => document.getElementById(x)?.classList.remove('open'));
            if (!isOpen) wrap.classList.add('open');
        });
    });

    // Sort
    document.querySelectorAll('#wlSortMenu .wl-dd-opt').forEach(btn => {
        btn.addEventListener('click', () => {
            currentSort = btn.dataset.sort;
            const label = btn.textContent.trim();
            document.querySelectorAll('#wlSortMenu .wl-dd-opt').forEach(b => { b.classList.remove('active'); b.querySelector('i')?.remove(); });
            btn.classList.add('active');
            const ic = document.createElement('i'); ic.className='fas fa-check'; btn.appendChild(ic);
            setPillValue('wlSortLabel', label, 'wlSortWrap', currentSort !== 'default');
            document.getElementById('wlSortWrap').classList.remove('open');
            render();
        });
    });

    // Material (client-side, no actual filter data so just label)
    document.querySelectorAll('#wlMatMenu .wl-dd-opt').forEach(btn => {
        btn.addEventListener('click', () => {
            currentMat = btn.dataset.mat;
            const label = btn.textContent.trim();
            document.querySelectorAll('#wlMatMenu .wl-dd-opt').forEach(b => { b.classList.remove('active'); b.querySelector('i')?.remove(); });
            btn.classList.add('active');
            const ic = document.createElement('i'); ic.className='fas fa-check'; btn.appendChild(ic);
            setPillValue('wlMatLabel', label, 'wlMatWrap', currentMat !== 'all');
            document.getElementById('wlMatWrap').classList.remove('open');
        });
    });

    // Size
    document.querySelectorAll('#wlSizeMenu .wl-dd-opt').forEach(btn => {
        btn.addEventListener('click', () => {
            currentSize = btn.dataset.size;
            const label = btn.textContent.trim();
            document.querySelectorAll('#wlSizeMenu .wl-dd-opt').forEach(b => { b.classList.remove('active'); b.querySelector('i')?.remove(); });
            btn.classList.add('active');
            const ic = document.createElement('i'); ic.className='fas fa-check'; btn.appendChild(ic);
            setPillValue('wlSizeLabel', label, 'wlSizeWrap', currentSize !== 'all');
            document.getElementById('wlSizeWrap').classList.remove('open');
            visibleCount = PER_PAGE;
            render();
        });
    });

    // Price
    document.querySelectorAll('#wlPriceMenu .wl-dd-opt').forEach(btn => {
        btn.addEventListener('click', () => {
            currentPrice = btn.dataset.price;
            const label = btn.textContent.trim();
            document.querySelectorAll('#wlPriceMenu .wl-dd-opt').forEach(b => { b.classList.remove('active'); b.querySelector('i')?.remove(); });
            btn.classList.add('active');
            const ic = document.createElement('i'); ic.className='fas fa-check'; btn.appendChild(ic);
            setPillValue('wlPriceLabel', label, 'wlPriceWrap', currentPrice !== 'all');
            document.getElementById('wlPriceWrap').classList.remove('open');
            visibleCount = PER_PAGE;
            render();
        });
    });

    document.addEventListener('click', () => pillIds.forEach(id => document.getElementById(id)?.classList.remove('open')));
    document.addEventListener('keydown', e => { if (e.key === 'Escape') pillIds.forEach(id => document.getElementById(id)?.classList.remove('open')); });

    // ===== SEARCH =====
    const searchInput = document.getElementById('wishlistSearch');
    const searchClear = document.getElementById('searchClearBtn');
    let searchTimer;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        currentSearch = searchInput.value;
        searchClear.style.display = currentSearch ? 'block' : 'none';
        searchTimer = setTimeout(() => { visibleCount = PER_PAGE; render(); }, 300);
    });
    searchClear.addEventListener('click', () => { currentSearch=''; searchInput.value=''; searchClear.style.display='none'; render(); });

    // ===== LOAD MORE =====
    document.getElementById('loadMoreBtn').addEventListener('click', () => { visibleCount += PER_PAGE; render(); });

    // ===== CLEAR ALL =====
    document.getElementById('clearAllBtn').addEventListener('click', async () => {
        if (!allItems.length) return;
        showConfirm('Clear your entire wishlist?', async () => {
            await clearWishlist();
            showToast('Wishlist cleared');
            render();
        });
    });

    render();
})();
</script>

@endsection