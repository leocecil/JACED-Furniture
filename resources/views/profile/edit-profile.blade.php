@extends('base.base')

@push('styles')
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

    .jaced-input, .jaced-select {
        width: 100%;
        padding: 12px 16px;
        border-radius: 10px;
        border: 1px solid var(--jaced-input);
        font-size: 0.9rem;
        margin-bottom: 16px;
        transition: 0.2s;
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
    }

    .change-pw-box {
        background-color: #fef1f0; /* Soft red/brown tint */
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

    .back-link svg {
        transition: transform 0.2s ease;
    }
</style>
@endpush

@section('content')
<div class="edit-profile-page">
    <div class="form-wrapper">
        {{-- Tombol Back --}}
        <a href="{{ route('profile') }}" class="back-link">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back</span>
        </a>
        <h3 class="fw-bold mb-4" style="color: var(--jaced-brown-dark);">Edit Profile</h3>

        <form action="#" method="POST">
            @csrf

            {{-- Section 1: Basic Info --}}
            <div class="section-title">Personal Information</div>
            <div class="jaced-card">
                <div class="row">
                    <div class="col-12">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="jaced-input" value="{{ $user->name ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="jaced-input" value="{{ $user->email ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="jaced-input" value="{{ $user->phone ?? '' }}" placeholder="0812...">
                    </div>
                </div>
            </div>

            {{-- Section 2: Address --}}
            <div class="section-title">Shipping Address</div>
            <div class="jaced-card">
                <label class="form-label">Full Address</label>
                <textarea name="address" class="jaced-input" rows="2">{{ $user->address ?? '' }}</textarea>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Province</label>
                        <select name="province" class="jaced-select">
                            <option value="bali">Bali</option>
                            <option value="java">Jawa Timur</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">City / District</label>
                        <input type="text" name="city" class="jaced-input" value="{{ $user->city ?? '' }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Postal Code</label>
                        <input type="text" name="postal_code" class="jaced-input" value="{{ $user->postal_code ?? '' }}">
                    </div>
                </div>
            </div>

            {{-- Section 3: Security --}}
            <div class="section-title">Security</div>
            <div class="jaced-card change-pw-box">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="jaced-input" placeholder="Leave blank if no change">
                
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="jaced-input">
                
                <p class="mb-0 mt-2" style="font-size: 0.75rem; color: #888;">
                    <i class="fas fa-info-circle"></i> For security, changing password will require you to log in again.
                </p>
            </div>

            <button type="submit" class="btn-save shadow-sm">Save All Changes</button>
        </form>

    </div>
</div>
@endsection