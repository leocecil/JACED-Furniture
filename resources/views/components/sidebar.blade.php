<div class="sidebar d-flex flex-column p-4">
    <!-- Header Logo & Brand -->
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
        </li>

        <!-- Menu Orders -->
        <li class="nav-item mb-2">
            <a href="{{ route('orders.index') }}" 
               class="nav-link d-flex align-items-center py-2 px-0 {{ request()->routeIs('orders.*') ? 'fw-bold text-jaced-dark' : 'text-jaced-muted' }}" 
               style="{{ request()->routeIs('orders.*') ? 'border-left: 4px solid var(--jaced-brown-dark); padding-left: 12px !important; margin-left: -24px;' : '' }}">
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
    </div>
</div>