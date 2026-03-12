@extends('layouts.app')

@section('title', 'Set New Password')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7" data-aos="fade-up">
            <div class="card-cafe overflow-hidden">
                <div class="card-header bg-gradient-primary text-white text-center py-4 border-0">
                    <i class="fas fa-lock fa-3x mb-3 d-block"></i>
                    <h3 class="mb-0 fw-bold">Set New Password</h3>
                    <p class="mb-0 opacity-75">Choose a strong new password</p>
                </div>

                <div class="card-body p-4">
                    @if(session('status') && !session('account_success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">
                                <i class="fas fa-envelope me-2 text-primary"></i>Email Address
                            </label>
                            <input id="email" type="email"
                                   class="form-control form-control-lg rounded-pill" name="email"
                                   value="{{ $email ?? '' }}" readonly>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">
                                <i class="fas fa-lock me-2 text-primary"></i>New Password
                            </label>
                            <input id="password" type="password"
                                   class="form-control form-control-lg rounded-pill @error('password') is-invalid @enderror"
                                   name="password" required autocomplete="new-password"
                                   placeholder="Enter a new password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="form-label fw-semibold">
                                <i class="fas fa-check-circle me-2 text-primary"></i>Confirm Password
                            </label>
                            <input id="password-confirm" type="password"
                                   class="form-control form-control-lg rounded-pill"
                                   name="password_confirmation" required autocomplete="new-password"
                                   placeholder="Re-enter your new password">
                        </div>

                        <button type="submit" class="btn btn-cafe w-100 py-3">
                            <i class="fas fa-save me-2"></i>Reset Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
