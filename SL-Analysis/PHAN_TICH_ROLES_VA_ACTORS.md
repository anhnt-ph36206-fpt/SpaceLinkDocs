# PHÂN TÍCH CHI TIẾT VỀ ROLES VÀ ACTORS

**Ngày phân tích:** 2026-01-21  
**Mục đích:** Làm rõ việc phân quyền cho website bán hàng cửa hàng tư nhân

---

## 🎯 BỐI CẢNH DỰ ÁN

- **Loại hình:** Website bán hàng cho **1 cửa hàng công nghệ tư nhân** tại Hà Nội
- **Quy mô:** Cửa hàng nhỏ, có 1 chủ và vài nhân viên
- **Mục tiêu:** MVP cho đồ án tốt nghiệp (4 tháng)
- **Đặc điểm giao hàng:**
  - Khách tự đến cửa hàng mua
  - Nếu cần giao hàng → Chủ shop hoặc nhân viên tự giao
  - Hoặc thuê bên shipper thứ 3 bên ngoài (không quản lý trong hệ thống)

---

## ❓ CÂU HỎI CẦN GIẢI QUYẾT

### Câu hỏi 1: Chủ cửa hàng (Owner) và Admin có nên tách riêng thành 2 role không?

**Lý do phân vân:**
- Khi code xong và bàn giao cho chủ cửa hàng → Chủ cửa hàng chính là người đăng nhập vào Admin panel
- Chủ cửa hàng sẽ tự CRUD sản phẩm, đơn hàng, v.v.
- Vậy có cần phân biệt "Chủ cửa hàng" và "Admin" không?

### Câu hỏi 2: Customer có cần tách thành 2 role (Guest và Registered) không?

**Bối cảnh:**
- Customer có 2 trạng thái:
  1. **Khách vãng lai** (Guest) - không cần đăng nhập vẫn đặt hàng được
  2. **Khách đã đăng nhập** (Registered Customer) - có tài khoản
- Vậy có cần 2 role riêng không?

---

## 💡 PHÂN TÍCH VÀ KHUYẾN NGHỊ

---

## 📌 PHẦN 1: ADMIN vs OWNER (Chủ cửa hàng)

### Cách 1: TÁCH RIÊNG thành 2 role

**Mô hình:**
```
roles:
- owner (Chủ cửa hàng) - ID: 1
- admin (Quản trị viên) - ID: 2  
- staff - ID: 3
- customer - ID: 4
```

**Ưu điểm:**
- ✅ Phân biệt rõ ràng quyền sở hữu
- ✅ Owner có toàn quyền, Admin có quyền giới hạn hơn
- ✅ Dễ mở rộng nếu sau này có thêm admin (ví dụ: thuê người quản lý website)
- ✅ Có thể cấu hình: Owner không bao giờ bị khóa, Admin có thể bị khóa

**Nhược điểm:**
- ❌ **Phức tạp hơn** cho MVP của cửa hàng nhỏ
- ❌ Cần logic phân quyền chi tiết hơn
- ❌ Trong thực tế cửa hàng tư nhân nhỏ, **chủ chính là admin duy nhất**

**Khi nào nên dùng:**
- Khi có nhiều người quản lý website
- Khi cần phân quyền rất chi tiết
- Khi có kế hoạch mở rộng thành chuỗi cửa hàng

---

### Cách 2: CHUNG 1 ROLE "admin" ⭐ **KHUYẾN NGHỊ**

**Mô hình:**
```
roles:
- admin (Chủ cửa hàng / Quản trị viên) - ID: 1
- staff - ID: 2
- customer - ID: 3
```

**Cách phân biệt Owner nếu cần:**
```sql
-- Thêm trường vào bảng users (TÙY CHỌN)
ALTER TABLE users 
ADD COLUMN is_owner BOOLEAN DEFAULT FALSE COMMENT 'Đánh dấu chủ cửa hàng';

-- Hoặc đơn giản: User đầu tiên có role_id = 1 chính là Owner
```

**Ưu điểm:**
- ✅ **ĐƠN GIẢN** - phù hợp với MVP và cửa hàng nhỏ
- ✅ Dễ quản lý, dễ code
- ✅ Đúng với thực tế: Chủ cửa hàng tư nhân **chính là admin toàn quyền**
- ✅ Database hiện tại đã hỗ trợ sẵn (không cần sửa gì)
- ✅ Vẫn có thể mở rộng sau bằng cách thêm trường `is_owner`

**Nhược điểm:**
- ⚠️ Nếu sau này cần phân quyền chi tiết hơn, phải refactor

**Khi nào nên dùng:**
- ✅ Cửa hàng tư nhân nhỏ, **1 chủ duy nhất**
- ✅ MVP cho đồ án tốt nghiệp
- ✅ Không có kế hoạch mở rộng phức tạp trong 6-12 tháng đầu

---

### 🎯 KẾT LUẬN - ADMIN vs OWNER:

**👉 KHUYẾN NGHỊ: CHUNG 1 ROLE "admin"**

**Lý do:**
1. Phù hợp với bối cảnh: **cửa hàng tư nhân nhỏ, 1 chủ**
2. Đơn giản cho MVP (4 tháng)
3. Khi bàn giao: Chủ cửa hàng = Admin toàn quyền = Hợp lý
4. Database hiện tại ĐÃ ĐÚNG, không cần sửa

**Nếu cần phân biệt Owner trong tương lai:**
- Thêm trường `is_owner` vào `users` (hoặc)
- Quy ước: User đầu tiên có `role_id = 1` (admin) chính là Owner

**Trong code logic:**
```php
// Ví dụ Laravel
if ($user->role_id === 1 && $user->is_owner) {
    // Đây là chủ cửa hàng - không bao giờ được khóa
}

if ($user->role_id === 1) {
    // Đây là admin - có toàn quyền
}
```

---

## 📌 PHẦN 2: CUSTOMER - GUEST vs REGISTERED

### Câu hỏi: Có cần tách thành 2 role không?

**Câu trả lời: KHÔNG CẦN** ⭐

### Mô hình ĐÚNG:

**1 ROLE duy nhất: `customer`**

```
roles:
- customer (Khách hàng)
```

**Phân biệt Guest vs Registered bằng logic code, KHÔNG PHẢI role:**

| | Guest (Khách vãng lai) | Registered (Đã đăng ký) |
|---|---|---|
| **Account** | Không có tài khoản | Có tài khoản (trong bảng `users`) |
| **Đăng nhập** | Không | Có |
| **Lưu thông tin** | Nhập tay mỗi lần đặt hàng | Lưu trong `users`, `user_addresses` |
| **Giỏ hàng** | Lưu trong `session` hoặc `localStorage` | Lưu trong bảng `cart` (user_id) |
| **Đặt hàng** | `orders.user_id = NULL` | `orders.user_id = ID` |
| **Theo dõi đơn** | Tra cứu bằng mã đơn + email/SĐT | Xem trong "Lịch sử đơn hàng" |
| **Quyền lợi** | Không có điểm tích lũy, địa chỉ lưu sẵn | Có điểm tích lũy, địa chỉ lưu sẵn |

---

### Cách xử lý trong database:

#### 1. Bảng `cart` (Giỏ hàng)
```sql
CREATE TABLE cart (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NULL,        -- NULL nếu là Guest
    session_id VARCHAR(255) NULL,         -- Dùng cho Guest
    product_id BIGINT UNSIGNED NOT NULL,
    variant_id BIGINT UNSIGNED NULL,
    quantity INT UNSIGNED NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE SET NULL
);
```

**Logic:**
- **Guest:** `user_id = NULL`, lưu bằng `session_id` (Laravel Session ID)
- **Registered:** `user_id = ID`, `session_id` có thể NULL

#### 2. Bảng `orders` (Đơn hàng)
```sql
CREATE TABLE orders (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NULL,        -- NULL nếu là Guest
    order_code VARCHAR(50) UNIQUE NOT NULL,
    
    -- Thông tin giao hàng (BẮT BUỘC nhập cho cả Guest và Registered)
    shipping_name VARCHAR(150) NOT NULL,
    shipping_phone VARCHAR(15) NOT NULL,
    shipping_email VARCHAR(255) NULL,
    shipping_address TEXT NOT NULL,
    
    ...
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);
```

**Logic:**
- **Guest:** `user_id = NULL`, nhập tay thông tin mỗi lần đặt
- **Registered:** `user_id = ID`, có thể fill sẵn từ `users` và `user_addresses`

---

### Cách xử lý trong code (Laravel):

```php
// CheckoutController.php

public function checkout(Request $request)
{
    // Kiểm tra đăng nhập
    if (Auth::check()) {
        // Registered Customer
        $cart = Cart::where('user_id', Auth::id())->get();
        $user = Auth::user();
        
        // Fill sẵn thông tin
        $defaultAddress = $user->addresses()->where('is_default', true)->first();
        
    } else {
        // Guest Customer
        $sessionId = session()->getId();
        $cart = Cart::where('session_id', $sessionId)->get();
        
        // Yêu cầu nhập thông tin giao hàng
    }
}

public function placeOrder(Request $request)
{
    $order = Order::create([
        'user_id' => Auth::check() ? Auth::id() : null,  // NULL nếu Guest
        'order_code' => $this->generateOrderCode(),
        'shipping_name' => $request->name,
        'shipping_phone' => $request->phone,
        'shipping_email' => $request->email ?? (Auth::check() ? Auth::user()->email : null),
        // ...
    ]);
}
```

---

### 🎯 KẾT LUẬN - CUSTOMER:

**👉 KHÔNG CẦN tách thành 2 role**

**1 ROLE duy nhất: `customer`**

**Phân biệt bằng logic:**
- `Auth::check()` = Guest hay Registered
- `orders.user_id IS NULL` = Đơn hàng của Guest
- `cart.session_id IS NOT NULL AND user_id IS NULL` = Giỏ hàng của Guest

**Database hiện tại ĐÃ ĐÚNG** - hỗ trợ cả 2 loại khách hàng!

---

## 📌 PHẦN 3: STAFF (Nhân viên)

### Định nghĩa:

**Staff** là nhân viên cửa hàng, có quyền hạn **hạn chế hơn Admin**, ví dụ:
- ✅ Xem sản phẩm, đơn hàng
- ✅ Cập nhật trạng thái đơn hàng
- ✅ Quản lý bình luận
- ❌ KHÔNG được xóa sản phẩm
- ❌ KHÔNG được quản lý user
- ❌ KHÔNG được xem báo cáo doanh thu

### Database hiện tại:

```sql
-- Bảng roles
INSERT INTO roles (id, name, display_name, description) VALUES
(1, 'admin', 'Quản trị viên', 'Có toàn quyền quản lý hệ thống'),
(2, 'staff', 'Nhân viên', 'Quản lý đơn hàng và sản phẩm'),  -- ✅ Đã có
(3, 'customer', 'Khách hàng', 'Người dùng mua hàng');

-- Role Permissions (Staff có quyền hạn chế)
INSERT INTO role_permissions (role_id, permission_id) VALUES
(2, 1), -- dashboard.view
(2, 2), -- products.view
(2, 3), -- products.create
(2, 4), -- products.edit
(2, 6), -- orders.view
(2, 7), -- orders.edit
(2, 12); -- comments.manage
```

**Database ĐÃ ĐÚNG** - hỗ trợ phân quyền chi tiết cho Staff!

---

## 🎯 KẾT LUẬN CUỐI CÙNG

### HỆ THỐNG CÓ 3 ROLES (3 ACTORS):

| Role ID | Role Name | Display Name | Mô tả | Ghi chú |
|---------|-----------|--------------|-------|---------|
| **1** | `admin` | **Quản trị viên / Chủ cửa hàng** | Toàn quyền quản lý hệ thống | Chủ cửa hàng = Admin toàn quyền |
| **2** | `staff` | **Nhân viên** | Quản lý đơn hàng, sản phẩm (hạn chế) | Quyền hạn được giới hạn bởi `permissions` |
| **3** | `customer` | **Khách hàng** | Mua hàng, đánh giá, bình luận | Bao gồm cả Guest và Registered |

---

### PHÂN BIỆT GUEST vs REGISTERED:

**KHÔNG DÙNG ROLE**, dùng **LOGIC CODE:**

```php
// Kiểm tra loại khách hàng
if (Auth::check()) {
    // Registered Customer (Khách đã đăng nhập)
    $cart = Cart::where('user_id', Auth::id())->get();
} else {
    // Guest Customer (Khách vãng lai)
    $cart = Cart::where('session_id', session()->getId())->get();
}
```

---

### KHÔNG CẦN ROLE "SHIPPER":

**Lý do:**
1. Khách tự đến cửa hàng
2. Nếu giao hàng → Chủ/Staff tự giao
3. Hoặc thuê bên thứ 3 (ngoài hệ thống)

**Nếu cần tracking giao hàng:**
- Không cần role "shipper"
- Có thể thêm trường `delivery_staff_id` vào bảng `orders` (tùy chọn)
- `delivery_staff_id` trỏ đến `users.id` (role = admin hoặc staff)

```sql
-- Nếu cần tracking ai giao hàng (TÙY CHỌN)
ALTER TABLE orders 
ADD COLUMN delivery_staff_id BIGINT UNSIGNED NULL COMMENT 'Nhân viên giao hàng',
ADD FOREIGN KEY (delivery_staff_id) REFERENCES users(id) ON DELETE SET NULL;
```

---

## ✅ DATABASE HIỆN TẠI CỦA BẠN LÀ HOÀN HẢO!

### Không cần sửa gì cả:

1. ✅ Đã có 3 roles: `admin`, `staff`, `customer`
2. ✅ Bảng `cart` hỗ trợ cả Guest (session_id) và Registered (user_id)
3. ✅ Bảng `orders` hỗ trợ cả Guest (user_id = NULL) và Registered
4. ✅ Bảng `permissions` và `role_permissions` hỗ trợ phân quyền chi tiết
5. ✅ Bảng `user_addresses` cho Registered Customer lưu địa chỉ

### Chỉ cần LƯU Ý khi code:

1. **Admin = Chủ cửa hàng**
   - User đầu tiên có `role_id = 1` là chủ cửa hàng
   - Hoặc thêm trường `is_owner` nếu cần phân biệt rõ

2. **Guest Checkout**
   - Cho phép đặt hàng mà không cần đăng nhập
   - `orders.user_id = NULL`
   - Tracking bằng `order_code` + email/phone

3. **Staff có quyền hạn chế**
   - Check permissions trước khi cho phép hành động
   - Middleware: `can:products.delete` → chỉ admin được xóa

---

## 🎯 TÓM TẮT NHANH:

| Vấn đề | Khuyến nghị | Lý do |
|--------|-------------|-------|
| **Admin vs Owner** | ✅ **CHUNG 1 ROLE "admin"** | Cửa hàng nhỏ, chủ = admin toàn quyền |
| **Guest vs Registered** | ✅ **CHUNG 1 ROLE "customer"** | Phân biệt bằng logic code, không phải role |
| **Shipper** | ❌ **KHÔNG CẦN** | Chủ/Staff tự giao hoặc thuê bên thứ 3 |
| **Database** | ✅ **ĐÃ HOÀN HẢO** | Không cần sửa gì! |

---

**HẾT** ✅
