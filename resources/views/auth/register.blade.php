@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8" data-aos="fade-up">
            <div class="card-cafe overflow-hidden">
                <div class="card-header bg-gradient-primary text-white text-center py-4 border-0">
                    <i class="fas fa-user-plus fa-3x mb-3 d-block"></i>
                    <h3 class="mb-0 fw-bold">Join Café Delight</h3>
                    <p class="mb-0 opacity-75">Create your account today</p>
                </div>

                <div class="card-body p-4">
                    <!-- Google Sign Up -->
                    <div class="text-center mb-4">
                        <a href="{{ route('auth.google') }}" class="btn btn-outline-danger btn-lg w-100 py-3 rounded-pill shadow-sm">
                            <i class="fab fa-google fa-lg me-2"></i>Sign up with Google
                        </a>
                        <div class="my-4">
                            <span class="text-muted">— OR REGISTER WITH EMAIL —</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">
                                <i class="fas fa-user me-2 text-primary"></i>Full Name
                            </label>
                            <input id="name" type="text" 
                                   class="form-control form-control-lg rounded-pill @error('name') is-invalid @enderror" 
                                   name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                                   placeholder="Enter your full name">
                            @error('name')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">
                                <i class="fas fa-envelope me-2 text-primary"></i>Email Address
                            </label>
                            <input id="email" type="email" 
                                   class="form-control form-control-lg rounded-pill @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required autocomplete="email"
                                   placeholder="your.email@example.com">
                            @error('email')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-semibold">
                                <i class="fas fa-phone me-2 text-primary"></i>Phone Number
                            </label>
                            <input id="phone" type="text" 
                                   class="form-control form-control-lg rounded-pill @error('phone') is-invalid @enderror" 
                                   name="phone" value="{{ old('phone') }}" required autocomplete="phone"
                                   placeholder="+1 (555) 000-0000">
                            @error('phone')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">
                                <i class="fas fa-lock me-2 text-primary"></i>Password
                            </label>
                            <div class="input-group input-group-lg">
                                <input id="password" type="password" 
                                       class="form-control rounded-start-pill @error('password') is-invalid @enderror" 
                                       name="password" required autocomplete="new-password"
                                       placeholder="Create a strong password">
                                <button class="btn btn-outline-secondary rounded-end-pill" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="form-label fw-semibold">
                                <i class="fas fa-lock me-2 text-primary"></i>Confirm Password
                            </label>
                            <div class="input-group input-group-lg">
                                <input id="password-confirm" type="password" 
                                       class="form-control rounded-start-pill" 
                                       name="password_confirmation" required autocomplete="new-password"
                                       placeholder="Confirm your password">
                                <button class="btn btn-outline-secondary rounded-end-pill" type="button" id="togglePasswordConfirm">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" class="text-decoration-none fw-semibold">Terms & Conditions</a>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-cafe w-100 py-3 mb-3">
                            <span><i class="fas fa-user-plus me-2"></i>Create Account</span>
                        </button>

                        <div class="text-center mt-4">
                            <p class="text-muted mb-0">
                                Already have an account? 
                                <a href="{{ route('login') }}" class="text-decoration-none fw-semibold text-gradient">
                                    Login Here
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
        const passwordConfirmInput = document.getElementById('password-confirm');
        const icon = this.querySelector('i');
        
        if (passwordConfirmInput.type === 'password') {
            passwordConfirmInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordConfirmInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>
@endsection
