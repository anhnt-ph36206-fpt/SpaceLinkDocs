<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku', 100)->unique()->nullable()->comment('Mã sản phẩm');
            $table->text('description')->nullable()->comment('Mô tả ngắn');
            $table->longText('content')->nullable()->comment('Mô tả chi tiết');
            $table->decimal('price', 15, 2)->comment('Giá gốc');
            $table->decimal('sale_price', 15, 2)->nullable()->comment('Giá khuyến mãi');
            $table->integer('quantity')->unsigned()->default(0)->comment('Tổng tồn kho');
            $table->integer('sold_count')->unsigned()->default(0)->comment('Đã bán');
            $table->integer('view_count')->unsigned()->default(0)->comment('Lượt xem');
            $table->boolean('is_featured')->default(false)->comment('Sản phẩm nổi bật');
            $table->boolean('is_active')->default(true);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('category_id');
            $table->index('brand_id');
            $table->index('price');
            $table->index('sold_count');
            $table->index('view_count');
            $table->index('is_featured');
            $table->index('is_active');
            
            // Note: Laravel migration for fulltext index
            $table->fullText(['name', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
