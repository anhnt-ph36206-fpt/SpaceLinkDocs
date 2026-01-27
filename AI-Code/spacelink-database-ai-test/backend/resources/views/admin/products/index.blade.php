@extends('layouts.admin')

@section('title', 'Quản lý Sản phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-box me-2"></i> Sản phẩm</h2>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Thêm mới
    </a>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" 
                       placeholder="Tìm kiếm..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="category_id" class="form-select">
                    <option value="">-- Danh mục --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="brand_id" class="form-select">
                    <option value="">-- Thương hiệu --</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">-- Trạng thái --</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang bán</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Ẩn</option>
                    <option value="featured" {{ request('status') == 'featured' ? 'selected' : '' }}>Nổi bật</option>
                    <option value="sale" {{ request('status') == 'sale' ? 'selected' : '' }}>Đang giảm giá</option>
                    <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <div class="col-md-1">
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="60">ID</th>
                    <th width="80">Ảnh</th>
                    <th>Sản phẩm</th>
                    <th>Danh mục</th>
                    <th width="130">Giá</th>
                    <th width="80">Tồn</th>
                    <th width="80">Đã bán</th>
                    <th width="100">Trạng thái</th>
                    <th width="120">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                        @if($product->primaryImage)
                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" 
                                 alt="{{ $product->name }}" width="50" height="50" 
                                 style="object-fit: cover; border-radius: 5px;">
                        @else
                            <div class="bg-light border rounded d-flex align-items-center justify-content-center" 
                                 style="width:50px; height:50px;">
                                <i class="fas fa-image text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ Str::limit($product->name, 40) }}</strong>
                        @if($product->is_featured)
                            <span class="badge bg-warning text-dark ms-1">⭐</span>
                        @endif
                        <br>
                        <small class="text-muted">SKU: {{ $product->sku ?? 'N/A' }}</small>
                    </td>
                    <td>
                        <a href="{{ route('admin.categories.show', $product->category) }}">
                            {{ $product->category->name }}
                        </a>
                        @if($product->brand)
                            <br><small class="text-muted">{{ $product->brand->name }}</small>
                        @endif
                    </td>
                    <td>
                        @if($product->sale_price)
                            <span class="text-decoration-line-through text-muted">
                                {{ number_format($product->price) }}đ
                            </span>
                            <br>
                            <strong class="text-danger">{{ number_format($product->sale_price) }}đ</strong>
                            <span class="badge bg-danger">-{{ $product->discount_percent }}%</span>
                        @else
                            <strong>{{ number_format($product->price) }}đ</strong>
                        @endif
                    </td>
                    <td>
                        @if($product->quantity > 10)
                            <span class="text-success">{{ $product->quantity }}</span>
                        @elseif($product->quantity > 0)
                            <span class="text-warning">{{ $product->quantity }}</span>
                        @else
                            <span class="text-danger">Hết</span>
                        @endif
                    </td>
                    <td>{{ $product->sold_count }}</td>
                    <td>
                        @if($product->is_active)
                            <span class="badge bg-success">Đang bán</span>
                        @else
                            <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.products.show', $product) }}" 
                               class="btn btn-outline-info" title="Xem">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product) }}" 
                               class="btn btn-outline-warning" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" 
                                  method="POST" style="display:inline"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2"></i><br>
                        Không có dữ liệu
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <span class="text-muted">
                Hiển thị {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} 
                / {{ $products->total() }} sản phẩm
            </span>
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
