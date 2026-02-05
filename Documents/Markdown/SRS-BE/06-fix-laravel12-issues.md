# FIX: Laravel 12 Setup Issues
**Date:** 2026-01-29  
**Issues:** CORS config + Missing Models/Migrations

---

## 🚨 VẤN ĐỀ BẠN GẶP

### 1. **KHÔNG CÓ `config/cors.php`**
**Nguyên nhân:** Laravel 12 đã thay đổi cách config CORS!

**Giải pháp:** Config CORS trong `bootstrap/app.php` thay vì file riêng

---

### 2. **Seeder lỗi vì thiếu Models và Migrations**
**Nguyên nhân:** 
- Chưa có Model `Role` và `Permission`
- Chưa có Migration cho bảng `roles` và `permissions`
- Chưa có Migration cho bảng `role_permissions`

**Giải pháp:** Tạo đầy đủ Models + Migrations trước khi chạy Seeder

---

## ✅ GIẢI PHÁP CHI TIẾT

### 🔧 **ISSUE 1: CORS Config trong Laravel 12**

Laravel 12 **KHÔNG CÒN** file `config/cors.php`!

#### Cách cũ (Laravel 10):
```php
// config/cors.php
'allowed_origins' => ['http://localhost:5173'],
```

#### ✅ Cách mới (Laravel 12):
**File: `bootstrap/app.php`**

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ✅ Config CORS ở đây
        $middleware->api(prepend: [
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);
        
        // ✅ Config CORS headers
        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
```

**Hoặc tạo file `config/cors.php` thủ công:**

```bash
# Tạo file mới
touch config/cors.php
```

**File: `config/cors.php`**
```php
<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:5173', // ReactJS Vite
        'http://localhost:3000', // Nếu dùng Create React App
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false, // ✅ false cho API Token
];
```

**Sau đó register trong `bootstrap/app.php`:**
```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->api(prepend: [
        \Illuminate\Http\Middleware\HandleCors::class,
    ]);
})
```

---

### 🔧 **ISSUE 2: Missing Models + Migrations**

#### **BƯỚC 1: Tạo Migrations**

```bash
# 1. Migration cho bảng roles
php artisan make:migration create_roles_table

# 2. Migration cho bảng permissions
php artisan make:migration create_permissions_table

# 3. Migration cho bảng role_permissions (pivot)
php artisan make:migration create_role_permissions_table
```

---

#### **BƯỚC 2: Viết Migrations**

**File: `database/migrations/xxxx_create_roles_table.php`**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique()->comment('admin, staff, customer');
            $table->string('display_name', 100);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
```

---

**File: `database/migrations/xxxx_create_permissions_table.php`**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique()->comment('products.view, orders.edit,...');
            $table->string('display_name', 100);
            $table->string('group_name', 50)->comment('products, orders, users,...');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
```

---

**File: `database/migrations/xxxx_create_role_permissions_table.php`**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->primary(['role_id', 'permission_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
```

---

#### **BƯỚC 3: Tạo Models**

```bash
# 1. Tạo Model Role
php artisan make:model Role

# 2. Tạo Model Permission
php artisan make:model Permission
```

---

**File: `app/Models/Role.php`**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    /**
     * Relationship: Role has many Users
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relationship: Role has many Permissions (Many-to-Many)
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }
}
```

---

**File: `app/Models/Permission.php`**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'group_name',
    ];

    /**
     * Relationship: Permission belongs to many Roles (Many-to-Many)
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }
}
```

---

#### **BƯỚC 4: Update Migration `users` table**

**File: `database/migrations/0001_01_01_000000_create_users_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->default(3)->constrained()->comment('Mặc định: 3-Customer');
            $table->string('fullname', 150);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 15)->nullable();
            $table->string('avatar')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active');
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
```

---

#### **BƯỚC 5: Update User Model**

**File: `app/Models/User.php`**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'role_id',
        'fullname',
        'email',
        'password',
        'phone',
        'avatar',
        'date_of_birth',
        'gender',
        'status',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Relationship: User belongs to Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
```

---

#### **BƯỚC 6: Run Migrations**

```bash
# Drop tất cả bảng và migrate lại
php artisan migrate:fresh

# Hoặc nếu muốn giữ data:
php artisan migrate
```

---

#### **BƯỚC 7: Tạo Seeder**

```bash
php artisan make:seeder RolePermissionSeeder
```

**File: `database/seeders/RolePermissionSeeder.php`**
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'Quản trị viên',
            'description' => 'Có toàn quyền quản lý hệ thống'
        ]);

        $staff = Role::create([
            'name' => 'staff',
            'display_name' => 'Nhân viên',
            'description' => 'Quản lý đơn hàng và sản phẩm'
        ]);

        $customer = Role::create([
            'name' => 'customer',
            'display_name' => 'Khách hàng',
            'description' => 'Người dùng mua hàng'
        ]);

        // Permissions
        $permissions = [
            ['name' => 'dashboard.view', 'display_name' => 'Xem Dashboard', 'group_name' => 'dashboard'],
            ['name' => 'products.view', 'display_name' => 'Xem sản phẩm', 'group_name' => 'products'],
            ['name' => 'products.create', 'display_name' => 'Thêm sản phẩm', 'group_name' => 'products'],
            ['name' => 'products.edit', 'display_name' => 'Sửa sản phẩm', 'group_name' => 'products'],
            ['name' => 'products.delete', 'display_name' => 'Xóa sản phẩm', 'group_name' => 'products'],
            ['name' => 'orders.view', 'display_name' => 'Xem đơn hàng', 'group_name' => 'orders'],
            ['name' => 'orders.edit', 'display_name' => 'Sửa đơn hàng', 'group_name' => 'orders'],
        ];

        foreach ($permissions as $perm) {
            Permission::create($perm);
        }

        // Assign all permissions to admin
        $admin->permissions()->attach(Permission::all());

        // Assign limited permissions to staff
        $staff->permissions()->attach(Permission::whereIn('name', [
            'dashboard.view',
            'products.view',
            'orders.view',
            'orders.edit'
        ])->get());
    }
}
```

---

#### **BƯỚC 8: Register Seeder**

**File: `database/seeders/DatabaseSeeder.php`**
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            // Thêm các seeders khác ở đây
        ]);
    }
}
```

---

#### **BƯỚC 9: Run Seeder**

```bash
# Chạy tất cả seeders
php artisan db:seed

# Hoặc chạy seeder cụ thể
php artisan db:seed --class=RolePermissionSeeder

# Hoặc migrate fresh + seed
php artisan migrate:fresh --seed
```

---

## 📋 CHECKLIST - THỨ TỰ THỰC HIỆN

### ✅ **Thứ tự đúng:**

1. **Tạo Migrations** (create tables)
   ```bash
   php artisan make:migration create_roles_table
   php artisan make:migration create_permissions_table
   php artisan make:migration create_role_permissions_table
   ```

2. **Viết code Migrations** (define schema)
   - `roles` table
   - `permissions` table
   - `role_permissions` table
   - Update `users` table (thêm `role_id`)

3. **Run Migrations** (create tables in DB)
   ```bash
   php artisan migrate:fresh
   ```

4. **Tạo Models** (để Seeder sử dụng)
   ```bash
   php artisan make:model Role
   php artisan make:model Permission
   ```

5. **Viết code Models** (define relationships)
   - `Role.php`
   - `Permission.php`
   - Update `User.php`

6. **Tạo Seeder**
   ```bash
   php artisan make:seeder RolePermissionSeeder
   ```

7. **Viết code Seeder** (insert data)

8. **Run Seeder**
   ```bash
   php artisan db:seed
   ```

---

## ❓ TRẢ LỜI CÂU HỎI CỦA BẠN

### **Câu 1: "Chỉ cần Seeder thôi hay cần cả Migration và Factory?"**

**Trả lời:**

#### ✅ **CẦN CẢ 3 THỨ (theo thứ tự):**

1. **Migration** (Bắt buộc)
   - Tạo bảng trong database
   - Define schema (columns, types, indexes, foreign keys)
   - **PHẢI CÓ TRƯỚC** khi chạy Seeder

2. **Model** (Bắt buộc)
   - Để Seeder có thể sử dụng Eloquent ORM
   - `Role::create()`, `Permission::create()`
   - **PHẢI CÓ TRƯỚC** khi chạy Seeder

3. **Seeder** (Bắt buộc)
   - Insert data mẫu vào database
   - Chạy SAU khi đã có Migration + Model

4. **Factory** (Không bắt buộc)
   - Dùng để generate fake data (testing)
   - Không cần cho data thật (roles, permissions)
   - Chỉ cần cho data mẫu (users, products)

---

### **Câu 2: "Phải Ctrl + Space để import Class?"**

**Trả lời:**

#### ✅ **ĐÚNG! Phải import class:**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;        // ✅ PHẢI IMPORT
use App\Models\Permission;  // ✅ PHẢI IMPORT

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Giờ mới dùng được Role::create()
        $admin = Role::create([...]);
    }
}
```

**Nếu không import:**
```php
// ❌ LỖI: Class "Role" not found
$admin = Role::create([...]);
```

**Cách import:**
- **VS Code:** Ctrl + Space → Chọn class → Enter
- **PhpStorm:** Alt + Enter → Import class
- **Hoặc viết tay:** `use App\Models\Role;`

---

## 🎯 TỔNG KẾT

### ✅ **Để chạy Seeder thành công, BẠN CẦN:**

1. ✅ **Migration** - Tạo bảng trong DB
2. ✅ **Model** - Để Seeder sử dụng Eloquent
3. ✅ **Import class** - `use App\Models\Role;`
4. ✅ **Run migration** - `php artisan migrate`
5. ✅ **Run seeder** - `php artisan db:seed`

### ❌ **KHÔNG CẦN:**

- ❌ Factory (trừ khi muốn fake data)
- ❌ `config/cors.php` trong Laravel 12 (dùng `bootstrap/app.php`)

---

## 🚀 QUICK FIX - CHẠY NGAY

```bash
# 1. Tạo migrations
php artisan make:migration create_roles_table
php artisan make:migration create_permissions_table
php artisan make:migration create_role_permissions_table

# 2. Copy code migrations từ file này vào các file migration

# 3. Tạo models
php artisan make:model Role
php artisan make:model Permission

# 4. Copy code models từ file này vào các file model

# 5. Run migrations
php artisan migrate:fresh

# 6. Tạo seeder
php artisan make:seeder RolePermissionSeeder

# 7. Copy code seeder từ file này

# 8. Run seeder
php artisan db:seed --class=RolePermissionSeeder

# 9. Check database
# Vào HeidiSQL/phpMyAdmin xem bảng roles, permissions đã có data chưa
```

---

**Created:** 2026-01-29  
**Status:** ✅ Ready to Fix
