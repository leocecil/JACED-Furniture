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
                        <label class="shop-sort-label">Sort</label>
                        <select class="shop-sort-select" name="sort"
                                onchange="document.getElementById('filter-form').submit()">
                            <option value="">Recommended</option>
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest first</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="bestseller" {{ request('sort') === 'bestseller' ? 'selected' : '' }}>Best sellers</option>
                        </select>
                    </div>
                </div>

                {{-- ACTIVE FILTER CHIPS --}}
                @php
                    $hasActiveFilters = request('search') || request('category') || request('material') || request('color') || request('min_price') || request('max_price') || request('sort');
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

                        @foreach((array) request('material', []) as $matSlug)
                            @php $matItem = $materials->firstWhere('slug', $matSlug); @endphp
                            @if($matItem)
                                <span class="shop-chip">
                                    {{ $matItem->name }}
                                    <a href="{{ route('shop', array_merge(request()->except('page'), ['material' => array_values(array_diff((array) request('material', []), [$matSlug]))])) }}"><i class="fas fa-times"></i></a>
                                </span>
                            @endif
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
                        <aside class="shop-sidebar">

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

                            {{-- Color (functional now) --}}
                            <div class="shop-filter-group">
                                <h6 class="shop-filter-title">Color</h6>
                                <div class="shop-color-grid">
                                    @php
                                        $colorOptions = [
                                            ['hex' => '#896540', 'name' => 'Caramel'],
                                            ['hex' => '#5A4D47', 'name' => 'Brown'],
                                            ['hex' => '#272E1D', 'name' => 'Dark'],
                                            ['hex' => '#C99A6B', 'name' => 'Tan'],
                                            ['hex' => '#5F7568', 'name' => 'Sage'],
                                            ['hex' => '#EDE8E3', 'name' => 'Cream'],
                                            ['hex' => '#2e313e', 'name' => 'Navy'],
                                            ['hex' => '#ac6c40', 'name' => 'Rust'],
                                        ];
                                        $activeColors = (array) request('color', []);
                                    @endphp
                                    @foreach($colorOptions as $color)
                                        <label class="shop-color-chip {{ in_array($color['hex'], $activeColors) ? 'active' : '' }}"
                                               title="{{ $color['name'] }}">
                                            <input type="checkbox" name="color[]" value="{{ $color['hex'] }}"
                                                   {{ in_array($color['hex'], $activeColors) ? 'checked' : '' }}
                                                   onchange="document.getElementById('filter-form').submit()">
                                            <span class="shop-color-dot" style="background: {{ $color['hex'] }};"></span>
                                        </label>
                                    @endforeach
                                </div>
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
                                <div class="col-md-6 col-xl-4">
                                    <a href="{{ route('product.show', $product->slug) }}" class="shop-product-card">
                                        <div class="shop-product-img-wrap">
                                            @if($product->badge)
                                                <span class="shop-product-badge {{ $product->badge === 'preorder' ? 'badge-dark' : 'badge-caramel' }}">
                                                    {{ ucfirst($product->badge) }}
                                                </span>
                                            @endif

                                            <div class="shop-product-actions">
                                                <button type="button" class="shop-action-btn wishlist-btn"
                                                        onclick="event.preventDefault(); event.stopPropagation(); this.classList.toggle('active'); const i = this.querySelector('i'); i.classList.toggle('far'); i.classList.toggle('fas');">
                                                    <i class="far fa-heart"></i>
                                                </button>
                                                <button type="button" class="shop-action-btn"
                                                        onclick="event.preventDefault(); event.stopPropagation();">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>

                                            <img src="{{ $product->main_image }}" alt="{{ $product->name }}"
                                                 class="shop-product-img">
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
                                                <div class="shop-product-variants" onclick="event.preventDefault(); event.stopPropagation();">
                                                    @foreach($product->variants as $variant)
                                                        <span class="shop-variant-dot {{ $variant->is_default ? 'active' : '' }}"
                                                              style="background: {{ $variant->color_hex }};"
                                                              title="{{ $variant->color_name }}"></span>
                                                    @endforeach
                                                </div>
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

        .shop-sort-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--jaced-card);
            border: 1px solid var(--jaced-input);
            border-radius: 999px;
            padding: 4px 6px 4px 20px;
        }
        .shop-sort-label {
            font-size: 12px;
            color: var(--jaced-muted);
            text-transform: uppercase;
            letter-spacing: 0.15em;
            margin: 0;
        }
        .shop-sort-select {
            background: transparent;
            border: none;
            padding: 10px 36px 10px 8px;
            font-size: 14px;
            color: var(--jaced-brown-dark);
            font-weight: 500;
            cursor: pointer;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23272E1D' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
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

        /* COLOR CHIPS (functional) */
        .shop-color-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .shop-color-chip {
            position: relative;
            width: 28px;
            height: 28px;
            cursor: pointer;
            margin: 0;
        }
        .shop-color-chip input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }
        .shop-color-dot {
            display: block;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 1.5px solid var(--jaced-input);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .shop-color-chip:hover .shop-color-dot { transform: scale(1.1); }
        .shop-color-chip.active .shop-color-dot {
            box-shadow: 0 0 0 2px var(--jaced-card), 0 0 0 4px var(--jaced-brown-dark);
            transform: scale(1.05);
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
        .shop-product-variants {
            display: flex;
            gap: 4px;
        }
        .shop-variant-dot {
            display: inline-block;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 1.5px solid var(--jaced-input);
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .shop-variant-dot:hover { transform: scale(1.2); }
        .shop-variant-dot.active {
            border-color: var(--jaced-brown-dark);
            border-width: 2px;
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

        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .shop-sidebar {
                position: static;
                margin-bottom: 24px;
            }
        }
        @media (max-width: 576px) {
            .shop-hero { padding: 140px 20px 60px; }
            .shop-toolbar { flex-direction: column; }
            .shop-sort-wrap { width: 100%; justify-content: space-between; }
        }
    </style>

@endsection