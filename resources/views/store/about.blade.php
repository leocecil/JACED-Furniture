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

    /* FLIP CARD */
    .team-flip-card {
        perspective: 1000px;
        height: 380px;
    }

    .team-flip-inner {
        position: relative;
        width: 100%;
        height: 100%;
        transition: transform 0.6s cubic-bezier(0.25, 0.8, 0.25, 1);
        transform-style: preserve-3d;
    }

    .team-flip-card:hover .team-flip-inner {
        transform: rotateY(180deg);
    }

    .team-flip-front,
    .team-flip-back {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        border-radius: 16px;
        overflow: hidden;
    }

    .team-flip-front {
        background: white;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }

    .team-flip-back {
        transform: rotateY(180deg);
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 28px 24px;
        color: white;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }

    /* Material backgrounds */
    .mat-walnut   { background: linear-gradient(145deg, #4A2C0A, #26170c); }
    .mat-marble   { background: linear-gradient(145deg, #6B7280, #374151); }
    .mat-rattan   { background: linear-gradient(145deg, #C4A882, #8B6343); }
    .mat-oak      { background: linear-gradient(145deg, #8B6343, #5C3D1E); }
    .mat-pine     { background: linear-gradient(145deg, #4A6741, #2D4A2A); }

    .team-flip-back .nickname {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        opacity: 0.6;
        margin-bottom: 4px;
    }

    .team-flip-back .member-name {
        font-size: 18px;
        font-weight: 700;
        font-family: Georgia, serif;
        margin-bottom: 4px;
    }

    .team-flip-back .member-role {
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        opacity: 0.5;
        margin-bottom: 20px;
    }

    .team-flip-back .divider {
        width: 32px;
        height: 2px;
        background: rgba(255,255,255,0.3);
        margin-bottom: 16px;
    }

    .team-flip-back .furniture-tag {
        font-size: 11px;
        opacity: 0.7;
        margin-bottom: 4px;
    }

    .team-flip-back .furniture-val {
        font-size: 13px;
        font-weight: 600;
        font-style: italic;
        margin-bottom: 16px;
        line-height: 1.4;
    }

    .team-flip-back .quote {
        font-size: 12px;
        line-height: 1.6;
        opacity: 0.85;
        border-left: 2px solid rgba(255,255,255,0.3);
        padding-left: 12px;
    }

    .team-flip-back .material-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-top: 16px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.2);
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
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
                    [
                        'img'       => 'diah.jpeg',
                        'name'      => 'Putu Diahloka Mahaputri',
                        'nickname'  => 'Diah',
                        'role'      => 'CEO · Chief Executive Officer',
                        'material'  => 'Walnut',
                        'mat_class' => 'mat-walnut',
                        'furniture' => 'A classic Chesterfield sofa — timeless and commanding',
                        'quote'     => 'Lead with intention, design with heart.',
                    ],
                    [
                        'img'       => 'cecill.jpeg',
                        'name'      => 'Cecilia Agusta Leo',
                        'nickname'  => 'Cecil',
                        'role'      => 'CFO · Chief Financial Officer',
                        'material'  => 'Marble',
                        'mat_class' => 'mat-marble',
                        'furniture' => 'A minimalist glass desk — precise and transparent',
                        'quote'     => 'Numbers tell stories. I make sure they\'re good ones.',
                    ],
                    [
                        'img'       => 'jolie.jpeg',
                        'name'      => 'Jocelyn Jolie',
                        'nickname'  => 'Jolie',
                        'role'      => 'CMO · Chief Marketing Officer',
                        'material'  => 'Rattan',
                        'mat_class' => 'mat-rattan',
                        'furniture' => 'A statement accent chair — bold and unforgettable',
                        'quote'     => 'Every brand has a soul. Mine just happens to be beautiful.',
                    ],
                    [
                        'img'       => 'audric.jpeg',
                        'name'      => 'Ignatius Audric Wijaya',
                        'nickname'  => 'Audric',
                        'role'      => 'COO · Chief Operating Officer',
                        'material'  => 'Oak Wood',
                        'mat_class' => 'mat-oak',
                        'furniture' => 'A solid oak dining table — reliable and brings people together',
                        'quote'     => 'Behind every great product is a greater process.',
                    ],
                    [
                        'img'       => 'ekkin.jpeg',
                        'name'      => 'Ekkin Kenneth Hosari',
                        'nickname'  => 'Ekkin',
                        'role'      => 'CTO · Chief Technology Officer',
                        'material'  => 'Japandi Pine',
                        'mat_class' => 'mat-pine',
                        'furniture' => 'A modular shelving unit — functional, flexible, always evolving',
                        'quote'     => 'Good code, like good furniture, should last a lifetime.',
                    ],
                ] as $member)
                <div class="col-6 col-md-4 col-lg">
                    <div class="team-flip-card">
                        <div class="team-flip-inner">

                            {{-- DEPAN --}}
                            <div class="team-flip-front">
                                <div style="height: 300px;">
                                    <img src="{{ asset('image/team/' . $member['img']) }}"
                                        alt="{{ $member['name'] }}"
                                        class="w-100 h-100"
                                        style="object-fit: cover; filter: grayscale(20%); transition: filter 0.4s ease;">
                                </div>
                                <div class="p-3 bg-white">
                                    <h5 class="fw-bold mb-1 text-truncate" style="font-size: 13px; color: #1c1917;">{{ $member['name'] }}</h5>
                                    <p class="text-uppercase text-muted mb-0" style="font-size: 10px; letter-spacing: 0.5px;">{{ $member['role'] }}</p>
                                </div>
                            </div>

                            {{-- BELAKANG --}}
                            <div class="team-flip-back {{ $member['mat_class'] }}">
                                {{-- <div class="nickname">{{ $member['nickname'] }}</div> --}}
                                <div class="member-name">{{ explode(' ', $member['nickname'])[0] }}</div>
                                <div class="member-role">{{ $member['role'] }}</div>
                                <div class="divider"></div>
                                <div class="furniture-tag">✦ If I were a furniture</div>
                                <div class="furniture-val">"{{ $member['furniture'] }}"</div>
                                <div class="quote">"{{ $member['quote'] }}"</div>
                                <div class="material-badge">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                                    {{ $member['material'] }}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</main>

@endsection