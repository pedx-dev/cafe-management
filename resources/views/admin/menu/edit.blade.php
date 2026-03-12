@extends('layouts.app')

@section('title', 'Edit Menu Item - Admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mb-4" data-aos="fade-down">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <span class="badge bg-primary px-3 py-2 rounded-pill mb-3">
                        <i class="fas fa-edit me-1"></i> Editing
                    </span>
                    <h1 class="display-5 fw-bold text-gradient mb-1">
                        <i class="fas fa-utensils me-2"></i>Edit Menu Item
                    </h1>
                    <p class="text-muted">Update details for <strong>{{ $menu->name }}</strong></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8" data-aos="fade-up">
            <div class="card-cafe overflow-hidden">
                <div class="card-header bg-gradient-primary text-white py-4 px-4">
                    <div class="d-flex align-items-center gap-4 flex-wrap menu-header">
                        <img src="{{ asset('storage/' . $menu->image) }}" 
                             alt="{{ $menu->name }}" 
                             class="rounded-3 border border-2 border-white menu-header-image">
                        <div class="menu-header-text">
                            <h4 class="mb-1 fw-bold">{{ $menu->name }}</h4>
                            <div class="d-flex flex-wrap align-items-center gap-3 text-white-50">
                                <span class="d-inline-flex align-items-center gap-2">
                                    <i class="fas fa-folder"></i>{{ $menu->category }}
                                </span>
                                <span class="d-inline-flex align-items-center gap-2">
                                    <i class="fas fa-box"></i>{{ $menu->stock }} in stock
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Item Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">
                                <i class="fas fa-tag me-2 text-primary"></i>Item Name
                            </label>
                            <input type="text" class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $menu->name) }}" 
                                   placeholder="e.g., Caramel Macchiato" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">
                                <i class="fas fa-align-left me-2 text-primary"></i>Description
                            </label>
                            <textarea class="form-control rounded-3 @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Describe your menu item..." required>{{ old('description', $menu->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Price -->
                            <div class="col-md-4 mb-4">
                                <label for="price" class="form-label fw-bold">
                                    <i class="fas fa-peso-sign me-2 text-primary"></i>Price (₱)
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">₱</span>
                                    <input type="number" step="0.01" 
                                           class="form-control form-control-lg @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price', $menu->price) }}" 
                                           placeholder="0.00" required>
                                </div>
                                @error('price')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="col-md-4 mb-4">
                                <label for="category" class="form-label fw-bold">
                                    <i class="fas fa-folder me-2 text-primary"></i>Category
                                </label>
                                <select class="form-select form-select-lg @error('category') is-invalid @enderror" 
                                        id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="Coffee" {{ old('category', $menu->category) == 'Coffee' ? 'selected' : '' }}>☕ Coffee</option>
                                    <option value="Tea" {{ old('category', $menu->category) == 'Tea' ? 'selected' : '' }}>🍵 Tea</option>
                                    <option value="Pastry" {{ old('category', $menu->category) == 'Pastry' ? 'selected' : '' }}>🥐 Pastry</option>
                                    <option value="Sandwich" {{ old('category', $menu->category) == 'Sandwich' ? 'selected' : '' }}>🥪 Sandwich</option>
                                    <option value="Dessert" {{ old('category', $menu->category) == 'Dessert' ? 'selected' : '' }}>🍰 Dessert</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Stock -->
                            <div class="col-md-4 mb-4">
                                <label for="stock" class="form-label fw-bold">
                                    <i class="fas fa-boxes me-2 text-primary"></i>Stock Quantity
                                </label>
                                <input type="number" class="form-control form-control-lg @error('stock') is-invalid @enderror" 
                                       id="stock" name="stock" value="{{ old('stock', $menu->stock) }}" min="0" required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Current Image & Upload -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-image me-2 text-primary"></i>Item Image
                            </label>
                            
                            <div class="row align-items-center">
                                <div class="col-md-4 mb-3">
                                    <div class="current-image-wrapper text-center p-3 bg-light rounded-3">
                                        <p class="small text-muted mb-2">Current Image</p>
                                        <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" 
                                             class="rounded-3 shadow-sm" style="max-width: 150px; height: auto;">
                                    </div>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <div class="upload-area p-4 rounded-3 text-center" id="uploadArea">
                                        <input type="file" class="d-none @error('image') is-invalid @enderror" 
                                               id="image" name="image" accept="image/*">
                                        <div class="upload-placeholder" id="uploadPlaceholder">
                                            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                            <h6 class="text-muted mb-2">Change Image (Optional)</h6>
                                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" onclick="document.getElementById('image').click()">
                                                <i class="fas fa-folder-open me-1"></i>Browse Files
                                            </button>
                                        </div>
                                        <div class="upload-preview d-none" id="uploadPreview">
                                            <p class="small text-success mb-2"><i class="fas fa-check me-1"></i>New Image Selected</p>
                                            <img src="" alt="Preview" class="img-fluid rounded-3" style="max-height: 120px;">
                                            <button type="button" class="btn btn-sm btn-danger mt-2 rounded-pill" onclick="clearImage()">
                                                <i class="fas fa-times me-1"></i>Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Toggles -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-check form-switch p-6 bg-light rounded-3">
                                    <input class="form-check-input" type="checkbox" id="is_available" 
                                           name="is_available" {{ old('is_available', $menu->is_available) ? 'checked' : '' }}
                                           style="width: 3em; height: 1.5em;">
                                    <label class="form-check-label ms-1 fw-semibold" for="is_available">
                                        <i class="fas fa-eye me-2 text-success"></i>Available for Purchase
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch p-6 bg-light rounded-3">
                                    <input class="form-check-input" type="checkbox" id="is_featured" 
                                           name="is_featured" {{ old('is_featured', $menu->is_featured) ? 'checked' : '' }}
                                           style="width: 3em; height: 1.5em;">
                                    <label class="form-check-label ms-2 fw-semibold" for="is_featured">
                                        <i class="fas fa-star me-2 text-warning"></i>Featured Item
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-cafe btn-lg flex-grow-1 rounded-pill">
                                <i class="fas fa-save me-2"></i>Update Item
                            </button>
                            <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.menu-header {
    min-height: 72px;
    row-gap: 0.75rem;
}

.menu-header-image {
    width: 64px;
    height: 64px;
    object-fit: cover;
}

.menu-header-text h4 {
    line-height: 1.2;
}

.form-label i {
    width: 18px;
    text-align: center;
}

.upload-area {
    border: 2px dashed #dee2e6;
    background: #fafafa;
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-area:hover, .upload-area.dragover {
    border-color: #6F4E37;
    background: rgba(111, 78, 55, 0.05);
}

.current-image-wrapper {
    border: 2px solid #e9ecef;
}

@media (max-width: 575.98px) {
    .menu-header-image {
        width: 56px;
        height: 56px;
    }
}
</style>

@push('scripts')
<script>
    // Image preview
    const imageInput = document.getElementById('image');
    const uploadArea = document.getElementById('uploadArea');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const uploadPreview = document.getElementById('uploadPreview');
    
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                uploadPlaceholder.classList.add('d-none');
                uploadPreview.classList.remove('d-none');
                uploadPreview.querySelector('img').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
    
    function clearImage() {
        imageInput.value = '';
        uploadPlaceholder.classList.remove('d-none');
        uploadPreview.classList.add('d-none');
    }
    
    // Drag and drop
    uploadArea.addEventListener('click', () => imageInput.click());
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });
    uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('dragover'));
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        imageInput.files = e.dataTransfer.files;
        imageInput.dispatchEvent(new Event('change'));
    });
</script>
@endpush
@endsection
