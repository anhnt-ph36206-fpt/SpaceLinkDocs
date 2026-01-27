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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('discount_type', ['percent', 'fixed'])->comment('Giảm % hoặc cố định');
            $table->decimal('discount_value', 15, 2);
            $table->decimal('max_discount', 15, 2)->nullable()->comment('Giảm tối đa (cho loại %)');
            $table->decimal('min_order_amount', 15, 2)->default(0.00)->comment('Giá trị đơn hàng tối thiểu');
            $table->integer('quantity')->unsigned()->nullable()->comment('Số lượng voucher (NULL = không giới hạn)');
            $table->integer('used_count')->unsigned()->default(0)->comment('Đã sử dụng');
            $table->integer('usage_limit_per_user')->unsigned()->default(1)->comment('Giới hạn/user');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('code');
            $table->index(['start_date', 'end_date']);
            $table->index('is_active');
        });

        Schema::create('cart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('session_id')->nullable()->comment('Cho guest user');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->onDelete('set null');
            $table->integer('quantity')->unsigned()->default(1);
            $table->timestamps();

            $table->unique(['user_id', 'product_id', 'variant_id'], 'unique_cart_item');
            $table->index('session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart');
        Schema::dropIfExists('vouchers');
    }
};
