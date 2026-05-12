{{-- TEMPORARY WRAPPER - hapus kalau base layout udah jadi --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jaced Furniture — Design Your Space. Live Your Style.</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --jaced-cream: #EDE8E3;
            --jaced-card: #FAF6F1;
            --jaced-brown-dark: #272E1D;
            --jaced-brown: #5A4D47;
            --jaced-caramel: #C99A6B;
            --jaced-sage: #5F7568;
            --jaced-input: #DDD6CE;
            --jaced-muted: #8A8278;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: var(--jaced-cream); color: var(--jaced-brown-dark); margin: 0; }
        .font-serif-jaced { font-family: 'DM Serif Display', serif; letter-spacing: -0.01em; }

        /* NAVBAR */
        .nav-wrap {
            position: sticky; top: 0; z-index: 50;
            background-color: rgba(237, 232, 227, 0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(138, 130, 120, 0.18);
        }
        .nav-inner { max-width: 1280px; margin: 0 auto; padding: 18px 32px; display: flex; align-items: center; justify-content: space-between; }
        .nav-logo { font-family: 'DM Serif Display', serif; font-size: 22px; letter-spacing: 0.18em; color: var(--jaced-brown-dark); text-decoration: none; }
        .nav-logo span { color: var(--jaced-caramel); }
        .nav-links { display: flex; gap: 36px; align-items: center; }
        .nav-link { color: var(--jaced-brown-dark); text-decoration: none; font-size: 14px; font-weight: 400; letter-spacing: 0.04em; transition: color 0.2s; }
        .nav-link:hover { color: var(--jaced-caramel); }
        .nav-actions { display: flex; gap: 18px; align-items: center; }
        .icon-btn { width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: transparent; border: 1px solid transparent; cursor: pointer; transition: 0.2s; color: var(--jaced-brown-dark); }
        .icon-btn:hover { background-color: var(--jaced-input); }
        .cart-badge { position: relative; }
        .cart-badge::after { content: '2'; position: absolute; top: 4px; right: 4px; background: var(--jaced-caramel); color: white; font-size: 10px; font-weight: 600; width: 16px; height: 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }

        /* HERO */
        .hero { max-width: 1280px; margin: 0 auto; padding: 80px 32px 60px; display: grid; grid-template-columns: 1.05fr 1fr; gap: 64px; align-items: center; }
        .hero-eyebrow { font-size: 12px; letter-spacing: 0.32em; text-transform: uppercase; color: var(--jaced-caramel); margin-bottom: 24px; font-weight: 500; }
        .hero-title { font-family: 'DM Serif Display', serif; font-size: 76px; line-height: 0.98; color: var(--jaced-brown-dark); margin: 0 0 28px; letter-spacing: -0.02em; }
        .hero-title em { font-style: italic; color: var(--jaced-caramel); }
        .hero-sub { font-size: 16px; line-height: 1.7; color: var(--jaced-muted); max-width: 480px; margin: 0 0 40px; }
        .hero-cta-row { display: flex; gap: 16px; align-items: center; flex-wrap: wrap; }
        .btn-primary { background: var(--jaced-brown-dark); color: var(--jaced-cream); padding: 16px 36px; border-radius: 100px; font-size: 14px; font-weight: 500; letter-spacing: 0.06em; text-decoration: none; border: none; cursor: pointer; transition: 0.2s; display: inline-flex; align-items: center; gap: 10px; }
        .btn-primary:hover { background: var(--jaced-sage); transform: translateY(-1px); }
        .btn-ghost { color: var(--jaced-brown-dark); padding: 16px 24px; font-size: 14px; font-weight: 500; letter-spacing: 0.06em; text-decoration: none; border: none; background: transparent; cursor: pointer; border-bottom: 1.5px solid var(--jaced-brown-dark); border-radius: 0; transition: 0.2s; }
        .btn-ghost:hover { color: var(--jaced-caramel); border-color: var(--jaced-caramel); }
        .hero-stats { display: flex; gap: 48px; margin-top: 64px; padding-top: 32px; border-top: 1px solid var(--jaced-input); }
        .stat-num { font-family: 'DM Serif Display', serif; font-size: 32px; color: var(--jaced-brown-dark); line-height: 1; }
        .stat-label { font-size: 11px; letter-spacing: 0.2em; text-transform: uppercase; color: var(--jaced-muted); margin-top: 6px; }
        .hero-visual { position: relative; aspect-ratio: 4/5; border-radius: 24px; overflow: hidden; background: var(--jaced-brown); }
        .hero-visual img { width: 100%; height: 100%; object-fit: cover; }
        .hero-tag { position: absolute; bottom: 24px; left: 24px; background: rgba(237, 232, 227, 0.95); backdrop-filter: blur(8px); padding: 14px 20px; border-radius: 100px; display: flex; align-items: center; gap: 12px; font-size: 13px; color: var(--jaced-brown-dark); }
        .hero-tag-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--jaced-caramel); }

        /* ABOUT */
        .section { max-width: 1280px; margin: 0 auto; padding: 100px 32px; }
        .section-head { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 56px; gap: 48px; }
        .section-eyebrow { font-size: 11px; letter-spacing: 0.32em; text-transform: uppercase; color: var(--jaced-caramel); margin-bottom: 16px; }
        .section-title { font-family: 'DM Serif Display', serif; font-size: 52px; line-height: 1.05; color: var(--jaced-brown-dark); margin: 0; max-width: 600px; letter-spacing: -0.01em; }
        .section-desc { font-size: 15px; line-height: 1.75; color: var(--jaced-muted); max-width: 360px; }
        .about-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
        .about-card { background: var(--jaced-card); border-radius: 20px; padding: 36px 32px; transition: 0.3s; cursor: default; }
        .about-card:hover { transform: translateY(-4px); background: var(--jaced-brown-dark); color: var(--jaced-cream); }
        .about-card:hover .about-card-num, .about-card:hover .about-card-desc { color: var(--jaced-cream); opacity: 0.85; }
        .about-card-num { font-family: 'DM Serif Display', serif; font-size: 14px; color: var(--jaced-caramel); margin-bottom: 32px; letter-spacing: 0.1em; }
        .about-card-title { font-family: 'DM Serif Display', serif; font-size: 26px; line-height: 1.2; margin: 0 0 14px; }
        .about-card-desc { font-size: 14px; line-height: 1.65; color: var(--jaced-muted); margin: 0; }

        /* RECOMMENDED */
        .recommended { background: var(--jaced-brown-dark); color: var(--jaced-cream); padding: 100px 0; }
        .recommended .section-eyebrow { color: var(--jaced-caramel); }
        .recommended .section-title { color: var(--jaced-cream); }
        .recommended .section-desc { color: rgba(237, 232, 227, 0.65); }
        .product-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
        .product-card { background: transparent; border-radius: 0; overflow: hidden; transition: 0.3s; cursor: pointer; }
        .product-card:hover .product-img-wrap { background: rgba(237, 232, 227, 0.08); }
        .product-card:hover .product-img { transform: scale(1.03); }
        .product-img-wrap { aspect-ratio: 1; background: rgba(237, 232, 227, 0.05); border-radius: 16px; overflow: hidden; margin-bottom: 18px; position: relative; transition: 0.3s; }
        .product-img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .product-badge { position: absolute; top: 14px; left: 14px; background: var(--jaced-caramel); color: white; font-size: 10px; padding: 5px 12px; border-radius: 100px; letter-spacing: 0.1em; text-transform: uppercase; font-weight: 600; }
        .product-fav { position: absolute; top: 14px; right: 14px; width: 36px; height: 36px; border-radius: 50%; background: rgba(237, 232, 227, 0.9); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s; border: none; color: var(--jaced-brown-dark); }
        .product-fav:hover { background: var(--jaced-cream); }
        .product-info { padding: 0 4px; }
        .product-cat { font-size: 11px; letter-spacing: 0.18em; text-transform: uppercase; color: var(--jaced-caramel); margin-bottom: 6px; }
        .product-name { font-family: 'DM Serif Display', serif; font-size: 22px; line-height: 1.2; margin: 0 0 10px; color: var(--jaced-cream); }
        .product-bottom { display: flex; justify-content: space-between; align-items: center; margin-top: 12px; }
        .product-price { font-size: 16px; color: var(--jaced-cream); font-weight: 500; }
        .product-price small { color: rgba(237, 232, 227, 0.55); text-decoration: line-through; font-size: 13px; font-weight: 400; margin-left: 8px; }
        .swatches { display: flex; gap: 6px; }
        .swatch { width: 14px; height: 14px; border-radius: 50%; border: 1.5px solid rgba(237, 232, 227, 0.25); cursor: pointer; transition: 0.2s; }
        .swatch:hover { transform: scale(1.2); border-color: var(--jaced-cream); }

        /* MATERIAL STRIP */
        .material-strip { background: var(--jaced-cream); padding: 80px 0; }
        .material-grid { max-width: 1280px; margin: 0 auto; padding: 0 32px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 32px; }
        .material-item { text-align: left; }
        .material-swatch { width: 100%; aspect-ratio: 16/10; border-radius: 12px; margin-bottom: 16px; }
        .material-name { font-family: 'DM Serif Display', serif; font-size: 20px; margin: 0 0 6px; }
        .material-desc { font-size: 13px; color: var(--jaced-muted); margin: 0; line-height: 1.5; }

        /* FOOTER */
        footer { background: var(--jaced-brown-dark); color: var(--jaced-cream); padding: 80px 32px 32px; }
        .footer-inner { max-width: 1280px; margin: 0 auto; display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr; gap: 64px; padding-bottom: 56px; border-bottom: 1px solid rgba(237, 232, 227, 0.1); }
        .footer-brand { font-family: 'DM Serif Display', serif; font-size: 28px; letter-spacing: 0.18em; margin: 0 0 18px; }
        .footer-brand span { color: var(--jaced-caramel); }
        .footer-tagline { font-size: 13px; line-height: 1.7; color: rgba(237, 232, 227, 0.6); margin: 0 0 24px; max-width: 280px; }
        .footer-col h4 { font-size: 11px; letter-spacing: 0.24em; text-transform: uppercase; color: var(--jaced-caramel); margin: 0 0 20px; font-weight: 500; }
        .footer-col a { display: block; color: rgba(237, 232, 227, 0.75); text-decoration: none; font-size: 14px; padding: 6px 0; transition: 0.2s; }
        .footer-col a:hover { color: var(--jaced-caramel); padding-left: 4px; }
        .footer-bottom { max-width: 1280px; margin: 0 auto; padding-top: 28px; display: flex; justify-content: space-between; font-size: 12px; color: rgba(237, 232, 227, 0.5); }

        @media (max-width: 900px) {
            .hero { grid-template-columns: 1fr; padding: 48px 24px; gap: 40px; }
            .hero-title { font-size: 48px; }
            .section { padding: 64px 24px; }
            .section-title { font-size: 36px; }
            .section-head { flex-direction: column; align-items: flex-start; gap: 16px; }
            .about-grid, .product-grid, .material-grid, .footer-inner { grid-template-columns: 1fr 1fr; }
            .nav-links { display: none; }
        }
        @media (max-width: 560px) {
            .about-grid, .product-grid, .material-grid, .footer-inner { grid-template-columns: 1fr; }
            .hero-stats { gap: 24px; flex-wrap: wrap; }
            .hero-title { font-size: 40px; }
        }
    </style>
</head>
<body>
{{-- END TEMPORARY WRAPPER --}}

{{-- NAVBAR --}}
<nav class="nav-wrap">
    <div class="nav-inner">
        <a href="{{ route('home') }}" class="nav-logo">J<span>F</span> JACED</a>
        <div class="nav-links">
            <a href="{{ route('home') }}" class="nav-link">Home</a>
            <a href="{{ route('shop') }}" class="nav-link">Shop</a>
            <a href="#about" class="nav-link">About</a>
            <a href="#materials" class="nav-link">Materials</a>
            <a href="#contact" class="nav-link">Contact</a>
        </div>
        <div class="nav-actions">
            <button class="icon-btn" aria-label="Search">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            </button>
            @auth
                <a href="{{ route('profile') }}" class="icon-btn" aria-label="Account">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-7 8-7s8 3 8 7"/></svg>
                </a>
            @else
                <a href="{{ route('login') }}" class="nav-link" style="font-weight:500;">Login</a>
            @endauth
            <button class="icon-btn cart-badge" aria-label="Cart">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </button>
        </div>
    </div>
</nav>

{{-- HERO --}}
<section class="hero">
    <div>
        <div class="hero-eyebrow">Curated Collection 2026</div>
        <h1 class="hero-title">Design your space. <em>Live your style.</em></h1>
        <p class="hero-sub">Furnitur kayu solid yang dirakit dengan teliti. Walnut, ash, oak. Setiap potongan dibuat untuk menemani lo seumur hidup, bukan satu musim.</p>
        <div class="hero-cta-row">
            <a href="{{ route('shop') }}" class="btn-primary">
                Explore Collection
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
            </a>
            <a href="#about" class="btn-ghost">Our Story</a>
        </div>
        <div class="hero-stats">
            <div>
                <div class="stat-num">12K+</div>
                <div class="stat-label">Happy Homes</div>
            </div>
            <div>
                <div class="stat-num">240+</div>
                <div class="stat-label">Crafted Pieces</div>
            </div>
            <div>
                <div class="stat-num">15yr</div>
                <div class="stat-label">Material Warranty</div>
            </div>
        </div>
    </div>
    <div class="hero-visual">
        <img src="https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?w=800&h=1000&fit=crop" alt="Hero">
        <div class="he@extends('base.base')

@section('title', 'Home — JACED Furniture')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .home-page { font-family: 'Lexend', sans-serif; color: var(--jaced-brown-dark); }
    .home-page * { box-sizing: border-box; }

    /* HERO */
    .hero { max-width: 1280px; margin: 0 auto; padding: 80px 32px 60px; display: grid; grid-template-columns: 1.05fr 1fr; gap: 64px; align-items: center; }
    .hero-eyebrow { font-size: 12px; letter-spacing: 0.32em; text-transform: uppercase; color: var(--jaced-caramel); margin-bottom: 24px; font-weight: 500; }
    .hero-title { font-size: 64px; line-height: 1.05; color: var(--jaced-brown-dark); margin: 0 0 28px; letter-spacing: -0.02em; font-weight: 700; }
    .hero-title em { font-style: italic; color: var(--jaced-caramel); font-weight: 600; }
    .hero-sub { font-size: 16px; line-height: 1.7; color: var(--jaced-muted); max-width: 480px; margin: 0 0 40px; }
    .hero-cta-row { display: flex; gap: 16px; align-items: center; flex-wrap: wrap; }
    .btn-sage { background: var(--jaced-sage); color: white; padding: 16px 36px; border-radius: 100px; font-size: 14px; font-weight: 500; letter-spacing: 0.04em; text-decoration: none; border: none; cursor: pointer; transition: 0.2s; display: inline-flex; align-items: center; gap: 10px; font-family: inherit; }
    .btn-sage:hover { background: #4a5d52; color: white; transform: translateY(-1px); }
    .btn-outline { color: var(--jaced-brown-dark); padding: 16px 24px; font-size: 14px; font-weight: 500; letter-spacing: 0.04em; text-decoration: none; background: transparent; border: none; cursor: pointer; border-bottom: 1.5px solid var(--jaced-brown-dark); border-radius: 0; transition: 0.2s; }
    .btn-outline:hover { color: var(--jaced-caramel); border-color: var(--jaced-caramel); }
    .hero-stats { display: flex; gap: 48px; margin-top: 64px; padding-top: 32px; border-top: 1px solid var(--jaced-input); }
    .stat-num { font-size: 32px; color: var(--jaced-brown-dark); line-height: 1; font-weight: 700; }
    .stat-label { font-size: 11px; letter-spacing: 0.2em; text-transform: uppercase; color: var(--jaced-muted); margin-top: 6px; }
    .hero-visual { position: relative; aspect-ratio: 4/5; border-radius: 24px; overflow: hidden; background: var(--jaced-brown); }
    .hero-visual img { width: 100%; height: 100%; object-fit: cover; }
    .hero-tag { position: absolute; bottom: 24px; left: 24px; background: rgba(237, 232, 227, 0.95); backdrop-filter: blur(8px); padding: 14px 20px; border-radius: 100px; display: flex; align-items: center; gap: 12px; font-size: 13px; color: var(--jaced-brown-dark); }
    .hero-tag-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--jaced-caramel); }

    /* SECTIONS */
    .home-section { max-width: 1280px; margin: 0 auto; padding: 100px 32px; }
    .section-head { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 56px; gap: 48px; }
    .section-eyebrow { font-size: 11px; letter-spacing: 0.32em; text-transform: uppercase; color: var(--jaced-caramel); margin-bottom: 16px; font-weight: 500; }
    .section-title { font-size: 44px; line-height: 1.1; color: var(--jaced-brown-dark); margin: 0; max-width: 600px; letter-spacing: -0.01em; font-weight: 700; }
    .section-desc { font-size: 15px; line-height: 1.75; color: var(--jaced-muted); max-width: 360px; }
    .about-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
    .about-card { background: var(--jaced-card); border-radius: 12px; padding: 36px 32px; transition: 0.3s; cursor: default; border: none; }
    .about-card:hover { transform: translateY(-4px); background: var(--jaced-brown-dark); color: var(--jaced-cream); }
    .about-card:hover .about-card-num, .about-card:hover .about-card-desc { color: var(--jaced-cream); opacity: 0.85; }
    .about-card-num { font-size: 13px; color: var(--jaced-caramel); margin-bottom: 32px; letter-spacing: 0.1em; font-weight: 500; }
    .about-card-title { font-size: 22px; line-height: 1.3; margin: 0 0 14px; font-weight: 600; }
    .about-card-desc { font-size: 14px; line-height: 1.65; color: var(--jaced-muted); margin: 0; }

    /* RECOMMENDED */
    .recommended { background: var(--jaced-brown-dark); color: var(--jaced-cream); padding: 100px 0; }
    .recommended .section-eyebrow { color: var(--jaced-caramel); }
    .recommended .section-title { color: var(--jaced-cream); }
    .recommended .section-desc { color: rgba(237, 232, 227, 0.65); }
    .product-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
    .product-card { background: transparent; transition: 0.3s; cursor: pointer; text-decoration: none; color: inherit; display: block; }
    .product-card:hover .product-img-wrap { background: rgba(237, 232, 227, 0.08); }
    .product-card:hover .product-img { transform: scale(1.03); }
    .product-img-wrap { aspect-ratio: 1; background: rgba(237, 232, 227, 0.05); border-radius: 12px; overflow: hidden; margin-bottom: 18px; position: relative; transition: 0.3s; }
    .product-img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .product-badge { position: absolute; top: 14px; left: 14px; background: var(--jaced-caramel); color: white; font-size: 10px; padding: 5px 12px; border-radius: 100px; letter-spacing: 0.1em; text-transform: uppercase; font-weight: 600; }
    .product-fav { position: absolute; top: 14px; right: 14px; width: 36px; height: 36px; border-radius: 50%; background: rgba(237, 232, 227, 0.9); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s; border: none; color: var(--jaced-brown-dark); }
    .product-fav:hover { background: var(--jaced-cream); }
    .product-cat { font-size: 11px; letter-spacing: 0.18em; text-transform: uppercase; color: var(--jaced-caramel); margin-bottom: 6px; font-weight: 500; }
    .product-name { font-size: 18px; line-height: 1.3; margin: 0 0 10px; color: var(--jaced-cream); font-weight: 600; }
    .product-bottom { display: flex; justify-content: space-between; align-items: center; margin-top: 12px; }
    .product-price { font-size: 15px; color: var(--jaced-cream); font-weight: 500; }
    .product-price small { color: rgba(237, 232, 227, 0.55); text-decoration: line-through; font-size: 13px; font-weight: 400; margin-left: 8px; }
    .swatches { display: flex; gap: 6px; }
    .swatch { width: 14px; height: 14px; border-radius: 50%; border: 1.5px solid rgba(237, 232, 227, 0.25); cursor: pointer; transition: 0.2s; }
    .swatch:hover { transform: scale(1.2); border-color: var(--jaced-cream); }

    /* MATERIAL STRIP */
    .material-strip { background: var(--jaced-cream); padding: 80px 0; }
    .material-grid { max-width: 1280px; margin: 0 auto; padding: 0 32px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 32px; }
    .material-swatch { width: 100%; aspect-ratio: 16/10; border-radius: 12px; margin-bottom: 16px; }
    .material-name { font-size: 18px; margin: 0 0 6px; font-weight: 600; }
    .material-desc { font-size: 13px; color: var(--jaced-muted); margin: 0; line-height: 1.5; }

    @media (max-width: 900px) {
        .hero { grid-template-columns: 1fr; padding: 48px 24px; gap: 40px; }
        .hero-title { font-size: 44px; }
        .home-section { padding: 64px 24px; }
        .section-title { font-size: 32px; }
        .section-head { flex-direction: column; align-items: flex-start; gap: 16px; }
        .about-grid, .product-grid, .material-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 560px) {
        .about-grid, .product-grid, .material-grid { grid-template-columns: 1fr; }
        .hero-stats { gap: 24px; flex-wrap: wrap; }
        .hero-title { font-size: 36px; }
    }
</style>
@endpush

@section('content')
<div class="home-page">

    {{-- HERO --}}
    <section class="hero">
        <div>
            <div class="hero-eyebrow">Curated Collection 2026</div>
            <h1 class="hero-title">Design your space. <em>Live your style.</em></h1>
            <p class="hero-sub">Furnitur kayu solid yang dirakit dengan teliti. Walnut, ash, oak. Setiap potongan dibuat untuk menemani lo seumur hidup, bukan satu musim.</p>
            <div class="hero-cta-row">
                <a href="{{ route('shop') }}" class="btn-sage">
                    Explore Collection
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                </a>
                <a href="#about" class="btn-outline">Our Story</a>
            </div>
            <div class="hero-stats">
                <div>
                    <div class="stat-num">12K+</div>
                    <div class="stat-label">Happy Homes</div>
                </div>
                <div>
                    <div class="stat-num">240+</div>
                    <div class="stat-label">Crafted Pieces</div>
                </div>
                <div>
                    <div class="stat-num">15yr</div>
                    <div class="stat-label">Material Warranty</div>
                </div>
            </div>
        </div>
        <div class="hero-visual">
            <img src="https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?w=800&h=1000&fit=crop" alt="Hero">
            <div class="hero-tag">
                <span class="hero-tag-dot"></span>
                New drop. Walnut Series.
            </div>
        </div>
    </section>

    {{-- ABOUT --}}
    <section class="home-section" id="about">
        <div class="section-head">
            <div>
                <div class="section-eyebrow">About Us</div>
                <h2 class="section-title">Built slow. Made to outlast trends.</h2>
            </div>
            <p class="section-desc">Jaced bukan brand furnitur biasa. Tiga prinsip yang kita pegang sejak hari pertama.</p>
        </div>
        <div class="about-grid">
            <div class="about-card">
                <div class="about-card-num">01 / KRAFT</div>
                <h3 class="about-card-title">Solid wood, jujur dari sumber.</h3>
                <p class="about-card-desc">Walnut, ash, dan oak yang ditelusuri sampai ke hutannya. Tanpa veneer, tanpa partikel board, tanpa kompromi.</p>
            </div>
            <div class="about-card">
                <div class="about-card-num">02 / DESIGN</div>
                <h3 class="about-card-title">Bentuk tenang yang tidak teriak.</h3>
                <p class="about-card-desc">Garis bersih, proporsi yang masuk akal, dan detail yang dipikirin sampai ke sambungan terkecil. Bukan dekorasi, ini arsitektur.</p>
            </div>
            <div class="about-card">
                <div class="about-card-num">03 / GARANSI</div>
                <h3 class="about-card-title">15 tahun bilang serius.</h3>
                <p class="about-card-desc">Setiap potong kayu kita garansi struktur 15 tahun. Kalau ada yang lepas atau retak, kita benerin tanpa nanya.</p>
            </div>
        </div>
    </section>

    {{-- RECOMMENDED --}}
    <section class="recommended">
        <div class="home-section" style="padding-top: 0; padding-bottom: 0;">
            <div class="section-head">
                <div>
                    <div class="section-eyebrow">Recommended For You</div>
                    <h2 class="section-title">Pilihan yang sedang dicari banyak orang.</h2>
                </div>
                <a href="{{ route('shop') }}" class="btn-outline" style="color: var(--jaced-cream); border-color: var(--jaced-cream);">View All Products</a>
            </div>

            <div class="product-grid">
                @forelse($recommended as $product)
                <a href="{{ route('product.show', $product->slug) }}" class="product-card">
                    <div class="product-img-wrap">
                        @if($product->badge)
                            <span class="product-badge">{{ ucfirst($product->badge) }}</span>
                        @endif
                        <button type="button" class="product-fav" aria-label="Favorite" onclick="event.preventDefault();">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        </button>
                        <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="product-img">
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
                                <span class="swatch" style="background: {{ $variant->color_hex }};" title="{{ $variant->color_name }}"></span>
                            @endforeach
                        </div>
                    </div>
                </a>
                @empty
                    <p style="color: var(--jaced-cream); grid-column: 1 / -1; text-align: center; opacity: 0.6;">Belum ada produk rekomendasi.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- MATERIAL STRIP --}}
    <section class="material-strip" id="materials">
        <div class="home-section" style="padding: 0;">
            <div class="section-head" style="margin-bottom: 40px;">
                <div>
                    <div class="section-eyebrow">Our Materials</div>
                    <h2 class="section-title">Empat kayu. Empat karakter.</h2>
                </div>
            </div>
            <div class="material-grid">
                <div>
                    <div class="material-swatch" style="background: linear-gradient(135deg, #4a2818 0%, #6b3a22 100%);"></div>
                    <h4 class="material-name">Walnut</h4>
                    <p class="material-desc">Coklat tua iconic yang menua jadi madu seiring waktu.</p>
                </div>
                <div>
                    <div class="material-swatch" style="background: linear-gradient(135deg, #d9c9a8 0%, #c4ad88 100%);"></div>
                    <h4 class="material-name">Ash</h4>
                    <p class="material-desc">Pucat dengan serat tegas. Karakter kuat tapi terang.</p>
                </div>
                <div>
                    <div class="material-swatch" style="background: linear-gradient(135deg, #b48a5c 0%, #8c6a44 100%);"></div>
                    <h4 class="material-name">Oak</h4>
                    <p class="material-desc">Klasik untuk semua ruang. Hangat, tahan banting.</p>
                </div>
                <div>
                    <div class="material-swatch" style="background: linear-gradient(135deg, #2a1f18 0%, #3d2b1f 100%);"></div>
                    <h4 class="material-name">Ebony</h4>
                    <p class="material-desc">Hitam pekat untuk statement piece yang berani.</p>
                </div>
            </div>
        </div>
    </section>

</div>
@endsectionro-tag">
            <span class="hero-tag-dot"></span>
            New drop. Walnut Series.
        </div>
    </div>
</section>

{{-- ABOUT --}}
<section class="section" id="about">
    <div class="section-head">
        <div>
            <div class="section-eyebrow">About Us</div>
            <h2 class="section-title">Built slow. Made to outlast trends.</h2>
        </div>
        <p class="section-desc">Jaced bukan brand furnitur biasa. Tiga prinsip yang kita pegang sejak hari pertama.</p>
    </div>
    <div class="about-grid">
        <div class="about-card">
            <div class="about-card-num">01 / KRAFT</div>
            <h3 class="about-card-title">Solid wood, jujur dari sumber.</h3>
            <p class="about-card-desc">Walnut, ash, dan oak yang ditelusuri sampai ke hutannya. Tanpa veneer, tanpa partikel board, tanpa kompromi.</p>
        </div>
        <div class="about-card">
            <div class="about-card-num">02 / DESIGN</div>
            <h3 class="about-card-title">Bentuk tenang yang tidak teriak.</h3>
            <p class="about-card-desc">Garis bersih, proporsi yang masuk akal, dan detail yang dipikirin sampai ke sambungan terkecil. Bukan dekorasi, ini arsitektur.</p>
        </div>
        <div class="about-card">
            <div class="about-card-num">03 / GARANSI</div>
            <h3 class="about-card-title">15 tahun bilang serius.</h3>
            <p class="about-card-desc">Setiap potong kayu kita garansi struktur 15 tahun. Kalau ada yang lepas atau retak, kita benerin tanpa nanya.</p>
        </div>
    </div>
</section>

{{-- RECOMMENDED PRODUCTS --}}
<section class="recommended">
    <div class="section" style="padding-top: 0; padding-bottom: 0;">
        <div class="section-head">
            <div>
                <div class="section-eyebrow">Recommended For You</div>
                <h2 class="section-title">Pilihan yang sedang dicari banyak orang.</h2>
            </div>
            <a href="{{ route('shop') }}" class="btn-ghost" style="color: var(--jaced-cream); border-color: var(--jaced-cream);">View All Products</a>
        </div>

        <div class="product-grid">
            @forelse($recommended as $product)
            <a href="{{ route('product.show', $product->slug) }}" class="product-card">
                <div class="product-img-wrap">
                    @if($product->badge)
                        <span class="product-badge">{{ ucfirst($product->badge) }}</span>
                    @endif
                    <button class="product-fav" aria-label="Favorite" onclick="event.preventDefault();">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    </button>
                    <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="product-img">
                </div>
                <div class="product-info">
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
                                <span class="swatch" style="background: {{ $variant->color_hex }};" title="{{ $variant->color_name }}"></span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </a>
            @empty
                <p style="color: var(--jaced-cream); grid-column: 1 / -1; text-align: center; opacity: 0.6;">Belum ada produk rekomendasi.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- MATERIAL STRIP --}}
<section class="material-strip" id="materials">
    <div class="section" style="padding: 0;">
        <div class="section-head" style="margin-bottom: 40px;">
            <div>
                <div class="section-eyebrow">Our Materials</div>
                <h2 class="section-title">Empat kayu. Empat karakter.</h2>
            </div>
        </div>
        <div class="material-grid">
            <div class="material-item">
                <div class="material-swatch" style="background: linear-gradient(135deg, #4a2818 0%, #6b3a22 100%);"></div>
                <h4 class="material-name">Walnut</h4>
                <p class="material-desc">Coklat tua iconic yang menua jadi madu seiring waktu.</p>
            </div>
            <div class="material-item">
                <div class="material-swatch" style="background: linear-gradient(135deg, #d9c9a8 0%, #c4ad88 100%);"></div>
                <h4 class="material-name">Ash</h4>
                <p class="material-desc">Pucat dengan serat tegas. Karakter kuat tapi terang.</p>
            </div>
            <div class="material-item">
                <div class="material-swatch" style="background: linear-gradient(135deg, #b48a5c 0%, #8c6a44 100%);"></div>
                <h4 class="material-name">Oak</h4>
                <p class="material-desc">Klasik untuk semua ruang. Hangat, tahan banting.</p>
            </div>
            <div class="material-item">
                <div class="material-swatch" style="background: linear-gradient(135deg, #2a1f18 0%, #3d2b1f 100%);"></div>
                <h4 class="material-name">Ebony</h4>
                <p class="material-desc">Hitam pekat untuk statement piece yang berani.</p>
            </div>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer id="contact">
    <div class="footer-inner">
        <div>
            <div class="footer-brand">J<span>F</span> JACED</div>
            <p class="footer-tagline">Design your space. Live your style. Furnitur kayu solid untuk hidup yang slow dan sengaja.</p>
            <div style="display:flex; gap: 12px;">
                <a href="#" class="icon-btn" style="border: 1px solid rgba(237,232,227,0.2); color: var(--jaced-cream);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.16c3.2 0 3.58 0 4.85.07 3.25.15 4.77 1.69 4.92 4.92.06 1.27.07 1.65.07 4.85s0 3.58-.07 4.85c-.15 3.23-1.66 4.77-4.92 4.92-1.27.06-1.64.07-4.85.07-3.2 0-3.58 0-4.85-.07-3.26-.15-4.77-1.69-4.92-4.92C2.16 15.58 2.16 15.2 2.16 12s.01-3.58.07-4.85C2.38 3.92 3.9 2.38 7.15 2.23 8.42 2.17 8.8 2.16 12 2.16zm0 5.84A4 4 0 1 0 12 16a4 4 0 0 0 0-8zm0 6.6a2.6 2.6 0 1 1 0-5.2 2.6 2.6 0 0 1 0 5.2zm5.1-6.76a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg>
                </a>
                <a href="#" class="icon-btn" style="border: 1px solid rgba(237,232,227,0.2); color: var(--jaced-cream);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-5.86 11.51 6.85 6.85 0 0 0 11.42-5.06V8.86a8.16 8.16 0 0 0 4.77 1.52V6.94a4.86 4.86 0 0 1-1.1-.25z"/></svg>
                </a>
            </div>
        </div>
        <div class="footer-col">
            <h4>Shop</h4>
            <a href="#">All Products</a>
            <a href="#">Seating</a>
            <a href="#">Tables</a>
            <a href="#">Storage</a>
            <a href="#">New Arrivals</a>
        </div>
        <div class="footer-col">
            <h4>Brand</h4>
            <a href="#">Our Story</a>
            <a href="#">Materials</a>
            <a href="#">Sustainability</a>
            <a href="#">Press</a>
        </div>
        <div class="footer-col">
            <h4>Help</h4>
            <a href="#">Contact</a>
            <a href="#">Shipping</a>
            <a href="#">Warranty</a>
            <a href="#">Care Guide</a>
        </div>
    </div>
    <div class="footer-bottom">
        <span>© 2026 Jaced Furniture. All rights reserved.</span>
        <span>Surabaya, Indonesia</span>
    </div>
</footer>

{{-- @endsection --}}
</body>
</html>