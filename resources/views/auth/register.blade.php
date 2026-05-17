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
    
                        <form action="{{ route('login.auth') }}" method="POST" id="registerForm" novalidate>
                            @csrf
    
                            <div id="step-1">
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-medium">Full Name</label>
                                    <input type="text" class="form-control form-control-lg fs-6 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="John Doe" required autofocus>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

                            {{-- <div id="step-2" class="d-none">
                                <h5 class="fw-bold mb-3">Personal Details</h5>
                                
                                <div class="mb-3">
                                    <label for="phone" class="form-label fw-medium">Phone Number</label>
                                    <input type="text" class="form-control form-control-lg fs-6 @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="08123456789" required>
                                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-8 mb-3 mb-md-0">
                                        <label for="address" class="form-label fw-medium">Street Address</label>
                                        <input type="text" class="form-control form-control-lg fs-6 @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" placeholder="123 Main St" required>
                                        @error('address') 
                                            <div class="invalid-feedback">{{ $message }}</div> 
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="postal_code" class="form-label fw-medium">Postal Code</label>
                                        <input type="text" class="form-control form-control-lg fs-6 @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" placeholder="12345" required>
                                        @error('postal_code') 
                                            <div class="invalid-feedback">{{ $message }}</div> 
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="city" class="form-label fw-medium">City</label>
                                    <input type="text" class="form-control form-control-lg fs-6 @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" placeholder="City Name" required>
                                    @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="province" class="form-label fw-medium">Province</label>
                                    <input type="text" class="form-control form-control-lg fs-6 @error('province') is-invalid @enderror" id="province" name="province" value="{{ old('province') }}" placeholder="Province Name" required>
                                    @error('province') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="country" class="form-label fw-medium">Country</label>
                                    <select class="form-select form-select-lg fs-6 @error('country') is-invalid @enderror" id="country" name="country" required>
                                        <option value="" disabled selected>Select your country</option>
                                        <option value="Indonesia" {{ old('country') == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                                        <option value="United States" {{ old('country') == 'United States' ? 'selected' : '' }}>United States</option>
                                        <option value="United Kingdom" {{ old('country') == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                                        <option value="Australia" {{ old('country') == 'Australia' ? 'selected' : '' }}>Australia</option>
                                        <option value="Canada" {{ old('country') == 'Canada' ? 'selected' : '' }}>Canada</option>
                                        <!-- Add more countries as needed -->
                                    </select>
                                    @error('country') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div> --}}                                
                            {{-- </div> --}}
    
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
            // Logic buat Form Stepper
            const step1 = document.getElementById("step-1");
            const step2 = document.getElementById("step-2");
            const btnNext = document.getElementById("btnNext");
            const btnPrev = document.getElementById("btnPrev");
            
            const indicator1 = document.getElementById("indicator-1");
            const indicator2 = document.getElementById("indicator-2");
            const line1 = document.getElementById("line-1");

            // Lanjut ke Step 2
            btnNext.addEventListener("click", function () {
                // Sembunyikan step 1, tampilkan step 2
                step1.classList.add("d-none");
                step2.classList.remove("d-none");

                // Update visual buletan & garis
                indicator2.classList.remove("bg-secondary");
                indicator2.classList.add("bg-dark");
                indicator2.style.opacity = "1";
                line1.classList.remove("bg-secondary");
                line1.classList.add("bg-dark");
                line1.style.opacity = "1";
            });

            // Kembali ke Step 1
            btnPrev.addEventListener("click", function () {
                // Sembunyikan step 2, tampilkan step 1
                step2.classList.add("d-none");
                step1.classList.remove("d-none");

                // Update visual buletan & garis
                indicator2.classList.add("bg-secondary");
                indicator2.classList.remove("bg-dark");
                indicator2.style.opacity = "0.5";
                line1.classList.add("bg-secondary");
                line1.classList.remove("bg-dark");
                line1.style.opacity = "0.3";
            });

            // Logic buat Toggle Password (Diperbaiki agar support multiple input)
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