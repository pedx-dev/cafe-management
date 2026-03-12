@extends('layouts.app')

@section('title', 'Manage Menu Items - Admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="row mb-4" data-aos="fade-down">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="display-5 fw-bold text-gradient mb-1">
                        <i class="fas fa-utensils me-2"></i>Menu Management
                    </h1>
                    <p class="text-muted mb-0">Manage your café menu items and inventory</p>
                </div>
                <a href="{{ route('admin.menu.create') }}" class="btn btn-cafe btn-lg rounded-pill mt-3 mt-md-0">
                    <i class="fas fa-plus me-2"></i>Add New Item
                </a>
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert" data-aos="fade-in">
            <div class="d-flex align-items-center">
                <div class="alert-icon me-3">
                    <i class="fas fa-exclamation-circle fa-2x"></i>
                </div>
                <div>
                    <strong>Error!</strong>
                    <p class="mb-0">{{ session('error') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Row -->
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-md-3 col-6 mb-3">
            <div class="card-cafe text-center py-3">
                <div class="text-primary mb-2">
                    <i class="fas fa-coffee fa-2x"></i>
                </div>
                <h4 class="fw-bold mb-0">{{ $menuItems->total() }}</h4>
                <small class="text-muted">Total Items</small>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card-cafe text-center py-3">
                <div class="text-success mb-2">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
                <h4 class="fw-bold mb-0">{{ $menuItems->where('is_available', true)->count() }}</h4>
                <small class="text-muted">Available</small>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card-cafe text-center py-3">
                <div class="text-warning mb-2">
                    <i class="fas fa-star fa-2x"></i>
                </div>
                <h4 class="fw-bold mb-0">{{ $menuItems->where('is_featured', true)->count() }}</h4>
                <small class="text-muted">Featured</small>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card-cafe text-center py-3">
                <div class="text-danger mb-2">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
                <h4 class="fw-bold mb-0">{{ $menuItems->where('stock', '<=', 10)->count() }}</h4>
                <small class="text-muted">Low Stock</small>
            </div>
        </div>
    </div>

    <!-- Menu Items Table -->
    <div class="card-cafe overflow-hidden" data-aos="fade-up">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-gradient-primary text-white">
                    <tr>
                        <th class="ps-4">Item</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Availability</th>
                        <th>Featured</th>
                        <th class="text-center pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menuItems as $item)
                        <tr class="menu-item-row">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/' . $item->image) }}" 
                                             alt="{{ $item->name }}" 
                                             class="rounded-3 shadow-sm" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                        @if(!$item->is_available)
                                            <span class="position-absolute top-0 start-0 translate-middle badge bg-danger rounded-pill" style="font-size: 0.6rem;">
                                                <i class="fas fa-times"></i>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="fw-bold mb-1">{{ $item->name }}</h6>
                                        <small class="text-muted">{{ Str::limit($item->description, 40) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-black rounded-pill px-3">
                                    {{ $item->category }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold text-gradient fs-5">₱{{ number_format($item->price, 2) }}</span>
                            </td>
                            <td>
                                @if($item->stock > 10)
                                    <span class="badge bg-black bg-opacity-10 text-black rounded-pill px-3">
                                        <i class="fas fa-box me-1"></i>{{ $item->stock }} items
                                    </span>
                                @elseif($item->stock > 0)
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">
                                        <i class="fas fa-exclamation me-1"></i>{{ $item->stock }} left
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">
                                        <i class="fas fa-times me-1"></i>Out of stock
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($item->is_available)
                                    <span class="badge bg-success rounded-pill px-3">
                                        <i class="fas fa-check me-1"></i>Available
                                    </span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3">
                                        <i class="fas fa-times me-1"></i>Unavailable
                                    </span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.menu.availability', $item->id) }}" method="POST" class="availability-form">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="is_available" value="0">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input availability-toggle" type="checkbox"
                                               name="is_available" value="1" {{ $item->is_available ? 'checked' : '' }}>
                                    </div>
                                </form>
                            </td>
                            <td class="text-center">
                                @if($item->is_featured)
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">
                                        <i class="fas fa-star"></i> Featured
                                    </span>
                                @else
                                    <span class="text-muted">
                                        <i class="far fa-star"></i>
                                    </span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.menu.edit', $item->id) }}" 
                                       class="btn btn-outline-primary btn-sm rounded-pill me-1"
                                       data-bs-toggle="tooltip" title="Edit Item">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <form action="{{ route('admin.menu.destroy', $item->id) }}" 
                                          method="POST" 
                                          class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-outline-danger btn-sm rounded-pill"
                                                data-bs-toggle="tooltip" title="Delete Item">
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
                                    <i class="fas fa-coffee fa-4x text-muted mb-3 d-block"></i>
                                    <h5 class="text-muted">No menu items found</h5>
                                    <p class="text-muted mb-3">Start building your menu by adding your first item!</p>
                                    <a href="{{ route('admin.menu.create') }}" class="btn btn-cafe rounded-pill">
                                        <i class="fas fa-plus me-2"></i>Add First Item
                                    </a>
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
        {{ $menuItems->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
.menu-item-row {
    transition: all 0.3s ease;
}

.menu-item-row:hover {
    background-color: rgba(111, 78, 55, 0.03);
}

.delete-form button {
    transition: all 0.3s ease;
}

.empty-state {
    padding: 40px 20px;
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Delete confirmation with SweetAlert2
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Delete this item?',
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
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    document.querySelectorAll('.availability-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
</script>
@endpush
@endsection
