@extends('base.base')

@section('title', 'Shop — JACED Furniture')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .shop-page { font-family: 'Lexend', sans-serif; color: var(--jaced-brown-dark); }
    .shop-page * { box-sizing: border-box; }

    /* PAGE HEAD */
    .page-head { max-width: 1280px; margin: 0 auto; padding: 56px 32px 32px; }
    .breadcrumb-jaced { font-size: 12px; letter-spacing: 0.18em; text-transform: uppercase; color: var(--jaced-muted); margin-bottom: 16px; }
    .breadcrumb-jaced a { color: var(--jaced-muted); text-decoration: none; }
    .breadcrumb-jaced a:hover { color: var(--jaced-caramel); }
    .page-title { font-size: 48px; line-height: 1.05; margin: 0 0 12px; letter-spacing: -0.01em; font-weight: 700; }
    .page-sub { color: var(--jaced-muted); font-size: 15px; margin: 0; }

    /* SEARCH */
    .search-row { max-width: 1280px; margin: 32px auto 0; padding: 0 32px; display: flex; gap: 16px; align-items: center; flex-wrap: wrap; }
    .search-box { flex: 1; min-width: 280px; position: relative; }
    .search-input { width: 100%; padding: 16px 20px 16px 52px; border-radius: 100px; border: 1px solid var(--jaced-input); background: var(--jaced-card); font-family: inherit; font-size: 14px; color: var(--jaced-brown-dark); outline: none; transition: 0.2s; }
    .search-input:focus { border-color: var(--jaced-sage); box-shadow: 0 0 0 4px rgba(95, 117, 104, 0.12); }
    .search-input::placeholder { color: var(--jaced-muted); }
    .search-icon { position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: var(--jaced-muted); pointer-events: none; }
    .sort-wrap { position: relative; }
    .sort-select { padding: 16px 48px 16px 24px; border-radius: 100px; border: 1px solid var(--jaced-input); background: var(--jaced-card); font-family: inherit; font-size: 14px; color: var(--jaced-brown-dark); cursor: pointer; appearance: none; outline: none; transition: 0.2s; }
    .sort-select:hover { border-color: var(--jaced-sage); }
    .sort-icon { position: absolute; right: 20px; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--jaced-brown-dark); }
    .view-toggle { display: flex; gap: 4px; background: var(--jaced-card); border: 1px solid var(--jaced-input); border-radius: 100px; padding: 4px; }
    .view-toggle button { width: 38px; height: 38px; border-radius: 50%; border: none; background: transparent; cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--jaced-muted); transition: 0.2s; }
    .view-toggle button.active { background: var(--jaced-brown-dark); color: var(--jaced-cream); }

    /* MAIN LAYOUT */
    .shop-layout { max-width: 1280px; margin: 32px auto 0; padding: 0 32px 100px; display: grid; grid-template-columns: 260px 1fr; gap: 48px; align-items: start; }

    /* FILTER SIDEBAR */
    .filter-side { position: sticky; top: 100px; align-self: start; background: var(--jaced-card); border-radius: 12px; padding: 32px 28px; }
    .filter-group { padding-bottom: 28px; margin-bottom: 28px; border-bottom: 1px solid var(--jaced-input); }
    .filter-group:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .filter-title { font-size: 11px; letter-spacing: 0.24em; text-transform: uppercase; color: var(--jaced-brown-dark); font-weight: 600; margin-bottom: 18px; }
    .filter-list { display: flex; flex-direction: column; gap: 12px; }
    .filter-item { display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 14px; color: var(--jaced-brown-dark); margin: 0; }
    .filter-item input { accent-color: var(--jaced-sage); width: 16px; height: 16px; }
    .filter-item .count { margin-left: auto; color: var(--jaced-muted); font-size: 12px; }
    .filter-item:hover { color: var(--jaced-sage); }
    .filter-swatches { display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; }
    .filter-swatch { aspect-ratio: 1; border-radius: 50%; cursor: pointer; border: 2px solid transparent; transition: 0.2s; }
    .filter-swatch:hover { transform: scale(1.1); }
    .price-range { display: flex; gap: 8px; align-items: center; }
    .price-input { width: 100%; padding: 10px 12px; border: 1px solid var(--jaced-input); border-radius: 8px; background: white; font-size: 12px; font-family: inherit; outline: none; }
    .price-input:focus { border-color: var(--jaced-sage); }
    .apply-btn { margin-top: 12px; width: 100%; padding: 10px; border-radius: 100px; border: none; background: var(--jaced-sage); color: white; font-size: 12px; letter-spacing: 0.1em; cursor: pointer; font-family: inherit; transition: 0.2s; }
    .apply-btn:hover { background: #4a5d52; }
    .reset-btn { width: 100%; padding: 12px; border-radius: 100px; border: 1px solid var(--jaced-brown-dark); background: transparent; color: var(--jaced-brown-dark); font-size: 13px; font-weight: 500; cursor: pointer; margin-top: 8px; transition: 0.2s; font-family: inherit; text-decoration: none; text-align: center; display: block; }
    .reset-btn:hover { background: var(--jaced-brown-dark); color: var(--jaced-cream); }

    /* RESULT META */
    .result-meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; font-size: 13px; color: var(--jaced-muted); }
    .result-tags { display: flex; gap: 8px; flex-wrap: wrap; }
    .result-tag { background: var(--jaced-card); border: 1px solid var(--jaced-input); padding: 6px 12px; border-radius: 100px; font-size: 12px; color: var(--jaced-brown-dark); }

    /* PRODUCT GRID */
    .product-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px 24px; }
    .product-card { cursor: pointer; transition: 0.3s; }
    .product-img-wrap { aspect-ratio: 1; background: var(--jaced-card); border-radius: 12px; overflow: hidden; margin-bottom: 16px; position: relative; }
    .product-img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .product-card:hover .product-img { transform: scale(1.04); }
    .product-badge { position: absolute; top: 14px; left: 14px; background: var(--jaced-caramel); color: white; font-size: 10px; padding: 5px 12px; border-radius: 100px; letter-spacing: 0.1em; text-transform: uppercase; font-weight: 600; }
    .product-badge.dark { background: var(--jaced-brown-dark); }
    .product-fav { position: absolute; top: 14px; right: 14px; width: 36px; height: 36px; border-radius: 50%; background: rgba(237, 232, 227, 0.9); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s; border: none; color: var(--jaced-brown-dark); }
    .product-fav:hover { background: white; transform: scale(1.06); }
    .product-quick { position: absolute; bottom: 14px; left: 14px; right: 14px; background: var(--jaced-sage); color: white; padding: 12px; border-radius: 100px; text-align: center; font-size: 12px; letter-spacing: 0.1em; text-transform: uppercase; font-weight: 500; opacity: 0; transform: translateY(10px); transition: 0.3s; border: none; cursor: pointer; font-family: inherit; }
    .product-card:hover .product-quick { opacity: 1; transform: translateY(0); }
    .product-cat { font-size: 11px; letter-spacing: 0.18em; text-transform: uppercase; color: var(--jaced-caramel); margin-bottom: 4px; font-weight: 500; }
    .product-name { font-size: 18px; line-height: 1.3; margin: 0 0 8px; color: var(--jaced-brown-dark); font-weight: 600; }
    .product-bottom { display: flex; justify-content: space-between; align-items: center; margin-top: 10px; }
    .product-price { font-size: 15px; color: var(--jaced-brown-dark); font-weight: 500; }
    .product-price small { color: var(--jaced-muted); text-decoration: line-through; font-size: 12px; font-weight: 400; margin-left: 6px; }
    .swatches { display: flex; gap: 6px; }
    .swatch { width: 16px; height: 16px; border-radius: 50%; border: 1.5px solid var(--jaced-input); cursor: pointer; transition: 0.2s; }
    .swatch:hover { transform: scale(1.2); }
    .swatch.active { border-color: var(--jaced-brown-dark); border-width: 2px; }

    /* PAGINATION */
    .pagination { display: flex; justify-content: center; gap: 8px; margin-top: 64px; }
    .page-btn { width: 42px; height: 42px; border-radius: 50%; border: 1px solid var(--jaced-input); background: var(--jaced-card); color: var(--jaced-brown-dark); cursor: pointer; font-family: inherit; font-size: 14px; transition: 0.2s; display: flex; align-items: center; justify-content: center; text-decoration: none; }
    .page-btn.active { background: var(--jaced-brown-dark); color: var(--jaced-cream); border-color: var(--jaced-brown-dark); }
    .page-btn:hover:not(.active) { border-color: var(--jaced-sage); color: var(--jaced-sage); }

    @media (max-width: 900px) {
        .shop-layout { grid-template-columns: 1fr; }
        .filter-side { position: static; }
        .product-grid { grid-template-columns: 1fr 1fr; }
        .page-title { font-size: 36px; }
    }
    @media (max-width: 560px) {
        .product-grid { grid-template-columns: 1fr; }
        .search-row { padding: 0 24px; }
        .page-head { padding: 32px 24px 16px; }
        .shop-layout { padding: 0 24px 60px; }
    }
</style>
@endpush

@section('content')
<div class="shop-page">

    <header class="page-head">
        <div class="breadcrumb-jaced"><a href="{{ route('home') }}">Home</a> · Shop</div>
        <h1 class="page-title">Shop the Collection</h1>
        <p class="page-sub">{{ $totalProducts }} pieces. Solid wood. Made to last 15+ years.</p>
    </header>

    <form action="{{ route('shop') }}" method="GET" id="filter-form">

    <div class="search-row">
        <div class="search-box">
            <span class="search-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            </span>
            <input type="text" name="search" class="search-input" placeholder="Cari nora chair, oka table, walnut..." value="{{ request('search') }}">
        </div>
        <div class="sort-wrap">
            <select class="sort-select" name="sort" onchange="document.getElementById('filter-form').submit()">
                <option value="">Sort: Recommended</option>
                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest first</option>
                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="bestseller" {{ request('sort') === 'bestseller' ? 'selected' : '' }}>Best sellers</option>
            </select>
            <span class="sort-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
            </span>
        </div>
        <div class="view-toggle">
            <button type="button" class="active" aria-label="Grid">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
            </button>
            <button type="button" aria-label="List">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
        </div>
    </div>

    <div class="shop-layout">

        <aside class="filter-side">
            <div class="filter-group">
                <div class="filter-title">Category</div>
                <div class="filter-list">
                    @foreach($categories as $cat)
                    <label class="filter-item">
                        <input type="checkbox" name="category[]" value="{{ $cat->slug }}"
                            {{ in_array($cat->slug, (array) request('category', [])) ? 'checked' : '' }}
                            onchange="document.getElementById('filter-form').submit()">
                        {{ $cat->name }} <span class="count">{{ $cat->products_count }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="filter-group">
                <div class="filter-title">Material</div>
                <div class="filter-list">
                    @foreach($materials as $mat)
                    <label class="filter-item">
                        <input type="checkbox" name="material[]" value="{{ $mat->slug }}"
                            {{ in_array($mat->slug, (array) request('material', [])) ? 'checked' : '' }}
                            onchange="document.getElementById('filter-form').submit()">
                        {{ $mat->name }} <span class="count">{{ $mat->products_count }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="filter-group">
                <div class="filter-title">Color</div>
                <div class="filter-swatches">
                    <div class="filter-swatch" style="background: #896540;" title="Caramel"></div>
                    <div class="filter-swatch" style="background: #5A4D47;" title="Brown"></div>
                    <div class="filter-swatch" style="background: #272E1D;" title="Dark"></div>
                    <div class="filter-swatch" style="background: #C99A6B;" title="Tan"></div>
                    <div class="filter-swatch" style="background: #5F7568;" title="Sage"></div>
                    <div class="filter-swatch" style="background: #EDE8E3; border-color: #DDD6CE;" title="Cream"></div>
                </div>
            </div>

            <div class="filter-group">
                <div class="filter-title">Price (IDR)</div>
                <div class="price-range">
                    <input type="number" name="min_price" class="price-input" placeholder="Min" value="{{ request('min_price') }}">
                    <span style="color: var(--jaced-muted);">—</span>
                    <input type="number" name="max_price" class="price-input" placeholder="Max" value="{{ request('max_price') }}">
                </div>
                <button type="submit" class="apply-btn">Apply Price</button>
            </div>

            <a href="{{ route('shop') }}" class="reset-btn">Reset all filters</a>
        </aside>

        <main>
            <div class="result-meta">
                <div class="result-tags">
                    @if(request('search'))
                        <span class="result-tag">Search: {{ request('search') }}</span>
                    @endif
                </div>
                <div>Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products</div>
            </div>

            <div class="product-grid">
                @forelse($products as $product)
                <article class="product-card" onclick="window.location='{{ route('product.show', $product->slug) }}'">
                    <div class="product-img-wrap">
                        @if($product->badge)
                            <span class="product-badge {{ $product->badge === 'preorder' ? 'dark' : '' }}">{{ ucfirst($product->badge) }}</span>
                        @endif
                        <button type="button" class="product-fav" aria-label="Favorite" onclick="event.stopPropagation();">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        </button>
                        <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="product-img">
                        <button type="button" class="product-quick" onclick="event.stopPropagation(); window.location='{{ route('product.show', $product->slug) }}'">Quick View</button>
                    </div>
                    <div class="product-cat">{{ $product->category->name }}</div>
                    <h3 class="product-name">{{ $product->name }}</h3>
                    <div class="product-bottom">
                        <div class="product-price">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                            @if($product->old_price)
                                <small>Rp {{ number_format($product->old_price, 0, ',', '.') }}</small>
                            @endif
                        </div>
                        <div class="swatches">
                            @foreach($product->variants as $variant)
                                <span class="swatch {{ $variant->is_default ? 'active' : '' }}" style="background: {{ $variant->color_hex }};" title="{{ $variant->color_name }}" onclick="event.stopPropagation();"></span>
                            @endforeach
                        </div>
                    </div>
                </article>
                @empty
                    <p style="grid-column: 1 / -1; text-align: center; padding: 80px 20px; color: var(--jaced-muted);">Belum ada produk yang cocok. Coba reset filter.</p>
                @endforelse
            </div>

            <div class="pagination">
                {{ $products->links('vendor.pagination.jaced') }}
            </div>
        </main>
    </div>

    </form>

</div>
@endsection