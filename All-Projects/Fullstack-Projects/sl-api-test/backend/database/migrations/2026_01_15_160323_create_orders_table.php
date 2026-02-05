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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('order_code', 50)->unique()->comment('Mã đơn hàng');
            
            // Thông tin giao hàng
            $table->string('shipping_name', 150);
            $table->string('shipping_phone', 15);
            $table->string('shipping_email')->nullable();
            $table->string('shipping_province', 100);
            $table->string('shipping_district', 100);
            $table->string('shipping_ward', 100);
            $table->text('shipping_address');
            
            // Thông tin tiền
            $table->decimal('subtotal', 15, 2)->comment('Tổng tiền hàng');
            $table->decimal('discount_amount', 15, 2)->default(0.00)->comment('Tiền giảm giá');
            $table->decimal('shipping_fee', 15, 2)->default(0.00)->comment('Phí vận chuyển');
            $table->decimal('total_amount', 15, 2)->comment('Tổng thanh toán');
            
            // Trạng thái
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipping', 'delivered', 'completed', 'cancelled', 'returned'])
                ->default('pending')->comment('Trạng thái đơn hàng');
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded', 'partial_refund'])
                ->default('unpaid')->comment('Trạng thái thanh toán');
            $table->enum('payment_method', ['cod', 'vnpay', 'momo', 'bank_transfer']);
            
            // Voucher
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->onDelete('set null');
            $table->string('voucher_code', 50)->nullable();
            $table->decimal('voucher_discount', 15, 2)->default(0.00);
            
            // Ghi chú
            $table->text('note')->nullable()->comment('Ghi chú của khách');
            $table->text('admin_note')->nullable()->comment('Ghi chú của admin');
            
            // Hủy đơn
            $table->text('cancelled_reason')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('cancelled_at')->nullable();
            
            // Timestamps
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
            $table->index('payment_status');
            $table->index('created_at');
            $table->index('order_code');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->onDelete('set null');
            
            // Sao lưu thông tin sản phẩm
            $table->string('product_name');
            $table->string('product_image')->nullable();
            $table->string('product_sku', 100)->nullable();
            $table->json('variant_info')->nullable()->comment('Thông tin biến thể: {color: "Đen", ram: "8GB"}');
            
            $table->decimal('price', 15, 2)->comment('Giá tại thời điểm mua');
            $table->integer('quantity')->unsigned();
            $table->decimal('total', 15, 2)->comment('Thành tiền');
            
            $table->boolean('is_reviewed')->default(false)->comment('Đã đánh giá chưa');
            $table->timestamp('created_at')->useCurrent();

            $table->index('order_id');
            $table->index('is_reviewed');
        });

        Schema::create('order_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('from_status', 50)->nullable();
            $table->string('to_status', 50);
            $table->text('note')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users')->onDelete('set null')->comment('User thay đổi');
            $table->timestamp('created_at')->useCurrent();

            $table->index('order_id');
        });

        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('transaction_id')->unique()->nullable()->comment('Mã giao dịch từ cổng thanh toán');
            $table->string('payment_method', 50);
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'processing', 'success', 'failed', 'refunded', 'cancelled'])->default('pending');
            $table->string('bank_code', 50)->nullable();
            $table->string('response_code', 50)->nullable();
            $table->text('response_message')->nullable();
            $table->json('response_data')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index('transaction_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
        Schema::dropIfExists('order_status_history');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
