# HƯỚNG DẪN SETUP BACKEND - TỪNG BƯỚC CHI TIẾT
**Laravel 12 - Step by Step - Không bỏ sót gì!**  
**Date:** 2026-01-29

---

## 🎯 MỤC TIÊU

Sau khi hoàn thành file này, bạn sẽ có:
- ✅ Laravel 12 project đã setup đầy đủ
- ✅ Database với bảng roles, permissions, users
- ✅ Auth APIs (Register, Login, Logout, Profile)
- ✅ Test thành công bằng Postman

---

## 📋 CHUẨN BỊ

### Yêu cầu:
- ✅ PHP 8.2+
- ✅ Composer
- ✅ MySQL
- ✅ Laragon/XAMPP đã chạy

### Kiểm tra:
```bash
php -v        # Phải >= 8.2
composer -V   # Phải có
mysql --version  # Phải có
```

---

## 🚀 BƯỚC 1: TẠO PROJECT LARAVEL 12

### 1.1. Tạo project mới

```bash
# Navigate đến thư mục
cd D:\WebServers\laragon6\www\SpaceLink-Projects\SL-SRS\SRS-BE

# Tạo project Laravel 12
composer create-project laravel/laravel spacelink-backend --prefer-dist

# Hoặc nếu đã có project:
cd spacelink-backend-test
```

### 1.2. Config Database

**File: `.env`**
```env
APP_NAME=SpaceLink
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=spacelink_db
DB_USERNAME=root
DB_PASSWORD=
```

### 1.3. Tạo Database

```bash
# Mở HeidiSQL hoặc phpMyAdmin
# Tạo database mới: spacelink_db
# Hoặc dùng command:
mysql -u root -e "CREATE DATABASE spacelink_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 1.4. Test kết nối

```bash
php artisan migrate
```

**Kết quả:** Phải thấy migrations chạy thành công (users, cache, jobs, sessions)

---

## 🚀 BƯỚC 2: SETUP CORS (Laravel 12)

### 2.1. Tạo file `config/cors.php`

**Laravel 12 KHÔNG CÓ file này mặc định, phải tạo thủ công!**

```bash
# Tạo file
touch config/cors.php

# Hoặc tạo bằng VS Code:
# Right click folder config → New File → cors.php
```

### 2.2. Viết code `config/cors.php`

**File: `config/cors.php`**
```php
<?php

return [
    /*
     * Paths được phép CORS
     */
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    /*
     * HTTP methods được phép
     */
    'allowed_methods' => ['*'],

    /*
     * Origins được phép (ReactJS Vite)
     */
    'allowed_origins' => [
        'http://localhost:5173',  // ReactJS Vite
        'http://localhost:3000',  // Create React App (nếu dùng)
    ],

    'allowed_origins_patterns' => [],

    /*
     * Headers được phép
     */
    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    /*
     * Credentials (false cho API Token)
     */
    'supports_credentials' => false,
];
```

### 2.3. Register CORS Middleware

**File: `bootstrap/app.php`**
```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ✅ THÊM DÒNG NÀY
        $middleware->api(prepend: [
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
```

---

## 🚀 BƯỚC 3: SETUP SANCTUM

### 3.1. Check Sanctum đã cài chưa

```bash
composer show laravel/sanctum
```

**Kết quả:** Phải thấy `laravel/sanctum v4.x.x`

**Nếu chưa có:**
```bash
composer require laravel/sanctum
```

### 3.2. Publish config (Optional)

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### 3.3. Run migration Sanctum

```bash
php artisan migrate
```

**Kết quả:** Phải thấy bảng `personal_access_tokens` được tạo

---

## 🚀 BƯỚC 4: TẠO MIGRATIONS CHO ROLES & PERMISSIONS

### 4.1. Tạo migration files

```bash
# 1. Migration cho bảng roles
php artisan make:migration create_roles_table

# 2. Migration cho bảng permissions
php artisan make:migration create_permissions_table

# 3. Migration cho bảng role_permissions (pivot)
php artisan make:migration create_role_permissions_table
```

**Kết quả:** 3 files mới trong `database/migrations/`

---

### 4.2. Viết code Migration: `roles`

**File: `database/migrations/xxxx_xx_xx_xxxxxx_create_roles_table.php`**

```php
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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique()->comment('admin, staff, customer');
            $table->string('display_name', 100);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
```

---

### 4.3. Viết code Migration: `permissions`

**File: `database/migrations/xxxx_xx_xx_xxxxxx_create_permissions_table.php`**

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

### 4.4. Viết code Migration: `role_permissions`

**File: `database/migrations/xxxx_xx_xx_xxxxxx_create_role_permissions_table.php`**

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

### 4.5. Update Migration: `users` table

**File: `database/migrations/0001_01_01_000000_create_users_table.php`**

**Tìm dòng:**
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');  // ← XÓA DÒNG NÀY
    $table->string('email')->unique();
    // ...
```

**Thay bằng:**
```php
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
```

---

### 4.6. Run Migrations

```bash
# Drop tất cả bảng và migrate lại
php artisan migrate:fresh
```

**Kết quả:** Phải thấy:
- ✅ `roles` table created
- ✅ `permissions` table created
- ✅ `role_permissions` table created
- ✅ `users` table created (với cột `role_id`)

**Check trong HeidiSQL/phpMyAdmin:**
- Vào database `spacelink_db`
- Phải thấy 4 bảng: `roles`, `permissions`, `role_permissions`, `users`

---

## 🚀 BƯỚC 5: TẠO MODELS

### 5.1. Tạo Model files

```bash
# 1. Tạo Model Role
php artisan make:model Role

# 2. Tạo Model Permission
php artisan make:model Permission
```

**Kết quả:** 2 files mới trong `app/Models/`

---

### 5.2. Viết code Model: `Role`

**File: `app/Models/Role.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * Fillable fields
     */
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

### 5.3. Viết code Model: `Permission`

**File: `app/Models/Permission.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    /**
     * Fillable fields
     */
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

### 5.4. Update Model: `User`

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

    /**
     * Fillable fields
     */
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

    /**
     * Hidden fields
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     */
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

## 🚀 BƯỚC 6: TẠO SEEDERS

### 6.1. Tạo Seeder file

```bash
php artisan make:seeder RolePermissionSeeder
```

**Kết quả:** File mới `database/seeders/RolePermissionSeeder.php`

---

### 6.2. Viết code Seeder

**File: `database/seeders/RolePermissionSeeder.php`**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ============================================
        // 1. TẠO ROLES
        // ============================================
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

        // ============================================
        // 2. TẠO PERMISSIONS
        // ============================================
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

        // ============================================
        // 3. ASSIGN PERMISSIONS TO ROLES
        // ============================================
        
        // Admin có tất cả quyền
        $admin->permissions()->attach(Permission::all());

        // Staff có quyền hạn chế
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

### 6.3. Register Seeder

**File: `database/seeders/DatabaseSeeder.php`**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
        ]);
    }
}
```

---

### 6.4. Run Seeder

```bash
php artisan db:seed
```

**Kết quả:** Phải thấy:
```
Seeding: Database\Seeders\RolePermissionSeeder
Seeded:  Database\Seeders\RolePermissionSeeder (xx.xx ms)
```

**Check trong HeidiSQL/phpMyAdmin:**
- Bảng `roles`: Phải có 3 rows (admin, staff, customer)
- Bảng `permissions`: Phải có 7 rows
- Bảng `role_permissions`: Phải có nhiều rows (admin có 7, staff có 4)

---

## 🚀 BƯỚC 7: TẠO AUTH CONTROLLER

### 7.1. Tạo Controller

```bash
php artisan make:controller Api/AuthController
```

**Kết quả:** File mới `app/Http/Controllers/Api/AuthController.php`

---

### 7.2. Viết code Controller

**File: `app/Http/Controllers/Api/AuthController.php`**

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register new user
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|regex:/^0[0-9]{9}$/',
        ]);

        $user = User::create([
            'role_id' => 3, // customer
            'fullname' => $validated['fullname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'status' => 'active',
        ]);

        // Tạo token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký thành công',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'fullname' => $user->fullname,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role->name,
                ],
                'token' => $token,
            ]
        ], 201);
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email hoặc mật khẩu không đúng'],
            ]);
        }

        if ($user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản của bạn đã bị khóa',
            ], 403);
        }

        // Update last login
        $user->update(['last_login_at' => now()]);

        // Tạo token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'fullname' => $user->fullname,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'avatar' => $user->avatar,
                    'role' => $user->role->name,
                ],
                'token' => $token,
            ]
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        // Xóa token hiện tại
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công',
        ]);
    }

    /**
     * Get user profile
     */
    public function profile(Request $request)
    {
        $user = $request->user()->load('role');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'fullname' => $user->fullname,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => $user->avatar,
                'date_of_birth' => $user->date_of_birth,
                'gender' => $user->gender,
                'role' => $user->role->name,
                'created_at' => $user->created_at,
            ]
        ]);
    }
}
```

---

## 🚀 BƯỚC 8: SETUP ROUTES

### 8.1. Viết code Routes

**File: `routes/api.php`**

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// ============================================
// PUBLIC ROUTES (Không cần token)
// ============================================
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// ============================================
// PROTECTED ROUTES (Cần token)
// ============================================
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
    });
});
```

---

## 🚀 BƯỚC 8.5: SETUP ROLE MIDDLEWARE (Phân quyền)

Mặc định `auth:sanctum` chỉ kiểm tra user có đăng nhập hay chưa. Để phân quyền (ví dụ: chỉ Admin mới được xóa sản phẩm), chúng ta cần tạo Middleware riêng.

### 8.5.1. Thêm helper vào Model User

**File: `app/Models/User.php`**

```php
    /**
     * Check if user has a specific role
     */
    public function hasRole(string|array $roles): bool
    {
        if (!$this->role) return false;
        
        if (is_array($roles)) {
            return in_array($this->role->name, $roles);
        }
        return $this->role->name === $roles;
    }
```

### 8.5.2. Tạo CheckRole Middleware

```bash
php artisan make:middleware CheckRole
```

**File: `app/Http/Middleware/CheckRole.php`**

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user() || !$request->user()->hasRole($roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền thực hiện hành động này.'
            ], 403);
        }

        return $next($request);
    }
}
```

### 8.5.3. Register Middleware Alias

**File: `bootstrap/app.php`**

```php
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
```

### 8.5.4. Sử dụng trong Routes

**File: `routes/api.php`**

```php
// Chỉ Admin và Staff mới được quản lý category
Route::middleware(['auth:sanctum', 'role:admin,staff'])->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
});
```

```

---

## 🚀 BƯỚC 9: TEST BẰNG POSTMAN

### 9.1. Start Laravel server

```bash
php artisan serve
```

**Kết quả:** Server chạy tại `http://localhost:8000`

---

### 9.2. Test Register

**Request:**
```
POST http://localhost:8000/api/auth/register
Content-Type: application/json

{
  "fullname": "Test User",
  "email": "test@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "0123456789"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Đăng ký thành công",
  "data": {
    "user": {
      "id": 1,
      "fullname": "Test User",
      "email": "test@example.com",
      "phone": "0123456789",
      "role": "customer"
    },
    "token": "1|abc123xyz..."
  }
}
```

**✅ COPY TOKEN này để test các API khác!**

---

### 9.3. Test Login

**Request:**
```
POST http://localhost:8000/api/auth/login
Content-Type: application/json

{
  "email": "test@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Đăng nhập thành công",
  "data": {
    "user": { ... },
    "token": "2|def456uvw..."
  }
}
```

---

### 9.4. Test Profile (Protected)

**Request:**
```
GET http://localhost:8000/api/auth/profile
Authorization: Bearer 2|def456uvw...
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "fullname": "Test User",
    "email": "test@example.com",
    ...
  }
}
```

---

### 9.5. Test Logout

**Request:**
```
POST http://localhost:8000/api/auth/logout
Authorization: Bearer 2|def456uvw...
```

**Response (200):**
```json
{
  "success": true,
  "message": "Đăng xuất thành công"
}
```

---

## ✅ CHECKLIST - ĐẢM BẢO HOÀN THÀNH

### Bước 1: Project Setup
- [ ] Tạo Laravel 12 project
- [ ] Config `.env` (database)
- [ ] Tạo database `spacelink_db`
- [ ] Test `php artisan migrate`

### Bước 2: CORS
- [ ] Tạo file `config/cors.php`
- [ ] Viết code CORS config
- [ ] Update `bootstrap/app.php`

### Bước 3: Sanctum
- [ ] Check Sanctum đã cài
- [ ] Run migration Sanctum
- [ ] Thấy bảng `personal_access_tokens`

### Bước 4: Migrations
- [ ] Tạo 3 migration files (roles, permissions, role_permissions)
- [ ] Viết code 3 migrations
- [ ] Update migration `users` table
- [ ] Run `php artisan migrate:fresh`
- [ ] Check database có 4 bảng

### Bước 5: Models
- [ ] Tạo 2 model files (Role, Permission)
- [ ] Viết code 2 models
- [ ] Update model `User`
- [ ] Check `use HasApiTokens` trong User

### Bước 6: Seeders
- [ ] Tạo seeder file
- [ ] Viết code seeder (nhớ import Models!)
- [ ] Register trong DatabaseSeeder
- [ ] Run `php artisan db:seed`
- [ ] Check database có data (3 roles, 7 permissions)

### Bước 7: Controller
- [ ] Tạo AuthController
- [ ] Viết code 4 methods (register, login, logout, profile)

### Bước 8: Routes
- [ ] Viết code routes (public + protected)

### Bước 9: Test
- [ ] Start server `php artisan serve`
- [ ] Test Register → Success + Token
- [ ] Test Login → Success + Token
- [ ] Test Profile → Success
- [ ] Test Logout → Success

---

## 🎯 TỔNG KẾT

**Bạn đã hoàn thành:**
- ✅ Setup Laravel 12 project
- ✅ Config CORS đúng
- ✅ Setup Sanctum
- ✅ Tạo database với roles, permissions, users
- ✅ Tạo Auth APIs
- ✅ Test thành công bằng Postman

**Next steps:**
- ✅ Tạo Brands & Categories APIs (Bước 10-11)
- ✅ Tạo Products APIs
- ✅ Tạo Cart APIs
- ✅ Tạo Orders APIs

---

# PHẦN 2: BRANDS & CATEGORIES APIs

## 🚀 BƯỚC 10: TẠO BRANDS APIs

### 10.1. Tạo Migration cho Brands

```bash
php artisan make:migration create_brands_table
```

**Kết quả:** File mới `database/migrations/xxxx_xx_xx_xxxxxx_create_brands_table.php`

---

### 10.2. Viết code Migration: `brands`

**File: `database/migrations/xxxx_xx_xx_xxxxxx_create_brands_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('slug', 100)->unique();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
```

---

### 10.3. Tạo Model Brand

```bash
php artisan make:model Brand
```

**File: `app/Models/Brand.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'status',
    ];

    /**
     * Auto-generate slug from name
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brand) {
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
        });

        static::updating(function ($brand) {
            if ($brand->isDirty('name') && empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
        });
    }

    /**
     * Relationship: Brand has many Products
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
```

---

### 10.4. Tạo Seeder cho Brands

```bash
php artisan make:seeder BrandSeeder
```

**File: `database/seeders/BrandSeeder.php`**

```php
<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Apple',
                'slug' => 'apple',
                'description' => 'Thương hiệu công nghệ hàng đầu thế giới',
                'status' => 'active',
            ],
            [
                'name' => 'Samsung',
                'slug' => 'samsung',
                'description' => 'Tập đoàn điện tử đa quốc gia Hàn Quốc',
                'status' => 'active',
            ],
            [
                'name' => 'Xiaomi',
                'slug' => 'xiaomi',
                'description' => 'Thương hiệu công nghệ Trung Quốc',
                'status' => 'active',
            ],
            [
                'name' => 'OPPO',
                'slug' => 'oppo',
                'description' => 'Thương hiệu smartphone phổ biến',
                'status' => 'active',
            ],
            [
                'name' => 'Vivo',
                'slug' => 'vivo',
                'description' => 'Thương hiệu smartphone giá tốt',
                'status' => 'active',
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
```

---

### 10.5. Tạo Controller cho Brands

```bash
php artisan make:controller Api/BrandController --api
```

**File: `app/Http/Controllers/Api/BrandController.php`**

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Display a listing of brands
     */
    public function index()
    {
        $brands = Brand::orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $brands
        ]);
    }

    /**
     * Store a newly created brand
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:brands,name',
            'logo' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $brand = Brand::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Thương hiệu đã được tạo thành công',
            'data' => $brand
        ], 201);
    }

    /**
     * Display the specified brand
     */
    public function show($id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thương hiệu'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $brand
        ]);
    }

    /**
     * Update the specified brand
     */
    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thương hiệu'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:brands,name,' . $id,
            'logo' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $brand->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Thương hiệu đã được cập nhật',
            'data' => $brand
        ]);
    }

    /**
     * Remove the specified brand
     */
    public function destroy($id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thương hiệu'
            ], 404);
        }

        $brand->delete();

        return response()->json([
            'success' => true,
            'message' => 'Thương hiệu đã được xóa'
        ]);
    }
}
```

---

### 10.6. Thêm Routes cho Brands

**File: `routes/api.php`** (Thêm vào cuối file)

```php
use App\Http\Controllers\Api\BrandController;

// ============================================
// BRANDS ROUTES
// ============================================
Route::prefix('brands')->group(function () {
    // Public routes
    Route::get('/', [BrandController::class, 'index']);
    Route::get('/{id}', [BrandController::class, 'show']);
    
    // Protected routes (Admin/Staff only)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [BrandController::class, 'store']);
        Route::put('/{id}', [BrandController::class, 'update']);
        Route::delete('/{id}', [BrandController::class, 'destroy']);
    });
});
```

---

## 🚀 BƯỚC 11: TẠO CATEGORIES APIs

### 11.1. Tạo Migration cho Categories

```bash
php artisan make:migration create_categories_table
```

---

### 11.2. Viết code Migration: `categories`

**File: `database/migrations/xxxx_xx_xx_xxxxxx_create_categories_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
```

---

### 11.3. Tạo Model Category

```bash
php artisan make:model Category
```

**File: `app/Models/Category.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'icon',
        'description',
        'status',
        'order',
    ];

    /**
     * Auto-generate slug from name
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Relationship: Category has many Products
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Relationship: Parent Category
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Relationship: Child Categories
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
```

---

### 11.4. Tạo Seeder cho Categories

```bash
php artisan make:seeder CategorySeeder
```

**File: `database/seeders/CategorySeeder.php`**

```php
<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Parent Categories
        $smartphone = Category::create([
            'name' => 'Điện thoại',
            'slug' => 'dien-thoai',
            'description' => 'Điện thoại thông minh',
            'status' => 'active',
            'order' => 1,
        ]);

        $laptop = Category::create([
            'name' => 'Laptop',
            'slug' => 'laptop',
            'description' => 'Máy tính xách tay',
            'status' => 'active',
            'order' => 2,
        ]);

        $tablet = Category::create([
            'name' => 'Tablet',
            'slug' => 'tablet',
            'description' => 'Máy tính bảng',
            'status' => 'active',
            'order' => 3,
        ]);

        $accessory = Category::create([
            'name' => 'Phụ kiện',
            'slug' => 'phu-kien',
            'description' => 'Phụ kiện công nghệ',
            'status' => 'active',
            'order' => 4,
        ]);

        // Child Categories for Smartphone
        Category::create([
            'parent_id' => $smartphone->id,
            'name' => 'iPhone',
            'slug' => 'iphone',
            'status' => 'active',
            'order' => 1,
        ]);

        Category::create([
            'parent_id' => $smartphone->id,
            'name' => 'Samsung Galaxy',
            'slug' => 'samsung-galaxy',
            'status' => 'active',
            'order' => 2,
        ]);

        // Child Categories for Accessory
        Category::create([
            'parent_id' => $accessory->id,
            'name' => 'Tai nghe',
            'slug' => 'tai-nghe',
            'status' => 'active',
            'order' => 1,
        ]);

        Category::create([
            'parent_id' => $accessory->id,
            'name' => 'Sạc dự phòng',
            'slug' => 'sac-du-phong',
            'status' => 'active',
            'order' => 2,
        ]);
    }
}
```

---

### 11.5. Tạo Controller cho Categories

```bash
php artisan make:controller Api/CategoryController --api
```

**File: `app/Http/Controllers/Api/CategoryController.php`**

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index()
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:100',
            'icon' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
            'order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category = Category::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Danh mục đã được tạo thành công',
            'data' => $category
        ], 201);
    }

    /**
     * Display the specified category
     */
    public function show($id)
    {
        $category = Category::with('children', 'parent')->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy danh mục'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy danh mục'
            ], 404);
        }

        $validated = $request->validate([
            'parent_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:100',
            'icon' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
            'order' => 'nullable|integer',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Danh mục đã được cập nhật',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified category
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy danh mục'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Danh mục đã được xóa'
        ]);
    }
}
```

---

### 11.6. Thêm Routes cho Categories

**File: `routes/api.php`** (Thêm vào cuối file)

```php
use App\Http\Controllers\Api\CategoryController;

// ============================================
// CATEGORIES ROUTES
// ============================================
Route::prefix('categories')->group(function () {
    // Public routes
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{id}', [CategoryController::class, 'show']);
    
    // Protected routes (Admin/Staff only)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });
});
```

---

## 🚀 BƯỚC 12: RUN MIGRATIONS & SEEDERS

### 12.1. Run Migrations

```bash
php artisan migrate
```

**Kết quả:** Phải thấy:
- ✅ `brands` table created
- ✅ `categories` table created

---

### 12.2. Update DatabaseSeeder

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
            // RolePermissionSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
        ]);
    }
}
```

---

### 12.3. Run Seeders

```bash
php artisan db:seed
```

**Kết quả:** Phải thấy:
```
Seeding: Database\Seeders\BrandSeeder
Seeded:  Database\Seeders\BrandSeeder (xx.xx ms)
Seeding: Database\Seeders\CategorySeeder
Seeded:  Database\Seeders\CategorySeeder (xx.xx ms)
```

---

## 🚀 BƯỚC 13: TEST BRANDS & CATEGORIES APIs

### 13.1. Test GET All Brands

**Request:**
```
GET http://localhost:8000/api/brands
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Apple",
      "slug": "apple",
      "logo": null,
      "description": "Thương hiệu công nghệ hàng đầu thế giới",
      "status": "active",
      "created_at": "2026-01-29T08:00:00.000000Z",
      "updated_at": "2026-01-29T08:00:00.000000Z"
    },
    ...
  ]
}
```

---

### 13.2. Test GET All Categories

**Request:**
```
GET http://localhost:8000/api/categories
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "parent_id": null,
      "name": "Điện thoại",
      "slug": "dien-thoai",
      "children": [
        {
          "id": 5,
          "parent_id": 1,
          "name": "iPhone",
          "slug": "iphone",
          ...
        }
      ],
      ...
    }
  ]
}
```

---

### 13.3. Test CREATE Brand (Protected)

**Request:**
```
POST http://localhost:8000/api/brands
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json

{
  "name": "Realme",
  "description": "Thương hiệu smartphone giá rẻ",
  "status": "active"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Thương hiệu đã được tạo thành công",
  "data": {
    "id": 6,
    "name": "Realme",
    "slug": "realme",
    ...
  }
}
```

---

## ✅ CHECKLIST - BRANDS & CATEGORIES

### Bước 10: Brands
- [ ] Tạo migration `brands`
- [ ] Viết code migration
- [ ] Tạo Model `Brand`
- [ ] Tạo Seeder `BrandSeeder`
- [ ] Tạo Controller `BrandController`
- [ ] Thêm routes cho brands
- [ ] Run migration
- [ ] Run seeder
- [ ] Test GET all brands
- [ ] Test CREATE brand (với token)

### Bước 11: Categories
- [ ] Tạo migration `categories`
- [ ] Viết code migration (có parent_id)
- [ ] Tạo Model `Category`
- [ ] Tạo Seeder `CategorySeeder`
- [ ] Tạo Controller `CategoryController`
- [ ] Thêm routes cho categories
- [ ] Run migration
- [ ] Run seeder
- [ ] Test GET all categories
- [ ] Test CREATE category (với token)

---

# PHẦN 3: PRODUCTS APIs

## 🚀 BƯỚC 12: TẠO PRODUCTS APIs

### 12.1. Tạo Migration cho Products

```bash
php artisan make:migration create_products_table
```

### 12.2. Viết code Migration: `products`

**File: `database/migrations/xxxx_xx_xx_xxxxxx_create_products_table.php`**

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->foreignId('brand_id')->constrained()->onDelete('cascade');
    $table->foreignId('category_id')->constrained()->onDelete('cascade');
    $table->string('name', 200);
    $table->string('slug', 200)->unique();
    $table->string('sku', 50)->unique();
    $table->decimal('price', 15, 2);
    $table->decimal('sale_price', 15, 2)->nullable();
    $table->integer('stock_quantity')->default(0);
    $table->string('thumbnail')->nullable();
    $table->text('description')->nullable();
    $table->enum('status', ['active', 'inactive', 'out_of_stock'])->default('active');
    $table->timestamps();
    $table->softDeletes();
});
```

### 12.3. Tạo Model Product

```bash
php artisan make:model Product
```

**File: `app/Models/Product.php`** (Đã thêm quan hệ brand/category)

### 12.4. Tạo Seeder cho Products

```bash
php artisan make:seeder ProductSeeder
```

**File: `database/seeders/ProductSeeder.php`**

### 12.5. Tạo Controller cho Products

```bash
php artisan make:controller Api/ProductController --api
```

### 12.6. Thêm Routes cho Products

**File: `routes/api.php`**

```php
use App\Http\Controllers\Api\ProductController;

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']); // Public
    Route::get('/{id}', [ProductController::class, 'show']); // Public
    
    Route::middleware(['auth:sanctum', 'role:admin,staff'])->group(function () {
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });
});
```

---

## ✅ CHECKLIST - PRODUCTS

### Bước 12: Products
- [x] Tạo migration `products`
- [x] Viết code migration
- [x] Tạo Model `Product`
- [x] Tạo Seeder `ProductSeeder`
- [x] Tạo Controller `ProductController`
- [x] Thêm routes cho products
- [x] Run migration
- [x] Run seeder
- [x] Test GET all products
- [x] Test CREATE Product (với token)

---

**Created:** 2026-01-29  
**Version:** 3.0 (Added Products)  
**Status:** ✅ Complete Step-by-Step Guide

