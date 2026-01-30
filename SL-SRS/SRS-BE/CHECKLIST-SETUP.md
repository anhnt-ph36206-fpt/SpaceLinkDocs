# CHECKLIST - Làm theo thứ tự này!
**Laravel 12 Setup - Không bỏ sót bước nào!**

---

## 📋 THỨ TỰ THỰC HIỆN

```
BƯỚC 1: Project Setup
   ↓
BƯỚC 2: CORS Config
   ↓
BƯỚC 3: Sanctum Setup
   ↓
BƯỚC 4: Tạo Migrations
   ↓
BƯỚC 5: Tạo Models
   ↓
BƯỚC 6: Tạo Seeders
   ↓
BƯỚC 7: Tạo Controller
   ↓
BƯỚC 8: Setup Routes
   ↓
BƯỚC 9: Test Postman
```

---

## ✅ BƯỚC 1: PROJECT SETUP

```bash
# 1.1. Tạo project (hoặc cd vào project có sẵn)
cd D:\WebServers\laragon6\www\SpaceLink-Projects\SL-SRS\SRS-BE
cd spacelink-backend-test

# 1.2. Config .env
# Sửa: DB_DATABASE=spacelink_db

# 1.3. Tạo database
# Mở HeidiSQL → Create database: spacelink_db

# 1.4. Test
php artisan migrate
```

**✅ Phải thấy:** Migrations chạy thành công

---

## ✅ BƯỚC 2: CORS CONFIG

```bash
# 2.1. Tạo file
touch config/cors.php
# Hoặc: Right click config → New File → cors.php
```

**2.2. Copy code từ file `07-step-by-step-laravel12.md` → Bước 2.2**

**2.3. Update `bootstrap/app.php`**
- Copy code từ file `07-step-by-step-laravel12.md` → Bước 2.3

**✅ Phải có:** File `config/cors.php` và middleware trong `bootstrap/app.php`

---

## ✅ BƯỚC 3: SANCTUM SETUP

```bash
# 3.1. Check Sanctum
composer show laravel/sanctum

# 3.2. Run migration
php artisan migrate
```

**✅ Phải thấy:** Bảng `personal_access_tokens` trong database

---

## ✅ BƯỚC 4: TẠO MIGRATIONS

```bash
# 4.1. Tạo 3 migration files
php artisan make:migration create_roles_table
php artisan make:migration create_permissions_table
php artisan make:migration create_role_permissions_table
```

**4.2. Viết code 3 migrations**
- Copy từ file `07-step-by-step-laravel12.md` → Bước 4.2, 4.3, 4.4

**4.3. Update migration `users`**
- Copy từ file `07-step-by-step-laravel12.md` → Bước 4.5

```bash
# 4.4. Run migrations
php artisan migrate:fresh
```

**✅ Phải thấy:** 4 bảng mới (roles, permissions, role_permissions, users)

---

## ✅ BƯỚC 5: TẠO MODELS

```bash
# 5.1. Tạo 2 model files
php artisan make:model Role
php artisan make:model Permission
```

**5.2. Viết code 2 models**
- Copy từ file `07-step-by-step-laravel12.md` → Bước 5.2, 5.3

**5.3. Update model `User`**
- Copy từ file `07-step-by-step-laravel12.md` → Bước 5.4

**✅ Phải có:**
- `use HasApiTokens` trong User Model
- Relationships trong cả 3 models

---

## ✅ BƯỚC 6: TẠO SEEDERS

```bash
# 6.1. Tạo seeder file
php artisan make:seeder RolePermissionSeeder
```

**6.2. Viết code seeder**
- Copy từ file `07-step-by-step-laravel12.md` → Bước 6.2
- **⚠️ QUAN TRỌNG:** Phải có `use App\Models\Role;` và `use App\Models\Permission;`

**6.3. Register seeder**
- Update `DatabaseSeeder.php`
- Copy từ file `07-step-by-step-laravel12.md` → Bước 6.3

```bash
# 6.4. Run seeder
php artisan db:seed
```

**✅ Phải thấy:**
- Bảng `roles`: 3 rows (admin, staff, customer)
- Bảng `permissions`: 7 rows
- Bảng `role_permissions`: Nhiều rows

---

## ✅ BƯỚC 7: TẠO CONTROLLER

```bash
# 7.1. Tạo controller
php artisan make:controller Api/AuthController
```

**7.2. Viết code controller**
- Copy từ file `07-step-by-step-laravel12.md` → Bước 7.2

**✅ Phải có:** 4 methods (register, login, logout, profile)

---

## ✅ BƯỚC 8: SETUP ROUTES

**8.1. Viết code routes**
- File: `routes/api.php`
- Copy từ file `07-step-by-step-laravel12.md` → Bước 8.1

**✅ Phải có:**
- Public routes: `/auth/register`, `/auth/login`
- Protected routes: `/auth/logout`, `/auth/profile`

---

## ✅ BƯỚC 9: TEST POSTMAN

```bash
# 9.1. Start server
php artisan serve
```

**9.2. Test Register**
```
POST http://localhost:8000/api/auth/register
Body: {
  "fullname": "Test User",
  "email": "test@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**✅ Phải nhận:** Token

**9.3. Test Login**
```
POST http://localhost:8000/api/auth/login
Body: {
  "email": "test@example.com",
  "password": "password123"
}
```

**✅ Phải nhận:** Token

**9.4. Test Profile**
```
GET http://localhost:8000/api/auth/profile
Authorization: Bearer {token}
```

**✅ Phải nhận:** User data

---

## 🚨 COMMON ERRORS

### ❌ "Class 'Role' not found"
**Nguyên nhân:** Chưa import Model trong Seeder

**Fix:**
```php
use App\Models\Role;
use App\Models\Permission;
```

---

### ❌ "Table 'roles' doesn't exist"
**Nguyên nhân:** Chưa run migrations

**Fix:**
```bash
php artisan migrate:fresh
```

---

### ❌ "CORS Error"
**Nguyên nhân:** Chưa tạo file `config/cors.php`

**Fix:** Làm lại Bước 2

---

### ❌ "Unauthenticated"
**Nguyên nhân:** Chưa gửi token hoặc sai format

**Fix:**
```
Authorization: Bearer {token}
# Phải có space sau "Bearer"
```

---

## 📂 FILES CẦN TẠO/SỬA

### Tạo mới:
- [ ] `config/cors.php`
- [ ] `database/migrations/xxxx_create_roles_table.php`
- [ ] `database/migrations/xxxx_create_permissions_table.php`
- [ ] `database/migrations/xxxx_create_role_permissions_table.php`
- [ ] `app/Models/Role.php`
- [ ] `app/Models/Permission.php`
- [ ] `database/seeders/RolePermissionSeeder.php`
- [ ] `app/Http/Controllers/Api/AuthController.php`

### Sửa:
- [ ] `bootstrap/app.php` (thêm CORS middleware)
- [ ] `database/migrations/0001_01_01_000000_create_users_table.php` (thêm role_id)
- [ ] `app/Models/User.php` (thêm HasApiTokens, fillable, relationship)
- [ ] `database/seeders/DatabaseSeeder.php` (register RolePermissionSeeder)
- [ ] `routes/api.php` (thêm auth routes)

---

## 🎯 FINAL CHECK

### Database:
- [ ] Bảng `roles` có 3 rows
- [ ] Bảng `permissions` có 7 rows
- [ ] Bảng `role_permissions` có data
- [ ] Bảng `users` có cột `role_id`
- [ ] Bảng `personal_access_tokens` tồn tại

### Code:
- [ ] `config/cors.php` tồn tại
- [ ] `User` Model có `use HasApiTokens`
- [ ] `RolePermissionSeeder` có import Models
- [ ] `routes/api.php` có auth routes

### Test:
- [ ] Register → Success + Token
- [ ] Login → Success + Token
- [ ] Profile → Success
- [ ] Logout → Success

---

**✅ NẾU TẤT CẢ CHECK PASS → BẠN ĐÃ SETUP THÀNH CÔNG!**

---

**File chi tiết:** `07-step-by-step-laravel12.md`  
**Created:** 2026-01-29
