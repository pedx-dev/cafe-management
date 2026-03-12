@extends('layouts.app')

@section('title', 'Enter Reset Code')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7" data-aos="fade-up">
            <div class="card-cafe overflow-hidden">
                <div class="card-header bg-gradient-primary text-white text-center py-4 border-0">
                    <i class="fas fa-shield-alt fa-3x mb-3 d-block"></i>
                    <h3 class="mb-0 fw-bold">Enter Reset Code</h3>
                    <p class="mb-0 opacity-75">Use the 6-digit code sent to your email</p>
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

                    <form method="POST" action="{{ route('password.verify') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">
                                <i class="fas fa-envelope me-2 text-primary"></i>Email Address
                            </label>
                            <input id="email" type="email"
                                   class="form-control form-control-lg rounded-pill @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email', $email) }}" required autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="code" class="form-label fw-semibold">
                                <i class="fas fa-key me-2 text-primary"></i>Reset Code
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
                                   required autofocus>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-cafe w-100 py-3">
                            <i class="fas fa-check-circle me-2"></i>Verify Code
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('password.request') }}" class="text-decoration-none">
                            <i class="fas fa-redo me-1"></i>Send a new code
                        </a>
                    </div>

                    <div class="alert alert-warning mt-4 rounded-4 shadow-sm border-0">
                        <small>
                            <i class="fas fa-clock me-2"></i>
                            <strong>Note:</strong> The reset code expires in 10 minutes.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('code').addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
    });
</script>
@endpush
@endsection
