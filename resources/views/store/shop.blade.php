@extends('base.base')

@section('title', 'Shop — JACED Furniture')

@section('content')

    {{-- ============== SHOP HERO (dark band biar navbar putih kebaca) ============== --}}
    <section class="shop-hero">
        <div class="shop-hero-overlay"></div>
        <div class="container shop-hero-content">
            <p class="shop-hero-label">/ The Collection</p>
            <h1 class="shop-hero-title">Shop the Collection</h1>
            <p class="shop-hero-subtitle">
                <span class="shop-hero-count">{{ $totalProducts }}</span> pieces. Solid wood. Built to last 15+ years.
            </p>
        </div>
    </section>

    {{-- ============== MAIN SHOP AREA ============== --}}
    <section class="shop-main">
        <div class="container">

            <form action="{{ route('shop') }}" method="GET" id="filter-form">

                {{-- TOP BAR: search + sort + active filters --}}
                <div class="shop-toolbar">
                    <button type="button" class="shop-filter-toggle" id="filterToggle">
                        <i class="fas fa-sliders-h"></i> Filters
                    </button>
                    <div class="shop-search-wrap">
                        <i class="fas fa-search shop-search-icon"></i>
                        <input type="text" name="search" class="shop-search-input"
                               placeholder="Search nora chair, oka table, walnut..."
                               value="{{ request('search') }}">
                        @if(request('search'))
                            <button type="button" class="shop-search-clear" onclick="this.previousElementSibling.value=''; document.getElementById('filter-form').submit();">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                    </div>

                    <div class="shop-sort-wrap">
                        @php
                            $sortLabels = [
                                '' => 'Recommended',
                                'newest' => 'Newest first',
                                'price_asc' => 'Price: Low to High',
                                'price_desc' => 'Price: High to Low',
                                'bestseller' => 'Best sellers',
                            ];
                            $currentSort = request('sort', '');
                            $currentSortLabel = $sortLabels[$currentSort] ?? 'Recommended';
                        @endphp

                        <input type="hidden" name="sort" id="sort-input" value="{{ $currentSort }}">

                        <button type="button" class="shop-sort-trigger" id="sortTrigger">
                            <span class="shop-sort-trigger-label">Sort</span>
                            <span class="shop-sort-trigger-value">{{ $currentSortLabel }}</span>
                            <i class="fas fa-chevron-down shop-sort-chevron"></i>
                        </button>

                        <div class="shop-sort-menu" id="sortMenu">
                            @foreach($sortLabels as $val => $label)
                                <button type="button"
                                        class="shop-sort-option {{ $currentSort === $val ? 'active' : '' }}"
                                        data-value="{{ $val }}">
                                    {{ $label }}
                                    @if($currentSort === $val)
                                        <i class="fas fa-check"></i>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ACTIVE FILTER CHIPS --}}
                @php
                    $hasActiveFilters = request('search') || request('category') || request('material') || request('room') || request('size') || request('min_price') || request('max_price') || request('sort');
                @endphp

                @if($hasActiveFilters)
                    <div class="shop-active-filters">
                        <span class="shop-active-label">Active filters:</span>

                        @if(request('search'))
                            <span class="shop-chip">
                                Search: "{{ request('search') }}"
                                <a href="{{ route('shop', array_merge(request()->except(['search', 'page']))) }}"><i class="fas fa-times"></i></a>
                            </span>
                        @endif

                        @foreach((array) request('category', []) as $catSlug)
                            @php $catItem = $categories->firstWhere('slug', $catSlug); @endphp
                            @if($catItem)
                                <span class="shop-chip">
                                    {{ $catItem->name }}
                                    <a href="{{ route('shop', array_merge(request()->except('page'), ['category' => array_values(array_diff((array) request('category', []), [$catSlug]))])) }}"><i class="fas fa-times"></i></a>
                                </span>
                            @endif
                        @endforeach

                        @if(isset($rooms))
                            @foreach((array) request('room', []) as $roomSlug)
                                @php $roomItem = $rooms->firstWhere('slug', $roomSlug); @endphp
                                @if($roomItem)
                                    <span class="shop-chip">
                                        {{ $roomItem->name }}
                                        <a href="{{ route('shop', array_merge(request()->except('page'), ['room' => array_values(array_diff((array) request('room', []), [$roomSlug]))])) }}"><i class="fas fa-times"></i></a>
                                    </span>
                                @endif
                            @endforeach
                        @endif

                        @foreach((array) request('material', []) as $matSlug)
                            @php $matItem = $materials->firstWhere('slug', $matSlug); @endphp
                            @if($matItem)
                                <span class="shop-chip">
                                    {{ $matItem->name }}
                                    <a href="{{ route('shop', array_merge(request()->except('page'), ['material' => array_values(array_diff((array) request('material', []), [$matSlug]))])) }}"><i class="fas fa-times"></i></a>
                                </span>
                            @endif
                        @endforeach

                        @foreach((array) request('size', []) as $sizeSlug)
                            <span class="shop-chip">
                                Size: {{ ucfirst($sizeSlug) }}
                                <a href="{{ route('shop', array_merge(request()->except('page'), ['size' => array_values(array_diff((array) request('size', []), [$sizeSlug]))])) }}"><i class="fas fa-times"></i></a>
                            </span>
                        @endforeach

                        @if(request('min_price') || request('max_price'))
                            <span class="shop-chip">
                                Rp {{ request('min_price') ? number_format(request('min_price'), 0, ',', '.') : '0' }} – {{ request('max_price') ? number_format(request('max_price'), 0, ',', '.') : '∞' }}
                                <a href="{{ route('shop', array_merge(request()->except(['min_price', 'max_price', 'page']))) }}"><i class="fas fa-times"></i></a>
                            </span>
                        @endif

                        <a href="{{ route('shop') }}" class="shop-clear-all">Clear all</a>
                    </div>
                @endif

                <div class="row g-4 mt-1">

                    {{-- ============ SIDEBAR FILTERS ============ --}}
                    <div class="col-lg-3">
                        <div class="shop-sidebar-overlay" id="sidebarOverlay"></div>
                        <aside class="shop-sidebar" id="shopSidebar">
                            <div class="shop-sidebar-head">
                                <span class="shop-sidebar-title">Filters</span>
                                <button type="button" class="shop-sidebar-close" id="sidebarClose">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            {{-- Category --}}
                            <div class="shop-filter-group">
                                <h6 class="shop-filter-title">Category</h6>
                                @foreach($categories as $cat)
                                    <label class="shop-filter-row" for="cat-{{ $cat->slug }}">
                                        <input type="checkbox" id="cat-{{ $cat->slug }}" name="category[]" value="{{ $cat->slug }}"
                                               {{ in_array($cat->slug, (array) request('category', [])) ? 'checked' : '' }}
                                               onchange="document.getElementById('filter-form').submit()">
                                        <span class="shop-filter-name">{{ $cat->name }}</span>
                                        <span class="shop-filter-count">{{ $cat->products_count }}</span>
                                    </label>
                                @endforeach
                            </div>

                            {{-- Room --}}
                            @if(isset($rooms) && $rooms->count() > 0)
                            <div class="shop-filter-group">
                                <h6 class="shop-filter-title">Room</h6>
                                @foreach($rooms as $room)
                                    <label class="shop-filter-row" for="room-{{ $room->slug }}">
                                        <input type="checkbox" id="room-{{ $room->slug }}" name="room[]" value="{{ $room->slug }}"
                                               {{ in_array($room->slug, (array) request('room', [])) ? 'checked' : '' }}
                                               onchange="document.getElementById('filter-form').submit()">
                                        <span class="shop-filter-name">{{ $room->name }}</span>
                                        <span class="shop-filter-count">{{ $room->products_count }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @endif

                            {{-- Material --}}
                            <div class="shop-filter-group">
                                <h6 class="shop-filter-title">Material</h6>
                                @foreach($materials as $mat)
                                    <label class="shop-filter-row" for="mat-{{ $mat->slug }}">
                                        <input type="checkbox" id="mat-{{ $mat->slug }}" name="material[]" value="{{ $mat->slug }}"
                                               {{ in_array($mat->slug, (array) request('material', [])) ? 'checked' : '' }}
                                               onchange="document.getElementById('filter-form').submit()">
                                        <span class="shop-filter-name">{{ $mat->name }}</span>
                                        <span class="shop-filter-count">{{ $mat->products_count }}</span>
                                    </label>
                                @endforeach
                            </div>

                            {{-- Size --}}
                            <div class="shop-filter-group">
                                <h6 class="shop-filter-title">Size</h6>
                                @php
                                    $sizeOptions = [
                                        ['slug' => 'small', 'name' => 'Small', 'note' => 'Compact pieces'],
                                        ['slug' => 'medium', 'name' => 'Medium', 'note' => 'Standard size'],
                                        ['slug' => 'large', 'name' => 'Large', 'note' => 'Statement pieces'],
                                    ];
                                    $activeSizes = (array) request('size', []);
                                @endphp
                                @foreach($sizeOptions as $sz)
                                    <label class="shop-filter-row" for="size-{{ $sz['slug'] }}">
                                        <input type="checkbox" id="size-{{ $sz['slug'] }}" name="size[]" value="{{ $sz['slug'] }}"
                                               {{ in_array($sz['slug'], $activeSizes) ? 'checked' : '' }}
                                               onchange="document.getElementById('filter-form').submit()">
                                        <span class="shop-filter-name">{{ $sz['name'] }}</span>
                                        <span class="shop-filter-count">{{ $sz['note'] }}</span>
                                    </label>
                                @endforeach
                            </div>

                            {{-- Price --}}
                            <div class="shop-filter-group">
                                <h6 class="shop-filter-title">Price (IDR)</h6>
                                <div class="shop-price-row">
                                    <input type="number" name="min_price" class="shop-price-input"
                                           placeholder="Min" value="{{ request('min_price') }}">
                                    <span class="shop-price-sep">–</span>
                                    <input type="number" name="max_price" class="shop-price-input"
                                           placeholder="Max" value="{{ request('max_price') }}">
                                </div>
                                <button type="submit" class="shop-apply-btn">Apply Price</button>
                            </div>

                            <a href="{{ route('shop') }}" class="shop-reset-btn">Reset all filters</a>
                        </aside>
                    </div>

                    {{-- ============ PRODUCT GRID ============ --}}
                    <div class="col-lg-9">

                        <div class="shop-result-bar">
                            <small class="shop-result-count">
                                Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}
                                of {{ $products->total() }} products
                            </small>
                        </div>

                        <div class="row g-4">
                            @forelse($products as $product)
                                @php
                                    $soldOut = ($product->stock ?? 1) <= 0;
                                    $lowStock = !$soldOut && ($product->stock ?? 99) <= 3;
                                @endphp
                                <div class="col-md-6 col-xl-4">
                                    <a href="{{ route('product.show', $product->slug) }}"
                                       class="shop-product-card {{ $soldOut ? 'is-sold-out' : '' }}"
                                       data-product-id="{{ $product->id }}"
                                       data-product-name="{{ $product->name }}"
                                       data-product-cat="{{ $product->category->name }}"
                                       data-product-price="{{ number_format($product->price, 0, ',', '.') }}"
                                       data-product-oldprice="{{ $product->old_price ? number_format($product->old_price, 0, ',', '.') : '' }}"
                                       data-product-img="{{ $product->main_image }}"
                                       data-product-dim="{{ $product->length }}×{{ $product->width }}×{{ $product->height }} {{ $product->unit }}"
                                       data-product-material="{{ $product->material->name ?? '' }}"
                                       data-product-room="{{ $product->room->name ?? '' }}"
                                       data-product-url="{{ route('product.show', $product->slug) }}"
                                       data-product-soldout="{{ $soldOut ? '1' : '0' }}">
                                        <div class="shop-product-img-wrap">
                                            @if($soldOut)
                                                <span class="shop-product-badge badge-soldout">Sold Out</span>
                                            @elseif($lowStock)
                                                <span class="shop-product-badge badge-lowstock">Only {{ $product->stock }} left</span>
                                            @elseif($product->badge)
                                                <span class="shop-product-badge {{ $product->badge === 'preorder' ? 'badge-dark' : 'badge-caramel' }}">
                                                    {{ ucfirst($product->badge) }}
                                                </span>
                                            @endif

                                            <div class="shop-product-actions">
                                                <button type="button" class="shop-action-btn js-wishlist-btn"
                                                        data-wish-id="{{ $product->id }}"
                                                        data-wish-name="{{ $product->name }}"
                                                        title="Add to wishlist">
                                                    <i class="far fa-heart"></i>
                                                </button>
                                                <button type="button" class="shop-action-btn js-quickview-btn" title="Quick view">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>

                                            <img src="{{ $product->main_image }}" alt="{{ $product->name }}"
                                                 class="shop-product-img">

                                            @if($soldOut)
                                                <div class="shop-soldout-overlay">
                                                    <span class="shop-soldout-text">Currently Unavailable</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="shop-product-info">
                                            <small class="shop-product-cat">{{ $product->category->name }}</small>
                                            <h5 class="shop-product-name">{{ $product->name }}</h5>

                                            <div class="shop-product-bottom">
                                                <div class="shop-product-price-wrap">
                                                    <span class="shop-product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                                    @if($product->old_price)
                                                        <small class="shop-product-oldprice">Rp {{ number_format($product->old_price, 0, ',', '.') }}</small>
                                                    @endif
                                                </div>
                                                <span class="shop-product-dim">{{ $product->length }}×{{ $product->width }} {{ $product->unit }}</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="shop-empty">
                                        <i class="fas fa-search shop-empty-icon"></i>
                                        <h4 class="shop-empty-title">No products match your filters</h4>
                                        <p class="shop-empty-desc">Try adjusting your search or clearing some filters.</p>
                                        <a href="{{ route('shop') }}" class="shop-empty-btn">Reset filters</a>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        @if($products->total() > 0)
                            <div class="shop-pagination">
                                {{ $products->onEachSide(1)->links() }}
                            </div>
                        @endif
                    </div>
                </div>

            </form>

        </div>
    </section>

    {{-- ============== QUICK VIEW MODAL ============== --}}
    <div class="qv-backdrop" id="qvBackdrop">
        <div class="qv-modal" role="dialog" aria-modal="true">
            <button type="button" class="qv-close" id="qvClose"><i class="fas fa-times"></i></button>
            <div class="qv-grid">
                <div class="qv-img-wrap">
                    <img src="" alt="" id="qvImg">
                    <span class="qv-soldout-tag" id="qvSoldoutTag">Sold Out</span>
                </div>
                <div class="qv-body">
                    <small class="qv-cat" id="qvCat"></small>
                    <h3 class="qv-name" id="qvName"></h3>
                    <div class="qv-price-row">
                        <span class="qv-price" id="qvPrice"></span>
                        <span class="qv-oldprice" id="qvOldprice"></span>
                    </div>
                    <div class="qv-meta">
                        <div class="qv-meta-item">
                            <span class="qv-meta-label">Dimensions</span>
                            <span class="qv-meta-value" id="qvDim"></span>
                        </div>
                        <div class="qv-meta-item">
                            <span class="qv-meta-label">Material</span>
                            <span class="qv-meta-value" id="qvMaterial"></span>
                        </div>
                        <div class="qv-meta-item">
                            <span class="qv-meta-label">Room</span>
                            <span class="qv-meta-value" id="qvRoom"></span>
                        </div>
                    </div>
                    <p class="qv-desc">Crafted by hand in our Surabaya workshop. Solid wood, built for daily life and made to last decades.</p>
                    <div class="qv-actions">
                        <a href="#" class="qv-view-btn" id="qvViewBtn">View full details <i class="fas fa-arrow-right ms-2"></i></a>
                        <button type="button" class="qv-wish-btn" id="qvWishBtn"><i class="far fa-heart"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- WISHLIST TOAST --}}
    <div class="wish-toast" id="wishToast"><i class="fas fa-check-circle"></i> <span id="wishToastText"></span></div>

    {{-- ============== STYLES ============== --}}
    <style>
        body { background-color: var(--jaced-caramel-bg) !important; }

        /* ===== SHOP HERO (dark band biar navbar putih kebaca) ===== */
        .shop-hero {
            position: relative;
            padding: 180px 24px 80px;
            background-image: url('https://images.unsplash.com/photo-1556228720-195a672e8a03?q=80&w=1600&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            overflow: hidden;
        }
        .shop-hero-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(39,46,29,0.7) 0%, rgba(39,46,29,0.55) 100%);
            z-index: 1;
        }
        .shop-hero-content {
            position: relative;
            z-index: 2;
            color: var(--jaced-cream);
        }
        .shop-hero-label {
            font-size: 12px;
            letter-spacing: 0.3em;
            color: var(--jaced-caramel);
            text-transform: uppercase;
            margin-bottom: 16px;
            opacity: 0;
            transform: translateY(20px);
            animation: shopFadeUp 0.8s ease forwards 0.2s;
        }
        .shop-hero-title {
            font-size: clamp(2.5rem, 5vw, 4.5rem);
            font-weight: 600;
            letter-spacing: -0.04em;
            line-height: 1;
            margin: 0 0 16px;
            opacity: 0;
            transform: translateY(20px);
            animation: shopFadeUp 0.8s ease forwards 0.35s;
        }
        .shop-hero-subtitle {
            font-size: 16px;
            opacity: 0;
            transform: translateY(20px);
            animation: shopFadeUp 0.8s ease forwards 0.5s;
            margin: 0;
        }
        .shop-hero-count {
            color: var(--jaced-caramel);
            font-weight: 600;
        }
        @keyframes shopFadeUp {
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== MAIN ===== */
        .shop-main {
            padding: 48px 24px 80px;
        }
        .shop-main .container { max-width: 1320px; }

        /* ===== TOOLBAR (search + sort) ===== */
        .shop-toolbar {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }
        .shop-search-wrap {
            flex: 1;
            min-width: 240px;
            position: relative;
            background: var(--jaced-card);
            border-radius: 999px;
            border: 1px solid var(--jaced-input);
            transition: border 0.3s ease;
        }
        .shop-search-wrap:focus-within {
            border-color: var(--jaced-brown-dark);
        }
        .shop-search-icon {
            position: absolute;
            left: 22px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--jaced-muted);
            font-size: 13px;
        }
        .shop-search-input {
            background: transparent;
            border: none;
            width: 100%;
            padding: 14px 44px 14px 48px;
            font-size: 14px;
            color: var(--jaced-brown-dark);
            outline: none;
        }
        .shop-search-input::placeholder { color: var(--jaced-muted); }
        .shop-search-clear {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: var(--jaced-muted);
            font-size: 12px;
            cursor: pointer;
        }
        .shop-search-clear:hover { color: var(--jaced-brown-dark); }

        /* ===== CUSTOM SORT DROPDOWN ===== */
        .shop-sort-wrap {
            position: relative;
        }
        .shop-sort-trigger {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--jaced-card);
            border: 1px solid var(--jaced-input);
            border-radius: 999px;
            padding: 12px 20px;
            cursor: pointer;
            transition: border 0.25s ease, box-shadow 0.25s ease;
            white-space: nowrap;
        }
        .shop-sort-trigger:hover {
            border-color: var(--jaced-brown-dark);
        }
        .shop-sort-wrap.open .shop-sort-trigger {
            border-color: var(--jaced-brown-dark);
            box-shadow: 0 4px 18px rgba(39, 46, 29, 0.08);
        }
        .shop-sort-trigger-label {
            font-size: 11px;
            color: var(--jaced-muted);
            text-transform: uppercase;
            letter-spacing: 0.18em;
        }
        .shop-sort-trigger-value {
            font-size: 14px;
            color: var(--jaced-brown-dark);
            font-weight: 600;
        }
        .shop-sort-chevron {
            font-size: 11px;
            color: var(--jaced-muted);
            transition: transform 0.3s ease;
            margin-left: 4px;
        }
        .shop-sort-wrap.open .shop-sort-chevron {
            transform: rotate(180deg);
        }
        .shop-sort-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            min-width: 220px;
            background: var(--jaced-card);
            border: 1px solid var(--jaced-input);
            border-radius: 16px;
            padding: 8px;
            box-shadow: 0 16px 40px rgba(39, 46, 29, 0.12);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: opacity 0.25s ease, transform 0.25s ease, visibility 0.25s;
            z-index: 50;
        }
        .shop-sort-wrap.open .shop-sort-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .shop-sort-option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            background: transparent;
            border: none;
            text-align: left;
            padding: 11px 14px;
            font-size: 14px;
            color: var(--jaced-brown-dark);
            cursor: pointer;
            border-radius: 10px;
            transition: background 0.2s ease, color 0.2s ease;
        }
        .shop-sort-option:hover {
            background: rgba(201, 154, 107, 0.1);
        }
        .shop-sort-option.active {
            color: var(--jaced-caramel);
            font-weight: 600;
        }
        .shop-sort-option i {
            font-size: 11px;
            color: var(--jaced-caramel);
        }

        /* ===== ACTIVE FILTER CHIPS ===== */
        .shop-active-filters {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            padding: 12px 16px;
            background: var(--jaced-card);
            border-radius: 12px;
            margin-bottom: 8px;
        }
        .shop-active-label {
            font-size: 12px;
            color: var(--jaced-muted);
            text-transform: uppercase;
            letter-spacing: 0.15em;
            margin-right: 4px;
        }
        .shop-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            background: var(--jaced-brown-dark);
            color: var(--jaced-cream);
            border-radius: 999px;
            font-size: 12px;
            font-weight: 500;
        }
        .shop-chip a {
            color: var(--jaced-cream);
            opacity: 0.7;
            font-size: 10px;
            transition: opacity 0.2s ease;
        }
        .shop-chip a:hover { opacity: 1; color: var(--jaced-caramel); }
        .shop-clear-all {
            font-size: 12px;
            color: var(--jaced-brown-dark);
            text-decoration: underline;
            margin-left: 8px;
            font-weight: 500;
        }
        .shop-clear-all:hover { color: var(--jaced-caramel); }

        /* ===== SIDEBAR ===== */
        .shop-sidebar {
            background: var(--jaced-card);
            border-radius: 18px;
            padding: 28px 24px;
            position: sticky;
            top: 110px;
        }
        .shop-filter-group {
            padding-bottom: 24px;
            margin-bottom: 24px;
            border-bottom: 1px solid var(--jaced-input);
        }
        .shop-filter-group:last-of-type {
            border-bottom: none;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }
        .shop-filter-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.25em;
            color: var(--jaced-brown-dark);
            font-weight: 700;
            margin-bottom: 16px;
        }
        .shop-filter-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 0;
            cursor: pointer;
            font-size: 14px;
            color: var(--jaced-brown-dark);
            transition: color 0.2s ease;
        }
        .shop-filter-row:hover { color: var(--jaced-caramel); }
        .shop-filter-row input[type="checkbox"] {
            accent-color: var(--jaced-brown-dark);
            cursor: pointer;
            width: 16px;
            height: 16px;
        }
        .shop-filter-name { flex: 1; }
        .shop-filter-count {
            font-size: 12px;
            color: var(--jaced-muted);
        }

        /* ===== SOLD OUT STATE ===== */
        .shop-product-card.is-sold-out .shop-product-img {
            filter: grayscale(0.85) brightness(0.92);
        }
        .shop-product-card.is-sold-out:hover { transform: none; }
        .shop-product-card.is-sold-out:hover .shop-product-img { transform: none; }
        .shop-product-card.is-sold-out .shop-product-price {
            color: var(--jaced-muted);
            text-decoration: line-through;
        }
        .shop-product-card.is-sold-out .shop-product-name { color: var(--jaced-muted); }
        .badge-soldout { background: #9c3535 !important; }
        .shop-soldout-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(28, 28, 26, 0.35);
            z-index: 4;
        }
        .shop-soldout-text {
            background: rgba(242, 237, 230, 0.95);
            color: #1c1c1a;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 8px 18px;
            border-radius: 999px;
        }

        /* PRICE */
        .shop-price-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            width: 100%;
        }
        .shop-price-input {
            flex: 1 1 0;
            min-width: 0;
            width: 100%;
            background: white;
            border: 1px solid var(--jaced-input);
            border-radius: 8px;
            padding: 8px 10px;
            font-size: 13px;
            color: var(--jaced-brown-dark);
            outline: none;
            transition: border 0.2s ease;
            box-sizing: border-box;
        }
        .shop-price-input:focus { border-color: var(--jaced-brown-dark); }
        .shop-price-input::-webkit-outer-spin-button,
        .shop-price-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        .shop-price-input[type=number] { -moz-appearance: textfield; }
        .shop-price-sep {
            color: var(--jaced-muted);
            flex-shrink: 0;
        }
        .shop-apply-btn {
            width: 100%;
            background: var(--jaced-brown-dark);
            color: var(--jaced-cream);
            border: none;
            padding: 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            letter-spacing: 0.05em;
            transition: background 0.3s ease;
        }
        .shop-apply-btn:hover { background: var(--jaced-caramel); }

        .shop-reset-btn {
            display: block;
            text-align: center;
            background: transparent;
            border: 1px solid var(--jaced-input);
            color: var(--jaced-brown-dark);
            padding: 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-top: 16px;
        }
        .shop-reset-btn:hover {
            background: var(--jaced-brown-dark);
            color: var(--jaced-cream);
            border-color: var(--jaced-brown-dark);
        }

        /* ===== RESULT BAR ===== */
        .shop-result-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 16px;
        }
        .shop-result-count {
            color: var(--jaced-muted);
            font-size: 13px;
        }

        /* ===== PRODUCT CARD ===== */
        .shop-product-card {
            display: block;
            text-decoration: none;
            color: var(--jaced-brown-dark);
            transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .shop-product-card:hover {
            color: var(--jaced-brown-dark);
            transform: translateY(-6px);
        }
        .shop-product-img-wrap {
            position: relative;
            aspect-ratio: 1;
            background: var(--jaced-card);
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 14px;
        }
        .shop-product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .shop-product-card:hover .shop-product-img {
            transform: scale(1.06);
        }
        .shop-product-badge {
            position: absolute;
            top: 14px;
            left: 14px;
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--jaced-cream);
            z-index: 3;
        }
        .badge-caramel { background: var(--jaced-caramel); }
        .badge-dark { background: var(--jaced-brown-dark); }

        .shop-product-actions {
            position: absolute;
            top: 14px;
            right: 14px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            z-index: 3;
            opacity: 0;
            transform: translateX(8px);
            transition: opacity 0.35s ease, transform 0.35s ease;
        }
        .shop-product-card:hover .shop-product-actions {
            opacity: 1;
            transform: translateX(0);
        }
        .shop-action-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(242, 237, 230, 0.95);
            backdrop-filter: blur(8px);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--jaced-brown-dark);
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
        }
        .shop-action-btn:hover {
            background: var(--jaced-brown-dark);
            color: var(--jaced-cream);
            transform: scale(1.08);
        }
        .wishlist-btn.active {
            background: var(--jaced-caramel);
            color: var(--jaced-cream);
        }

        .shop-product-info {
            padding: 0 4px;
        }
        .shop-product-cat {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: var(--jaced-caramel);
            font-weight: 500;
            margin-bottom: 6px;
        }
        .shop-product-name {
            font-size: 17px;
            font-weight: 600;
            letter-spacing: -0.01em;
            margin: 0 0 12px;
            color: var(--jaced-brown-dark);
        }
        .shop-product-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
        }
        .shop-product-price {
            font-size: 15px;
            font-weight: 600;
            color: var(--jaced-brown-dark);
        }
        .shop-product-oldprice {
            color: var(--jaced-muted);
            text-decoration: line-through;
            margin-left: 4px;
            font-size: 12px;
        }
        .shop-product-dim {
            font-size: 12px;
            color: var(--jaced-muted);
            font-weight: 500;
            letter-spacing: 0.02em;
            white-space: nowrap;
        }

        /* ===== EMPTY STATE ===== */
        .shop-empty {
            text-align: center;
            padding: 80px 24px;
            background: var(--jaced-card);
            border-radius: 18px;
        }
        .shop-empty-icon {
            font-size: 40px;
            color: var(--jaced-input);
            margin-bottom: 20px;
        }
        .shop-empty-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--jaced-brown-dark);
            margin-bottom: 8px;
        }
        .shop-empty-desc {
            color: var(--jaced-muted);
            margin-bottom: 24px;
        }
        .shop-empty-btn {
            display: inline-block;
            background: var(--jaced-brown-dark);
            color: var(--jaced-cream);
            padding: 12px 28px;
            border-radius: 999px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: background 0.3s ease;
        }
        .shop-empty-btn:hover {
            background: var(--jaced-caramel);
            color: var(--jaced-cream);
        }

        /* ===== PAGINATION ===== */
        .shop-pagination {
            display: flex;
            justify-content: center;
            margin-top: 48px;
        }
        .shop-pagination .pagination {
            gap: 4px;
        }
        .shop-pagination .page-link {
            border: 1px solid var(--jaced-input);
            color: var(--jaced-brown-dark);
            background: transparent;
            border-radius: 999px !important;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 500;
            margin: 0 2px;
        }
        .shop-pagination .page-item.active .page-link {
            background: var(--jaced-brown-dark);
            border-color: var(--jaced-brown-dark);
            color: var(--jaced-cream);
        }
        .shop-pagination .page-link:hover {
            background: var(--jaced-caramel);
            border-color: var(--jaced-caramel);
            color: var(--jaced-cream);
        }
        .shop-pagination .page-item.disabled .page-link {
            color: var(--jaced-muted);
            background: transparent;
        }

        /* ===== LOW STOCK BADGE ===== */
        .badge-lowstock {
            background: #c9762b !important;
        }

        /* ===== FILTER TOGGLE BUTTON (mobile) ===== */
        .shop-filter-toggle {
            display: none;
            align-items: center;
            gap: 8px;
            background: var(--jaced-brown-dark);
            color: var(--jaced-cream);
            border: none;
            padding: 13px 22px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }

        /* ===== SIDEBAR HEAD (mobile only) ===== */
        .shop-sidebar-head {
            display: none;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--jaced-input);
        }
        .shop-sidebar-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--jaced-brown-dark);
        }
        .shop-sidebar-close {
            background: transparent;
            border: none;
            font-size: 20px;
            color: var(--jaced-brown-dark);
            cursor: pointer;
        }
        .shop-sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(28, 28, 26, 0.5);
            z-index: 1090;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .shop-sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        /* ===== QUICK VIEW MODAL ===== */
        .qv-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(28, 28, 26, 0.55);
            backdrop-filter: blur(4px);
            z-index: 1100;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s;
        }
        .qv-backdrop.active {
            opacity: 1;
            visibility: visible;
        }
        .qv-modal {
            background: var(--jaced-caramel-bg);
            border-radius: 24px;
            max-width: 880px;
            width: 100%;
            position: relative;
            overflow: hidden;
            transform: scale(0.95) translateY(12px);
            transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1);
            max-height: 90vh;
            overflow-y: auto;
        }
        .qv-backdrop.active .qv-modal {
            transform: scale(1) translateY(0);
        }
        .qv-close {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(242, 237, 230, 0.9);
            border: none;
            color: var(--jaced-brown-dark);
            font-size: 16px;
            cursor: pointer;
            z-index: 5;
            transition: background 0.2s ease;
        }
        .qv-close:hover { background: var(--jaced-cream); }
        .qv-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
        .qv-img-wrap {
            position: relative;
            aspect-ratio: 1;
            background: var(--jaced-card);
        }
        .qv-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .qv-soldout-tag {
            position: absolute;
            top: 16px;
            left: 16px;
            background: #9c3535;
            color: var(--jaced-cream);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 6px 14px;
            border-radius: 999px;
            display: none;
        }
        .qv-soldout-tag.show { display: inline-block; }
        .qv-body {
            padding: 48px 40px;
        }
        .qv-cat {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: var(--jaced-caramel);
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
        }
        .qv-name {
            font-size: 28px;
            font-weight: 600;
            letter-spacing: -0.02em;
            color: var(--jaced-brown-dark);
            margin: 0 0 16px;
        }
        .qv-price-row {
            display: flex;
            align-items: baseline;
            gap: 10px;
            margin-bottom: 28px;
        }
        .qv-price {
            font-size: 22px;
            font-weight: 700;
            color: var(--jaced-brown-dark);
        }
        .qv-oldprice {
            font-size: 15px;
            color: var(--jaced-muted);
            text-decoration: line-through;
        }
        .qv-meta {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 20px 0;
            border-top: 1px solid var(--jaced-input);
            border-bottom: 1px solid var(--jaced-input);
            margin-bottom: 24px;
        }
        .qv-meta-item {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
        }
        .qv-meta-label {
            color: var(--jaced-muted);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 11px;
        }
        .qv-meta-value {
            color: var(--jaced-brown-dark);
            font-weight: 600;
        }
        .qv-desc {
            font-size: 14px;
            line-height: 1.6;
            color: var(--jaced-muted);
            margin-bottom: 28px;
        }
        .qv-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        .qv-view-btn {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--jaced-brown-dark);
            color: var(--jaced-cream);
            text-decoration: none;
            padding: 14px 24px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        .qv-view-btn:hover {
            background: var(--jaced-caramel);
            color: var(--jaced-cream);
        }
        .qv-wish-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: transparent;
            border: 1px solid var(--jaced-input);
            color: var(--jaced-brown-dark);
            font-size: 17px;
            cursor: pointer;
            transition: all 0.25s ease;
            flex-shrink: 0;
        }
        .qv-wish-btn:hover,
        .qv-wish-btn.active {
            background: var(--jaced-caramel);
            color: var(--jaced-cream);
            border-color: var(--jaced-caramel);
        }
        @media (max-width: 768px) {
            .qv-grid { grid-template-columns: 1fr; }
            .qv-img-wrap { aspect-ratio: 16/10; }
            .qv-body { padding: 32px 24px; }
            .qv-name { font-size: 22px; }
        }

        /* ===== WISHLIST ACTIVE STATE ===== */
        .js-wishlist-btn.active {
            background: var(--jaced-caramel);
            color: var(--jaced-cream);
        }

        /* ===== WISHLIST TOAST ===== */
        .wish-toast {
            position: fixed;
            bottom: 28px;
            left: 50%;
            transform: translateX(-50%) translateY(80px);
            background: var(--jaced-brown-dark);
            color: var(--jaced-cream);
            padding: 14px 26px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1200;
            opacity: 0;
            transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.4s ease;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.2);
        }
        .wish-toast.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }
        .wish-toast i { color: #6fae6f; }

        /* ===== RESPONSIVE / DRAWER ===== */
        @media (max-width: 992px) {
            .shop-filter-toggle { display: inline-flex; }
            .shop-sidebar-head { display: flex; }
            .shop-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 320px;
                max-width: 85vw;
                z-index: 1095;
                border-radius: 0;
                overflow-y: auto;
                transform: translateX(-100%);
                transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
                margin-bottom: 0;
            }
            .shop-sidebar.open {
                transform: translateX(0);
            }
        }
        @media (max-width: 576px) {
            .shop-hero { padding: 140px 20px 60px; }
            .shop-toolbar { flex-direction: column; }
            .shop-sort-wrap { width: 100%; }
            .shop-filter-toggle { width: 100%; justify-content: center; }
        }
    </style>

    <script>
        (function () {
            const wrap = document.querySelector('.shop-sort-wrap');
            const trigger = document.getElementById('sortTrigger');
            const menu = document.getElementById('sortMenu');
            const input = document.getElementById('sort-input');
            const form = document.getElementById('filter-form');

            if (!wrap || !trigger || !menu || !input || !form) return;

            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                wrap.classList.toggle('open');
            });

            menu.querySelectorAll('.shop-sort-option').forEach(function (opt) {
                opt.addEventListener('click', function () {
                    input.value = this.getAttribute('data-value');
                    // reset page ke 1 saat ganti sort
                    let pageField = form.querySelector('input[name="page"]');
                    if (pageField) pageField.value = 1;
                    form.submit();
                });
            });

            document.addEventListener('click', function (e) {
                if (!wrap.contains(e.target)) {
                    wrap.classList.remove('open');
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') wrap.classList.remove('open');
            });
        })();

        /* ===== WISHLIST (localStorage) ===== */
        (function () {
            const KEY = 'jaced_wishlist';

            function getWishlist() {
                try { return JSON.parse(localStorage.getItem(KEY)) || []; }
                catch (e) { return []; }
            }
            function saveWishlist(list) {
                localStorage.setItem(KEY, JSON.stringify(list));
            }
            function isWished(id) {
                return getWishlist().some(function (x) { return String(x.id) === String(id); });
            }

            const toast = document.getElementById('wishToast');
            const toastText = document.getElementById('wishToastText');
            let toastTimer = null;

            function showToast(msg) {
                if (!toast) return;
                toastText.textContent = msg;
                toast.classList.add('show');
                clearTimeout(toastTimer);
                toastTimer = setTimeout(function () {
                    toast.classList.remove('show');
                }, 2500);
            }

            function setBtnState(btn, active) {
                btn.classList.toggle('active', active);
                const icon = btn.querySelector('i');
                if (icon) {
                    icon.classList.toggle('far', !active);
                    icon.classList.toggle('fas', active);
                }
            }

            function toggleWish(id, name) {
                let list = getWishlist();
                const exists = list.some(function (x) { return String(x.id) === String(id); });
                if (exists) {
                    list = list.filter(function (x) { return String(x.id) !== String(id); });
                    saveWishlist(list);
                    showToast(name + ' removed from wishlist');
                    return false;
                } else {
                    list.push({ id: id, name: name });
                    saveWishlist(list);
                    showToast(name + ' added to wishlist');
                    return true;
                }
            }

            // Init all card wishlist buttons
            document.querySelectorAll('.js-wishlist-btn').forEach(function (btn) {
                const id = btn.getAttribute('data-wish-id');
                setBtnState(btn, isWished(id));

                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const name = btn.getAttribute('data-wish-name');
                    const nowActive = toggleWish(id, name);
                    setBtnState(btn, nowActive);
                    syncQvWishBtn(id);
                });
            });

            /* ===== QUICK VIEW MODAL ===== */
            const backdrop = document.getElementById('qvBackdrop');
            const qvClose = document.getElementById('qvClose');
            let currentQvId = null;

            function syncQvWishBtn(id) {
                const qvWish = document.getElementById('qvWishBtn');
                if (!qvWish || String(currentQvId) !== String(id)) return;
                setBtnState(qvWish, isWished(id));
            }

            function openQuickView(card) {
                currentQvId = card.getAttribute('data-product-id');
                document.getElementById('qvImg').src = card.getAttribute('data-product-img');
                document.getElementById('qvImg').alt = card.getAttribute('data-product-name');
                document.getElementById('qvCat').textContent = card.getAttribute('data-product-cat');
                document.getElementById('qvName').textContent = card.getAttribute('data-product-name');
                document.getElementById('qvPrice').textContent = 'Rp ' + card.getAttribute('data-product-price');

                const oldP = card.getAttribute('data-product-oldprice');
                const oldEl = document.getElementById('qvOldprice');
                if (oldP) { oldEl.textContent = 'Rp ' + oldP; oldEl.style.display = 'inline'; }
                else { oldEl.style.display = 'none'; }

                document.getElementById('qvDim').textContent = card.getAttribute('data-product-dim');
                document.getElementById('qvMaterial').textContent = card.getAttribute('data-product-material') || '-';
                document.getElementById('qvRoom').textContent = card.getAttribute('data-product-room') || '-';
                document.getElementById('qvViewBtn').href = card.getAttribute('data-product-url');

                const soldTag = document.getElementById('qvSoldoutTag');
                soldTag.classList.toggle('show', card.getAttribute('data-product-soldout') === '1');

                const qvWish = document.getElementById('qvWishBtn');
                setBtnState(qvWish, isWished(currentQvId));

                backdrop.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeQuickView() {
                backdrop.classList.remove('active');
                document.body.style.overflow = '';
            }

            document.querySelectorAll('.js-quickview-btn').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const card = btn.closest('.shop-product-card');
                    if (card) openQuickView(card);
                });
            });

            if (qvClose) qvClose.addEventListener('click', closeQuickView);
            if (backdrop) {
                backdrop.addEventListener('click', function (e) {
                    if (e.target === backdrop) closeQuickView();
                });
            }
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeQuickView();
            });

            const qvWishBtn = document.getElementById('qvWishBtn');
            if (qvWishBtn) {
                qvWishBtn.addEventListener('click', function () {
                    if (!currentQvId) return;
                    const name = document.getElementById('qvName').textContent;
                    const nowActive = toggleWish(currentQvId, name);
                    setBtnState(qvWishBtn, nowActive);
                    // sync card button
                    const cardBtn = document.querySelector('.js-wishlist-btn[data-wish-id="' + currentQvId + '"]');
                    if (cardBtn) setBtnState(cardBtn, nowActive);
                });
            }
        })();

        /* ===== MOBILE FILTER DRAWER ===== */
        (function () {
            const toggle = document.getElementById('filterToggle');
            const sidebar = document.getElementById('shopSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const closeBtn = document.getElementById('sidebarClose');

            if (!toggle || !sidebar || !overlay) return;

            function openDrawer() {
                sidebar.classList.add('open');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
            function closeDrawer() {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            toggle.addEventListener('click', openDrawer);
            overlay.addEventListener('click', closeDrawer);
            if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeDrawer();
            });
        })();
    </script>

@endsection