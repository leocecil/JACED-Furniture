@extends('base.base')

@section('title', 'Home — JACED Furniture')

@section('content')

    {{-- ============== HERO BLOCK ============== --}}
    <section class="hero-section">
        <div id="heroCarousel" class="carousel slide hero-carousel-block" data-bs-ride="carousel" data-bs-interval="7000">

            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
            </div>

            <div class="carousel-inner h-100">

                {{-- SLIDE 1 --}}
                <div class="carousel-item active h-100">
                    <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?q=80&w=1600&auto=format&fit=crop');">
                        <div class="hero-overlay"></div>
                        <div class="hero-content">
                            <p class="hero-subtitle text-uppercase mb-3">/ Curated Collection 2026</p>
                            <h1 class="hero-title fw-semibold mb-3">Design Your Space</h1>
                            <p class="hero-desc mb-4">Crafted furniture for modern living.</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('shop') }}" class="btn hero-btn-primary rounded-pill px-4 py-2 fw-semibold">Shop Now</a>
                                <a href="/about" class="btn hero-btn-secondary rounded-pill px-4 py-2 fw-semibold">Learn More</a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SLIDE 2 --}}
                <div class="carousel-item h-100">
                    <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?q=80&w=1600&auto=format&fit=crop');">
                        <div class="hero-overlay"></div>
                        <div class="hero-content">
                            <p class="hero-subtitle text-uppercase mb-3">/ Built to Last</p>
                            <h1 class="hero-title fw-semibold mb-3">Solid Wood Craft</h1>
                            <p class="hero-desc mb-4">Timeless proportions, made to outlast trends.</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('shop') }}" class="btn hero-btn-primary rounded-pill px-4 py-2 fw-semibold">Shop Now</a>
                                <a href="/about" class="btn hero-btn-secondary rounded-pill px-4 py-2 fw-semibold">Learn More</a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SLIDE 3 --}}
                <div class="carousel-item h-100">
                    <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1484101403633-562f891dc89a?q=80&w=1600&auto=format&fit=crop');">
                        <div class="hero-overlay"></div>
                        <div class="hero-content">
                            <p class="hero-subtitle text-uppercase mb-3">/ Quiet Luxury</p>
                            <h1 class="hero-title fw-semibold mb-3">Minimal Forms</h1>
                            <p class="hero-desc mb-4">Warm materials. Honest craftsmanship.</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('shop') }}" class="btn hero-btn-primary rounded-pill px-4 py-2 fw-semibold">Shop Now</a>
                                <a href="/about" class="btn hero-btn-secondary rounded-pill px-4 py-2 fw-semibold">Learn More</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>

        </div>
    </section>

    {{-- ============== INTRO TEXT ============== --}}
    <section class="intro-section">
        <div class="container">
            <p class="intro-text text-center">
                Discover furniture crafted with care, blending timeless craftsmanship, comfort, and beauty.
                <span class="intro-text-muted">Designed for modern living with refined elegance in every piece.</span>
            </p>
        </div>
    </section>

    {{-- ============== STATS (DARI PUNYA KAMU) ============== --}}
    <section class="stats-section">
        <div class="container">
            <div class="row text-center g-4 align-items-center">
                <div class="col-md-4">
                    <i class="fas fa-hammer fs-2 mb-3"></i>
                    <h5 class="fw-semibold mb-2">Hand-crafted in Surabaya</h5>
                    <p class="text-jaced-muted mb-0 small">Carefully made by local artisans.</p>
                </div>
                <div class="col-md-4 stats-divider">
                    <i class="fas fa-truck fs-2 mb-3"></i>
                    <h5 class="fw-semibold mb-2">Free Shipping Java & Bali</h5>
                    <p class="text-jaced-muted mb-0 small">Secure premium delivery experience.</p>
                </div>
                <div class="col-md-4">
                    <i class="fas fa-shield-alt fs-2 mb-3"></i>
                    <h5 class="fw-semibold mb-2">15-Year Warranty</h5>
                    <p class="text-jaced-muted mb-0 small">Long-term structural guarantee.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============== CATEGORIES (product types, beda dari Rooms di bawah) ============== --}}
    <section class="categories-section">
        <div class="container">
            <div class="section-header d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
                <div>
                    <p class="section-label">/ Categories</p>
                    <h2 class="section-title">Shop by product type</h2>
                </div>
                <a href="{{ route('shop') }}" class="btn-browse rounded-pill px-3 py-2">
                    Browse all categories <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-3 category-grid">
                <div class="col-6 col-md-3">
                    <a href="{{ route('shop', ['category' => ['seating']]) }}" class="category-card d-block text-decoration-none">
                        <img src="https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?q=80&w=800&auto=format&fit=crop" alt="Seating">
                        <div class="category-overlay">
                            <span class="category-name">Seating</span>
                            <span class="category-num">01</span>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('shop', ['category' => ['tables']]) }}" class="category-card d-block text-decoration-none">
                        <img src="https://images.unsplash.com/photo-1577140917170-285929fb55b7?q=80&w=800&auto=format&fit=crop" alt="Tables">
                        <div class="category-overlay">
                            <span class="category-name">Tables</span>
                            <span class="category-num">02</span>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('shop', ['category' => ['sofas']]) }}" class="category-card d-block text-decoration-none">
                        <img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800&auto=format&fit=crop" alt="Sofas">
                        <div class="category-overlay">
                            <span class="category-name">Sofas</span>
                            <span class="category-num">03</span>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('shop', ['category' => ['storage']]) }}" class="category-card d-block text-decoration-none">
                        <img src="https://images.unsplash.com/photo-1594620302200-9a762244a156?q=80&w=800&auto=format&fit=crop" alt="Storage">
                        <div class="category-overlay">
                            <span class="category-name">Storage</span>
                            <span class="category-num">04</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>


    {{-- ============== BEST SELLER LIST (horizontal rows ala Aunara) ============== --}}
    <section class="bestseller-section">
        <div class="container">
            <div class="bestseller-card">
                <div class="section-header d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
                    <div>
                        <p class="section-label">/ Best Seller</p>
                        <h2 class="section-title" style="max-width: 320px;">Timeless pieces for modern living</h2>
                    </div>
                    <a href="{{ route('shop') }}" class="btn-browse rounded-pill px-3 py-2">
                        See all best sellers <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="bestseller-list">
                    @forelse($recommended->take(4) as $product)
                        <a href="{{ route('product.show', $product->slug) }}" class="bestseller-row text-decoration-none">
                            <div class="bestseller-name">
                                <small class="text-uppercase bestseller-cat">{{ $product->category->name ?? 'Furniture' }}</small>
                                <h5 class="mb-0 fw-semibold">{{ $product->name }}</h5>
                            </div>
                            <p class="bestseller-desc mb-0">
                                Crafted with premium materials, blending natural form with modern function for any room.
                            </p>
                            <div class="bestseller-img-wrap">
                                <img src="{{ $product->main_image }}" alt="{{ $product->name }}">
                            </div>
                            <div class="bestseller-price-wrap">
                                <span class="bestseller-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="btn-see-details rounded-pill px-3 py-2">
                                    See Details <i class="fas fa-arrow-right ms-1" style="font-size: 10px;"></i>
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-5 text-jaced-muted">No best sellers yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    {{-- ============== SHOP BY ROOM (2x2 grid, gambar besar) ============== --}}
    <section class="rooms-section">
        <div class="container">
            <div class="section-header d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
                <div>
                    <p class="section-label">/ Shop by Room</p>
                    <h2 class="section-title">Find pieces for every space</h2>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <a href="{{ route('shop', ['room' => 'living-room']) }}" class="room-card d-block text-decoration-none">
                        <img src="https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?q=80&w=1200&auto=format&fit=crop" alt="Living Room">
                        <span class="room-label">Living Room</span>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('shop', ['room' => 'bedroom']) }}" class="room-card d-block text-decoration-none">
                        <img src="https://images.unsplash.com/photo-1540518614846-7eded433c457?q=80&w=1200&auto=format&fit=crop" alt="Bedroom">
                        <span class="room-label">Bedroom</span>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('shop', ['room' => 'dining-room']) }}" class="room-card d-block text-decoration-none">
                        <img src="https://images.unsplash.com/photo-1617806118233-18e1de247200?q=80&w=1200&auto=format&fit=crop" alt="Dining Room">
                        <span class="room-label">Dining Room</span>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('shop', ['room' => 'office']) }}" class="room-card d-block text-decoration-none">
                        <img src="https://images.unsplash.com/photo-1524758631624-e2822e304c36?q=80&w=1200&auto=format&fit=crop" alt="Office">
                        <span class="room-label">Office</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============== TESTIMONIALS (DARI PUNYA KAMU) ============== --}}
    <section class="testimonials-section">
        <div class="container">
            <div class="section-header d-flex justify-content-between align-items-end mb-5 flex-wrap gap-3">
                <div>
                    <p class="section-label">/ Testimonials</p>
                    <h2 class="section-title">What our clients say</h2>
                </div>
                <p class="text-jaced-muted mb-0 small" style="max-width: 280px;">
                    Our customers feedback reflects the quality and trust we have built together.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="mb-3 text-warning">★★★★★</div>
                        <p class="text-jaced-muted mb-4">"Beautiful craftsmanship and surprisingly solid finish."</p>
                        <div class="d-flex align-items-center">
                            <img src="https://i.pravatar.cc/60?img=1" class="rounded-circle me-3" width="48" height="48">
                            <div>
                                <h6 class="mb-0 fw-semibold">Jonathan Lee</h6>
                                <small class="text-jaced-muted">Interior Designer</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="mb-3 text-warning">★★★★★</div>
                        <p class="text-jaced-muted mb-4">"The walnut dining table completely transformed our space."</p>
                        <div class="d-flex align-items-center">
                            <img src="https://i.pravatar.cc/60?img=5" class="rounded-circle me-3" width="48" height="48">
                            <div>
                                <h6 class="mb-0 fw-semibold">Amelia Hart</h6>
                                <small class="text-jaced-muted">Architect</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="mb-3 text-warning">★★★★★</div>
                        <p class="text-jaced-muted mb-4">"Minimal, elegant, and genuinely premium quality."</p>
                        <div class="d-flex align-items-center">
                            <img src="https://i.pravatar.cc/60?img=12" class="rounded-circle me-3" width="48" height="48">
                            <div>
                                <h6 class="mb-0 fw-semibold">Michael Tan</h6>
                                <small class="text-jaced-muted">Creative Director</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============== STYLES ============== --}}
    <style>
        /* ===== GLOBAL ===== */
        body { background-color: var(--jaced-caramel-bg) !important; }
        .container { max-width: 1280px; }

        /* ===== HERO SECTION (FULL SCREEN, edge-to-edge) ===== */
        .hero-section {
            padding: 0;
            margin: 0;
            position: relative;
        }
        .hero-carousel-block {
            position: relative;
            border-radius: 0;
            overflow: hidden;
            height: 100vh;
            min-height: 700px;
            box-shadow: none;
        }
        .hero-slide {
            width: 100%;
            height: 100vh;
            min-height: 700px;
            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            align-items: center;
        }
        .hero-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(90deg, rgba(39,46,29,0.75) 0%, rgba(39,46,29,0.35) 55%, rgba(39,46,29,0.15) 100%),
                linear-gradient(180deg, rgba(39,46,29,0.45) 0%, rgba(39,46,29,0) 25%);
        }
        .hero-content {
            position: relative;
            z-index: 2;
            color: var(--jaced-cream);
            padding-left: clamp(32px, 8vw, 120px);
            max-width: 780px;
        }
        .hero-subtitle {
            letter-spacing: 0.3em;
            font-size: 12px;
            opacity: 0.85;
            color: var(--jaced-caramel);
        }
        .hero-title {
            font-size: clamp(3rem, 7vw, 7rem);
            line-height: 0.95;
            letter-spacing: -0.05em;
        }
        .hero-desc {
            font-size: 18px;
            opacity: 0.9;
            max-width: 420px;
        }
        .hero-btn-primary {
            background-color: var(--jaced-caramel);
            color: var(--jaced-cream);
            border: none;
            transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .hero-btn-primary:hover {
            background-color: #b8895c;
            color: var(--jaced-cream);
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(201, 154, 107, 0.35);
        }
        .hero-btn-secondary {
            background-color: var(--jaced-cream);
            color: var(--jaced-brown-dark);
            border: none;
            transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .hero-btn-secondary:hover {
            background-color: white;
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
        }

        /* ===== FLOATING PRODUCT CARD ===== */
        .hero-floating-card {
            position: absolute;
            bottom: 60px;
            right: clamp(24px, 6vw, 80px);
            background: rgba(242, 237, 230, 0.92);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            padding: 14px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 280px;
            z-index: 5;
            color: var(--jaced-brown-dark);
            transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
            box-shadow: 0 12px 40px rgba(0,0,0,0.18);
        }
        .hero-floating-card:hover {
            color: var(--jaced-brown-dark);
            transform: translateY(-4px);
            background: rgba(242, 237, 230, 1);
            box-shadow: 0 16px 40px rgba(0,0,0,0.18);
        }
        .hero-floating-img {
            width: 56px;
            height: 56px;
            border-radius: 10px;
            overflow: hidden;
            flex-shrink: 0;
            background: var(--jaced-input);
        }
        .hero-floating-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .hero-floating-info { flex: 1; }
        .hero-floating-tag {
            font-size: 10px;
            color: var(--jaced-muted);
            margin: 0;
            letter-spacing: 0.1em;
        }
        .hero-floating-name {
            font-size: 13px;
            font-weight: 600;
            margin: 2px 0;
            line-height: 1.2;
        }
        .hero-floating-price {
            font-size: 12px;
            font-weight: 500;
            margin: 0;
            color: var(--jaced-caramel);
        }

        /* ===== CAROUSEL INDICATORS & CONTROLS ===== */
        .carousel-indicators {
            margin-bottom: 24px;
            z-index: 4;
        }
        .carousel-indicators [data-bs-target] {
            width: 36px;
            height: 2px;
            border-radius: 99px;
            margin: 0 4px;
            opacity: 0.4;
            background: var(--jaced-cream);
            border: none;
        }
        .carousel-indicators .active { opacity: 1; }
        .carousel-control-prev, .carousel-control-next { width: 6%; opacity: 0.4; }
        .carousel-control-prev:hover, .carousel-control-next:hover { opacity: 1; }
        .carousel-control-prev-icon, .carousel-control-next-icon {
            width: 1.5rem;
            height: 1.5rem;
        }

        /* HERO ANIMATIONS */
        .hero-subtitle, .hero-title, .hero-desc, .hero-content .d-flex {
            opacity: 0;
            transform: translateX(-60px);
            filter: blur(6px);
            transition: opacity 1s ease, transform 1.2s cubic-bezier(0.22, 1, 0.36, 1), filter 1s ease;
        }
        .carousel-item.active.animate-in .hero-subtitle {
            opacity: 1; transform: translateX(0); filter: blur(0); transition-delay: 0.1s;
        }
        .carousel-item.active.animate-in .hero-title {
            opacity: 1; transform: translateX(0); filter: blur(0); transition-delay: 0.25s;
        }
        .carousel-item.active.animate-in .hero-desc {
            opacity: 1; transform: translateX(0); filter: blur(0); transition-delay: 0.4s;
        }
        .carousel-item.active.animate-in .hero-content .d-flex {
            opacity: 1; transform: translateX(0); filter: blur(0); transition-delay: 0.55s;
        }

        /* ===== INTRO TEXT ===== */
        .intro-section {
            padding: 48px 24px 24px;
        }
        .intro-text {
            font-size: 17px;
            line-height: 1.7;
            color: var(--jaced-brown-dark);
            max-width: 640px;
            margin: 0 auto;
        }
        .intro-text-muted { color: var(--jaced-muted); }

        /* ===== STATS ===== */
        .stats-section {
            padding: 48px 24px;
        }
        .stats-section h5 { font-size: 16px; }
        .stats-section i { color: var(--jaced-caramel); }
        .stats-divider {
            border-left: 1px solid var(--jaced-input);
            border-right: 1px solid var(--jaced-input);
        }
        @media (max-width: 768px) {
            .stats-divider { border: none; }
        }

        /* ===== SECTION HEADER (label + title) ===== */
        .section-label {
            font-size: 11px;
            letter-spacing: 0.25em;
            color: var(--jaced-muted);
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .section-title {
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            font-weight: 600;
            letter-spacing: -0.02em;
            margin: 0;
            color: var(--jaced-brown-dark);
        }
        .btn-browse {
            background: transparent;
            border: 1px solid var(--jaced-input);
            color: var(--jaced-brown-dark);
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        .btn-browse:hover {
            background: var(--jaced-brown-dark);
            color: var(--jaced-cream);
            border-color: var(--jaced-brown-dark);
        }

        /* ===== CATEGORIES ===== */
        .categories-section {
            padding: 32px 24px 48px;
        }
        .category-card {
            position: relative;
            display: block;
            border-radius: 18px;
            overflow: hidden;
            aspect-ratio: 3/4;
            transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.5s ease;
        }
        .category-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.8s cubic-bezier(0.22, 1, 0.36, 1), filter 0.5s ease;
        }
        .category-card:hover img {
            transform: scale(1.08);
        }
        .category-overlay {
            position: absolute;
            inset: 0;
            padding: 18px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: linear-gradient(180deg, rgba(0,0,0,0) 30%, rgba(39,46,29,0.55) 100%);
        }
        .category-name {
            color: var(--jaced-cream);
            font-weight: 600;
            font-size: 15px;
            letter-spacing: -0.01em;
        }
        .category-num {
            color: rgba(242, 237, 230, 0.55);
            font-size: 18px;
            font-weight: 500;
            align-self: flex-end;
        }
        .category-grid:hover .category-card:not(:hover) {
            transform: scale(0.97);
            opacity: 0.7;
        }
        .category-grid:hover .category-card:not(:hover) img {
            filter: brightness(0.6) saturate(0.7);
        }

        /* ===== BESTSELLER LIST ===== */
        .bestseller-section {
            padding: 32px 24px 48px;
        }
        .bestseller-card {
            background: var(--jaced-card);
            border-radius: 24px;
            padding: 40px 32px;
        }
        .bestseller-list {
            display: flex;
            flex-direction: column;
        }
        .bestseller-row {
            display: grid;
            grid-template-columns: 220px 1fr 100px 140px;
            gap: 24px;
            align-items: center;
            padding: 20px 8px;
            border-bottom: 1px solid var(--jaced-input);
            color: var(--jaced-brown-dark);
            border-radius: 12px;
            transform: scale(1);
            transition: background 0.35s ease, padding 0.35s ease, transform 0.35s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.35s ease;
        }
        .bestseller-row:last-child { border-bottom: none; }
        .bestseller-row:hover {
            background: rgba(201, 154, 107, 0.08);
            padding-left: 20px;
            padding-right: 20px;
            transform: scale(1.015);
            box-shadow: 0 8px 28px rgba(39, 46, 29, 0.08);
            color: var(--jaced-brown-dark);
            position: relative;
            z-index: 2;
        }
        .bestseller-cat {
            color: var(--jaced-caramel);
            letter-spacing: 0.2em;
            font-size: 10px;
            display: block;
            margin-bottom: 4px;
        }
        .bestseller-name h5 {
            font-size: 16px;
            letter-spacing: -0.01em;
        }
        .bestseller-desc {
            font-size: 13px;
            color: var(--jaced-muted);
            line-height: 1.6;
        }
        .bestseller-img-wrap {
            width: 90px;
            height: 70px;
            border-radius: 10px;
            overflow: hidden;
            background: var(--jaced-input);
            justify-self: end;
        }
        .bestseller-img-wrap img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform 0.4s ease;
        }
        .bestseller-row:hover .bestseller-img-wrap img { transform: scale(1.08); }
        .bestseller-price-wrap {
            position: relative;
            text-align: right;
            min-height: 38px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }
        .bestseller-price {
            font-size: 15px;
            font-weight: 600;
            color: var(--jaced-brown-dark);
            opacity: 1;
            transform: translateX(0);
            transition: opacity 0.35s ease, transform 0.35s ease;
        }
        .btn-see-details {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translate(8px, -50%);
            background: var(--jaced-caramel);
            color: var(--jaced-cream);
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.05em;
            opacity: 0;
            pointer-events: none;
            white-space: nowrap;
            transition: opacity 0.35s ease, transform 0.35s ease, background 0.3s ease;
        }
        .bestseller-row:hover .bestseller-price {
            opacity: 0;
            transform: translateX(-8px);
        }
        .bestseller-row:hover .btn-see-details {
            opacity: 1;
            transform: translate(0, -50%);
        }
        .bestseller-row:hover .btn-see-details:hover {
            background: var(--jaced-brown-dark);
        }
        @media (max-width: 768px) {
            .bestseller-row {
                grid-template-columns: 1fr 80px;
                gap: 12px;
            }
            .bestseller-desc, .bestseller-price-wrap { display: none; }
        }

        /* ===== ROOMS ASYMMETRIC ===== */
        .rooms-section {
            padding: 32px 24px 48px;
        }
        .room-card {
            position: relative;
            display: block;
            border-radius: 20px;
            overflow: hidden;
            height: 440px;
            transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .room-card:hover {
            transform: translateY(-6px);
        }
        .room-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.8s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .room-card:hover img { transform: scale(1.05); }
        .room-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0) 50%, rgba(39,46,29,0.5) 100%);
        }
        .room-label {
            position: absolute;
            bottom: 24px;
            left: 28px;
            color: var(--jaced-cream);
            font-size: 22px;
            font-weight: 600;
            letter-spacing: -0.01em;
            z-index: 2;
        }

        /* ===== TESTIMONIALS ===== */
        .testimonials-section {
            padding: 48px 24px 80px;
        }
        .testimonial-card {
            background: var(--jaced-card);
            border-radius: 18px;
            padding: 28px;
            height: 100%;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }
        .testimonial-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(39, 46, 29, 0.08);
        }
    </style>

    <script>
        // Hero animation trigger
        window.addEventListener('load', () => {
            const firstSlide = document.querySelector('.carousel-item.active');
            if (firstSlide) {
                setTimeout(() => firstSlide.classList.add('animate-in'), 200);
            }
        });

        const heroCarousel = document.getElementById('heroCarousel');
        if (heroCarousel) {
            heroCarousel.addEventListener('slide.bs.carousel', () => {
                document.querySelectorAll('.carousel-item').forEach(el => el.classList.remove('animate-in'));
            });
            heroCarousel.addEventListener('slid.bs.carousel', () => {
                const active = document.querySelector('.carousel-item.active');
                if (active) setTimeout(() => active.classList.add('animate-in'), 100);
            });
        }
    </script>

@endsection