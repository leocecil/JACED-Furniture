@extends('base.base')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/jaced.css') }}">
<style>
    .edit-profile-page {
        background-color: var(--jaced-cream);
        min-height: 100vh;
        padding: 40px 16px 80px;
    }
    .form-wrapper {
        max-width: 600px;
        margin: 0 auto;
    }
    .section-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--jaced-muted);
        margin: 30px 0 15px;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--jaced-input);
    }
    .jaced-card {
        background: white;
        padding: 24px;
        border-radius: 16px;
        border: 1px solid var(--jaced-input);
        margin-bottom: 20px;
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
        background-color: #fef1f0;
        border: 1px dashed #e5d1d0;
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
</style>
@endpush

@section('content')
<div class="edit-profile-page">
    <div class="form-wrapper">

        <a href="{{ route('profile') }}" class="back-link">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back</span>
        </a>

        <h3 class="fw-bold mb-4" style="color: var(--jaced-brown-dark);">Edit Profile</h3>

        {{-- Alert Success --}}
        @if(session('success'))
            <div class="alert alert-success mb-4" style="font-size: 13px; border-radius: 10px;">
                {{ session('success') }}
            </div>
        @endif

        {{-- Alert Error --}}
        @if($errors->any())
            <div class="alert alert-danger mb-4" style="font-size: 13px; border-radius: 10px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('profile.update', $user->id) }}" method="POST">
            @csrf

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
                        <label class="form-label">Email</label>
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
            <div class="jaced-card d-flex align-items-center justify-content-between"
                 style="padding: 20px 24px;">
                <div>
                    <p class="mb-1 fw-semibold" style="font-size: 14px; color: var(--jaced-brown-dark);">
                        Manage your saved addresses
                    </p>
                    <p class="mb-0" style="font-size: 12px; color: var(--jaced-muted);">
                        Add, edit, or remove shipping addresses
                    </p>
                </div>
                <a href="{{ route('profile.addresses') }}" class="btn-manage-address">
                    Manage Addresses
                </a>
            </div>

            {{-- Section 3: Security --}}
            <div class="section-title">Security</div>
            <div class="jaced-card change-pw-box">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="jaced-input"
                       placeholder="Leave blank if no change">

                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="jaced-input">

                <p class="mb-0 mt-2" style="font-size: 0.75rem; color: #888;">
                    <i class="fas fa-info-circle"></i>
                    For security, changing password will require you to log in again.
                </p>
            </div>

            <button type="submit" class="btn-save shadow-sm">Save All Changes</button>
        </form>

    </div>
</div>
@endsection