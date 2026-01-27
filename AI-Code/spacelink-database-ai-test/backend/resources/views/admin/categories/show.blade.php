@extends('layouts.admin')

@section('title', $category->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        @if($category->icon)
            <i class="fas {{ $category->icon }} me-2"></i>
        @endif
        {{ $category->name }}
    </h2>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Sửa
        </a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">Thông tin chi tiết</div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">ID:</th>
                        <td>{{ $category->id }}</td>
                    </tr>
                    <tr>
                        <th>Tên:</th>
                        <td><strong>{{ $category->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Slug:</th>
                        <td><code>{{ $category->slug }}</code></td>
                    </tr>
                    <tr>
                        <th>Đường dẫn:</th>
                        <td><code>{{ $category->full_path }}</code></td>
                    </tr>
                    <tr>
                        <th>Danh mục cha:</th>
                        <td>
                            @if($category->parent)
                                <a href="{{ route('admin.categories.show', $category->parent) }}">
                                    {{ $category->parent->name }}
                                </a>
                            @else
                                <span class="badge bg-primary">Danh mục gốc</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Mô tả:</th>
                        <td>{{ $category->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Cấp độ:</th>
                        <td>Level {{ $category->depth }}</td>
                    </tr>
                    <tr>
                        <th>Trạng thái:</th>
                        <td>
                            @if($category->is_active)
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-danger">Không hoạt động</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Children Categories -->
        @if($category->children->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                Danh mục con ({{ $category->children->count() }})
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Số sản phẩm</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($category->children as $child)
                        <tr>
                            <td>{{ $child->id }}</td>
                            <td>
                                <a href="{{ route('admin.categories.show', $child) }}">
                                    {{ $child->name }}
                                </a>
                            </td>
                            <td><span class="badge bg-info">{{ $child->products()->count() }}</span></td>
                            <td>
                                @if($child->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        
        <!-- Products -->
        <div class="card">
            <div class="card-header">
                Sản phẩm ({{ $category->products->count() }})
            </div>
            <div class="card-body">
                @if($category->products->count() > 0)
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên sản phẩm</th>
                                <th>Giá</th>
                                <th>Tồn kho</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->products->take(10) as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    <a href="{{ route('admin.products.show', $product) }}">
                                        {{ $product->name }}
                                    </a>
                                </td>
                                <td>{{ number_format($product->price) }}đ</td>
                                <td>{{ $product->quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted mb-0">Chưa có sản phẩm</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Hình ảnh</div>
            <div class="card-body text-center">
                @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" 
                         alt="{{ $category->name }}" class="img-fluid" style="max-height: 200px;">
                @else
                    <p class="text-muted mb-0">Chưa có hình ảnh</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
