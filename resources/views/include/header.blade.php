<nav id="mainNavbar" class="navbar navbar-expand-lg fixed-top py-4 px-md-4 transition-navbar">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <div class="logo-wrapper p-2 rounded-2 me-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 38px;">
                {{-- Dark logo (shows when scrolled) --}}
                <img src="{{ asset('image/jaced_logo1.png') }}" alt="Jaced Logo" class="logo-default" style="width: 49px; height: 30px;">
                {{-- White logo (shows when navbar transparent) --}}
                <img src="{{ asset('image/jaced_logo2.png') }}" alt="Jaced Logo" class="logo-white" style="width: 49px; height: 30px;">
            </div>
            <span class="fw-black tracking-tighter fs-4" style="font-weight: 900; letter-spacing: -1px;">Jaced Furniture</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-4">
                <li class="nav-item">
                    <a class="nav-link text-uppercase tracking-widest fw-bold {{ request()->routeIs('home') ? 'active' : '' }}" style="font-size: 14px; letter-spacing: 2px;" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-uppercase tracking-widest fw-bold {{ request()->routeIs('shop') ? 'active' : '' }}" style="font-size: 14px; letter-spacing: 2px;" href="{{ route('shop') }}">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-uppercase tracking-widest fw-bold" style="font-size: 14px; letter-spacing: 2px;" href="/about">About</a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link text-uppercase tracking-widest fw-bold" style="font-size: 14px; letter-spacing: 2px;" href="/orders">My Orders</a>
                    </li>
                @endauth
            </ul>

            <div class="d-flex align-items-center gap-4 ms-lg-4">
                <a href="#" class="nav-icon opacity-75 hover-opacity-100 transition"><i class="fas fa-search"></i></a>

                @auth
                    <a href="/cart" class="nav-icon opacity-75 hover-opacity-100 transition position-relative">
                        <i class="fas fa-shopping-bag"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark" style="font-size: 8px;">0</span>
                    </a>
                @endauth

                @guest
                    <a href="/login" class="btn btn-login btn-sm px-4 fw-bold rounded-pill shadow-sm">Login</a>
                @else
                    <div class="position-relative">
                        <button class="btn border-0 btn-sm fw-bold d-flex align-items-center user-btn" type="button" id="customUserDropdown">
                            <i class="fas fa-user-circle me-2 fs-5"></i>
                            <span style="font-size: 12px; letter-spacing: 1px;">{{ strtoupper(auth()->user()->name) }}</span>
                        </button>
                        <div class="dropdown-menu shadow-lg border-0 mt-2 position-absolute rounded-3" id="customUserMenu" style="right: 0; left: auto; min-width: 180px; display: none; z-index: 1050; background: #fff;">
                            <a class="dropdown-item text-danger d-flex align-items-center py-2 px-3 fw-bold" style="font-size: 12px;" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                <i class="fas fa-sign-out-alt me-2"></i> LOGOUT
                            </a>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

<style>
    /* ===== BASE NAVBAR (always transparent + white) ===== */
    .transition-navbar {
        transition: all 0.35s ease;
        background: transparent;
    }

    .transition-navbar.scrolled {
        background: rgba(255,255,255,0.68);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border-bottom: 1px solid rgba(255,255,255,0.18);
        box-shadow: 0 4px 30px rgba(0,0,0,0.04);
    }

    /* ===== LOGO SWAP (white default, dark on scroll) ===== */
    .logo-default { display: none; }
    .logo-white   { display: block; }

    .transition-navbar.scrolled .logo-default { display: block; }
    .transition-navbar.scrolled .logo-white   { display: none; }

    /* ===== TEXT COLOR (white default, dark on scroll) ===== */
    .transition-navbar .navbar-brand,
    .transition-navbar .nav-link,
    .transition-navbar .nav-icon,
    .transition-navbar .nav-icon i,
    .transition-navbar .navbar-toggler,
    .transition-navbar .user-btn {
        color: #f3f3f1 !important;
        text-shadow: 0 1px 12px rgba(0, 0, 0, 0.25);
    }

    .transition-navbar .btn-login {
        background-color: #f3f3f1;
        color: #1f1f1f;
        border: none;
        text-shadow: none;
    }

    .transition-navbar.scrolled .navbar-brand,
    .transition-navbar.scrolled .nav-link,
    .transition-navbar.scrolled .nav-icon,
    .transition-navbar.scrolled .nav-icon i,
    .transition-navbar.scrolled .navbar-toggler,
    .transition-navbar.scrolled .user-btn {
        color: #1f1f1f !important;
        text-shadow: none;
    }

    .transition-navbar.scrolled .btn-login {
        background-color: #1f1f1f;
        color: #ffffff;
        border: none;
    }

    /* ===== NAV LINK UNDERLINE ===== */
    .nav-link {
        position: relative;
        transition: all 0.3s ease;
        opacity: 0.7;
    }
    .nav-link::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0px;
        width: 0%;
        height: 1px;
        background: currentColor;
        transition: width 0.3s ease;
    }
    .nav-link:hover {
        opacity: 1 !important;
        transform: translateY(-1px);
    }
    .nav-link:hover::after {
        width: 100%;
    }
    .nav-link.active {
        opacity: 1 !important;
    }
    .nav-link.active::after {
        width: 100%;
    }

    .tracking-widest { letter-spacing: 0.2em; }
    .tracking-tighter { letter-spacing: -0.05em; }
    .hover-opacity-100:hover { opacity: 1 !important; }

    /* ===== PRELOAD INTRO ANIMATION (HOME ONLY) ===== */
    .transition-navbar.preload {
        opacity: 0;
        transform: translateY(-24px);
        animation: navbarReveal 0.8s ease forwards;
        animation-delay: 0.25s;
    }

    @keyframes navbarReveal {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-0" style="background: #f3f3f1;">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-black tracking-tighter">CONFIRM LOGOUT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <p class="mb-0 text-muted" style="font-size: 14px;">Are you sure you want to exit your session?</p>
            </div>
            <div class="modal-footer border-0 pb-4 px-4">
                <button type="button" class="btn btn-link text-decoration-none fw-bold" style="font-size: 12px;" data-bs-dismiss="modal">CANCEL</button>
                <form action="/logout" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-dark rounded-0 px-4 fw-bold" style="font-size: 12px;">LOGOUT</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const navbar = document.getElementById('mainNavbar');

    // Scroll: add .scrolled class past 40px
    window.addEventListener('scroll', () => {
        if (window.scrollY > 40) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Preload intro: ONLY on home page. Not on Shop, About, etc.
    const isHomePage = @json(request()->routeIs('home'));

    if (isHomePage) {
        navbar.classList.add('preload');
    }

    history.scrollRestoration = "manual";
    window.scrollTo(0, 0);

    // User dropdown toggle
    const userBtn = document.getElementById('customUserDropdown');
    const userMenu = document.getElementById('customUserMenu');
    if (userBtn && userMenu) {
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userMenu.style.display = userMenu.style.display === 'block' ? 'none' : 'block';
        });
        document.addEventListener('click', () => {
            userMenu.style.display = 'none';
        });
    }
</script>