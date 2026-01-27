<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng BRANDS - Thương hiệu sản phẩm
     * 
     * Quan hệ:
     * - HasMany: products (1 brand có nhiều sản phẩm)
     */
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();                                    // BIGINT UNSIGNED AUTO_INCREMENT
            $table->string('name');                          // Tên thương hiệu (Apple, Samsung)
            $table->string('slug')->unique();                // URL-friendly (apple, samsung)
            $table->string('logo')->nullable();              // Đường dẫn file logo
            $table->text('description')->nullable();         // Mô tả thương hiệu
            $table->boolean('is_active')->default(true);     // Trạng thái hiển thị
            $table->integer('display_order')->default(0);    // Thứ tự sắp xếp
            $table->timestamps();                            // created_at, updated_at
            
            // Indexes để tối ưu query
            $table->index('slug');
            $table->index('is_active');
            $table->index('display_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
