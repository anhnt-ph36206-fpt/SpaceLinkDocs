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
        // 1. ROLES
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique()->comment('admin, staff, customer');
            $table->string('display_name', 100);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. PERMISSIONS
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique()->comment('products.view, orders.edit,...');
            $table->string('display_name', 100);
            $table->string('group_name', 50)->comment('products, orders, users,...');
            $table->timestamps();
        });

        // 3. ROLE_PERMISSIONS
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');
            $table->primary(['role_id', 'permission_id']);
        });

        // 4. USERS
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->default(3)->constrained('roles')->onDelete('restrict')->comment('Mặc định: 3-Customer');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('fullname', 150);
            $table->string('phone', 15)->nullable();
            $table->string('avatar')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active')->comment('Trạng thái tài khoản');
            $table->rememberToken();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('role_id');
            $table->index('email');
            $table->index('status');
        });

        // 5. USER_ADDRESSES
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('fullname', 150);
            $table->string('phone', 15);
            $table->string('province', 100);
            $table->string('district', 100);
            $table->string('ward', 100);
            $table->text('address_detail');
            $table->boolean('is_default')->default(false)->comment('Địa chỉ mặc định');
            $table->enum('address_type', ['home', 'office', 'other'])->default('home');
            $table->timestamps();

            $table->index('user_id');
            $table->index('is_default');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
