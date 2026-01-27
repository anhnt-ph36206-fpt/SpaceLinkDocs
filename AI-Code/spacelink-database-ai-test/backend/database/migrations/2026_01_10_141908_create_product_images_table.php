<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng PRODUCT_IMAGES - Hình ảnh sản phẩm
     * 
     * Quan hệ:
     * - BelongsTo: product (thuộc 1 sản phẩm)
     * 
     * Mỗi sản phẩm có thể có nhiều hình ảnh
     * is_primary = true: ảnh chính hiển thị ở danh sách
     */
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            
            // Xóa product -> xóa luôn tất cả ảnh
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->onDelete('cascade');
            
            $table->string('image_path');                    // Đường dẫn file ảnh
            $table->boolean('is_primary')->default(false);   // Ảnh chính
            $table->integer('display_order')->default(0);    // Thứ tự hiển thị
            
            // Chỉ cần created_at, không cần updated_at
            $table->timestamp('created_at')->useCurrent();
            
            // Index
            $table->index('product_id');
            $table->index('is_primary');
            $table->index('display_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
