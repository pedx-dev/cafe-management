@extends('layouts.app')

@section('title', 'Checkout - Café Delight')

@push('styles')
    <!-- ...existing code... -->
@endpush

@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAESWAccCjxPg0NHszTHGM5K3f29vNMW6o"></script>
    <script>
        let map, marker;
        function updateRiderMap(orderId) {
            fetch(`/api/orders/${orderId}/tracking`)
                .then(res => res.json())
                .then(data => {
                    if (data.location && data.location.lat && data.location.lng) {
                        document.getElementById('rider-map-section').style.display = '';
                        const lat = parseFloat(data.location.lat);
                        const lng = parseFloat(data.location.lng);
                        if (!map) {
                            map = new google.maps.Map(document.getElementById('map'), {
                                center: {lat, lng},
                                zoom: 15
                            });
                            marker = new google.maps.Marker({
                                position: {lat, lng},
                                map: map,
                                title: 'Rider Location',
                                icon: 'https://maps.google.com/mapfiles/ms/icons/motorcycling.png'
                            });
                        } else {
                            marker.setPosition({lat, lng});
                            map.setCenter({lat, lng});
                        }
                        document.getElementById('rider-status').innerHTML = `<b>Status:</b> ${data.status} <br><b>ETA:</b> ${data.eta || 'N/A'}`;
                    } else {
                        document.getElementById('rider-map-section').style.display = 'none';
                    }
                });
        }
        document.addEventListener('DOMContentLoaded', function() {
            var orderId = document.getElementById('order-id') ? document.getElementById('order-id').value : null;
            if (orderId) {
                updateRiderMap(orderId);
                setInterval(() => updateRiderMap(orderId), 5000); // Poll every 5 seconds
            }
        });
    </script>
@endpush

<!-- Add this hidden input where you have the order ID available after placing an order -->
<input type="hidden" id="order-id" value="{{ $order->id ?? '' }}">

@section('content')
<div class="container">
    <!-- Header -->
    <div class="row mb-5" data-aos="fade-down">
        <div class="col-12">
            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary rounded-pill mb-3">
                <i class="fas fa-arrow-left me-2"></i>Back to Cart
            </a>
            <div class="text-center">
                <span class="badge bg-light text-dark px-3 py-2 rounded-pill mb-3">
                    <i class="fas fa-lock me-1"></i> Secure Checkout
                </span>
                <h1 class="display-5 fw-bold text-gradient mb-1">
                    <i class="fas fa-credit-card me-2"></i>Checkout
                </h1>
                <p class="text-muted">Review your order and complete your purchase</p>
            </div>
        </div>
    </div>

    <!-- Checkout Steps -->
    <div class="row mb-5" data-aos="fade-up">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                <div class="checkout-steps d-flex align-items-center">
                    <div class="checkout-step completed">
                        <div class="step-number"><i class="fas fa-check"></i></div>
                        <span>Cart</span>
                    </div>
                    <div class="step-connector completed"></div>
                    <div class="checkout-step active">
                        <div class="step-number">2</div>
                        <span>Details</span>
                    </div>
                    <div class="step-connector"></div>
                    <div class="checkout-step">
                        <div class="step-number">3</div>
                        <span>Confirm</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Order Summary -->
        <div class="col-lg-7 mb-4" data-aos="fade-right">
            <div class="card-cafe overflow-hidden">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0 fw-bold mp-4">
                        </i>Order Summary
                        <span class="badge bg-light text-dark ms-2 rounded-pill">{{ $cartItems->count() }} items</span>
                    </h5>
                </div>
                <div class="card-body p-2">
                    @foreach($cartItems as $item)
                    <div class="checkout-item p-10 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex align-items-center">
                            <div class="position-relative">
                                <img src="{{ asset('storage/' . $item->menuItem->image) }}" 
                                     alt="{{ $item->menuItem->name }}"
                                     class="rounded-3 shadow-sm" style="width: 70px; height: 70px; object-fit: cover;">
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
                                    ×{{ $item->quantity }}
                                </span>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <h6 class="fw-bold mb-1">{{ $item->menuItem->name }}</h6>
                                <span class="badge bg-secondary rounded-pill small">{{ $item->menuItem->category }}</span>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-gradient">₱{{ number_format($item->menuItem->price * $item->quantity, 2) }}</span>
                                <small class="d-block text-muted">₱{{ number_format($item->menuItem->price, 2) }} each</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="card-footer bg-light p-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span>₱{{ number_format($total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Delivery Fee</span>
                        <span class="text-success fw-semibold">FREE</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fs-5 fw-bold">Total</span>
                        <span class="fs-4 fw-bold text-gradient">₱{{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delivery Details Form -->
        <div class="col-lg-5 mb-4" data-aos="fade-left">
            <div class="card-cafe">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-truck me-2"></i>Delivery Details
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('orders.place') }}" method="POST" id="checkout-form">
                        @csrf
                        
                        <!-- Delivery Type Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-shipping-fast me-2 text-primary"></i>Delivery Option
                            </label>
                            <div class="row g-3">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="delivery_type" id="pickup" value="pickup" checked>
                                    <label class="btn btn-outline-primary w-100 py-3 rounded-3" for="pickup">
                                        <i class="fas fa-store fa-2x mb-2 d-block"></i>
                                        <span class="fw-bold">Pickup</span>
                                        <small class="d-block text-muted">At our store</small>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="delivery_type" id="delivery" value="delivery">
                                    <label class="btn btn-outline-primary w-100 py-3 rounded-3" for="delivery">
                                        <i class="fas fa-motorcycle fa-2x mb-2 d-block"></i>
                                        <span class="fw-bold">Delivery</span>
                                        <small class="d-block text-muted">To your door</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Address -->
                        <div class="mb-4" id="address-section">
                            <label for="delivery_address" class="form-label fw-bold">
                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>Delivery Address
                            </label>
                            <textarea class="form-control rounded-3 @error('delivery_address') is-invalid @enderror" 
                                      id="delivery_address" 
                                      name="delivery_address" 
                                      rows="3" 
                                      placeholder="Enter your complete delivery address...">{{ old('delivery_address', auth()->user()->address ?? '') }}</textarea>
                            @error('delivery_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contact Number -->
                        <div class="mb-4">
                            <label for="phone" class="form-label fw-bold">
                                <i class="fas fa-phone me-2 text-primary"></i>Contact Number
                            </label>
                            <input type="text" class="form-control rounded-3" id="phone" 
                                   value="{{ auth()->user()->phone ?? '' }}" readonly>
                            <small class="text-muted">We'll contact you for delivery updates</small>
                        </div>

                        <!-- Special Instructions -->
                        <div class="mb-4">
                            <label for="notes" class="form-label fw-bold">
                                <i class="fas fa-sticky-note me-2 text-primary"></i>Special Instructions
                                <span class="text-muted fw-normal">(Optional)</span>
                            </label>
                            <textarea class="form-control rounded-3 @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="2" 
                                      placeholder="Any special requests? (e.g., extra sugar, no ice...)">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-wallet me-2 text-primary"></i>Payment Method
                            </label>
                            <div class="bg-light rounded-3 p-3 d-flex align-items-center">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-money-bill-wave text-white"></i>
                                </div>
                                <div>
                                    <span class="fw-bold">Cash on Delivery</span>
                                    <small class="d-block text-muted">Pay when you receive your order</small>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Place Order Button -->
                        <button type="submit" class="btn btn-cafe btn-lg w-100 rounded-pill py-3">
                            <i class="fas fa-check-circle me-2"></i>Place Order • ₱{{ number_format($total, 2) }}
                        </button>
                        
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1 text-success"></i>
                                Your order is protected by our satisfaction guarantee
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Rider Map Section -->
    <div class="row mb-5" id="rider-map-section" style="display:none;">
        <div class="col-12">
            <div class="card-cafe">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-map-marker-alt me-2"></i>Rider Location
                    </h5>
                </div>
                <div class="card-body">
                    <div id="map" style="width:100%;height:300px;border-radius:12px;"></div>
                    <div id="rider-status" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ...existing code... */
   .card-header h5 {
        display: flex;
        align-items: center;
        
        margin-left: 20px;
    }
.checkout-steps {
    background: white;
    padding: 20px 40px;
    border-radius: 50px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.checkout-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    color: #6c757d;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-bottom: 5px;
}

.checkout-step.active .step-number {
    background: linear-gradient(135deg, #6F4E37, #D4A574);
    color: white;
}

.checkout-step.completed .step-number {
    background: #10B981;
    color: white;
}

.checkout-step.active span,
.checkout-step.completed span {
    color: #6F4E37;
    font-weight: 600;
}

.step-connector {
    width: 60px;
    height: 3px;
    background: #e9ecef;
    margin: 0 15px;
    margin-bottom: 20px;
}

.step-connector.completed {
    background: #10B981;
}

.checkout-item {
    transition: background-color 0.3s ease;
}

.checkout-item:hover {
    background-color: rgba(111, 78, 55, 0.03);
}

.btn-check:checked + .btn-outline-primary {
    background: linear-gradient(135deg, #6F4E37, #D4A574);
    border-color: #6F4E37;
    color: white;
}
</style>

<!-- ...existing code... -->
@endsection
