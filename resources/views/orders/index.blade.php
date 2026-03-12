@extends('layouts.app')

@section('title', 'My Orders - Café Delight')

@section('content')
<div class="container">
    <!-- Enhanced Page Header -->
    <div class="row mb-5" data-aos="fade-down">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill mb-2">
                        <i class="fas fa-history me-1"></i> Order History
                    </span>
                    <h1 class="display-5 fw-bold text-gradient mb-1">
                        <i class="fas fa-receipt me-2"></i>My Orders
                    </h1>
                    <p class="text-muted mb-0">Track and manage your recent orders</p>
                </div>
                <a href="{{ route('menu') }}" class="btn btn-cafe rounded-pill px-4">
                    <i class="fas fa-plus me-2"></i>New Order
                </a>
            </div>
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="card-cafe text-center py-5" data-aos="fade-up">
            <div class="card-body">
                <div class="mb-4">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                        <i class="fas fa-shopping-bag fa-4x text-muted"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-3">No orders yet</h3>
                <p class="text-muted mb-4 fs-5">Start your coffee journey by placing your first order!</p>
                <a href="{{ route('menu') }}" class="btn btn-cafe btn-lg rounded-pill px-5">
                    <i class="fas fa-utensils me-2"></i>Browse Our Menu
                </a>
            </div>
        </div>
    @else
        <!-- Order Stats -->
        <div class="row mb-4" data-aos="fade-up">
            <div class="col-md-3 col-6 mb-3">
                <div class="card-cafe h-100 text-center p-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                        <i class="fas fa-shopping-bag text-primary"></i>
                    </div>
                    <h4 class="text-gradient mb-0">{{ $orders->total() }}</h4>
                    <small class="text-muted">Total Orders</small>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="card-cafe h-100 text-center p-3">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                        <i class="fas fa-clock text-warning"></i>
                    </div>
                    <h4 class="text-gradient mb-0">{{ $orders->where('status', 'pending')->count() + $orders->where('status', 'preparing')->count() }}</h4>
                    <small class="text-muted">In Progress</small>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="card-cafe h-100 text-center p-3">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                    <h4 class="text-gradient mb-0">{{ $orders->where('status', 'delivered')->count() }}</h4>
                    <small class="text-muted">Completed</small>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="card-cafe h-100 text-center p-3">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                        <i class="fas fa-star text-info"></i>
                    </div>
                    <h4 class="text-gradient mb-0">{{ auth()->user()->loyalty_points ?? 0 }}</h4>
                    <small class="text-muted">Loyalty Points</small>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div class="row" data-aos="fade-up">
            @foreach($orders as $order)
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
                @endphp
                <div class="col-12 mb-4">
                    <div class="card-cafe overflow-hidden order-card {{ $order->status == 'pending' || $order->status == 'preparing' ? 'border-start border-4 border-' . $color : '' }}">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                            <i class="fas fa-receipt text-white"></i>
                                        </div>
                                        <div>
                                            <h5 class="text-gradient mb-0 fw-bold">Order #{{ $order->id }}</h5>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                {{ $order->created_at->format('M d, Y') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-3 col-6 mb-3 mb-lg-0">
                                    <small class="text-muted d-block mb-1">Items</small>
                                    <span class="badge bg-info rounded-pill px-3 py-2">
                                        <i class="fas fa-box me-1"></i>{{ $order->items->count() }} items
                                    </span>
                                </div>
                                <div class="col-lg-2 col-md-3 col-6 mb-3 mb-lg-0">
                                    <small class="text-muted d-block mb-1">Total</small>
                                    <h5 class="mb-0 text-gradient fw-bold">₱{{ number_format($order->total_amount, 2) }}</h5>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                                    <small class="text-muted d-block mb-1">Status</small>
                                    <span class="badge bg-{{ $color }} rounded-pill px-3 py-2">
                                        <i class="fas fa-{{ $icon }} me-1"></i>{{ ucfirst($order->status) }}
                                    </span>
                                    @if($order->status == 'preparing')
                                        <div class="progress mt-2" style="height: 4px;">
                                            <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" style="width: 60%"></div>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-lg-2 col-md-6 text-lg-end">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-cafe btn-sm rounded-pill px-4">
                                        <i class="fas fa-eye me-1"></i>View Details
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Quick Preview of Items -->
                            <div class="mt-3 pt-3 border-top">
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($order->items->take(4) as $item)
                                        <div class="d-flex align-items-center bg-light rounded-pill px-3 py-1">
                                            <img src="{{ asset('storage/' . $item->menuItem->image) }}" 
                                                 class="rounded-circle me-2" 
                                                 style="width: 25px; height: 25px; object-fit: cover;">
                                            <small>{{ $item->menuItem->name }} × {{ $item->quantity }}</small>
                                        </div>
                                    @endforeach
                                    @if($order->items->count() > 4)
                                        <span class="badge bg-secondary rounded-pill px-3 py-2">
                                            +{{ $order->items->count() - 4 }} more
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4 d-flex justify-content-center" data-aos="fade-up">
            {{ $orders->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
    .order-card {
        transition: all 0.3s ease;
    }
    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection
