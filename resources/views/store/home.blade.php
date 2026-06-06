@extends('base.base')

@section('title', 'Home — JACED Furniture')

@section('content')

    {{-- ============== HERO BLOCK ============== --}}
    <section class="hero-section">
        <div id="heroCarousel" class="carousel slide hero-carousel-block" data-bs-ride="carousel" data-bs-interval="7000" data-bs-pause="false" data-bs-touch="true">

            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
            </div>

            <div class="caro    usel-inner" style="height: 100svh; overflow: hidden;">
                <div class="carousel-item active">
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
                <div class="carousel-item">
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
                <div class="carousel-item">
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
            <p class="intro-text text-center" data-reveal="fade-up">
                Discover furniture crafted with care, blending timeless craftsmanship, comfort, and beauty.
                <span class="intro-text-muted">Designed for modern living with refined elegance in every piece.</span>
            </p>
        </div>
    </section>

    {{-- ============== STATS ============== --}}
    <section class="stats-section">
        <div class="container">
            <div class="row text-center g-4 align-items-center">
                <div class="col-md-4" data-reveal="slide-up" data-reveal-delay="0">
                    <i class="fas fa-hammer fs-2 mb-3"></i>
                    <h5 class="fw-semibold mb-2">Hand-crafted in Surabaya</h5>
                    <p class="text-jaced-muted mb-0 small">Carefully made by local artisans.</p>
                </div>
                <div class="col-md-4 stats-divider" data-reveal="slide-up" data-reveal-delay="150">
                    <i class="fas fa-truck fs-2 mb-3"></i>
                    <h5 class="fw-semibold mb-2">Fast Shipping in Java</h5>
                    <p class="text-jaced-muted mb-0 small">Secure premium delivery experience.</p>
                </div>
                <div class="col-md-4" data-reveal="slide-up" data-reveal-delay="300">
                    <i class="fas fa-shield-alt fs-2 mb-3"></i>
                    <h5 class="fw-semibold mb-2">15-Year Warranty</h5>
                    <p class="text-jaced-muted mb-0 small">Long-term structural guarantee.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============== CATEGORIES ============== --}}
    <section class="categories-section">
        <div class="container">
            <div class="section-header d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
                <div data-reveal="slide-right">
                    <p class="section-label">/ Categories</p>
                    <h2 class="section-title">Shop by product type</h2>
                </div>
                <a href="{{ route('shop') }}" class="btn-browse rounded-pill px-3 py-2" data-reveal="slide-left" data-reveal-delay="200">
                    Browse all categories <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="category-scroll-track" id="categoryTrack">
                @php
                    $catImages = [
                        'sofa'        => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800&auto=format&fit=crop',
                        'chair'       => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?q=80&w=800&auto=format&fit=crop',
                        'table'       => 'https://images.unsplash.com/photo-1577140917170-285929fb55b7?q=80&w=800&auto=format&fit=crop',
                        'bed'         => 'https://images.unsplash.com/photo-1540518614846-7eded433c457?q=80&w=800&auto=format&fit=crop',
                        'lighting'    => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?q=80&w=800&auto=format&fit=crop',
                        'storage'     => 'https://images.unsplash.com/photo-1594620302200-9a762244a156?q=80&w=800&auto=format&fit=crop',
                        'bathroom'    => 'https://images.unsplash.com/photo-1552321554-5fefe8c9ef14?q=80&w=800&auto=format&fit=crop',
                        'decorations' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?q=80&w=800&auto=format&fit=crop',
                    ];
                @endphp
                @foreach($categories as $index => $cat)
                    <a href="{{ route('shop', ['category' => [$cat->slug]]) }}"
                        class="category-slide-item text-decoration-none"
                        data-reveal="fade"
                        data-reveal-delay="{{ $index * 80 }}">
                            <div class="category-slide-img">
                                <img src="{{ $catImages[$cat->slug] ?? 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800&auto=format&fit=crop' }}"
                                    alt="{{ $cat->name }}">
                            </div>
                            <div class="category-slide-overlay"></div>
                            <div class="category-slide-label">
                                <span class="category-slide-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="category-slide-name">{{ $cat->name }}</span>
                            </div>
                        </a>
                    @endforeach
            </div>
        </div>
    </section>

    {{-- ============== BEST SELLER (dari DB) ============== --}}
    <section class="bestseller-section">
        <div class="container">
            <div class="bestseller-card" data-reveal="fade-up">
                <div class="section-header d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
                    <div data-reveal="slide-right" data-reveal-delay="100">
                        <p class="section-label">/ Best Seller</p>
                        <h2 class="section-title" style="max-width: 320px;">Timeless pieces for modern living</h2>
                    </div>
                    <a href="{{ route('shop') }}" class="btn-browse rounded-pill px-3 py-2" data-reveal="slide-left" data-reveal-delay="250">
                        See all best sellers <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="bestseller-list">
                    @forelse($recommended as $index => $product)
                        <a href="{{ route('product.show', $product->slug) }}" class="bestseller-row text-decoration-none" data-reveal="slide-up" data-reveal-delay="{{ $index * 100 }}">
                            <div class="bestseller-name">
                                <small class="text-uppercase bestseller-cat">{{ $product->category->name ?? 'Furniture' }}</small>
                                <h5 class="mb-0 fw-semibold">{{ $product->name }}</h5>
                            </div>
                            <p class="bestseller-desc mb-0">
                                {{ Str::limit($product->description, 80) }}
                            </p>
                            <div class="bestseller-img-wrap">
                                <img src="{{ $product->main_image }}" alt="{{ $product->name }}">
                            </div>
                            <div class="bestseller-price-wrap">
                                <span class="bestseller-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="btn-see-details rounded-pill px-3 py-2">
                                     <span>See details</span> <i class="fas fa-arrow-right ms-1" style="font-size: 10px;"></i>
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-5 text-jaced-muted">No products yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    {{-- ============== SHOP BY ROOM ============== --}}
    <section class="rooms-section">
        <div class="container">
            <div class="section-header d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
                <div data-reveal="slide-right">
                    <p class="section-label">/ Shop by Room</p>
                    <h2 class="section-title">Find pieces for every space</h2>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6" data-reveal="slide-up" data-reveal-delay="0">
                    <a href="{{ route('shop', ['category' => ['sofa', 'chair', 'table']]) }}" class="room-card d-block text-decoration-none">
                        <img src="https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?q=80&w=1200&auto=format&fit=crop" alt="Living Room">
                        <span class="room-label">Living Room</span>
                    </a>
                </div>
                <div class="col-md-6" data-reveal="slide-up" data-reveal-delay="150">
                    <a href="{{ route('shop', ['category' => ['bed']]) }}" class="room-card d-block text-decoration-none">
                        <img src="https://images.unsplash.com/photo-1540518614846-7eded433c457?q=80&w=1200&auto=format&fit=crop" alt="Bedroom">
                        <span class="room-label">Bedroom</span>
                    </a>
                </div>
                <div class="col-md-6" data-reveal="slide-up" data-reveal-delay="300">
                    <a href="{{ route('shop', ['category' => ['table']]) }}" class="room-card d-block text-decoration-none">
                        <img src="https://images.unsplash.com/photo-1617806118233-18e1de247200?q=80&w=1200&auto=format&fit=crop" alt="Dining Room">
                        <span class="room-label">Dining Room</span>
                    </a>
                </div>
                <div class="col-md-6" data-reveal="slide-up" data-reveal-delay="450">
                    <a href="{{ route('shop', ['category' => ['storage', 'lighting']]) }}" class="room-card d-block text-decoration-none">
                        <img src="https://images.unsplash.com/photo-1524758631624-e2822e304c36?q=80&w=1200&auto=format&fit=crop" alt="Office">
                        <span class="room-label">Office</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="feature-promo-section" data-reveal="fade-up">
        <div class="feature-promo-full"
            style="background-image:url('https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=2200&auto=format&fit=crop');">
            
            {{-- Shadow/Gradasi Gelap di BAGIAN ATAS Feature Promo --}}
            <div class="feature-promo-top-shadow"></div>

            {{-- Overlay Gelap Keseluruhan agar teks lebih terbaca --}}
            <div class="feature-promo-overlay"></div>

            {{-- Konten Teks yang posisinya BENAR-BENAR DI TENGAH --}}
            <div class="feature-promo-content text-center" data-reveal="slide-up" data-reveal-delay="200">
                <p class="section-label" style="color: var(--jaced-caramel);">
                    / Refined Living
                </p>
                <h2 class="feature-promo-title">
                    Designed For Spaces That Feel Effortless
                </h2>
                <p class="feature-promo-desc mx-auto"> {{-- Tambah mx-auto agar deskripsi ikut tengah --}}
                    Thoughtfully crafted furniture made to bring warmth,
                    balance, and understated character into everyday living.
                </p>
                <a href="{{ route('shop') }}" class="feature-promo-link justify-content-center"> {{-- Tambah justify-center --}}
                    Explore The Collection
                    <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- ============== STYLES ============== --}}
    <style>
       html, body {
            scroll-behavior: auto !important;
            overflow-anchor: none;
        }

        body { background-color: var(--jaced-caramel-bg) !important; }
        .container { max-width: 1280px; }

        /* ===== SHADOW & DEPTH UPGRADES ===== */

        /* Hero bottom fade shadow */
        .hero-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 120px;
            background: linear-gradient(to bottom, transparent, var(--jaced-caramel-bg));
            z-index: 3;
            pointer-events: none;
        }

        /* Stats section subtle card */
        .stats-section {
            padding: 48px 0px 60px;
            background: var(--jaced-card);
            margin: 0 24px;
            border-radius: 24px;
            box-shadow: 0 4px 32px rgba(39, 46, 29, 0.06);
            margin-bottom: 48px;
            overflow: visible;
        }
        .stats-section i { color: var(--jaced-caramel); }

        /* Bestseller card deeper shadow */
        .bestseller-card {
            background: var(--jaced-card);
            border-radius: 24px;
            padding: 40px 32px;
            box-shadow: 0 8px 48px rgba(39, 46, 29, 0.08);
        }

        /* Room cards shadow on hover */
        .room-card {
            box-shadow: 0 4px 24px rgba(39, 46, 29, 0.08);
            transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.5s ease;
        }
        .room-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 48px rgba(39, 46, 29, 0.14);
        }

        /* Section divider subtle shadow buat intro text */
        .intro-section {
            padding: 56px 24px 32px;
            position: relative;
        }
        .intro-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 2px;
            background: var(--jaced-caramel);
            opacity: 0.4;
            border-radius: 999px;
        }
        .hero-section { 
            padding: 0; margin: 0; position: relative;
            overflow: hidden;
            height: 100vh;
            min-height: 700px;
        }
        .hero-carousel-block {
            position: relative; border-radius: 0; box-shadow: none;
            height: 100%;
        }
        .hero-carousel-block .carousel-inner {
            height: 100%;
            overflow: hidden;
            position: relative;
        }
        .hero-carousel-block .carousel-item {
            height: 100%;
        }
        .hero-slide {
            width: 100%;
            height: 100%;
            min-height: 700px;
            background-size: cover; background-position: center;
            position: relative; display: flex; align-items: center;
        }

        @media (max-width: 768px) {
            .hero-carousel-block .carousel-inner {
                display: block !important;
                overflow: hidden !important;
                height: 100svh !important;
            }
            #heroCarousel .carousel-item {
                display: none !important;
            }
            #heroCarousel .carousel-item.active {
                display: block !important;
                height: 100svh !important;
            }
            #heroCarousel .carousel-indicators,
            #heroCarousel .carousel-control-prev,
            #heroCarousel .carousel-control-next {
                display: none !important;
            }
            .hero-section {
                height: 100svh !important;
                min-height: unset !important;
                overflow: hidden !important;
            }
            .hero-carousel-block { height: 100svh !important; }
            .hero-slide { height: 100svh !important; min-height: unset !important; }
        }

        /* ===== KEN BURNS HERO ===== */
        .carousel-item.active .hero-slide {
            animation: kenBurns 12s ease-out forwards;
        }
        @keyframes kenBurns {
            0% {
                background-size: 100%;
                background-position: center center;
            }
            100% {
                background-size: 115%;
                background-position: center 55%;
            }
        }
        .hero-overlay {
            position: absolute; inset: 0;
            background:
                linear-gradient(90deg, rgba(39,46,29,0.75) 0%, rgba(39,46,29,0.35) 55%, rgba(39,46,29,0.15) 100%),
                linear-gradient(180deg, rgba(39,46,29,0.45) 0%, rgba(39,46,29,0) 25%);
        }
        .hero-content {
            position: relative; z-index: 2; color: var(--jaced-caramel-bg);
            padding-left: clamp(32px, 8vw, 120px); max-width: 780px;
        }
        .hero-subtitle { letter-spacing: 0.3em; font-size: 12px; opacity: 0.85; color: var(--jaced-caramel); }
        .hero-title { font-size: clamp(3rem, 7vw, 7rem); line-height: 0.95; letter-spacing: -0.05em; }
        .hero-desc { font-size: 18px; opacity: 0.9; max-width: 420px; }
        .hero-btn-primary {
            background-color: var(--jaced-caramel); color: var(--jaced-cream); border: none;
            transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .hero-btn-primary:hover {
            background-color: #b8895c; color: var(--jaced-cream);
            transform: translateY(-2px); box-shadow: 0 12px 28px rgba(201,154,107,0.35);
        }
        .hero-btn-secondary {
            background-color: var(--jaced-cream); color: var(--jaced-brown-dark); border: none;
            transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .hero-btn-secondary:hover {
            background-color: white; transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(0,0,0,0.15);
        }

        /* Carousel controls */
        .carousel-indicators { margin-bottom: 24px; z-index: 4; }
        .carousel-indicators [data-bs-target] {
            width: 36px; height: 2px; border-radius: 99px;
            margin: 0 4px; opacity: 0.4; background: var(--jaced-cream); border: none;
        }
        .carousel-indicators .active { opacity: 1; }
        .carousel-control-prev, .carousel-control-next {
            width: 80px; height: 80px; top: 50%; bottom: auto;
            transform: translateY(-50%); opacity: 1; z-index: 10;
            display: flex; align-items: center; justify-content: center;
        }
        .carousel-control-prev { left: 24px; }
        .carousel-control-next { right: 24px; }
        .carousel-control-prev-icon, .carousel-control-next-icon {
            width: 48px; height: 48px;
            background-color: rgba(242,237,230,0.15);
            backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
            border-radius: 50%; background-size: 14px 14px;
            background-position: center; background-repeat: no-repeat;
            border: 1px solid rgba(242,237,230,0.3);
            transition: all 0.3s ease;
        }
        .carousel-control-prev:hover .carousel-control-prev-icon,
        .carousel-control-next:hover .carousel-control-next-icon {
            background-color: var(--jaced-cream); border-color: var(--jaced-cream); transform: scale(1.1);
        }
        .carousel-control-prev:hover .carousel-control-prev-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23272e1d'%3E%3Cpath d='M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z'/%3E%3C/svg%3E");
        }
        .carousel-control-next:hover .carousel-control-next-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23272e1d'%3E%3Cpath d='M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
        }
        @media (max-width: 768px) {
            .carousel-control-prev { left: 12px; }
            .carousel-control-next { right: 12px; }
            .carousel-control-prev, .carousel-control-next { width: 60px; height: 60px; }
            .carousel-control-prev-icon, .carousel-control-next-icon { width: 40px; height: 40px; }
        }

        /* Hero animations */
        .hero-subtitle, .hero-title, .hero-desc, .hero-content .d-flex {
            opacity: 0; transform: translateX(-60px); filter: blur(6px);
            transition: opacity 1s ease, transform 1.2s cubic-bezier(0.22,1,0.36,1), filter 1s ease;
        }
        .carousel-item.active.animate-in .hero-subtitle { opacity:1; transform:translateX(0); filter:blur(0); transition-delay:0.1s; }
        .carousel-item.active.animate-in .hero-title   { opacity:1; transform:translateX(0); filter:blur(0); transition-delay:0.25s; }
        .carousel-item.active.animate-in .hero-desc    { opacity:1; transform:translateX(0); filter:blur(0); transition-delay:0.4s; }
        .carousel-item.active.animate-in .hero-content .d-flex { opacity:1; transform:translateX(0); filter:blur(0); transition-delay:0.55s; }

        /* Scroll reveal */
        [data-reveal] {
            opacity: 0; will-change: opacity, transform;
            transition: opacity 0.9s cubic-bezier(0.22,1,0.36,1), transform 1s cubic-bezier(0.22,1,0.36,1);
        }
        [data-reveal="fade-up"]   { transform: translateY(40px); }
        [data-reveal="slide-up"]  { transform: translateY(60px); }
        [data-reveal="fade"] { transform: none; }
        [data-reveal="slide-right"] { transform: translateX(-50px); }
        [data-reveal="slide-left"]  { transform: translateX(50px); }
        [data-reveal].is-visible  { opacity: 1; transform: translate(0,0); }
        @media (prefers-reduced-motion: reduce) {
            [data-reveal] { opacity:1 !important; transform:none !important; transition:none !important; }
        }

        .intro-section { padding: 48px 24px 24px; }
        .intro-text { font-size:17px; line-height:1.7; color:var(--jaced-brown-dark); max-width:640px; margin:0 auto; }
        .intro-text-muted { color: var(--jaced-muted); }

        .stats-section h5 { font-size: 16px; }
        .stats-section i { color: var(--jaced-caramel); }
        .stats-divider { border-left: 1px solid var(--jaced-input); border-right: 1px solid var(--jaced-input); }
        @media (max-width: 768px) { .stats-divider { border: none; } }

        .section-label { font-size:11px; letter-spacing:0.25em; color:var(--jaced-muted); margin-bottom:8px; text-transform:uppercase; }
        .section-title { font-size:clamp(1.6rem,3vw,2.2rem); font-weight:600; letter-spacing:-0.02em; margin:0; color:var(--jaced-brown-dark); }
        .btn-browse {
            background:transparent; border:1px solid var(--jaced-input); color:var(--jaced-brown-dark);
            font-size:12px; font-weight:500; transition:all 0.3s ease; white-space:nowrap;
        }
        .btn-browse:hover { background:var(--jaced-brown-dark); color:var(--jaced-cream); border-color:var(--jaced-brown-dark); }

        .categories-section { padding: 60px 0 60px; overflow: visible; }
        .categories-section .container {
            overflow: visible;
            padding-right: 0;
        }
        .categories-section .section-header {
            margin-bottom: 32px;
            padding: 0 24px;
        }
        .category-scroll-track {
            display: flex;
            gap: 14px;
            overflow-x: auto;
            overflow-y: visible;
            scroll-behavior: smooth;
            cursor: grab;
            padding: 8px 24px 16px;
            padding-right: 80px;
            scrollbar-width: none;
            -ms-overflow-style: none;
            user-select: none;
            padding-bottom: 24px;
        }
        .category-scroll-track::-webkit-scrollbar { display: none; }
        .category-scroll-track.is-dragging {
            cursor: grabbing;
            scroll-behavior: auto;
        }
        .category-slide-item {
            flex: 0 0 340px;
            position: relative;
            border-radius: 18px;
            overflow: hidden;
            aspect-ratio: 3/4;
            display: block;
            transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.4s ease;
        }
        .category-slide-img {
            width: 100%;
            height: 100%;
            position: absolute;
            inset: 0;
        }
        .category-slide-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.8s cubic-bezier(0.22, 1, 0.36, 1), filter 0.5s ease, brightness 0.5s ease;
            display: block;
        }
        .category-slide-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0) 35%, rgba(39,46,29,0.65) 100%);
            z-index: 1;
            transition: opacity 0.5s ease;
        }
        .category-slide-label {
            position: absolute;
            bottom: 18px;
            left: 18px;
            right: 18px;
            z-index: 2;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .category-slide-name {
            font-size: 16px;
            font-weight: 600;
            color: var(--jaced-cream);
            letter-spacing: -0.01em;
            text-transform: capitalize;
            line-height: 1.2;
        }
        .category-slide-num {
            font-size: 11px;
            color: rgba(242, 237, 230, 0.6);
            font-weight: 500;
            letter-spacing: 0.15em;
        }

        /* Hover: card aktif zoom, yang lain blur + grayscale */
        .category-scroll-track:hover .category-slide-item:not(:hover) {
            opacity: 0.6;
        }
        .category-scroll-track:hover .category-slide-item:not(:hover) .category-slide-img img {
            filter: brightness(0.55) saturate(0) contrast(1.1) blur(2px);
        }
        .category-slide-item:hover .category-slide-img img {
            transform: scale(1.1);
            filter: brightness(1) saturate(1);
        }
        .category-slide-item:hover {
            transform: translateY(-8px) scale(1.02);
            z-index: 10;
        }
        /* .category-card {
            position:relative; display:block; border-radius:18px; overflow:hidden;
            aspect-ratio:3/4; transition:transform 0.5s cubic-bezier(0.22,1,0.36,1), opacity 0.5s ease;
        }
        .category-card img { width:100%; height:100%; object-fit:cover; transition:transform 0.8s cubic-bezier(0.22,1,0.36,1), filter 0.5s ease; }
        .category-card:hover img { transform: scale(1.08); }
        .category-overlay {
            position:absolute; inset:0; padding:18px; display:flex;
            flex-direction:column; justify-content:space-between;
            background:linear-gradient(180deg, rgba(0,0,0,0) 30%, rgba(39,46,29,0.55) 100%);
        }   
        .category-name { color:var(--jaced-cream); font-weight:600; font-size:15px; letter-spacing:-0.01em; }
        .category-num { color:rgba(242,237,230,0.55); font-size:18px; font-weight:500; align-self:flex-end; }
        .category-grid:hover .category-card:not(:hover) { transform:scale(0.97); opacity:0.7; }
        .category-grid:hover .category-card:not(:hover) img { filter:brightness(0.6) saturate(0.7); } */

        .bestseller-section { padding: 32px 24px 48px; }
        .bestseller-card { background:var(--jaced-card); border-radius:24px; padding:40px 32px; }
        .bestseller-list { display:flex; flex-direction:column; }
        .bestseller-row {
            display:grid; grid-template-columns:220px 1fr 100px 140px;
            gap:24px; align-items:center; padding:20px 8px;
            border-bottom:1px solid var(--jaced-input); color:var(--jaced-brown-dark);
            border-radius:12px;
            transition:background 0.35s ease, padding 0.35s ease, box-shadow 0.35s ease;
        }
        .bestseller-row:last-child { border-bottom:none; }
        .bestseller-row:hover {
            background:rgba(201,154,107,0.08); padding-left:20px; padding-right:20px;
            box-shadow:0 8px 28px rgba(39,46,29,0.08); color:var(--jaced-brown-dark);
            position:relative; z-index:2;
        }
        .bestseller-cat { color:var(--jaced-caramel); letter-spacing:0.2em; font-size:10px; display:block; margin-bottom:4px; }
        .bestseller-name h5 { font-size:16px; letter-spacing:-0.01em; }
        .bestseller-desc { font-size:13px; color:var(--jaced-muted); line-height:1.6; }
        .bestseller-img-wrap { width:90px; height:70px; border-radius:10px; overflow:hidden; background:var(--jaced-input); justify-self:end; }
        .bestseller-img-wrap img { width:100%; height:100%; object-fit:cover; transition:transform 0.4s ease; }
        .bestseller-row:hover .bestseller-img-wrap img { transform:scale(1.08); }
        .bestseller-price-wrap { position:relative; text-align:right; min-height:38px; display:flex; align-items:center; justify-content:flex-end; }
        .bestseller-price { font-size:15px; font-weight:600; color:var(--jaced-brown-dark); opacity:1; transform:translateX(0); transition:opacity 0.35s ease, transform 0.35s ease; }
        .btn-see-details {
            position:absolute; right:0; top:50%; transform:translate(8px,-50%);
            background:var(--jaced-caramel); color:var(--jaced-cream);
            font-size:11px; font-weight:500; letter-spacing:0.05em;
            opacity:0; pointer-events:none; white-space:nowrap;
            transition:opacity 0.35s ease, transform 0.35s ease, color 0.4s ease;
            overflow:hidden;
        }
        .btn-see-details::before {
            content:'';
            position:absolute;
            top:0; left:-100%; width:100%; height:100%;
            background:var(--jaced-cream);
            transition:left 0.4s cubic-bezier(0.22,1,0.36,1);
            z-index:0;
        }
        .btn-see-details span, .btn-see-details i {
            position:relative; z-index:1;
        }
        .bestseller-row:hover .btn-see-details {
            opacity:1;
            transform:translate(0,-50%);
            pointer-events:auto;
        }
        .bestseller-row:hover .bestseller-price { opacity:0; transform:translateX(-8px); }
        .bestseller-row:hover .btn-see-details:hover::before { left:0; }
        .bestseller-row:hover .btn-see-details:hover { color:var(--jaced-caramel) !important; }
        @media (max-width: 768px) {
            .bestseller-row { grid-template-columns:1fr 80px; gap:12px; }
            .bestseller-desc, .bestseller-price-wrap { display:none; }
        }

        .rooms-section { padding: 32px 24px 48px; }
        .room-card { position:relative; display:block; border-radius:20px; overflow:hidden; height:440px; transition:transform 0.5s cubic-bezier(0.22,1,0.36,1); }
        .room-card:hover { transform:translateY(-6px); }
        .room-card img { width:100%; height:100%; object-fit:cover; transition:transform 0.8s cubic-bezier(0.22,1,0.36,1); }
        .room-card:hover img { transform:scale(1.05); }
        .room-card::after { content:''; position:absolute; inset:0; background:linear-gradient(180deg, rgba(0,0,0,0) 50%, rgba(39,46,29,0.5) 100%); }
        .room-label { position:absolute; bottom:24px; left:28px; color:var(--jaced-cream); font-size:22px; font-weight:600; letter-spacing:-0.01em; z-index:2; }

        /* ===== HOME RESPONSIVE PATCH ===== */

        @media (max-width:992px){

            .hero-content{
                padding-left:40px;
                padding-right:24px;
                max-width:100%;
            }

            .hero-title{
                font-size:clamp(2.5rem,8vw,4.8rem);
            }

            .hero-desc{
                font-size:16px;
                max-width:100%;
            }

            .bestseller-card{
                padding:32px 24px;
            }

            .room-card{
                height:340px;
            }

        }

        @media (max-width:768px){

            .hero-carousel-block{overflow:hidden;}

            .hero-content{
                padding:0 24px;
            }

            .hero-title{
                font-size:clamp(2rem,10vw,3.5rem);
                line-height:1;
            }

            .hero-subtitle{
                font-size:10px;
                letter-spacing:.2em;
            }

            .hero-desc{
                font-size:15px;
            }

            .stats-section{
                margin:0 12px;
                border-radius:18px;
                padding:36px 20px;
            }

            .intro-text{
                font-size:15px;
                line-height:1.8;
            }

            .category-slide-item{
                flex:0 0 240px;
            }

            .section-header{
                align-items:flex-start !important;
            }

            .section-title{
                font-size:1.8rem;
            }

            .room-card{
                height:260px;
            }

            .room-label{
                left:18px;
                bottom:18px;
                font-size:18px;
            }

            .hero-section {
                height: 100svh;
                min-height: 560px;
                overflow: hidden;
            }

        }

        @media (max-width:576px){

            .hero-title { font-size: clamp(1.6rem, 7vw, 2.2rem); line-height: 1.1; }
            .hero-desc { font-size: 13px; margin-bottom: 12px !important; }
            .hero-subtitle { font-size: 9px; letter-spacing: 0.2em; }
            .hero-content { padding: 0 20px; }

            .hero-content .d-flex{
                flex-direction: row !important;
                align-items: center;
                width: auto !important;
                gap: 8px;
                flex-wrap: nowrap !important;
            }

            .hero-content .btn{
                width: auto !important;
                padding: 8px 16px !important;
                font-size: 12px !important;
                justify-content: center;
            }

            .carousel-control-prev,
            .carousel-control-next{
                display:none;
            }

            .category-slide-item{
                flex:0 0 200px;
            }

            .bestseller-card{
                padding:24px 18px;
            }

            .rooms-section,
            .bestseller-section,
            .categories-section{
                padding-left:16px;
                padding-right:16px;
            }

        }

        @media (max-width: 992px) {
            .bestseller-row {
                grid-template-columns: 1fr 80px;
                gap: 12px;
            }
            .bestseller-desc { display: none; }
            .bestseller-price-wrap { display: none; }

            .feature-promo-full {
                background-attachment: scroll;
                height: auto;
                min-height: 360px;
                padding: 60px 24px;
            }
            .feature-promo-title { font-size: clamp(1.8rem, 5vw, 2.8rem); }
            .feature-promo-desc { font-size: 16px; }
        }

        @media (max-width: 768px) {
            .stats-section { margin: 0 12px; padding: 32px 16px; }
            .stats-section h5 { font-size: 14px; }
            .stats-section .small { font-size: 12px; }
            .stats-divider { border: none; border-top: 1px solid var(--jaced-input); border-bottom: 1px solid var(--jaced-input); padding: 24px 0; margin: 8px 0; }

            .categories-section { padding: 40px 0 36px; }
            .category-slide-item { flex: 0 0 220px; }
            .category-scroll-track { padding: 8px 16px 12px; padding-right: 60px; }

            .bestseller-section { padding: 24px 16px 36px; }
            .bestseller-card { padding: 24px 16px; }
            .bestseller-row { grid-template-columns: 1fr 70px; gap: 8px; padding: 14px 4px; }
            .bestseller-name h5 { font-size: 14px; }
            .bestseller-cat { font-size: 9px; }
            .bestseller-img-wrap { width: 70px; height: 56px; }

            .rooms-section { padding: 24px 16px 36px; }
            .room-card { height: 220px; }
            .room-label { font-size: 16px; left: 16px; bottom: 16px; }

            .feature-promo-full {
                background-attachment: scroll;
                height: auto;
                min-height: 320px;
                padding: 48px 20px;
            }
            .feature-promo-title { font-size: clamp(1.6rem, 6vw, 2.4rem); margin-bottom: 14px; }
            .feature-promo-desc { font-size: 15px; margin-bottom: 20px; }

            .intro-section { padding: 36px 16px 20px; }
            .intro-text { font-size: 15px; }

            .section-header { flex-direction: column; align-items: flex-start !important; gap: 12px; }
            .btn-browse { align-self: flex-start; }
        }

        @media (max-width: 480px) {
            .category-slide-item { flex: 0 0 180px; }
            .category-slide-name { font-size: 14px; }
            .room-card { height: 180px; }
            .feature-promo-full { padding: 40px 16px; min-height: 280px; }
            .feature-promo-title { font-size: clamp(1.4rem, 7vw, 2rem); }
            .feature-promo-desc { font-size: 14px; }
        }


        /* ===== FEATURE PROMO UPDATES (SHADOW ATAS & TEKS TENGAH) ===== */
        .feature-promo-section {
            padding: 0;
            margin-top: 100px; /* Jarak dari section atas */
            margin-bottom: 0;   /* Mepet footer */
            overflow: hidden;
            position: relative; /* Penting untuk posisi shadow */
        }

        .feature-promo-full {
            width: 100%;
            height: 60vh; /* Resolusi bingkai diperkecil sesuai permintaan sebelumnya */
            min-height: 450px;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
            display: flex;
            align-items: center;    /* Pusatkan vertikal */
            justify-content: center; /* Pusatkan horizontal */
        }

        /* Shadow/Gradasi Gelap di Bagian ATAS Section Promo */
        .feature-promo-top-shadow {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 180px;
            background: linear-gradient(to bottom, var(--jaced-caramel-bg) 0%, rgba(39,46,29,0) 100%);
            z-index: 2;
        }

        .feature-promo-overlay {
            position: absolute;
            inset: 0;
            /* Overlay keseluruhan gelap agar teks putih kontras */
            background: rgba(0, 0, 0, 0.3); 
            z-index: 1;
        }

        .feature-promo-content {
            position: relative;
            z-index: 2; /* Di atas shadow dan overlay */
            color: var(--jaced-cream);
            padding: 0 20px; /* Padding sisi */
            max-width: 800px; /* Batas lebar teks agar tidak terlalu melebar */
        }

        .feature-promo-title {
            font-size: clamp(2.2rem, 5vw, 3.8rem); /* Ukuran lebih besar agar dominan di tengah */
            font-weight: 600;
            line-height: 1.1;
            margin-bottom: 20px;
            letter-spacing: -0.02em;
        }

        .feature-promo-desc {
            font-size: 18px; /* Sedikit lebih besar untuk keterbacaan */
            opacity: 0.9;
            margin-bottom: 30px;
            max-width: 550px; /* Batasi lebar deskripsi agar rata tengah enak dilihat */
        }

        .feature-promo-link {
            color: var(--jaced-caramel);
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .feature-promo-link:hover {
            color: var(--jaced-white);
            transform: translateY(-3px); /* Hover effect ke atas */
        }
    </style>

    <script>
        // Fix carousel mobile - jalanin SEBELUM Bootstrap init
        (function() {
            if (window.innerWidth > 768) return;
            
            const style = document.createElement('style');
            style.textContent = `
                #heroCarousel .carousel-inner { display: block !important; }
                #heroCarousel .carousel-item { display: none !important; height: 0 !important; }
                #heroCarousel .carousel-item.active { display: block !important; height: 100svh !important; }
            `;
            document.head.appendChild(style);

            document.addEventListener('DOMContentLoaded', function() {
                const carousel = document.getElementById('heroCarousel');
                if (!carousel) return;
                
                const observer = new MutationObserver(function() {
                    const inner = carousel.querySelector('.carousel-inner');
                    if (inner) inner.style.removeProperty('display');
                });
                
                observer.observe(carousel, { attributes: true, subtree: true, attributeFilter: ['style'] });
            });
        })();   

        if (history.scrollRestoration) {
            history.scrollRestoration = 'manual';
        }
        window.scrollTo(0, 0);
        document.documentElement.scrollTop = 0;
        document.body.scrollTop = 0;
        // Hero entry animation
        window.addEventListener('load', () => {
            const firstSlide = document.querySelector('.carousel-item.active');
            if (firstSlide) setTimeout(() => firstSlide.classList.add('animate-in'), 200);
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

        // Scroll reveal
        (() => {
            const targets = document.querySelectorAll('[data-reveal]');
            if (!targets.length) return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const el = entry.target;
                        const delay = parseInt(el.getAttribute('data-reveal-delay') || '0', 10);
                        setTimeout(() => el.classList.add('is-visible'), delay);
                        observer.unobserve(el);
                    }
                });
            }, { threshold: 0.15, rootMargin: '0px 0px -80px 0px' });

            targets.forEach(el => observer.observe(el));
        })();

        // ===== CATEGORY HORIZONTAL SCROLL (drag + wheel) =====
        (() => {
            const track = document.getElementById('categoryTrack');
            if (!track) return;

            let isDown = false;
            let startX;
            let scrollLeft;

            track.addEventListener('mousedown', (e) => {
                isDown = true;
                track.classList.add('is-dragging');
                startX = e.pageX - track.offsetLeft;
                scrollLeft = track.scrollLeft;
            });

            track.addEventListener('mouseleave', () => {
                isDown = false;
                track.classList.remove('is-dragging');
            });

            track.addEventListener('mouseup', () => {
                isDown = false;
                track.classList.remove('is-dragging');
            });

            track.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - track.offsetLeft;
                const walk = (x - startX) * 1.5;
                track.scrollLeft = scrollLeft - walk;
            });

            // // Scroll wheel horizontal
            // track.addEventListener('wheel', (e) => {
            //     if (Math.abs(e.deltaX) > Math.abs(e.deltaY)) return;
            //     e.preventDefault();
            //     track.scrollLeft += e.deltaY * 1.2;
            // }, { passive: false });

            // Touch support
            let touchStartX = 0;
            let touchScrollLeft = 0;

            track.addEventListener('touchstart', (e) => {
                touchStartX = e.touches[0].pageX;
                touchScrollLeft = track.scrollLeft;
            }, { passive: true });

            track.addEventListener('touchmove', (e) => {
                const x = e.touches[0].pageX;
                const walk = (touchStartX - x) * 1.2;
                track.scrollLeft = touchScrollLeft + walk;
            }, { passive: true });
        })();
    </script>

@endsection