@extends('base.base')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    /* LAYOUT CONTAINER UTAMA (2 KOLOM) */
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
        /* Animasi halus saat halaman dimuat */
        animation: profileIn 0.4s cubic-bezier(0.25, 1, 0.5, 1) both;
    }

    @keyframes profileIn {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* KANVAS KARTU GLOBAL (JACED STYLE) */
    .jaced-card {
        background: white;
        padding: 24px;
        border-radius: 16px;
        border: 1px solid var(--jaced-input);
        box-shadow: 0 4px 20px rgba(42, 35, 24, 0.01);
    }

    /* SISI KIRI: SIDEBAR BRANDING & MENU */
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
        bottom: 0;
        right: 0;
        width: 32px;
        height: 32px;
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
        box-shadow: 0 2px 6px rgba(110, 69, 36, 0.15);
    }

    /* MINI NAVIGATION MENU DI SIDEBAR */
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
    }

    .sidebar-menu-item svg {
        width: 16px;
        height: 16px;
        stroke: var(--jaced-brown);
        fill: none;
        stroke-width: 2;
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

    /* SISI KANAN: FORM & AKSI DATA */
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
        background-color: #white;
    }

    /* TOMBOL SIMPAN UTAMA */
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
        transition: background .2s, transform 0.2s;
    }
    .btn-save:hover { 
        background-color: #333; 
    }

    .btn-manage-address {
        background: var(--jaced-sage);
        color: white !important;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: background .2s;
    }
    .btn-manage-address:hover { background: #4a5d4b; }

    /* KOTAK PENGAMAN PASSWORD YANG DIRESPONS Tombol JAVASCRIPT */
    .change-pw-box {
        background-color: #faf6f0;
        border: 1px dashed var(--jaced-input);
        transition: all 0.3s ease;
    }

    .password-fields-collapsed {
        display: none; /* Disembunyikan default agar tidak terlalu terbuka */
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
    }
    .btn-trigger-password:hover {
        background: var(--jaced-brown-dark);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="edit-profile-page font-serif-jaced">
    <div class="profile-grid-container">

        {{-- KOLOM KIRI: SIDEBAR INFORMASI & HUKUM --}}
        <aside class="col-left">
            <div class="jaced-card profile-sidebar text-center">
                
                {{-- AVATAR INTERACTIVE WRAPPER --}}
                <div class="avatar-upload-container">
                    <div class="avatar-preview-wrapper">
                        <img id="avatarPreview" 
                            src="{{ $user->avatar 
                            ? (str_starts_with($user->avatar, 'http') ? $user->avatar : asset($user->avatar)) 
                            : asset('image/avatars/default_avatar.png') }}?v={{ time() }}"
                            alt="Profile Picture">
                    </div>
                    <label for="avatarFileInput" class="avatar-edit-trigger">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                            <circle cx="12" cy="13" r="4"></circle>
                        </svg>
                    </label>
                </div>

                {{-- Hapus Foto Button --}}
                @if($user->avatar && !str_contains($user->avatar, 'default_avatar'))
                    <button type="button" onclick="confirmDeleteAvatar()" style="
                        background: none; border: 1px solid #ffcccc; color: #c0392b; 
                        font-size: 10px; cursor: pointer; padding: 4px 10px; 
                        border-radius: 20px; margin-bottom: 14px; transition: all 0.2s;
                    " onmouseover="this.style.background='#fff0f0'" onmouseout="this.style.background='none'">
                        Remove Photo
                    </button>
                @endif

                <h5 class="fw-bold mb-1 text-jaced-dark" style="font-size: 14px;">{{ $user->name }}</h5>
                <p class="text-muted small mb-3" style="font-size: 11px;">
                    Member since {{ $user->created_at ? $user->created_at->format('M Y') : 'Oct 2022' }}
                </p>
                
                <div class="mini-tier-badge shadow-sm mb-3">
                    {{ $stage ?? 'Bronze' }} Member
                </div>

                <hr style="border-color: var(--jaced-input); margin: 12px 0;">

                {{-- LINK LEGALITAS & MENU YANG TADINYA DI HALAMAN DEPAN --}}
                <nav class="sidebar-menu-list">
                    <a href="{{ route('tos') }}" class="sidebar-menu-item">
                        <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><circle cx="3" cy="6" r="1"/><circle cx="3" cy="12" r="1"/><circle cx="3" cy="18" r="1"/></svg>
                        <span>Terms of Service</span>
                    </nav>
                    <a href="{{ route('privacy') }}" class="sidebar-menu-item">
                        <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>Privacy Policy</span>
                    </a>
                    <a href="{{ route('help') }}" class="sidebar-menu-item">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        <span>Help Center</span>
                    </a>
                    <a href="#" class="sidebar-menu-item danger" onclick="return confirm('Are you sure you want to delete your account?')">
                        <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                        <span>Delete Account</span>
                    </a>
                </nav>

            </div>
        </aside>

        {{-- KOLOM KANAN: MANAGING DATA UTAMA & INPUT --}}
        <main class="col-right-content">
            
            {{-- Alerts Notifikasi --}}
            @if(session('success'))
                <div class="alert alert-success mb-3" style="font-size: 13px; border-radius: 10px; background-color: #edf7ed; color: #1e4620; border: none;">
                    ✨ {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger mb-3" style="font-size: 13px; border-radius: 10px; background-color: #fdeded; color: #5f2120; border: none;">
                    ⚠️ {{ $errors->first() }}
                </div>
            @endif

            {{-- FORM SUBMISSION UTAMA --}}
            <form action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="avatar" id="avatarFileInput" accept="image/*" style="display: none;" onchange="previewImage(this)">

                {{-- S1: Personal Info --}}
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

                {{-- S2: Shipping Address --}}
                <div class="section-title">Shipping Address</div>
                <div class="jaced-card mb-4 d-flex align-items-center justify-content-between" style="padding: 18px 24px;">
                    <div>
                        <p class="mb-0 fw-semibold" style="font-size: 13px; color: var(--jaced-brown-dark);">Manage Saved Addresses</p>
                        <p class="mb-0 text-muted" style="font-size: 11px;">Add or change destination delivery point details.</p>
                    </div>
                    <a href="{{ route('profile.addresses') }}" class="btn-manage-address shadow-sm">Manage Addresses</a>
                </div>

                {{-- S3: Security & Password (SARAN KAMU: TERPROTEKSI/TERSEMBUNYI) --}}
                <div class="section-title">Security &amp; Password</div>
                <div class="jaced-card change-pw-box mb-4">
                    <div class="d-flex justify-content-between align-items-center" id="pwOverviewHeader">
                        <div>
                            <p class="mb-0 fw-semibold" style="font-size: 13px; color: var(--jaced-brown-dark);">Account Password</p>
                            <p class="mb-0 text-muted" style="font-size: 11px;">Update password regularly to keep your assets secure.</p>
                        </div>
                        {{-- Tombol Pemancing untuk membuka Kolom input Password --}}
                        <button type="button" class="btn-trigger-password" id="togglePasswordBtn" onclick="togglePasswordSection()">Change Password</button>
                    </div>

                    {{-- Elemen Input Form Tersembunyi --}}
                    <div class="password-fields-collapsed mt-3" id="passwordFieldsContainer">
                        <hr style="border-color: var(--jaced-input); margin-bottom: 16px;">
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
                        <p class="mb-0 mt-1 d-flex align-items-center gap-2" style="font-size: 11px; color: var(--jaced-muted);">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            <span>Changing password will log you out from your current session.</span>
                        </p>
                    </div>
                </div>

                <button type="submit" class="btn-save shadow-sm">Save All Changes</button>
            </form>
        </main>
    </div>
</div>

{{-- Hidden Form delete avatar asli bawaanmu --}}
@if($user->avatar && !str_contains($user->avatar, 'default_avatar'))
    <form id="form-delete-avatar" action="{{ route('profile.avatar.delete', $user->id) }}" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
@endif 
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // FUNGSI TOGGLE COLLAPSE UNTUK MENYEMBUNYIKAN INPUT PASSWORD FIELD
    function togglePasswordSection() {
        const container = document.getElementById('passwordFieldsContainer');
        const btn = document.getElementById('togglePasswordBtn');
        
        if (container.style.display === 'block') {
            container.style.display = 'none';
            btn.innerText = 'Change Password';
            btn.style.background = 'white';
            btn.style.color = 'var(--jaced-brown-dark)';
        } else {
            container.style.display = 'block';
            btn.innerText = 'Cancel Change';
            btn.style.background = '#C0392B';
            btn.style.color = 'white';
            btn.style.borderColor = '#C0392B';
        }
    }

    // REALTIME FILE PREVIEW
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // SWEETALERT CONFIRM DELETE DARI KODE ASLIMU
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
            borderRadius: '16px'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-delete-avatar').submit();
            }
        });
    }
</script>
@endpush