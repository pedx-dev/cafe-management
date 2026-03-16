@extends('layouts.app')

@section('title', 'Order #' . $order->id . ' - Admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="row mb-4" data-aos="fade-down">
        <div class="col-12">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary rounded-pill mb-3">
                <i class="fas fa-arrow-left me-2"></i>Back to Orders
            </a>
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="display-5 fw-bold text-gradient mb-1">
                        <i class="fas fa-file-invoice me-2"></i>Order #{{ $order->id }}
                    </h1>
                    <p class="text-muted mb-0">
                        <i class="fas fa-calendar me-1"></i>{{ $order->created_at->format('M d, Y \a\t h:i A') }}
                        <span class="mx-2">•</span>
                        <i class="fas fa-clock me-1"></i>{{ $order->created_at->diffForHumans() }}
                    </p>
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
                @endphp
                <div class="d-flex flex-column align-items-sm-end gap-2">
                    <span class="badge bg-{{ $color }} fs-5 px-4 py-3 rounded-pill shadow">
                        <i class="fas fa-{{ $icon }} me-2"></i>{{ ucfirst($order->status) }}
                    </span>
                    @if($isFastTrackOrder)
                        <span class="badge bg-dark px-3 py-2 rounded-pill shadow-sm">
                            <i class="fas fa-bolt me-1 text-warning"></i>FAST TRACK DELIVERY
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert" data-aos="fade-in">
            <div class="d-flex align-items-center">
                <div class="alert-icon me-3">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
                <div>
                    <strong>Success!</strong>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Order Progress Timeline -->
    <div class="card-cafe mb-4 overflow-hidden" data-aos="fade-up">
        <div class="card-header bg-gradient-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-tasks me-2"></i>Order Progress</h5>
        </div>
        <div class="card-body py-4">
            <div class="progress-timeline d-flex justify-content-between align-items-start">
                @php
                    $statuses = ['pending', 'confirmed', 'preparing', 'ready', 'delivered'];
                    $currentIndex = array_search($order->status, $statuses);
                    if ($order->status == 'cancelled') $currentIndex = -1;
                @endphp
                @foreach($statuses as $index => $status)
                    <div class="timeline-step text-center {{ $index <= $currentIndex ? 'completed' : '' }}">
                        <div class="step-icon-wrapper">
                            <div class="step-icon {{ $index <= $currentIndex ? 'bg-success' : 'bg-secondary' }} {{ $index == $currentIndex ? 'current' : '' }}">
                                <i class="fas fa-{{ $statusIcons[$status] }}"></i>
                            </div>
                        </div>
                        <p class="step-label mb-0 mt-2 {{ $index <= $currentIndex ? 'fw-bold text-success' : 'text-muted' }}">
                            {{ ucfirst($status) }}
                        </p>
                        @if($index == $currentIndex && $order->status !== 'cancelled')
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill mt-1">Current</span>
                        @endif
                    </div>
                    @if($index < count($statuses) - 1)
                        <div class="timeline-connector {{ $index < $currentIndex ? 'completed' : '' }}"></div>
                    @endif
                @endforeach
            </div>
            @if($order->status == 'cancelled')
                <div class="alert alert-danger mt-4 mb-0 rounded-3">
                    <i class="fas fa-exclamation-circle me-2"></i>This order has been <strong>cancelled</strong>.
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Order Status Update & Customer Info -->
        <div class="col-lg-4 mb-4" data-aos="fade-right">
            <!-- Status Update Card -->
            <div class="card-cafe mb-4 overflow-hidden">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-edit me-2 text-primary"></i>Update Status</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <select name="status" class="form-select form-select-lg rounded-3" required>
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>✅ Confirmed</option>
                                <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>🔥 Preparing</option>
                                <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>✔️ Ready for Pickup</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>🚚 Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-cafe w-100 btn-lg rounded-pill">
                            <i class="fas fa-save me-2"></i>Update Status
                        </button>
                    </form>
                </div>
            </div>

            @if($order->delivery_type === 'delivery')
            <!-- Rider Tracking Card -->
            <div class="card-cafe mb-4 overflow-hidden">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-motorcycle me-2 text-primary"></i>Update Rider Location</h5>
                </div>
                <div class="card-body p-4">
                    @php $tracking = $order->orderTracking; @endphp
                    <form action="{{ route('admin.orders.updateTracking', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Click map to pin rider location, or type manually</label>
                            @if(config('services.google_maps.js_api_key'))
                            <div id="admin-map" style="width:100%;height:220px;border-radius:10px;border:1px solid #dee2e6;cursor:crosshair;" class="mb-2"></div>
                            @endif
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold">Latitude</label>
                                <input type="number" step="any" name="lat" id="input-lat"
                                    class="form-control rounded-3"
                                    value="{{ $tracking->lat ?? '' }}"
                                    placeholder="e.g. 14.5995" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">Longitude</label>
                                <input type="number" step="any" name="lng" id="input-lng"
                                    class="form-control rounded-3"
                                    value="{{ $tracking->lng ?? '' }}"
                                    placeholder="e.g. 120.9842" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">ETA <span class="text-muted fw-normal">(optional)</span></label>
                            <input type="text" name="eta" class="form-control rounded-3"
                                value="{{ $tracking->eta ?? '' }}"
                                placeholder="e.g. 15 mins">
                        </div>
                        <button type="submit" class="btn btn-cafe w-100 rounded-pill">
                            <i class="fas fa-map-marker-alt me-2"></i>Save Rider Location
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Customer Info Card -->
            <div class="card-cafe overflow-hidden">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-user me-2"></i>Customer Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="customer-avatar bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                            <span class="text-white fw-bold fs-4">{{ strtoupper(substr($order->user->name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">{{ $order->user->name }}</h5>
                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill">Customer</span>
                        </div>
                    </div>

                    <div class="customer-details">
                        <div class="detail-item mb-3 p-3 bg-light rounded-3">
                            <label class="small text-muted mb-1 d-block">
                                <i class="fas fa-envelope me-2 text-primary"></i>Email
                            </label>
                            <p class="mb-0 fw-semibold">{{ $order->user->email }}</p>
                        </div>
                        <div class="detail-item mb-3 p-3 bg-light rounded-3">
                            <label class="small text-muted mb-1 d-block">
                                <i class="fas fa-phone me-2 text-primary"></i>Phone
                            </label>
                            <p class="mb-0 fw-semibold">{{ $order->user->phone ?? 'Not provided' }}</p>
                        </div>
                        @if($order->delivery_address)
                            <div class="detail-item mb-3 p-3 bg-light rounded-3">
                                <label class="small text-muted mb-1 d-block">
                                    <i class="fas fa-map-marker-alt me-2 text-primary"></i>Delivery Address
                                </label>
                                <p class="mb-0 fw-semibold">{{ $order->delivery_address }}</p>
                            </div>
                        @endif
                        @if($isFastTrackOrder)
                            <div class="detail-item mb-3 p-3 bg-dark text-white rounded-3">
                                <label class="small mb-1 d-block text-uppercase opacity-75">
                                    <i class="fas fa-bolt me-2 text-warning"></i>Courier Source
                                </label>
                                <p class="mb-0 fw-semibold">FastTrack Delivery</p>
                            </div>
                        @endif
                    </div>

                    @if($order->notes)
                        <div class="notes-section mt-3 p-3 bg-warning bg-opacity-10 rounded-3">
                            <label class="small text-warning mb-1 d-block fw-bold">
                                <i class="fas fa-sticky-note me-2"></i>Special Instructions
                            </label>
                            <p class="mb-0">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="col-lg-8 mb-4" data-aos="fade-left">
            <div class="card-cafe overflow-hidden">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-shopping-bag me-2"></i>Order Items</h5>
                        <span class="badge bg-white text-primary rounded-pill px-3 py-2">
                            {{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @foreach($order->items as $item)
                        <div class="order-item-row p-3 d-flex align-items-center {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="position-relative">
                                <img src="{{ asset('storage/' . $item->menuItem->image) }}" 
                                     alt="{{ $item->menuItem->name }}" 
                                     class="rounded-3 shadow-sm"
                                     style="width: 70px; height: 70px; object-fit: cover;">
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
                                    ×{{ $item->quantity }}
                                </span>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <h6 class="mb-1 fw-bold">{{ $item->menuItem->name }}</h6>
                                <span class="badge bg-info bg-opacity-10 text-info rounded-pill">{{ $item->menuItem->category }}</span>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block">₱{{ number_format($item->price, 2) }} each</small>
                                <span class="fw-bold text-gradient fs-5">₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="card-footer bg-light p-4">
                    <div class="row justify-content-end">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal:</span>
                                <span>₱{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Delivery Fee:</span>
                                <span class="text-success fw-semibold">FREE</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="fs-5 fw-bold">Total Amount:</span>
                                <span class="fs-4 fw-bold text-gradient">₱{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card-cafe mt-4" data-aos="fade-up">
                <div class="card-body p-4">
                    <div class="d-flex gap-3 flex-wrap justify-content-center">
                        <button class="btn btn-outline-primary btn-lg rounded-pill px-4" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Print Order
                        </button>
                        <a href="mailto:{{ $order->user->email }}" class="btn btn-outline-info btn-lg rounded-pill px-4">
                            <i class="fas fa-envelope me-2"></i>Email Customer
                        </a>
                        <form action="{{ route('admin.orders.destroy', $order->id) }}" 
                              method="POST" 
                              class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-lg rounded-pill px-4">
                                <i class="fas fa-trash me-2"></i>Delete Order
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.progress-timeline {
    position: relative;
    padding: 0 1rem;
    gap: 0.25rem;
}

.timeline-step {
    flex: 0 0 auto;
    z-index: 2;
    min-width: 64px;
}

.step-icon-wrapper {
    position: relative;
}

.step-icon {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.step-icon.current {
    animation: pulse 2s infinite;
    box-shadow: 0 0 0 8px rgba(16, 185, 129, 0.2);
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.timeline-connector {
    flex: 1;
    height: 4px;
    background: #dee2e6;
    margin: 27px 8px 0;
    transition: background 0.3s ease;
}

.timeline-connector.completed {
    background: #10B981;
}

.step-label {
    font-size: 0.85rem;
}

.card-header h5 i,
.detail-item label i,
.notes-section label i {
    margin-right: 0.6rem !important;
}

.order-item-row {
    transition: background-color 0.3s ease;
}

.order-item-row:hover {
    background-color: rgba(111, 78, 55, 0.03);
}

@media print {
    .btn, form, .card-header {
        display: none !important;
    }
}

@media (max-width: 991.98px) {
    .progress-timeline {
        padding: 0 0.5rem;
    }

    .timeline-step {
        min-width: 56px;
    }

    .step-icon {
        width: 48px;
        height: 48px;
        font-size: 1rem;
    }

    .timeline-connector {
        margin-top: 23px;
    }

    .step-label {
        font-size: 0.75rem;
    }
}
</style>

@push('scripts')
@if($order->delivery_type === 'delivery' && config('services.google_maps.js_api_key'))
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.js_api_key') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const existingLat = parseFloat('{{ $order->orderTracking->lat ?? 14.5995 }}');
        const existingLng = parseFloat('{{ $order->orderTracking->lng ?? 120.9842 }}');
        const hasExisting = {{ $order->orderTracking && $order->orderTracking->lat ? 'true' : 'false' }};

        const adminMap = new google.maps.Map(document.getElementById('admin-map'), {
            center: { lat: existingLat, lng: existingLng },
            zoom: hasExisting ? 15 : 12,
        });

        let adminMarker = hasExisting ? new google.maps.Marker({
            position: { lat: existingLat, lng: existingLng },
            map: adminMap,
            title: 'Rider',
            icon: 'https://maps.google.com/mapfiles/ms/icons/motorcycling.png'
        }) : null;

        adminMap.addListener('click', function (e) {
            const lat = e.latLng.lat();
            const lng = e.latLng.lng();

            document.getElementById('input-lat').value = lat.toFixed(7);
            document.getElementById('input-lng').value = lng.toFixed(7);

            if (adminMarker) {
                adminMarker.setPosition({ lat, lng });
            } else {
                adminMarker = new google.maps.Marker({
                    position: { lat, lng },
                    map: adminMap,
                    title: 'Rider',
                    icon: 'https://maps.google.com/mapfiles/ms/icons/motorcycling.png'
                });
            }
        });
    });
</script>
@endif
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Delete confirmation with SweetAlert2
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Delete this order?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash me-1"></i> Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
@endsection
