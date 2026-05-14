@extends('base.base')

@section('title', 'Home — JACED Furniture')

@section('content')

{{-- HERO --}}
<section class="py-5">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <p class="text-uppercase mb-3" style="color: var(--jaced-caramel); letter-spacing: 0.3em; font-size: 12px; font-weight: 500;">Curated Collection 2026</p>
                <h1 class="display-3 fw-bold mb-4" style="font-family: 'Cormorant Garamond', serif; line-height: 1.05;">
                    Design your space.
                    <span class="fst-italic fw-semibold" style="color: var(--jaced-caramel);">Live your style.</span>
                </h1>
                <p class="text-jaced-muted lead mb-4">
                    Furnitur kayu solid yang dirakit dengan teliti. Walnut, ash, oak. Setiap potongan dibuat untuk menemani lo seumur hidup, bukan satu musim.
                </p>
                <div class="d-flex gap-3 align-items-center flex-wrap mb-5">
                    <a href="{{ route('shop') }}" class="btn px-4 py-3 rounded-pill" style="background-color: var(--jaced-sage); color: white;">
                        Explore Collection
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                    <a href="#about" class="btn btn-link text-decoration-none px-3 py-3 border-bottom border-dark text-jaced-dark rounded-0">
                        Our Story
                    </a>
                </div>
                <div class="row g-4 pt-4 border-top" style="border-color: var(--jaced-input) !important;">
                    <div class="col-4">
                        <div class="h2 fw-bold mb-1">12K+</div>
                        <small class="text-uppercase text-jaced-muted" style="letter-spacing: 0.2em; font-size: 10px;">Happy Homes</small>
                    </div>
                    <div class="col-4">
                        <div class="h2 fw-bold mb-1">240+</div>
                        <small class="text-uppercase text-jaced-muted" style="letter-spacing: 0.2em; font-size: 10px;">Crafted Pieces</small>
                    </div>
                    <div class="col-4">
                        <div class="h2 fw-bold mb-1">15yr</div>
                        <small class="text-uppercase text-jaced-muted" style="letter-spacing: 0.2em; font-size: 10px;">Material Warranty</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="position-relative rounded-4 overflow-hidden" style="aspect-ratio: 4/5; background: var(--jaced-brown);">
                    <img src="https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?w=800&h=1000&fit=crop" alt="Hero" class="w-100 h-100" style="object-fit: cover;">
                    <div class="position-absolute bottom-0 start-0 m-4 px-3 py-2 rounded-pill d-flex align-items-center gap-2" style="background: rgba(237, 232, 227, 0.95); backdrop-filter: blur(8px); font-size: 13px;">
                        <span class="rounded-circle" style="width: 8px; height: 8px; background: var(--jaced-caramel);"></span>
                        New drop. Walnut Series.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ABOUT --}}
<section class="py-5" id="about">
    <div class="container py-5">
        <div class="row align-items-end mb-5 g-4">
            <div class="col-lg-7">
                <p class="text-uppercase mb-2" style="color: var(--jaced-caramel); letter-spacing: 0.3em; font-size: 11px; font-weight: 500;">About Us</p>
                <h2 class="display-5 fw-bold mb-0" style="font-family: 'Cormorant Garamond', serif;">Built slow. Made to outlast trends.</h2>
            </div>
            <div class="col-lg-5">
                <p class="text-jaced-muted mb-0">Jaced bukan brand furnitur biasa. Tiga prinsip yang kita pegang sejak hari pertama.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card jaced-card h-100 p-4">
                    <div class="card-body">
                        <small class="d-block mb-4" style="color: var(--jaced-caramel); letter-spacing: 0.1em; font-weight: 500;">01 / KRAFT</small>
                        <h3 class="h4 fw-semibold mb-3">Solid wood, jujur dari sumber.</h3>
                        <p class="text-jaced-muted mb-0 small">Walnut, ash, dan oak yang ditelusuri sampai ke hutannya. Tanpa veneer, tanpa partikel board, tanpa kompromi.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card jaced-card h-100 p-4">
                    <div class="card-body">
                        <small class="d-block mb-4" style="color: var(--jaced-caramel); letter-spacing: 0.1em; font-weight: 500;">02 / DESIGN</small>
                        <h3 class="h4 fw-semibold mb-3">Bentuk tenang yang tidak teriak.</h3>
                        <p class="text-jaced-muted mb-0 small">Garis bersih, proporsi yang masuk akal, dan detail yang dipikirin sampai ke sambungan terkecil. Bukan dekorasi, ini arsitektur.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card jaced-card h-100 p-4">
                    <div class="card-body">
                        <small class="d-block mb-4" style="color: var(--jaced-caramel); letter-spacing: 0.1em; font-weight: 500;">03 / GARANSI</small>
                        <h3 class="h4 fw-semibold mb-3">15 tahun bilang serius.</h3>
                        <p class="text-jaced-muted mb-0 small">Setiap potong kayu kita garansi struktur 15 tahun. Kalau ada yang lepas atau retak, kita benerin tanpa nanya.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- RECOMMENDED --}}
<section class="py-5" style="background-color: var(--jaced-brown-dark); color: var(--jaced-cream);">
    <div class="container py-5">
        <div class="row align-items-end mb-5 g-4">
            <div class="col-lg-7">
                <p class="text-uppercase mb-2" style="color: var(--jaced-caramel); letter-spacing: 0.3em; font-size: 11px; font-weight: 500;">Recommended For You</p>
                <h2 class="display-5 fw-bold mb-0" style="color: var(--jaced-cream);">Pilihan yang sedang dicari banyak orang.</h2>
            </div>
            <div class="col-lg-5 text-lg-end">
                <a href="{{ route('shop') }}" class="btn btn-link text-decoration-none px-3 py-3 border-bottom rounded-0" style="color: var(--jaced-cream); border-color: var(--jaced-cream) !important;">
                    View All Products
                </a>
            </div>
        </div>

        <div class="row g-4">
            @forelse($recommended as $product)
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none" style="color: inherit;">
                    <div class="position-relative rounded-4 overflow-hidden mb-3" style="aspect-ratio: 1; background: rgba(237, 232, 227, 0.05);">
                        @if($product->badge)
                            <span class="position-absolute top-0 start-0 m-3 px-3 py-1 rounded-pill text-uppercase fw-bold" style="background: var(--jaced-caramel); color: white; font-size: 10px; letter-spacing: 0.1em;">
                                {{ ucfirst($product->badge) }}
                            </span>
                        @endif
                        <button type="button" class="btn position-absolute top-0 end-0 m-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(237, 232, 227, 0.9); color: var(--jaced-brown-dark); border: none;" onclick="event.preventDefault();">
                            <i class="far fa-heart"></i>
                        </button>
                        <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="w-100 h-100" style="object-fit: cover;">
                    </div>
                    <small class="d-block text-uppercase mb-1" style="color: var(--jaced-caramel); letter-spacing: 0.18em; font-size: 11px;">{{ $product->category->name }}</small>
                    <h5 class="fw-semibold mb-2"   style="font-family: 'Cormorant Garamond', serif; color: var(--jaced-cream);">{{ $product->name }}</h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-medium" style="color: var(--jaced-cream);">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @if($product->old_price)
                                <small class="text-decoration-line-through ms-1" style="color: rgba(237, 232, 227, 0.55);">Rp {{ number_format($product->old_price, 0, ',', '.') }}</small>
                            @endif
                        </div>
                        <div class="d-flex gap-1">
                            @foreach($product->variants as $variant)
                                <span class="rounded-circle d-inline-block" style="width: 14px; height: 14px; background: {{ $variant->color_hex }}; border: 1.5px solid rgba(237, 232, 227, 0.25);" title="{{ $variant->color_name }}"></span>
                            @endforeach
                        </div>
                    </div>
                </a>
            </div>
            @empty
                <div class="col-12 text-center py-5" style="opacity: 0.6;">Belum ada produk rekomendasi.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- MATERIALS --}}
<section class="py-5" id="materials">
    <div class="container py-5">
        <div class="row align-items-end mb-5">
            <div class="col">
                <p class="text-uppercase mb-2" style="color: var(--jaced-caramel); letter-spacing: 0.3em; font-size: 11px; font-weight: 500;">Our Materials</p>
                <h2 class="display-5 fw-bold mb-0" style="font-family: 'Cormorant Garamond', serif;">Empat kayu. Empat karakter.</h2>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="rounded-3 mb-3" style="aspect-ratio: 16/10; background: linear-gradient(135deg, #4a2818 0%, #6b3a22 100%);"></div>
                <h5 class="fw-semibold mb-2">Walnut</h5>
                <p class="text-jaced-muted small mb-0">Coklat tua iconic yang menua jadi madu seiring waktu.</p>
            </div>
            <div class="col-md-3 col-6">
                <div class="rounded-3 mb-3" style="aspect-ratio: 16/10; background: linear-gradient(135deg, #d9c9a8 0%, #c4ad88 100%);"></div>
                <h5 class="fw-semibold mb-2">Ash</h5>
                <p class="text-jaced-muted small mb-0">Pucat dengan serat tegas. Karakter kuat tapi terang.</p>
            </div>
            <div class="col-md-3 col-6">
                <div class="rounded-3 mb-3" style="aspect-ratio: 16/10; background: linear-gradient(135deg, #b48a5c 0%, #8c6a44 100%);"></div>
                <h5 class="fw-semibold mb-2">Oak</h5>
                <p class="text-jaced-muted small mb-0">Klasik untuk semua ruang. Hangat, tahan banting.</p>
            </div>
            <div class="col-md-3 col-6">
                <div class="rounded-3 mb-3" style="aspect-ratio: 16/10; background: linear-gradient(135deg, #2a1f18 0%, #3d2b1f 100%);"></div>
                <h5 class="fw-semibold mb-2">Ebony</h5>
                <p class="text-jaced-muted small mb-0">Hitam pekat untuk statement piece yang berani.</p>
            </div>
        </div>
    </div>
</section>

@endsection