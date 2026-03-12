@extends('layouts.app')

@section('title', 'Enter Verification Code')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7" data-aos="fade-up">
            <div class="card-cafe overflow-hidden">
                <div class="card-header bg-gradient-primary text-white text-center py-4 border-0">
                    <i class="fas fa-shield-alt fa-3x mb-3 d-block"></i>
                    <h3 class="mb-0 fw-bold">Verify Your Email</h3>
                    <p class="mb-0 opacity-75">Enter the 6-digit code we sent you</p>
                </div>

                <div class="card-body p-4">
                    @if (session('success') && !session('account_success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <p class="text-muted mb-1">We sent a verification code to:</p>
                        <p class="fw-bold text-dark">{{ auth()->user()->email }}</p>
                    </div>

                    <form method="POST" action="{{ route('verification.code.verify') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="code" class="form-label fw-semibold">
                                <i class="fas fa-key me-2"></i>Verification Code
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg text-center @error('code') is-invalid @enderror" 
                                   id="code" 
                                   name="code" 
                                   placeholder="000000"
                                   maxlength="6"
                                   pattern="[0-9]{6}"
                                   inputmode="numeric"
                                   autocomplete="one-time-code"
                                   style="font-size: 32px; letter-spacing: 10px; font-weight: bold;"
                                   required 
                                   autofocus>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-cafe btn-lg py-3">
                                <i class="fas fa-check-circle me-2"></i>Verify Email
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="text-muted mb-3">Didn't receive the code?</p>
                        <form method="POST" action="{{ route('verification.code.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-redo me-2"></i>Resend Code
                            </button>
                        </form>
                    </div>

                    <form class="mt-3 d-grid gap-2" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout & Try Another Account
                        </button>
                    </form>

                    <div class="alert alert-warning mt-4 rounded-4 shadow-sm border-0" data-aos="fade-up">
                        <small>
                            <i class="fas fa-clock me-2"></i>
                            <strong>Note:</strong> The verification code expires in 10 minutes. Check your spam folder if you don't see the email.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-focus and format code input
    document.getElementById('code').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
    });
</script>
@endpush
@endsection
