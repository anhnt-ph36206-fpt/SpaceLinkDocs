@extends('layouts.admin')

@section('title', 'Sửa Thương hiệu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-edit me-2"></i> Sửa: {{ $brand->name }}</h2>
    <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
                    <!-- Name -->
                    <div class="mb-3">
                        <label class="form-label">Tên thương hiệu <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $brand->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Slug (readonly) -->
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" class="form-control" value="{{ $brand->slug }}" readonly disabled>
                        <small class="text-muted">Slug sẽ tự động cập nhật khi thay đổi tên</small>
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $brand->description) }}</textarea>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <!-- Current Logo -->
                    @if($brand->logo)
                    <div class="mb-3">
                        <label class="form-label">Logo hiện tại</label>
                        <div class="border rounded p-3 text-center bg-light">
                            <img src="{{ asset('storage/' . $brand->logo) }}" 
                                 alt="{{ $brand->name }}" style="max-height: 100px;">
                        </div>
                    </div>
                    @endif
                    
                    <!-- New Logo -->
                    <div class="mb-3">
                        <label class="form-label">{{ $brand->logo ? 'Thay đổi logo' : 'Logo' }}</label>
                        <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" 
                               accept="image/*">
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Display Order -->
                    <div class="mb-3">
                        <label class="form-label">Thứ tự hiển thị</label>
                        <input type="number" name="display_order" class="form-control" 
                               value="{{ old('display_order', $brand->display_order) }}" min="0">
                    </div>
                    
                    <!-- Is Active -->
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_active" class="form-check-input" 
                                   id="is_active" value="1" {{ old('is_active', $brand->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Kích hoạt</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Cập nhật
                </button>
                <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i> Hủy
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
