@extends('layouts.app')

@section('title', 'Verify Email Address')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8" data-aos="fade-up">
            <div class="card-cafe overflow-hidden">
                <div class="card-header bg-gradient-primary text-white text-center py-4 border-0">
                    <i class="fas fa-envelope fa-3x mb-3 d-block"></i>
                    <h3 class="mb-0 fw-bold">Verify Your Email</h3>
                    <p class="mb-0 opacity-75">One last step to secure your account</p>
                </div>

                <div class="card-body p-4">
                    @if (session('resent'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Success!</strong> A fresh verification link has been sent to your email address.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <div class="verification-icon mb-4">
                            <i class="fas fa-inbox fa-5x text-primary"></i>
                        </div>
                        <h4 class="fw-bold mb-2">Check Your Email</h4>
                        <p class="text-muted mb-3">
                            We've sent a verification link to:<br>
                            <strong class="text-dark">{{ auth()->user()->email }}</strong>
                        </p>
                        <div class="alert alert-info bg-light border-0 text-dark">
                            <small>
                                <i class="fas fa-lightbulb me-2"></i>
                                <strong>Tip:</strong> Please check your spam/junk folder if you don't see the email in your inbox.
                            </small>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <form method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-cafe btn-lg py-3">
                                <i class="fas fa-redo me-2"></i>Resend Verification Email
                            </button>
                        </form>
                    </div>

                    <form class="mt-3 d-grid gap-2" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-lg py-3">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout & Try Another Account
                        </button>
                    </form>

                    <!-- Info Alert -->
                    <div class="alert alert-info mt-4 rounded-4 shadow-sm border-0 bg-light" data-aos="fade-up">
                        <h6 class="mb-2 text-dark">
                            <i class="fas fa-info-circle me-2"></i>Need Help?
                        </h6>
                        <p class="mb-0 small text-dark">
                            The verification email may take a few minutes to arrive. If you don't see it after 5 minutes, check your spam folder or request a new link above.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection