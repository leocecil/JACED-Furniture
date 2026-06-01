<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>JACED Furniture</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
    @stack('styles') 
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden; /* Mengunci layar agar tidak bisa digeser ke kanan jika ada elemen bocor */
        }
        
        .main-content {
            flex: 1;
            transition: padding-top 0.3s ease;
        }

        /* Jarak default dari atas untuk halaman selain Homepage di Layar Desktop */
        .main-content.default-content {
            padding-top: 8rem !important;
        }

        /* ══ RESPONSIVE ADAPTATION: Mengoptimalkan Jarak Konten Saat Dibuka di HP ══ */
        @media (max-width: 991.98px) {
            .main-content {
                padding-left: 12px !important;
                padding-right: 12px !important;
                padding-top: 2rem !important;
                padding-bottom: 2rem !important;
            }
            /* Menyesuaikan tumpukan konten agar tidak tertabrak navbar mobile yang memadat */
            .main-content.default-content {
                padding-top: 6.5rem !important; 
            }
        }
    </style>
</head>
<body>

    {{-- Komponen Navbar Atas --}}
    @include('include.header')
    
    {{-- Pembungkus Konten Utama Dinamis --}}
    <div class="container-fluid main-content px-4 px-md-5 py-5 {{ request()->routeIs('home') ? '' : 'default-content' }}" style="margin:0 auto; max-width: 1400px;">
        @yield('content')
    </div>
    
    {{-- GLOBAL CART SIDEBAR --}}
    @include('partials.cart-sidebar')

    {{-- Komponen Footer Bawah --}}
    @include('include.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cartSidebar = document.getElementById('cartSidebar');

            if (cartSidebar) {
                // Mengembalikan keadaan boks belanja terbuka otomatis setelah halaman di-reload
                if (localStorage.getItem('cartOpen') === 'true') {
                    const bsOffcanvas = new bootstrap.Offcanvas(cartSidebar);
                    bsOffcanvas.show();
                }

                // Rekam ke memori lokal browser saat boks belanja dibuka
                cartSidebar.addEventListener('shown.bs.offcanvas', () => {
                    localStorage.setItem('cartOpen', 'true');
                });

                // Rekam ke memori lokal browser saat boks belanja ditutup kembali
                cartSidebar.addEventListener('hidden.bs.offcanvas', () => {
                    localStorage.setItem('cartOpen', 'false');
                });
            }
        });
    </script>
    
    @stack('scripts')

    {{-- Toast Notification --}}
    @if(session('success') || session('error'))
    <div id="toast-notif" style="position:fixed; top:24px; right:24px; z-index:99999; animation:slideInToast .3s ease;">
        <div style="background:{{ session('success') ? '#1A1714' : '#C62828' }}; color:white; padding:14px 20px; border-radius:12px; box-shadow:0 8px 24px rgba(0,0,0,.2); display:flex; align-items:center; gap:12px; min-width:280px; max-width:380px;">
            <span style="font-size:18px;">{{ session('success') ? '✓' : '✕' }}</span>
            <p style="margin:0; font-size:13px; font-weight:500; flex:1;">
                {{ session('success') ?? session('error') }}
            </p>
            <button onclick="document.getElementById('toast-notif').remove()" 
                style="background:none; border:none; color:rgba(255,255,255,.7); font-size:18px; cursor:pointer; padding:0; line-height:1;">×</button>
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