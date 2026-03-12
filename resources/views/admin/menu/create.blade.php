@extends('layouts.app')

@section('title', 'Add Menu Item - Admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mb-4" data-aos="fade-down">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <span class="badge bg-success px-3 py-2 rounded-pill mb-3">
                        <i class="fas fa-plus-circle me-1"></i> New Item
                    </span>
                    <h1 class="display-5 fw-bold text-gradient mb-1">
                        <i class="fas fa-utensils me-2"></i>Add Menu Item
                    </h1>
                    <p class="text-muted">Create a new item for your café menu</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8" data-aos="fade-up">
            <div class="card-cafe overflow-hidden">
                <div class="card-header bg-gradient-primary text-white py-4">
                    <div class="d-flex align-items-center">
                        <div class="header-icon me-3">
                            <i class="fas fa-coffee fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">Item Details</h4>
                            <small class="text-white-50">Fill in all the required fields</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Item Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">
                                <i class="fas fa-tag me-2 text-primary"></i>Item Name
                            </label>
                            <input type="text" class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
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
                                      placeholder="Describe your menu item..." required>{{ old('description') }}</textarea>
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
                                           id="price" name="price" value="{{ old('price') }}" 
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
                                    <option value="Coffee" {{ old('category') == 'Coffee' ? 'selected' : '' }}>☕ Coffee</option>
                                    <option value="Tea" {{ old('category') == 'Tea' ? 'selected' : '' }}>🍵 Tea</option>
                                    <option value="Pastry" {{ old('category') == 'Pastry' ? 'selected' : '' }}>🥐 Pastry</option>
                                    <option value="Sandwich" {{ old('category') == 'Sandwich' ? 'selected' : '' }}>🥪 Sandwich</option>
                                    <option value="Dessert" {{ old('category') == 'Dessert' ? 'selected' : '' }}>🍰 Dessert</option>
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
                                       id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="mb-4">
                            <label for="image" class="form-label fw-bold">
                                <i class="fas fa-image me-2 text-primary"></i>Item Image
                            </label>
                            <div class="upload-area p-4 rounded-3 text-center" id="uploadArea">
                                <input type="file" class="d-none @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*" required>
                                <div class="upload-placeholder" id="uploadPlaceholder">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                    <h6 class="text-muted mb-2">Drag & drop an image here</h6>
                                    <p class="text-muted small mb-3">or</p>
                                    <button type="button" class="btn btn-outline-primary rounded-pill" onclick="document.getElementById('image').click()">
                                        <i class="fas fa-folder-open me-2"></i>Browse Files
                                    </button>
                                    <p class="text-muted small mt-2">PNG, JPG, JPEG (Max 2MB)</p>
                                </div>
                                <div class="upload-preview d-none" id="uploadPreview">
                                    <img src="" alt="Preview" class="img-fluid rounded-3" style="max-height: 200px;">
                                    <button type="button" class="btn btn-sm btn-danger mt-2 rounded-pill" onclick="clearImage()">
                                        <i class="fas fa-times me-1"></i>Remove
                                    </button>
                                </div>
                            </div>
                            @error('image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Toggles -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-check form-switch p-3 bg-light rounded-3">
                                    <input class="form-check-input" type="checkbox" id="is_available" 
                                           name="is_available" {{ old('is_available', true) ? 'checked' : '' }}
                                           style="width: 3em; height: 1.5em;">
                                    <label class="form-check-label ms-2 fw-semibold" for="is_available">
                                        <i class="fas fa-eye me-2 text-success"></i>Available for Purchase
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch p-3 bg-light rounded-3">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
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
                                <i class="fas fa-save me-2"></i>Create Item
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
