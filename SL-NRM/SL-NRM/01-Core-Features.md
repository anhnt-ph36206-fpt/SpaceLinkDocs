# LARAVEL 12 CORE FEATURES FOR API DEVELOPMENT
## 1. Eloquent ORM & Relationships
Laravel Core mạnh nhất ở khả năng xử lý Database. Với 27 bảng của SpaceLink, bạn sẽ dùng triệt để các tính năng sau:

### Cách hoạt động
- **Model**: Đại diện cho bảng (VD: `Product` model đại diện bảng `products`).
- **Relationships**:
    - `hasMany`: Category -> Products.
    - `belongsTo`: Product -> Category.
    - `belongsToMany`: User <-> Roles (thông qua `role_permissions` custom).
    - `hasOne`: User -> Cart.

### Ứng dụng trong SpaceLink
- **Eager Loading (`with()`)**: Khi lấy danh sách sản phẩm, bạn cần lấy luôn ảnh và danh mục để tránh lỗi N+1 query.
  ```php
  // Bad
  $products = Product::all();
  foreach($products as $p) { echo $p->category->name; } // N+1 queries
  
  // Good (Core Feature)
  $products = Product::with(['category', 'images', 'brand'])->get(); // 2 queries
  ```
- **Scopes**: Tạo các filter tái sử dụng cho "Sản phẩm bán chạy", "Sản phẩm mới".
  ```php
  public function scopeActive($query) {
      return $query->where('is_active', true);
  }
  ```

## 2. API Resources
Đây là lớp "Transformation". Khi trả data về cho FE (React), bạn không nên trả nguyên model User (lộ password, tokens).

### Cách hoạt động
- Biến đổi Model -> JSON Array.
- Cho phép format lại ngày tháng, ẩn các trường nhạy cảm, thêm field computed.
- **Path**: `app/Http/Resources/`

### Ứng dụng
- `ProductResource`: Format giá tiền, đường dẫn ảnh đầy đủ (full URL).
- `UserResource`: Ẩn `role_id`, hiển thị tên Role thay vì ID.

## 3. Form Requests (Validation)
Tách logic kiểm tra dữ liệu ra khỏi Controller.

### Cách hoạt động
- Class riêng chứa rules validation.
- Tự động return 422 JSON nếu lỗi -> FE React dễ dàng hiển thị lỗi.
- **Path**: `app/Http/Requests/`

### Ứng dụng
- `StoreProductRequest`:
  ```php
  return [
      'name' => 'required|string|max:255',
      'price' => 'required|numeric|min:0',
      'category_id' => 'required|exists:categories,id' // Auto check DB
  ];
  ```

## 4. Middleware, Policies & Gates
Bảo mật và Phân quyền.

### Cách hoạt động
- **Middleware**: Chặn cửa (VD: chưa login thì chặn).
- **Policies/Gates**: Logic quyền chi tiết (VD: User A có được sửa đơn hàng B không).

### Ứng dụng
- **Auth Middleware (`auth:sanctum`)**: Bảo vệ các route private.
- **Custom Gate**: Check quyền `products.create` dựa trên bảng `permissions` bạn đã tạo.

## 5. Events & Listeners
Xử lý logic phụ để không làm chậm API chính.

### Ứng dụng
- Khi `OrderCreated`:
    - Controller chỉ lưu Order -> Trả response "Success" ngay lập tức (100ms).
    - Fire Event `OrderCreated`.
    - Listener 1: Gửi email xác nhận (chạy ngầm).
    - Listener 2: Trừ tồn kho (nếu chưa xử lý rollback).
    - Listener 3: Thông báo cho Admin.

## 6. Task Scheduling
Chạy các tác vụ định kỳ (Cron job).

### Ứng dụng
- Tự động hủy đơn hàng "Pending" quá 24h mà chưa thanh toán.
- `php artisan schedule:work`
