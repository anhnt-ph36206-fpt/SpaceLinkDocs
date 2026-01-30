<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
      /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            // $table->foreignId('category_id')->constrained();
            
            $table->string('name', 200);
            $table->string('slug', 200)->unique();
            // $table->string('sku', 50)->unique();
            $table->string('sku')->default('');        // default chuỗi rỗng
            $table->decimal('price', 15, 2);
            $table->decimal('sale_price', 15, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->string('thumbnail')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive', 'out_of_stock'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    // public function up(): void
    // {
    //     Schema::create('products', function (Blueprint $table) {
    //     $table->id();
    //     // Bỏ hẳn 2 dòng foreign key và cột liên quan nếu chưa cần
    //     // $table->foreignId('brand_id')->constrained()->onDelete('cascade');
    //     // $table->foreignId('category_id')->constrained()->onDelete('cascade');

    //     $table->string('name', 200);
    //     $table->string('slug', 200)->unique();
    //     $table->string('sku', 50)->unique();
    //     $table->decimal('price', 15, 2);
    //     $table->decimal('sale_price', 15, 2)->nullable();
    //     $table->integer('stock_quantity')->default(0);
    //     $table->string('thumbnail')->nullable();
    //     $table->text('description')->nullable();
    //     $table->enum('status', ['active', 'inactive', 'out_of_stock'])->default('active');

    //     $table->timestamps();
    //     $table->softDeletes();
    // });
    // }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
