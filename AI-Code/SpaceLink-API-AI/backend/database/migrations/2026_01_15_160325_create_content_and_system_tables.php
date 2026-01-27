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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('thumbnail')->nullable();
            $table->text('summary')->nullable()->comment('Tóm tắt');
            $table->longText('content');
            $table->integer('view_count')->unsigned()->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
            $table->index('published_at');
            $table->index('is_active');
        });

        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('email');
            $table->string('phone', 15)->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->enum('status', ['unread', 'read', 'replied', 'spam'])->default('unread');
            $table->text('reply_content')->nullable();
            $table->foreignId('replied_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key_name', 100)->unique();
            $table->text('value')->nullable();
            $table->enum('type', ['string', 'number', 'boolean', 'json', 'html'])->default('string');
            $table->string('group_name', 50)->default('general')->comment('general, payment, shipping,...');
            $table->string('description')->nullable();
            $table->boolean('is_public')->default(false)->comment('Hiển thị cho client');
            $table->timestamps();

            $table->index('group_name');
            $table->index('is_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('news');
    }
};
