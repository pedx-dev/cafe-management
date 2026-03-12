@extends('layouts.app')

@section('title', 'My Profile - Café Delight')

@section('content')
<div class="container">
    <!-- Header Section -->
    <div class="row mb-5" data-aos="fade-down">
        <div class="col-12">
            <a href="{{ route('menu') }}" class="btn btn-outline-secondary rounded-pill mb-3">
                <i class="fas fa-arrow-left me-2"></i>Back to Menu
            </a>
            <div class="text-center">
                <h1 class="display-5 fw-bold text-gradient mb-1">
                    <i class="fas fa-user-circle me-2"></i>My Profile
                </h1>
                <p class="text-muted">Manage your account settings and preferences</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" data-aos="fade-in">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Profile Sidebar -->
            <div class="col-lg-4 mb-4" data-aos="fade-right">
                <div class="card-cafe text-center overflow-hidden">
                    <div class="bg-gradient-primary py-5">
                        <div class="position-relative d-inline-block">
                            <img src="{{ asset('storage/profiles/' . auth()->user()->profile_image) }}" 
                                 alt="Profile" class="rounded-circle border border-4 border-white shadow-lg profile-avatar" 
                                 id="profilePreview"
                                 width="150" height="150" style="object-fit: cover;">
                            <label for="profile_image" class="btn btn-light btn-sm rounded-circle position-absolute shadow" 
                                   style="bottom: 5px; right: 5px; width: 40px; height: 40px; cursor: pointer;">
                                <i class="fas fa-camera text-primary"></i>
                            </label>
                            <input type="file" id="profile_image" name="profile_image" 
                                   class="d-none" accept="image/*">
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <h4 class="fw-bold mb-1">{{ auth()->user()->name }}</h4>
                        <p class="text-muted mb-3">
                            <i class="fas fa-envelope me-1"></i>{{ auth()->user()->email }}
                        </p>
                        <div class="d-flex justify-content-center gap-3 mb-3">
                            <div class="text-center">
                                <div class="bg-light rounded-3 px-4 py-3">
                                    <i class="fas fa-gift fa-2x text-primary mb-2"></i>
                                    <h5 class="mb-0 fw-bold">{{ auth()->user()->loyalty_points }}</h5>
                                    <small class="text-muted">Points</small>
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="bg-light rounded-3 px-4 py-3">
                                    <i class="fas fa-shopping-bag fa-2x text-success mb-2"></i>
                                    <h5 class="mb-0 fw-bold">{{ auth()->user()->orders()->count() ?? 0 }}</h5>
                                    <small class="text-muted">Orders</small>
                                </div>
                            </div>
                        </div>
                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                            <i class="fas fa-calendar me-1"></i>Member since {{ auth()->user()->created_at->format('M Y') }}
                        </span>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="card-cafe mt-4">
                    <div class="card-header bg-light">
                        <h6 class="fw-bold mb-0">
                            <i class="fas fa-bolt me-2 text-warning"></i>Quick Links
                        </h6>
                    </div>
                    <div class="list-group list-group-flush rounded-bottom">
                        <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-history me-3 text-muted"></i>
                            Order History
                            <i class="fas fa-chevron-right ms-auto text-muted"></i>
                        </a>
                        <a href="{{ route('cart.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-shopping-cart me-3 text-muted"></i>
                            My Cart
                            <i class="fas fa-chevron-right ms-auto text-muted"></i>
                        </a>
                        <a href="{{ route('menu') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-coffee me-3 text-muted"></i>
                            Browse Menu
                            <i class="fas fa-chevron-right ms-auto text-muted"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Form Section -->
            <div class="col-lg-8 mb-4" data-aos="fade-left">
                <!-- Personal Information -->
                <div class="card-cafe mb-4">
                    <div class="card-header bg-gradient-primary text-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-user me-2"></i>Personal Information
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-bold">
                                    <i class="fas fa-user me-2 text-primary"></i>Full Name
                                </label>
                                <input id="name" type="text" 
                                       class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name', auth()->user()->name) }}" required
                                       placeholder="Enter your full name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-bold">
                                    <i class="fas fa-envelope me-2 text-primary"></i>Email Address
                                </label>
                                <input id="email" type="email" 
                                       class="form-control form-control-lg rounded-3 @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email', auth()->user()->email) }}" required
                                       placeholder="Enter your email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-bold">
                                    <i class="fas fa-phone me-2 text-primary"></i>Phone Number
                                </label>
                                <input id="phone" type="text" 
                                       class="form-control form-control-lg rounded-3 @error('phone') is-invalid @enderror" 
                                       name="phone" value="{{ old('phone', auth()->user()->phone) }}" required
                                       placeholder="Enter your phone number">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="loyalty_points" class="form-label fw-bold">
                                    <i class="fas fa-gift me-2 text-primary"></i>Loyalty Points
                                </label>
                                <div class="input-group">
                                    <input id="loyalty_points" type="text" 
                                           class="form-control form-control-lg rounded-start" 
                                           value="{{ auth()->user()->loyalty_points }}" readonly>
                                    <span class="input-group-text bg-success text-white rounded-end">
                                        <i class="fas fa-star"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="address" class="form-label fw-bold">
                                    <i class="fas fa-map-marker-alt me-2 text-primary"></i>Delivery Address
                                </label>
                                <textarea id="address" 
                                          class="form-control form-control-lg rounded-3 @error('address') is-invalid @enderror" 
                                          name="address" rows="3"
                                          placeholder="Enter your default delivery address...">{{ old('address', auth()->user()->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Password Section -->
                <div class="card-cafe mb-4">
                    <div class="card-header bg-light py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-lock me-2 text-primary"></i>Change Password
                            </h5>
                            <span class="badge bg-secondary rounded-pill">Optional</span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            Leave password fields empty if you don't want to change your password.
                        </div>
                        
                        <div class="row g-4">
                            <div class="col-12">
                                <label for="current_password" class="form-label fw-bold">
                                    <i class="fas fa-key me-2 text-primary"></i>Current Password
                                </label>
                                <div class="input-group">
                                    <input id="current_password" type="password" 
                                           class="form-control form-control-lg rounded-start @error('current_password') is-invalid @enderror" 
                                           name="current_password"
                                           placeholder="Enter current password">
                                    <button class="btn btn-outline-secondary rounded-end toggle-password" type="button" 
                                            data-target="current_password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="new_password" class="form-label fw-bold">
                                    <i class="fas fa-lock me-2 text-primary"></i>New Password
                                </label>
                                <div class="input-group">
                                    <input id="new_password" type="password" 
                                           class="form-control form-control-lg rounded-start @error('new_password') is-invalid @enderror" 
                                           name="new_password"
                                           placeholder="Enter new password">
                                    <button class="btn btn-outline-secondary rounded-end toggle-password" type="button" 
                                            data-target="new_password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('new_password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="new_password_confirmation" class="form-label fw-bold">
                                    <i class="fas fa-check-circle me-2 text-primary"></i>Confirm New Password
                                </label>
                                <div class="input-group">
                                    <input id="new_password_confirmation" type="password" 
                                           class="form-control form-control-lg rounded-start" 
                                           name="new_password_confirmation"
                                           placeholder="Confirm new password">
                                    <button class="btn btn-outline-secondary rounded-end toggle-password" type="button" 
                                            data-target="new_password_confirmation">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-cafe btn-lg flex-grow-1 rounded-pill py-3">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                    <a href="{{ route('menu') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.profile-avatar {
    transition: transform 0.3s ease;
}

.profile-avatar:hover {
    transform: scale(1.05);
}

.list-group-item {
    padding: 1rem 1.25rem;
    border-left: none;
    border-right: none;
    transition: all 0.3s ease;
}

.list-group-item:hover {
    background-color: rgba(111, 78, 55, 0.05);
    padding-left: 1.5rem;
}

.toggle-password {
    border-color: #ced4da;
}

.toggle-password:hover {
    background-color: #f8f9fa;
}
</style>

@push('scripts')
<script>
    // Profile image preview
    document.getElementById('profile_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profilePreview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
    
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
</script>
@endpush
@endsection