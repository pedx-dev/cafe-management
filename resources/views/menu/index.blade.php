@extends('layouts.app')

@section('title', 'Our Menu - Café Delight')

@section('content')
<div class="container">
    <!-- Enhanced Page Header -->
    <div class="row mb-5" data-aos="fade-down">
        <div class="col-12">
            <div class="text-center py-4">
                <span class="badge bg-light text-dark px-3 py-2 rounded-pill mb-3">
                    <i class="fas fa-coffee me-1"></i> Fresh & Delicious
                </span>
                <h1 class="display-4 fw-bold text-gradient mb-3">
                    <i class="fas fa-utensils me-2"></i>Our Menu
                </h1>
                <p class="lead text-muted fs-5">Discover our delicious selection of coffee, tea, and pastries</p>
            </div>
        </div>
    </div>

    <!-- Enhanced Category Pills -->
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-12">
            <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                <a href="{{ route('menu') }}" class="btn {{ !request('category') || request('category') == 'all' ? 'btn-cafe' : 'btn-outline-secondary' }} rounded-pill px-4">
                    <i class="fas fa-th-large me-2"></i>All Items
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('menu', ['category' => $category]) }}" 
                       class="btn {{ request('category') == $category ? 'btn-cafe' : 'btn-outline-secondary' }} rounded-pill px-4">
                        <i class="fas fa-{{ $category == 'Coffee' ? 'mug-hot' : ($category == 'Tea' ? 'leaf' : ($category == 'Pastry' ? 'cookie' : ($category == 'Sandwich' ? 'hamburger' : 'ice-cream'))) }} me-2"></i>{{ $category }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Enhanced Filters Card -->
    <div class="card-cafe mb-5 shadow-sm" data-aos="fade-up">
        <div class="card-body p-4">
            <form action="{{ route('menu') }}" method="GET" class="row g-3 align-items-end">
                @if(request('category') && request('category') != 'all')
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                
                <div class="col-lg-6 col-md-6">
                    <label for="search" class="form-label fw-semibold">
                        <i class="fas fa-search me-2 text-primary"></i>Search Menu
                    </label>
                    <div class="input-group input-group-lg">
                        <input type="text" name="search" id="search" class="form-control rounded-start-pill border-end-0" 
                               placeholder="Search for your favorite items..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-cafe rounded-end-pill px-4">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-4">
                    <label for="sort" class="form-label fw-semibold">
                        <i class="fas fa-sort me-2 text-primary"></i>Sort By
                    </label>
                    <select name="sort" id="sort" class="form-select form-select-lg rounded-pill">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                        <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price (Low to High)</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Newest First</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-2">
                    @if(request('search') || request('sort'))
                        <a href="{{ route('menu', ['category' => request('category')]) }}" class="btn btn-outline-secondary btn-lg rounded-pill w-100">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Results Count -->
    @if($menuItems->total() > 0)
        <div class="row mb-4" data-aos="fade-up">
            <div class="col-12">
                <p class="text-muted mb-0">
                    <i class="fas fa-utensils me-2"></i>
                    Showing <strong>{{ $menuItems->firstItem() }}-{{ $menuItems->lastItem() }}</strong> of <strong>{{ $menuItems->total() }}</strong> items
                    @if(request('category') && request('category') != 'all')
                        in <strong>{{ request('category') }}</strong>
                    @endif
                </p>
            </div>
        </div>
    @endif

    <!-- Enhanced Menu Items Grid -->
    <div class="row">
        @forelse($menuItems as $index => $item)
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="{{ ($index % 4) * 100 }}">
            <div class="card card-cafe h-100 border-0 shadow-sm menu-item-card overflow-hidden">
                <div class="position-relative overflow-hidden" style="height: 200px;">
                    <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top w-100 h-100 menu-item-image" 
                         alt="{{ $item->name }}" style="object-fit: cover; transition: transform 0.5s ease;">
                    
                    <!-- Badges -->
                    <div class="position-absolute top-0 start-0 m-3">
                        @if($item->is_featured)
                            <span class="badge bg-warning text-dark shadow-sm me-1">
                                <i class="fas fa-star me-1"></i>Featured
                            </span>
                        @endif
                        @if(!$item->is_available)
                            <span class="badge bg-dark bg-opacity-75 shadow-sm me-1">
                                <i class="fas fa-ban me-1"></i>Unavailable
                            </span>
                        @endif
                        @if($item->stock <= 5 && $item->stock > 0)
                            <span class="badge bg-danger shadow-sm">
                                <i class="fas fa-exclamation-triangle me-1"></i>Low Stock
                            </span>
                        @endif
                    </div>

                    <!-- Category Badge -->
                    <div class="position-absolute bottom-0 start-0 m-3">
                        <span class="badge bg-dark bg-opacity-75 rounded-pill px-3 py-2">
                            <i class="fas fa-{{ $item->category == 'Coffee' ? 'mug-hot' : ($item->category == 'Tea' ? 'leaf' : ($item->category == 'Pastry' ? 'cookie' : ($item->category == 'Sandwich' ? 'hamburger' : 'ice-cream'))) }} me-1"></i>
                            {{ $item->category }}
                        </span>
                    </div>

                    <!-- Quick View Overlay -->
                    <div class="position-absolute top-0 end-0 bottom-0 start-0 d-flex align-items-center justify-content-center menu-overlay" 
                         style="background: rgba(111, 78, 55, 0.8); transition: opacity 0.3s; opacity: 0;">
                        <a href="{{ route('menu.show', $item->id) }}" class="btn btn-light rounded-pill px-4">
                            <i class="fas fa-eye me-2"></i>Quick View
                        </a>
                    </div>
                </div>
                
                <div class="card-body d-flex flex-column p-4">
                    <h5 class="card-title fw-bold mb-2">{{ $item->name }}</h5>
                    <p class="card-text text-muted small flex-grow-1 mb-3">
                        {{ Str::limit($item->description, 70) }}
                    </p>
                    
                    @if($item->calories)
                        <p class="small text-muted mb-3">
                            <i class="fas fa-fire text-danger me-1"></i>{{ $item->calories }} cal
                            @if($item->ingredients)
                                <span class="mx-2">•</span>
                                <i class="fas fa-leaf text-success me-1"></i>Fresh
                            @endif
                        </p>
                    @endif
                    
                    <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                        <div>
                            <span class="h4 text-gradient fw-bold mb-0">₱{{ number_format($item->price, 2) }}</span>
                        </div>
                        <div class="d-flex gap-2">
                            @if(!$item->is_available)
                                <button class="btn btn-secondary btn-sm rounded-pill px-3" disabled title="Unavailable">
                                    <i class="fas fa-ban"></i>
                                </button>
                            @elseif($item->stock > 0)
                                <form action="{{ route('cart.add', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-cafe btn-sm rounded-pill px-3" title="Add to Cart">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-secondary btn-sm rounded-pill px-3" disabled title="Out of Stock">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                            <a href="{{ route('menu.show', $item->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12" data-aos="fade-up">
            <div class="card-cafe text-center py-5">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                            <i class="fas fa-search fa-3x text-muted"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-2">No items found</h4>
                    <p class="text-muted mb-4">We couldn't find any items matching your search criteria</p>
                    <a href="{{ route('menu') }}" class="btn btn-cafe rounded-pill px-4">
                        <i class="fas fa-undo me-2"></i>View All Items
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Enhanced Pagination -->
    @if($menuItems->hasPages())
        <div class="row mt-5" data-aos="fade-up">
            <div class="col-12 d-flex justify-content-center">
                <nav aria-label="Menu pagination">
                    {{ $menuItems->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        </div>
    @endif
</div>

<style>
    .menu-item-card:hover .menu-overlay {
        opacity: 1 !important;
    }
    .menu-item-card:hover .menu-item-image {
        transform: scale(1.1);
    }
</style>

@push('scripts')
<script>
    // Auto-submit sort filter on change
    document.getElementById('sort').addEventListener('change', function() {
        this.form.submit();
    });
</script>
@endpush
@endsection