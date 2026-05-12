<nav class="navbar navbar-expand-lg sticky-top py-4 px-md-5" style="background-color: #f3f3f1; border-bottom: 1px solid #e5e5e3;">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <div class="bg-dark p-2 rounded-2 me-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                <svg class="text-white" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <span class="fw-black tracking-tighter text-dark fs-4" style="font-weight: 900; letter-spacing: -1px;">Jaced Furniture</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-4">
                <li class="nav-item">
                    <a class="nav-link text-uppercase tracking-widest fw-bold {{ request()->routeIs('home') ? 'active' : '' }}" style="font-size: 11px; letter-spacing: 2px;" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-uppercase tracking-widest fw-bold {{ request()->routeIs('shop') ? 'active' : '' }}" style="font-size: 11px; letter-spacing: 2px;" href="{{ route('shop') }}">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-uppercase tracking-widest fw-bold text-dark-50" style="font-size: 11px; letter-spacing: 2px;" href="/about">About</a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link text-uppercase tracking-widest fw-bold text-dark-50" style="font-size: 11px; letter-spacing: 2px;" href="/orders">My Orders</a>
                    </li>
                @endauth
            </ul>

            <div class="d-flex align-items-center gap-4">
                <a href="#" class="text-dark opacity-75 hover-opacity-100 transition"><i class="fas fa-search"></i></a>
                
                @auth
                    <a href="/cart" class="text-dark opacity-75 hover-opacity-100 transition position-relative">
                        <i class="fas fa-shopping-bag"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark" style="font-size: 8px;">0</span>
                    </a>
                @endauth

                @guest
                    <a href="/login" class="btn btn-dark btn-sm px-4 fw-bold rounded-pill shadow-sm">Login</a>
                @else
                    <div class="position-relative">
                        <button class="btn btn-outline-dark border-0 btn-sm fw-bold d-flex align-items-center" type="button" id="customUserDropdown">
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
    /* Tambahan agar lebih mirip Furnispace */
    .nav-link {
        transition: all 0.3s ease;
        opacity: 0.6;
    }
    .nav-link:hover {
        opacity: 1 !important;
        transform: translateY(-1px);
    }
    .nav-link.active {
        opacity: 1 !important;
        color: #1f1f1f !important;
    }
    .tracking-widest { letter-spacing: 0.2em; }
    .tracking-tighter { letter-spacing: -0.05em; }
    .hover-opacity-100:hover { opacity: 1 !important; }
</style>

<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-0" style="background: #f3f3f1;">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-black text-dark tracking-tighter">CONFIRM LOGOUT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-dark px-4 py-3">
                <p class="mb-0 text-muted" style="font-size: 14px;">Are you sure you want to exit your session?</p>
            </div>
            <div class="modal-footer border-0 pb-4 px-4">
                <button type="button" class="btn btn-link text-dark text-decoration-none fw-bold" style="font-size: 12px;" data-bs-dismiss="modal">CANCEL</button>
                <form action="/logout" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-dark rounded-0 px-4 fw-bold" style="font-size: 12px;">LOGOUT</button>
                </form>
            </div>
        </div>
    </div>
</div>