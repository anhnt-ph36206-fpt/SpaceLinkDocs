<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng CATEGORIES - Danh mục sản phẩm (đa cấp)
     * 
     * Quan hệ:
     * - BelongsTo: parent (danh mục cha)
     * - HasMany: children (danh mục con)
     * - HasMany: products (sản phẩm thuộc danh mục)
     * 
     * Ví dụ cấu trúc:
     * - Điện thoại (parent_id = null)
     *   - iPhone (parent_id = 1)
     *     - iPhone 16 Series (parent_id = 5)
     *   - Samsung (parent_id = 1)
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            
            // Self-referencing: danh mục cha
            // Khi xóa parent -> children.parent_id = NULL
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('categories')
                  ->onDelete('set null');
            
            $table->string('name');                          // Tên danh mục
            $table->string('slug')->unique();                // URL slug
            $table->string('image')->nullable();             // Hình ảnh đại diện
            $table->string('icon', 100)->nullable();         // CSS icon class (fa-mobile)
            $table->text('description')->nullable();         // Mô tả
            $table->integer('display_order')->default(0);    // Thứ tự hiển thị
            $table->boolean('is_active')->default(true);     // Trạng thái
            $table->timestamps();
            $table->softDeletes();                           // Xóa mềm: deleted_at
            
            // Indexes
            $table->index('parent_id');
            $table->index('slug');
            $table->index('is_active');
            $table->index('display_order');
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
