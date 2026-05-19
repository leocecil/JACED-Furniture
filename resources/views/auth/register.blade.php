@extends('base.base')

@section('content')
    <div class="container my-5 mx-auto">
        <div class="row justify-content-center">
            <div class="col-md-5">
    
                <div class="card shadow border-0 rounded-4">
                    <div class="card-header text-center bg-transparent border-0 pt-4 pb-0">
                        <h3 class="fw-bold mb-1" style="color: #1F2937;">Get Started</h3>
                        <p class="text-muted small">Create your account</p>
                    </div>
                    
                    <div class="card-body p-4 pt-3">
    
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
    
                        <form action="{{ route('register') }}" method="POST" id="registerForm" novalidate>
                            @csrf
    
                            <div id="step-1">
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-medium">Full Name</label>
                                    <input type="text" class="form-control form-control-lg fs-6 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="John Doe" required autofocus>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone_number" class="form-label fw-medium">Phone Number</label>
                                    <input type="text" class="form-control form-control-lg fs-6 @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="+1234567890" required>
                                    @error('phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
    
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-medium">Email address</label>
                                    <input type="email" class="form-control form-control-lg fs-6 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="example@gmail.com" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
        
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-medium">Password</label>
                                    <div class="input-group input-group-lg has-validation">
                                        <input type="password" class="form-control fs-6 @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter your password" required>
                                        <button class="btn btn-outline-secondary px-3 toggle-password" type="button" data-target="password">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>
                                        </button>
                                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
    
                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label fw-medium">Confirm Password</label>
                                    <div class="input-group input-group-lg has-validation">
                                        <input type="password" class="form-control fs-6 @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required>
                                        <button class="btn btn-outline-secondary px-3 toggle-password" type="button" data-target="password_confirmation">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>
                                        </button>
                                        @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-dark btn-lg fs-6 fw-bold w-100">Register</button>
                            </div>
    
                            <div class="text-center mt-4">
                                <p class="text-muted mb-0">Already have an account? <a href="{{ route('login') }}" class="text-decoration-none fw-medium" style="color: #1F2937;">Login here</a></p>
                            </div>
    
                        </form>
    
                    </div>
                </div>
    
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggleButtons = document.querySelectorAll(".toggle-password");
            
            toggleButtons.forEach(button => {
                button.addEventListener("click", function () {
                    const targetId = this.getAttribute("data-target");
                    const passwordInput = document.getElementById(targetId);
                    
                    if (passwordInput) {
                        const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
                        passwordInput.setAttribute("type", type);
                    }
                });
            });
        });
    </script>
@endsection