@extends('base.base')

@section('title', 'Shop — JACED Furniture')

@section('content')

    {{-- ============== SHOP HERO ============== --}}
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

                {{-- ===== ROW 1: SEARCH (full width) ===== --}}
                <div class="shop-search-wrap">
                    <i class="fas fa-search shop-search-icon"></i>
                    <input type="text" id="shopSearchInput" name="search"
                           class="shop-search-input"
                           placeholder="Search nora chair, oka table, walnut..."
                           value="{{ request('search') }}">
                    @if(request('search'))
                        <button type="button" class="shop-search-clear"
                                onclick="this.previousElementSibling.value=''; document.getElementById('filter-form').submit();">
                            <i class="fas fa-times"></i>
                        </button>
                    @endif
                </div>

                {{-- ===== ROW 2: FILTER DROPDOWNS ===== --}}
                <div class="shop-filters-row">

                    {{-- SORT --}}
                    @php
                        $sortLabels = [
                            ''           => 'Recommended',
                            'newest'     => 'Newest first',
                            'price_asc'  => 'Price: Low to High',
                            'price_desc' => 'Price: High to Low',
                            'bestseller' => 'Best sellers',
                        ];
                        $currentSort = request('sort', '');
                        $currentSortLabel = $sortLabels[$currentSort] ?? 'Recommended';
                    @endphp
                    <input type="hidden" name="sort" id="sort-input" value="{{ $currentSort }}">
                    <div class="shop-filter-pill-wrap" id="sortWrap">
                        <button type="button" class="shop-filter-pill {{ $currentSort ? 'active-pill' : '' }}" id="sortTrigger">
                            <span class="pill-label">Sort</span>
                            <span class="pill-value">{{ $currentSortLabel }}</span>
                            <i class="fas fa-chevron-down pill-chevron"></i>
                        </button>
                        <div class="shop-filter-dropdown" id="sortMenu">
                            @foreach($sortLabels as $val => $label)
                                <button type="button" class="shop-dd-option {{ $currentSort === $val ? 'active' : '' }}" data-value="{{ $val }}">
                                    {{ $label }}
                                    @if($currentSort === $val)<i class="fas fa-check"></i>@endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- CATEGORY --}}
                    @php $activeCats = (array) request('category', []); @endphp
                    <div class="shop-filter-pill-wrap" id="catWrap">
                        <button type="button" class="shop-filter-pill {{ count($activeCats) ? 'active-pill' : '' }}" id="catTrigger">
                            <span class="pill-label">Category</span>
                            <span class="pill-value">{{ count($activeCats) ? implode(', ', array_map('ucfirst', $activeCats)) : 'All' }}</span>
                            <i class="fas fa-chevron-down pill-chevron"></i>
                        </button>
                        <div class="shop-filter-dropdown" id="catMenu">
                            <button type="button" class="shop-dd-option {{ !count($activeCats) ? 'active' : '' }}"
                                    onclick="setFilter('category', [])">
                                All @if(!count($activeCats))<i class="fas fa-check"></i>@endif
                            </button>
                            @foreach($categories as $cat)
                                <button type="button"
                                        class="shop-dd-option {{ in_array($cat->slug, $activeCats) ? 'active' : '' }}"
                                        onclick="setFilter('category', ['{{ $cat->slug }}'])">
                                    {{ $cat->name }}
                                    @if(in_array($cat->slug, $activeCats))<i class="fas fa-check"></i>@endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- MATERIAL --}}
                    @php $activeMats = (array) request('material', []); @endphp
                    <div class="shop-filter-pill-wrap" id="matWrap">
                        <button type="button" class="shop-filter-pill {{ count($activeMats) ? 'active-pill' : '' }}" id="matTrigger">
                            <span class="pill-label">Material</span>
                            <span class="pill-value">{{ count($activeMats) ? implode(', ', array_map('ucfirst', $activeMats)) : 'All' }}</span>
                            <i class="fas fa-chevron-down pill-chevron"></i>
                        </button>
                        <div class="shop-filter-dropdown" id="matMenu">
                            <button type="button" class="shop-dd-option {{ !count($activeMats) ? 'active' : '' }}"
                                    onclick="setFilter('material', [])">
                                All @if(!count($activeMats))<i class="fas fa-check"></i>@endif
                            </button>
                            @foreach($materials as $mat)
                                <button type="button"
                                        class="shop-dd-option {{ in_array($mat->slug, $activeMats) ? 'active' : '' }}"
                                        onclick="setFilter('material', ['{{ $mat->slug }}'])">
                                    {{ $mat->name }}
                                    @if(in_array($mat->slug, $activeMats))<i class="fas fa-check"></i>@endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- SIZE --}}
                    @php
                        $sizeOptions = [
                            ['slug' => 'small',  'name' => 'Small',  'note' => 'Compact pieces'],
                            ['slug' => 'medium', 'name' => 'Medium', 'note' => 'Standard size'],
                            ['slug' => 'large',  'name' => 'Large',  'note' => 'Statement pieces'],
                        ];
                        $activeSizes = (array) request('size', []);
                    @endphp
                    <div class="shop-filter-pill-wrap" id="sizeWrap">
                        <button type="button" class="shop-filter-pill {{ count($activeSizes) ? 'active-pill' : '' }}" id="sizeTrigger">
                            <span class="pill-label">Size</span>
                            <span class="pill-value">{{ count($activeSizes) ? implode(', ', array_map('ucfirst', $activeSizes)) : 'All' }}</span>
                            <i class="fas fa-chevron-down pill-chevron"></i>
                        </button>
                        <div class="shop-filter-dropdown" id="sizeMenu">
                            <button type="button" class="shop-dd-option {{ !count($activeSizes) ? 'active' : '' }}"
                                    onclick="setFilter('size', [])">
                                All @if(!count($activeSizes))<i class="fas fa-check"></i>@endif
                            </button>
                            @foreach($sizeOptions as $sz)
                                <button type="button"
                                        class="shop-dd-option {{ in_array($sz['slug'], $activeSizes) ? 'active' : '' }}"
                                        onclick="setFilter('size', ['{{ $sz['slug'] }}'])">
                                    {{ $sz['name'] }}
                                    @if(in_array($sz['slug'], $activeSizes))<i class="fas fa-check"></i>@endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- PRICE --}}
                    @php $hasPriceFilter = request('min_price') || request('max_price'); @endphp
                    <div class="shop-filter-pill-wrap" id="priceWrap">
                        <button type="button" class="shop-filter-pill {{ $hasPriceFilter ? 'active-pill' : '' }}" id="priceTrigger">
                            <span class="pill-label">Price</span>
                            <span class="pill-value">
                                {{ $hasPriceFilter
                                    ? 'Rp ' . (request('min_price') ? number_format(request('min_price'),0,',','.') : '0')
                                      . ' – '
                                      . (request('max_price') ? number_format(request('max_price'),0,',','.') : '∞')
                                    : 'Any' }}
                            </span>
                            <i class="fas fa-chevron-down pill-chevron"></i>
                        </button>
                        <div class="shop-filter-dropdown price-dropdown" id="priceMenu">
                            <button type="button" class="shop-dd-option {{ !request('min_price') && !request('max_price') ? 'active' : '' }}"
                                    onclick="setPriceFilter('', '')">
                                Any @if(!request('min_price') && !request('max_price'))<i class="fas fa-check"></i>@endif
                            </button>
                            <button type="button" class="shop-dd-option {{ request('max_price') == 1000000 && !request('min_price') ? 'active' : '' }}"
                                    onclick="setPriceFilter(0, 1000000)">
                                Under Rp 1.000.000 @if(request('max_price') == 1000000 && !request('min_price'))<i class="fas fa-check"></i>@endif
                            </button>
                            <button type="button" class="shop-dd-option {{ request('min_price') == 1000000 && request('max_price') == 5000000 ? 'active' : '' }}"
                                    onclick="setPriceFilter(1000000, 5000000)">
                                Rp 1.000.000 – 5.000.000 @if(request('min_price') == 1000000 && request('max_price') == 5000000)<i class="fas fa-check"></i>@endif
                            </button>
                            <button type="button" class="shop-dd-option {{ request('min_price') == 5000000 && request('max_price') == 15000000 ? 'active' : '' }}"
                                    onclick="setPriceFilter(5000000, 15000000)">
                                Rp 5.000.000 – 15.000.000 @if(request('min_price') == 5000000 && request('max_price') == 15000000)<i class="fas fa-check"></i>@endif
                            </button>
                            <button type="button" class="shop-dd-option {{ request('min_price') == 15000000 && request('max_price') == 30000000 ? 'active' : '' }}"
                                    onclick="setPriceFilter(15000000, 30000000)">
                                Rp 15.000.000 – 30.000.000 @if(request('min_price') == 15000000 && request('max_price') == 30000000)<i class="fas fa-check"></i>@endif
                            </button>
                            <button type="button" class="shop-dd-option {{ request('min_price') == 30000000 && !request('max_price') ? 'active' : '' }}"
                                    onclick="setPriceFilter(30000000, '')">
                                Above Rp 30.000.000 @if(request('min_price') == 30000000 && !request('max_price'))<i class="fas fa-check"></i>@endif
                            </button>
                        </div>
                    </div>

                    {{-- CLEAR ALL --}}
                    @if(request('search') || request('category') || request('material') || request('size') || request('min_price') || request('max_price') || request('sort'))
                        <a href="{{ route('shop') }}" class="shop-clear-btn">
                            <i class="fas fa-times me-1"></i> Clear all
                        </a>
                    @endif

                </div>

                {{-- ===== ACTIVE FILTER CHIPS ===== --}}
                @php
                    $hasActiveFilters = request('search') || request('category') || request('material') || request('room') || request('size') || request('min_price') || request('max_price') || request('sort');
                @endphp
                @if($hasActiveFilters)
                    <div class="shop-active-filters">
                        <span class="shop-active-label">Active:</span>
                        @if(request('search'))
                            <span class="shop-chip">Search: "{{ request('search') }}"
                                <a href="{{ route('shop', array_merge(request()->except(['search', 'page']))) }}"><i class="fas fa-times"></i></a>
                            </span>
                        @endif
                        @foreach((array) request('category', []) as $catSlug)
                            @php $catItem = $categories->firstWhere('slug', $catSlug); @endphp
                            @if($catItem)
                                <span class="shop-chip">{{ $catItem->name }}
                                    <a href="{{ route('shop', array_merge(request()->except('page'), ['category' => array_values(array_diff((array) request('category', []), [$catSlug]))])) }}"><i class="fas fa-times"></i></a>
                                </span>
                            @endif
                        @endforeach
                        @foreach((array) request('material', []) as $matSlug)
                            @php $matItem = $materials->firstWhere('slug', $matSlug); @endphp
                            @if($matItem)
                                <span class="shop-chip">{{ $matItem->name }}
                                    <a href="{{ route('shop', array_merge(request()->except('page'), ['material' => array_values(array_diff((array) request('material', []), [$matSlug]))])) }}"><i class="fas fa-times"></i></a>
                                </span>
                            @endif
                        @endforeach
                        @foreach((array) request('size', []) as $sizeSlug)
                            <span class="shop-chip">Size: {{ ucfirst($sizeSlug) }}
                                <a href="{{ route('shop', array_merge(request()->except('page'), ['size' => array_values(array_diff((array) request('size', []), [$sizeSlug]))])) }}"><i class="fas fa-times"></i></a>
                            </span>
                        @endforeach
                        @if(request('min_price') || request('max_price'))
                            <span class="shop-chip">
                                Rp {{ request('min_price') ? number_format(request('min_price'),0,',','.') : '0' }} – {{ request('max_price') ? number_format(request('max_price'),0,',','.') : '∞' }}
                                <a href="{{ route('shop', array_merge(request()->except(['min_price','max_price','page']))) }}"><i class="fas fa-times"></i></a>
                            </span>
                        @endif
                    </div>
                @endif

                {{-- ===== RESULT BAR ===== --}}
                <div class="shop-result-bar">
                    <small class="shop-result-count">
                        Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}
                        of {{ $products->total() }} products
                    </small>
                </div>

                {{-- ===== PRODUCT GRID (4 col) ===== --}}
                <div class="row g-4">
                    @forelse($products as $product)
                        @php
                            $soldOut  = ($product->stock ?? 1) <= 0;
                            $lowStock = !$soldOut && ($product->stock ?? 99) <= 3;
                        @endphp
                        <div class="col-6 col-md-4 col-lg-3">
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

                                    <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="shop-product-img">

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
                        {{ $products->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @endif

            </form>
        </div>
    </section>

    {{-- QUICK VIEW MODAL --}}
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
                        <a href="#" class="qv-view-btn" id="qvViewBtn">See details <i class="fas fa-arrow-right ms-2"></i></a>
                        <button type="button" class="qv-wish-btn" id="qvWishBtn"><i class="far fa-heart"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="wish-toast" id="wishToast"><i class="fas fa-check-circle"></i> <span id="wishToastText"></span></div>

    <div class="shop-confirm-backdrop" id="shopConfirmBackdrop">
        <div class="shop-confirm-modal">
            <div class="shop-confirm-icon"><i class="fas fa-heart-crack"></i></div>
            <h4 class="shop-confirm-title">Remove from Wishlist?</h4>
            <p class="shop-confirm-msg" id="shopConfirmMsg"></p>
            <div class="shop-confirm-actions">
                <button class="shop-confirm-cancel" id="shopConfirmCancel">Keep it</button>
                <button class="shop-confirm-ok" id="shopConfirmOk">Remove</button>
            </div>
        </div>
    </div>

    <style>
        body { background-color: var(--jaced-caramel-bg) !important; }

        /* ===== HERO ===== */
        .shop-hero {
            position: relative; padding: 180px 24px 80px;
            background-image: url('https://images.unsplash.com/photo-1556228720-195a672e8a03?q=80&w=1600&auto=format&fit=crop');
            background-size: cover; background-position: center; overflow: hidden;
        }
        .shop-hero-overlay { position: absolute; inset: 0; background: linear-gradient(180deg, rgba(39,46,29,0.7) 0%, rgba(39,46,29,0.55) 100%); z-index: 1; }
        .shop-hero-content { position: relative; z-index: 2; color: var(--jaced-cream); }
        .shop-hero-label { font-size: 12px; letter-spacing: 0.3em; color: var(--jaced-caramel); text-transform: uppercase; margin-bottom: 16px; opacity: 0; transform: translateY(20px); animation: shopFadeUp 0.8s ease forwards 0.2s; }
        .shop-hero-title { font-size: clamp(2.5rem, 5vw, 4.5rem); font-weight: 600; letter-spacing: -0.04em; line-height: 1; margin: 0 0 16px; opacity: 0; transform: translateY(20px); animation: shopFadeUp 0.8s ease forwards 0.35s; }
        .shop-hero-subtitle { font-size: 16px; opacity: 0; transform: translateY(20px); animation: shopFadeUp 0.8s ease forwards 0.5s; margin: 0; }
        .shop-hero-count { color: var(--jaced-caramel); font-weight: 600; }
        @keyframes shopFadeUp { to { opacity: 1; transform: translateY(0); } }

        /* ===== MAIN ===== */
        .shop-main { padding: 40px 24px 80px; }
        .shop-main .container { max-width: 1320px; }

        /* ===== SEARCH (full width) ===== */
        .shop-search-wrap {
            position: relative; background: var(--jaced-card);
            border-radius: 999px; border: 1px solid var(--jaced-input);
            transition: border 0.3s ease; margin-bottom: 14px;
        }
        .shop-search-wrap:focus-within { border-color: var(--jaced-brown-dark); }
        .shop-search-icon { position: absolute; left: 22px; top: 50%; transform: translateY(-50%); color: var(--jaced-muted); font-size: 13px; }
        .shop-search-input { background: transparent; border: none; width: 100%; padding: 14px 44px 14px 48px; font-size: 14px; color: var(--jaced-brown-dark); outline: none; }
        .shop-search-input::placeholder { color: var(--jaced-muted); }
        .shop-search-clear { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); background: transparent; border: none; color: var(--jaced-muted); font-size: 12px; cursor: pointer; }
        .shop-search-clear:hover { color: var(--jaced-brown-dark); }

        /* ===== FILTER ROW ===== */
        .shop-filters-row {
            display: flex; gap: 10px; flex-wrap: wrap;
            align-items: center; justify-content: center;
            margin-bottom: 16px;
        }

        /* ===== FILTER PILLS ===== */
        .shop-filter-pill-wrap { position: relative; }
        .shop-filter-pill {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--jaced-card); border: 1px solid var(--jaced-input);
            border-radius: 999px; padding: 9px 16px; cursor: pointer;
            transition: border 0.25s ease, box-shadow 0.25s ease; white-space: nowrap;
            font-size: 13px;
        }
        .shop-filter-pill:hover { border-color: var(--jaced-brown-dark); }
        .shop-filter-pill.active-pill { background: var(--jaced-brown-dark); border-color: var(--jaced-brown-dark); }
        .shop-filter-pill.active-pill .pill-label,
        .shop-filter-pill.active-pill .pill-value { color: var(--jaced-cream); }
        .shop-filter-pill.active-pill .pill-chevron { color: rgba(242,237,230,0.7); }
        .shop-filter-pill-wrap.open .shop-filter-pill { border-color: var(--jaced-brown-dark); box-shadow: 0 4px 18px rgba(39,46,29,0.08); }
        .pill-label { font-size: 10px; color: var(--jaced-muted); text-transform: uppercase; letter-spacing: 0.15em; }
        .pill-value { font-size: 13px; color: var(--jaced-brown-dark); font-weight: 600; max-width: 140px; overflow: hidden; text-overflow: ellipsis; }
        .pill-chevron { font-size: 10px; color: var(--jaced-muted); transition: transform 0.3s ease; }
        .shop-filter-pill-wrap.open .pill-chevron { transform: rotate(180deg); }

        /* ===== FILTER DROPDOWN ===== */
        .shop-filter-dropdown {
            position: absolute; top: calc(100% + 8px); left: 0;
            min-width: 220px; background: var(--jaced-card);
            border: 1px solid var(--jaced-input); border-radius: 16px;
            padding: 8px; box-shadow: 0 16px 40px rgba(39,46,29,0.12);
            opacity: 0; visibility: hidden; transform: translateY(-8px);
            transition: opacity 0.25s ease, transform 0.25s ease, visibility 0.25s;
            z-index: 200;
        }
        .shop-filter-pill-wrap.open .shop-filter-dropdown { opacity: 1; visibility: visible; transform: translateY(0); }
        .shop-dd-option {
            display: flex; align-items: center; justify-content: space-between;
            width: 100%; background: transparent; border: none; text-align: left;
            padding: 10px 14px; font-size: 13px; color: var(--jaced-brown-dark);
            cursor: pointer; border-radius: 10px; transition: background 0.2s ease;
        }
        .shop-dd-option:hover { background: rgba(201,154,107,0.1); }
        .shop-dd-option.active { color: var(--jaced-caramel); font-weight: 600; }
        .shop-dd-option i { font-size: 10px; color: var(--jaced-caramel); }

        /* Clear all button */
        .shop-clear-btn {
            display: inline-flex; align-items: center;
            padding: 9px 16px; border-radius: 999px;
            font-size: 13px; font-weight: 500; color: var(--jaced-muted);
            text-decoration: none; border: 1px solid var(--jaced-input);
            transition: all 0.2s ease;
        }
        .shop-clear-btn:hover { background: #9c3535; border-color: #9c3535; color: white; }

        /* ===== ACTIVE FILTER CHIPS ===== */
        .shop-active-filters { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; padding: 12px 16px; background: var(--jaced-card); border-radius: 12px; margin-bottom: 16px; }
        .shop-active-label { font-size: 11px; color: var(--jaced-muted); text-transform: uppercase; letter-spacing: 0.15em; }
        .shop-chip { display: inline-flex; align-items: center; gap: 8px; padding: 5px 12px; background: var(--jaced-brown-dark); color: var(--jaced-cream); border-radius: 999px; font-size: 12px; font-weight: 500; }
        .shop-chip a { color: var(--jaced-cream); opacity: 0.7; font-size: 10px; }
        .shop-chip a:hover { opacity: 1; }

        /* ===== RESULT BAR ===== */
        .shop-result-bar { display: flex; justify-content: flex-end; margin-bottom: 16px; }
        .shop-result-count { color: var(--jaced-muted); font-size: 13px; }

        /* ===== PRODUCT CARD ===== */
        .shop-product-card {
            display: block; text-decoration: none; color: var(--jaced-brown-dark);
            transition: transform 0.4s cubic-bezier(0.22,1,0.36,1);
            position: relative; border-radius: 20px;
        }
        .shop-product-card::before {
            content: ''; position: absolute; inset: -6px; border-radius: 24px;
            background: linear-gradient(135deg, var(--jaced-caramel), var(--jaced-brown-dark), var(--jaced-caramel));
            background-size: 200% 200%; opacity: 0; transition: opacity 0.4s ease;
            z-index: -1; animation: borderRotate 3s linear infinite paused;
        }
        .shop-product-card:hover::before { opacity: 1; animation-play-state: running; }
        @keyframes borderRotate { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        .shop-product-card:hover { color: var(--jaced-brown-dark); transform: translateY(-6px); }
        .shop-product-img-wrap {
            position: relative; aspect-ratio: 1; background: var(--jaced-card);
            border-radius: 16px; overflow: hidden; margin-bottom: 14px;
            border: 2px solid transparent;
            transition: border-color 0.4s ease, box-shadow 0.4s ease;
        }
        .shop-product-card:hover .shop-product-img-wrap {
            border-color: var(--jaced-caramel);
            box-shadow: 0 0 0 6px rgba(201,154,107,0.18), 0 16px 40px rgba(39,46,29,0.12);
        }
        .shop-product-name, .shop-product-price, .shop-product-dim, .shop-product-cat { transition: color 0.4s ease; }
        .shop-product-card:hover .shop-product-name { color: var(--jaced-cream); }
        .shop-product-card:hover .shop-product-cat { color: var(--jaced-caramel); }
        .shop-product-card:hover .shop-product-price { color: var(--jaced-cream); }
        .shop-product-card:hover .shop-product-dim { color: rgba(242,237,230,0.6); }

        .shop-product-card.is-sold-out .shop-product-img { filter: grayscale(0.85) brightness(0.92); }
        .shop-product-card.is-sold-out:hover { transform: none; }
        .shop-product-card.is-sold-out .shop-product-price { color: var(--jaced-muted); text-decoration: line-through; }
        .shop-product-card.is-sold-out .shop-product-name { color: var(--jaced-muted); }
        .badge-soldout { background: #9c3535 !important; }
        .shop-soldout-overlay { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(28,28,26,0.35); z-index: 4; }
        .shop-soldout-text { background: rgba(242,237,230,0.95); color: #1c1c1a; font-size: 12px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; padding: 8px 18px; border-radius: 999px; }

        .shop-product-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s cubic-bezier(0.22,1,0.36,1); }
        .shop-product-card:hover .shop-product-img { transform: scale(1.06); }
        .shop-product-badge { position: absolute; top: 12px; left: 12px; padding: 4px 10px; border-radius: 999px; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: var(--jaced-cream); z-index: 3; }
        .badge-caramel { background: var(--jaced-caramel); }
        .badge-dark { background: var(--jaced-brown-dark); }
        .badge-lowstock { background: #c9762b !important; }

        .shop-product-actions { position: absolute; top: 12px; right: 12px; display: flex; flex-direction: column; gap: 8px; z-index: 3; opacity: 0; transform: translateX(8px); transition: opacity 0.35s ease, transform 0.35s ease; }
        .shop-product-card:hover .shop-product-actions { opacity: 1; transform: translateX(0); }
        .shop-action-btn { width: 34px; height: 34px; border-radius: 50%; background: rgba(242,237,230,0.95); backdrop-filter: blur(8px); border: none; display: flex; align-items: center; justify-content: center; color: var(--jaced-brown-dark); font-size: 13px; cursor: pointer; transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease; }
        .shop-action-btn:hover { background: var(--jaced-brown-dark); color: var(--jaced-cream); transform: scale(1.08); }

        .js-wishlist-btn i { transition: all 0.25s ease; }
        .js-wishlist-btn.active { background: var(--jaced-caramel); color: var(--jaced-cream); }
        .js-wishlist-btn.active:hover { background: rgba(156,53,53,0.15); color: #9c3535; }
        .js-wishlist-btn.active .fa-heart::before { content: "\f004"; }
        .js-wishlist-btn.active:hover .fa-heart::before { content: "\f7a9"; }

        .shop-product-info { padding: 0 4px; }
        .shop-product-cat { display: block; font-size: 10px; text-transform: uppercase; letter-spacing: 0.2em; color: var(--jaced-caramel); font-weight: 500; margin-bottom: 5px; }
        .shop-product-name { font-size: 15px; font-weight: 600; letter-spacing: -0.01em; margin: 0 0 10px; color: var(--jaced-brown-dark); line-height: 1.3; }
        .shop-product-bottom { display: flex; justify-content: space-between; align-items: center; gap: 8px; }
        .shop-product-price { font-size: 14px; font-weight: 600; color: var(--jaced-brown-dark); }
        .shop-product-oldprice { color: var(--jaced-muted); text-decoration: line-through; margin-left: 4px; font-size: 11px; }
        .shop-product-dim { font-size: 11px; color: var(--jaced-muted); font-weight: 500; white-space: nowrap; }

        /* ===== EMPTY STATE ===== */
        .shop-empty { text-align: center; padding: 80px 24px; background: var(--jaced-card); border-radius: 18px; }
        .shop-empty-icon { font-size: 40px; color: var(--jaced-input); margin-bottom: 20px; }
        .shop-empty-title { font-size: 20px; font-weight: 600; color: var(--jaced-brown-dark); margin-bottom: 8px; }
        .shop-empty-desc { color: var(--jaced-muted); margin-bottom: 24px; }
        .shop-empty-btn { display: inline-block; background: var(--jaced-brown-dark); color: var(--jaced-cream); padding: 12px 28px; border-radius: 999px; text-decoration: none; font-size: 13px; font-weight: 500; transition: background 0.3s ease; }
        .shop-empty-btn:hover { background: var(--jaced-caramel); color: var(--jaced-cream); }

        /* ===== PAGINATION ===== */
        .shop-pagination { display: flex; justify-content: center; margin-top: 48px; }
        .shop-pagination .pagination { gap: 4px; align-items: center; flex-wrap: wrap; justify-content: center; }
        .shop-pagination .page-link { border: 1px solid var(--jaced-input); color: var(--jaced-brown-dark); background: transparent; border-radius: 999px !important; padding: 8px 16px; font-size: 13px; font-weight: 500; margin: 0 2px; min-width: 40px; text-align: center; line-height: 1.5; display: flex; align-items: center; justify-content: center; }
        .shop-pagination .page-item.active .page-link { background: var(--jaced-brown-dark); border-color: var(--jaced-brown-dark); color: var(--jaced-cream); }
        .shop-pagination .page-link:hover { background: var(--jaced-caramel); border-color: var(--jaced-caramel); color: var(--jaced-cream); }
        .shop-pagination .page-item.disabled .page-link { color: var(--jaced-muted); opacity: 0.5; }

        /* ===== QUICK VIEW ===== */
        .qv-backdrop { position: fixed; inset: 0; background: rgba(28,28,26,0.55); backdrop-filter: blur(4px); z-index: 1100; display: flex; align-items: center; justify-content: center; padding: 24px; opacity: 0; visibility: hidden; transition: opacity 0.3s ease, visibility 0.3s; }
        .qv-backdrop.active { opacity: 1; visibility: visible; }
        .qv-modal { background: var(--jaced-caramel-bg); border-radius: 24px; max-width: 880px; width: 100%; position: relative; overflow: hidden; transform: scale(0.95) translateY(12px); transition: transform 0.35s cubic-bezier(0.22,1,0.36,1); max-height: 90vh; overflow-y: auto; }
        .qv-backdrop.active .qv-modal { transform: scale(1) translateY(0); }
        .qv-close { position: absolute; top: 16px; right: 16px; width: 38px; height: 38px; border-radius: 50%; background: rgba(242,237,230,0.9); border: none; color: var(--jaced-brown-dark); font-size: 16px; cursor: pointer; z-index: 5; transition: background 0.2s ease; }
        .qv-close:hover { background: var(--jaced-cream); }
        .qv-grid { display: grid; grid-template-columns: 1fr 1fr; }
        .qv-img-wrap { position: relative; aspect-ratio: 1; background: var(--jaced-card); }
        .qv-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
        .qv-soldout-tag { position: absolute; top: 16px; left: 16px; background: #9c3535; color: var(--jaced-cream); font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; padding: 6px 14px; border-radius: 999px; display: none; }
        .qv-soldout-tag.show { display: inline-block; }
        .qv-body { padding: 48px 40px; }
        .qv-cat { font-size: 11px; text-transform: uppercase; letter-spacing: 0.2em; color: var(--jaced-caramel); font-weight: 600; display: block; margin-bottom: 8px; }
        .qv-name { font-size: 28px; font-weight: 600; letter-spacing: -0.02em; color: var(--jaced-brown-dark); margin: 0 0 16px; }
        .qv-price-row { display: flex; align-items: baseline; gap: 10px; margin-bottom: 28px; }
        .qv-price { font-size: 22px; font-weight: 700; color: var(--jaced-brown-dark); }
        .qv-oldprice { font-size: 15px; color: var(--jaced-muted); text-decoration: line-through; }
        .qv-meta { display: flex; flex-direction: column; gap: 12px; padding: 20px 0; border-top: 1px solid var(--jaced-input); border-bottom: 1px solid var(--jaced-input); margin-bottom: 24px; }
        .qv-meta-item { display: flex; justify-content: space-between; font-size: 14px; }
        .qv-meta-label { color: var(--jaced-muted); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; }
        .qv-meta-value { color: var(--jaced-brown-dark); font-weight: 600; }
        .qv-desc { font-size: 14px; line-height: 1.6; color: var(--jaced-muted); margin-bottom: 28px; }
        .qv-actions { display: flex; gap: 12px; align-items: center; }
        .qv-view-btn { flex: 1; display: inline-flex; align-items: center; justify-content: center; background: var(--jaced-brown-dark); color: var(--jaced-cream); text-decoration: none; padding: 14px 24px; border-radius: 999px; font-size: 14px; font-weight: 600; transition: background 0.3s ease; }
        .qv-view-btn:hover { background: var(--jaced-caramel); color: var(--jaced-cream); }
        .qv-wish-btn { width: 50px; height: 50px; border-radius: 50%; background: transparent; border: 1px solid var(--jaced-input); color: var(--jaced-brown-dark); font-size: 17px; cursor: pointer; transition: all 0.25s ease; flex-shrink: 0; }
        .qv-wish-btn:hover, .qv-wish-btn.active { background: var(--jaced-caramel); color: var(--jaced-cream); border-color: var(--jaced-caramel); }
        .qv-wish-btn.active:hover { background: rgba(156,53,53,0.15); color: #9c3535; border-color: rgba(156,53,53,0.3); }
        .qv-wish-btn.active .fa-heart::before { content: "\f004"; }
        .qv-wish-btn.active:hover .fa-heart::before { content: "\f7a9"; }
        @media (max-width: 768px) { .qv-grid { grid-template-columns: 1fr; } .qv-img-wrap { aspect-ratio: 16/10; } .qv-body { padding: 32px 24px; } .qv-name { font-size: 22px; } }

        /* ===== TOAST ===== */
        .wish-toast { position: fixed; bottom: 28px; left: 50%; transform: translateX(-50%) translateY(80px); background: var(--jaced-brown-dark); color: var(--jaced-cream); padding: 14px 26px; border-radius: 999px; font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 10px; z-index: 1200; opacity: 0; transition: transform 0.4s cubic-bezier(0.22,1,0.36,1), opacity 0.4s ease; box-shadow: 0 12px 32px rgba(0,0,0,0.2); }
        .wish-toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }
        .wish-toast i { color: #6fae6f; }

        /* ===== CONFIRM POPUP ===== */
        .shop-confirm-backdrop { position: fixed; inset: 0; background: rgba(28,28,26,0.5); backdrop-filter: blur(4px); z-index: 1300; display: flex; align-items: center; justify-content: center; opacity: 0; visibility: hidden; transition: opacity 0.3s ease, visibility 0.3s; }
        .shop-confirm-backdrop.show { opacity: 1; visibility: visible; }
        .shop-confirm-modal { background: var(--jaced-caramel-bg); border-radius: 24px; padding: 40px 36px; max-width: 380px; width: 90%; text-align: center; transform: scale(0.92) translateY(12px); transition: transform 0.35s cubic-bezier(0.22,1,0.36,1); box-shadow: 0 24px 60px rgba(0,0,0,0.15); }
        .shop-confirm-backdrop.show .shop-confirm-modal { transform: scale(1) translateY(0); }
        .shop-confirm-icon { font-size: 32px; color: #9c3535; margin-bottom: 16px; }
        .shop-confirm-title { font-size: 18px; font-weight: 700; color: var(--jaced-brown-dark); margin-bottom: 8px; letter-spacing: -0.02em; }
        .shop-confirm-msg { font-size: 14px; color: var(--jaced-muted); margin-bottom: 28px; line-height: 1.5; }
        .shop-confirm-actions { display: flex; gap: 12px; }
        .shop-confirm-cancel { flex: 1; background: transparent; border: 1px solid var(--jaced-input); color: var(--jaced-brown-dark); padding: 12px; border-radius: 999px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; }
        .shop-confirm-cancel:hover { background: var(--jaced-card); }
        .shop-confirm-ok { flex: 1; background: #9c3535; border: none; color: white; padding: 12px; border-radius: 999px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; }
        .shop-confirm-ok:hover { background: #7a2828; }

        @media (max-width: 576px) {
            .shop-hero { padding: 140px 20px 60px; }
            .shop-filters-row { gap: 8px; }
        }
    </style>

    <script>
        // ===== FILTER PILL DROPDOWNS =====
        (function () {
            const pills = [
                { wrap: 'sortWrap', trigger: 'sortTrigger', menu: 'sortMenu' },
                { wrap: 'catWrap',  trigger: 'catTrigger',  menu: 'catMenu'  },
                { wrap: 'matWrap',  trigger: 'matTrigger',  menu: 'matMenu'  },
                { wrap: 'sizeWrap', trigger: 'sizeTrigger', menu: 'sizeMenu' },
                { wrap: 'priceWrap',trigger: 'priceTrigger',menu: 'priceMenu'},
            ];

            pills.forEach(function(p) {
                const wrap    = document.getElementById(p.wrap);
                const trigger = document.getElementById(p.trigger);
                if (!wrap || !trigger) return;

                trigger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isOpen = wrap.classList.contains('open');
                    // close all
                    pills.forEach(function(x) {
                        const w = document.getElementById(x.wrap);
                        if (w) w.classList.remove('open');
                    });
                    if (!isOpen) wrap.classList.add('open');
                });
            });

            // Sort: click option → submit
            const sortMenu = document.getElementById('sortMenu');
            const sortInput = document.getElementById('sort-input');
            const form = document.getElementById('filter-form');
            if (sortMenu && sortInput && form) {
                sortMenu.querySelectorAll('.shop-dd-option').forEach(function(opt) {
                    opt.addEventListener('click', function() {
                        sortInput.value = this.getAttribute('data-value');
                        form.submit();
                    });
                });
            }

            // Close on outside click
            document.addEventListener('click', function() {
                pills.forEach(function(p) {
                    const w = document.getElementById(p.wrap);
                    if (w) w.classList.remove('open');
                });
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    pills.forEach(function(p) {
                        const w = document.getElementById(p.wrap);
                        if (w) w.classList.remove('open');
                    });
                }
            });
        })();

        // ===== WISHLIST + CONFIRM POPUP =====
        (function () {
            let wishedIds = new Set(
                @json(
                    auth()->check()
                        ? \App\Models\Wishlist::where('user_id', auth()->id())->pluck('product_id')
                        : []
                )
            );

            function isWished(id) { return wishedIds.has(parseInt(id)); }

            const toast = document.getElementById('wishToast');
            const toastText = document.getElementById('wishToastText');
            let toastTimer = null;

            function showToast(msg) {
                if (!toast) return;
                toastText.textContent = msg;
                toast.classList.add('show');
                clearTimeout(toastTimer);
                toastTimer = setTimeout(function () { toast.classList.remove('show'); }, 2500);
            }

            let shopConfirmCb = null;
            const confirmBackdrop = document.getElementById('shopConfirmBackdrop');

            function showConfirmPopup(name, onConfirm) {
                document.getElementById('shopConfirmMsg').textContent = 'Remove "' + name + '" from your wishlist?';
                confirmBackdrop.classList.add('show');
                shopConfirmCb = onConfirm;
            }
            document.getElementById('shopConfirmOk').addEventListener('click', function () {
                confirmBackdrop.classList.remove('show');
                if (shopConfirmCb) { shopConfirmCb(); shopConfirmCb = null; }
            });
            document.getElementById('shopConfirmCancel').addEventListener('click', function () {
                confirmBackdrop.classList.remove('show');
                shopConfirmCb = null;
            });
            confirmBackdrop.addEventListener('click', function (e) {
                if (e.target === confirmBackdrop) { confirmBackdrop.classList.remove('show'); shopConfirmCb = null; }
            });

            function setBtnState(btn, active) {
                btn.classList.toggle('active', active);
                const icon = btn.querySelector('i');
                if (icon) { icon.classList.toggle('far', !active); icon.classList.toggle('fas', active); }
            }

            function toggleWishlist(productId, onSuccess) {
                fetch('{{ route("wishlist.toggle") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify({ product_id: productId })
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.status === 'added') { wishedIds.add(parseInt(productId)); }
                    else { wishedIds.delete(parseInt(productId)); }
                    if (onSuccess) onSuccess(data.status);
                })
                .catch(function(err) { console.error(err); });
            }

            document.querySelectorAll('.js-wishlist-btn').forEach(function (btn) {
                const id = btn.getAttribute('data-wish-id');
                setBtnState(btn, isWished(id));
                btn.addEventListener('click', function (e) {
                    e.preventDefault(); e.stopPropagation();
                    const name = btn.getAttribute('data-wish-name');
                    if (isWished(id)) {
                        showConfirmPopup(name, function () {
                            toggleWishlist(id, function() {
                                setBtnState(btn, false);
                                syncQvWishBtn(id, false);
                                showToast(name + ' removed from wishlist');
                            });
                        });
                    } else {
                        toggleWishlist(id, function() {
                            setBtnState(btn, true);
                            syncQvWishBtn(id, true);
                            showToast(name + ' added to wishlist');
                        });
                    }
                });
            });

            const backdrop = document.getElementById('qvBackdrop');
            const qvClose  = document.getElementById('qvClose');
            let currentQvId = null;

            function syncQvWishBtn(id, state) {
                const qvWish = document.getElementById('qvWishBtn');
                if (!qvWish || String(currentQvId) !== String(id)) return;
                setBtnState(qvWish, state !== undefined ? state : isWished(id));
            }

            function openQuickView(card) {
                currentQvId = card.getAttribute('data-product-id');
                document.getElementById('qvImg').src          = card.getAttribute('data-product-img');
                document.getElementById('qvImg').alt          = card.getAttribute('data-product-name');
                document.getElementById('qvCat').textContent  = card.getAttribute('data-product-cat');
                document.getElementById('qvName').textContent = card.getAttribute('data-product-name');
                document.getElementById('qvPrice').textContent = 'Rp ' + card.getAttribute('data-product-price');
                const oldP  = card.getAttribute('data-product-oldprice');
                const oldEl = document.getElementById('qvOldprice');
                if (oldP) { oldEl.textContent = 'Rp ' + oldP; oldEl.style.display = 'inline'; }
                else { oldEl.style.display = 'none'; }
                document.getElementById('qvDim').textContent      = card.getAttribute('data-product-dim');
                document.getElementById('qvMaterial').textContent = card.getAttribute('data-product-material') || '-';
                document.getElementById('qvRoom').textContent     = card.getAttribute('data-product-room') || '-';
                document.getElementById('qvViewBtn').href         = card.getAttribute('data-product-url');
                document.getElementById('qvSoldoutTag').classList.toggle('show', card.getAttribute('data-product-soldout') === '1');
                setBtnState(document.getElementById('qvWishBtn'), isWished(currentQvId));
                backdrop.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeQuickView() { backdrop.classList.remove('active'); document.body.style.overflow = ''; }

            document.querySelectorAll('.js-quickview-btn').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault(); e.stopPropagation();
                    const card = btn.closest('.shop-product-card');
                    if (card) openQuickView(card);
                });
            });

            if (qvClose)  qvClose.addEventListener('click', closeQuickView);
            if (backdrop) backdrop.addEventListener('click', function (e) { if (e.target === backdrop) closeQuickView(); });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeQuickView(); });

            const qvWishBtn = document.getElementById('qvWishBtn');
            if (qvWishBtn) {
                qvWishBtn.addEventListener('click', function () {
                    if (!currentQvId) return;
                    const name = document.getElementById('qvName').textContent;
                    if (isWished(currentQvId)) {
                        showConfirmPopup(name, function () {
                            toggleWishlist(currentQvId, function() {
                                setBtnState(qvWishBtn, false);
                                const cardBtn = document.querySelector('.js-wishlist-btn[data-wish-id="' + currentQvId + '"]');
                                if (cardBtn) setBtnState(cardBtn, false);
                                showToast(name + ' removed from wishlist');
                            });
                        });
                    } else {
                        toggleWishlist(currentQvId, function() {
                            setBtnState(qvWishBtn, true);
                            const cardBtn = document.querySelector('.js-wishlist-btn[data-wish-id="' + currentQvId + '"]');
                            if (cardBtn) setBtnState(cardBtn, true);
                            showToast(name + ' added to wishlist');
                        });
                    }
                });
            }
        })();

        // ===== LIVE SEARCH =====
        (function () {
            const searchInput = document.getElementById('shopSearchInput');
            const form = document.getElementById('filter-form');
            if (!searchInput || !form) return;
            let debounceTimer;
            searchInput.addEventListener('input', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function () { form.submit(); }, 500);
            });
        })();

        function setFilter(name, values) {
            const form = document.getElementById('filter-form');
            // Hapus semua input dengan nama itu dulu
            form.querySelectorAll('input[name="' + name + '[]"], input[name="' + name + '"]').forEach(el => el.remove());
            // Tambahin nilai baru
            values.forEach(function(v) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = name + '[]';
                input.value = v;
                form.appendChild(input);
            });
            form.submit();
        }

        function setPriceFilter(min, max) {
            const form = document.getElementById('filter-form');
            form.querySelectorAll('input[name="min_price"], input[name="max_price"]').forEach(el => el.remove());
            if (min !== '') {
                const i = document.createElement('input');
                i.type = 'hidden'; i.name = 'min_price'; i.value = min;
                form.appendChild(i);
            }
            if (max !== '') {
                const i = document.createElement('input');
                i.type = 'hidden'; i.name = 'max_price'; i.value = max;
                form.appendChild(i);
            }
            form.submit();
        }
    </script>

@endsection