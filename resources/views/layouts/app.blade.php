<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Jaced Furniture') }} — @yield('title', 'Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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

        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; background: var(--jaced-cream); font-family: 'Lexend', sans-serif !important; color: var(--jaced-brown-dark); }

        /* ── APP SHELL ── */
        .app-shell { display: flex; width: 100%; height: 100vh; overflow: hidden; }

        /* ── MAIN AREA ── */
        .main-area { 
            flex: 1; 
            display: flex; 
            flex-direction: column; 
            height: 100vh; 
            overflow: hidden; 
            min-width: 0;
            transition: all 0.3s ease;
            z-index: 1010;
        }

        /* ── PAGE CONTENT ── */
        .page-content { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 28px 28px 48px; }
        .page-content::-webkit-scrollbar { width: 5px; }
        .page-content::-webkit-scrollbar-thumb { background: #d4d0ca; border-radius: 6px; }

        /* ── JACED UTILITIES ── */
        .jaced-card {
            background-color: var(--jaced-card);
            border-radius: 12px;
            border: none;
            padding: 24px;
        }
        .btn-jaced-primary {
            background-color: var(--jaced-brown-dark) !important;
            color: white !important;
            border-radius: 8px;
            padding: 10px 20px;
            border: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .btn-jaced-primary:hover { background-color: #38422a !important; }
        .input-jaced {
            background-color: var(--jaced-input) !important;
            border: none !important;
            border-radius: 8px !important;
            color: var(--jaced-brown-dark) !important;
        }
        .text-jaced-muted { color: var(--jaced-muted) !important; }
        .text-jaced-sage { color: var(--jaced-sage) !important; }
        .divider-jaced { border-color: var(--jaced-input) !important; }

        /* ── OVERLAY (mobile) ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            backdrop-filter: blur(2px);
        }
        .sidebar-overlay.show { display: block; }

        @media (max-width: 768px) {
            .page-content { padding: 20px 16px 40px; }
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<div class="app-shell">
    {{-- SIDEBAR --}}
    @include('components.sidebar')

    {{-- MAIN AREA --}}
    <div class="main-area">
        {{-- TOPBAR --}}
        @include('components.topbar')

        {{-- PAGE CONTENT --}}
        <main class="page-content">
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('modals')

<script>
    // ── SIDEBAR TOGGLE (mobile drawer) ──
    function openSidebar() {
        document.getElementById('appSidebar').classList.add('open');
        document.getElementById('sidebarOverlay').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        document.getElementById('appSidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('show');
        document.body.style.overflow = '';
    }

    window.addEventListener('resize', function () {
        if (window.innerWidth > 768) closeSidebar();
    });
</script>
@stack('scripts')
</body>
</html>