@extends('layouts.app')

@section('title', 'Order #' . $order->id . ' - Café Delight')

@section('content')
<div class="container">
    <!-- Header with Back Button -->
    <div class="row mb-4" data-aos="fade-down">
        <div class="col-12">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary rounded-pill mb-3">
                <i class="fas fa-arrow-left me-2"></i>Back to My Orders
            </a>
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill mb-2">
                        <i class="fas fa-receipt me-1"></i> Order Details
                    </span>
                    <h1 class="display-5 fw-bold text-gradient mb-1">
                        Order #{{ $order->id }}
                    </h1>
                    <p class="text-muted mb-0">
                        <i class="fas fa-calendar me-1"></i>{{ $order->created_at->format('M d, Y \\a\\t h:i A') }}
                        <span class="mx-2">•</span>
                        <i class="fas fa-clock me-1"></i>{{ $order->created_at->diffForHumans() }}
                    </p>
                    {{-- Barcode/QR code removed --}}
                </div>
                @php
                    $statusColors = [
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'preparing' => 'primary',
                        'ready' => 'success',
                        'delivered' => 'success',
                        'cancelled' => 'danger'
                    ];
                    $statusIcons = [
                        'pending' => 'clock',
                        'confirmed' => 'check',
                        'preparing' => 'fire',
                        'ready' => 'check-double',
                        'delivered' => 'truck',
                        'cancelled' => 'times'
                    ];
                    $color = $statusColors[$order->status] ?? 'secondary';
                    $icon = $statusIcons[$order->status] ?? 'circle';
                    $isFastTrackOrder = $order->delivery_type === 'fasttrack' || $order->courier_provider === 'fasttrack';
                    $isGoMetrixOrder = $order->delivery_type === 'gometrix' || $order->courier_provider === 'gometrix';
                @endphp
                <div class="d-flex flex-column align-items-sm-end gap-2">
                    <span class="badge bg-{{ $color }} fs-5 px-4 py-2 rounded-pill">
                        <i class="fas fa-{{ $icon }} me-2"></i>{{ ucfirst($order->status) }}
                    </span>
                    @if($isFastTrackOrder)
                        <span class="badge bg-dark px-3 py-2 rounded-pill">
                            <i class="fas fa-bolt me-1 text-warning"></i>FAST TRACK DELIVERY
                        </span>
                    @endif
                    @if($isGoMetrixOrder)
                        <span class="badge bg-primary px-3 py-2 rounded-pill">
                            <i class="fas fa-route me-1"></i>GOMETRIX DELIVERY
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" data-aos="fade-in">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" data-aos="fade-in">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Order Progress Timeline -->
    @if($order->status != 'cancelled')
    <div class="card-cafe mb-4" data-aos="fade-up">
        <div class="card-body p-4">
            <h5 class="mb-4 fw-bold"><i class="fas fa-tasks me-2 text-primary"></i>Order Progress</h5>
            @php
                $statusSteps = ['pending', 'confirmed', 'preparing', 'ready', 'delivered'];
                $currentIndex = array_search($order->status, $statusSteps);
            @endphp
            <div class="order-progress-timeline">
                @foreach($statusSteps as $index => $step)
                    <div class="progress-step {{ $index <= $currentIndex ? 'active' : '' }} {{ $index == $currentIndex ? 'current' : '' }}">
                        <div class="step-icon">
                            @if($index < $currentIndex)
                                <i class="fas fa-check"></i>
                            @elseif($index == $currentIndex)
                                <i class="fas fa-{{ $statusIcons[$step] }}"></i>
                            @else
                                <span>{{ $index + 1 }}</span>
                            @endif
                        </div>
                        <div class="step-label">{{ ucfirst($step) }}</div>
                        @if($index < count($statusSteps) - 1)
                            <div class="step-line {{ $index < $currentIndex ? 'active' : '' }}"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-danger mb-4" data-aos="fade-up">
        <i class="fas fa-times-circle me-2"></i>
        <strong>Order Cancelled</strong> - This order has been cancelled.
    </div>
    @endif

    <div class="row">
        <!-- Order Details -->
        <div class="col-lg-8 mb-4" data-aos="fade-right">
            <div class="card-cafe overflow-hidden">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-shopping-bag me-2"></i>Order Items
                    </h5>
                </div>
                <div class="card-body p-0">
                    @foreach($order->items as $item)
                    <div class="order-item p-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="row align-items-center">
                            <div class="col-md-2 col-3">
                                <img src="{{ asset('storage/' . $item->menuItem->image) }}" 
                                     alt="{{ $item->menuItem->name }}"
                                     class="rounded-3 shadow-sm w-100" style="height: 80px; object-fit: cover;">
                            </div>
                            <div class="col-md-5 col-9">
                                <h6 class="fw-bold mb-1">{{ $item->menuItem->name }}</h6>
                                <span class="badge bg-primary rounded-pill">{{ $item->menuItem->category }}</span>
                            </div>
                            <div class="col-md-2 col-4 mt-3 mt-md-0 text-center">
                                <small class="text-muted d-block">Qty</small>
                                <span class="badge bg-secondary rounded-pill px-3 py-2 fs-6">×{{ $item->quantity }}</span>
                            </div>
                            <div class="col-md-1 col-4 mt-3 mt-md-0 text-center">
                                <small class="text-muted d-block">Price</small>
                                <span>₱{{ number_format($item->price, 2) }}</span>
                            </div>
                            <div class="col-md-2 col-4 mt-3 mt-md-0 text-end">
                                <small class="text-muted d-block">Subtotal</small>
                                <span class="fw-bold text-gradient fs-5">₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="card-footer bg-light p-4">
                    <div class="row justify-content-end">
                        <div class="col-md-5">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal:</span>
                                <span>₱{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Delivery Fee:</span>
                                <span class="text-success">FREE</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="fs-5 fw-bold">Total Paid:</span>
                                <span class="fs-4 fw-bold text-gradient">₱{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Info Sidebar -->
        <div class="col-lg-4 mb-4" data-aos="fade-left">
            <!-- Delivery Info -->
            <div class="card-cafe mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-map-marker-alt me-2 text-primary"></i>Delivery Details
                    </h5>

                    @if($order->payment_method === 'card' && $order->payment_status !== 'paid' && $order->status !== 'cancelled')
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div class="small">Card payment is still pending.</div>
                    </div>
                    <form action="{{ route('orders.payment.retry', $order->id) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-warning w-100 rounded-pill">
                            <i class="fas fa-credit-card me-2"></i>Retry Card Payment
                        </button>
                    </form>
                    @endif

                    <div class="bg-light rounded-3 p-3 mb-3">
                        <small class="text-muted d-block mb-1">Delivery Address</small>
                        <p class="mb-0 fw-semibold">{{ $order->delivery_address ?? 'Pickup at Store' }}</p>
                    </div>
                    @if($isFastTrackOrder)
                    <div class="bg-dark bg-opacity-100 text-white rounded-3 p-3 mb-3">
                        <small class="d-block mb-1 text-uppercase opacity-75">Courier</small>
                        <p class="mb-0 fw-semibold"><i class="fas fa-bolt me-2 text-warning"></i>Delivered via FastTrack</p>
                    </div>
                    @endif
                    @if($order->courier_provider)
                    <div class="bg-light rounded-3 p-3 mb-3 border border-primary-subtle">
                        <small class="text-muted d-block mb-2 text-uppercase">Courier Integration</small>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Provider</span>
                            <span class="badge bg-dark rounded-pill">{{ strtoupper($order->courier_provider) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Raw courier status</span>
                            <span class="badge bg-info text-dark rounded-pill">{{ $order->courier_status ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Courier reference</span>
                            <span class="fw-semibold small">{{ $order->courier_reference ?? 'N/A' }}</span>
                        </div>
                        <small class="d-block mt-2 text-muted">
                            Demo note: these raw values come directly from the external courier integration so you can show that GoMetrix is controlling the delivery workflow.
                        </small>
                    </div>
                    @endif
                    @if($order->notes)
                    <div class="bg-light rounded-3 p-3">
                        <small class="text-muted d-block mb-1">Special Instructions</small>
                        <p class="mb-0">{{ $order->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Help Card -->
            <div class="card-cafe">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-headset me-2 text-primary"></i>Need Help?
                    </h5>
                    <p class="text-muted small mb-3">Having issues with your order? We're here to help!</p>
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary rounded-pill">
                            <i class="fas fa-comments me-2"></i>Chat with Us
                        </button>
                        <button class="btn btn-outline-secondary rounded-pill">
                            <i class="fas fa-phone me-2"></i>Call Support
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $mapsKey = config('services.google_maps.js_api_key');
        $isDeliveryOrder = $order->delivery_type === 'delivery';
    @endphp

    @if($isDeliveryOrder)
    <!-- Rider Map Section -->
    <div class="row mb-5" id="rider-map-section">
        <div class="col-12">
            <div class="card-cafe">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-map-marker-alt me-2"></i>Rider Location
                    </h5>
                </div>
                <div class="card-body">
                    @if(!$mapsKey)
                        <div class="alert alert-warning mb-0">
                            Google Maps API key is not configured yet. Add <code>GOOGLE_MAPS_JS_API_KEY</code> in your <code>.env</code>.
                        </div>
                    @else
                        <div id="map" style="width:100%;height:300px;border-radius:12px;display:none;"></div>
                        <div id="rider-status" class="mt-3 text-muted">Waiting for rider location...</div>
                    @endif
                </div>
            </div>
        </div>
        <input type="hidden" id="order-id" value="{{ $order->id }}">
    </div>
    @endif

    @if($isDeliveryOrder && $mapsKey)
    @push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ $mapsKey }}"></script>
    <script>
        let map, marker;
        function updateRiderMap(orderId) {
            fetch(`/api/orders/${orderId}/tracking`)
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Tracking API unavailable');
                    }
                    return res.json();
                })
                .then(data => {
                    const statusEl = document.getElementById('rider-status');
                    const mapEl = document.getElementById('map');

                    if (data.location && data.location.lat && data.location.lng) {
                        const lat = parseFloat(data.location.lat);
                        const lng = parseFloat(data.location.lng);

                        mapEl.style.display = 'block';

                        if (!map) {
                            map = new google.maps.Map(mapEl, {
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

                        statusEl.innerHTML = `<b>Status:</b> ${data.status || 'N/A'} <br><b>ETA:</b> ${data.eta || 'N/A'}`;
                    } else {
                        mapEl.style.display = 'none';
                        statusEl.textContent = 'Tracking is active, but rider location is not available yet.';
                    }
                })
                .catch(() => {
                    const statusEl = document.getElementById('rider-status');
                    if (statusEl) {
                        statusEl.textContent = 'Unable to load tracking right now. Please refresh and try again.';
                    }
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const orderInput = document.getElementById('order-id');
            const orderId = orderInput ? orderInput.value : null;

            if (orderId) {
                updateRiderMap(orderId);
                setInterval(() => updateRiderMap(orderId), 5000);
            }
        });
    </script>
    @endpush
    @endif
</div>

<style>
.order-progress-timeline {
    display: flex;
    justify-content: space-between;
    position: relative;
}

.progress-step {
    flex: 1;
    text-align: center;
    position: relative;
}

.step-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    font-weight: bold;
    color: #6c757d;
    font-size: 14px;
    transition: all 0.3s ease;
}

.progress-step.active .step-icon {
    background: linear-gradient(135deg, #6F4E37, #D4A574);
    color: white;
}

.progress-step.current .step-icon {
    box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.2);
    animation: pulse 2s infinite;
}

.step-label {
    font-size: 12px;
    color: #6c757d;
    font-weight: 500;
}

.progress-step.active .step-label {
    color: #6F4E37;
    font-weight: 600;
}

.step-line {
    position: absolute;
    top: 25px;
    left: 60%;
    width: 80%;
    height: 4px;
    background: #e9ecef;
    z-index: -1;
}

.step-line.active {
    background: linear-gradient(90deg, #6F4E37, #D4A574);
}

@keyframes pulse {
    0%, 100% {
        box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.2);
    }
    50% {
        box-shadow: 0 0 0 8px rgba(111, 78, 55, 0.1);
    }
}

.order-item {
    transition: background-color 0.3s ease;
}

.order-item:hover {
    background-color: rgba(111, 78, 55, 0.03);
}
</style>
@endsection
