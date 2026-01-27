# HƯỚNG DẪN THIẾT KẾ DATABASE CHO USERS VÀ ROLES

**CÂU HỎI:** Cần bao nhiêu BẢNG để quản lý users và roles?

---

## ❌ CÁCH SAI: Tạo nhiều bảng riêng cho từng role

### ❌ Cách này SAI - KHÔNG NÊN LÀM:

```sql
-- ❌ CÁCH SAI
CREATE TABLE admins (
    id BIGINT PRIMARY KEY,
    email VARCHAR(255),
    password VARCHAR(255),
    fullname VARCHAR(150)
);

CREATE TABLE staffs (
    id BIGINT PRIMARY KEY,
    email VARCHAR(255),
    password VARCHAR(255),
    fullname VARCHAR(150)
);

CREATE TABLE customers (
    id BIGINT PRIMARY KEY,
    email VARCHAR(255),
    password VARCHAR(255),
    fullname VARCHAR(150)
);

CREATE TABLE guests (
    id BIGINT PRIMARY KEY,
    session_id VARCHAR(255),
    -- ???
);
```

### ⚠️ Tại sao CÁCH NÀY SAI?

1. **Trùng lặp dữ liệu** - Mỗi bảng đều có email, password, fullname,...
2. **Khó mở rộng** - Nếu thêm role mới phải tạo bảng mới
3. **Khó truy vấn** - Muốn lấy tất cả users phải UNION nhiều bảng
4. **Khó quản lý** - Phải maintain nhiều bảng giống nhau
5. **Vi phạm nguyên tắc chuẩn hóa database**

---

## ✅ CÁCH ĐÚNG: Chỉ cần 1 BẢNG `users` + 1 BẢNG `roles`

### ✅ Thiết kế ĐÚNG - NÊN LÀM:

```sql
-- ✅ BƯỚC 1: Bảng roles (Danh sách các vai trò)
CREATE TABLE roles (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE NOT NULL,        -- 'admin', 'staff', 'customer'
    display_name VARCHAR(100) NOT NULL,      -- 'Quản trị viên', 'Nhân viên', 'Khách hàng'
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Data mẫu
INSERT INTO roles (id, name, display_name) VALUES
(1, 'admin', 'Quản trị viên'),
(2, 'staff', 'Nhân viên'),
(3, 'customer', 'Khách hàng');


-- ✅ BƯỚC 2: Bảng users (Tất cả users đều ở đây)
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    role_id BIGINT UNSIGNED NOT NULL DEFAULT 3,  -- Mặc định: 3 = customer
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(150) NOT NULL,
    phone VARCHAR(15) NULL,
    avatar VARCHAR(255) NULL,
    status ENUM('active', 'inactive', 'banned') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT,
    INDEX idx_role (role_id)
);
```

---

## 🎯 VỀ GUEST (Khách vãng lai) - QUAN TRỌNG!

### ❓ Câu hỏi: Guest có cần bảng riêng không?

**TRẢ LỜI: KHÔNG!**

**Guest KHÔNG CÓ RECORD trong bảng `users`**

### Giải thích:

**Guest (Khách vãng lai)** là người:
- Chưa đăng ký tài khoản
- Không đăng nhập
- Không có email/password trong hệ thống
- **⚠️ KHÔNG CÓ DỮ LIỆU trong bảng `users`**

**Registered Customer (Khách đã đăng ký)** là người:
- Đã đăng ký tài khoản
- Có email/password
- Có thể đăng nhập
- **✅ CÓ 1 RECORD trong bảng `users`** với `role_id = 3`

---

## 📊 VÍ DỤ CỤ THỂ VỀ DỮ LIỆU

### Bảng `roles`:

| id | name | display_name |
|----|------|--------------|
| 1 | admin | Quản trị viên |
| 2 | staff | Nhân viên |
| 3 | customer | Khách hàng |

**👉 Chỉ có 3 roles, KHÔNG có role "guest"**

---

### Bảng `users`:

| id | role_id | email | fullname | password | status |
|----|---------|-------|----------|----------|--------|
| 1 | 1 | admin@spacelink.com | Nguyễn Văn A | $2y$10$... | active |
| 2 | 2 | staff1@spacelink.com | Trần Thị B | $2y$10$... | active |
| 3 | 2 | staff2@spacelink.com | Lê Văn C | $2y$10$... | active |
| 4 | 3 | customer1@gmail.com | Phạm Văn D | $2y$10$... | active |
| 5 | 3 | customer2@gmail.com | Hoàng Thị E | $2y$10$... | active |

**Giải thích:**
- `id = 1` → Admin (Chủ cửa hàng)
- `id = 2, 3` → Staff (Nhân viên)
- `id = 4, 5` → Registered Customer (Khách đã đăng ký)

**⚠️ Lưu ý:** Guest (Khách vãng lai) **KHÔNG CÓ** trong bảng này!

---

## 🛒 GUEST CHECKOUT - XỬ LÝ NHƯ THẾ NÀO?

### VÍ DỤ: Khách vãng lai đặt hàng

**Tình huống:**
- Khách hàng tên "Nguyễn Văn F" vào web
- KHÔNG đăng ký, KHÔNG đăng nhập
- Thêm sản phẩm vào giỏ → Đặt hàng → Thanh toán

### Bảng `cart` (Giỏ hàng)

| id | user_id | session_id | product_id | quantity |
|----|---------|------------|------------|----------|
| 1 | 5 | NULL | 10 | 2 |
| 2 | **NULL** | **abc123xyz** | 15 | 1 |

**Giải thích:**
- **Dòng 1:** Khách đã đăng ký (user_id = 5) thêm sản phẩm 10 vào giỏ
- **Dòng 2:** Guest (session_id = "abc123xyz") thêm sản phẩm 15 vào giỏ
  - `user_id = NULL` → Chưa đăng ký
  - `session_id = "abc123xyz"` → Tracking bằng session của browser

---

### Bảng `orders` (Đơn hàng)

| id | user_id | order_code | shipping_name | shipping_phone | total_amount |
|----|---------|------------|---------------|----------------|--------------|
| 1 | 5 | ORD001 | Hoàng Thị E | 0912345678 | 15000000 |
| 2 | **NULL** | **ORD002** | **Nguyễn Văn F** | **0987654321** | 8000000 |

**Giải thích:**
- **Dòng 1:** Đơn hàng của Registered Customer (user_id = 5)
  - Có thể tra cứu lịch sử đơn hàng trong tài khoản
- **Dòng 2:** Đơn hàng của Guest (user_id = NULL)
  - `user_id = NULL` → Khách vãng lai
  - Vẫn lưu thông tin giao hàng (shipping_name, shipping_phone)
  - Tra cứu bằng `order_code` + email hoặc phone

---

## 🎯 TÓM TẮT - THIẾT KẾ DATABASE

### ✅ CHỈ CẦN 2 BẢNG CHÍNH:

```
roles (3 records)        users (nhiều records)
├─ 1. admin         ←─── ├─ User 1: admin (role_id = 1)
├─ 2. staff         ←─── ├─ User 2: staff (role_id = 2)
└─ 3. customer      ←─── ├─ User 3: staff (role_id = 2)
                          ├─ User 4: customer (role_id = 3)
                          └─ User 5: customer (role_id = 3)
```

**⚠️ Guest KHÔNG CÓ TRONG BẢNG `users`**

---

### 📋 Bảng so sánh:

| Loại người dùng | Trong bảng `users`? | role_id | Ví dụ |
|-----------------|---------------------|---------|-------|
| **Admin/Owner** | ✅ CÓ | 1 | Chủ cửa hàng đăng nhập vào admin panel |
| **Staff** | ✅ CÓ | 2 | Nhân viên đăng nhập vào admin panel |
| **Registered Customer** | ✅ CÓ | 3 | Khách đã đăng ký, có thể đăng nhập |
| **Guest Customer** | ❌ KHÔNG | - | Khách vãng lai, không đăng ký |

---

## 🔍 XỬ LÝ TRONG CODE (Laravel)

### Kiểm tra loại customer:

```php
// Kiểm tra xem có đăng nhập không?
if (Auth::check()) {
    // ✅ Registered Customer
    $user = Auth::user();
    echo "Xin chào: " . $user->fullname;
    
    // Lấy giỏ hàng
    $cart = Cart::where('user_id', $user->id)->get();
    
    // Lấy lịch sử đơn hàng
    $orders = Order::where('user_id', $user->id)->get();
    
} else {
    // ❌ Guest Customer
    echo "Xin chào khách! Bạn chưa đăng nhập.";
    
    // Lấy giỏ hàng bằng session
    $sessionId = session()->getId();
    $cart = Cart::where('session_id', $sessionId)->get();
    
    // Không có lịch sử đơn hàng (phải tra cứu bằng mã đơn)
}
```

---

### Xử lý checkout:

```php
public function checkout(Request $request)
{
    // Tạo đơn hàng
    $order = Order::create([
        'user_id' => Auth::check() ? Auth::id() : null,  // NULL nếu Guest
        'order_code' => $this->generateOrderCode(),
        'shipping_name' => $request->name,
        'shipping_phone' => $request->phone,
        'shipping_email' => $request->email,
        'shipping_address' => $request->address,
        'total_amount' => $request->total,
    ]);
    
    // Nếu Guest → user_id = NULL
    // Nếu Registered → user_id = ID của user
}
```

---

## 🎯 KẾT LUẬN CUỐI CÙNG

### ❌ KHÔNG CẦN 4 BẢNG:
```
❌ admins
❌ staffs  
❌ customers
❌ guests
```

### ✅ CHỈ CẦN 2 BẢNG:
```
✅ roles (3 records: admin, staff, customer)
✅ users (chứa admin, staff, registered customers)
```

### ⚠️ GUEST:
- **KHÔNG CÓ RECORD** trong bảng `users`
- Tracking bằng `session_id` trong giỏ hàng
- Đơn hàng có `user_id = NULL`
- Tra cứu đơn hàng bằng `order_code` + email/phone

---

## 🔄 LUỒNG HOẠT ĐỘNG

### Luồng 1: Registered Customer (Đã đăng ký)
1. User đăng ký → Tạo record trong `users` với `role_id = 3`
2. User đăng nhập → `Auth::check() = true`
3. Thêm sản phẩm vào giỏ → `cart.user_id = ID`
4. Đặt hàng → `orders.user_id = ID`
5. Xem lịch sử → Query `orders WHERE user_id = ID`

### Luồng 2: Guest Customer (Vãng lai)
1. User vào web → **KHÔNG đăng ký, KHÔNG đăng nhập**
2. Thêm sản phẩm vào giỏ → `cart.session_id = "abc123"`, `cart.user_id = NULL`
3. Đặt hàng → `orders.user_id = NULL`, nhập thông tin giao hàng
4. Tra cứu đơn → Dùng `order_code` + email/phone (không có tài khoản)

---

## 📝 DATABASE CỦA BẠN HIỆN TẠI

Bạn đã thiết kế HOÀN TOÀN ĐÚNG:

```sql
-- ✅ Bảng roles
CREATE TABLE roles (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE NOT NULL,  -- admin, staff, customer (3 roles)
    ...
);

-- ✅ Bảng users
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    role_id BIGINT UNSIGNED NOT NULL DEFAULT 3,  -- 1=admin, 2=staff, 3=customer
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    ...
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- ✅ Bảng cart - hỗ trợ cả Guest và Registered
CREATE TABLE cart (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NULL,      -- NULL = Guest
    session_id VARCHAR(255) NULL,       -- Dùng cho Guest
    product_id BIGINT UNSIGNED NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    ...
);

-- ✅ Bảng orders - hỗ trợ cả Guest và Registered
CREATE TABLE orders (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NULL,      -- NULL = Guest
    order_code VARCHAR(50) UNIQUE NOT NULL,
    shipping_name VARCHAR(150) NOT NULL,   -- Guest nhập tay, Registered fill sẵn
    shipping_phone VARCHAR(15) NOT NULL,
    ...
);
```

**👉 Database của bạn ĐÃ HOÀN HẢO! KHÔNG CẦN SỬA GÌ!**

---

## ✅ TRẢ LỜI CÂU HỎI CỦA BẠN:

### Câu hỏi: "Chỉ cần 3 bảng admin, customer và staff là đủ?"

**❌ SAI!** Không cần 3 bảng riêng.

**✅ ĐÚNG:** Chỉ cần **2 bảng:**
1. Bảng `roles` (3 records: admin, staff, customer)
2. Bảng `users` (chứa tất cả admin, staff, registered customers)

---

### Câu hỏi: "Bảng customers sẽ đảm nhận cả Guest và Registered?"

**❌ SAI!** Không có "bảng customers".

**✅ ĐÚNG:**
- Bảng `users` chỉ chứa **Registered Customers** (đã đăng ký)
- **Guest** không có record trong bảng `users`
- Guest được xử lý bằng `session_id` trong `cart` và `user_id = NULL` trong `orders`

---

### Câu hỏi: "Hay nên tách thành bảng guest và bảng registered?"

**❌ SAI!** Không cần tách.

**✅ ĐÚNG:**
- **Registered Customer:** Có record trong bảng `users` với `role_id = 3`
- **Guest Customer:** Không có record trong bảng `users`

---

## 📊 HÌNH ẢNH MINH HỌA

```
┌────────────────────────────────────────────────────┐
│                  WEBSITE E-COMMERCE                 │
└────────────────────────────────────────────────────┘
                         │
         ┌───────────────┼───────────────┐
         │               │               │
    ┌────▼────┐    ┌────▼────┐    ┌────▼────┐
    │  Admin  │    │  Staff  │    │Customer │
    │(Owner)  │    │         │    │         │
    └────┬────┘    └────┬────┘    └────┬────┘
         │               │               │
         │               │               ├──────────┐
         │               │               │          │
    ┌────▼───────────────▼───────────────▼────┐ ┌──▼─────┐
    │         BẢNG users                      │ │ Guest  │
    ├──────────────────────────────────────────┤ │(Không  │
    │ id │ role_id │ email         │ fullname │ │có trong│
    ├────┼─────────┼───────────────┼──────────┤ │ users) │
    │ 1  │ 1       │ admin@...     │ Nguyễn A │ └────────┘
    │ 2  │ 2       │ staff1@...    │ Trần B   │
    │ 3  │ 2       │ staff2@...    │ Lê C     │
    │ 4  │ 3       │ customer1@... │ Phạm D   │
    │ 5  │ 3       │ customer2@... │ Hoàng E  │
    └────┴─────────┴───────────────┴──────────┘
              ▲
              │
    ┌─────────┴─────────┐
    │    BẢNG roles     │
    ├───────────────────┤
    │ id │ name         │
    ├────┼──────────────┤
    │ 1  │ admin        │
    │ 2  │ staff        │
    │ 3  │ customer     │
    └────┴──────────────┘
```

---

**HẾT** ✅

**KẾT LUẬN:**
- ✅ Chỉ cần 2 bảng: `roles` + `users`
- ✅ Guest KHÔNG CÓ trong bảng `users`
- ✅ Database hiện tại của bạn ĐÃ ĐÚNG 100%
