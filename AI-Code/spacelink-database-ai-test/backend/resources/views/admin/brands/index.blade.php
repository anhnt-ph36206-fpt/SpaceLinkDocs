@extends('layouts.admin')

@section('title', 'Quản lý Thương hiệu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-copyright me-2"></i> Thương hiệu</h2>
    <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Thêm mới
    </a>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" 
                       placeholder="Tìm kiếm..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Trạng thái --</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="fas fa-search"></i> Lọc
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary w-100">
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
                    <th width="80">Logo</th>
                    <th>Tên</th>
                    <th>Slug</th>
                    <th width="100">Thứ tự</th>
                    <th width="120">Trạng thái</th>
                    <th width="150">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                <tr>
                    <td>{{ $brand->id }}</td>
                    <td>
                        @if($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}" 
                                 alt="{{ $brand->name }}" width="40" height="40" 
                                 style="object-fit: contain; border-radius: 5px;">
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $brand->name }}</strong>
                        @if($brand->description)
                            <br><small class="text-muted">{{ Str::limit($brand->description, 50) }}</small>
                        @endif
                    </td>
                    <td><code>{{ $brand->slug }}</code></td>
                    <td>{{ $brand->display_order }}</td>
                    <td>
                        @if($brand->is_active)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-danger">Tắt</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.brands.show', $brand) }}" 
                               class="btn btn-outline-info" title="Xem">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.brands.edit', $brand) }}" 
                               class="btn btn-outline-warning" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.brands.destroy', $brand) }}" 
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
                    <td colspan="7" class="text-center text-muted py-4">
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
                Hiển thị {{ $brands->firstItem() ?? 0 }} - {{ $brands->lastItem() ?? 0 }} 
                / {{ $brands->total() }} kết quả
            </span>
            {{ $brands->links() }}
        </div>
    </div>
</div>
@endsection
