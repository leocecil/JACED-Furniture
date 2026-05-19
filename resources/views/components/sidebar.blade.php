<style>
    /* ══════════════════════════════
       SIDEBAR
    ══════════════════════════════ */
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
        transition: transform 0.3s ease;
        z-index: 1000;
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

    /* ══════════════════════════════
       MOBILE: sidebar tersembunyi
    ══════════════════════════════ */
    @media (max-width: 768px) {
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            transform: translateX(-100%); /* hidden ke kiri */
        }
        /* Saat openSidebar() dipanggil → muncul */
        .sidebar.open {
            transform: translateX(0);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.3);
        }
    }
</style>

<aside class="sidebar" id="appSidebar">

    {{-- Brand --}}
    <div class="brand">
        <div class="brand-name">Jaced Furniture</div>
        <div class="brand-sub">Premium Craftsmanship</div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">

        <div class="nav-section-label">Main</div>

        <a href="{{ route('dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
           onclick="if(window.innerWidth<=768) closeSidebar()">
            <i class="bi bi-grid-1x2"></i> Overview
        </a>

        <a href="{{ route('orders.index1') }}"
           class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}"
           onclick="if(window.innerWidth<=768) closeSidebar()">
            <i class="bi bi-bag-check"></i> Orders
            @isset($orderCount)
                <span class="nav-badge">{{ $orderCount }}</span>
            @endisset
        </a>

        <a href="{{ route('inventory.index') }}"
           class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}"
           onclick="if(window.innerWidth<=768) closeSidebar()">
            <i class="bi bi-box-seam"></i> Inventory
        </a>

        <a href="#"
           class="nav-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}"
           onclick="if(window.innerWidth<=768) closeSidebar()">
            <i class="bi bi-bar-chart-line"></i> Analytics
        </a>

        <a href="#"
           class="nav-link {{ request()->routeIs('logistics.*') ? 'active' : '' }}"
           onclick="if(window.innerWidth<=768) closeSidebar()">
            <i class="bi bi-truck"></i> Logistics
        </a>

    </nav>

</aside>