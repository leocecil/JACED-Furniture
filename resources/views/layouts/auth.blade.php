<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Jaced Furniture') }} — @yield('title', 'Auth')</title>

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
            min-height: 100vh;
            background-color: var(--jaced-cream);
            font-family: 'Lexend', sans-serif;
            color: var(--jaced-brown-dark);
        }

        /* ── LAYOUT ── */
        .auth-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 24px;
        }

        /* ── LOGO ── */
        .brand-logo {
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
            transition: opacity 0.2s;
        }

        .brand-logo:hover {
            opacity: 0.8;
        }

        .brand-logo span {
            font-weight: 800;
            font-size: 1.4rem;
            letter-spacing: -1.5px;
            color: var(--jaced-brown-dark);
        }

        /* ── CARD ── */
        .jaced-auth-card {
            background-color: var(--jaced-card);
            border-radius: 16px;
            width: 100%;
            max-width: 440px;
            padding: 40px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        }

        /* ── CARD TITLE ── */
        .auth-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--jaced-brown-dark);
            margin-bottom: 6px;
        }

        .auth-subtitle {
            font-size: 0.85rem;
            color: var(--jaced-muted);
            margin-bottom: 28px;
        }

        /* ── FORM INPUTS ── */
        .input-jaced {
            background-color: var(--jaced-input) !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 12px 16px !important;
            font-size: 14px !important;
            font-family: 'Lexend', sans-serif !important;
            color: var(--jaced-brown-dark) !important;
            box-shadow: none !important;
            transition: box-shadow 0.2s;
        }

        .input-jaced:focus {
            box-shadow: 0 0 0 2px var(--jaced-sage) !important;
        }

        .input-jaced::placeholder {
            color: var(--jaced-muted) !important;
        }

        .input-jaced:disabled,
        .input-jaced[disabled] {
            opacity: 0.6 !important;
            cursor: not-allowed !important;
        }

        /* ── LABEL ── */
        .label-jaced {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--jaced-brown);
            margin-bottom: 6px;
            display: block;
        }

        /* ── INPUT WITH TOGGLE BUTTON ── */
        .input-icon-wrap {
            position: relative;
        }

        .input-icon-wrap .input-jaced {
            padding-right: 44px !important;
        }

        .input-icon-wrap .btn-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            color: var(--jaced-muted);
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }

        .input-icon-wrap .btn-icon:hover {
            color: var(--jaced-brown);
        }

        .input-icon-wrap .btn-icon svg {
            width: 18px;
            height: 18px;
        }

        /* ── ERROR MESSAGE ── */
        .error-msg {
            font-size: 0.78rem;
            color: #c0392b;
            margin-top: 5px;
            display: block;
        }

        .input-jaced.is-error {
            box-shadow: 0 0 0 2px #c0392b !important;
        }

        /* ── BUTTONS ── */
        .btn-jaced-primary {
            background-color: var(--jaced-brown-dark) !important;
            color: #fff !important;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-family: 'Lexend', sans-serif;
            font-size: 0.9rem;
            border: none;
            width: 100%;
            transition: background-color 0.2s, transform 0.1s;
            cursor: pointer;
        }

        .btn-jaced-primary:hover {
            background-color: #38422a !important;
        }

        .btn-jaced-primary:active {
            transform: scale(0.99);
        }

        .btn-jaced-secondary {
            background-color: transparent !important;
            color: var(--jaced-brown-dark) !important;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-family: 'Lexend', sans-serif;
            font-size: 0.9rem;
            border: 1.5px solid var(--jaced-input) !important;
            width: 100%;
            transition: border-color 0.2s, background-color 0.2s;
            cursor: pointer;
        }

        .btn-jaced-secondary:hover {
            background-color: var(--jaced-input) !important;
        }

        /* ── DIVIDER ── */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
            color: var(--jaced-muted);
            font-size: 0.8rem;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: var(--jaced-input);
        }

        /* ── LINKS ── */
        .auth-link {
            color: var(--jaced-sage);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
            transition: color 0.2s;
        }

        .auth-link:hover {
            color: var(--jaced-brown-dark);
            text-decoration: underline;
        }

        /* ── FLASH MESSAGES ── */
        .flash-success {
            background-color: #eaf4ef;
            color: #276749;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .flash-error {
            background-color: #fdecea;
            color: #c0392b;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .flash-info {
            background-color: #eef3fb;
            color: #2c5fa0;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="auth-container">

    {{-- Logo --}}
    <a class="brand-logo" href="{{ url('/') }}">
        <img src="{{ asset('image/jaced_logo1.png') }}" alt="Jaced Logo" style="width: 38px; height: auto;">
        <span>Jaced Furniture</span>
    </a>

    {{-- Card --}}
    <div class="jaced-auth-card">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="flash-success">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="flash-error">
                <i class="bi bi-x-circle-fill"></i>
                {{ session('error') }}
            </div>
        @endif

        @if(session('status'))
            <div class="flash-info">
                <i class="bi bi-info-circle-fill"></i>
                {{ session('status') }}
            </div>
        @endif

        {{-- Konten Halaman --}}
        @yield('content')

    </div>

    {{-- Footer kecil --}}
    <p style="margin-top: 24px; font-size: 0.78rem; color: var(--jaced-muted);">
        &copy; {{ date('Y') }} Jaced Furniture. All rights reserved.
    </p>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>