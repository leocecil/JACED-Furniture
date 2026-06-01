@extends('layouts.abase')

@section('title', 'Admin Login')

@section('content')
<div class="text-center mb-4">
    <h3 class="fw-bold mb-1" style="color: var(--jaced-brown-dark);">Welcome Back</h3>
    <p class="text-jaced-muted small">Please login to your account</p>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 small py-2 d-flex align-items-center gap-2 mb-3" role="alert" style="border-radius: 8px;">
        <i class="bi bi-x-circle-fill"></i>
        <span>{{ session('error') }}</span>
        <button type="button" class="btn-close shadow-none small ms-auto" data-bs-dismiss="alert" aria-label="Close" style="padding: 0.75rem;"></button>
    </div>
@endif

<form action="{{ route('admin.login.auth') }}" method="POST" novalidate>
    @csrf

    <div class="mb-3">
        <label for="email" class="form-label small fw-medium mb-2">Email address</label>
        <input
            type="email"
            class="form-control input-jaced @error('email') is-invalid @enderror"
            id="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="example@gmail.com"
            required
            autofocus
        >
        @error('email')
            <div class="invalid-feedback small mt-1">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <label for="password" class="form-label small fw-medium m-0">Password</label>
        </div>
        
        <div class="input-group has-validation" style="border-radius: 8px; overflow: hidden; background-color: var(--jaced-input);">
            <input
                type="password"
                class="form-control input-jaced border-0 @error('password') is-invalid @enderror"
                id="password"
                name="password"
                placeholder="Enter your password"
                required
                style="border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important;"
            >
            <button class="btn border-0 px-3 d-flex align-items-center" type="button" id="togglePassword" title="Toggle Password Visibility" style="background-color: var(--jaced-input); color: var(--jaced-muted);">
                <i class="bi bi-eye fs-5"></i>
            </button>
            
            @error('password')
                <div class="invalid-feedback small mt-1 px-2">
                    {{ $message }}
                </div>
            @enderror
        </div>
        
        <a href="{{ route('password.request') }}" class="small text-decoration-none fw-medium" style="color: var(--jaced-caramel);">Forgot password?</a>
    </div>

    <div class="d-grid mb-3">
        <button type="submit" class="btn btn-jaced-primary fw-bold py-2.5">Login</button>
    </div>

    <div class="d-flex align-items-center my-4">
        <hr class="flex-grow-1 m-0" style="border-color: var(--jaced-input); opacity: 0.6;">
        <span class="mx-3 text-jaced-muted small" style="font-size: 12px; font-weight: 500;">or sign in with</span>
        <hr class="flex-grow-1 m-0" style="border-color: var(--jaced-input); opacity: 0.6;">
    </div>

    <div class="d-grid mb-2">
        <button type="button" class="btn btn-outline-dark fw-bold py-2.5 d-flex align-items-center justify-content-center gap-2" style="border-color: var(--jaced-input); border-radius: 8px; color: var(--jaced-brown-dark); background-color: transparent;">
            <i class="bi bi-google"></i> Sign in with Google
        </button>
    </div>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const togglePassword = document.getElementById("togglePassword");
        const passwordInput = document.getElementById("password");
        
        if (togglePassword && passwordInput) {
            const icon = togglePassword.querySelector('i');
            togglePassword.addEventListener("click", function () {
                const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
                passwordInput.setAttribute("type", type);
                
                // Mengubah icon mata coret / biasa saat diklik
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            });
        }
    });
</script>
@endsection