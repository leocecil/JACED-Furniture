<style>
    /* ── TOPBAR ── */
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
    }
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

    .topbar-icon-btn {
        width: 36px; height: 36px; border-radius: 6px; border: none; background: transparent;
        display: flex; align-items: center; justify-content: center;
        color: #6b6860; font-size: 18px; cursor: pointer;
        transition: background 0.15s, color 0.15s; position: relative;
    }
    .topbar-icon-btn:hover { background: #f0eeeb; color: #1a1a18; }

    .notif-dot {
        position: absolute; top: 7px; right: 7px;
        width: 7px; height: 7px;
        background: #e05c3a; border-radius: 50%; border: 1.5px solid #fff;
    }

    .topbar-divider { width: 1px; height: 22px; background: #e2ddd8; margin: 0 6px; }

    .topbar-user {
        display: flex; align-items: center; gap: 10px;
        cursor: pointer; padding: 5px 8px; border-radius: 8px;
        transition: background 0.15s;
    }
    .topbar-user:hover { background: #f0eeeb; }
    .topbar-user-name { font-size: 13px; font-weight: 600; color: #1a1a18; line-height: 1.2; }
    .topbar-user-role { font-size: 11px; color: #9c9890; }

    .topbar-avatar {
        width: 34px; height: 34px; border-radius: 50%;
        background: #2a2520;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 700; color: #c4a882;
        flex-shrink: 0; overflow: hidden;
    }
    .topbar-avatar img { width: 100%; height: 100%; object-fit: cover; }
</style>

<header class="topbar">

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

        {{-- User --}}
        <div class="topbar-user">
            <div class="text-end">
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
        </div>

    </div>
</header>