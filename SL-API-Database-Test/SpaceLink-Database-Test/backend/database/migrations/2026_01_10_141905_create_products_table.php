<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng PRODUCTS - Sản phẩm chính
     * 
     * Quan hệ:
     * - BelongsTo: category (thuộc 1 danh mục)
     * - BelongsTo: brand (thuộc 1 thương hiệu)
     * - HasMany: images (có nhiều hình ảnh)
     * - HasMany: variants (có nhiều biến thể - Phase 2)
     * - HasMany: reviews (có nhiều đánh giá - Phase 6)
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            // Không cho xóa category nếu còn sản phẩm
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->onDelete('restrict');
            
            // Xóa brand thì sản phẩm vẫn còn (brand_id = null)
            $table->foreignId('brand_id')
                  ->nullable()
                  ->constrained('brands')
                  ->onDelete('set null');
            
            // === THÔNG TIN CƠ BẢN ===
            $table->string('name');                                 // Tên sản phẩm
            $table->string('slug')->unique();                       // URL slug
            $table->string('sku', 100)->unique()->nullable();       // Mã sản phẩm (SKU)
            $table->text('description')->nullable();                // Mô tả ngắn
            $table->longText('content')->nullable();                // Nội dung chi tiết (HTML)
            
            // === GIÁ ===
            $table->decimal('price', 15, 2);                        // Giá gốc
            $table->decimal('sale_price', 15, 2)->nullable();       // Giá khuyến mãi
            
            // === SỐ LƯỢNG & THỐNG KÊ ===
            $table->unsignedInteger('quantity')->default(0);        // Tồn kho
            $table->unsignedInteger('sold_count')->default(0);      // Số lượng đã bán
            $table->unsignedInteger('view_count')->default(0);      // Lượt xem
            
            // === TRẠNG THÁI ===
            $table->boolean('is_featured')->default(false);         // Sản phẩm nổi bật
            $table->boolean('is_active')->default(true);            // Hiển thị hay ẩn
            
            // === SEO ===
            $table->string('meta_title')->nullable();               // Tiêu đề SEO
            $table->text('meta_description')->nullable();           // Mô tả SEO
            
            $table->timestamps();
            $table->softDeletes();                                  // Xóa mềm
            
            // === INDEXES ===
            $table->index('category_id');
            $table->index('brand_id');
            $table->index('price');
            $table->index('sale_price');
            $table->index('sold_count');
            $table->index('view_count');
            $table->index('is_featured');
            $table->index('is_active');
            $table->index('created_at');
            $table->index('deleted_at');
            
            // Full-text search cho tên và mô tả
            $table->fullText(['name', 'description']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
