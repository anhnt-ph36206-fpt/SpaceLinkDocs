# 🗄️ DATABASE & MIGRATIONS

**Module:** Foundation  
**Priority:** 🔴 Critical  
**Độ khó:** ⭐⭐ Dễ  
**Thời gian:** 4-6 giờ  
**Day:** 1-2

---

## 🎯 MỤC TIÊU

- ✅ Import database SQL vào MySQL
- ✅ Verify 27 tables với data mẫu
- ✅ Tạo 27 Laravel migrations
- ✅ Test `php artisan migrate:fresh` thành công

---

## 📋 PREREQUISITES

- ✅ MySQL đã cài đặt (Laragon/XAMPP)
- ✅ Laravel đã cài đặt (hoặc sẵn sàng tạo project mới)
- ✅ File SQL: `D:\WebServers\laragon6\www\SpaceLinkDocs\import-sql\new-claude-sl_db.sql`

---

## 📝 CHECKLIST CHI TIẾT

### **STEP 1: Import Database SQL** (30 phút)

#### **Option A: Dùng HeidiSQL (Laragon)**
```bash
# 1. Mở Laragon → Database → HeidiSQL
# 2. File → Run SQL file
# 3. Chọn file: new-claude-sl_db.sql
# 4. Click Execute
```

#### **Option B: Command Line**
```bash
# Mở terminal tại folder import-sql:
cd D:\WebServers\laragon6\www\SpaceLinkDocs\import-sql

# Import:
mysql -u root -p < new-claude-sl_db.sql

# Hoặc nếu không có password:
mysql -u root < new-claude-sl_db.sql
```

#### **Verify:**
```sql
-- Kiểm tra database đã tạo:
SHOW DATABASES;

-- Sử dụng database:
USE spacelink_db;

-- Kiểm tra tables:
SHOW TABLES;
-- Expected: 27 tables

-- Kiểm tra data mẫu:
SELECT * FROM roles;
SELECT * FROM brands;
SELECT * FROM categories;
```

**✅ Checkpoint:** 27 bảng có trong database, data mẫu có sẵn

---

### **STEP 2: Setup Laravel Project** (30 phút)

#### **Option A: Project mới**
```bash
# Tạo Laravel project:
cd D:\WebServers\laragon6\www\spacelink
composer create-project laravel/laravel backend

cd backend

# Install dependencies:
composer require laravel/sanctum
composer require --dev laravel/pint
```

#### **Option B: Project có sẵn**
```bash
# Chỉ cần verify:
cd D:\WebServers\laragon6\www\spacelink\backend
php artisan --version
# Expected: Laravel Framework 12.x.x
```

#### **Config `.env`:**
```env
APP_NAME=SpaceLink
APP_ENV=local
APP_KEY=base64:xxx... (generate if needed)
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=spacelink_db
DB_USERNAME=root
DB_PASSWORD=

# Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost:3000,127.0.0.1:3000
```

#### **Test connection:**
```bash
php artisan tinker

# Test DB connection:
>>> DB::connection()->getPdo();
# Expected: PDO object (no errors)

>>> DB::table('ro les')->count();
# Expected: 3 (admin, staff, customer)
```

**✅ Checkpoint:** Laravel kết nối được database

---

### **STEP 3: Tạo Migrations** (3-4 giờ)

#### **Thứ tự tạo migrations (theo dependencies):**

**Group 1: Không phụ thuộc (5 bảng)**
```bash
php artisan make:migration create_roles_table
php artisan make:migration create_permissions_table
php artisan make:migration create_brands_table
php artisan make:migration create_attribute_groups_table
php artisan make:migration create_vouchers_table
```

**Group 2: Level 1 dependencies (7 bảng)**
```bash
php artisan make:migration create_role_permissions_table
php artisan make:migration create_users_table
php artisan make:migration create_password_reset_tokens_table
php artisan make:migration create_categories_table
php artisan make:migration create_attributes_table
php artisan make:migration create_news_table
php artisan make:migration create_settings_table
```

**Group 3: Level 2 dependencies (5 bảng)**
```bash
php artisan make:migration create_user_addresses_table
php artisan make:migration create_products_table
php artisan make:migration create_contacts_table
php artisan make:migration create_orders_table
php artisan make:migration create_comments_table
```

**Group 4: Level 3 dependencies (10 bảng)**
```bash
php artisan make:migration create_product_images_table
php artisan make:migration create_product_variants_table
php artisan make:migration create_product_variant_attributes_table
php artisan make:migration create_product_views_table
php artisan make:migration create_cart_table
php artisan make:migration create_order_items_table
php artisan make:migration create_order_status_history_table
php artisan make:migration create_payment_transactions_table
php artisan make:migration create_reviews_table
php artisan make:migration create_comment_reports_table
```

---

### **STEP 4: Viết Migration Code** (2-3 giờ)

#### **Ví dụ: Migration cho `roles` table**

```php
<?php
// database/migrations/xxxx_xx_xx_create_roles_table.php

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

#### **Ví dụ: Migration cho `users` table (có foreign key)**

```php
<?php
// database/migrations/xxxx_xx_xx_create_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->default(3)->constrained('roles')->restrictOnDelete();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('fullname', 150);
            $table->string('phone', 15)->nullable();
            $table->string('avatar')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active');
            $table->rememberToken();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('role_id');
            $table->index('email');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
```

#### **Ví dụ: Migration cho `products` table (complex)**

```php
<?php
// database/migrations/xxxx_xx_xx_create_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku', 100)->unique()->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->decimal('price', 15, 2);
            $table->decimal('sale_price', 15, 2)->nullable();
            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedInteger('sold_count')->default(0);
            $table->unsignedInteger('view_count')->default(0);
            $table->boolean('is_featured')->default(false);
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
            $table->fullText(['name', 'description']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

---

### **STEP 5: Test Migrations** (30 phút)

```bash
# Chạy migrations:
php artisan migrate:fresh

# Expected output:
# Migration table created successfully.
# Migrating: xxxx_xx_xx_create_roles_table
# Migrated:  xxxx_xx_xx_create_roles_table (xx.xxms)
# ...
# (27 migrations)

# Nếu có lỗi, fix và chạy lại:
php artisan migrate:fresh

# Verify tables:
php artisan tinker
>>> DB::select('SHOW TABLES');
>>> DB::table('roles')->count(); // Should be 0 (chưa có data)
```

**Common Errors & Solutions:**

```bash
# Error: Foreign key constraint fails
# → Check migration order (dependencies)
# → Run migrations in correct order

# Error: Syntax error in migration
# → Check column types
# → Check constraints
# → Check indexes

# Error: Table already exists
# → Run: php artisan migrate:fresh --force
```

**✅ Checkpoint:** All 27 migrations chạy thành công

---

### **STEP 6: Tạo Seeders (Optional - có thể dùng SQL data có sẵn)** (1 giờ)

#### **Option A: Dùng SQL data có sẵn**
Bỏ qua bước này vì SQL đã có INSERT statements

#### **Option B: Tạo Laravel Seeders**

```bash
php artisan make:seeder RoleSeeder
php artisan make:seeder PermissionSeeder
php artisan make:seeder BrandSeeder
php artisan make:seeder CategorySeeder
php artisan make:seeder AttributeSeeder
```

**Ví dụ: RoleSeeder**
```php
<?php
// database/seeders/RoleSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name' => 'admin', 'display_name' => 'Quản trị viên', 'description' => 'Có toàn quyền quản lý hệ thống'],
            ['name' => 'staff', 'display_name' => 'Nhân viên', 'description' => 'Quản lý đơn hàng và sản phẩm'],
            ['name' => 'customer', 'display_name' => 'Khách hàng', 'description' => 'Người dùng mua hàng'],
        ]);
    }
}
```

**Chạy seeders:**
```bash
php artisan db:seed
# Or specific seeder:
php artisan db:seed --class=RoleSeeder
```

---

## ✅ TESTING CHECKLIST

### **Database:**
- [ ] Database `spacelink_db` tồn tại
- [ ] 27 tables có trong database
- [ ] Data mẫu có sẵn (roles, permissions, brands...)

### **Laravel:**
- [ ] Laravel project chạy được
- [ ] `.env` config đúng
- [ ] Kết nối database thành công

### **Migrations:**
- [ ] 27 migration files đã tạo
- [ ] `php artisan migrate:fresh` chạy thành công
- [ ] Không có errors
- [ ] Tất cả tables được tạo
- [ ] Foreign keys hoạt động
- [ ] Indexes đã tạo

---

## 🚨 TROUBLESHOOTING

### **Problem: Cannot connect to database**
```bash
# Check MySQL running:
# Laragon: Start MySQL

# Check credentials in .env:
DB_DATABASE=spacelink_db
DB_USERNAME=root
DB_PASSWORD=

# Test connection:
php artisan tinker
>>> DB::connection()->getPdo();
```

### **Problem: Migration foreign key error**
```
# Ensure migrations run in correct order
# Check: database/migrations/*.php filenames
# Format: YYYY_MM_DD_HHMMSS_table_name.php
# Earlier dependencies must have earlier timestamps
```

### **Problem: Table already exists**
```bash
# Drop all tables and re-run:
php artisan migrate:fresh --force

# Or manually:
DROP DATABASE spacelink_db;
CREATE DATABASE spacelink_db;
USE spacelink_db;
```

---

## 📚 RESOURCES

- **Laravel Migrations:** https://laravel.com/docs/12.x/migrations
- **Schema Builder:** https://laravel.com/docs/12.x/migrations#columns
- **Foreign Keys:** https://laravel.com/docs/12.x/migrations#foreign-key-constraints

---

## ✅ DELIVERABLES

- [x] Database imported
- [x] 27 migrations created
- [x] Migrations tested
- [x] Ready for Models

**Next:** `features/02_models_and_relationships.md`

---

**Last updated:** 2026-01-27
