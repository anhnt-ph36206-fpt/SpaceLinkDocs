@extends('layouts.admin')

@section('title', $product->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ $product->name }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Sửa
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>
</div>

<div class="row">
    <!-- Left Column -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">Thông tin chi tiết</div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">ID:</th>
                        <td>{{ $product->id }}</td>
                    </tr>
                    <tr>
                        <th>SKU:</th>
                        <td><code>{{ $product->sku ?? 'N/A' }}</code></td>
                    </tr>
                    <tr>
                        <th>Slug:</th>
                        <td><code>{{ $product->slug }}</code></td>
                    </tr>
                    <tr>
                        <th>Danh mục:</th>
                        <td>
                            <a href="{{ route('admin.categories.show', $product->category) }}">
                                {{ $product->category->name }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Thương hiệu:</th>
                        <td>
                            @if($product->brand)
                                <a href="{{ route('admin.brands.show', $product->brand) }}">
                                    {{ $product->brand->name }}
                                </a>
                            @else
                                <span class="text-muted">Không có</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Giá gốc:</th>
                        <td><strong>{{ number_format($product->price) }}đ</strong></td>
                    </tr>
                    @if($product->sale_price)
                    <tr>
                        <th>Giá khuyến mãi:</th>
                        <td>
                            <strong class="text-danger">{{ number_format($product->sale_price) }}đ</strong>
                            <span class="badge bg-danger">-{{ $product->discount_percent }}%</span>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th>Tồn kho:</th>
                        <td>
                            @if($product->in_stock)
                                <span class="text-success">{{ $product->quantity }} sản phẩm</span>
                            @else
                                <span class="text-danger">Hết hàng</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Đã bán:</th>
                        <td>{{ $product->sold_count }}</td>
                    </tr>
                    <tr>
                        <th>Lượt xem:</th>
                        <td>{{ $product->view_count }}</td>
                    </tr>
                    <tr>
                        <th>Trạng thái:</th>
                        <td>
                            @if($product->is_active)
                                <span class="badge bg-success">Đang bán</span>
                            @else
                                <span class="badge bg-secondary">Ẩn</span>
                            @endif
                            @if($product->is_featured)
                                <span class="badge bg-warning text-dark">⭐ Nổi bật</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Ngày tạo:</th>
                        <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Description -->
        @if($product->description)
        <div class="card mb-4">
            <div class="card-header">Mô tả ngắn</div>
            <div class="card-body">
                {{ $product->description }}
            </div>
        </div>
        @endif
        
        <!-- Content -->
        @if($product->content)
        <div class="card mb-4">
            <div class="card-header">Nội dung chi tiết</div>
            <div class="card-body">
                {!! $product->content !!}
            </div>
        </div>
        @endif
    </div>
    
    <!-- Right Column -->
    <div class="col-md-4">
        <!-- Images -->
        <div class="card mb-4">
            <div class="card-header">Hình ảnh ({{ $product->images->count() }})</div>
            <div class="card-body">
                @if($product->images->count() > 0)
                    <div class="row g-2">
                        @foreach($product->images as $image)
                        <div class="col-6">
                            <div class="position-relative">
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                     alt="{{ $product->name }}" class="img-fluid rounded">
                                @if($image->is_primary)
                                    <span class="badge bg-success position-absolute top-0 start-0 m-1">Chính</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0 text-center">Chưa có hình ảnh</p>
                @endif
            </div>
        </div>
        
        <!-- SEO -->
        <div class="card">
            <div class="card-header">SEO</div>
            <div class="card-body">
                <p><strong>Meta Title:</strong><br>{{ $product->meta_title ?? $product->name }}</p>
                <p class="mb-0"><strong>Meta Description:</strong><br>{{ $product->meta_description ?? Str::limit($product->description, 160) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
