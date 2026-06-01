@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')

    <div style="display:flex; align-items:center; gap:10px; margin-bottom:6px;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="var(--jaced-caramel)" style="width:18px;height:18px;">
            <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
        </svg>
        <h5 class="auth-title" style="margin:0;">Reset Password</h5>
    </div>
    <p class="auth-subtitle">Enter your new password below.</p>

    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">
        <input type="hidden" name="role" value="{{ $role ?? 'customer' }}">

        {{-- Email (disabled, hanya tampilan) --}}
        <div class="mb-3">
            <label class="label-jaced" for="email-display">Email Address</label>
            <input id="email-display" type="email"
                   class="input-jaced form-control"
                   value="{{ $email }}" disabled>
        </div>

        {{-- New Password --}}
        <div class="mb-3">
            <label class="label-jaced" for="password">New Password</label>
            <div class="input-icon-wrap">
                <input id="password" name="password" type="password"
                       class="input-jaced form-control @error('password') is-error @enderror"
                       placeholder="Min. 8 characters" autocomplete="new-password" required>
                <button type="button" class="btn-icon" onclick="togglePw('password', this)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </button>
            </div>
            @error('password')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="mb-3">
            <label class="label-jaced" for="password_confirmation">Confirm New Password</label>
            <div class="input-icon-wrap">
                <input id="password_confirmation" name="password_confirmation" type="password"
                       class="input-jaced form-control"
                       placeholder="Repeat new password" autocomplete="new-password" required>
                <button type="button" class="btn-icon" onclick="togglePw('password_confirmation', this)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-jaced-primary mt-1">
            Reset Password
        </button>
    </form>

@endsection

@push('scripts')
<script>
    function togglePw(id, btn) {
        const input = document.getElementById(id);
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>
@endpush