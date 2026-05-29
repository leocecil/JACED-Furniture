@extends('base.base')

@section('title', 'JACED Furniture — Crafted for Modern Living')

@section('content')

<section class="jaced-landing">
    {{-- FLOATING PRODUCT IMAGES --}}
    <div class="jaced-float-container" id="floatContainer">

        <div class="jf-img jf-1" data-depth="0.04">
            <img src="{{ asset('image/sienna-sofa/2.webp') }}" alt="Sienna Sofa">
        </div>
        <div class="jf-img jf-2" data-depth="0.07">
            <img src="{{ asset('image/capri-chair/1.webp') }}" alt="Capri Chair">
        </div>
        <div class="jf-img jf-3" data-depth="0.05">
            <img src="{{ asset('image/avalon-chair/1.webp') }}" alt="Avalon Chair">
        </div>
        <div class="jf-img jf-4" data-depth="0.09">
            <img src="{{ asset('image/midnight-bed/3.webp') }}" alt="Midnight Bed">
        </div>
        <div class="jf-img jf-5" data-depth="0.06">
            <img src="{{ asset('image/wonban-dining-table/1.jpg') }}" alt="Wonban Table">
        </div>
        <div class="jf-img jf-6" data-depth="0.08">
            <img src="{{ asset('image/loungescape-bed/1.webp') }}" alt="Loungescape Bed">
        </div>
        <div class="jf-img jf-7" data-depth="0.05">
            <img src="{{ asset('image/oval-stone-dining-table/1.png') }}" alt="Oval Table">
        </div>
        <div class="jf-img jf-8" data-depth="0.07">
            <img src="{{ asset('image/gregory-bed/1.webp') }}" alt="Gregory Bed">
        </div>

    </div>

    {{-- CENTER CONTENT --}}
    <div class="jaced-center">
        <div class="jaced-logo-wrap">
            <img src="{{ asset('image/jaced_logo2.png') }}" alt="JACED" class="jaced-logo-img">
        </div>
        <h1 class="jaced-brand-title">JACED</h1>
        <p class="jaced-brand-sub">Furniture</p>
        <a href="{{ route('home') }}" class="jaced-cta-btn">
            <span>Get In</span>
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>

</section>

<style>
    body {
        overflow: hidden;
        background: #0e0e0c;
    }

    .jaced-landing {
        position: fixed;
        inset: 0;
        width: 100vw;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(ellipse at center, #1a1a14 0%, #0e0e0c 70%);
        overflow: hidden;
        z-index: 9999;
        transform-origin: center center;
    }

    .jaced-float-container {
        position: absolute;
        inset: 0;
        pointer-events: none;
    }

    .jf-img {
        position: absolute;
        border-radius: 16px;
        overflow: hidden;
        will-change: transform;
        transition: transform 0.1s linear;
        pointer-events: auto;
    }
    .jf-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.6s cubic-bezier(0.22, 1, 0.36, 1), filter 0.5s ease;
        filter: brightness(0.75) saturate(0.8);
    }
    .jf-img:hover img {
        transform: scale(1.08);
        filter: brightness(1) saturate(1);
    }

    .jf-1 { top: 8%; left: 4%; width: 180px; height: 220px; }
    .jf-2 { top: 18%; left: 14%; width: 130px; height: 160px; border-radius: 999px; }
    .jf-3 { top: 4%; right: 8%; width: 200px; height: 150px; }
    .jf-4 { top: 22%; right: 5%; width: 140px; height: 180px; border-radius: 999px 999px 0 0; }
    .jf-5 { top: 42%; left: 2%; width: 160px; height: 120px; }
    .jf-6 { top: 38%; right: 3%; width: 170px; height: 130px; }
    .jf-7 { bottom: 10%; left: 6%; width: 200px; height: 150px; border-radius: 999px; }
    .jf-8 { bottom: 8%; right: 6%; width: 180px; height: 220px; }

    .jaced-center {
        position: relative;
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        gap: 0;
    }

    .jaced-logo-wrap {
        width: 150px;
        height: 150px;
        margin-bottom: 16px;
        opacity: 0;
        transform: translateY(16px);
        animation: fadeUp 0.8s ease forwards 0.3s;
    }
    .jaced-logo-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .jaced-brand-title {
        font-size: clamp(5rem, 14vw, 11rem);
        font-weight: 700;
        letter-spacing: -0.04em;
        line-height: 0.9;
        margin: 0;
        background: linear-gradient(
            135deg,
            #f2ede6 0%,
            #c99a6b 25%,
            #f2ede6 50%,
            #896540 75%,
            #f2ede6 100%
        );
        background-size: 300% 300%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: gradientShift 6s ease infinite, fadeUp 0.8s ease forwards 0.5s;
        opacity: 0;
    }

    .jaced-brand-sub {
        font-size: clamp(0.9rem, 2vw, 1.2rem);
        letter-spacing: 0.5em;
        text-transform: uppercase;
        color: rgba(242, 237, 230, 0.45);
        margin: 8px 0 40px;
        opacity: 0;
        transform: translateY(12px);
        animation: fadeUp 0.8s ease forwards 0.7s;
    }

    .jaced-cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: rgba(242, 237, 230, 0.08);
        border: 1px solid rgba(242, 237, 230, 0.2);
        color: rgba(242, 237, 230, 0.9);
        padding: 14px 32px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 500;
        letter-spacing: 0.1em;
        text-decoration: none;
        text-transform: uppercase;
        backdrop-filter: blur(10px);
        transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        opacity: 0;
        transform: translateY(12px);
        animation: fadeUp 0.8s ease forwards 0.9s;
    }
    .jaced-cta-btn:hover {
        background: rgba(201, 154, 107, 0.15);
        border-color: rgba(201, 154, 107, 0.5);
        color: #c99a6b;
        transform: translateY(-3px);
        box-shadow: 0 12px 40px rgba(201, 154, 107, 0.15);
    }
    .jaced-cta-btn i {
        font-size: 12px;
        transition: transform 0.3s ease;
    }
    .jaced-cta-btn:hover i { transform: translateX(4px); }

    @keyframes fadeUp {
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    @media (max-width: 768px) {
        .jf-1 { width: 110px; height: 140px; }
        .jf-2 { width: 80px; height: 100px; left: 10%; }
        .jf-3 { width: 120px; height: 90px; right: 4%; }
        .jf-4 { width: 90px; height: 110px; }
        .jf-5 { width: 100px; height: 75px; }
        .jf-6 { width: 100px; height: 80px; }
        .jf-7 { width: 110px; height: 85px; }
        .jf-8 { width: 110px; height: 140px; }
    }
</style>

<script>
(() => {
    const container = document.getElementById('floatContainer');
    const images = document.querySelectorAll('.jf-img');
    if (!container || !images.length) return;

    let mouseX = window.innerWidth / 2;
    let mouseY = window.innerHeight / 2;
    let currentPositions = Array.from(images).map(() => ({ x: 0, y: 0 }));
    let targetPositions = Array.from(images).map(() => ({ x: 0, y: 0 }));

    window.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    const animate = () => {
        const centerX = window.innerWidth / 2;
        const centerY = window.innerHeight / 2;
        const offsetX = (mouseX - centerX) / centerX;
        const offsetY = (mouseY - centerY) / centerY;

        images.forEach((img, i) => {
            const depth = parseFloat(img.getAttribute('data-depth') || '0.05');
            const moveX = offsetX * depth * window.innerWidth * 0.5;
            const moveY = offsetY * depth * window.innerHeight * 0.5;

            targetPositions[i].x = moveX;
            targetPositions[i].y = moveY;

            currentPositions[i].x += (targetPositions[i].x - currentPositions[i].x) * 0.06;
            currentPositions[i].y += (targetPositions[i].y - currentPositions[i].y) * 0.06;

            img.style.transform = `translate3d(${currentPositions[i].x}px, ${currentPositions[i].y}px, 0)`;
        });

        requestAnimationFrame(animate);
    };

    animate();

    const cta = document.querySelector('.jaced-cta-btn');
    if (cta) {
        cta.addEventListener('click', (e) => {
            e.preventDefault();
            const landing = document.querySelector('.jaced-landing');
            landing.style.transition = 'transform 0.6s cubic-bezier(0.22, 1, 0.36, 1), filter 0.6s ease, opacity 0.5s ease';
            landing.style.transform = 'scale(1.15)';
            landing.style.filter = 'blur(18px)';
            landing.style.opacity = '0';
            setTimeout(() => {
                window.location.replace(cta.getAttribute('href')); // pakai replace bukan href
            }, 500);
        });
    }
})();
</script>

@endsection