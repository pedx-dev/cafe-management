<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Café Delight - Modern Coffee Experience')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #6F4E37;
            --secondary-color: #A0826D;
            --accent-color: #D4A574;
            --dark-color: #2C2416;
            --light-color: #F8F5F2;
            --success-color: #10B981;
            --warning-color: #F59E0B;
            --danger-color: #EF4444;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-color);
            padding-top: 80px;
            overflow-x: hidden;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
        }
        
        /* Modern Navbar */
        .navbar-cafe {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-bottom: 2px solid var(--accent-color);
        }
        
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--primary-color) !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .navbar-brand i {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2rem;
        }
        
        .nav-link {
            color: var(--dark-color) !important;
            font-weight: 500;
            padding: 0.8rem 1.2rem !important;
            border-radius: 12px;
            margin: 0 4px;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            bottom: 5px;
            left: 50%;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            transition: all 0.3s ease;
            transform: translateX(-50%);
            border-radius: 2px;
        }
        
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 80%;
        }
        
        .nav-link:hover {
            color: var(--primary-color) !important;
            transform: translateY(-2px);
        }
        
        /* Modern Buttons */
        .btn-cafe {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white !important;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(111, 78, 55, 0.2);
            position: relative;
            overflow: hidden;
            isolation: isolate;
        }
        
        .btn-cafe::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--accent-color), var(--primary-color));
            transition: left 0.3s ease;
            z-index: -1;
        }
        
        .btn-cafe:hover::before {
            left: 0;
        }
        
        .btn-cafe:hover {
            color: white !important;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(111, 78, 55, 0.3);
        }
        
        .btn-cafe i,
        .btn-cafe span,
        .btn-cafe * {
            position: relative;
            z-index: 1;
        }
        
        /* Modern Cards */
        .card-cafe {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.4s ease;
            overflow: hidden;
            background: white;
        }
        
        .card-cafe:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }
        
        .card-cafe .card-img-top {
            transition: transform 0.5s ease;
        }
        
        .card-cafe:hover .card-img-top {
            transform: scale(1.1);
        }
        
        /* Glass Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        /* Modern Footer */
        .footer {
            background: linear-gradient(135deg, var(--dark-color), var(--primary-color));
            color: white;
            padding: 3rem 0 1rem;
            margin-top: 5rem;
            position: relative;
        }
        
        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color), var(--warning-color), var(--accent-color));
        }
        
        /* Cart Badge */
        .cart-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            background: linear-gradient(135deg, var(--danger-color), #DC2626);
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }
        
        /* Dropdown Modern */
        .dropdown-menu {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            padding: 0.5rem;
            margin-top: 0.5rem;
        }
        
        .dropdown-item {
            border-radius: 10px;
            padding: 0.7rem 1rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .dropdown-item:hover {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            transform: translateX(5px);
        }
        
        /* Mobile Toggle */
        .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%236F4E37' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        /* Utility Classes */
        .text-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        }
        
        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--light-color);
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: 5px;
        }

        /* Pagination */
        .pagination {
            gap: 6px;
        }

        .pagination .page-link {
            font-size: 0.9rem;
            padding: 0.35rem 0.65rem;
            border-radius: 10px;
            color: var(--primary-color);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-color: transparent;
            color: #fff;
        }

        /* Button Text Fix - Ensure all button content is visible */
        .btn {
            position: relative;
        }

        .btn-outline-primary,
        .btn-outline-secondary,
        .btn-outline-success,
        .btn-outline-danger,
        .btn-outline-warning,
        .btn-outline-info {
            z-index: 1;
        }

        /* Form Controls */
        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(111, 78, 55, 0.15);
        }

        /* Badge fixes */
        .badge {
            font-weight: 500;
        }

        /* Alert improvements */
        .alert {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .alert-danger {
            background: linear-gradient(135deg, #fff5f5, #ffe3e3);
            color: #c53030;
        }

        .alert-success {
            background: linear-gradient(135deg, #f0fff4, #c6f6d5);
            color: #22543d;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fffaf0, #feebc8);
            color: #744210;
        }

        .alert-info {
            background: linear-gradient(135deg, #ebf8ff, #bee3f8);
            color: #2a4365;
        }

        /* Card header gradient text fix */
        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color)) !important;
        }

        .bg-gradient-primary * {
            color: white;
        }

        /* Table improvements */
        .table > :not(caption) > * > * {
            padding: 1rem 0.75rem;
        }

        /* Input group text */
        .input-group-text {
            background-color: #f8f9fa;
            border-color: #ced4da;
        }

        /* ===== FORM VALIDATION ERROR STYLING ===== */
        .is-invalid {
            border-color: #dc3545 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(.375em + .1875rem) center;
            background-size: calc(.75em + .375rem) calc(.75em + .375rem);
        }

        .invalid-feedback {
            display: none;
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            padding: 0.5rem 0.75rem;
            background-color: #fff5f5;
            border-radius: 8px;
            border-left: 3px solid #dc3545;
        }

        .is-invalid ~ .invalid-feedback,
        .is-invalid ~ .text-danger {
            display: block;
        }

        .text-danger.small {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
            background-color: #fff5f5;
            border-radius: 8px;
            border-left: 3px solid #dc3545;
            margin-top: 0.5rem;
        }

        /* ===== BUTTON CONTENT FIX ===== */
        /* Ensure all button text/content is visible */
        .btn {
            position: relative;
            z-index: 1;
        }

        /* Button outline variants - ensure text visibility */
        .btn-outline-primary,
        .btn-outline-secondary,
        .btn-outline-success,
        .btn-outline-danger,
        .btn-outline-warning,
        .btn-outline-info,
        .btn-outline-dark,
        .btn-outline-light {
            position: relative;
            z-index: 1;
        }

        /* Fix for input-group buttons */
        .input-group .btn {
            z-index: 2;
        }

        .input-group .btn:focus {
            z-index: 3;
        }

        /* Form check improvements */
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(111, 78, 55, 0.25);
        }

        /* Link styling */
        a.text-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            transition: all 0.3s ease;
        }

        a.text-gradient:hover {
            filter: brightness(1.2);
        }

        /* Card animations */
        .card-cafe .card-body {
            position: relative;
            z-index: 1;
        }

        /* Badge improvements */
        .badge.rounded-pill {
            padding: 0.5em 1em;
        }

        .badge.bg-success {
            background-color: #22c55e !important;
        }

        .badge.bg-warning {
            background-color: #eab308 !important;
            color: #1a1a1a !important;
        }

        .badge.bg-danger {
            background-color: #ef4444 !important;
        }

        .badge.bg-info {
            background-color: #06b6d4 !important;
        }

        .badge.bg-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color)) !important;
        }

        /* Status badges */
        .status-badge {
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Pill buttons text fix */
        .rounded-pill.btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        /* Card overlay fix */
        .card-img-overlay {
            z-index: 1;
        }

        /* Dropdown fixes */
        .dropdown-menu {
            z-index: 1050;
        }

        .dropdown-toggle::after {
            margin-left: 0.5rem;
        }

        /* Progress bar improvements */
        .progress {
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        }

        /* Empty state styling */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--accent-color);
            margin-bottom: 1.5rem;
        }

        .empty-state h4 {
            color: var(--dark-color);
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: #6c757d;
            margin-bottom: 1.5rem;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div id="app">
        <!-- Navigation Bar -->
        <nav class="navbar navbar-expand-lg navbar-cafe fixed-top">
            <div class="container">
                <!-- Brand -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fas fa-coffee me-2"></i>Café Delight
                </a>
                
                <!-- Mobile Toggle -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- Navigation Links -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            @if(auth()->user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.menu.index') }}">
                                        <i class="fas fa-utensils me-1"></i>Menu
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.orders.index') }}">
                                        <i class="fas fa-shopping-cart me-1"></i>Orders
                                    </a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('menu') }}">
                                        <i class="fas fa-coffee me-1"></i>Menu
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('cart.index') }}">
                                        <i class="fas fa-shopping-cart me-1"></i>Cart
                                        @php
                                            $cartCount = auth()->user()->carts()->count();
                                        @endphp
                                        @if($cartCount > 0)
                                            <span class="cart-badge">{{ $cartCount }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('orders.index') }}">
                                        <i class="fas fa-history me-1"></i>History
                                    </a>
                                </li>
                            @endif
                        @endauth
                    </ul>
                    
                    <!-- Right Side -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt me-1"></i>Login
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">
                                    <i class="fas fa-user-plus me-1"></i>Register
                                </a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                            <i class="fas fa-user-edit me-2"></i>Profile
                                        </a>
                                    </li>
                                    @if(auth()->user()->isAdmin())
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                                <i class="fas fa-cog me-2"></i>Admin Panel
                                            </a>
                                        </li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}" 
                                           onclick="event.preventDefault(); confirmLogout();">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="py-4">
            @yield('content')
        </main>

       

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });

        // Account success alert for authentication actions
        @if(session('account_success'))
            Swal.fire({
                title: {!! json_encode(session('account_success_title', 'Account Success')) !!},
                text: {!! json_encode(session('account_success')) !!},
                icon: 'success',
                confirmButtonColor: '#6F4E37',
                confirmButtonText: '<i class="fas fa-check me-2"></i>Continue',
                timer: 4500,
                timerProgressBar: true,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        @endif
        
        // Logout Confirmation
        function confirmLogout() {
            Swal.fire({
                title: 'Logout Confirmation',
                text: 'Are you sure you want to logout?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6F4E37',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-sign-out-alt me-2"></i>Yes, Logout',
                cancelButtonText: '<i class="fas fa-times me-2"></i>Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'border-0 shadow-lg',
                    confirmButton: 'btn-cafe',
                    cancelButton: 'btn btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Logging out...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('logout-form').submit();
                }
            });
        }

        // Auto-dismiss alerts
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
        
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-cafe');
            if (window.scrollY > 50) {
                navbar.style.boxShadow = '0 8px 40px rgba(0, 0, 0, 0.12)';
            } else {
                navbar.style.boxShadow = '0 4px 30px rgba(0, 0, 0, 0.08)';
            }
        });
    </script>
    
    @yield('page-scripts')
    @stack('scripts')
</body>
</html>