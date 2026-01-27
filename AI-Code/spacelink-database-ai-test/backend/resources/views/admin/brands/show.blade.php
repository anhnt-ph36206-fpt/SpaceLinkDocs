@extends('layouts.admin')

@section('title', $brand->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-copyright me-2"></i> {{ $brand->name }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Sửa
        </a>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">
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
                        <td>{{ $brand->id }}</td>
                    </tr>
                    <tr>
                        <th>Tên:</th>
                        <td><strong>{{ $brand->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Slug:</th>
                        <td><code>{{ $brand->slug }}</code></td>
                    </tr>
                    <tr>
                        <th>Mô tả:</th>
                        <td>{{ $brand->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Thứ tự:</th>
                        <td>{{ $brand->display_order }}</td>
                    </tr>
                    <tr>
                        <th>Trạng thái:</th>
                        <td>
                            @if($brand->is_active)
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-danger">Không hoạt động</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Ngày tạo:</th>
                        <td>{{ $brand->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Cập nhật:</th>
                        <td>{{ $brand->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Products of this brand -->
        <div class="card">
            <div class="card-header">
                Sản phẩm ({{ $brand->products->count() }})
            </div>
            <div class="card-body">
                @if($brand->products->count() > 0)
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên sản phẩm</th>
                                <th>Giá</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brand->products->take(10) as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    <a href="{{ route('admin.products.show', $product) }}">
                                        {{ $product->name }}
                                    </a>
                                </td>
                                <td>{{ number_format($product->price) }}đ</td>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($brand->products->count() > 10)
                        <p class="text-muted mb-0">
                            ... và {{ $brand->products->count() - 10 }} sản phẩm khác
                        </p>
                    @endif
                @else
                    <p class="text-muted mb-0">Chưa có sản phẩm</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Logo</div>
            <div class="card-body text-center">
                @if($brand->logo)
                    <img src="{{ asset('storage/' . $brand->logo) }}" 
                         alt="{{ $brand->name }}" class="img-fluid" style="max-height: 200px;">
                @else
                    <p class="text-muted mb-0">Chưa có logo</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
