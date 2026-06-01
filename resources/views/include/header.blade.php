<nav id="mainNavbar" class="navbar navbar-expand-lg fixed-top py-3 py-lg-4 px-md-4 transition-navbar {{ request()->routeIs('home') ? 'home-navbar' : 'solid-navbar' }}">
    <div class="container-fluid">

        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <div class="logo-wrapper p-2 rounded-2 me-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 38px;">
                <img src="{{ asset('image/jaced_logo1.png') }}" alt="Jaced Logo" class="logo-default" style="width: 49px; height: 30px;">
                <img src="{{ asset('image/jaced_logo2.png') }}" alt="Jaced Logo" class="logo-white" style="width: 49px; height: 30px;">
            </div>
            <span class="fw-black tracking-tighter fs-4 branding-title" style="font-weight: 900; letter-spacing: -1px;">Jaced Furniture</span>
        </a>

        {{-- Tombol Hamburger Menu Garis Tiga --}}
        <button class="navbar-toggler border-0 shadow-none p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="custom-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-4 mobile-nav-spacing">
                <li class="nav-item">
                    <a class="nav-link text-uppercase tracking-widest fw-bold {{ request()->routeIs('home') ? 'active' : '' }}" style="font-size: 14px; letter-spacing: 2px;" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-uppercase tracking-widest fw-bold {{ request()->routeIs('shop') ? 'active' : '' }}" style="font-size: 14px; letter-spacing: 2px;" href="{{ route('shop') }}">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-uppercase tracking-widest fw-bold {{ request()->routeIs('about') ? 'active': '' }}" style="font-size: 14px; letter-spacing: 2px;" href="{{ route('about') }}">About</a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link text-uppercase tracking-widest fw-bold {{ request()->routeIs('store.orderhistory') ? 'active' : '' }}" style="font-size: 14px; letter-spacing: 2px;" href="{{ route('store.orderhistory') }}">My Orders</a>
                    </li>
                @endauth
            </ul>

            <div class="d-flex align-items-center gap-4 ms-lg-4 mobile-action-wrap">
                @auth
                    <a href="{{ route('wishlist') }}" class="header-wishlist">
                        <i class="fas fa-heart"></i>
                    </a>
                    <a href="#" class="nav-icon opacity-75 hover-opacity-100 transition position-relative d-flex align-items-center justify-content-center" style="width:42px; height:42px; color:inherit;"
                       data-bs-toggle="offcanvas" data-bs-target="#cartSidebar">
                        <i class="fas fa-shopping-bag" style="font-size: 18px;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark" style="font-size: 8px; margin-top: 6px; margin-left: -6px;">{{ $globalCartItems->sum('quantity') }}</span>
                    </a>
                @endauth

                @guest
                    <a href="/login" class="btn btn-login btn-sm px-4 fw-bold rounded-pill shadow-sm w-mobile-100">Login</a>
                @else
                    <div class="position-relative w-mobile-100">
                        <button class="btn border-0 btn-sm fw-bold d-flex align-items-center justify-content-between user-btn w-mobile-100" type="button" id="customUserDropdown">
                            <span class="d-flex align-items-center">
                                <i class="fas fa-user-circle me-2 fs-5"></i>
                                <span style="font-size: 12px; letter-spacing: 1px;">{{ strtoupper(auth()->user()->name) }}</span>
                            </span>
                            <i class="fas fa-chevron-down ms-2 d-lg-none" style="font-size: 10px;"></i>
                        </button>
                        <div class="dropdown-menu shadow-lg border-0 mt-2 position-absolute rounded-3" id="customUserMenu" style="right: 0; left: auto; min-width: 180px; display: none; z-index: 1050; background: #fff;">
                            <a href="{{ route('profile.edit', Auth::user()->id) }}" 
                            class="dropdown-item text-dark d-flex align-items-center py-2 px-3 fw-bold border-0 bg-transparent w-100" 
                            style="font-size: 12px;">
                                <i class="fas fa-user me-2"></i> PROFILE
                            </a>
                            <a href="{{ route('wishlist') }}" class="dropdown-item text-dark d-flex align-items-center py-2 px-3 fw-bold border-0 bg-transparent w-100" style="font-size: 12px;">
                                <i class="fas fa-heart me-2"></i> WISHLIST
                            </a>
                            <a href="{{ route('reward') }}" class="dropdown-item text-dark d-flex align-items-center py-2 px-3 fw-bold border-0 bg-transparent w-100" style="font-size: 12px;">
                                <i class="fas fa-trophy me-2" style="color: #C99A6B;"></i> REWARD
                            </a>
                            <hr class="dropdown-divider my-1" style="border-color: #DDD6CE; opacity: 0.5;">
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger d-flex align-items-center py-2 px-3 fw-bold border-0 bg-transparent w-100" style="font-size: 12px;">
                                    <i class="fas fa-sign-out-alt me-2"></i> LOGOUT
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

<style>
    .transition-navbar { transition: all 0.35s ease; }
    .transition-navbar.home-navbar { background: transparent; }
    .transition-navbar.solid-navbar {
        background: rgba(255,255,255,0.68); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
        border-bottom: 1px solid rgba(255,255,255,0.18); box-shadow: 0 4px 30px rgba(0,0,0,0.04);
    }
    .transition-navbar.scrolled {
        background: rgba(255,255,255,0.95) !important; border-bottom: 1px solid rgba(0,0,0,0.08); box-shadow: 0 4px 30px rgba(0,0,0,0.04);
    }
    .header-wishlist { position: relative; display: flex; align-items: center; justify-content: center; width: 42px; height: 42px; border-radius: 50%; color: inherit; text-decoration: none; transition: all 0.3s ease; }
    .header-wishlist:hover { background: rgba(0,0,0,0.05); color: #C99A6B; }
    .custom-toggler-icon { display: block; width: 22px; height: 2px; background: currentColor; position: relative; transition: background 0.3s ease; }
    .custom-toggler-icon::before, .custom-toggler-icon::after { content: ''; display: block; width: 22px; height: 2px; background: currentColor; position: absolute; left: 0; transition: transform 0.3s ease, top 0.3s ease; }
    .custom-toggler-icon::before { top: -6px; } .custom-toggler-icon::after { top: 6px; }
    .navbar-toggler[aria-expanded="true"] .custom-toggler-icon { background: transparent; }
    .navbar-toggler[aria-expanded="true"] .custom-toggler-icon::before { top: 0; transform: rotate(45deg); }
    .navbar-toggler[aria-expanded="true"] .custom-toggler-icon::after { top: 0; transform: rotate(-45deg); }
    .logo-default { display: none; } .logo-white { display: block; }
    .transition-navbar.scrolled .logo-default, .transition-navbar.solid-navbar .logo-default { display: block; }
    .transition-navbar.scrolled .logo-white, .transition-navbar.solid-navbar .logo-white { display: none; }
    .transition-navbar.home-navbar:not(.scrolled) .navbar-brand, .transition-navbar.home-navbar:not(.scrolled) .nav-link, .transition-navbar.home-navbar:not(.scrolled) .navbar-toggler, .transition-navbar.home-navbar:not(.scrolled) .user-btn { color: #f3f3f1 !important; }
    .transition-navbar.scrolled .navbar-brand, .transition-navbar.scrolled .nav-link, .transition-navbar.scrolled .user-btn, .transition-navbar.solid-navbar .navbar-brand, .transition-navbar.solid-navbar .nav-link, .transition-navbar.solid-navbar .user-btn { color: #1c1c1a !important; }
    .transition-navbar.home-navbar:not(.scrolled) .btn-login { background-color: #f3f3f1; color: #1c1c1a; border: none; }
    .transition-navbar.scrolled .btn-login, .transition-navbar.solid-navbar .btn-login { background-color: #1c1c1a; color: #ffffff; border: none; }
    .nav-link { position: relative; transition: all 0.3s ease; opacity: 0.7; }
    .nav-link::after { content: ''; position: absolute; left: 0; bottom: 0px; width: 0%; height: 1px; background: currentColor; transition: width 0.3s ease; }
    .nav-link:hover { opacity: 1 !important; transform: translateY(-1px); }
    .nav-link:hover::after, .nav-link.active::after { width: 100%; } .nav-link.active { opacity: 1 !important; }
    .tracking-widest { letter-spacing: 0.2em; } .tracking-tighter { letter-spacing: -0.05em; }
    .transition-navbar.preload { opacity: 0; transform: translateY(-24px); animation: navbarReveal 0.8s ease forwards; animation-delay: 0.25s; }
    @keyframes navbarReveal { to { opacity: 1; transform: translateY(0); } }

    @media (max-width: 991.98px) {
        .navbar-collapse { background: #ffffff; padding: 24px; border-radius: 12px; margin-top: 14px; box-shadow: 0 12px 36px rgba(0,0,0,0.1); }
        .navbar-collapse .nav-link, .navbar-collapse .user-btn { color: #1c1c1a !important; text-shadow: none !important; padding: 10px 0; }
        .mobile-nav-spacing { margin-bottom: 20px !important; }
        .mobile-action-wrap { flex-direction: column; align-items: flex-start !important; width: 100%; gap: 14px !important; }
        .w-mobile-100 { width: 100% !important; justify-content: center; }
        #customUserMenu { position: static !important; width: 100%; box-shadow: none !important; background: #faf9f6; }
        .branding-title { font-size: 1.15rem !important; }
    }
</style>

<script>
    (function() {
        const navbar = document.getElementById('mainNavbar');
        const isHomePage = @json(request()->routeIs('home'));
        if (isHomePage && navbar) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 40) navbar.classList.add('scrolled');
                else navbar.classList.remove('scrolled');
            });
            navbar.classList.add('preload');
        }
        const userBtn = document.getElementById('customUserDropdown');
        const userMenu = document.getElementById('customUserMenu');
        if (userBtn && userMenu) {
            userBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                userMenu.style.display = userMenu.style.display === 'block' ? 'none' : 'block';
            });
            document.addEventListener('click', () => userMenu.style.display = 'none');
        }
    })();
</script>