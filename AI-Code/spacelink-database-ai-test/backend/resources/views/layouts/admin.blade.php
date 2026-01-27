<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - SpaceLink</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 250px;
        }
        
        body {
            background-color: #f4f6f9;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #1e3a5f 0%, #0d1b2a 100%);
            padding-top: 20px;
            z-index: 1000;
        }
        
        .sidebar .brand {
            color: #fff;
            font-size: 1.5rem;
            font-weight: bold;
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.1);
        }
        
        .sidebar .nav-link i {
            width: 20px;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: 100vh;
        }
        
        .card {
            border: none;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            border-radius: 10px;
        }
        
        .card-header {
            background: #fff;
            border-bottom: 1px solid #eee;
            font-weight: 600;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #1e3a5f 0%, #0d1b2a 100%);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #2a4a6f 0%, #1d2b3a 100%);
        }
        
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        
        .badge-active {
            background-color: #28a745;
        }
        
        .badge-inactive {
            background-color: #dc3545;
        }
        
        .stat-card {
            padding: 20px;
            border-radius: 10px;
            color: #fff;
        }
        
        .stat-card.brands { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-card.categories { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stat-card.products { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .stat-card.featured { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        
        .stat-card h3 {
            font-size: 2rem;
            font-weight: bold;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <i class="fas fa-rocket"></i> SpaceLink
        </div>
        
        <nav class="nav flex-column mt-3">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
               href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            
            <a class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}" 
               href="{{ route('admin.brands.index') }}">
                <i class="fas fa-copyright"></i> Thương hiệu
            </a>
            
            <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" 
               href="{{ route('admin.categories.index') }}">
                <i class="fas fa-th-large"></i> Danh mục
            </a>
            
            <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" 
               href="{{ route('admin.products.index') }}">
                <i class="fas fa-box"></i> Sản phẩm
            </a>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @yield('content')
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
