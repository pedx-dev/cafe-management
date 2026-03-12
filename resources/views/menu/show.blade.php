@extends('layouts.app')

@section('title', $item->name . ' - Café Delight')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <div class="row mb-4" data-aos="fade-down">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('menu') }}" class="text-decoration-none">
                            <i class="fas fa-utensils me-1"></i>Menu
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('menu', ['category' => $item->category]) }}" class="text-decoration-none">
                            {{ $item->category }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row g-4">
        <!-- Product Image -->
        <div class="col-lg-5" data-aos="fade-right">
            <div class="card-cafe overflow-hidden position-relative">
                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" 
                     class="img-fluid w-100" style="height: 450px; object-fit: cover;">
                
                <!-- Badges -->
                <div class="position-absolute top-0 start-0 m-3">
                    @if($item->is_featured)
                        <span class="badge bg-warning text-dark shadow px-3 py-2 rounded-pill">
                            <i class="fas fa-star me-1"></i>Featured
                        </span>
                    @endif
                </div>
                
                @if($item->stock <= 5 && $item->stock > 0)
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-danger shadow px-3 py-2 rounded-pill">
                            <i class="fas fa-exclamation-triangle me-1"></i>Only {{ $item->stock }} left!
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-lg-7" data-aos="fade-left">
            <div class="card-cafe h-100">
                <div class="card-body p-4">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge bg-primary rounded-pill px-3 py-2 mb-2">
                                <i class="fas fa-{{ $item->category == 'Coffee' ? 'mug-hot' : ($item->category == 'Tea' ? 'leaf' : ($item->category == 'Pastry' ? 'cookie' : ($item->category == 'Sandwich' ? 'hamburger' : 'ice-cream'))) }} me-1"></i>
                                {{ $item->category }}
                            </span>
                            <h1 class="display-6 fw-bold text-gradient mb-0">{{ $item->name }}</h1>
                        </div>
                        <div class="text-end">
                            <div class="display-6 text-gradient fw-bold">₱{{ number_format($item->price, 2) }}</div>
                            <small class="text-muted">
                                @if($item->is_available)
                                    <i class="fas fa-box me-1"></i>{{ $item->stock }} in stock
                                @else
                                    <i class="fas fa-ban me-1"></i>Unavailable
                                @endif
                            </small>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Description -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-info-circle me-2 text-primary"></i>Description
                        </h5>
                        <p class="text-muted fs-5 lh-lg">{{ $item->description }}</p>
                    </div>

                    <!-- Details Grid -->
                    <div class="row g-3 mb-4">
                        @if($item->ingredients)
                            <div class="col-md-6">
                                <div class="bg-light rounded-3 p-3 h-100">
                                    <h6 class="fw-bold mb-2">
                                        <i class="fas fa-leaf text-success me-2"></i>Ingredients
                                    </h6>
                                    <p class="mb-0 text-muted small">{{ $item->ingredients }}</p>
                                </div>
                            </div>
                        @endif

                        @if($item->calories)
                            <div class="col-md-6">
                                <div class="bg-light rounded-3 p-3 h-100">
                                    <h6 class="fw-bold mb-2">
                                        <i class="fas fa-fire text-danger me-2"></i>Nutrition Info
                                    </h6>
                                    <p class="mb-0">
                                        <span class="h4 text-gradient">{{ $item->calories }}</span>
                                        <span class="text-muted">calories per serving</span>
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Availability Status -->
                    <div class="alert {{ $item->is_available && $item->stock > 0 ? 'alert-success' : 'alert-danger' }} d-flex align-items-center mb-4" role="alert">
                        <i class="fas fa-{{ $item->is_available && $item->stock > 0 ? 'check-circle' : 'times-circle' }} fa-lg me-3"></i>
                        <div>
                            @if($item->is_available && $item->stock > 0)
                                <strong>Available Now!</strong> Ready for immediate order.
                            @elseif(!$item->is_available)
                                <strong>Unavailable</strong> This item is temporarily disabled.
                            @else
                                <strong>Out of Stock</strong> This item is currently unavailable.
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        @if($item->is_available && $item->stock > 0)
                            <form action="{{ route('cart.add', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-cafe btn-lg px-5 rounded-pill">
                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                </button>
                            </form>
                        @elseif(!$item->is_available)
                            <button class="btn btn-secondary btn-lg px-5 rounded-pill" disabled>
                                <i class="fas fa-ban me-2"></i>Unavailable
                            </button>
                        @else
                            <button class="btn btn-secondary btn-lg px-5 rounded-pill" disabled>
                                <i class="fas fa-times me-2"></i>Out of Stock
                            </button>
                        @endif
                        <a href="{{ route('menu') }}" class="btn btn-outline-secondary btn-lg px-4 rounded-pill">
                            <i class="fas fa-arrow-left me-2"></i>Back to Menu
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Items -->
    @if($relatedItems->count())
        <div class="row mt-5" data-aos="fade-up">
            <div class="col-12 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold text-gradient mb-1">
                            <i class="fas fa-heart me-2"></i>You May Also Like
                        </h3>
                        <p class="text-muted mb-0">More delicious items from {{ $item->category }}</p>
                    </div>
                    <a href="{{ route('menu', ['category' => $item->category]) }}" class="btn btn-outline-primary rounded-pill">
                        View All <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
            @foreach($relatedItems as $index => $related)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="card card-cafe h-100 overflow-hidden">
                        <div class="position-relative overflow-hidden" style="height: 180px;">
                            <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}" 
                                 class="card-img-top w-100 h-100" style="object-fit: cover; transition: transform 0.3s;">
                            @if($related->is_featured)
                                <span class="position-absolute top-0 end-0 m-2 badge bg-warning text-dark">
                                    <i class="fas fa-star"></i>
                                </span>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column p-3">
                            <h6 class="card-title fw-bold mb-2">{{ $related->name }}</h6>
                            <p class="text-muted small flex-grow-1 mb-2">{{ Str::limit($related->description, 50) }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <span class="text-gradient fw-bold fs-5">₱{{ number_format($related->price, 2) }}</span>
                                <a href="{{ route('menu.show', $related->id) }}" class="btn btn-cafe btn-sm rounded-pill px-3">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
