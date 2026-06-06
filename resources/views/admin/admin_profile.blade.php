@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/profile.css') }}">

<div class="ap-page">
    <p class="ap-heading">Profile</p>

    <div class="ap-layout">

        {{-- ═══════════════════════════════════════ SIDEBAR ══ --}}
        <aside class="ap-sidebar">
            <div class="ap-profile-card">

                {{-- Avatar --}}
                <div class="ap-avatar-wrap" onclick="document.getElementById('avatarInput').click()" title="Change avatar">
                    @if ($user->avatar)
                        <img src="{{ asset($user->avatar) }}" alt="Avatar" class="ap-avatar" id="ap-avatar-img">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=EAE5DB&color=5A4D47&size=200&bold=true"
                                alt="Avatar" class="ap-avatar" id="ap-avatar-img">
                    @endif
                    <div class="ap-avatar-overlay">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                        </svg>
                    </div>
                </div>

                <p class="ap-profile-name">{{ $user->name }}</p>

                <span class="ap-profile-role">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.403 12.652a3 3 0 0 0 0-5.304 3 3 0 0 0-3.75-3.751 3 3 0 0 0-5.305 0 3 3 0 0 0-3.751 3.75 3 3 0 0 0 0 5.305 3 3 0 0 0 3.75 3.751 3 3 0 0 0 5.305 0 3 3 0 0 0 3.751-3.75Zm-2.546-4.46a.75.75 0 0 0-1.214-.883l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                    </svg>
                    {{ $user->is_admin ? 'Administrator' : 'Staff' }}
                </span>

                {{-- Upload avatar button --}}
                <form action="{{ route('admin.profile.avatar') }}" method="POST" enctype="multipart/form-data" id="ap-avatar-form">
                    @csrf
                    <input type="file" id="avatarInput" name="avatar" accept="image/jpeg,image/png,image/webp"
                            style="display:none" onchange="handleAvatarChange(this)">
                </form>

                <button type="button" class="ap-upload-btn" onclick="document.getElementById('avatarInput').click()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                    </svg>
                    Update Avatar
                </button>

                @if (session('avatar_success'))
                    <div class="ap-alert ap-alert-success" style="width:100%;margin-bottom:0;margin-top:.25rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                        </svg>
                        {{ session('avatar_success') }}
                    </div>
                @endif

                {{-- ── Account Details ── --}}
                <div class="ap-account-details">
                    <p class="ap-account-details-title">Account Details</p>

                    <div class="ap-account-row">
                        <span class="ap-account-label">Member Since</span>
                        <span class="ap-account-value">{{ $user->created_at->format('d M Y') }}</span>
                    </div>

                    <div class="ap-account-divider"></div>

                    <div class="ap-account-row">
                        <span class="ap-account-label">Last Updated</span>
                        <span class="ap-account-value">{{ $user->updated_at->format('d M Y') }}</span>
                    </div>
                </div>

            </div>
        </aside>

        {{-- ═══════════════════════════════════════ MAIN ══════ --}}
        <div class="ap-main">

            {{-- ── Personal Information Card ── --}}
            <div class="ap-card">
                <div class="ap-card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.723 1.723 0 0 0 .41 1.412A8.001 8.001 0 0 0 10 18c2.188 0 4.163-.87 5.618-2.283l.01-.01c.38-.38.36-.982-.104-1.338A7.5 7.5 0 0 0 10 12.5a7.5 7.5 0 0 0-6.535 1.993Z" />
                    </svg>
                    Personal Information
                </div>

                @if (session('info_success'))
                    <div class="ap-alert ap-alert-success">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                        </svg>
                        {{ session('info_success') }}
                    </div>
                @endif

                <form action="{{ route('admin.profile.info') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="ap-form-grid">
                        <div class="ap-field ap-field-full">
                            <label class="ap-label" for="name">Full Name</label>
                            <input id="name" name="name" type="text" class="ap-input @error('name') is-error @enderror"
                                    value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="ap-error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="ap-field">
                            <label class="ap-label" for="email">Email Address</label>
                            <input id="email" name="email" type="email" class="ap-input @error('email') is-error @enderror"
                                    value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="ap-error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="ap-field">
                            <label class="ap-label" for="phone_number">Phone Number</label>
                            <input id="phone_number" name="phone_number" type="text"
                                    class="ap-input @error('phone_number') is-error @enderror"
                                    value="{{ old('phone_number', $user->phone_number) }}" required>
                            @error('phone_number')
                                <span class="ap-error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="ap-form-footer">
                        <button type="submit" class="ap-btn ap-btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.75 2.75a.75.75 0 0 0-1.5 0v8.614L6.295 8.235a.75.75 0 1 0-1.09 1.03l4.25 4.5a.75.75 0 0 0 1.09 0l4.25-4.5a.75.75 0 0 0-1.09-1.03l-2.955 3.129V2.75Z" />
                                <path d="M3.5 12.75a.75.75 0 0 0-1.5 0v2.5A2.75 2.75 0 0 0 4.75 18h10.5A2.75 2.75 0 0 0 18 15.25v-2.5a.75.75 0 0 0-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5Z" />
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            {{-- ── Security Card (summary + trigger modal) ── --}}
            <div class="ap-card">
                <div class="ap-card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
                    </svg>
                    Security
                </div>

                @if (session('password_success'))
                    <div class="ap-alert ap-alert-success">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                        </svg>
                        {{ session('password_success') }}
                    </div>
                @endif

                <div class="ap-security-row">
                    <div class="ap-security-info">
                        <p class="ap-security-label">Password</p>
                        <p class="ap-security-meta">
                            Last updated:
                            {{ $user->updated_at->format('d M Y') }}
                        </p>
                    </div>
                    <button type="button" class="ap-btn ap-btn-outline" onclick="openPasswordModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
                        </svg>
                        Change Password
                    </button>
                </div>
            </div>

        </div>{{-- .ap-main --}}
    </div>{{-- .ap-layout --}}
</div>


{{-- ══════════════════════════════════ MODAL ══════════ --}}
<div class="ap-modal-backdrop" id="ap-pw-modal" onclick="handleBackdropClick(event)">
    <div class="ap-modal">

        {{-- Header --}}
        <div class="ap-modal-header">
            <div class="ap-modal-title-wrap">
                <div class="ap-modal-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span class="ap-modal-title">Change Password</span>
            </div>
            <button class="ap-modal-close" onclick="closePasswordModal()" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- ── Step 1: Verify current password ── --}}
        <div id="ap-modal-step-1">
            <p class="ap-modal-desc">Enter your current password to continue.</p>

            <div id="ap-verify-error" class="ap-alert ap-alert-error" style="display:none;margin-bottom:1rem;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                </svg>
                <span id="ap-verify-error-text">Incorrect password. Please try again.</span>
            </div>

            <div class="ap-field">
                <label class="ap-label" for="modal-current-pw">Current Password</label>
                <div class="ap-input-wrap">
                    <input id="modal-current-pw" type="password" class="ap-input"
                            placeholder="Enter current password" autocomplete="current-password"
                            onkeydown="if(event.key==='Enter') verifyCurrentPassword()">
                    <button type="button" class="ap-pw-toggle" onclick="togglePw('modal-current-pw', this)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </button>
                </div>
                <a href="{{ route('password.request') }}" class="ap-forgot-link">Forgot password?</a>
            </div>

            <div class="ap-modal-footer">
                <button type="button" class="ap-btn ap-btn-ghost" onclick="closePasswordModal()">Cancel</button>
                <button type="button" class="ap-btn ap-btn-primary" id="ap-verify-btn" onclick="verifyCurrentPassword()">
                    <span id="ap-verify-btn-text">Verify</span>
                    <span id="ap-verify-btn-loading" style="display:none;">
                        <svg class="ap-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="31.4" stroke-dashoffset="10" />
                        </svg>
                    </span>
                </button>
            </div>
        </div>

        {{-- ── Step 2: New password ── --}}
        <div id="ap-modal-step-2" style="display:none;">
            <p class="ap-modal-desc">Choose a strong new password.</p>

            <form action="{{ route('admin.profile.password') }}" method="POST" id="ap-pw-form">
                @csrf
                @method('PUT')
                {{-- hidden field to carry verified current password --}}
                <input type="hidden" name="current_password" id="ap-hidden-current-pw">

                <div class="ap-modal-fields">
                    <div class="ap-field">
                        <label class="ap-label" for="modal-new-pw">New Password</label>
                        <div class="ap-input-wrap">
                            <input id="modal-new-pw" name="password" type="password"
                                    class="ap-input" placeholder="Min. 8 characters"
                                    autocomplete="new-password" oninput="checkStrength(this.value)">
                            <button type="button" class="ap-pw-toggle" onclick="togglePw('modal-new-pw', this)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                        <div class="ap-strength-bar"><div class="ap-strength-fill" id="ap-strength-fill"></div></div>
                        <span class="ap-strength-label" id="ap-strength-label"></span>
                    </div>

                    <div class="ap-field">
                        <label class="ap-label" for="modal-confirm-pw">Confirm New Password</label>
                        <div class="ap-input-wrap">
                            <input id="modal-confirm-pw" name="password_confirmation" type="password"
                                    class="ap-input" placeholder="Repeat new password"
                                    autocomplete="new-password">
                            <button type="button" class="ap-pw-toggle" onclick="togglePw('modal-confirm-pw', this)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="ap-modal-footer">
                    <button type="button" class="ap-btn ap-btn-ghost" onclick="closePasswordModal()">Cancel</button>
                    <button type="submit" class="ap-btn ap-btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
                        </svg>
                        Update Password
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
{{-- pass the verify route to JS --}}
<script>
    const AP_VERIFY_URL = "{{ route('admin.profile.verify-password') }}";
    const AP_CSRF      = "{{ csrf_token() }}";
</script>

<script src="{{ asset('js/admin/profile.js') }}"></script>

@endsection