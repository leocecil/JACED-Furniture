@extends('base.base')

@section('content')
<style>
    /* 1. Paksa gambar background naik full ke atas melewati navbar */
    .login-wrapper {
        background: url('https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=1920&auto=format&fit=crop') no-repeat center center fixed;
        background-size: cover;
        position: fixed; /* Mengunci background full layar */
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000; /* Menimpa sisa layout bawah */
    }

    /* 2. Manipulasi Navbar bawaan biar transparan dan melayang di atas background */
    header, nav, .navbar {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        background-color: transparent !important; /* Hilangin warna krem/putih navbar */
        border: none !important;
        z-index: 1001 !important; /* Harus lebih tinggi dari login-wrapper */
    }

    /* Overlay gelap tipis agar form kaca makin kelihatan stand out */
    .login-wrapper::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.15);
        z-index: -1;
    }

    .login-container {
        width: 100%;
        max-width: 500px; /* Batasi lebar form biar pas di tengah */
        padding: 15px;
    }

    /* 3. Efek Glassmorphic Premium */
    .glass-card {
        background: rgba(255, 255, 255, 0.45) !important;
        backdrop-filter: blur(15px) saturate(160%);
        -webkit-backdrop-filter: blur(15px) saturate(160%);
        border: 1px solid rgba(255, 255, 255, 0.45) !important;
        box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.15) !important;
    }

    .glass-card .form-control {
        background: rgba(255, 255, 255, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.5);
    }
    
    .glass-card .form-control:focus {
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 0 0 0 0.25rem rgba(31, 41, 55, 0.15);
        border-color: #1F2937;
    }

    .glass-card .btn-outline-secondary {
        background: rgba(255, 255, 255, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-left: none;
    }
</style>

<div class="login-wrapper">
    <div class="login-container mx-auto">
    
        <div class="card glass-card border-0 rounded-4">
            <div class="card-header text-center bg-transparent border-0 pt-4 pb-0">
                <h3 class="fw-bold mb-1" style="color: #1F2937;">Welcome Back</h3>
                <p class="text-muted small">Please login to your account</p>
            </div>
            <div class="card-body p-4 pt-3">

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('login.auth') }}" method="POST" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label fw-medium">Email address</label>
                        <input
                            type="email"
                            class="form-control form-control-lg fs-6 @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="example@gmail.com"
                            required
                            autofocus
                        >
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-medium">Password</label>
                        <div class="input-group input-group-lg has-validation">
                            <input
                                type="password"
                                class="form-control fs-6 @error('password') is-invalid @enderror"
                                id="password"
                                name="password"
                                placeholder="Enter your password"
                                required
                            >
                            <button class="btn btn-outline-secondary px-3" type="button" id="togglePassword" title="Toggle Password Visibility">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                </svg>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-text text-end">
                            <a href="#" class="text-decoration-none fw-medium" style="color: #1F2937;">Forgot password?</a>
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-dark btn-lg fs-6 fw-bold">Login</button>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <hr class="flex-grow-1" style="border-color: rgba(0,0,0,0.15);">
                        <span class="mx-2 text-muted small">or sign in with</span>
                        <hr class="flex-grow-1" style="border-color: rgba(0,0,0,0.15);">
                    </div>

                    <div class="d-grid mb-3">
                        <a href="{{ route('auth.google') }}" class="btn btn-outline-dark btn-lg fs-6 fw-bold w-100" style="background: rgba(255,255,255,0.25);">
                            <i class="fab fa-google me-2"></i> Sign in with Google
                        </a>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-muted mb-0">Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none fw-medium" style="color: #1F2937;">Sign up here</a></p>
                    </div>

                </form>

            </div>
        </div>
    
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const togglePassword = document.getElementById("togglePassword");
        const passwordInput = document.getElementById("password");

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener("click", function () {
                const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
                passwordInput.setAttribute("type", type);
            });
        }
    });
</script>
@endsection