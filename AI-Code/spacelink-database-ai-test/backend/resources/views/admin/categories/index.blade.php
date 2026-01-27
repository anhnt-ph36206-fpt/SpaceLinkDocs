@extends('layouts.admin')

@section('title', 'Quản lý Danh mục')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-th-large me-2"></i> Danh mục</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
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
            <div class="col-md-3">
                <select name="parent_id" class="form-select">
                    <option value="">-- Danh mục cha --</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">-- Trạng thái --</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="fas fa-search"></i> Lọc
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-redo"></i> Reset
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
                    <th>Tên danh mục</th>
                    <th>Danh mục cha</th>
                    <th width="80">Icon</th>
                    <th width="100">Thứ tự</th>
                    <th width="100">Sản phẩm</th>
                    <th width="120">Trạng thái</th>
                    <th width="150">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>
                        @if($category->parent_id)
                            <span class="text-muted">└─</span>
                        @endif
                        <strong>{{ $category->name }}</strong>
                        <br><small class="text-muted"><code>{{ $category->slug }}</code></small>
                    </td>
                    <td>
                        @if($category->parent)
                            <a href="{{ route('admin.categories.show', $category->parent) }}">
                                {{ $category->parent->name }}
                            </a>
                        @else
                            <span class="badge bg-primary">Danh mục gốc</span>
                        @endif
                    </td>
                    <td>
                        @if($category->icon)
                            <i class="fas {{ $category->icon }}"></i> {{ $category->icon }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $category->display_order }}</td>
                    <td>
                        <span class="badge bg-info">{{ $category->products()->count() }}</span>
                    </td>
                    <td>
                        @if($category->is_active)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-danger">Tắt</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.categories.show', $category) }}" 
                               class="btn btn-outline-info" title="Xem">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.categories.edit', $category) }}" 
                               class="btn btn-outline-warning" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" 
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
                    <td colspan="8" class="text-center text-muted py-4">
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
                Hiển thị {{ $categories->firstItem() ?? 0 }} - {{ $categories->lastItem() ?? 0 }} 
                / {{ $categories->total() }} kết quả
            </span>
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
