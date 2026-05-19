<style>
    /* ══════════════════════════════
       TOPBAR
    ══════════════════════════════ */
    .topbar {
        height: 60px;
        min-height: 60px;
        background: #ffffff;
        border-bottom: 1px solid #e2ddd8;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 24px;
        flex-shrink: 0;
        position: relative;
        z-index: 100;
    }

    /* Hamburger — hanya muncul di mobile */
    .btn-hamburger {
        display: none;
        width: 36px; height: 36px;
        border: none; background: transparent;
        border-radius: 6px; cursor: pointer;
        align-items: center; justify-content: center;
        color: #1a1a18; font-size: 20px;
        flex-shrink: 0;
        transition: background 0.15s;
    }
    .btn-hamburger:hover { background: #f0eeeb; }

    @media (max-width: 768px) {
        .btn-hamburger { display: flex; }
        .topbar { padding: 0 16px; gap: 8px; }
        .topbar-search { max-width: none !important; flex: 1; }
    }

    /* Search */
    .topbar-search { flex: 1; max-width: 420px; }
    .topbar-search .input-group-text {
        background: #f0eeeb; border: 1px solid #e2ddd8;
        border-right: none; color: #9c9890;
    }
    .topbar-search .form-control {
        background: #f0eeeb; border: 1px solid #e2ddd8;
        border-left: none; font-size: 13px; box-shadow: none;
    }
    .topbar-search .form-control:focus {
        background: #f0eeeb; box-shadow: none; border-color: #e2ddd8;
    }

    /* Icon buttons */
    .topbar-icon-btn {
        width: 36px; height: 36px; border-radius: 6px; border: none; background: transparent;
        display: flex; align-items: center; justify-content: center;
        color: #6b6860; font-size: 18px; cursor: pointer;
        transition: background 0.15s, color 0.15s; position: relative;
        text-decoration: none;
    }
    .topbar-icon-btn:hover { background: #f0eeeb; color: #1a1a18; }

    .notif-dot {
        position: absolute; top: 7px; right: 7px;
        width: 7px; height: 7px;
        background: #e05c3a; border-radius: 50%; border: 1.5px solid #fff;
    }

    .topbar-divider { width: 1px; height: 22px; background: #e2ddd8; margin: 0 4px; flex-shrink: 0; }

    /* ══════════════════════════════
       PROFILE DROPDOWN
    ══════════════════════════════ */
    .profile-dropdown-wrap { position: relative; }

    .topbar-user {
        display: flex; align-items: center; gap: 10px;
        cursor: pointer; padding: 5px 8px; border-radius: 8px;
        transition: background 0.15s;
        border: none; background: transparent;
    }
    .topbar-user:hover { background: #f0eeeb; }

    .topbar-user-name { font-size: 13px; font-weight: 600; color: #1a1a18; line-height: 1.2; white-space: nowrap; }
    .topbar-user-role { font-size: 11px; color: #9c9890; white-space: nowrap; }

    .topbar-avatar {
        width: 34px; height: 34px; border-radius: 50%;
        background: #2a2520;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 700; color: #c4a882;
        flex-shrink: 0; overflow: hidden;
    }
    .topbar-avatar img { width: 100%; height: 100%; object-fit: cover; }

    /* Dropdown panel */
    .profile-dropdown-menu {
        display: none;
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        min-width: 200px;
        background: #ffffff;
        border: 1px solid #e2ddd8;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.10), 0 2px 6px rgba(0,0,0,0.06);
        z-index: 500;
        overflow: hidden;
        animation: dropIn 0.15s ease;
    }
    .profile-dropdown-menu.show { display: block; }

    @keyframes dropIn {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Profile header di dalam dropdown */
    .profile-dropdown-header {
        display: flex; align-items: center; gap: 10px;
        padding: 14px 16px;
        border-bottom: 1px solid #f0eeeb;
    }
    .profile-dropdown-header .avatar-lg {
        width: 40px; height: 40px; border-radius: 50%;
        background: #2a2520; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; font-weight: 700; color: #c4a882;
        overflow: hidden;
    }
    .profile-dropdown-header .avatar-lg img { width: 100%; height: 100%; object-fit: cover; }
    .profile-dropdown-name { font-size: 13px; font-weight: 700; color: #1a1a18; }
    .profile-dropdown-role { font-size: 11px; color: #9c9890; }

    /* Menu items */
    .profile-dropdown-item {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 16px;
        font-size: 13px; color: #3a3a36;
        text-decoration: none; cursor: pointer;
        transition: background 0.12s;
        border: none; background: transparent; width: 100%; text-align: left;
    }
    .profile-dropdown-item:hover { background: #f7f5f2; }
    .profile-dropdown-item i { font-size: 15px; color: #9c9890; width: 18px; }

    .profile-dropdown-divider { height: 1px; background: #f0eeeb; margin: 4px 0; }

    /* Sign Out — warna merah */
    .profile-dropdown-item.signout { color: #c0392b; }
    .profile-dropdown-item.signout i { color: #c0392b; }
    .profile-dropdown-item.signout:hover { background: #fdecea; }

    /* Sembunyikan nama user di mobile biar tidak sesak */
    @media (max-width: 480px) {
        .topbar-user-info { display: none; }
    }
</style>

<header class="topbar">

    {{-- Hamburger — hanya tampil di mobile --}}
    <button class="btn-hamburger" onclick="openSidebar()" title="Open menu">
        <i class="bi bi-list"></i>
    </button>

    {{-- Search --}}
    <div class="topbar-search">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control"
                   placeholder="Search orders, clients, or serial numbers...">
        </div>
    </div>

    <div class="ms-auto d-flex align-items-center gap-1">

        {{-- Notifications --}}
        <button class="topbar-icon-btn" title="Notifications">
            <i class="bi bi-bell"></i>
            @isset($unreadNotifications)
                @if($unreadNotifications > 0)
                    <span class="notif-dot"></span>
                @endif
            @endisset
        </button>

        {{-- Settings --}}
        <a href="#" class="topbar-icon-btn" title="Settings">
            <i class="bi bi-gear"></i>
        </a>

        <div class="topbar-divider"></div>

        {{-- ══ PROFILE DROPDOWN ══ --}}
        <div class="profile-dropdown-wrap">

            {{-- Tombol trigger --}}
            <button class="topbar-user" id="profileToggleBtn" onclick="toggleProfileDropdown()">
                <div class="topbar-user-info text-end">
                    <div class="topbar-user-name">{{ auth()->user()->name ?? 'Julian Thorne' }}</div>
                    <div class="topbar-user-role">{{ auth()->user()->role ?? 'Creative Director' }}</div>
                </div>
                <div class="topbar-avatar">
                    @if(auth()->user()?->avatar)
                        <img src="{{ auth()->user()->avatar }}" alt="">
                    @else
                        {{ strtoupper(substr(auth()->user()->name ?? 'JT', 0, 2)) }}
                    @endif
                </div>
            </button>

            {{-- Dropdown panel --}}
            <div class="profile-dropdown-menu" id="profileDropdown">

                {{-- Header profil --}}
                <div class="profile-dropdown-header">
                    <div class="avatar-lg">
                        @if(auth()->user()?->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="">
                        @else
                            {{ strtoupper(substr(auth()->user()->name ?? 'JT', 0, 2)) }}
                        @endif
                    </div>
                    <div>
                        <div class="profile-dropdown-name">{{ auth()->user()->name ?? 'Julian Thorne' }}</div>
                        <div class="profile-dropdown-role">{{ auth()->user()->email ?? 'creative@studio.com' }}</div>
                    </div>
                </div>

                {{-- Menu items --}}
                <div style="padding: 6px 0;">
                    <a href="#" class="profile-dropdown-item">
                        <i class="bi bi-person"></i> My Profile
                    </a>
                </div>

                <div class="profile-dropdown-divider"></div>

                {{-- Sign Out --}}
                <div style="padding: 6px 0 8px;">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="profile-dropdown-item signout">
                            <i class="bi bi-box-arrow-right"></i> Sign Out
                        </button>
                    </form>
                </div>

            </div>
        </div>
        {{-- END PROFILE DROPDOWN --}}

    </div>
</header>

<script>
    // ── Profile dropdown toggle ──
    function toggleProfileDropdown() {
        const menu = document.getElementById('profileDropdown');
        menu.classList.toggle('show');
    }

    // Tutup dropdown saat klik di luar
    document.addEventListener('click', function (e) {
        const wrap = document.querySelector('.profile-dropdown-wrap');
        const menu = document.getElementById('profileDropdown');
        if (menu && wrap && !wrap.contains(e.target)) {
            menu.classList.remove('show');
        }
    });
</script>