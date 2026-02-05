@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tachometer-alt me-2"></i> Dashboard</h2>
    <span class="text-muted">{{ now()->format('d/m/Y H:i') }}</span>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card brands">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1 opacity-75">Thương hiệu</h6>
                    <h3 class="mb-0">{{ $stats['brands'] }}</h3>
                </div>
                <i class="fas fa-copyright fa-2x opacity-50"></i>
            </div>
            <a href="{{ route('admin.brands.index') }}" class="text-white text-decoration-none small">
                Xem chi tiết <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card categories">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1 opacity-75">Danh mục</h6>
                    <h3 class="mb-0">{{ $stats['categories'] }}</h3>
                </div>
                <i class="fas fa-th-large fa-2x opacity-50"></i>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="text-white text-decoration-none small">
                Xem chi tiết <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card products">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1 opacity-75">Sản phẩm</h6>
                    <h3 class="mb-0">{{ $stats['products'] }}</h3>
                </div>
                <i class="fas fa-box fa-2x opacity-50"></i>
            </div>
            <a href="{{ route('admin.products.index') }}" class="text-white text-decoration-none small">
                Xem chi tiết <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card featured">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1 opacity-75">SP Nổi bật</h6>
                    <h3 class="mb-0">{{ $stats['featured_products'] }}</h3>
                </div>
                <i class="fas fa-star fa-2x opacity-50"></i>
            </div>
            <a href="{{ route('admin.products.index', ['status' => 'featured']) }}" class="text-white text-decoration-none small">
                Xem chi tiết <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-bolt me-2"></i> Thao tác nhanh
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <a href="{{ route('admin.brands.create') }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-plus me-2"></i> Thêm thương hiệu
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-success w-100 mb-2">
                    <i class="fas fa-plus me-2"></i> Thêm danh mục
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.products.create') }}" class="btn btn-outline-info w-100 mb-2">
                    <i class="fas fa-plus me-2"></i> Thêm sản phẩm
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
