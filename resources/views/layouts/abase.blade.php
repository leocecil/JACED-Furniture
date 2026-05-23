<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Jaced Furniture') }} — @yield('title', 'Login')</title>

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

        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
        }

        body { 
            height: 100vh; 
            background-color: var(--jaced-cream) !important; 
            font-family: 'Lexend', sans-serif !important;
            color: var(--jaced-brown-dark);
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 24px;
        }

        /* ── LOGO STYLING ── */
        .brand-logo {
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
            transition: opacity 0.2s;
        }

        .brand-logo:hover {
            opacity: 0.85;
        }

        .brand-logo span {
            font-weight: 800;
            letter-spacing: -1.5px;
            color: var(--jaced-brown-dark);
        }

        /* ── AUTH CARD ── */
        .jaced-auth-card {
            background-color: var(--jaced-card);
            border-radius: 16px;
            width: 100%;
            max-width: 440px;
            padding: 40px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
            border: none;
        }

        /* Reusable Form Inputs inside child views */
        .input-jaced {
            background-color: var(--jaced-input) !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 12px 16px !important;
            font-size: 14px !important;
            color: var(--jaced-brown-dark) !important;
            box-shadow: none !important;
        }

        .input-jaced:focus {
            box-shadow: 0 0 0 2px var(--jaced-sage) !important;
        }

        .btn-jaced-primary {
            background-color: var(--jaced-brown-dark) !important;
            color: white !important;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            border: none;
            transition: all 0.2s;
        }

        .btn-jaced-primary:hover {
            background-color: #38422a !important;
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="auth-container">
    
    <a class="brand-logo" href="#">
        <img src="{{ asset('image/jaced_logo1.png') }}" alt="Jaced Logo" style="width: 38px; height: auto;">
        <span class="fs-3">Jaced Furniture</span>
    </a>

    <div class="jaced-auth-card">
        
        {{-- Flash Messages --}}
        @if(session('error'))
            <div class="alert alert-danger border-0 small py-2 d-flex align-items-center gap-2 mb-3" style="border-radius: 8px;">
                <i class="bi bi-x-circle-fill"></i> {{ session('error') }}
            </div>
        @endif

        {{-- Konten Dinamis Halaman Form Login --}}
        @yield('content')
        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>