@extends('layouts.admin')

@section('title', 'Thêm Sản phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-plus me-2"></i> Thêm Sản phẩm</h2>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
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
                               value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <!-- Category -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Brand -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Thương hiệu</label>
                            <select name="brand_id" class="form-select">
                                <option value="">-- Chọn thương hiệu --</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- SKU -->
                    <div class="mb-3">
                        <label class="form-label">Mã sản phẩm (SKU)</label>
                        <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" 
                               value="{{ old('sku') }}" placeholder="VD: IP16PM-256-BLK">
                        @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label">Mô tả ngắn</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>
                    
                    <!-- Content -->
                    <div class="mb-3">
                        <label class="form-label">Nội dung chi tiết</label>
                        <textarea name="content" class="form-control" rows="6" 
                                  placeholder="Mô tả chi tiết sản phẩm (hỗ trợ HTML)">{{ old('content') }}</textarea>
                    </div>
                </div>
            </div>
            
            <!-- Images -->
            <div class="card mb-4">
                <div class="card-header">Hình ảnh sản phẩm</div>
                <div class="card-body">
                    <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                    <small class="text-muted">
                        Có thể chọn nhiều ảnh. Ảnh đầu tiên sẽ là ảnh chính. Tối đa 2MB/ảnh.
                    </small>
                </div>
            </div>
            
            <!-- SEO -->
            <div class="card">
                <div class="card-header">SEO</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description') }}</textarea>
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
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                                   value="{{ old('price') }}" min="0" step="1000" required>
                            <span class="input-group-text">đ</span>
                        </div>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Giá khuyến mãi</label>
                        <div class="input-group">
                            <input type="number" name="sale_price" class="form-control" 
                                   value="{{ old('sale_price') }}" min="0" step="1000">
                            <span class="input-group-text">đ</span>
                        </div>
                        <small class="text-muted">Để trống nếu không khuyến mãi</small>
                    </div>
                </div>
            </div>
            
            <!-- Stock -->
            <div class="card mb-4">
                <div class="card-header">Kho hàng</div>
                <div class="card-body">
                    <label class="form-label">Số lượng tồn kho <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" 
                           value="{{ old('quantity', 0) }}" min="0" required>
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <!-- Status -->
            <div class="card mb-4">
                <div class="card-header">Trạng thái</div>
                <div class="card-body">
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" name="is_active" class="form-check-input" 
                               id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Hiển thị sản phẩm</label>
                    </div>
                    <div class="form-check form-switch">
                        <input type="checkbox" name="is_featured" class="form-check-input" 
                               id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">Sản phẩm nổi bật</label>
                    </div>
                </div>
            </div>
            
            <!-- Submit -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-1"></i> Lưu sản phẩm
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i> Hủy
                </a>
            </div>
        </div>
    </div>
</form>
@endsection
