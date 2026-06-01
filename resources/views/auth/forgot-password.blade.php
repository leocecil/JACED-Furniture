@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')

    <div style="display:flex; align-items:center; gap:10px; margin-bottom:6px;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="var(--jaced-caramel)" style="width:18px;height:18px;">
            <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
        </svg>
        <h5 class="auth-title" style="margin:0;">Forgot Password</h5>
    </div>
    <p class="auth-subtitle">Enter your email address and we'll send you a link to reset your password.</p>

    <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="label-jaced" for="email">Email Address</label>
            <input id="email" name="email" type="email"
                   class="input-jaced form-control @error('email') is-error @enderror"
                   value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
            @error('email')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-jaced-primary mt-1">
            Send Reset Link
        </button>
    </form>

    <p style="text-align:center; margin-top:1rem; font-size:.85rem;">
        <a href="{{ route('login') }}" class="auth-link">← Back to login</a>
    </p>
@endsection