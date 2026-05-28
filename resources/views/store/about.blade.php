@extends('base.base')

@section('content')
<style>
    :root {
        --walnut-dark: #26170c;
        --walnut-light: #ac9181;
        --stone-100: #f5f5f4;
        --stone-200: #e7e5e4;
        --stone-900: #1c1917;
    }
    
    /* Menetralkan pembungkus box container bawaan base template khusus untuk Hero Section */
    .force-full-bleed {
        margin-top: -2rem !important; 
        margin-left: -3rem !important;
        margin-right: -3rem !important;
        width: calc(100% + 6rem) !important;
    }
    
    @media (max-width: 768px) {
        .force-full-bleed {
            margin-left: -1.5rem !important;
            margin-right: -1.5rem !important;
            width: calc(100% + 3rem) !important;
        }
    }

    .hero-bg-overlay {
        background: linear-gradient(90deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.45) 60%, transparent 100%), 
                    url('https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=1920&q=80');
        background-size: cover;
        background-position: center;
    }

    .btn-walnut-white {
        background-color: #fcfbf9;
        color: var(--walnut-dark);
        font-weight: 600;
        transition: all 0.2s ease-in-out;
        border: none;
    }
    .btn-walnut-white:hover {
        background-color: #eae6e1;
        transform: translateY(-1px);
    }

    .btn-walnut-outline {
        background-color: transparent;
        color: #ffffff;
        border: 1px solid rgba(255,255,255,0.7);
        font-weight: 600;
        transition: all 0.2s ease-in-out;
    }
    .btn-walnut-outline:hover {
        background-color: rgba(255,255,255,0.1);
        color: #ffffff;
    }

    /* Utilitas rasio gambar pengganti aspect-[4/5] Tailwind */
    .ratio-4-5 {
        aspect-ratio: 4 / 5;
    }
    @media (max-width: 576px) {
        .ratio-4-5 { aspect-ratio: 4 / 3; }
    }
    
    .object-cover {
        object-fit: cover;
    }
    
    /* Efek hover monokrom tim */
    .team-img {
        filter: grayscale(100%);
        transition: filter 0.4s ease;
    }
    .team-card:hover .team-img {
        filter: grayscale(0%);
    }
</style>

<main style="background-color: #fafaf9; color: var(--stone-900); font-family: 'Lexend', sans-serif; overflow-x: hidden;">
    
    <section class="position-relative d-flex align-items-center hero-bg-overlay force-full-bleed" style="min-height: 80vh;">
        <div class="container py-5" style="padding-left: 3rem; padding-right: 3rem;">
            <div class="row">
                <div class="col-12 col-md-10 col-lg-7 text-white">
                    <span class="text-uppercase small d-block mb-2 fw-bold tracking-widest" style="letter-spacing: 0.2em; color: #d6ccc2;">
                        JACED Furniture
                    </span>
                    <h1 class="display-4 fw-bold mb-4" style="font-family: Georgia, serif; line-height: 1.15;">
                        Crafting Spaces, Defining Stories
                    </h1>
                    <p class="lead mb-4" style="color: #e7e5e4; max-width: 520px; font-size: 1.1rem; line-height: 1.6;">
                        At JACED Furniture, every piece is designed to bring warmth, character, and timeless elegance into modern living spaces. Built with passion by five creative minds, we craft furniture that feels personal, refined, and made to last.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            
            <div class="col-12 col-lg-6">
                <span class="text-uppercase fw-bold small d-block mb-2" style="letter-spacing: 0.2em; color: var(--walnut-light);">
                    Our Heritage
                </span>
                <h2 class="fw-bold mb-4" style="font-family: Georgia, serif; font-size: 2.5rem;">
                    An Architecture of Comfort
                </h2>
                <p class="mb-3" style="color: #4f453f; line-height: 1.7;">
                    JACED Furniture was born from a shared passion for thoughtful living and modern craftsmanship. What started as an idea between five creators grew into a brand dedicated to designing furniture that feels warm, functional, and timeless.
                </p>
                <p class="mb-0" style="color: #4f453f; line-height: 1.7;">
                    We believe every space deserves pieces that are not only beautiful, but meaningful — crafted to support everyday moments and lasting memories.
                </p>
            </div>
            
            <div class="col-12 col-lg-6 position-relative">
                <div class="rounded-4 overflow-hidden shadow-lg">
                    <img src="https://images.unsplash.com/photo-1540518614846-7eded433c457?auto=format&fit=crop&w=800&q=80" 
                        alt="Exquisite Walnut Joinery Detail" 
                        class="w-100 h-auto d-block">
                </div>
            </div>
            
        </div>
    </div>
</section>

    <section class="py-5 border-top border-bottom" style="background-color: #f5f5f4; border-color: var(--stone-200) !important;">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3" style="font-family: Georgia, serif;">The Artisan Way</h2>
                <div class="rounded mx-auto" style="width: 64px; height: 4px; background-color: var(--walnut-dark);"></div>
            </div>
            
            <div class="row g-4">
                <div class="col-12 col-md-4">
                    <div class="card h-100 p-4 border-0 shadow-sm rounded-4 bg-white dynamic-card">
                        <div class="d-flex align-items-center justify-content-center rounded-circle mb-4" style="width: 56px; height: 56px; background-color: #fafaf9; color: var(--stone-900);">
                            <i class="fa-solid fa-compass fs-4"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-3" style="font-family: Georgia, serif;">Precision</h3>
                        <p class="text-muted small mb-0" style="line-height: 1.6;">Every detail matters. From structure to finish, our furniture is crafted with care to deliver both durability and refined aesthetics.</p>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card h-100 p-4 border-0 shadow-sm rounded-4 bg-white dynamic-card">
                        <div class="d-flex align-items-center justify-content-center rounded-circle mb-4" style="width: 56px; height: 56px; background-color: #fafaf9; color: var(--stone-900);">
                            <i class="fa-solid fa-tree fs-4"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-3" style="font-family: Georgia, serif;">Materiality</h3>
                        <p class="text-muted small mb-0" style="line-height: 1.6;">We use thoughtfully selected materials that bring warmth, texture, and timeless beauty into every living space.</p>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card h-100 p-4 border-0 shadow-sm rounded-4 bg-white dynamic-card">
                        <div class="d-flex align-items-center justify-content-center rounded-circle mb-4" style="width: 56px; height: 56px; background-color: #fafaf9; color: var(--stone-900);">
                            <i class="fa-solid fa-feather-pointed fs-4"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-3" style="font-family: Georgia, serif;">Legacy</h3>
                        <p class="text-muted small mb-0" style="line-height: 1.6;">JACED Furniture is designed to grow with your home — creating pieces that remain meaningful through every chapter of life.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container py-4">
            <div class="mb-5">
                <span class="text-uppercase fw-bold small d-block mb-1" style="letter-spacing: 0.2em; color: var(--walnut-light);">
                    Leadership
                </span>
                <h2 class="fw-bold" style="font-family: Georgia, serif;">
                    The Visionaries Behind the Craft
                </h2>
            </div>
            
            <div class="row g-4">
                @foreach([
                    ['img' => 'diah.jpeg',   'name' => 'Putu Diahloka Mahaputri',  'role' => 'CEO (Chief Executive Officer)'],
                    ['img' => 'cecill.jpeg',  'name' => 'Cecilia Agusta Leo',        'role' => 'CFO (Chief Financial Officer)'],
                    ['img' => 'jolie.jpeg', 'name' => 'Jocelyn Jolie',             'role' => 'CMO (Chief Marketing Officer)'],
                    ['img' => 'audric.jpeg', 'name' => 'Ignatius Audric Wijaya',    'role' => 'COO (Chief Operating Officer)'],
                    ['img' => 'ekkin.jpeg', 'name' => 'Ekkin Kenneth Hosari',      'role' => 'CTO (Chief Technology Officer)'],
                ] as $member)
                <div class="col-6 col-md-4 col-lg">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100">
                        <div style="height: 280px;">
                            <img src="{{ asset('image/team/' . $member['img']) }}"
                                alt="{{ $member['name'] }}"
                                class="w-100 h-100"
                                style="object-fit: cover;">
                        </div>
                        <div class="p-3 bg-white">
                            <h5 class="fw-bold mb-1 text-truncate" style="font-size: 14px;">{{ $member['name'] }}</h5>
                            <p class="text-uppercase text-muted mb-0" style="font-size: 10px; letter-spacing: 0.5px;">{{ $member['role'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section>
</main>

@endsection