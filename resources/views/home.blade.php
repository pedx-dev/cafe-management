@extends('layouts.app')

@section('title', 'Welcome to Café Delight - Modern Coffee Experience')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="hero-section text-center py-5 mb-5 rounded-4 position-relative overflow-hidden" 
         style="background: linear-gradient(135deg, var(--primary-color), var(--accent-color)); color: white;"
         data-aos="fade-down">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=1600') center/cover; opacity: 0.2;"></div>
        <div class="position-relative z-1 py-4">
            <span class="badge bg-light text-dark px-3 py-2 rounded-pill mb-3" data-aos="fade-up" data-aos-delay="100">
                Freshly Brewed • Locally Loved
            </span>
            <h1 class="display-3 fw-bold mb-3 text-white" data-aos="fade-up" data-aos-delay="200">
                <i class="fas fa-mug-hot me-3"></i>Your Daily Coffee Ritual
            </h1>
            <p class="lead mb-4 fs-4" data-aos="fade-up" data-aos-delay="300">
                Crafted beans, cozy vibes, and pastries baked to perfection.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap" data-aos="zoom-in" data-aos-delay="400">
                <a href="{{ route('menu') }}" class="btn btn-light btn-lg px-5 py-3 rounded-pill shadow-lg">
                    <i class="fas fa-coffee me-2"></i>Browse Menu
                </a>
                <a href="{{ route('menu') }}?category=Coffee" class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill">
                    <i class="fas fa-seedling me-2"></i>Try Signature Brews
                </a>
            </div>
        </div>
    </div>

    <!-- Highlights -->
    <div class="row mb-5" data-aos="fade-up">
        <div class="col-lg-4 mb-4">
            <div class="card-cafe p-4 h-100">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-gradient-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                        <i class="fas fa-seedling text-white"></i>
                    </div>
                    <h5 class="ms-3 mb-0">Bean-to-Cup Quality</h5>
                </div>
                <p class="text-muted mb-0">Single-origin beans, roasted weekly for peak flavor.</p>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card-cafe p-4 h-100">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-gradient-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                        <i class="fas fa-bread-slice text-white"></i>
                    </div>
                    <h5 class="ms-3 mb-0">Fresh-Baked Daily</h5>
                </div>
                <p class="text-muted mb-0">Pastries and desserts made in-house every morning.</p>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card-cafe p-4 h-100">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-gradient-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                        <i class="fas fa-truck-fast text-white"></i>
                    </div>
                    <h5 class="ms-3 mb-0">Fast & Reliable</h5>
                </div>
                <p class="text-muted mb-0">Quick prep and smooth delivery you can count on.</p>
            </div>
        </div>
    </div>

    <!-- Featured Items -->
    <div class="row mb-5">
        <div class="col-12 text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 text-gradient mb-2">
                <i class="fas fa-star me-2"></i>Featured Selections
            </h2>
            <p class="text-muted fs-5">Handpicked favorites from our premium collection</p>
        </div>
        @foreach($featuredItems as $index => $item)
        <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
            <div class="card-cafe h-100 position-relative">
                <div class="position-relative overflow-hidden" style="height: 250px;">
                    <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top w-100 h-100" 
                         alt="{{ $item->name }}" style="object-fit: cover;">
                    @if($item->is_featured)
                        <span class="position-absolute top-0 end-0 badge bg-warning text-dark px-3 py-2 m-3 rounded-pill shadow">
                            <i class="fas fa-star me-1"></i>Featured
                        </span>
                    @endif
                    <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill">{{ $item->category }}</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <h5 class="card-title mb-2 text-dark">{{ $item->name }}</h5>
                    <p class="card-text text-muted small mb-3">{{ Str::limit($item->description, 100) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h4 mb-0 text-gradient fw-bold">₱{{ number_format($item->price, 2) }}</span>
                        <a href="{{ route('menu.show', $item->id) }}" class="btn btn-cafe btn-sm px-4 rounded-pill">
                            <i class="fas fa-eye me-1"></i>View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Categories -->
    <div class="row mb-5">
        <div class="col-12 text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 text-gradient mb-2">
                <i class="fas fa-th-large me-2"></i>Explore Categories
            </h2>
            <p class="text-muted fs-5">Discover our diverse menu offerings</p>
        </div>
        @foreach($categories as $index => $category)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-aos="zoom-in" data-aos-delay="{{ $index * 100 }}">
            <a href="{{ route('menu') }}?category={{ urlencode($category) }}" class="text-decoration-none">
                <div class="card-cafe text-center p-4 h-100 bg-gradient-primary position-relative overflow-hidden" style="min-height: 200px;">
                    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" 
                         style="background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><circle cx=\"50\" cy=\"50\" r=\"40\" fill=\"white\"/></svg> </div>
                    <div class="position-relative">
                        <i class="fas fa-{{ $category == 'Coffee' ? 'mug-hot' : ($category == 'Tea' ? 'leaf' : ($category == 'Pastry' ? 'cookie' : 'utensils')) }} fa-4x mb-3 text-white"></i>
                        <h5 class="text-white fw-bold mb-3">{{ $category }}</h5>
                        <span class="btn btn-black btn-sm rounded-pill px-4">
                            Browse <i class="fas fa-arrow-right ms-2"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    <!-- Stats Section -->
    <div class="row mb-5" data-aos="fade-up">
        <div class="col-12 text-center mb-4">
            <h2 class="display-5 text-gradient mb-2">Why Choose Us</h2>
        </div>
        <div class="col-lg-3 col-md-6 mb-4" data-aos="flip-left" data-aos-delay="100">
            <div class="card-cafe text-center p-4 h-100 glass-effect">
                <div class="bg-gradient-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 80px; height: 80px;">
                    <i class="fas fa-coffee fa-2x text-white"></i>
                </div>
                <h3 class="text-gradient mb-2">50+</h3>
                <p class="text-muted mb-0 fw-semibold">Coffee Varieties</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4" data-aos="flip-left" data-aos-delay="200">
            <div class="card-cafe text-center p-4 h-100 glass-effect">
                <div class="bg-gradient-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 80px; height: 80px;">
                    <i class="fas fa-users fa-2x text-white"></i>
                </div>
                <h3 class="text-gradient mb-2">CEO</h3>
                <p class="text-muted mb-0 fw-semibold">John Peter Gamboa</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4" data-aos="flip-left" data-aos-delay="300">
            <div class="card-cafe text-center p-4 h-100 glass-effect">
                <div class="bg-gradient-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 80px; height: 80px;">
                    <i class="fas fa-award fa-2x text-white"></i>
                </div>
                <h3 class="text-gradient mb-2">1 week</h3>
                <p class="text-muted mb-0 fw-semibold">Of Excellence</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4" data-aos="flip-left" data-aos-delay="400">
            <div class="card-cafe text-center p-4 h-100 glass-effect">
                <div class="bg-gradient-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 80px; height: 80px;">
                    <i class="fas fa-bolt fa-2x text-white"></i>
                </div>
                <h3 class="text-gradient mb-2">30 Min</h3>
                <p class="text-muted mb-0 fw-semibold">Quick Delivery</p>
            </div>
        </div>
    </div>
</div>
@endsection