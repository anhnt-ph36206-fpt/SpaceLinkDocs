# Backend Development Roadmap & Strategy
**SpaceLink E-Commerce Project**  
**Tech Stack:** Laravel 12 + ReactJS 19  
**Timeline:** 3 tuần cho chức năng cơ bản | 3 tháng tổng thể  
**Team:** 1 Backend Lead + 2 Backend Interns  
**Date:** 2026-01-28

---

## 📋 TÓM TẮT TÌNH HUỐNG

### Hiện trạng
- **Database:** 26-27 bảng đã có SQL thuần, đã convert sang migrations và test thành công
- **API:** Đã có 3 API controllers cơ bản (Brands, Categories, Products)
- **Authentication:** Laravel Sanctum đã cài đặt nhưng chưa config
- **Team BE:** 2 interns - skill CRUD cơ bản, chưa biết API/Auth/Middleware
- **Timeline:** 3 tuần phải hoàn thành chức năng bắt buộc

### Mục tiêu chính
1. ✅ Hoàn thiện Database + Migrations + Seeders
2. ✅ Xây dựng API đầy đủ cho FE team sử dụng
3. ✅ Implement Authentication (Đăng ký/Đăng nhập)
4. ✅ Hoàn thành luồng bán hàng chính: Browse → Cart → Checkout → Order
5. ✅ Documentation (Postman Collection) để FE test

---

## 🎯 ROADMAP TỔNG QUAN (3 TUẦN)

### **WEEK 1: Foundation & Core APIs** (Ngày 1-7)
**Mục tiêu:** Setup cơ sở hạ tầng + API cơ bản cho FE bắt đầu làm việc

| Ngày | Task | Owner | Priority |
|------|------|-------|----------|
| 1-2 | Database Review & Seeder Enhancement | Lead | P0 |
| 2-3 | Auth System Setup (Sanctum) | Lead | P0 |
| 3-4 | Products API (List, Detail, Search, Filter) | Lead + Intern 1 | P0 |
| 4-5 | Brands & Categories API | Intern 2 | P1 |
| 5-7 | Cart API (CRUD) | Lead + Intern 1 | P0 |

**Deliverables Week 1:**
- ✅ Auth APIs: Register, Login, Logout, Profile
- ✅ Products APIs: List (pagination, filter, search), Detail
- ✅ Brands & Categories APIs: List, Detail
- ✅ Cart APIs: Add, Update, Remove, Get Cart
- ✅ Postman Collection v1.0

---

### **WEEK 2: Transaction Flow** (Ngày 8-14)
**Mục tiêu:** Hoàn thành luồng đặt hàng và thanh toán

| Ngày | Task | Owner | Priority |
|------|------|-------|----------|
| 8-9 | Checkout API (Create Order) | Lead | P0 |
| 9-10 | Payment Integration (COD + VNPAY) | Lead | P0 |
| 10-11 | Order Management API (List, Detail, Status) | Intern 1 | P0 |
| 11-12 | Voucher API (Apply, Validate) | Intern 2 | P1 |
| 12-14 | Order History & Cancel Order | Lead + Interns | P0 |

**Deliverables Week 2:**
- ✅ Checkout API (validate stock, create order)
- ✅ Payment APIs (COD, VNPAY callback)
- ✅ Order APIs: List, Detail, Cancel
- ✅ Voucher APIs: List, Apply
- ✅ Email notifications (Order confirmation)
- ✅ Postman Collection v2.0

---

### **WEEK 3: Reviews, Admin & Polish** (Ngày 15-21)
**Mục tiêu:** Hoàn thiện tính năng phụ + Admin APIs

| Ngày | Task | Owner | Priority |
|------|------|-------|----------|
| 15-16 | Reviews & Comments API | Intern 1 | P1 |
| 16-17 | Admin: Product Management | Lead | P0 |
| 17-18 | Admin: Order Management | Lead | P0 |
| 18-19 | Admin: Dashboard Statistics | Intern 2 | P1 |
| 19-21 | Testing, Bug Fixes, Documentation | All | P0 |

**Deliverables Week 3:**
- ✅ Reviews & Comments APIs
- ✅ Admin APIs: Products, Orders, Users
- ✅ Dashboard Statistics API
- ✅ Complete API Documentation
- ✅ Testing & Bug Fixes

---

## 🔧 TECH DECISIONS

### 1. Authentication: **Laravel Sanctum** ✅
**Lý do chọn:**
- ✅ Đã built-in Laravel 12
- ✅ Đơn giản, dễ học cho team intern
- ✅ Phù hợp với SPA (ReactJS)
- ✅ Token-based, stateless
- ✅ Không cần config phức tạp như Passport

**Alternatives (KHÔNG dùng):**
- ❌ **Laravel Passport:** Quá phức tạp, overkill cho project này
- ❌ **JWT (tymon/jwt-auth):** Cần cài thêm package, phức tạp hơn Sanctum

### 2. API Structure: **RESTful API** ✅
**Lý do:**
- ✅ Chuẩn, dễ hiểu cho team yếu
- ✅ FE team đã quen với REST
- ✅ Postman dễ test

### 3. Database: **Giữ nguyên 26 bảng hiện tại** ✅
**Đánh giá:** Database design tốt, đầy đủ cho yêu cầu

---

## 📊 ĐÁNH GIÁ DATABASE HIỆN TẠI

### ✅ Điểm mạnh
1. **Cấu trúc rõ ràng:** 6 phần logic (Users, Products, Orders, Reviews, Content, System)
2. **Relationships đầy đủ:** Foreign keys, indexes hợp lý
3. **Soft Delete:** Có `deleted_at` cho các bảng quan trọng
4. **Audit Trail:** `created_at`, `updated_at` đầy đủ
5. **Flexible:** Hỗ trợ product variants, vouchers, reviews
6. **Guest Cart:** Có `session_id` cho khách vãng lai

### ⚠️ Điểm cần lưu ý
1. **Product Variants:** Cần validate logic khi add to cart (variant_id)
2. **Order Status Flow:** Cần define rõ state machine (pending → confirmed → shipping → delivered)
3. **Stock Management:** Cần transaction khi checkout để tránh oversell
4. **Voucher Validation:** Cần check date, quantity, min_order_amount
5. **Payment Callback:** Cần handle VNPAY/MOMO webhook

### 📝 Thiếu sót (Có thể bổ sung sau)
- ❌ Bảng `user_addresses` chưa có trong migration (cần thêm)
- ❌ Bảng `password_reset_tokens` cần verify
- ⚠️ Chưa có bảng `notifications` (nếu cần real-time)

---

## 🚀 HƯỚNG DẪN TRIỂN KHAI

### Phase 1: Database & Seeders (Ngày 1-2)

#### 1.1. Review & Fix Migrations
```bash
# Check migrations hiện tại
php artisan migrate:status

# Nếu cần rollback và migrate lại
php artisan migrate:fresh --seed
```

**Tasks:**
- [ ] Verify tất cả 26 bảng đã migrate đúng
- [ ] Thêm bảng `user_addresses` nếu thiếu
- [ ] Check foreign keys, indexes

#### 1.2. Enhance Seeders
**Mục tiêu:** Tạo data mẫu đầy đủ để FE test

```php
// DatabaseSeeder.php - Thứ tự chạy
$this->call([
    RoleSeeder::class,           // 1. Roles (admin, staff, customer)
    PermissionSeeder::class,     // 2. Permissions
    UserSeeder::class,           // 3. Users (1 admin, 2 customers)
    BrandSeeder::class,          // 4. Brands (5 brands)
    CategorySeeder::class,       // 5. Categories (10 categories)
    AttributeSeeder::class,      // 6. Attributes (color, ram, storage)
    ProductSeeder::class,        // 7. Products (20 products)
    ProductVariantSeeder::class, // 8. Product Variants
    VoucherSeeder::class,        // 9. Vouchers (3 vouchers)
    NewsSeeder::class,           // 10. News (5 articles)
]);
```

**Data mẫu cần có:**
- 1 Admin account: `admin@spacelink.com` / `password`
- 2 Customer accounts: `customer1@test.com`, `customer2@test.com`
- 5 Brands: Apple, Samsung, Xiaomi, OPPO, Vivo
- 10 Categories (có parent-child)
- 20 Products (có đủ variants, images)
- 3 Vouchers (1 active, 1 expired, 1 used up)

---

### Phase 2: Authentication Setup (Ngày 2-3)

#### 2.1. Config Sanctum
```php
// config/sanctum.php
'expiration' => 60 * 24 * 7, // Token expire sau 7 ngày
```

```php
// app/Http/Kernel.php (Laravel 12: bootstrap/app.php)
'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

#### 2.2. Auth APIs cần implement

| Endpoint | Method | Description | Auth |
|----------|--------|-------------|------|
| `/api/auth/register` | POST | Đăng ký tài khoản | No |
| `/api/auth/login` | POST | Đăng nhập | No |
| `/api/auth/logout` | POST | Đăng xuất | Yes |
| `/api/auth/profile` | GET | Xem profile | Yes |
| `/api/auth/profile` | PUT | Cập nhật profile | Yes |
| `/api/auth/change-password` | POST | Đổi mật khẩu | Yes |

**Request/Response Examples:**

```json
// POST /api/auth/register
{
  "fullname": "Nguyễn Văn A",
  "email": "user@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "0123456789"
}

// Response 201
{
  "success": true,
  "message": "Đăng ký thành công",
  "data": {
    "user": {
      "id": 1,
      "fullname": "Nguyễn Văn A",
      "email": "user@example.com",
      "role": "customer"
    },
    "token": "1|abc123..."
  }
}
```

---

### Phase 3: Products API (Ngày 3-4)

#### 3.1. Products Endpoints

| Endpoint | Method | Description | Auth |
|----------|--------|-------------|------|
| `/api/products` | GET | Danh sách sản phẩm (pagination, filter, search) | No |
| `/api/products/{id}` | GET | Chi tiết sản phẩm | No |
| `/api/products/featured` | GET | Sản phẩm nổi bật | No |
| `/api/products/best-selling` | GET | Sản phẩm bán chạy | No |
| `/api/products/new-arrivals` | GET | Sản phẩm mới | No |

#### 3.2. Query Parameters (Filter & Search)

```
GET /api/products?
  page=1
  &per_page=20
  &category_id=1
  &brand_id=2
  &min_price=1000000
  &max_price=5000000
  &sort_by=price
  &sort_order=asc
  &search=iphone
  &is_featured=1
```

#### 3.3. Response Structure

```json
{
  "success": true,
  "data": {
    "products": [
      {
        "id": 1,
        "name": "iPhone 15 Pro Max",
        "slug": "iphone-15-pro-max",
        "price": 29990000,
        "sale_price": 27990000,
        "discount_percent": 7,
        "image": "/images/products/iphone-15-pro-max.jpg",
        "rating": 4.8,
        "sold_count": 150,
        "stock": 50,
        "brand": {
          "id": 1,
          "name": "Apple",
          "slug": "apple"
        },
        "category": {
          "id": 5,
          "name": "iPhone",
          "slug": "iphone"
        }
      }
    ],
    "pagination": {
      "current_page": 1,
      "per_page": 20,
      "total": 100,
      "last_page": 5
    }
  }
}
```

---

### Phase 4: Cart API (Ngày 5-7)

#### 4.1. Cart Endpoints

| Endpoint | Method | Description | Auth |
|----------|--------|-------------|------|
| `/api/cart` | GET | Xem giỏ hàng | Optional* |
| `/api/cart/add` | POST | Thêm vào giỏ | Optional* |
| `/api/cart/update/{id}` | PUT | Cập nhật số lượng | Optional* |
| `/api/cart/remove/{id}` | DELETE | Xóa khỏi giỏ | Optional* |
| `/api/cart/clear` | DELETE | Xóa toàn bộ giỏ | Optional* |

**Note:** `Optional*` = Hỗ trợ cả user đã login (dùng `user_id`) và guest (dùng `session_id`)

#### 4.2. Logic quan trọng

```php
// Add to Cart - Validate stock
public function addToCart(Request $request)
{
    // 1. Validate input
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'variant_id' => 'nullable|exists:product_variants,id',
        'quantity' => 'required|integer|min:1',
    ]);

    // 2. Check stock
    if ($variant_id) {
        $stock = ProductVariant::find($variant_id)->quantity;
    } else {
        $stock = Product::find($product_id)->quantity;
    }

    if ($validated['quantity'] > $stock) {
        return response()->json([
            'success' => false,
            'message' => 'Số lượng vượt quá tồn kho'
        ], 400);
    }

    // 3. Add to cart (merge if exists)
    // ...
}
```

---

### Phase 5: Checkout & Orders (Ngày 8-14)

#### 5.1. Checkout Flow

```
1. User click "Thanh toán" từ Cart
2. FE gọi GET /api/cart/summary → Backend tính tổng tiền, validate stock
3. User nhập thông tin giao hàng, chọn payment method
4. FE gọi POST /api/orders/checkout
5. Backend:
   - Validate stock (lần 2)
   - Create order (status: pending)
   - Decrease stock (trong transaction)
   - Clear cart items
   - Return order_id + payment_url (nếu online)
6. User thanh toán:
   - COD: Order status = confirmed
   - VNPAY: Redirect → Callback → Update payment_status
7. Send email confirmation
```

#### 5.2. Orders Endpoints

| Endpoint | Method | Description | Auth |
|----------|--------|-------------|------|
| `/api/orders/checkout` | POST | Tạo đơn hàng | Yes |
| `/api/orders` | GET | Lịch sử đơn hàng | Yes |
| `/api/orders/{id}` | GET | Chi tiết đơn hàng | Yes |
| `/api/orders/{id}/cancel` | POST | Hủy đơn hàng | Yes |
| `/api/payment/vnpay/callback` | GET | VNPAY callback | No |

#### 5.3. Order Status Flow

```
pending → confirmed → processing → shipping → delivered → completed
   ↓
cancelled (chỉ khi status = pending hoặc confirmed)
```

---

## 📚 REAL-TIME FEATURES (Giải thích)

### 1. **Notifications (Thông báo)**
**Ví dụ:**
- Admin nhận thông báo khi có đơn hàng mới
- User nhận thông báo khi đơn hàng thay đổi trạng thái

**Cách implement (Nâng cao - Làm sau):**
- Laravel Broadcasting + Pusher/Laravel Echo
- Hoặc đơn giản: Polling (FE gọi API mỗi 30s)

### 2. **Inventory Update (Cập nhật tồn kho real-time)**
**Ví dụ:**
- User A đang xem sản phẩm có 5 cái
- User B mua 3 cái
- Màn hình User A tự động cập nhật còn 2 cái

**Cách implement:**
- WebSocket (Laravel Reverb - Laravel 11+)
- Hoặc: FE polling API `/api/products/{id}/stock` mỗi 10s

**Quyết định:** ❌ KHÔNG làm real-time cho phase 1 (3 tuần)
- Lý do: Phức tạp, team yếu, không phải yêu cầu bắt buộc
- Alternative: Validate stock khi checkout (đủ)

---

## 👥 PHÂN CÔNG CHI TIẾT

### **Backend Lead (Bạn)**
**Responsibilities:**
- ✅ Setup Auth system (Sanctum)
- ✅ Complex APIs: Checkout, Payment, Order Management
- ✅ Code review cho interns
- ✅ Database design decisions
- ✅ API documentation (Postman)
- ✅ Deployment & troubleshooting

**Weekly Tasks:**
- Week 1: Auth + Cart API
- Week 2: Checkout + Payment + Order
- Week 3: Admin APIs + Review code

---

### **Intern 1 (Mạnh hơn)**
**Learning Goals:**
- API Resource & Collections
- Eloquent Relationships
- Request Validation

**Weekly Tasks:**
- Week 1: Products API (List, Detail) - Pair với Lead
- Week 2: Order Management API (List, Detail)
- Week 3: Reviews & Comments API

**Hướng dẫn:**
```php
// Example: ProductController
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'category', 'images']);

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Pagination
        $products = $query->paginate(20);

        return ProductResource::collection($products);
    }
}
```

---

### **Intern 2 (Yếu hơn)**
**Learning Goals:**
- Basic CRUD
- Seeders & Factories
- Simple API endpoints

**Weekly Tasks:**
- Week 1: Brands & Categories API (Simple CRUD)
- Week 2: Voucher API (List, Apply)
- Week 3: Dashboard Statistics (Count queries)

**Hướng dẫn:**
```php
// Example: BrandController (Simple)
class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $brands
        ]);
    }

    public function show($id)
    {
        $brand = Brand::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $brand
        ]);
    }
}
```

---

## 📖 LEARNING RESOURCES CHO TEAM

### 1. Laravel Sanctum (Auth)
- 📹 Video: "Laravel Sanctum API Authentication" - Traversy Media
- 📄 Docs: https://laravel.com/docs/12.x/sanctum

### 2. API Resources
- 📹 Video: "Laravel API Resources" - Laracasts
- 📄 Docs: https://laravel.com/docs/12.x/eloquent-resources

### 3. Validation
- 📄 Docs: https://laravel.com/docs/12.x/validation

### 4. Eloquent Relationships
- 📹 Video: "Laravel Eloquent Relationships" - Codecourse

---

## 🎯 FE TEAM PRIORITIES (API cần NGAY)

### **Tuần 1 - FE cần:**
1. ✅ **Auth APIs** (Register, Login) - Để làm trang đăng ký/đăng nhập
2. ✅ **Products APIs** (List, Detail) - Để làm trang chủ, danh sách sản phẩm
3. ✅ **Brands & Categories APIs** - Để làm menu, filter
4. ✅ **Cart APIs** - Để làm giỏ hàng

**Communication:**
- Ngày 1: Gửi cho FE Postman Collection (Auth + Products)
- Ngày 3: Update Postman (Cart APIs)
- Ngày 5: Meeting sync progress

---

## 🛡️ BEST PRACTICES

### 1. API Response Format (Chuẩn hóa)

```json
// Success
{
  "success": true,
  "message": "Thành công",
  "data": { ... }
}

// Error
{
  "success": false,
  "message": "Lỗi xảy ra",
  "errors": {
    "email": ["Email đã tồn tại"]
  }
}
```

### 2. HTTP Status Codes

| Code | Meaning | Use Case |
|------|---------|----------|
| 200 | OK | GET, PUT success |
| 201 | Created | POST success |
| 400 | Bad Request | Validation error |
| 401 | Unauthorized | Not logged in |
| 403 | Forbidden | No permission |
| 404 | Not Found | Resource not found |
| 500 | Server Error | Unexpected error |

### 3. Validation Rules

```php
// Example: Register validation
$request->validate([
    'fullname' => 'required|string|max:150',
    'email' => 'required|email|unique:users,email',
    'password' => 'required|string|min:6|confirmed',
    'phone' => 'nullable|regex:/^0[0-9]{9}$/',
]);
```

### 4. Database Transactions (Quan trọng!)

```php
// Checkout - Phải dùng transaction
DB::transaction(function () use ($request) {
    // 1. Create order
    $order = Order::create([...]);

    // 2. Create order items
    foreach ($cartItems as $item) {
        OrderItem::create([...]);

        // 3. Decrease stock
        if ($item->variant_id) {
            ProductVariant::find($item->variant_id)
                ->decrement('quantity', $item->quantity);
        } else {
            Product::find($item->product_id)
                ->decrement('quantity', $item->quantity);
        }
    }

    // 4. Clear cart
    Cart::where('user_id', auth()->id())->delete();
});
```

---

## 🚨 CRITICAL NOTES

### 1. **Stock Management (Tránh oversell)**
```php
// ❌ WRONG - Race condition
$product = Product::find($id);
if ($product->quantity >= $request->quantity) {
    $product->quantity -= $request->quantity;
    $product->save();
}

// ✅ CORRECT - Atomic operation
Product::where('id', $id)
    ->where('quantity', '>=', $request->quantity)
    ->decrement('quantity', $request->quantity);

// Check if actually decremented
if (DB::affectedRows() === 0) {
    throw new Exception('Hết hàng');
}
```

### 2. **Guest Cart (Khách vãng lai)**
```php
// Sử dụng session_id để track cart của guest
$sessionId = $request->session()->getId();

Cart::updateOrCreate(
    [
        'session_id' => $sessionId,
        'product_id' => $productId,
        'variant_id' => $variantId,
    ],
    [
        'quantity' => DB::raw('quantity + ' . $quantity),
    ]
);

// Khi user login, merge guest cart vào user cart
```

### 3. **Order Code Generation**
```php
// Format: SL-YYYYMMDD-XXXX
// Example: SL-20260128-0001

$orderCode = 'SL-' . date('Ymd') . '-' . str_pad(
    Order::whereDate('created_at', today())->count() + 1,
    4,
    '0',
    STR_PAD_LEFT
);
```

---

## 📝 DOCUMENTATION CHECKLIST

### Postman Collection phải có:
- [ ] Environments (Local, Staging)
- [ ] Auth folder (Register, Login, Logout, Profile)
- [ ] Products folder (List, Detail, Search, Filter)
- [ ] Cart folder (Add, Update, Remove, Get)
- [ ] Orders folder (Checkout, List, Detail, Cancel)
- [ ] Admin folder (Products, Orders, Users)
- [ ] Pre-request Scripts (Auto set token)
- [ ] Tests (Auto validate response)

### README.md phải có:
- [ ] Installation steps
- [ ] Database setup
- [ ] Seeder commands
- [ ] API endpoints list
- [ ] Authentication guide
- [ ] Common errors & solutions

---

## 🎓 MENTORING STRATEGY CHO INTERNS

### Daily Standup (15 phút/ngày)
**Format:**
1. Hôm qua làm gì?
2. Hôm nay làm gì?
3. Có vấn đề gì cần hỗ trợ?

### Code Review Checklist
- [ ] Validation đầy đủ?
- [ ] Response format chuẩn?
- [ ] HTTP status code đúng?
- [ ] Có handle error?
- [ ] Có test API bằng Postman?
- [ ] Code có comment (nếu phức tạp)?

### Pair Programming Sessions
- **Intern 1:** 2 sessions/week (1h/session) - Complex topics
- **Intern 2:** 3 sessions/week (1h/session) - Basic guidance

---

## 🔄 NEXT STEPS (Sau 3 tuần)

### Phase 4: Advanced Features (Tuần 4-8)
- [ ] Admin Dashboard (Charts, Statistics)
- [ ] Product Variants Management
- [ ] Voucher System
- [ ] Email Notifications
- [ ] Image Upload (Cloudinary/S3)
- [ ] Reviews & Ratings
- [ ] News Management

### Phase 5: Optimization (Tuần 9-12)
- [ ] API Caching (Redis)
- [ ] Database Indexing
- [ ] N+1 Query Optimization
- [ ] API Rate Limiting
- [ ] Logging & Monitoring
- [ ] Testing (Unit, Feature)

---

## 📞 SUPPORT & ESCALATION

### Khi gặp vấn đề:
1. **Tự research:** Google, Laravel Docs (15 phút)
2. **Hỏi team:** Slack/Discord (30 phút)
3. **Escalate to Lead:** Nếu block quá 1 giờ

### Resources:
- Laravel Docs: https://laravel.com/docs/12.x
- Laracasts: https://laracasts.com
- Stack Overflow: https://stackoverflow.com/questions/tagged/laravel

---

## ✅ SUCCESS CRITERIA (3 tuần)

### Week 1:
- [ ] FE có thể đăng ký/đăng nhập
- [ ] FE có thể xem danh sách sản phẩm
- [ ] FE có thể thêm sản phẩm vào giỏ hàng

### Week 2:
- [ ] FE có thể checkout và tạo đơn hàng
- [ ] FE có thể xem lịch sử đơn hàng
- [ ] Payment COD hoạt động

### Week 3:
- [ ] Admin có thể quản lý sản phẩm
- [ ] Admin có thể quản lý đơn hàng
- [ ] API Documentation hoàn chỉnh

---

**Prepared by:** Backend Lead  
**Last Updated:** 2026-01-28  
**Version:** 1.0
