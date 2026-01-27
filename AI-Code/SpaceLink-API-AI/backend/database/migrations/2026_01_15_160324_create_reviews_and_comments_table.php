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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('order_item_id')->unique()->constrained('order_items')->onDelete('cascade')->comment('Chỉ đánh giá khi đã mua');
            $table->unsignedTinyInteger('rating')->comment('1-5 sao');
            $table->text('content')->nullable();
            $table->json('images')->nullable()->comment('Ảnh đánh giá');
            $table->boolean('is_hidden')->default(false)->comment('Ẩn đánh giá');
            $table->text('admin_reply')->nullable()->comment('Phản hồi từ admin');
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();

            $table->index('product_id');
            $table->index('rating');
            $table->index('is_hidden');
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade')->comment('Bình luận cha (reply comment)');
            $table->text('content');
            $table->boolean('is_hidden')->default(false)->comment('Ẩn bình luận');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->timestamps();

            $table->index('product_id');
            $table->index('parent_id');
            $table->index('status');
            $table->index('is_hidden');
        });

        Schema::create('comment_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained('comments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('Người báo cáo');
            $table->string('reason')->comment('Lý do: spam, offensive,...');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'resolved', 'rejected'])->default('pending');
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null')->comment('Admin xử lý');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('comment_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_reports');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('reviews');
    }
};
