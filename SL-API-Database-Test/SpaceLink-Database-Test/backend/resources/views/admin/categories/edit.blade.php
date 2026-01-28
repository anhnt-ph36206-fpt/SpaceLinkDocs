@extends('layouts.admin')

@section('title', 'Sửa Danh mục')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-edit me-2"></i> Sửa: {{ $category->name }}</h2>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
                    <!-- Name -->
                    <div class="mb-3">
                        <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Slug (readonly) -->
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" class="form-control" value="{{ $category->slug }}" readonly disabled>
                    </div>
                    
                    <!-- Parent Category -->
                    <div class="mb-3">
                        <label class="form-label">Danh mục cha</label>
                        <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                            <option value="">-- Không có (Danh mục gốc) --</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" 
                                    {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $category->description) }}</textarea>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <!-- Current Image -->
                    @if($category->image)
                    <div class="mb-3">
                        <label class="form-label">Hình ảnh hiện tại</label>
                        <div class="border rounded p-3 text-center bg-light">
                            <img src="{{ asset('storage/' . $category->image) }}" 
                                 alt="{{ $category->name }}" style="max-height: 100px;">
                        </div>
                    </div>
                    @endif
                    
                    <!-- Icon -->
                    <div class="mb-3">
                        <label class="form-label">Icon (FontAwesome)</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                @if($category->icon)
                                    <i class="fas {{ $category->icon }}"></i>
                                @else
                                    <i class="fas fa-icons"></i>
                                @endif
                            </span>
                            <input type="text" name="icon" class="form-control" 
                                   value="{{ old('icon', $category->icon) }}" placeholder="fa-mobile-alt">
                        </div>
                    </div>
                    
                    <!-- New Image -->
                    <div class="mb-3">
                        <label class="form-label">{{ $category->image ? 'Thay đổi hình ảnh' : 'Hình ảnh' }}</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    
                    <!-- Display Order -->
                    <div class="mb-3">
                        <label class="form-label">Thứ tự hiển thị</label>
                        <input type="number" name="display_order" class="form-control" 
                               value="{{ old('display_order', $category->display_order) }}" min="0">
                    </div>
                    
                    <!-- Is Active -->
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_active" class="form-check-input" 
                                   id="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
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
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i> Hủy
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
