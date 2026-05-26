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
            /* background-color: var(--jaced-cream); */
        }
        .main-content {
            flex: 1;
        }
        .main-content.default-content {
            padding-top: 8rem !important;
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
</body>
</html>