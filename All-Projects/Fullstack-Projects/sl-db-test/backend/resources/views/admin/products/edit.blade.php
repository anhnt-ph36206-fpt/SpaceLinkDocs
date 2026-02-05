@extends('layouts.admin')

@section('title', 'Sửa: ' . $product->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-edit me-2"></i> Sửa: {{ Str::limit($product->name, 30) }}</h2>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row">
        <!-- Left Column -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">Thông tin cơ bản</div>
                <div class="card-body">
                    <!-- Name -->
                    <div class="mb-3">
                        <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Slug -->
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" class="form-control" value="{{ $product->slug }}" readonly disabled>
                    </div>
                    
                    <div class="row">
                        <!-- Category -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" 
                                        {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Brand -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Thương hiệu</label>
                            <select name="brand_id" class="form-select">
                                <option value="">-- Không có --</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" 
                                        {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- SKU -->
                    <div class="mb-3">
                        <label class="form-label">Mã sản phẩm (SKU)</label>
                        <input type="text" name="sku" class="form-control" 
                               value="{{ old('sku', $product->sku) }}">
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label">Mô tả ngắn</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
                    </div>
                    
                    <!-- Content -->
                    <div class="mb-3">
                        <label class="form-label">Nội dung chi tiết</label>
                        <textarea name="content" class="form-control" rows="6">{{ old('content', $product->content) }}</textarea>
                    </div>
                </div>
            </div>
            
            <!-- Current Images -->
            @if($product->images->count() > 0)
            <div class="card mb-4">
                <div class="card-header">Hình ảnh hiện tại ({{ $product->images->count() }})</div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($product->images as $image)
                        <div class="col-md-3">
                            <div class="position-relative border rounded p-2">
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                     alt="Image" class="img-fluid">
                                     
                                @if($image->is_primary)
                                    <span class="badge bg-success position-absolute top-0 start-0 m-1">Chính</span>
                                @endif
                                
                                <div class="mt-2 d-flex gap-1">
                                    @if(!$image->is_primary)
                                    <form action="{{ route('admin.products.images.primary', $image) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Đặt làm ảnh chính">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    </form>
                                    @endif
                                    
                                    <form action="{{ route('admin.products.images.delete', $image) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Xóa ảnh này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Add New Images -->
            <div class="card mb-4">
                <div class="card-header">Thêm hình ảnh mới</div>
                <div class="card-body">
                    <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                </div>
            </div>
            
            <!-- SEO -->
            <div class="card">
                <div class="card-header">SEO</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control" 
                               value="{{ old('meta_title', $product->meta_title) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description', $product->meta_description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column -->
        <div class="col-md-4">
            <!-- Price -->
            <div class="card mb-4">
                <div class="card-header">Giá bán</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Giá gốc <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="price" class="form-control" 
                                   value="{{ old('price', $product->price) }}" min="0" step="1000" required>
                            <span class="input-group-text">đ</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Giá khuyến mãi</label>
                        <div class="input-group">
                            <input type="number" name="sale_price" class="form-control" 
                                   value="{{ old('sale_price', $product->sale_price) }}" min="0" step="1000">
                            <span class="input-group-text">đ</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Stock -->
            <div class="card mb-4">
                <div class="card-header">Kho hàng</div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label">Số lượng tồn kho</label>
                        <input type="number" name="quantity" class="form-control" 
                               value="{{ old('quantity', $product->quantity) }}" min="0" required>
                    </div>
                    <small class="text-muted">
                        Đã bán: <strong>{{ $product->sold_count }}</strong> | 
                        Lượt xem: <strong>{{ $product->view_count }}</strong>
                    </small>
                </div>
            </div>
            
            <!-- Status -->
            <div class="card mb-4">
                <div class="card-header">Trạng thái</div>
                <div class="card-body">
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" name="is_active" class="form-check-input" 
                               id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Hiển thị sản phẩm</label>
                    </div>
                    <div class="form-check form-switch">
                        <input type="checkbox" name="is_featured" class="form-check-input" 
                               id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">Sản phẩm nổi bật</label>
                    </div>
                </div>
            </div>
            
            <!-- Submit -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-1"></i> Cập nhật
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i> Hủy
                </a>
            </div>
        </div>
    </div>
</form>
@endsection
