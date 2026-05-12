{{-- TEMPORARY WRAPPER - hapus ini semua kalau base layout udah jadi --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders — CasaLoom</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --cl-cream:       #F2EDE6;
            --cl-card:        #FAF7F2;
            --cl-dark:        #1C1C1A;
            --cl-muted:       #8A857D;
            --cl-sage:        #5A6B5B;
            --cl-sage-light:  #8A9E8B;
            --cl-sage-bg:     #E8EDE8;
            --cl-border:      #E0D9D0;
            --cl-white:       #FFFFFF;
            --cl-caramel:     #C99A6B;
            --cl-caramel-bg:  #F5EBE0;
        }
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--cl-cream);
            color: var(--cl-dark);
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .font-serif { font-family: 'DM Serif Display', serif; }

        /* NAV */
        nav {
            background-color: var(--cl-white);
            border-bottom: 1px solid var(--cl-border);
            padding: 0 40px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .nav-logo { font-family: 'DM Serif Display', serif; font-size: 20px; color: var(--cl-dark); text-decoration: none; }
        .nav-links { display: flex; gap: 32px; list-style: none; margin: 0; padding: 0; }
        .nav-links a { text-decoration: none; font-size: 14px; color: var(--cl-dark); font-weight: 500; transition: color .2s; }
        .nav-links a:hover { color: var(--cl-sage); }
        .nav-right { display: flex; align-items: center; gap: 16px; }
        .nav-search {
            display: flex; align-items: center; gap: 8px;
            background: var(--cl-cream); border: 1px solid var(--cl-border);
            border-radius: 999px; padding: 8px 16px; font-size: 13px; color: var(--cl-muted);
            min-width: 200px;
        }
        .nav-icon { background: none; border: none; cursor: pointer; padding: 6px; color: var(--cl-dark); display: flex; align-items: center; }

        /* MAIN */
        main { flex: 1; max-width: 1100px; margin: 0 auto; width: 100%; padding: 48px 24px; }

        /* PAGE HEADER */
        .page-header { margin-bottom: 36px; }
        .page-header h1 { font-family: 'DM Serif Display', serif; font-size: 48px; font-weight: 400; margin: 0 0 8px; line-height: 1.1; }
        .page-header p { font-size: 14px; color: var(--cl-muted); margin: 0; max-width: 480px; line-height: 1.6; }

        /* FILTER TABS */
        .filter-tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 32px; }
        .filter-tab {
            padding: 8px 20px; border-radius: 999px; border: 1px solid var(--cl-border);
            background: transparent; font-size: 13px; font-family: 'Inter', sans-serif;
            color: var(--cl-dark); cursor: pointer; transition: all .2s; font-weight: 500;
        }
        .filter-tab:hover { border-color: var(--cl-sage); color: var(--cl-sage); }
        .filter-tab.active { background-color: var(--cl-caramel); border-color: var(--cl-caramel); color: white; }

        /* CONTENT GRID */
        .content-grid { display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start; }
        @media (max-width: 860px) { .content-grid { grid-template-columns: 1fr; } }

        /* ORDER CARD */
        .order-card { background: var(--cl-white); border-radius: 16px; padding: 24px; border: 1px solid var(--cl-border); }
        .order-card-top { display: flex; align-items: flex-start; gap: 20px; margin-bottom: 24px; }
        .order-img { width: 140px; height: 140px; object-fit: cover; border-radius: 12px; flex-shrink: 0; }
        .order-info { flex: 1; }
        .order-info h2 { font-family: 'DM Serif Display', serif; font-size: 24px; font-weight: 400; margin: 0 0 6px; }
        .order-id { font-size: 12px; font-weight: 600; letter-spacing: .08em; color: var(--cl-muted); text-transform: uppercase; margin: 0 0 16px; }
        .status-badge {
            display: inline-block; font-size: 11px; font-weight: 600; letter-spacing: .08em;
            text-transform: uppercase; color: var(--cl-sage); background: var(--cl-sage-bg);
            padding: 4px 12px; border-radius: 999px;
        }
        .order-actions { display: flex; align-items: center; gap: 16px; margin-top: 16px; }
        .btn-track {
            background: var(--cl-dark); color: white; padding: 10px 24px;
            border-radius: 8px; font-size: 14px; font-weight: 500; border: none; cursor: pointer;
            transition: background .2s;
        }
        .btn-track:hover { background: #333; }
        .btn-details { background: none; border: none; font-size: 14px; font-weight: 500; color: var(--cl-caramel); cursor: pointer; }
        .btn-details:hover { text-decoration: underline; }

        /* TRACKING BOX */
        .tracking-box { background: var(--cl-cream); border-radius: 12px; padding: 20px 24px; }
        .tracking-label { font-size: 10px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: var(--cl-muted); margin: 0 0 4px; }
        .tracking-eta { font-family: 'DM Serif Display', serif; font-size: 22px; font-weight: 400; margin: 0 0 20px; }
        .tracking-steps { display: flex; align-items: center; gap: 0; }
        .step { display: flex; flex-direction: column; align-items: center; position: relative; }
        .step-circle {
            width: 32px; height: 32px; border-radius: 50%; border: 2px solid var(--cl-border);
            background: var(--cl-white); display: flex; align-items: center; justify-content: center;
            z-index: 1; position: relative;
        }
        .step-circle.done { background: var(--cl-dark); border-color: var(--cl-dark); }
        .step-circle.active { background: var(--cl-white); border-color: var(--cl-sage); }
        .step-circle svg { color: white; }
        .step-circle.active svg { color: var(--cl-sage); }
        .step-circle.pending svg { color: var(--cl-muted); }
        .step-label { font-size: 11px; color: var(--cl-muted); margin-top: 8px; white-space: nowrap; }
        .step-connector { flex: 1; height: 2px; background: var(--cl-border); margin: 0 -1px; position: relative; top: -10px; }
        .step-connector.done { background: var(--cl-dark); }

        /* SIDEBAR */
        .sidebar { display: flex; flex-direction: column; gap: 20px; }

        /* ARTISAN UPDATE */
        .artisan-card { background: var(--cl-white); border-radius: 16px; padding: 24px; border: 1px solid var(--cl-border); }
        .artisan-card h3 { font-family: 'DM Serif Display', serif; font-size: 20px; font-weight: 400; margin: 0 0 20px; }
        .artisan-item { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 18px; }
        .artisan-item:last-child { margin-bottom: 0; }
        .artisan-icon {
            width: 40px; height: 40px; border-radius: 10px; background: var(--cl-caramel-bg);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .artisan-icon svg { color: var(--cl-caramel); }
        .artisan-item-title { font-size: 13px; font-weight: 600; margin: 0 0 4px; }
        .artisan-item-desc { font-size: 12px; color: var(--cl-muted); margin: 0; line-height: 1.5; }

        /* INSPIRATION CARD */
        .inspiration-card { border-radius: 16px; overflow: hidden; position: relative; min-height: 200px; }
        .inspiration-card img { width: 100%; height: 220px; object-fit: cover; display: block; }
        .inspiration-overlay {
            position: absolute; bottom: 0; left: 0; right: 0; padding: 20px;
            background: linear-gradient(to top, rgba(0,0,0,.75) 0%, transparent 100%);
            color: white;
        }
        .inspiration-label { font-size: 10px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; opacity: .8; margin: 0 0 4px; }
        .inspiration-title { font-family: 'DM Serif Display', serif; font-size: 18px; font-weight: 400; margin: 0; line-height: 1.3; }

        /* FOOTER */
        footer {
            background: var(--cl-white); border-top: 1px solid var(--cl-border);
            padding: 32px 40px; display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 16px;
        }
        .footer-brand .brand-name { font-family: 'DM Serif Display', serif; font-size: 18px; margin: 0 0 4px; }
        .footer-brand p { font-size: 12px; color: var(--cl-muted); margin: 0; }
        .footer-links { display: flex; gap: 24px; }
        .footer-links a { font-size: 13px; color: var(--cl-muted); text-decoration: none; transition: color .2s; }
        .footer-links a:hover { color: var(--cl-dark); }
    </style>
</head>
<body>

{{-- @extends('base.base') --}}
{{-- @section('content') --}}

@php
    $activeFilter = 'Shipped';
    $filters = ['All', 'Unpaid', 'Processing', 'Shipped', 'Completed', 'Returns', 'Cancelled'];

    $order = [
        'name'    => 'The Kyoto Lounge Chair',
        'id'      => '#CL-8924',
        'status'  => 'Shipped',
        'eta'     => 'Thursday, Oct 26',
        'image'   => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=400&h=400&fit=crop',
        'steps'   => [
            ['label' => 'Confirmed',     'state' => 'done'],
            ['label' => 'In Production', 'state' => 'done'],
            ['label' => 'Shipped',       'state' => 'active'],
            ['label' => 'Delivered',     'state' => 'pending'],
        ],
    ];

    $artisanUpdates = [
        [
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
            'title' => 'QC Inspection Passed',
            'desc'  => 'Our master craftsmen have verified the finish on your lounge chair.',
        ],
        [
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 22a8 8 0 0 1 8-8h4a8 8 0 0 1 8 8"/><circle cx="12" cy="7" r="4"/></svg>',
            'title' => 'Sustainable Packaging',
            'desc'  => 'Your order is secured using 100% recycled structural fiber panels.',
        ],
    ];
@endphp

{{-- NAV --}}
<nav>
    <a href="#" class="nav-logo">CasaLoom</a>
    <ul class="nav-links">
        <li><a href="#">Shop</a></li>
        <li><a href="#">Inspiration</a></li>
        <li><a href="#">Atelier</a></li>
        <li><a href="#">About</a></li>
    </ul>
    <div class="nav-right">
        <div class="nav-search">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            Search Atelier
        </div>
        <button class="nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        </button>
        <button class="nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </button>
    </div>
</nav>

{{-- MAIN --}}
<main>

    {{-- PAGE HEADER --}}
    <div class="page-header">
        <h1 class="font-serif">My Orders</h1>
        <p>Manage your architectural additions and track their journey from our artisan workshops to your sanctuary.</p>
    </div>

    {{-- FILTER TABS --}}
    <div class="filter-tabs">
        @foreach ($filters as $filter)
            <button class="filter-tab {{ $filter === $activeFilter ? 'active' : '' }}">
                {{ $filter }}
            </button>
        @endforeach
    </div>

    {{-- CONTENT GRID --}}
    <div class="content-grid">

        {{-- LEFT: ORDER CARD --}}
        <div>
            <div class="order-card">

                {{-- Order Top --}}
                <div class="order-card-top">
                    <img src="{{ $order['image'] }}" alt="{{ $order['name'] }}" class="order-img">
                    <div class="order-info">
                        <h2 class="font-serif">{{ $order['name'] }}</h2>
                        <p class="order-id">ORDER ID: {{ $order['id'] }}</p>
                        <span class="status-badge">{{ strtoupper($order['status']) }}</span>
                        <div class="order-actions">
                            <button class="btn-track">Track Order</button>
                            <button class="btn-details">Order Details</button>
                        </div>
                    </div>
                </div>

                {{-- Tracking --}}
                <div class="tracking-box">
                    <p class="tracking-label">Estimated Arrival</p>
                    <p class="tracking-eta font-serif">Arriving by {{ $order['eta'] }}</p>

                    <div class="tracking-steps">
                        @foreach ($order['steps'] as $index => $step)
                            @if ($index > 0)
                                <div class="step-connector {{ $step['state'] === 'done' || $order['steps'][$index-1]['state'] === 'done' ? 'done' : '' }}"></div>
                            @endif
                            <div class="step">
                                <div class="step-circle {{ $step['state'] }}">
                                    @if ($step['state'] === 'done')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    @elseif ($step['state'] === 'active')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="5 12 12 5 19 12"/><line x1="12" y1="19" x2="12" y2="5"/></svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--cl-muted)"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                    @endif
                                </div>
                                <span class="step-label">{{ $step['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        {{-- RIGHT: SIDEBAR --}}
        <div class="sidebar">

            {{-- Artisan Update --}}
            <div class="artisan-card">
                <h3 class="font-serif">Artisan Update</h3>
                @foreach ($artisanUpdates as $update)
                    <div class="artisan-item">
                        <div class="artisan-icon">{!! $update['icon'] !!}</div>
                        <div>
                            <p class="artisan-item-title">{{ $update['title'] }}</p>
                            <p class="artisan-item-desc">{{ $update['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Inspiration Card --}}
            <div class="inspiration-card">
                <img src="https://images.unsplash.com/photo-1519710164239-da123dc03ef4?w=700&h=450&fit=crop" alt="Artisan Sideboards">
                <div class="inspiration-overlay">
                    <p class="inspiration-label">Inspiration</p>
                    <p class="inspiration-title font-serif">Complete the Look with our Artisan Sideboards</p>
                </div>
            </div>

        </div>
    </div>
</main>

{{-- FOOTER --}}
<footer>
    <div class="footer-brand">
        <p class="brand-name font-serif">CasaLoom</p>
        <p>© 2024 CasaLoom Atelier. Curating the modern sanctuary.</p>
    </div>
    <div class="footer-links">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
        <a href="#">Shipping &amp; Returns</a>
        <a href="#">Contact</a>
    </div>
</footer>

{{-- @endsection --}}

{{-- TEMPORARY WRAPPER - hapus ini kalau base layout udah jadi --}}
</body>
</html>
{{-- END TEMPORARY WRAPPER --}}