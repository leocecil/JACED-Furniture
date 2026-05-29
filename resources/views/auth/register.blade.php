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

                        @if(session('info'))
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle me-2" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                </svg>
                                {{ session('info') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('google_data'))
                            <div class="form-text text-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 48 48" class="me-1">
                                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                                </svg>
                                Email retrieved from your Google account
                            </div>
                        @endif
    
                        <form action="{{ route('register') }}" method="POST" id="registerForm" novalidate>
                            @csrf
    
                            <div id="step-1">
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-medium">Full Name</label>
                                    <input type="text" 
                                        class="form-control form-control-lg fs-6 @error('name') is-invalid @enderror" 
                                        id="name" name="name" 
                                        value="{{ old('name', session('google_data.name')) }}" 
                                        placeholder="John Doe" required autofocus>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone_number" class="form-label fw-medium">Phone Number</label>
                                    <input type="text" class="form-control form-control-lg fs-6 @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="081234567890" required>
                                    @error('phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
    
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-medium">Email address</label>
                                    <input type="email" 
                                        class="form-control form-control-lg fs-6 @error('email') is-invalid @enderror
                                            {{ session('google_data') ? 'bg-light' : '' }}" 
                                        id="email" name="email" 
                                        value="{{ old('email', session('google_data.email')) }}" 
                                        placeholder="example@gmail.com" 
                                        {{ session('google_data') ? 'readonly' : '' }} required>
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