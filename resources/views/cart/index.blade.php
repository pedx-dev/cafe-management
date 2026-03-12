@extends('layouts.app')

@section('title', 'My Cart - Café Delight')

@section('content')
<div class="container">
    <!-- Enhanced Page Header -->
    <div class="row mb-5" data-aos="fade-down">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill mb-2">
                        <i class="fas fa-shopping-bag me-1"></i> Your Selection
                    </span>
                    <h1 class="display-5 fw-bold text-gradient mb-1">
                        <i class="fas fa-shopping-cart me-2"></i>My Shopping Cart
                    </h1>
                    <p class="text-muted mb-0">Review your items before checkout</p>
                </div>
                @if(!$cartItems->isEmpty())
                    <div class="text-end">
                        <span class="badge bg-primary fs-6 px-4 py-2 rounded-pill">
                            <i class="fas fa-box me-2"></i>{{ $cartItems->count() }} {{ Str::plural('item', $cartItems->count()) }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" data-aos="fade-in">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($cartItems->isEmpty())
        <div class="card-cafe text-center" data-aos="fade-up">
            <div class="card-body py-5">
                <div class="mb-4">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                        <i class="fas fa-shopping-cart fa-4x text-muted"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-3">Your cart is empty</h3>
                <p class="text-muted mb-4 fs-5">Looks like you haven't added any delicious items yet!</p>
                <a href="{{ route('menu') }}" class="btn btn-cafe btn-lg rounded-pill px-5">
                    <i class="fas fa-utensils me-2"></i>Explore Our Menu
                </a>
            </div>
        </div>
    @else
        <div class="row gy-4"> <!-- Added gy-4 for vertical gutter -->
            <!-- Cart Items -->
            <div class="col-lg-8 mb-4" data-aos="fade-right">
                <div class="card-cafe mb-4 overflow-hidden">
                    <div class=" mb -4 card-header bg-gradient-primary text-white py-3">
                        <h5 class="mb-16 fw-bold d-flex align-items-center" style="gap: 22px;">
                          <span>Cart Items</span>
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @foreach($cartItems as $item)
                        <div class="cart-item p-4 border-bottom {{ !$loop->last ? '' : 'border-0' }}">
                            <div class="row align-items-center">
                                <div class="col-md-2 col-3">
                                    <img src="{{ asset('storage/' . $item->menuItem->image) }}" 
                                         alt="{{ $item->menuItem->name }}" 
                                         class="rounded-3 shadow-sm w-100" style="height: 80px; object-fit: cover;">
                                </div>
                                <div class="col-md-4 col-9">
                                    <h6 class="fw-bold mb-1">{{ $item->menuItem->name }}</h6>
                                    <span class="badge bg-primary rounded-pill">{{ $item->menuItem->category }}</span>
                                    @if($item->special_instructions)
                                    <p class="small text-info mb-0 mt-1">
                                        <i class="fas fa-sticky-note me-1"></i>{{ $item->special_instructions }}
                                    </p>
                                    @endif
                                </div>
                                <div class="col-md-2 col-4 mt-3 mt-md-0">
                                    <small class="text-muted d-block mb-1">Price</small>
                                    <span class="fw-bold text-gradient">₱{{ number_format($item->menuItem->price, 2) }}</span>
                                </div>
                                <div class="col-md-2 col-4 mt-3 mt-md-0">
                                    <small class="text-muted d-block mb-1">Quantity</small>
                                    <div class="input-group input-group-sm" style="width: 110px;">
                                        <button class="btn btn-outline-secondary decrement-btn rounded-start-pill" 
                                            data-id="{{ $item->id }}" type="button">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" class="form-control text-center quantity-input border-secondary" 
                                               value="{{ $item->quantity }}" min="1" data-id="{{ $item->id }}" style="max-width: 50px;">
                                        <button class="btn btn-outline-secondary increment-btn rounded-end-pill" 
                                            data-id="{{ $item->id }}" type="button">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2 col-4 mt-3 mt-md-0 text-end">
                                    <small class="text-muted d-block mb-1">Subtotal</small>
                                    <span class="fw-bold text-gradient fs-5">₱{{ number_format($item->menuItem->price * $item->quantity, 2) }}</span>
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline ms-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle cart-remove-btn" 
                                            onclick="return confirm('Remove this item from cart?')" title="Remove">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="card-footer bg-light py-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <a href="{{ route('menu') }}" class="btn btn-outline-primary rounded-pill">
                                <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                            </a>
                            <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger rounded-pill" 
                                        onclick="return confirm('Clear entire cart? This action cannot be undone.')">
                                    <i class="fas fa-trash me-2"></i>Clear Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4 mb-4" data-aos="fade-left">
                <div class="card-cafe sticky-top shadow" style="top: 100px;">
                    <div class="card-header bg-gradient-primary text-white py-3">
                        <h5 class="mb-0 fw-bold d-flex align-items-center" style="gap: 12px;">
                            <span>Order Summary</span>
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Subtotal ({{ $cartItems->count() }} items)</span>
                            <span class="fw-semibold" id="cart-subtotal">₱{{ number_format($total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Tax (10%)</span>
                            <span class="fw-semibold" id="cart-tax">₱{{ number_format($total * 0.1, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Delivery Fee</span>
                            <span class="text-success fw-semibold">FREE</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fs-5 fw-bold">Total</span>
                            <span class="fs-4 fw-bold text-gradient" id="cart-total">₱{{ number_format($total + ($total * 0.1), 2) }}</span>
                        </div>
                        
                        <!-- Promo Code -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-muted">
                                <i class="fas fa-tag me-1"></i>Have a promo code?
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control rounded-start-pill" placeholder="Enter code">
                                <button class="btn btn-outline-primary rounded-end-pill px-4" type="button">Apply</button>
                            </div>
                        </div>
                        
                        <!-- Checkout Button -->
                        <a href="{{ route('checkout') }}" class="btn btn-cafe btn-lg w-100 rounded-pill py-3 mb-3">
                            <i class="fas fa-lock me-2"></i>Proceed to Checkout
                        </a>
                        
                        <!-- Security Badge -->
                        <div class="text-center">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1 text-success"></i>
                                Secure Checkout • SSL Encrypted
                            </small>
                        </div>
                    </div>
                    
                  
                
            </div>
        </div>
    @endif
</div>

<style>
    .cart-item {
        transition: background-color 0.3s ease;
    }
    .cart-item:hover {
        background-color: rgba(111, 78, 55, 0.03);
    }
    .cart-remove-btn {
        margin-left: 20px;
    }
    .card-header h5 {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-left: 20px;
    }
    @media (min-width: 992px) {
        .card-cafe {
            min-height: 340px;
        }
    }
</style>

@push('scripts')
<script>
    // Update quantity
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            updateQuantity(this.dataset.id, this.value);
        });
    });
    
    document.querySelectorAll('.increment-btn').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            input.value = parseInt(input.value) + 1;
            updateQuantity(input.dataset.id, input.value);
        });
    });
    
    document.querySelectorAll('.decrement-btn').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            if (input.value > 1) {
                input.value = parseInt(input.value) - 1;
                updateQuantity(input.dataset.id, input.value);
            }
        });
    });
    
    function updateQuantity(cartId, quantity) {
        fetch(`/cart/${cartId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
</script>
@endpush
@endsection