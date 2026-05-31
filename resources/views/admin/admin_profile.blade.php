@extends('layouts.app')

@section('content')

<style>
    /* ── Variables ───────────────────────────────────────── */
    :root {
        --cream:        #FAF8F4;
        --cream-2:      #F3F0EA;
        --cream-3:      #EAE5DB;
        --ink:          #1A1714;
        --ink-2:        #3D3830;
        --ink-muted:    #7A7369;
        --border:       #DDD8CF;
        --accent:       #B87333;
        --accent-soft:  #F5E6D3;
        --blue:         #2667CC;
        --blue-soft:    #DBE8FB;
        --teal:         #007A7A;
        --teal-soft:    #CCEAEA;
        --amber:        #C47B00;
        --amber-soft:   #FAF0D0;
        --danger:       #A0320A;
        --danger-soft:  #FAE0D3;
        --green:        #1E7D45;
        --green-soft:   #D4EDDA;
        --jaced-white:        #F9F9F7;
        --jaced-cream:        #F2EDE6;
        --jaced-card:         #FAF7F2;
        --jaced-brown-dark:   #272E1D;
        --jaced-dark:         #1C1C1A;
        --jaced-brown:        #5A4D47;
        --jaced-caramel:      #C99A6B;
        --jaced-sage:         #5A6B5B;
        --jaced-input:        #DDD6CE;
        --jaced-muted:        #8A857D;
        --jaced-caramel-bg:   #F5EBE0;
    }

    /* ── Page wrapper ────────────────────────────────────── */
    .ap-page {
        min-height: 100vh;
        background: var(--cream);
        padding: 2rem 1.5rem 3rem;
        color: var(--ink);
    }

    .ap-heading {
        font-size: 1.05rem;
        font-weight: 600;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--ink-muted);
        margin-bottom: 1.75rem;
    }

    /* ── Layout ──────────────────────────────────────────── */
    .ap-layout {
        display: flex;
        gap: 1.75rem;
        align-items: flex-start;
    }

    .ap-sidebar {
        width: 30%;
        flex-shrink: 0;
        position: sticky;
        top: 1.5rem;
    }

    .ap-main {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    @media (max-width: 768px) {
        .ap-layout {
            flex-direction: column;
        }
        .ap-sidebar {
            width: 100%;
            position: static;
        }
    }

    /* ── Card base ───────────────────────────────────────── */
    .ap-card {
        background: var(--jaced-card);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 1.75rem;
    }

    .ap-card-title {
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .09em;
        text-transform: uppercase;
        color: var(--ink-muted);
        margin-bottom: 1.25rem;
        padding-bottom: .75rem;
        border-bottom: 1px solid var(--cream-3);
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .ap-card-title svg {
        width: 15px;
        height: 15px;
        color: var(--accent);
        flex-shrink: 0;
    }

    /* ── Sidebar card ────────────────────────────────────── */
    .ap-profile-card {
        background: var(--jaced-card);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 2rem 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: .85rem;
    }

    /* Avatar */
    .ap-avatar-wrap {
        position: relative;
        width: 100px;
        height: 100px;
    }

    .ap-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--border);
        background: var(--cream-3);
        display: block;
    }

    .ap-avatar-overlay {
        position: absolute;
        inset: 0;
        border-radius: 50%;
        background: rgba(26,23,20,.45);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity .2s;
        cursor: pointer;
    }

    .ap-avatar-wrap:hover .ap-avatar-overlay {
        opacity: 1;
    }

    .ap-avatar-overlay svg {
        width: 22px;
        height: 22px;
        color: #fff;
    }

    .ap-profile-name {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--ink);
        line-height: 1.3;
        margin: 0;
    }

    .ap-profile-role {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        font-size: .73rem;
        font-weight: 600;
        letter-spacing: .07em;
        text-transform: uppercase;
        color: var(--accent);
        background: var(--accent-soft);
        border: 1px solid rgba(184,115,51,.2);
        border-radius: 999px;
        padding: .28rem .85rem;
    }

    .ap-upload-btn {
        width: 100%;
        margin-top: .25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .45rem;
        padding: .65rem 1rem;
        background: transparent;
        border: 1.5px dashed var(--border);
        border-radius: 10px;
        font-size: .8rem;
        font-weight: 600;
        letter-spacing: .04em;
        color: var(--ink-muted);
        cursor: pointer;
        transition: border-color .2s, background .2s, color .2s;
    }

    .ap-upload-btn:hover {
        border-color: var(--accent);
        background: var(--accent-soft);
        color: var(--accent);
    }

    .ap-upload-btn svg {
        width: 15px;
        height: 15px;
        flex-shrink: 0;
    }

    /* ── Form elements ───────────────────────────────────── */
    .ap-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .ap-form-grid .ap-field-full {
        grid-column: 1 / -1;
    }

    @media (max-width: 600px) {
        .ap-form-grid {
            grid-template-columns: 1fr;
        }
        .ap-form-grid .ap-field-full {
            grid-column: auto;
        }
    }

    .ap-field {
        display: flex;
        flex-direction: column;
        gap: .4rem;
    }

    .ap-label {
        font-size: .73rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--ink-muted);
    }

    .ap-input {
        width: 100%;
        padding: .65rem .9rem;
        background: var(--cream-2);
        border: 1.5px solid var(--border);
        border-radius: 9px;
        font-size: .9rem;
        color: var(--ink);
        font-family: inherit;
        transition: border-color .2s, background .2s, box-shadow .2s;
        outline: none;
        box-sizing: border-box;
    }

    .ap-input:focus {
        border-color: var(--accent);
        background: #fff;
        box-shadow: 0 0 0 3px var(--accent-soft);
    }

    .ap-input.is-error {
        border-color: var(--danger);
        background: var(--danger-soft);
    }

    .ap-error-msg {
        font-size: .75rem;
        color: var(--danger);
        margin-top: .1rem;
    }

    /* ── Submit row ──────────────────────────────────────── */
    .ap-form-footer {
        display: flex;
        justify-content: flex-end;
        margin-top: .75rem;
    }

    .ap-btn {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .65rem 1.4rem;
        border-radius: 9px;
        font-size: .85rem;
        font-weight: 700;
        letter-spacing: .04em;
        cursor: pointer;
        border: none;
        transition: opacity .18s, transform .15s, box-shadow .18s;
    }

    .ap-btn:hover {
        opacity: .88;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(0,0,0,.1);
    }

    .ap-btn:active { transform: translateY(0); }

    .ap-btn-primary {
        background: var(--accent);
        color: #fff;
    }

    .ap-btn svg {
        width: 15px;
        height: 15px;
        flex-shrink: 0;
    }

    /* ── Alert banners ───────────────────────────────────── */
    .ap-alert {
        display: flex;
        align-items: center;
        gap: .6rem;
        padding: .75rem 1rem;
        border-radius: 9px;
        font-size: .83rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .ap-alert svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    .ap-alert-success {
        background: var(--green-soft);
        color: var(--green);
        border: 1px solid rgba(30,125,69,.2);
    }

    .ap-alert-error {
        background: var(--danger-soft);
        color: var(--danger);
        border: 1px solid rgba(160,50,10,.2);
    }

    /* Password strength bar */
    .ap-strength-bar {
        height: 4px;
        border-radius: 99px;
        background: var(--cream-3);
        margin-top: .4rem;
        overflow: hidden;
    }

    .ap-strength-fill {
        height: 100%;
        border-radius: 99px;
        width: 0%;
        transition: width .3s, background .3s;
    }

    .ap-strength-label {
        font-size: .7rem;
        color: var(--ink-muted);
        margin-top: .25rem;
    }

    /* Password toggle */
    .ap-input-wrap {
        position: relative;
    }

    .ap-input-wrap .ap-input {
        padding-right: 2.5rem;
    }

    .ap-pw-toggle {
        position: absolute;
        right: .75rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: var(--ink-muted);
        padding: 0;
        display: flex;
        align-items: center;
    }

    .ap-pw-toggle:hover { color: var(--ink); }
    .ap-pw-toggle svg { width: 16px; height: 16px; }

    /* Avatar upload modal backdrop */
    #ap-avatar-form {
        display: none;
    }
</style>

<div class="ap-page">
    <p class="ap-heading">Admin Profile</p>

    <div class="ap-layout">

        {{-- ═══════════════════════════════════════ SIDEBAR ══ --}}
        <aside class="ap-sidebar">
            <div class="ap-profile-card">

                {{-- Avatar --}}
                <div class="ap-avatar-wrap" onclick="document.getElementById('avatarInput').click()" title="Change avatar">
                    @if ($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" class="ap-avatar" id="ap-avatar-img">
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
                    Upload Avatar
                </button>

                @if (session('avatar_success'))
                    <div class="ap-alert ap-alert-success" style="width:100%;margin-bottom:0;margin-top:.25rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                        </svg>
                        {{ session('avatar_success') }}
                    </div>
                @endif

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

            {{-- ── Security / Change Password Card ── --}}
            <div class="ap-card">
                <div class="ap-card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
                    </svg>
                    Change Password
                </div>

                @if (session('password_success'))
                    <div class="ap-alert ap-alert-success">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                        </svg>
                        {{ session('password_success') }}
                    </div>
                @endif

                @if ($errors->has('current_password'))
                    <div class="ap-alert ap-alert-error">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                        </svg>
                        {{ $errors->first('current_password') }}
                    </div>
                @endif

                <form action="{{ route('admin.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="ap-form-grid">
                        <div class="ap-field ap-field-full">
                            <label class="ap-label" for="current_password">Current Password</label>
                            <div class="ap-input-wrap">
                                <input id="current_password" name="current_password" type="password"
                                       class="ap-input @error('current_password') is-error @enderror"
                                       placeholder="Enter current password" autocomplete="current-password">
                                <button type="button" class="ap-pw-toggle" onclick="togglePw('current_password', this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="ap-field">
                            <label class="ap-label" for="password">New Password</label>
                            <div class="ap-input-wrap">
                                <input id="password" name="password" type="password"
                                       class="ap-input @error('password') is-error @enderror"
                                       placeholder="Min. 8 characters" autocomplete="new-password"
                                       oninput="checkStrength(this.value)">
                                <button type="button" class="ap-pw-toggle" onclick="togglePw('password', this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                            </div>
                            <div class="ap-strength-bar"><div class="ap-strength-fill" id="ap-strength-fill"></div></div>
                            <span class="ap-strength-label" id="ap-strength-label"></span>
                            @error('password')
                                <span class="ap-error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="ap-field">
                            <label class="ap-label" for="password_confirmation">Confirm New Password</label>
                            <div class="ap-input-wrap">
                                <input id="password_confirmation" name="password_confirmation" type="password"
                                       class="ap-input" placeholder="Repeat new password" autocomplete="new-password">
                                <button type="button" class="ap-pw-toggle" onclick="togglePw('password_confirmation', this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="ap-form-footer">
                        <button type="submit" class="ap-btn ap-btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
                            </svg>
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

        </div>{{-- .ap-main --}}
    </div>{{-- .ap-layout --}}
</div>

<script>
    /* ── Avatar preview before upload ───────────────── */
    function handleAvatarChange(input) {
        if (!input.files || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('ap-avatar-img').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
        // Auto-submit the hidden form
        document.getElementById('ap-avatar-form').submit();
    }

    /* ── Password visibility toggle ─────────────────── */
    function togglePw(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';

        // Swap icon
        btn.innerHTML = isHidden
            ? `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
               </svg>`
            : `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                   <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
               </svg>`;
    }

    /* ── Password strength meter ─────────────────────── */
    function checkStrength(val) {
        const fill  = document.getElementById('ap-strength-fill');
        const label = document.getElementById('ap-strength-label');
        if (!val) {
            fill.style.width = '0%';
            label.textContent = '';
            return;
        }

        let score = 0;
        if (val.length >= 8)                      score++;
        if (/[A-Z]/.test(val))                    score++;
        if (/[a-z]/.test(val))                    score++;
        if (/[0-9]/.test(val))                    score++;
        if (/[^A-Za-z0-9]/.test(val))             score++;

        const levels = [
            { pct: '20%', bg: 'var(--danger)',  text: 'Very weak' },
            { pct: '40%', bg: 'var(--amber)',   text: 'Weak'      },
            { pct: '60%', bg: 'var(--amber)',   text: 'Fair'      },
            { pct: '80%', bg: 'var(--teal)',    text: 'Strong'    },
            { pct: '100%',bg: 'var(--green)',   text: 'Very strong'},
        ];

        const level = levels[score - 1] || levels[0];
        fill.style.width      = level.pct;
        fill.style.background = level.bg;
        label.textContent     = level.text;
        label.style.color     = level.bg;
    }
</script>

@endsection