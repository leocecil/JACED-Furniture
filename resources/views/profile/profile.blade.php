@extends('base.base')

@push('styles')
{{-- <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700&display=swap" rel="stylesheet"> --}}
<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
<style>
    .profile-page {
        background-color: var(--jaced-cream);
        min-height: calc(100vh - 60px);
        padding: 40px 16px 64px;
    }

    .profile-wrapper {
        max-width: 640px;
        margin: 0 auto;
    }

    /* Avatar */
    .avatar-img {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--jaced-input);
    }

    .avatar-badge {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 22px;
        height: 22px;
        background-color: var(--jaced-brown-dark);
        border-radius: 50%;
        border: 2px solid var(--jaced-cream);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Stat Cards */
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid var(--jaced-input);
    }

    .stat-icon {
        color: var(--jaced-muted);
        margin-bottom: 10px;
    }

    .stat-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--jaced-brown-dark);
        margin-bottom: 8px;
    }

    .stat-link {
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--jaced-caramel);
        text-decoration: none;
    }

    .stat-link:hover {
        color: var(--jaced-brown);
    }

    /* Section Label */
    .section-label {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--jaced-muted);
        margin-bottom: 10px;
    }

    /* Menu List */
    .menu-list {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--jaced-input);
        overflow: hidden;
    }

    .menu-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 18px;
        text-decoration: none;
        color: var(--jaced-brown-dark);
        border-bottom: 1px solid var(--jaced-input);
        transition: background 0.15s;
    }

    .menu-item:last-child {
        border-bottom: none;
    }

    .menu-item:hover {
        background-color: var(--jaced-caramel-bg);
    }

    .menu-icon-wrap {
        width: 38px;
        height: 38px;
        background-color: var(--jaced-cream);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .menu-icon-wrap svg {
        width: 16px;
        height: 16px;
        stroke: var(--jaced-brown);
        fill: none;
        stroke-width: 1.8;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .menu-label {
        flex: 1;
        font-size: 0.9rem;
        font-weight: 400;
    }

    .menu-chevron svg {
        width: 15px;
        height: 15px;
        stroke: var(--jaced-input);
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    /* Danger */
    .menu-item.danger .menu-icon-wrap {
        background-color: #FEE9E7;
    }

    .menu-item.danger .menu-icon-wrap svg {
        stroke: #C0392B;
    }

    .menu-item.danger .menu-label {
        color: #C0392B;
        font-weight: 500;
    }
</style>
@endpush

@section('content')

<div class="profile-page font-serif-jaced">
    <div class="profile-wrapper">

        {{-- Profile Header --}}
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="position-relative" style="flex-shrink:0;">
                <img src="{{ $user->avatar ?? asset('image/avatar-placeholder.png') }}"
                     alt="{{ $user->name }}"
                     class="avatar-img">
                <div class="avatar-badge">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="white">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
            </div>
            <div>
                <h2 class="fw-bold mb-1 text-jaced-dark" style="font-size:1.4rem; letter-spacing:-0.3px;">
                    {{ $user->name ?? 'Diah' }}
                </h2>
                <p class="mb-0 text-jaced-muted" style="font-size:0.85rem;">
                    Member since {{ $user->created_at ? $user->created_at->format('F Y') : 'October 2022' }}
                </p>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <div class="stat-value">{{ number_format($user->artisan_points ?? 1250) }} Artisan Points</div>
                    <a href="{{ route('reward') }}" class="stat-link">Redeem</a>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                            <line x1="1" y1="10" x2="23" y2="10"/>
                        </svg>
                    </div>
                    <div class="stat-value">{{ $user->vouchers_count ?? 3 }} Available Vouchers</div>
                    <a href="#" class="stat-link">View All</a>
                </div>
            </div>
        </div>

        {{-- Preferences & Account --}}
        <div class="section-label">Preferences &amp; Account</div>
        <div class="menu-list mb-3">

            <a href="{{ route('profile.edit', $user->id) }}" class="menu-item">
                <div class="menu-icon-wrap">
                    <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </div>
                <span class="menu-label">Edit Profile</span>
                <span class="menu-chevron"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>
            </a>

            <a href="{{ route('reward') }}" class="menu-item">
                <div class="menu-icon-wrap">
                    <svg viewBox="0 0 24 24"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                </div>
                <span class="menu-label">Reward Center</span>
                <span class="menu-chevron"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>
            </a>

            <a href="{{ route('tos') }}" class="menu-item">
                <div class="menu-icon-wrap">
                    <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                </div>
                <span class="menu-label">Terms of Service</span>
                <span class="menu-chevron"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>
            </a>

            <a href="{{ route('privacy') }}" class="menu-item">
                <div class="menu-icon-wrap">
                    <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <span class="menu-label">Privacy Policy</span>
                <span class="menu-chevron"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>
            </a>

            <a href="{{ route('help') }}" class="menu-item">
                <div class="menu-icon-wrap">
                    <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><path d="M9 9a3 3 0 0 1 5.12-2.12A3 3 0 0 1 12 12"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
                <span class="menu-label">Help Center</span>
                <span class="menu-chevron"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>
            </a>

        </div>

        {{-- Delete Account --}}
        <div class="menu-list">
            <a href="#" class="menu-item danger"
               onclick="return confirm('Are you sure you want to delete your account?')">
                <div class="menu-icon-wrap">
                    <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                </div>
                <span class="menu-label">Delete Account</span>
                <span class="menu-chevron"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>
            </a>
        </div>

    </div>
</div>

@endsection