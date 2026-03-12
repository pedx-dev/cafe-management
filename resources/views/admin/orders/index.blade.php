@extends('layouts.app')

@section('title', 'Manage Orders - Admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="row mb-4" data-aos="fade-down">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="display-5 fw-bold text-gradient mb-1">
                        <i class="fas fa-shopping-cart me-2"></i>Order Management
                    </h1>
                    <p class="text-muted mb-0">View and manage all customer orders</p>
                </div>
                <div class="d-flex gap-2 mt-3 mt-md-0 flex-wrap">
                    <div class="stat-badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                        <i class="fas fa-clock me-1"></i>
                        <span class="fw-bold">{{ $statusCounts['pending'] ?? 0 }}</span> Pending
                    </div>
                    <div class="stat-badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                        <i class="fas fa-fire me-1"></i>
                        <span class="fw-bold">{{ $statusCounts['preparing'] ?? 0 }}</span> Preparing
                    </div>
                    <div class="stat-badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                        <i class="fas fa-check-double me-1"></i>
                        <span class="fw-bold">{{ $statusCounts['ready'] ?? 0 }}</span> Ready
                    </div>
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

    <!-- Quick Filter Tabs -->
    <div class="card-cafe mb-4" data-aos="fade-up">
        <div class="card-body py-3">
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <span class="text-muted me-2 fw-bold"><i class="fas fa-filter me-1"></i>Filter:</span>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-cafe' : 'btn-outline-secondary' }} rounded-pill px-4">
                    <i class="fas fa-list me-1"></i>All <span class="badge bg-white text-dark ms-1">{{ $orders->total() }}</span>
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') == 'pending' ? 'btn-warning' : 'btn-outline-warning' }} rounded-pill px-3">
                    <i class="fas fa-clock me-1"></i>Pending
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}" class="btn btn-sm {{ request('status') == 'confirmed' ? 'btn-info' : 'btn-outline-info' }} rounded-pill px-3">
                    <i class="fas fa-check me-1"></i>Confirmed
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'preparing']) }}" class="btn btn-sm {{ request('status') == 'preparing' ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-3">
                    <i class="fas fa-fire me-1"></i>Preparing
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'ready']) }}" class="btn btn-sm {{ request('status') == 'ready' ? 'btn-success' : 'btn-outline-success' }} rounded-pill px-3">
                    <i class="fas fa-check-double me-1"></i>Ready
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="btn btn-sm {{ request('status') == 'delivered' ? 'btn-success' : 'btn-outline-success' }} rounded-pill px-3">
                    <i class="fas fa-truck me-1"></i>Delivered
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="btn btn-sm {{ request('status') == 'cancelled' ? 'btn-danger' : 'btn-outline-danger' }} rounded-pill px-3">
                    <i class="fas fa-times me-1"></i>Cancelled
                </a>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card-cafe overflow-hidden" data-aos="fade-up">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-gradient-primary text-white">
                    <tr>
                        <th class="py-3 ps-4">Order</th>
                        <th class="py-3">Customer</th>
                        <th class="py-3">Items</th>
                        <th class="py-3">Total</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Date</th>
                        <th class="py-3 text-center">Quick Update</th>
                        <th class="py-3 text-center pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="order-row {{ $order->status == 'pending' ? 'pending-highlight' : '' }}">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="order-number-badge me-2">
                                        <span class="fw-bold text-gradient fs-5">#{{ $order->id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="customer-avatar bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                        <span class="text-white fw-bold">{{ strtoupper(substr($order->user->name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $order->user->name }}</h6>
                                        <small class="text-muted">{{ $order->user->email }}</small>
                                        @if($order->user->phone)
                                            <br><small class="text-muted"><i class="fas fa-phone me-1"></i>{{ $order->user->phone }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="items-preview">
                                    @foreach($order->items->take(3) as $item)
                                        <img src="{{ asset('storage/' . $item->menuItem->image) }}" 
                                             alt="{{ $item->menuItem->name }}"
                                             class="item-thumb rounded-circle border border-2 border-white"
                                             style="width: 32px; height: 32px; object-fit: cover; margin-left: -8px;"
                                             data-bs-toggle="tooltip" title="{{ $item->menuItem->name }} (x{{ $item->quantity }})">
                                    @endforeach
                                    @if($order->items->count() > 3)
                                        <span class="badge bg-secondary rounded-circle" style=" text-black width: 32px; height: 32px; line-height: 24px; margin-left: -8px;">
                                            +{{ $order->items->count() - 3 }}
                                        </span>
                                    @endif
                                    <span class=" text-black badge bg-info bg-opacity-10 text-info rounded-pill ms-2">
                                        {{ $order->items->count() }} items
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold text-gradient fs-5">₱{{ number_format($order->total_amount, 2) }}</span>
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'confirmed' => 'success',
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
                                <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded-pill px-3 py-2">
                                    <i class="fas fa-{{ $icon }} me-1"></i>{{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="date-info">
                                    <span class="fw-semibold">{{ $order->created_at->format('M d, Y') }}</span>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>{{ $order->created_at->format('h:i A') }}
                                    </small>
                                </div>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-select form-select-sm status-select rounded-pill" style="width: auto;" onchange="this.form.submit()">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                        <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>✅ Confirmed</option>
                                        <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>🔥 Preparing</option>
                                        <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>✔️ Ready</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>🚚 Delivered</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                                    </select>
                                </form>
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" 
                                       class="btn btn-sm btn-outline-primary rounded-pill me-1"
                                       data-bs-toggle="tooltip" title="View Details">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-info rounded-pill me-1" onclick="fetchTracking({{ $order->id }})" data-bs-toggle="tooltip" title="Track Delivery">
                                        <i class="fas fa-truck me-1"></i>Track
                                    </button>
                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" 
                                          method="POST" 
                                          class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" 
                                                data-bs-toggle="tooltip" title="Delete Order">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-inbox fa-4x text-muted mb-3 d-block"></i>
                                    <h5 class="text-muted">No orders found</h5>
                                    <p class="text-muted">Orders will appear here when customers place them</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center" data-aos="fade-up">
        {{ $orders->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
.stat-badge {
    font-size: 0.875rem;
}

.order-row {
    transition: all 0.3s ease;
}

.order-row:hover {
    background-color: rgba(111, 78, 55, 0.03);
}

.pending-highlight {
    background-color: rgba(255, 193, 7, 0.08);
    border-left: 3px solid #ffc107;
}

.pending-highlight:hover {
    background-color: rgba(255, 193, 7, 0.12);
}

.items-preview {
    display: flex;
    align-items: center;
}

.items-preview .item-thumb:first-child {
    margin-left: 0 !important;
}

.status-select {
    border: 2px solid #e9ecef;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.status-select:focus {
    border-color: #6F4E37;
    box-shadow: 0 0 0 0.2rem rgba(111, 78, 55, 0.15);
}

.customer-avatar {
    font-size: 1.1rem;
}

.empty-state {
    padding: 40px 20px;
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
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

    function fetchTracking(orderId) {
        fetch(`/api/orders/${orderId}/tracking`)
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    Swal.fire('Tracking Not Found', data.error, 'error');
                } else {
                    Swal.fire({
                        title: 'Delivery Tracking',
                        html: `<b>Status:</b> ${data.status}<br><b>ETA:</b> ${data.eta || 'N/A'}<br><b>Location:</b> (${data.location.lat}, ${data.location.lng})`,
                        icon: 'info'
                    });
                }
            })
            .catch(() => {
                Swal.fire('Error', 'Could not fetch tracking info.', 'error');
            });
    }
</script>
@endpush
@endsection
