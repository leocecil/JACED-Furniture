<div class="sidebar d-flex flex-column p-4">
    {{-- <!-- Header Logo & Brand -->
    <div class="mb-5">
        <a class="navbar-brand d-flex align-items-center gap-2" href="#" style="text-decoration: none;">
            <img src="{{ asset('image/jaced_logo1.png') }}" alt="Jaced Logo" style="width: 35px; height: auto;">
            <span class="text-dark fs-4" style="font-weight: 800; letter-spacing: -1.5px; font-family: 'Lexend', sans-serif;">
                Jaced Furniture
            </span>
        </a>
    </div>
    
    <!-- Navigation Menu -->
    <ul class="nav flex-column mb-auto">
        <li class="nav-item mb-2">
            <a href="#" class="nav-link text-jaced-muted d-flex align-items-center py-2 px-0">
                <i class="bi bi-grid me-3 fs-5"></i> Overview
            </a>
        </li> --}}
        <style>
    /* ── SIDEBAR ── */
    .sidebar {
        width: 220px;
        min-width: 220px;
        background-color: #1a1a18;
        display: flex;
        flex-direction: column;
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
        flex-shrink: 0;
    }
    .sidebar::-webkit-scrollbar { width: 3px; }
    .sidebar::-webkit-scrollbar-thumb { background: #3a3a36; border-radius: 4px; }

    .sidebar .brand { padding: 20px 18px 16px; border-bottom: 1px solid #2e2e2b; }
    .sidebar .brand-name { font-size: 13px; font-weight: 700; color: #f5f2ee; letter-spacing: 0.05em; text-transform: uppercase; }
    .sidebar .brand-sub  { font-size: 10px; color: #5a5a56; letter-spacing: 0.1em; text-transform: uppercase; margin-top: 3px; }

    .sidebar-nav { flex: 1; padding: 12px 8px; }

    .nav-section-label {
        font-size: 10px; font-weight: 700;
        letter-spacing: 0.12em; text-transform: uppercase;
        color: #3a3a36; padding: 10px 12px 5px;
    }

    .sidebar .nav-link {
        display: flex; align-items: center; gap: 10px;
        padding: 9px 12px; border-radius: 6px;
        color: #a8a49e; font-size: 13px; font-weight: 500;
        text-decoration: none;
        transition: background 0.15s, color 0.15s;
        position: relative; margin-bottom: 2px;
    }
    .sidebar .nav-link i { font-size: 15px; opacity: 0.7; flex-shrink: 0; }
    .sidebar .nav-link:hover { background-color: #2a2a27; color: #d4d0ca; }
    .sidebar .nav-link:hover i { opacity: 1; }
    .sidebar .nav-link.active { background-color: #2f2d28; color: #f5f2ee; }
    .sidebar .nav-link.active i { opacity: 1; }
    .sidebar .nav-link.active::before {
        content: ''; position: absolute; left: 0; top: 7px; bottom: 7px;
        width: 3px; background: #c4a882; border-radius: 0 3px 3px 0;
    }
    .nav-badge {
        margin-left: auto; font-size: 10px; font-weight: 700;
        background: #3a3835; color: #a8a49e; padding: 2px 7px; border-radius: 10px;
    }

    .sidebar-footer { padding: 12px 8px; border-top: 1px solid #2e2e2b; }

    .btn-new-order {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        width: 100%; padding: 10px;
        background: #1e1c18; color: #f5f2ee;
        border: none; border-radius: 6px;
        font-size: 13px; font-weight: 600;
        text-decoration: none; cursor: pointer; transition: background 0.15s;
    }
    .btn-new-order:hover { background: #2e2b26; color: #f5f2ee; }

    .sidebar-footer-links { display: flex; justify-content: space-between; padding-top: 10px; }

    .sidebar-footer-link {
        display: flex; align-items: center; gap: 5px;
        font-size: 12px; color: #4a4a46;
        background: none; border: none; cursor: pointer;
        text-decoration: none; transition: color 0.15s; padding: 0;
    }
    .sidebar-footer-link:hover { color: #7a7a76; }
</style>

<aside class="sidebar">

    {{-- Brand --}}
    <div class="brand">
        <div class="brand-name">Jaced Furniture</div>
        <div class="brand-sub">Premium Craftsmanship</div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">

        <div class="nav-section-label">Main</div>

        <a href="{{ route('dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Overview
        </a>

        <a href="{{ route('orders.index1') }}"
           class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
            <i class="bi bi-bag-check"></i> Orders
            @isset($orderCount)
                <span class="nav-badge">{{ $orderCount }}</span>
            @endisset
        </a>

        <a href="{{ route('inventory.index') }}"
           class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Inventory
        </a>

        <a href="#"
           class="nav-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> Analytics
        </a>

        <a href="#"
           class="nav-link {{ request()->routeIs('logistics.*') ? 'active' : '' }}">
            <i class="bi bi-truck"></i> Logistics
        </a>

    </nav>

    {{-- Footer --}}
    <div class="sidebar-footer">
    
        <div class="sidebar-footer-links">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-footer-link">
                    <i class="bi bi-box-arrow-right"></i> Sign Out
                </button>
            </form>
        </div>
    </div>

</aside>
{{-- 
        <!-- Menu Orders -->
        <li class="nav-item mb-2">
            <a href="{{ route('orders.index1') }}" 
               class="nav-link d-flex align-items-center py-2 px-0 {{ request()->routeIs('orders.index1') ? 'fw-bold text-jaced-dark' : 'text-jaced-muted' }}" 
               style="{{ request()->routeIs('orders.index1') ? 'border-left: 4px solid var(--jaced-brown-dark); padding-left: 12px !important; margin-left: -24px;' : '' }}">
                <i class="bi bi-cart me-3 fs-5"></i> Orders
            </a>
        </li>

        <!-- Menu Inventory -->
        <li class="nav-item mb-2">
            <a href="{{ route('inventory.index') }}" 
               class="nav-link d-flex align-items-center py-2 px-0 {{ request()->routeIs('inventory.*') ? 'fw-bold text-jaced-dark' : 'text-jaced-muted' }}"
               style="{{ request()->routeIs('inventory.*') ? 'border-left: 4px solid var(--jaced-brown-dark); padding-left: 12px !important; margin-left: -24px;' : '' }}">
                <i class="bi bi-box me-3 fs-5"></i> Inventory
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="#" class="nav-link text-jaced-muted d-flex align-items-center py-2 px-0">
                <i class="bi bi-graph-up me-3 fs-5"></i> Analytics
            </a>
        </li>
    </ul>

    <!-- Footer Links -->
    <div class="mt-auto pt-4 border-top divider-jaced">
        <a href="#" class="text-jaced-muted text-decoration-none small d-flex align-items-center mb-3">
            <i class="bi bi-question-circle me-3 fs-5"></i> Support
        </a>
        <a href="#" class="text-jaced-muted text-decoration-none small d-flex align-items-center">
            <i class="bi bi-box-arrow-right me-3 fs-5"></i> Sign Out
        </a>
    </div> --}}
</div>