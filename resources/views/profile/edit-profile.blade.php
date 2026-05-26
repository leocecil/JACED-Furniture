@extends('base.base')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .edit-profile-page {
        background-color: var(--jaced-cream);
        min-height: 100vh;
        padding: 40px 16px 80px;
    }
    .form-wrapper {
        max-width: 1000px; /* Diperlebar agar muat 2 kolom */
        margin: 0 auto;
    }
    .section-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--jaced-muted);
        margin: 0 0 15px;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--jaced-input);
    }
    .jaced-card {
        background: white;
        padding: 24px;
        border-radius: 16px;
        border: 1px solid var(--jaced-input);
        margin-bottom: 24px;
    }
    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--jaced-brown-dark);
        margin-bottom: 6px;
    }
    .jaced-input {
        width: 100%;
        padding: 12px 16px;
        border-radius: 10px;
        border: 1px solid var(--jaced-input);
        font-size: 0.9rem;
        margin-bottom: 16px;
        transition: 0.2s;
        background: white;
    }
    .jaced-input:focus {
        outline: none;
        border-color: var(--jaced-caramel);
        background-color: #fffdfb;
    }
    .btn-save {
        background-color: var(--jaced-brown-dark);
        color: white;
        border: none;
        width: 100%;
        padding: 16px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background .2s;
    }
    .btn-save:hover { background-color: #333; }
    
    .change-pw-box {
        background-color: #faf6f0; /* Diubah dari merah ke warna warm tone jaced */
        border: 1px dashed var(--jaced-input);
    }
    
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--jaced-brown-dark);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 20px;
        transition: all 0.2s ease;
        opacity: 0.8;
    }
    .back-link:hover {
        color: var(--jaced-caramel);
        opacity: 1;
        transform: translateX(-4px);
    }
    .btn-manage-address {
        background: var(--jaced-sage);
        color: white;
        padding: 9px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        text-decoration: none;
        transition: background .2s;
    }
    .btn-manage-address:hover {
        background: #4a5d4b;
        color: white;
    }

    /* AVATAR UPLOAD COMPONENT STYLE */
    .avatar-upload-container {
        position: relative;
        width: 130px;
        height: 130px;
        margin: 0 auto 20px;
    }
    .avatar-preview-wrapper {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid white;
        box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        background: #f3f3f3;
    }
    .avatar-preview-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .avatar-edit-trigger {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 36px;
        height: 36px;
        background: var(--jaced-brown-dark);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        transition: all 0.2s;
    }
    .avatar-edit-trigger:hover {
        background: var(--jaced-caramel);
        transform: scale(1.05);
    }
    
    /* MINI TIER VISUALIZER CARD */
    .mini-tier-badge {
        background: linear-gradient(135deg, var(--jaced-caramel), #6E4524);
        color: white;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        display: inline-block;
        text-transform: uppercase;
    }
</style>
@endpush

@section('content')
<div class="edit-profile-page">
    <div class="form-wrapper">

        {{-- BACK LINK --}}
        <a href="{{ route('profile') }}" class="back-link">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back to Profile</span>
        </a>

        <h3 class="fw-bold mb-4" style="color: var(--jaced-brown-dark); letter-spacing: -0.5px;">Edit Profile</h3>

        {{-- Session Alerts --}}
        @if(session('success'))
            <div class="alert alert-success mb-4" style="font-size: 13px; border-radius: 10px; background-color: #edf7ed; color: #1e4620; border: none;">
                ✨ {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mb-4" style="font-size: 13px; border-radius: 10px; background-color: #fdeded; color: #5f2120; border: none;">
                ⚠️ {{ $errors->first() }}
            </div>
        @endif

        {{-- MAIN FORM WITH MULTIPART FOR IMAGES --}}
        <form action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row g-4">
                
                {{-- SISI KIRI: PROFILE PICTURE & MEMBERSHIP CARD --}}
                <div class="col-12 col-md-4 col-lg-3.5 text-center">
                    <div class="jaced-card py-4">
                        
                        {{-- PHOTO PROFILE PICKER --}}
                        <div class="avatar-upload-container">
                            <div class="avatar-preview-wrapper">
                                <img id="avatarPreview" 
                                    src="{{ $user->avatar 
                                    ? (str_starts_with($user->avatar, 'http') ? $user->avatar : asset($user->avatar)) 
                                    : asset('image/avatars/default_avatar.png') }}?v={{ time() }}"
                                    alt="Profile Picture">
                            </div>
                            <label for="avatarFileInput" class="avatar-edit-trigger">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                        <circle cx="12" cy="13" r="4"></circle>
                                    </svg>
                            </label>
                            <input type="file" name="avatar" id="avatarFileInput" accept="image/*" style="display: none;" onchange="previewImage(this)">
                        </div>
                        {{-- Tombol trigger --}}
                        @if($user->avatar && !str_contains($user->avatar, 'default_avatar'))
                            <button type="button" onclick="document.getElementById('deleteModal').style.display='flex'" style="
                                background: none; 
                                border: 1px solid #ffcccc; 
                                color: #c0392b; 
                                font-size: 11px; 
                                cursor: pointer;
                                padding: 5px 12px;
                                border-radius: 20px;
                                display: inline-flex;
                                align-items: center;
                                gap: 4px;
                                transition: all 0.2s;
                            "
                            onmouseover="this.style.background='#fff0f0'; this.style.borderColor='#e74c3c';"
                            onmouseout="this.style.background='none'; this.style.borderColor='#ffcccc';">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6l-1 14H6L5 6"></path>
                                    <path d="M10 11v6M14 11v6"></path>
                                </svg>
                                Remove Photo
                            </button>
                        @endif

                       

                        <h5 class="fw-bold mb-1 text-jaced-dark" style="font-size: 15px;">{{ $user->name }}</h5>
                        <p class="text-muted small mb-3">{{ $user->email }}</p>
                        
                        <hr style="border-color: var(--jaced-input);">

                        {{-- TIER SUMMARY BOX --}}
                        <div class="mt-3">
                            <p class="text-uppercase tracking-wider text-muted mb-2" style="font-size: 10px; font-weight: 700;">Account Tier Status</p>
                            <div class="mini-tier-badge shadow-sm">
                                {{ $stage ?? 'Bronze' }} Member
                            </div>
                            <p class="text-muted mt-2 mb-0" style="font-size: 11px;">
                                Total Pts Accumulation:<br><strong>{{ number_format($user->accumulated_points ?? 0) }} Pts</strong>
                            </p>
                        </div>

                    </div>
                </div>

                {{-- SISI KANAN: FIELD DETAIL DATA DIRI --}}
                <div class="col-12 col-md-8 col-lg-8.5">
                    
                    {{-- Section 1: Personal Info --}}
                    <div class="section-title">Personal Information</div>
                    <div class="jaced-card">
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="jaced-input"
                                       value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="jaced-input"
                                       value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="jaced-input"
                                       value="{{ old('phone', $user->phone_number) }}"
                                       placeholder="0812...">
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Shipping Address --}}
                    <div class="section-title">Shipping Address</div>
                    <div class="jaced-card d-flex align-items-center justify-content-between" style="padding: 20px 24px;">
                        <div>
                            <p class="mb-1 fw-semibold" style="font-size: 14px; color: var(--jaced-brown-dark);">
                                Manage your saved addresses
                            </p>
                            <p class="mb-0" style="font-size: 12px; color: var(--jaced-muted);">
                                Add, edit, or remove shipping addresses for faster checkouts.
                            </p>
                        </div>
                        <a href="{{ route('profile.addresses') }}" class="btn-manage-address shadow-sm">
                            Manage Addresses
                        </a>
                    </div>

                    {{-- Section 3: Security --}}
                    <div class="section-title">Security & Password</div>
                    <div class="jaced-card change-pw-box">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="jaced-input"
                                       placeholder="Leave blank if no change">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="jaced-input"
                                       placeholder="Leave blank if no change">
                            </div>
                        </div>

                        <p class="mb-0 mt-1 d-flex align-items-center gap-1.5" style="font-size: 0.75rem; color: var(--jaced-muted);">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            <span>For security, changing password will require you to log in again.</span>
                        </p>
                    </div>

                    {{-- ACTION BUTTON --}}
                    <button type="submit" class="btn-save shadow-sm mt-2">Save All Profile Changes</button>
                    
                </div>
            </div>

        </form>
        <div id="deleteModal" style="
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        ">
            <div style="
                background: white;
                border-radius: 20px;
                padding: 32px 28px;
                max-width: 360px;
                width: 90%;
                text-align: center;
                box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            ">
                <div style="font-size: 40px; margin-bottom: 12px;">🗑️</div>
                <h5 style="font-weight: 700; color: var(--jaced-brown-dark); margin-bottom: 8px;">Remove Profile Photo?</h5>
                <p style="font-size: 13px; color: var(--jaced-muted); margin-bottom: 24px;">
                    Your photo will be reset back to the default avatar.
                </p>
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <button onclick="document.getElementById('deleteModal').style.display='none'" style="
                        padding: 10px 24px;
                        border-radius: 10px;
                        border: 1px solid var(--jaced-input);
                        background: white;
                        font-size: 13px;
                        font-weight: 600;
                        cursor: pointer;
                        color: var(--jaced-muted);
                    ">Cancel</button>
                    <button onclick="document.getElementById('form-delete-avatar').submit()" style="
                        padding: 10px 24px;
                        border-radius: 10px;
                        border: none;
                        background: #c0392b;
                        color: white;
                        font-size: 13px;
                        font-weight: 600;
                        cursor: pointer;
                    ">Yes, Remove</button>
                </div>
            </div>
        </div>

        {{-- Form delete --}}
        @if($user->avatar && !str_contains($user->avatar, 'default_avatar'))
            <form id="form-delete-avatar" action="{{ route('profile.avatar.delete', $user->id) }}" method="POST">
                @csrf
                @method('DELETE')
            </form>
        @endif       
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // FUNGSI JAVASCRIPT REALTIME PREVIEW UNTUK FOTO YANG DIPILIH
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Ganti onclick button jadi panggil fungsi ini
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
            borderRadius: '16px',
            customClass: {
                popup: 'swal-jaced',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-delete-avatar').submit();
            }
        });
    }
</script>
@endpush