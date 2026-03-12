@extends('layouts.app')

@section('title', 'Admin Dashboard - Café Delight')

@section('content')
<div class="container py-5">
    <!-- Modern Page Header -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <span class="badge bg-white text-dark px-3 py-2 rounded-pill shadow-sm mb-2">
                        <i class="fas fa-tachometer-alt me-1"></i> Admin
                    </span>
                    <h1 class="fw-bold mb-1" style="font-size:2.5rem; letter-spacing:-1px;">
                        <i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard
                    </h1>
                    <p class="text-muted mb-0">Welcome back! Here's what's happening at your café.</p>
                </div>
                <span class="badge bg-primary bg-opacity-75 fs-6 px-4 py-2 rounded-pill shadow">
                    <i class="fas fa-calendar me-1"></i>{{ now()->format('l, F j, Y') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow rounded-4 stats-card overflow-hidden">
                <div class="card-body position-relative">
                    <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stats-content">
                        <p class="text-muted small mb-1 text-uppercase fw-semibold">Today's Orders</p>
                        <h2 class="fw-bold mb-0">{{ $stats['today_orders'] }}</h2>
                        <small class="text-success"><i class="fas fa-arrow-up me-1"></i>Active today</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow rounded-4 stats-card overflow-hidden">
                <div class="card-body position-relative">
                    <div class="stats-icon bg-success bg-opacity-10 text-success">
                        <i class="fas fa-peso-sign"></i>
                    </div>
                    <div class="stats-content">
                        <p class="text-muted small mb-1 text-uppercase fw-semibold">Today's Revenue</p>
                        <h2 class="fw-bold mb-0">₱{{ number_format($stats['revenue_today'], 2) }}</h2>
                        <small class="text-success"><i class="fas fa-chart-line me-1"></i>Sales income</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow rounded-4 stats-card overflow-hidden">
                <div class="card-body position-relative">
                    <div class="stats-icon bg-info bg-opacity-10 text-info">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-content">
                        <p class="text-muted small mb-1 text-uppercase fw-semibold">Total Customers</p>
                        <h2 class="fw-bold mb-0">{{ $stats['total_users'] }}</h2>
                        <small class="text-info"><i class="fas fa-user-plus me-1"></i>Registered users</small>
                    </div>
                </div>
                <div class="stats-footer bg-info"></div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
            <div class="card-cafe stats-card overflow-hidden">
                <div class="card-body position-relative">
                    <div class="stats-icon bg-danger bg-opacity-10 text-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stats-content">
                        <p class="text-muted small mb-1 text-uppercase fw-semibold">Low Stock Items</p>
                        <h2 class="fw-bold mb-0">{{ $stats['low_stock_items'] }}</h2>
                        <small class="text-danger"><i class="fas fa-box me-1"></i>Need attention</small>
                    </div>
                </div>
                <div class="stats-footer bg-danger"></div>
            </div>
        </div>
    </div>

    <!-- Charts and Orders -->
    <div class="row g-4">
        <!-- Orders Chart -->
        <div class="col-xl-5">
            <div class="card border-0 shadow rounded-4 h-100">
                <div class="card-header bg-primary bg-opacity-75 text-white py-3 rounded-top-4 border-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-area me-2"></i>Orders Last 7 Days
                    </h5>
                </div>
                <div class="card-body" style="padding: 1.5rem 1.5rem 1rem 1.5rem;">
                    <canvas id="ordersChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <!-- Recent Orders -->
        <div class="col-xl-7">
            <div class="card border-0 shadow rounded-4 h-100">
                <div class="card-header bg-primary bg-opacity-75 text-white py-3 d-flex justify-content-between align-items-center rounded-top-4 border-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-history me-2"></i>Recent Orders
                    </h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm rounded-pill shadow-sm">
                        <i class="fas fa-arrow-right me-1"></i>View All
                    </a>
                </div>
                <div class="card-body p-0 bg-white rounded-bottom-4">
                    <div class="list-group list-group-flush">
                        @forelse($recentOrders as $order)
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="list-group-item list-group-item-action py-3 order-item">
                            <div class="d-flex align-items-center">
                                <div class="order-avatar me-3">
                                    <div class="avatar-circle bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'pending' ? 'warning' : 'primary') }} bg-opacity-10">
                                        <i class="fas fa-receipt text-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'pending' ? 'warning' : 'primary') }}"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1 fw-bold">Order #{{ $order->order_number }}</h6>
                                            <p class="mb-0 text-muted small">
                                                <i class="fas fa-user me-1"></i>{{ $order->user->name }}
                                            </p>
                                        </div>
                                        <div class="text-end">
                                            <span class="fw-bold text-primary">₱{{ number_format($order->total_amount, 2) }}</span>
                                            <br>
                                            <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'preparing' => 'info',
                                            'ready' => 'primary',
                                            'delivered' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} rounded-pill">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                        </a>
                        @empty
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent orders</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Items & Quick Actions -->
    <div class="row g-4">
        <!-- Menu Items -->
        <div class="col-xl-8">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-primary bg-opacity-75 text-white py-3 d-flex justify-content-between align-items-center rounded-top-4 border-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-utensils me-2"></i>Menu Items Overview
                    </h5>
                    <a href="{{ route('admin.menu.index') }}" class="btn btn-light btn-sm rounded-pill shadow-sm">
                        <i class="fas fa-cog me-1"></i>Manage Menu
                    </a>
                </div>
                <div class="card-body p-0 bg-white rounded-bottom-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Item</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topItems as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . $item->image) }}" 
                                                 alt="{{ $item->name }}" 
                                                 class="rounded-3 me-3 shadow-sm" 
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $item->name }}</h6>
                                                @if($item->is_featured)
                                                    <small class="text-warning"><i class="fas fa-star me-1"></i>Featured</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info bg-opacity-10 text-dark rounded-pill">{{ $item->category }}</span></td>
                                    <td><span class="fw-bold text-primary">₱{{ number_format($item->price, 2) }}</span></td>
                                    <td>
                                        @if($item->stock > 10)
                                            <span class="badge bg-success bg-opacity-10 text-dark rounded-pill">
                                                <i class="fas fa-check me-1"></i>{{ $item->stock }} in stock
                                            </span>
                                        @elseif($item->stock > 0)
                                            <span class="badge bg-warning bg-opacity-10 text-dark rounded-pill">
                                                <i class="fas fa-exclamation me-1"></i>{{ $item->stock }} left
                                            </span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-dark rounded-pill">
                                                <i class="fas fa-times me-1"></i>Out of stock
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->is_available)
                                            <span class="badge bg-success bg-opacity-10 text-dark rounded-pill">Available</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-dark rounded-pill">Unavailable</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="fas fa-coffee fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">No menu items yet</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Quick Actions -->
        <div class="col-xl-4">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-primary bg-opacity-75 text-white py-3 rounded-top-4 border-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body p-4 bg-white rounded-bottom-4">
                    <div class="d-grid gap-3">
                        <a href="{{ route('admin.menu.create') }}" class="btn btn-primary btn-lg rounded-pill quick-action-btn shadow-sm">
                            <div class="d-flex align-items-center">
                                <div class="action-icon bg-primary bg-opacity-10 rounded-circle me-3">
                                    <i class="fas fa-plus text-primary"></i>
                                </div>
                                <div class="text-start">
                                    <span class="fw-bold">Add New Item</span>
                                    <small class="d-block text-white-50">Create menu item</small>
                                </div>
                                <i class="fas fa-chevron-right ms-auto"></i>
                            </div>
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-success btn-lg rounded-pill quick-action-btn shadow-sm">
                            <div class="d-flex align-items-center">
                                <div class="action-icon bg-brown bg-opacity-20 rounded-circle me-3">
                                    <i class="fas fa-shopping-cart text-white"></i>
                                </div>
                                <div class="text-start">
                                    <span class="fw-bold">Manage Orders</span>
                                    <small class="d-block text-white-50">View all orders</small>
                                </div>
                                <i class="fas fa-chevron-right ms-auto"></i>
                            </div>
                        </a>
                        <a href="{{ route('admin.menu.index') }}" class="btn btn-info btn-lg rounded-pill quick-action-btn quick-action-light shadow-sm">
                            <div class="d-flex align-items-center">
                                <div class="action-icon bg-white bg-opacity-75 rounded-circle me-3">
                                    <i class="fas fa-boxes text-dark"></i>
                                </div>
                                <div class="text-start">
                                    <span class="fw-bold">View Inventory</span>
                                    <small class="d-block text-body-secondary">Check stock levels</small>
                                </div>
                                <i class="fas fa-chevron-right ms-auto"></i>
                            </div>
                        </a>
                    </div>
                    <hr class="my-4">
                    <!-- Quick Stats -->
                    <div class="quick-stats">
                        <h6 class="text-muted fw-bold mb-3">
                            <i class="fas fa-chart-pie me-2"></i>Order Status Overview
                        </h6>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                                <i class="fas fa-clock me-1"></i>Pending
                            </span>
                            <span class="badge bg-success bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                <i class="fas fa-fire me-1"></i>Preparing
                            </span>
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                <i class="fas fa-check-circle me-1"></i>Ready
                            </span>
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                <i class="fas fa-truck me-1"></i>Delivered
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stats-card {
        border: none;
        border-radius: 1.5rem !important;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stats-card .card-body {
        padding: 1.5rem;
        min-height: 120px;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .stats-icon {
        position: absolute;
        top: 26px;
        right: 26px;
        width: 56px;
        height: 56px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }
    .stats-content {
        padding-right: 110px;
        position: relative;
        z-index: 1;
    }
    @media (max-width: 575.98px) {
        .stats-card .card-body {
            padding: 1.25rem;
            min-height: 110px;
        }
        .stats-icon {
            width: 48px;
            height: 48px;
            font-size: 1.2rem;
        }
        .stats-content {
            padding-right: 96px;
        }
        .order-item .avatar-circle {
            width: 42px;
            height: 42px;
            border-radius: 12px;
        }
    }
    .card-header {
        border-radius: 1.5rem 1.5rem 0 0 !important;
        padding-left: 1.75rem;
        padding-right: 1.75rem;
    }
    .card-header h5 i {
        margin-right: 0.5rem;
    }
    .avatar-circle {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .order-item {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
        border-radius: 1rem;
    }
    .order-item .order-avatar {
        margin-right: 1rem;
    }
    .order-item .avatar-circle {
        width: 48px;
        height: 48px;
        border-radius: 14px;
    }
    .order-item .d-flex.align-items-center {
        gap: 0.75rem;
    }
    .order-item:hover {
        background-color: #f8f9fa;
        border-left-color: #6F4E37;
        box-shadow: 0 2px 12px 0 rgba(0,0,0,0.04);
    }
    .quick-action-btn {
        padding: 1rem 1.25rem;
        transition: all 0.3s ease;
    }
    .quick-action-light,
    .quick-action-light * {
        color: #0f172a !important;
    }
    .quick-action-light .text-body-secondary {
        color: rgba(15, 23, 42, 0.7) !important;
    }
    .quick-action-light .action-icon {
        background-color: rgba(255, 255, 255, 0.8) !important;
    }
    .quick-action-btn:hover {
        transform: translateX(5px);
    }
    .action-icon {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartCanvas = document.getElementById('ordersChart');
    if (!chartCanvas) return;
    
    const ordersCtx = chartCanvas.getContext('2d');
    const gradient = ordersCtx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(111, 78, 55, 0.3)');
    gradient.addColorStop(1, 'rgba(111, 78, 55, 0.0)');
    
    new Chart(ordersCtx, {
        type: 'line',
        data: {
            labels: @json(array_keys($ordersByDay)),
            datasets: [{
                label: 'Orders',
                data: @json(array_values($ordersByDay)),
                borderColor: '#6F4E37',
                backgroundColor: gradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#6F4E37',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#6F4E37',
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    padding: 12,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, font: { size: 12 } },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    ticks: { font: { size: 11 } },
                    grid: { display: false }
                }
            }
        }
    });
});
</script>
@endsection