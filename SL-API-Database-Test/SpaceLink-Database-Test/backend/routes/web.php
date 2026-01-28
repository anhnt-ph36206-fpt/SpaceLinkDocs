<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes - SpaceLink Admin
|--------------------------------------------------------------------------
*/

// Trang chủ
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// ===========================
// ADMIN ROUTES
// ===========================
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', function () {
        $stats = [
            'brands' => \App\Models\Brand::count(),
            'categories' => \App\Models\Category::count(),
            'products' => \App\Models\Product::count(),
            'featured_products' => \App\Models\Product::featured()->count(),
        ];
        return view('admin.dashboard', compact('stats'));
    })->name('dashboard');

    // Brands CRUD
    Route::resource('brands', BrandController::class);

    // Categories CRUD
    Route::resource('categories', CategoryController::class);
    Route::get('categories/{category}/children', [CategoryController::class, 'getChildren'])
        ->name('categories.children');

    // Products CRUD
    Route::resource('products', ProductController::class);
    Route::delete('products/images/{image}', [ProductController::class, 'deleteImage'])
        ->name('products.images.delete');
    Route::patch('products/images/{image}/primary', [ProductController::class, 'setPrimaryImage'])
        ->name('products.images.primary');
});
