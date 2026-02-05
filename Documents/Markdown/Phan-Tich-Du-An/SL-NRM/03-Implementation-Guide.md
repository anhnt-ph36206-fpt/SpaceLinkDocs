# IMPLEMENTATION & MAPPING GUIDE
Tài liệu này giải thích cách map các khái niệm vào cấu trúc thư mục của Laravel 12.

## 1. Cấu trúc thư mục API
```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/              <-- Nơi viết logic chính (AuthController, ProductController)
│   ├── Requests/             <-- Validation (StoreProductRequest)
│   ├── Resources/            <-- JSON Format (ProductResource)
│   └── Middleware/           <-- Check Role (CheckRoleMiddleware)
├── Models/                   <-- Tương tác DB (User, Product)
├── Policies/                 <-- Phân quyền (OrderPolicy)
routes/
└── api.php                   <-- Định nghĩa URL endpoint
```

## 2. Quy trình code 1 API (Ví dụ: Tạo sản phẩm)
Đây là "Best Practice" flow cho bạn:

**Bước 1: Route (`routes/api.php`)**
```php
Route::middleware(['auth:sanctum', 'role:admin,staff'])->post('/products', [ProductController::class, 'store']);
```

**Bước 2: Validation (`app/Http/Requests/StoreProductRequest.php`)**
```php
public function rules(): array {
    return [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric'
    ];
}
```

**Bước 3: Controller (`app/Http/Controllers/Api/ProductController.php`)**
```php
public function store(StoreProductRequest $request) {
    // 1. Logic xử lý (không cần validate lại nữa)
    $product = Product::create($request->validated());
    
    // 2. Trả về Resource (JSON chuẩn)
    return new ProductResource($product);
}
```

**Bước 4: Resource (`app/Http/Resources/ProductResource.php`)**
```php
public function toArray($request): array {
    return [
        'id' => $this->id,
        'name' => $this->name,
        'display_price' => number_format($this->price) . ' VND', // Format cho FE
    ];
}
```

## 3. Liên quan đến file SQL & Docs
- **Migration**: Bạn sẽ chuyển đổi file `new-claude-sl_db.sql` thành các file migration trong `database/migrations/`.
- **Chức năng CSV**: Mỗi dòng trong Excel chức năng sẽ tương ứng với 1 hoặc nhiều API Route. Ví dụ dòng "Đăng nhập" -> `POST /api/login`.
- **Role/Permission**: Sử dụng bảng `role_permissions` trong DB để check quyền trong Middleware hoặc Policy.
