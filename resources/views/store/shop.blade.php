@extends('base.base')

@section('title', 'Shop — JACED Furniture')

@section('content')
<div class="container py-5">

    {{-- PAGE HEAD --}}
    <div class="mb-4">
        <h1 class="display-4 fw-bold mb-2">Shop the Collection</h1>
        <p class="text-jaced-muted mb-0">{{ $totalProducts }} pieces. Solid wood. Made to last 15+ years.</p>
    </div>

    <form action="{{ route('shop') }}" method="GET" id="filter-form">

    {{-- SEARCH + SORT --}}
    <div class="row g-3 mb-4 align-items-center">
        <div class="col-md">
            <div class="input-group input-group-lg">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-jaced-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nora chair, oka table, walnut..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-auto">
            <select class="form-select form-select-lg" name="sort" onchange="document.getElementById('filter-form').submit()">
                <option value="">Sort: Recommended</option>
                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest first</option>
                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="bestseller" {{ request('sort') === 'bestseller' ? 'selected' : '' }}>Best sellers</option>
            </select>
        </div>
    </div>

    <div class="row g-4">

        {{-- SIDEBAR --}}
        <div class="col-lg-3">
            <div class="card jaced-card p-4 sticky-top" style="top: 100px;">
                <div class="card-body p-0">

                    {{-- Category --}}
                    <div class="mb-4 pb-4 border-bottom" style="border-color: var(--jaced-input) !important;">
                        <h6 class="text-uppercase fw-bold mb-3" style="letter-spacing: 0.2em; font-size: 11px;">Category</h6>
                        @foreach($categories as $cat)
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="cat-{{ $cat->slug }}" name="category[]" value="{{ $cat->slug }}"
                                    {{ in_array($cat->slug, (array) request('category', [])) ? 'checked' : '' }}
                                    onchange="document.getElementById('filter-form').submit()">
                                <label class="form-check-label d-flex justify-content-between" for="cat-{{ $cat->slug }}">
                                    <span>{{ $cat->name }}</span>
                                    <small class="text-jaced-muted">{{ $cat->products_count }}</small>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    {{-- Material --}}
                    <div class="mb-4 pb-4 border-bottom" style="border-color: var(--jaced-input) !important;">
                        <h6 class="text-uppercase fw-bold mb-3" style="letter-spacing: 0.2em; font-size: 11px;">Material</h6>
                        @foreach($materials as $mat)
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="mat-{{ $mat->slug }}" name="material[]" value="{{ $mat->slug }}"
                                    {{ in_array($mat->slug, (array) request('material', [])) ? 'checked' : '' }}
                                    onchange="document.getElementById('filter-form').submit()">
                                <label class="form-check-label d-flex justify-content-between" for="mat-{{ $mat->slug }}">
                                    <span>{{ $mat->name }}</span>
                                    <small class="text-jaced-muted">{{ $mat->products_count }}</small>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    {{-- Color --}}
                    <div class="mb-4 pb-4 border-bottom" style="border-color: var(--jaced-input) !important;">
                        <h6 class="text-uppercase fw-bold mb-3" style="letter-spacing: 0.2em; font-size: 11px;">Color</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(['#896540','#5A4D47','#272E1D','#C99A6B','#5F7568','#EDE8E3','#2e313e','#ac6c40'] as $color)
                                <span class="rounded-circle border" style="width: 28px; height: 28px; background: {{ $color }}; cursor: pointer; border-color: var(--jaced-input) !important;"></span>
                            @endforeach
                        </div>
                    </div>

                    {{-- Price --}}
                    <div class="mb-4 pb-4 border-bottom" style="border-color: var(--jaced-input) !important;">
                        <h6 class="text-uppercase fw-bold mb-3" style="letter-spacing: 0.2em; font-size: 11px;">Price (IDR)</h6>
                        <div class="row g-2 mb-3">
                            <div class="col-5">
                                <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min" value="{{ request('min_price') }}">
                            </div>
                            <div class="col-2 d-flex align-items-center justify-content-center text-jaced-muted">—</div>
                            <div class="col-5">
                                <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max" value="{{ request('max_price') }}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-sm w-100 rounded-pill" style="background: var(--jaced-sage); color: white;">Apply Price</button>
                    </div>

                    <a href="{{ route('shop') }}" class="btn btn-outline-dark rounded-pill w-100">Reset all filters</a>
                </div>
            </div>
        </div>

        {{-- PRODUCT GRID --}}
        <div class="col-lg-9">

            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div>
                    @if(request('search'))
                        <span class="badge rounded-pill text-bg-light border">Search: {{ request('search') }}</span>
                    @endif
                </div>
                <small class="text-jaced-muted">Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products</small>
            </div>

            <div class="row g-4">
                @forelse($products as $product)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 h-100" style="cursor: pointer; background: transparent;" onclick="window.location='{{ route('product.show', $product->slug) }}'">
                        <div class="position-relative rounded-3 overflow-hidden mb-3" style="aspect-ratio: 1; background: var(--jaced-card);">
                            @if($product->badge)
                                <span class="position-absolute top-0 start-0 m-3 px-3 py-1 rounded-pill text-uppercase fw-bold" style="background: {{ $product->badge === 'preorder' ? 'var(--jaced-brown-dark)' : 'var(--jaced-caramel)' }}; color: white; font-size: 10px; letter-spacing: 0.1em;">
                                    {{ ucfirst($product->badge) }}
                                </span>
                            @endif
                            <button type="button" class="btn position-absolute top-0 end-0 m-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(237, 232, 227, 0.9); border: none;" onclick="event.stopPropagation();">
                                <i class="far fa-heart"></i>
                            </button>
                            <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="w-100 h-100" style="object-fit: cover;">
                        </div>
                        <div>
                            <small class="d-block text-uppercase mb-1" style="color: var(--jaced-caramel); letter-spacing: 0.18em; font-size: 11px;">{{ $product->category->name }}</small>
                            <h5 class="fw-semibold mb-2">{{ $product->name }}</h5>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-medium">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    @if($product->old_price)
                                        <small class="text-decoration-line-through text-jaced-muted ms-1">Rp {{ number_format($product->old_price, 0, ',', '.') }}</small>
                                    @endif
                                </div>
                                <div class="d-flex gap-1" onclick="event.stopPropagation();">
                                    @foreach($product->variants as $variant)
                                        <span class="rounded-circle d-inline-block" style="width: 16px; height: 16px; background: {{ $variant->color_hex }}; border: 1.5px solid var(--jaced-input); cursor: pointer; {{ $variant->is_default ? 'border-color: var(--jaced-brown-dark); border-width: 2px;' : '' }}" title="{{ $variant->color_name }}"></span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-jaced-muted mb-0">Belum ada produk yang cocok. Coba reset filter.</p>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    </form>

</div>
@endsection