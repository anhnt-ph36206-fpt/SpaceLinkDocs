# GIẢI PHÁP AUTH & PHÂN QUYỀN (Auth Strategy)
Tài liệu này giải quyết yêu cầu: "Cài Sanctum, Spatie và bám sát Database".

## 1. TỒNG QUAN CORE vs LIBRARY
Để đảm bảo đúng Database đã thiết kế (`users` có `role_id`), ta sẽ chia chiến lược như sau:

### CORE (Dùng của Laravel - Tối ưu cho DB hiện tại)
Do DB của bạn thiết kế quan hệ `One-To-Many` (1 User có 1 Role qua `role_id`), còn thư viện **Spatie** mặc định dùng `Many-To-Many` (1 User có nhiều Roles qua bảng trung gian).
-> **Quyết định**: Sử dụng **Laravel Gates & Policies** (Core) để tự viết Logic phân quyền *giống hệt Spatie* nhưng tương thích 100% với DB của bạn.

### LIBRARY (Cài thêm)
1. **Laravel Sanctum**: Đã cài (quản lý Token đăng nhập).
2. **Standard Libraries**:
   - `maatwebsite/excel`: Export Excel (Yêu cầu bắt buộc).
   - `darkaonline/l5-swagger` hoặc `scramble`: Tạo Docs API.

---

## 2. CHI TIẾT IMPLEMENTATION (Custom "Spatie-like" Logic)

### Bước 1: Setup Sanctum (Authentication)
Đã chạy `composer require laravel/sanctum`.
Cần thêm Trait vào Model User:
```php
// app/Models/User.php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
    use HasApiTokens, Notifiable;
    // ...
}
```

### Bước 2: Setup Phân quyền (Authorization)
Chúng ta sẽ cài đặt 2 hàm quan trọng nhất của Spatie là `hasRole` và `hasPermissionTo` thủ công để khớp với DB.

**1. Định nghĩa Quan hệ trong Model (Đã có trong file Model Role/Permission trước):**
- User `belongsTo` Role.
- Role `belongsToMany` Permissions.

**2. Thêm Helper Trait cho User:**
Tạo file `app/Traits/HasPermissionsTrait.php`:
```php
namespace App\Traits;

trait HasPermissionsTrait {
    // Check Role (VD: $user->hasRole('admin'))
    public function hasRole($roleName) {
        return $this->role->name === $roleName;
    }

    // Check Permission (VD: $user->hasPermission('products.create'))
    public function hasPermission($permissionName) {
        // 1. Lấy permission của Role hiện tại (Eager load để nhanh)
        $role = $this->role()->with('permissions')->first();
        
        // 2. Check xem role này có permission đó không
        return $role->permissions->contains('name', $permissionName);
    }
}
```
Nhúng vào User Model:
```php
class User extends Authenticatable {
    use HasApiTokens, HasPermissionsTrait; //...
}
```

### Bước 3: Đăng ký Global Gate (Middleware)
Để dùng middleware kiểu `can:products.view`, bạn khai báo trong `AppServiceProvider`:

```php
// app/Providers/AppServiceProvider.php
use Illuminate\Support\Facades\Gate;
use App\Models\User;

public function boot(): void
{
    // Admin luôn có mọi quyền
    Gate::before(function (User $user, $ability) {
        if ($user->hasRole('admin')) {
            return true;
        }
    });

    // Định nghĩa logic check quyền động
    // Lưu ý: Laravel 12 dùng Gate::define hơi khác, 
    // nhưng cách tốt nhất là dùng Middleware check permission trực tiếp.
}
```

---

## 3. FLOW LÀM VIỆC THỰC TẾ
Khi bạn code API `Create Product`:

**Cách 1: Dùng Middleware (Routes)**
```php
// routes/api.php
Route::middleware(['auth:sanctum'])->group(function () {
    // Chỉ check login
    Route::get('/products', [ProductController::class, 'index']); 
    
    // Check quyền cụ thể
    Route::post('/products', [ProductController::class, 'store'])
        ->can('products.create'); // Gate sẽ check $user->hasPermission('products.create')
});
```

**Cách 2: Check trong Code**
```php
public function store(Request $request) {
    if (!$request->user()->hasRole('admin')) {
        abort(403, 'Bạn không phải Admin');
    }
    // ...
}
```

## TỔNG KẾT
- **Không cài `spatie/laravel-permission`**: Vì nó xung đột với cấu trúc bảng `users.role_id` và bảng `role_permissions` của bạn.
- **Dùng Custom Trait**: Vẫn có cú pháp `$user->hasRole()`, `$user->can()` y hệt Spatie nhưng bám sát DB 100%.
- **Sanctum**: Dùng để cấp Token khi Login.
