<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>JACED Furniture</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
    @stack('styles') 
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: var(--jaced-caramel);
        }
        .main-content {
            flex: 1;
        }
        .main-content.default-content {
            padding-top: 6rem !important;
        }
    </style>
</head>
<body>
    @include('include.header')
    
    <div class="container-fluid main-content px-4 px-md-5 py-5 {{ request()->routeIs('home') ? '' : 'default-content' }}" style="margin:0 auto; max-width: 1400px;">
        @yield('content')
    </div>
    
    {{-- GLOBAL CART --}}
    @include('partials.cart-sidebar')

    @include('include.footer')

    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // reopen cart after reload
        if(localStorage.getItem('cartOpen') === 'true'){
            const cartSidebar = document.getElementById('cartSidebar');
            const bsOffcanvas = new bootstrap.Offcanvas(cartSidebar);
            bsOffcanvas.show();
        }

        // save state when opened
        const cartSidebar = document.getElementById('cartSidebar');

        if(cartSidebar){

            cartSidebar.addEventListener('shown.bs.offcanvas', () => {
                localStorage.setItem('cartOpen', 'true');
            });

            cartSidebar.addEventListener('hidden.bs.offcanvas', () => {
                localStorage.setItem('cartOpen', 'false');
            });
        }
    </script>
    @stack('scripts')

    {{-- Toast Notification --}}
    @if(session('success') || session('error') || session('warning') || session('info'))
    <div id="toast-notif" style="position:fixed; top:24px; right:24px; z-index:99999; animation:slideInToast .3s ease; min-width:300px; max-width:400px;">
        <div style="background:white; border-radius:16px; box-shadow:0 8px 32px rgba(0,0,0,.12); display:flex; align-items:center; gap:14px; padding:16px 20px;">
            {{-- Icon Bulat --}}
            <div style="
                width:38px; height:38px; border-radius:50%; flex-shrink:0;
                display:flex; align-items:center; justify-content:center;
                background:{{ session('success') ? '#e8f5e9' : (session('error') ? '#fdecea' : (session('warning') ? '#fff8e1' : '#e3f2fd')) }};
            ">
                @if(session('success'))
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2e7d32" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                @elseif(session('error'))
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c62828" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                @elseif(session('warning'))
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f57f17" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                @else
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1565c0" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                @endif
            </div>

            {{-- Text --}}
            <div style="flex:1;">
                <p style="margin:0; font-size:13px; font-weight:600; color:#1a1714;">
                    {{ session('success') ?? session('error') ?? session('warning') ?? session('info') }}
                </p>
            </div>

            {{-- Close Button --}}
            <button onclick="document.getElementById('toast-notif').remove()" 
                style="background:none; border:none; color:#aaa; font-size:20px; cursor:pointer; padding:0; line-height:1; flex-shrink:0;">×</button>
        </div>
    </div>

    <style>
    @keyframes slideInToast {
        from { opacity:0; transform:translateY(-16px); }
        to   { opacity:1; transform:translateY(0); }
    }
    </style>

    <script>
        // Auto hide after 4 seconds
        setTimeout(() => {
            const toast = document.getElementById('toast-notif');
            if (toast) {
                toast.style.transition = 'opacity .3s, transform .3s';
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(8px)';
                setTimeout(() => toast.remove(), 300);
            }
        }, 4000);
    </script>
    @endif
</body>
</html>