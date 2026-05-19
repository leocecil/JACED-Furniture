<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Studio Manager') }} — @yield('title', 'Dashboard')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width:          220px;
            --topbar-height:          60px;
            --color-sidebar:          #1a1a18;
            --color-sidebar-hover:    #2a2a27;
            --color-sidebar-active:   #2f2d28;
            --color-sidebar-text:     #a8a49e;
            --color-sidebar-text-act: #f5f2ee;
            --color-accent:           #c4a882;
            --color-bg:               #f0eeeb;
            --color-border:           #e2ddd8;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; background: var(--color-bg); font-family: system-ui, -apple-system, 'Segoe UI', sans-serif; }

        .app-shell { display: flex; width: 100%; height: 100vh; overflow: hidden; }

        .main-area { flex: 1; display: flex; flex-direction: column; height: 100vh; overflow: hidden; min-width: 0; }

        /* PAGE CONTENT */
        .page-content { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 28px 28px 48px; }
        .page-content::-webkit-scrollbar { width: 5px; }
        .page-content::-webkit-scrollbar-thumb { background: #d4d0ca; border-radius: 6px; }

        /* PAGE HEADER */
        .breadcrumb { font-size: 11px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 4px !important; }
        .breadcrumb-item a { color: #9c9890; text-decoration: none; }
        .breadcrumb-item a:hover { color: #6b6860; }
        .breadcrumb-item.active { color: #6b6860; }
        .breadcrumb-item + .breadcrumb-item::before { color: #bbb8b2; }
        .page-title { font-size: 24px; font-weight: 700; letter-spacing: -0.02em; color: #1a1a18; margin: 0; }
    </style>

    {{-- CSS tambahan dari halaman child --}}
    @stack('styles')
</head>
<body>

<div class="app-shell">

    {{-- =====================
         SIDEBAR (component)
         resources/views/components/sidebar.blade.php
    ===================== --}}
    @include('components.sidebar')

    {{-- =====================
         MAIN AREA
    ===================== --}}
    <div class="main-area">

        {{-- TOPBAR (component)
             resources/views/components/topbar.blade.php --}}
        @include('components.topbar')

        {{-- PAGE CONTENT --}}
        <main class="page-content">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-x-circle-fill"></i> {{ session('error') }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('warning') }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Page Header --}}
            @hasSection('page-title')
                <div class="d-flex align-items-start justify-content-between mb-4">
                    <div>
                        @hasSection('breadcrumb')
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">@yield('breadcrumb')</ol>
                            </nav>
                        @endif
                        <h1 class="page-title">@yield('page-title')</h1>
                    </div>
                    @hasSection('page-actions')
                        <div class="d-flex gap-2 mt-1">@yield('page-actions')</div>
                    @endif
                </div>
            @endif

            {{-- KONTEN UTAMA --}}
            @yield('content')

        </main>

    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- Modal dirender di sini — di luar .app-shell agar tidak terpotong overflow --}}
@stack('modals')

{{-- JS tambahan dari halaman child --}}
@stack('scripts')

</body>
</html>