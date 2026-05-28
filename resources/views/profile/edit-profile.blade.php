@extends('base.base')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    * { box-sizing: border-box; }
    html, body { overflow-x: hidden; }

    .edit-profile-page {
        background-color: var(--jaced-caramel-bg) !important;
        min-height: 100vh;
        padding: 40px 16px 80px;
    }

    .profile-grid-container {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 32px;
        max-width: 1050px;
        margin: 0 auto;
        animation: profileIn 0.4s cubic-bezier(0.25, 1, 0.5, 1) both;
    }

    @keyframes profileIn {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .jaced-card {
        background: white;
        padding: 24px;
        border-radius: 16px;
        border: 1px solid var(--jaced-input);
        box-shadow: 0 4px 20px rgba(42, 35, 24, 0.01);
    }

    .profile-sidebar {
        height: fit-content;
        position: sticky;
        top: 20px;
    }

    .avatar-upload-container {
        position: relative;
        width: 110px;
        height: 110px;
        margin: 0 auto 16px;
    }

    .avatar-preview-wrapper {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid white;
        box-shadow: 0 6px 16px rgba(0,0,0,0.06);
        background: #fdfcfb;
    }

    .avatar-preview-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-edit-trigger {
        position: absolute;
        bottom: 0; right: 0;
        width: 32px; height: 32px;
        background: var(--jaced-brown-dark);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: all 0.2s;
    }

    .avatar-edit-trigger:hover {
        background: var(--jaced-caramel);
        transform: scale(1.05);
    }

    .mini-tier-badge {
        background: linear-gradient(135deg, var(--jaced-caramel), #6E4524);
        color: white;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.5px;
        display: inline-block;
        text-transform: uppercase;
        box-shadow: 0 2px 6px rgba(110,69,36,0.15);
    }

    .sidebar-menu-list {
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin-top: 20px;
        text-align: left;
    }

    .sidebar-menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        color: var(--jaced-brown-dark);
        text-decoration: none;
        font-size: 0.85rem;
        border-radius: 10px;
        font-weight: 500;
        transition: background 0.2s, color 0.2s;
        background: none;
        border: none;
        text-align: left;
        width: 100%;
        cursor: pointer;
    }

    .sidebar-menu-item svg {
        width: 16px; height: 16px;
        stroke: var(--jaced-brown);
        fill: none;
        stroke-width: 2;
        flex-shrink: 0;
    }

    .sidebar-menu-item:hover {
        background-color: var(--jaced-caramel-bg);
        color: var(--jaced-brown);
    }

    .sidebar-menu-item.danger {
        color: #C0392B;
        margin-top: 12px;
        border-top: 1px solid var(--jaced-input);
        border-radius: 0 0 10px 10px;
    }

    .sidebar-menu-item.danger svg { stroke: #C0392B; }
    .sidebar-menu-item.danger:hover { background-color: #FEE9E7; }

    .sidebar-menu-item.active-nav {
        background-color: var(--jaced-caramel-bg);
        color: var(--jaced-brown);
        font-weight: 600;
    }

    .sidebar-menu-item.active-nav svg { stroke: var(--jaced-brown); }

    .section-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--jaced-muted);
        margin: 0 0 16px;
        padding-bottom: 6px;
        border-bottom: 1px solid var(--jaced-input);
    }

    .form-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--jaced-brown-dark);
        margin-bottom: 6px;
    }

    .jaced-input {
        width: 100%;
        padding: 11px 16px;
        border-radius: 10px;
        border: 1px solid var(--jaced-input);
        font-size: 0.9rem;
        margin-bottom: 16px;
        transition: 0.2s;
        background: #fcfbfa;
    }

    .jaced-input:focus {
        outline: none;
        border-color: var(--jaced-caramel);
    }

    .btn-save {
        background-color: var(--jaced-brown-dark);
        color: white;
        border: none;
        width: 100%;
        padding: 14px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: background .2s;
    }

    .btn-save:hover { background-color: #333; }

    .btn-manage-address {
        background: var(--jaced-sage);
        color: white !important;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: background .2s;
        white-space: nowrap;
    }

    .btn-manage-address:hover { background: #4a5d4b; }

    .change-pw-box {
        background-color: #faf6f0;
        border: 1px dashed var(--jaced-input);
        transition: all 0.3s ease;
    }

    .password-fields-collapsed {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .btn-trigger-password {
        background: white;
        border: 1px solid var(--jaced-brown-dark);
        color: var(--jaced-brown-dark);
        padding: 8px 16px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .btn-trigger-password:hover {
        background: var(--jaced-brown-dark);
        color: white;
    }

    .content-panel { display: none; }
    .content-panel.active { display: block; }

    /* ── MOBILE TAB BAR ── */
    .mobile-tab-bar {
        display: none;
    }

    @media (max-width: 768px) {
        .back-wrapper {
            padding: 16px 12px 6px !important;
        }
        .mob-avatar {
            width: 38px; height: 38px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid var(--jaced-input);
            flex-shrink: 0;
        }
        .mob-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .mob-name { font-size: 13px; font-weight: 600; color: var(--jaced-brown-dark); line-height: 1.2; }
        .mob-badge { font-size: 9px; font-weight: 700; color: var(--jaced-caramel); text-transform: uppercase; letter-spacing: 0.5px; }
        .edit-profile-page { padding: 0 0 80px; }

        .profile-grid-container {
            grid-template-columns: 1fr;
            gap: 0;
        }

        /* Sembunyikan sidebar desktop di mobile */
        .col-left { display: none; }

        .col-right-content { padding: 16px 12px; }

        /* Tab bar muncul di mobile */
        .mobile-tab-bar {
            display: flex;
            justify-content: center;
            background: transparent; /* transparan! */
            border-bottom: none; /* hapus border bawah */
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 4px 0;
        }

        .mobile-tab-bar::-webkit-scrollbar { display: none; }

        .mobile-tab-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 10px 20px;
            font-size: 11px;
            font-weight: 500;
            color: var(--jaced-muted);
            cursor: pointer;
            border: none;
            background: none;
            white-space: nowrap;
            flex-shrink: 0;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
        }

        .mobile-tab-item svg {
            width: 20px; height: 20px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
        }

        .mobile-tab-item.active-tab {
            color: var(--jaced-caramel);
            border-bottom: 2px solid var(--jaced-caramel);
            font-weight: 600;
        }

        /* User info compact di atas tab bar */
        /* .mobile-user-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            background: white;
            border-bottom: 0.5px solid var(--jaced-input);
        } */

        .mobile-user-bar .mob-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid var(--jaced-input);
            flex-shrink: 0;
        }

        .mobile-user-bar .mob-avatar img {
            width: 100%; height: 100%;
            object-fit: cover;
        }

        .mobile-user-bar .mob-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--jaced-caramel);
            line-height: 1.2;
        }

        .mobile-user-bar .mob-badge {
            font-size: 9px;
            font-weight: 700;
            color: var(--jaced-caramel);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Address card mobile */
        .jaced-card.mb-4.d-flex {
            flex-direction: column;
            gap: 12px;
            align-items: flex-start !important;
        }

        .btn-manage-address { width: 100%; text-align: center; }
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--jaced-muted);
        text-decoration: none;
        margin-bottom: 20px;
        font-size: 0.9rem;
    }
</style>
@endpush

@section('content')
<div class="edit-profile-page font-serif-jaced">

    {{-- BACK --}}
    <div class="back-wrapper" style="max-width: 1050px; margin: 0 auto; padding: 16px 12px 0;">
        <a href="{{ route('home') }}" class="back-link">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            <span>Back</span>
        </a>
    </div>

    {{-- USER BAR: card dengan avatar besar --}}
    <div class="d-md-none" style="padding: 0 12px 8px;">
        <div class="jaced-card" style="padding: 20px 16px; text-align: center;">
            
            {{-- Avatar dengan tombol kamera --}}
            <div style="position: relative; width: 80px; height: 80px; margin: 0 auto 12px;">
                <div style="width: 80px; height: 80px; border-radius: 50%; overflow: hidden; border: 3px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                    <img src="{{ $user->avatar ? (str_starts_with($user->avatar, 'http') ? $user->avatar : asset($user->avatar)) : asset('image/avatars/default_avatar.png') }}"
                        style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <label for="avatarFileInput" style="position: absolute; bottom: 0; right: 0; width: 26px; height: 26px; background: var(--jaced-brown-dark); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                        <circle cx="12" cy="13" r="4"/>
                    </svg>
                </label>
            </div>

            <div style="font-size: 14px; font-weight: 600; color: var(--jaced-caramel); margin-bottom: 2px;">{{ $user->name }}</div>
            <div style="font-size: 11px; color: var(--jaced-muted); margin-bottom: 10px;">Member since {{ $user->created_at ? $user->created_at->format('M Y') : 'Oct 2022' }}</div>
            <div class="mini-tier-badge">{{ $stage ?? 'Bronze' }} Member</div>

        </div>
    </div>

    <div class="mobile-tab-bar d-md-none" style="margin-bottom: 16px; margin-top: 8px;">
        <button class="mobile-tab-item active-tab" data-panel="main" onclick="showPanel('main', this)">
            <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Profile
        </button>
        <button class="mobile-tab-item" data-panel="tos" onclick="showPanel('tos', this)">
            <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><circle cx="3" cy="6" r="1"/><circle cx="3" cy="12" r="1"/><circle cx="3" cy="18" r="1"/></svg>
            Terms
        </button>
        <button class="mobile-tab-item" data-panel="privacy" onclick="showPanel('privacy', this)">
            <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Privacy
        </button>
        <button class="mobile-tab-item" data-panel="help" onclick="showPanel('help', this)">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            Help
        </button>
    </div>

    <div class="profile-grid-container">

        {{-- KOLOM KIRI: SIDEBAR (desktop only) --}}
        <aside class="col-left">
            <div class="jaced-card profile-sidebar text-center">
                <div class="avatar-upload-container">
                    <div class="avatar-preview-wrapper">
                        <img id="avatarPreview"
                            src="{{ $user->avatar ? (str_starts_with($user->avatar, 'http') ? $user->avatar : asset($user->avatar)) : asset('image/avatars/default_avatar.png') }}?v={{ time() }}"
                            alt="Profile Picture">
                    </div>
                    <label for="avatarFileInput" class="avatar-edit-trigger">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                            <circle cx="12" cy="13" r="4"/>
                        </svg>
                    </label>
                </div>

                @if ($user->avatar && !str_contains($user->avatar, 'default_avatar'))
                    <button type="button" onclick="confirmDeleteAvatar()" style="background:none;border:1px solid #ffcccc;color:#c0392b;font-size:10px;cursor:pointer;padding:4px 10px;border-radius:20px;margin-bottom:14px;transition:all 0.2s;" onmouseover="this.style.background='#fff0f0'" onmouseout="this.style.background='none'">
                        Remove Photo
                    </button>
                @endif

                <h5 class="fw-bold mb-1 text-jaced-dark" style="font-size:14px;">{{ $user->name }}</h5>
                <p class="text-muted small mb-3" style="font-size:11px;">Member since {{ $user->created_at ? $user->created_at->format('M Y') : 'Oct 2022' }}</p>
                <div class="mini-tier-badge shadow-sm mb-3">{{ $stage ?? 'Bronze' }} Member</div>
                <hr style="border-color:var(--jaced-input);margin:12px 0;">

                <nav class="sidebar-menu-list">
                    <button type="button" class="sidebar-menu-item active-nav" data-panel="main" onclick="showPanel('main', null)">
                        <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span>My Profile</span>
                    </button>
                    <button type="button" class="sidebar-menu-item" data-panel="tos" onclick="showPanel('tos', null)">
                        <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><circle cx="3" cy="6" r="1"/><circle cx="3" cy="12" r="1"/><circle cx="3" cy="18" r="1"/></svg>
                        <span>Terms of Service</span>
                    </button>
                    <button type="button" class="sidebar-menu-item" data-panel="privacy" onclick="showPanel('privacy', null)">
                        <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>Privacy Policy</span>
                    </button>
                    <button type="button" class="sidebar-menu-item" data-panel="help" onclick="showPanel('help', null)">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        <span>Help Center</span>
                    </button>
                    <a href="#" class="sidebar-menu-item danger" onclick="return confirm('Are you sure you want to delete your account?')">
                        <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                        <span>Delete Account</span>
                    </a>
                </nav>
            </div>
        </aside>

        {{-- KOLOM KANAN --}}
        <main class="col-right-content">

            @if (session('success'))
                <div class="alert alert-success mb-3" style="font-size:13px;border-radius:10px;background-color:#edf7ed;color:#1e4620;border:none;">
                    ✨ {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger mb-3" style="font-size:13px;border-radius:10px;background-color:#fdeded;color:#5f2120;border:none;">
                    ⚠️ {{ $errors->first() }}
                </div>
            @endif

            {{-- PANEL: My Profile --}}
            <div id="panel-main" class="main-form-panel">
                <form action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="avatar" id="avatarFileInput" accept="image/*" style="display:none;" onchange="previewImage(this)">

                    <div class="section-title">Personal Information</div>
                    <div class="jaced-card mb-4">
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="jaced-input" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="jaced-input" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="jaced-input" value="{{ old('phone', $user->phone_number) }}" placeholder="0812...">
                            </div>
                        </div>
                    </div>

                    <div class="section-title">Shipping Address</div>
                    <div class="jaced-card mb-4 d-flex align-items-center justify-content-between" style="padding:18px 24px;">
                        <div>
                            <p class="mb-0 fw-semibold" style="font-size:13px;color:var(--jaced-brown-dark);">Manage Saved Addresses</p>
                            <p class="mb-0 text-muted" style="font-size:11px;">Add or change destination delivery point details.</p>
                        </div>
                        <a href="{{ route('profile.addresses') }}" class="btn-manage-address shadow-sm">Manage Addresses</a>
                    </div>

                    <div class="section-title">Security &amp; Password</div>
                    <div class="jaced-card change-pw-box mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0 fw-semibold" style="font-size:13px;color:var(--jaced-brown-dark);">Account Password</p>
                                <p class="mb-0 text-muted" style="font-size:11px;">Update password regularly to keep your assets secure.</p>
                            </div>
                            <button type="button" class="btn-trigger-password" id="togglePasswordBtn" onclick="togglePasswordSection()">Change Password</button>
                        </div>
                        <div class="password-fields-collapsed mt-3" id="passwordFieldsContainer">
                            <hr style="border-color:var(--jaced-input);margin-bottom:16px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="password" class="jaced-input" placeholder="Min. 8 characters">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="jaced-input" placeholder="Repeat new password">
                                </div>
                            </div>
                            <p class="mb-0 mt-1 d-flex align-items-center gap-2" style="font-size:11px;color:var(--jaced-muted);">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                <span>Changing password will log you out from your current session.</span>
                            </p>
                        </div>
                    </div>

                    <button type="submit" class="btn-save shadow-sm">Save All Changes</button>
                </form>
            </div>

            {{-- PANEL: Terms of Service --}}
            <div id="panel-tos" class="content-panel">
                <div class="jaced-card">
                    <div class="section-title">Terms of Service</div>
                    <p class="text-muted small mb-4">Last Updated: May 2026</p>
                    <div style="font-size:0.9rem;line-height:1.8;color:var(--jaced-brown-dark);">
                        <p>Welcome to <strong>Jaced</strong>. By accessing our website and using our services, you agree to comply with and be bound by the following terms and conditions.</p>
                        <h6 class="fw-bold mt-4" style="color:var(--jaced-brown);">1. Use of Service</h6>
                        <p>Our platform is designed to provide high-quality artisan products. You agree to use the service only for lawful purposes and in a way that does not infringe the rights of others.</p>
                        <h6 class="fw-bold mt-4" style="color:var(--jaced-brown);">2. Account Responsibility</h6>
                        <p>When you create an account, you are responsible for maintaining the security of your account and password. Jaced cannot and will not be liable for any loss or damage from your failure to comply with this security obligation.</p>
                        <h6 class="fw-bold mt-4" style="color:var(--jaced-brown);">3. Intellectual Property</h6>
                        <p>The content, logo, designs, and artisan works displayed on Jaced are the property of Jaced or its content creators. You may not reproduce or use any of our property without prior written consent.</p>
                        <h6 class="fw-bold mt-4" style="color:var(--jaced-brown);">4. Limitation of Liability</h6>
                        <p>Jaced shall not be liable for any indirect, incidental, or consequential damages resulting from the use or inability to use our services or products.</p>
                        <h6 class="fw-bold mt-4" style="color:var(--jaced-brown);">5. Changes to Terms</h6>
                        <p>We reserve the right to modify these terms at any time. We will notify users of any significant changes by posting the new terms on this page.</p>
                        <div class="mt-4 p-3 rounded text-center" style="background-color:var(--jaced-cream);">
                            <p class="mb-0">Questions? <a href="mailto:support@jaced.com" style="color:var(--jaced-caramel);font-weight:600;">Contact our support team</a></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PANEL: Privacy Policy --}}
            <div id="panel-privacy" class="content-panel">
                <div class="jaced-card">
                    <div class="section-title">Privacy Policy</div>
                    <p class="text-muted small mb-4">Last Updated: May 2026</p>
                    <div style="font-size:0.9rem;line-height:1.8;color:var(--jaced-brown-dark);">
                        <p>At <strong>Jaced</strong>, your privacy is a priority. This Privacy Policy explains how we collect, use, and protect your personal information.</p>
                        <h6 class="fw-bold mt-4" style="color:var(--jaced-brown);">1. Information We Collect</h6>
                        <ul style="padding-left:20px;">
                            <li>Personal details (Name, email address, phone number).</li>
                            <li>Shipping information (Home address, city, postal code).</li>
                            <li>Account credentials (Encrypted passwords).</li>
                        </ul>
                        <h6 class="fw-bold mt-4" style="color:var(--jaced-brown);">2. How We Use Your Information</h6>
                        <ul style="padding-left:20px;">
                            <li>To process and deliver your furniture orders.</li>
                            <li>To manage your Jaced account and Artisan Points.</li>
                            <li>To send transactional emails and occasional marketing updates.</li>
                        </ul>
                        <h6 class="fw-bold mt-4" style="color:var(--jaced-brown);">3. Data Security</h6>
                        <p>We implement industry-standard security measures to protect your data. Passwords are encrypted and payment info is never stored directly.</p>
                        <h6 class="fw-bold mt-4" style="color:var(--jaced-brown);">4. Third-Party Services</h6>
                        <p>We may share your data with trusted partners only to the extent necessary to fulfill your orders.</p>
                        <h6 class="fw-bold mt-4" style="color:var(--jaced-brown);">5. Your Rights</h6>
                        <p>You have the right to access, correct, or delete your personal information through your Profile settings.</p>
                        <div class="mt-4 p-3 rounded text-center" style="background-color:var(--jaced-cream);">
                            <p class="mb-0">Questions? <a href="mailto:privacy@jaced.com" style="color:var(--jaced-caramel);font-weight:600;">privacy@jaced.com</a></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PANEL: Help Center --}}
            <div id="panel-help" class="content-panel">
                <div class="jaced-card">
                    <div class="section-title">Help Center</div>
                    <p class="text-muted small mb-4">Find answers to your questions about Jaced furniture.</p>
                    <div class="faq-item-help mb-3 p-3 rounded" style="border:1px solid var(--jaced-input);cursor:pointer;" onclick="toggleFaq(this)">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong style="font-size:0.9rem;color:var(--jaced-brown-dark);">How do I track my order?</strong>
                            <svg class="faq-chevron" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="transition:transform 0.3s;flex-shrink:0;"><path d="M19 9l-7 7-7-7"/></svg>
                        </div>
                        <p class="faq-body mb-0 mt-2 text-muted" style="font-size:0.85rem;display:none;">You can track your order in the "Transaction" menu. We will provide a tracking number once the artisan finishes your furniture and ships it.</p>
                    </div>
                    <div class="faq-item-help mb-3 p-3 rounded" style="border:1px solid var(--jaced-input);cursor:pointer;" onclick="toggleFaq(this)">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong style="font-size:0.9rem;color:var(--jaced-brown-dark);">Can I request custom furniture?</strong>
                            <svg class="faq-chevron" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="transition:transform 0.3s;flex-shrink:0;"><path d="M19 9l-7 7-7-7"/></svg>
                        </div>
                        <p class="faq-body mb-0 mt-2 text-muted" style="font-size:0.85rem;display:none;">Yes! Most of our artisans accept custom orders. Click "Request Custom" on the artisan's profile page to start a discussion.</p>
                    </div>
                    <div class="faq-item-help mb-3 p-3 rounded" style="border:1px solid var(--jaced-input);cursor:pointer;" onclick="toggleFaq(this)">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong style="font-size:0.9rem;color:var(--jaced-brown-dark);">What are Artisan Points?</strong>
                            <svg class="faq-chevron" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="transition:transform 0.3s;flex-shrink:0;"><path d="M19 9l-7 7-7-7"/></svg>
                        </div>
                        <p class="faq-body mb-0 mt-2 text-muted" style="font-size:0.85rem;display:none;">Artisan Points are rewards for every purchase. Redeem them for exclusive discounts or vouchers in the Reward Center.</p>
                    </div>
                    <div class="mt-4 p-4 rounded text-center text-white" style="background:var(--jaced-brown-dark);">
                        <h6 class="fw-bold mb-1">Still have questions?</h6>
                        <p class="small opacity-75 mb-2">Our team is ready to help you.</p>
                        <a href="https://wa.me/6281226449681" class="btn btn-sm" style="background:var(--jaced-caramel);color:white;font-weight:600;border-radius:8px;">Chat with Support</a>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

@if ($user->avatar && !str_contains($user->avatar, 'default_avatar'))
    <form id="form-delete-avatar" action="{{ route('profile.avatar.delete', $user->id) }}" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showPanel(name, tabEl) {
        // sembunyikan semua panel
        document.getElementById('panel-main').style.display = 'none';
        document.querySelectorAll('.content-panel').forEach(p => p.classList.remove('active'));

        // highlight sidebar desktop
        document.querySelectorAll('.sidebar-menu-item[data-panel]').forEach(el => el.classList.remove('active-nav'));
        const sidebarBtn = document.querySelector(`.sidebar-menu-item[data-panel="${name}"]`);
        if (sidebarBtn) sidebarBtn.classList.add('active-nav');

        // highlight tab mobile
        document.querySelectorAll('.mobile-tab-item').forEach(el => el.classList.remove('active-tab'));
        if (tabEl) tabEl.classList.add('active-tab');
        else {
            const mobileTab = document.querySelector(`.mobile-tab-item[data-panel="${name}"]`);
            if (mobileTab) mobileTab.classList.add('active-tab');
        }

        if (name === 'main') {
            document.getElementById('panel-main').style.display = 'block';
        } else {
            document.getElementById('panel-' + name).classList.add('active');
        }
    }

    function togglePasswordSection() {
        const container = document.getElementById('passwordFieldsContainer');
        const btn = document.getElementById('togglePasswordBtn');
        if (container.style.display === 'block') {
            container.style.display = 'none';
            btn.innerText = 'Change Password';
            btn.style.background = 'white';
            btn.style.color = 'var(--jaced-brown-dark)';
            btn.style.borderColor = 'var(--jaced-brown-dark)';
        } else {
            container.style.display = 'block';
            btn.innerText = 'Cancel';
            btn.style.background = '#C0392B';
            btn.style.color = 'white';
            btn.style.borderColor = '#C0392B';
        }
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => { document.getElementById('avatarPreview').src = e.target.result; };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function confirmDeleteAvatar() {
        Swal.fire({
            title: 'Remove Profile Photo?',
            text: 'Your photo will be reset to default.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#c0392b',
            cancelButtonColor: '#d4b896',
            confirmButtonText: 'Yes, remove it',
            cancelButtonText: 'Cancel',
        }).then(result => {
            if (result.isConfirmed) document.getElementById('form-delete-avatar').submit();
        });
    }

    function toggleFaq(el) {
        const body = el.querySelector('.faq-body');
        const icon = el.querySelector('.faq-chevron');
        const isOpen = body.style.display === 'block';
        body.style.display = isOpen ? 'none' : 'block';
        icon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
    }
</script>
@endpush