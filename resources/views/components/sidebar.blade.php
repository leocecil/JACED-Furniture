<style>
    /* ══════════════════════════════
        SIDEBAR GLOBAL STYLING
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
        transition: width 0.3s ease, min-width 0.3s ease, transform 0.3s ease;
        z-index: 1040; /* Ditinggikan agar di atas struktur konten biasa */
    }
    .sidebar::-webkit-scrollbar { width: 3px; }
    .sidebar::-webkit-scrollbar-thumb { background: #3a3a36; border-radius: 4px; }

    .sidebar .brand { 
        padding: 20px 18px 16px; 
        border-bottom: 1px solid #2e2e2b; 
        transition: padding 0.3s ease;
    }
    .sidebar .brand-name { font-size: 13px; font-weight: 700; color: #f5f2ee; letter-spacing: 0.05em; text-transform: uppercase; white-space: nowrap; }
    .sidebar .brand-sub  { font-size: 10px; color: #5a5a56; letter-spacing: 0.1em; text-transform: uppercase; margin-top: 3px; white-space: nowrap; }

    .sidebar-nav { flex: 1; padding: 12px 8px; transition: padding 0.3s ease; }

    .nav-section-label {
        font-size: 10px; font-weight: 700;
        letter-spacing: 0.12em; text-transform: uppercase;
        color: #3a3a36; padding: 10px 12px 5px;
        white-space: nowrap;
    }

    .sidebar .nav-link {
        display: flex; align-items: center; gap: 10px;
        padding: 9px 12px; border-radius: 6px;
        color: #a8a49e; font-size: 13px; font-weight: 500;
        text-decoration: none;
        transition: background 0.15s, color 0.15s, padding 0.3s ease;
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

    .menu-text {
        transition: opacity 0.2s ease;
        opacity: 1;
    }

    /* ── TOGGLE INTERACTIVE STYLING ── */
    .sidebar-toggle-container {
        position: relative;
    }

    .btn-sidebar-toggle {
        background: #2a2a27;
        color: #a8a49e;
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-sidebar-toggle:hover {
        background: #3a3a36;
        color: #f5f2ee;
    }

    /* Kustomisasi Balon Tooltip Asli Bootstrap agar Sesuai Tema (image_b18482.png) */
    .tooltip .tooltip-inner {
        background-color: #e0e0e0 !important;
        color: #1a1a18 !important;
        font-weight: 500 !important;
        font-size: 12px !important;
        padding: 6px 14px !important;
        border-radius: 50px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .tooltip .tooltip-arrow::before {
        border-right-color: #e0e0e0 !important;
    }

    /* ── LAYAR DESKTOP: KONDISI COLLAPSED (MENGECIL) ── */
    @media (min-width: 769px) {
        body.sidebar-collapsed .sidebar {
            width: 68px;
            min-width: 68px;
        }

        body.sidebar-collapsed .sidebar .brand {
            padding: 20px 0;
            text-align: center;
            justify-content: center !important;
        }

        body.sidebar-collapsed .sidebar .brand-core,
        body.sidebar-collapsed .sidebar .menu-text,
        body.sidebar-collapsed .sidebar .nav-section-label,
        body.sidebar-collapsed .sidebar .nav-badge {
            display: none !important;
        }

        body.sidebar-collapsed .sidebar .sidebar-nav {
            padding: 12px 14px;
        }

        body.sidebar-collapsed .sidebar .nav-link {
            padding: 10px 0;
            justify-content: center;
            gap: 0;
        }

        body.sidebar-collapsed .sidebar .nav-link.active::before {
            top: 10px;
            bottom: 10px;
        }
    }

    /* ── LAYAR MOBILE (max-width: 768px) ── */
    @media (max-width: 768px) {
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            transform: translateX(-100%);
        }
        .sidebar.open {
            transform: translateX(0);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.3);
        }
    }
</style>

<aside class="sidebar" id="appSidebar">

    {{-- Brand --}}
    <div class="brand d-flex align-items-center justify-content-between">
        <div class="brand-core">
            <div class="brand-name">Jaced Furniture</div>
            <div class="brand-sub">Premium Craftsmanship</div>
        </div>
        
        <div class="sidebar-toggle-container d-none d-md-block">
            <button type="button" 
                    class="btn-sidebar-toggle" 
                    onclick="toggleSidebarDesktop()" 
                    id="desktopToggleBtn"
                    data-bs-toggle="tooltip" 
                    data-bs-placement="right" 
                    data-bs-container="body" 
                    title="Close sidebar">
                <i class="bi bi-layout-sidebar"></i>
            </button>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">


        <a href="{{ route('admin.dashboard') }}"
            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
            onclick="if(window.innerWidth<=768) closeSidebar()">
            <i class="bi bi-grid-1x2"></i> <span class="menu-text">Overview</span>
        </a>

        <a href="{{ route('orders.index') }}"
            class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}"
            onclick="if(window.innerWidth<=768) closeSidebar()">
            <i class="bi bi-bag-check"></i> <span class="menu-text">Orders</span>
            @isset($orderCount)
                <span class="nav-badge">{{ $orderCount }}</span>
            @endisset
        </a>

        <a href="{{ route('inventory.index') }}"
            class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}"
            onclick="if(window.innerWidth<=768) closeSidebar()">
            <i class="bi bi-box-seam"></i> <span class="menu-text">Inventory</span>
        </a>

        <a href="{{ route('analytics.customers') }}"
           class="nav-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}"
           onclick="if(window.innerWidth<=768) closeSidebar()">
            <i class="bi bi-bar-chart-line"></i> <span class="menu-text">Customer Analytics</span>
        </a>

        <a href="#"
            class="nav-link {{ request()->routeIs('logistics.*') ? 'active' : '' }}"
            onclick="if(window.innerWidth<=768) closeSidebar()">
            <i class="bi bi-ticket-perforated"></i> <span class="menu-text">Voucher</span>
        </a>

    </nav>

</aside>

<script>
    // Inisialisasi library tooltip Bootstrap 5 saat halaman dimuat
    document.addEventListener("DOMContentLoaded", function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    function toggleSidebarDesktop() {
        const body = document.body;
        const icon = document.querySelector('#desktopToggleBtn i');
        const toggleBtn = document.getElementById('desktopToggleBtn');
        
        if (!icon || !toggleBtn) return;

        body.classList.toggle('sidebar-collapsed');
        
        // Memanggil instance kontroler tooltip milik elemen ini
        const tooltipInstance = bootstrap.Tooltip.getInstance(toggleBtn);
        
        if (body.classList.contains('sidebar-collapsed')) {
            icon.className = 'bi bi-list';
            
            // Perbarui muatan judul data asli agar di-render ulang oleh Bootstrap
            toggleBtn.setAttribute('data-bs-original-title', 'Open sidebar');
            if (tooltipInstance) {
                tooltipInstance.setContent({ '.tooltip-inner': 'Open sidebar' });
                tooltipInstance.hide(); // Reset trigger visual agar tidak tersangkut
            }
        } else {
            icon.className = 'bi bi-layout-sidebar';
            
            toggleBtn.setAttribute('data-bs-original-title', 'Close sidebar');
            if (tooltipInstance) {
                tooltipInstance.setContent({ '.tooltip-inner': 'Close sidebar' });
                tooltipInstance.hide();
            }
        }
    }
</script>