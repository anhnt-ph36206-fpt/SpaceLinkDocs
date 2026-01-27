# BÁO CÁO PHÂN TÍCH: SRS MVP vs SHEETS vs DATABASE

**Ngày phân tích:** 2026-01-21  
**Phân tích bởi:** Antigravity AI Assistant

---

## 📋 TÓM TẮT EXECUTIVE

### Kết luận chung:
✅ **Database (27 bảng) ĐÃ ĐẦY ĐỦ** cho các yêu cầu MVP trong SRS  
⚠️ **Google Sheets có NHIỀU chức năng VƯỢT QUANH MVP** - cần lọc lại  
⚠️ **SRS thiếu một số Actor quan trọng** đã được đề cập trong Sheets

---

## 🎯 PHẦN 1: PHÂN TÍCH ACTORS

### ⭐ YÊU CẦU THỰC TẾ CỦA KHÁCH HÀNG:

**HỆ THỐNG CÓ 3 ACTORS CHÍNH:**

1. **Admin/Owner (Chủ cửa hàng)** - CHUNG 1 ROLE
   - Khi bàn giao website → Chủ cửa hàng chính là Admin toàn quyền
   - Thực hiện tất cả các chức năng quản trị (CRUD sản phẩm, đơn hàng, thống kê, v.v.)

2. **Customer (Khách hàng)** - CHUNG 1 ROLE
   - Bao gồm cả **Khách vãng lai** (Guest - không cần đăng nhập) và **Khách đã đăng ký** (Registered)
   - Phân biệt bằng logic code (`Auth::check()`), KHÔNG phải role riêng

3. **Staff (Nhân viên cửa hàng)**
   - Có quyền hạn hạn chế hơn Admin
   - Hỗ trợ quản lý đơn hàng, sản phẩm
   - Có thể đóng vai trò giao hàng (nếu cần)

### ❌ KHÔNG CẦN ROLE "SHIPPER":

**Lý do:**
1. Khách hàng tự đến cửa hàng mua
2. Nếu cần giao hàng → Chủ shop hoặc nhân viên (Staff) tự giao
3. Hoặc thuê bên shipper thứ 3 bên ngoài (không quản lý trong hệ thống)

### So sánh Actors giữa SRS và Sheets (ĐÃ CẬP NHẬT):

| Actor | SRS MVP | Sheets | Database Support | Trạng thái |
|-------|---------|--------|------------------|------------|
| **Customer (Khách hàng)** | ✅ Có | ✅ Có | ✅ `users` (role_id=3) | ✅ ĐẦY ĐỦ |
| **Admin/Owner (Chủ cửa hàng)** | ✅ Có | ✅ Có | ✅ `users` (role_id=1) + `permissions` | ✅ ĐẦY ĐỦ - CHUNG 1 ROLE |
| **Staff (Nhân viên)** | ⚠️ Tùy chọn | ✅ Có #34 | ✅ `users` (role_id=2) + `permissions` | ✅ ĐẦY ĐỦ |
| **Shipper** | ⚠️ Có trong SRS | ❌ KHÔNG | ❌ KHÔNG CẦN | ❌ LOẠI BỎ |

### 💡 KHUYẾN NGHỊ - ACTORS:
- ✅ **GIỮ NGUYÊN Database** - 3 roles (admin, staff, customer) là HOÀN HẢO
- ✅ **Admin/Owner CHUNG 1 ROLE** - phù hợp với cửa hàng tư nhân nhỏ
- ✅ **Customer CHUNG 1 ROLE** - phân biệt Guest/Registered bằng logic code
- ❌ **LOẠI BỎ Shipper** khỏi SRS - không cần thiết cho MVP
- ⚠️ **CẬP NHẬT SRS**: Nâng Staff từ "tùy chọn" lên "chuẩn MVP"

---

## 🎯 PHẦN 2: PHÂN TÍCH CHỨC NĂNG CHO CUSTOMER

### Chức năng KHÁCH HÀNG theo SRS MVP:

| # | Chức năng SRS | Sheets | Database | Trạng thái |
|---|---------------|--------|----------|------------|
| **3.1.1** | Quản lý tài khoản | ✅ #1, #2, #4 | ✅ `users`, `password_reset_tokens` | ✅ ĐẦY ĐỦ |
| **3.1.2** | Xem & tìm kiếm sản phẩm | ✅ #5, #6, #9 | ✅ `products`, `categories`, `brands` | ✅ ĐẦY ĐỦ |
| **3.1.3** | Giỏ hàng | ✅ #12 | ✅ `cart` | ✅ ĐẦY ĐỦ |
| **3.1.4** | Đặt hàng | ✅ #13 | ✅ `orders`, `order_items`, `payment_transactions` | ✅ ĐẦY ĐỦ |
| **3.1.5** | Theo dõi đơn hàng | ✅ #16, #17 | ✅ `orders`, `order_status_history` | ✅ ĐẦY ĐỦ |

### Chi tiết từng chức năng Customer:

#### ✅ 3.1.1: Quản lý tài khoản
- **Sheets #1**: Đăng nhập hệ thống (Yêu cầu bắt buộc) ✅
- **Sheets #2**: Đăng ký, đăng nhập (Yêu cầu bắt buộc) ✅
- **Sheets #3**: Đăng nhập bằng bên thứ 3 (Facebook, Google) - **Có thể làm** ⚠️
- **Sheets #4**: Quản lý thông tin (Yêu cầu bắt buộc) ✅
- **Database**: `users`, `password_reset_tokens` ✅

**Phân tích:**
- SRS yêu cầu: Đăng ký/Đăng nhập/Quên mật khẩu ✅
- Sheets #3 có **OAuth (Facebook, Google)** - NÂNG CAO, không cần cho MVP
- Database đã đủ

#### ✅ 3.1.2: Xem & tìm kiếm sản phẩm
- **Sheets #5**: Trang chủ (Sản phẩm bán chạy, mới nhất, giảm giá) ✅
- **Sheets #6**: Danh sách sản phẩm (phân trang, lọc, tìm kiếm) ✅
- **Sheets #7**: Danh sách yêu thích - **NÂNG CAO** ⚠️
- **Sheets #9**: Chi tiết sản phẩm ✅
- **Database**: `products`, `categories`, `brands`, `product_images`, `product_variants` ✅

**Phân tích:**
- SRS yêu cầu: Xem danh sách, chi tiết, tìm kiếm, lọc ✅
- Sheets #7 "Danh sách yêu thích" - KHÔNG CÓ TRONG SRS MVP ⚠️
- Database **THIẾU bảng `wishlist`** cho chức năng yêu thích

#### ✅ 3.1.3: Giỏ hàng
- **Sheets #12**: Quản lý giỏ hàng (Yêu cầu bắt buộc) ✅
  - Thêm/xóa sản phẩm ✅
  - Cập nhật số lượng ✅
  - Validate tồn kho ✅
  - Áp dụng voucher ✅
- **Database**: `cart` ✅

**Phân tích:** ✅ Đầy đủ

#### ✅ 3.1.4: Đặt hàng
- **Sheets #13**: Thanh toán (Yêu cầu bắt buộc) ✅
  - Guest Checkout ✅
  - Nhập thông tin giao hàng ✅
  - COD + Online (VNPAY, MOMO) ✅
  - Áp dụng voucher ✅
  - Gửi email thông báo ✅
- **Sheets #14**: Thanh toán không cần đăng nhập - **NÂNG CAO** ⚠️
- **Sheets #15**: Điểm thưởng - **NÂNG CAO** ⚠️
- **Database**: `orders`, `order_items`, `payment_transactions`, `vouchers` ✅

**Phân tích:**
- SRS chỉ yêu cầu COD, nhưng Sheets có Online Payment (VNPAY, MOMO) - **CÓ THỂ GIỮ vì DB đã hỗ trợ**
- Sheets #15 "Điểm thưởng" - Database **THIẾU bảng `loyalty_points`**

#### ✅ 3.1.5: Theo dõi đơn hàng
- **Sheets #16**: Lịch sử đơn hàng (Yêu cầu bắt buộc) ✅
- **Sheets #17**: Chi tiết đơn hàng, hủy đơn (Yêu cầu bắt buộc) ✅
- **Sheets #18**: Hoàn hàng - **NÂNG CAO** ⚠️
- **Sheets #19**: Đánh giá sản phẩm (Yêu cầu bắt buộc) ✅
- **Database**: `orders`, `order_status_history`, `reviews` ✅

**Phân tích:** ✅ Đầy đủ, chỉ có hoàn hàng là nâng cao

#### ✅ Bình luận & Tin tức
- **Sheets #8**: Tin tức, Liên hệ (Yêu cầu bắt buộc) ✅
- **Sheets #10**: Bình luận sản phẩm (Yêu cầu bắt buộc) ✅
- **Sheets #11**: Đánh giá sản phẩm (Yêu cầu bắt buộc) ✅
- **Database**: `news`, `contacts`, `comments`, `comment_reports`, `reviews` ✅

**Phân tích:** ✅ Đầy đủ

---

## 🎯 PHẦN 3: PHÂN TÍCH CHỨC NĂNG CHO ADMIN

### Chức năng ADMIN theo SRS MVP:

| # | Chức năng SRS | Sheets | Database | Trạng thái |
|---|---------------|--------|----------|------------|
| **3.2.1** | Quản lý sản phẩm | ✅ #22, #23 | ✅ `products`, `product_variants` | ✅ ĐẦY ĐỦ |
| **3.2.2** | Quản lý danh mục & thương hiệu | ✅ #21 | ✅ `categories`, `brands` | ✅ ĐẦY ĐỦ |
| **3.2.3** | Quản lý đơn hàng | ✅ #24, #25 | ✅ `orders`, `order_items` | ✅ ĐẦY ĐỦ |
| **3.2.4** | Báo cáo & thống kê | ✅ #20 | ✅ Dữ liệu từ các bảng | ✅ ĐẦY ĐỦ |

### Chi tiết từng chức năng Admin:

#### ✅ 3.2.1: Quản lý sản phẩm
- **Sheets #22**: Quản lý sản phẩm (Yêu cầu bắt buộc) ✅
  - CRUD sản phẩm ✅
  - Upload hình ảnh ✅
  - Soft delete ✅
- **Sheets #23**: Quản lý biến thể (Yêu cầu bắt buộc) ✅
- **Database**: `products`, `product_images`, `product_variants`, `attributes`, `attribute_groups` ✅

**Phân tích:** ✅ Đầy đủ

#### ✅ 3.2.2: Quản lý danh mục & thương hiệu
- **Sheets #21**: Quản lý danh mục (Yêu cầu bắt buộc) ✅
  - CRUD danh mục ✅
  - Ẩn danh mục ✅
  - Không xóa nếu còn sản phẩm ✅
- **Database**: `categories`, `brands` ✅

**Phân tích:** ✅ Đầy đủ (Sheets không tách riêng Thương hiệu nhưng DB có `brands`)

#### ✅ 3.2.3: Quản lý đơn hàng
- **Sheets #24**: Quản lý đơn hàng (Yêu cầu bắt buộc) ✅
  - Xem danh sách đơn ✅
  - Xác nhận/Hủy đơn ✅
  - Phân công shipper ✅
  - Thay đổi trạng thái ✅
- **Sheets #25**: Hoàn hàng - **NÂNG CAO** ⚠️
- **Database**: `orders`, `order_items`, `order_status_history` ✅

**Phân tích:** 
- ✅ Đầy đủ cho MVP
- ⚠️ **THIẾU trường `shipper_id`** trong bảng `orders` để phân công shipper

#### ✅ 3.2.4: Báo cáo & thống kê
- **Sheets #20**: Thống kê (Yêu cầu bắt buộc) ✅
  - Doanh thu theo ngày/tháng ✅
  - Số lượng đơn hàng ✅
  - Top sản phẩm bán chạy ✅
  - Top người mua ✅
- **Database**: Không cần bảng riêng, query từ `orders`, `order_items`, `users` ✅

**Phân tích:** ✅ Đầy đủ

#### ⚠️ Chức năng ADMIN bổ sung trong Sheets:
- **Sheets #26**: Quản lý Voucher (Yêu cầu bắt buộc) ✅ - **SRS KHÔNG ĐỀ CẬP**
- **Sheets #27**: Quản lý Bình luận (Yêu cầu bắt buộc) ✅ - **SRS KHÔNG ĐỀ CẬP**
- **Sheets #28**: Quản lý Banner (Có thể làm) ⚠️ - NÂNG CAO
- **Sheets #29**: Quản lý Tin tức (Có thể làm) ✅ - Database có `news`
- **Sheets #30**: Quản lý Sự kiện (Nâng cao) ⚠️ - Database **THIẾU**
- **Sheets #31**: Quản lý Kho hàng (Có thể làm) ⚠️
- **Sheets #32**: Quản lý Khách hàng ⚠️ - SRS không đề cập
- **Sheets #33**: Quản lý User (Yêu cầu bắt buộc) ✅ - Database có `users`

---

## 🎯 PHẦN 4: PHÂN TÍCH CHỨC NĂNG CHO SHIPPER

### ⚠️ VẤN ĐỀ NGHIÊM TRỌNG:

**SRS định nghĩa rõ Actor "Shipper" (Actor 3) với các chức năng:**
1. Đăng nhập hệ thống
2. Xem đơn được giao
3. Cập nhật trạng thái giao hàng

**Nhưng Google Sheets HOÀN TOÀN THIẾU các chức năng này!**

### 💡 KHUYẾN NGHỊ - SHIPPER:
- ⚠️ **BỔ SUNG vào Sheets**: Thêm chức năng cho Shipper
- ⚠️ **BỔ SUNG vào Database**: Thêm trường `shipper_id` vào bảng `orders`

---

## 🎯 PHẦN 5: PHÂN TÍCH DATABASE

### Database hiện tại: 27 bảng

#### ✅ Các bảng ĐÃ ĐẦY ĐỦ cho SRS MVP:

**PHẦN 1: USERS & AUTH (6 bảng)** ✅
1. `roles` - Vai trò ✅
2. `permissions` - Quyền hạn ✅
3. `role_permissions` - Phân quyền ✅
4. `users` - Người dùng ✅
5. `user_addresses` - Địa chỉ giao hàng ✅
6. `password_reset_tokens` - Reset mật khẩu ✅

**PHẦN 2: PRODUCTS (9 bảng)** ✅
7. `brands` - Thương hiệu ✅
8. `categories` - Danh mục ✅
9. `attribute_groups` - Nhóm thuộc tính ✅
10. `attributes` - Giá trị thuộc tính ✅
11. `products` - Sản phẩm ✅
12. `product_images` - Hình ảnh ✅
13. `product_variants` - Biến thể ✅
14. `product_variant_attributes` - Liên kết biến thể-thuộc tính ✅
15. `product_views` - Lượt xem ✅

**PHẦN 3: ORDERS (6 bảng)** ✅
16. `cart` - Giỏ hàng ✅
17. `orders` - Đơn hàng ✅
18. `order_items` - Chi tiết đơn hàng ✅
19. `order_status_history` - Lịch sử trạng thái ✅
20. `payment_transactions` - Giao dịch thanh toán ✅
21. `vouchers` - Mã giảm giá ✅

**PHẦN 4: REVIEWS & COMMENTS (3 bảng)** ✅
22. `reviews` - Đánh giá sản phẩm ✅
23. `comments` - Bình luận ✅
24. `comment_reports` - Báo cáo bình luận ✅

**PHẦN 5: CONTENT (2 bảng)** ✅
25. `news` - Tin tức ✅
26. `contacts` - Liên hệ ✅

**PHẦN 6: SYSTEM (1 bảng)** ✅
27. `settings` - Cấu hình hệ thống ✅

---

### ⚠️ CÁC VẤN ĐỀ CẦN SỬA TRONG DATABASE:

#### 1. 🔴 THIẾU trường `shipper_id` trong bảng `orders`
**Lý do:** SRS yêu cầu "Phân công shipper" nhưng bảng `orders` không có trường này

**Giải pháp:**
```sql
ALTER TABLE orders 
ADD COLUMN shipper_id BIGINT UNSIGNED NULL COMMENT 'Shipper được phân công',
ADD COLUMN assigned_at TIMESTAMP NULL COMMENT 'Thời gian phân công',
ADD FOREIGN KEY (shipper_id) REFERENCES users(id) ON DELETE SET NULL;
```

#### 2. ⚠️ BỔ SUNG bảng `wishlists` (nếu giữ chức năng Yêu thích)
**Lý do:** Sheets #7 có "Danh sách yêu thích" nhưng DB chưa có

**Nếu GIỮ chức năng này (NÂNG CAO):**
```sql
CREATE TABLE wishlists (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_wishlist (user_id, product_id)
) ENGINE=InnoDB COMMENT='Danh sách yêu thích';
```

**Nếu LOẠI BỎ:** Xóa Sheets #7

#### 3. ⚠️ BỔ SUNG bảng `loyalty_points` (nếu giữ chức năng Điểm thưởng)
**Lý do:** Sheets #15 có "Điểm thưởng" nhưng DB chưa có

**Nếu GIỮ chức năng này (NÂNG CAO):**
```sql
CREATE TABLE loyalty_points (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    points INT DEFAULT 0,
    total_earned INT DEFAULT 0,
    total_spent INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Điểm tích lũy';

CREATE TABLE loyalty_point_history (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    order_id BIGINT UNSIGNED NULL,
    type ENUM('earn', 'spend', 'expire') NOT NULL,
    points INT NOT NULL,
    description VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL
) ENGINE=InnoDB COMMENT='Lịch sử điểm tích lũy';
```

**Nếu LOẠI BỎ:** Xóa Sheets #15

#### 4. ⚠️ BỔ SUNG bảng `banners` (nếu giữ chức năng Banner)
**Lý do:** Sheets #28 có "Quản lý Banner" (Có thể làm) nhưng DB chưa có

**Nếu GIỮ chức năng này:**
```sql
CREATE TABLE banners (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL,
    link VARCHAR(255) NULL,
    position VARCHAR(50) DEFAULT 'home_slider' COMMENT 'home_slider, sidebar, popup,...',
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    start_date DATETIME NULL,
    end_date DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB COMMENT='Banner quảng cáo';
```

**Nếu LOẠI BỎ:** Xóa Sheets #28

#### 5. ⚠️ BỔ SUNG bảng `events` (nếu giữ chức năng Sự kiện)
**Lý do:** Sheets #30 có "Quản lý Sự kiện" (Nâng cao) nhưng DB chưa có

**Nếu GIỮ chức năng này (NÂNG CAO):**
```sql
CREATE TABLE events (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    type ENUM('flash_sale', 'black_friday', 'countdown', 'promotion') NOT NULL,
    description TEXT NULL,
    discount_type ENUM('percent', 'fixed') NULL,
    discount_value DECIMAL(15,2) NULL,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB COMMENT='Sự kiện khuyến mãi';

CREATE TABLE event_products (
    event_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    special_price DECIMAL(15,2) NULL,
    PRIMARY KEY (event_id, product_id),
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Sản phẩm tham gia sự kiện';
```

**Nếu LOẠI BỎ:** Xóa Sheets #30

---

## 🎯 PHẦN 6: TỔNG HỢP & KHUYẾN NGHỊ (CẬP NHẬT)

### 📊 Bảng tổng hợp chức năng:

| Chức năng | SRS MVP | Sheets | Database | Khuyến nghị |
|-----------|---------|--------|----------|-------------|
| Đăng ký/Đăng nhập cơ bản | ✅ Bắt buộc | ✅ #1,#2 | ✅ | **GIỮ** |
| OAuth (Facebook, Google) | ❌ | ⚠️ #3 (Có thể làm) | ❌ | **LOẠI BỎ khỏi MVP** |
| Quản lý thông tin | ✅ Bắt buộc | ✅ #4 | ✅ | **GIỮ** |
| Danh sách sản phẩm | ✅ Bắt buộc | ✅ #5,#6 | ✅ | **GIỮ** |
| Danh sách yêu thích | ❌ | ⚠️ #7 (Nâng cao) | ❌ | **LOẠI BỎ hoặc BỔ SUNG DB** |
| Tin tức & Liên hệ | ⚠️ Không rõ | ✅ #8 | ✅ | **GIỮ** (DB đã có) |
| Chi tiết sản phẩm | ✅ Bắt buộc | ✅ #9 | ✅ | **GIỮ** |
| Bình luận | ⚠️ Không rõ | ✅ #10 | ✅ | **GIỮ** (DB đã có) |
| Đánh giá | ✅ Bắt buộc | ✅ #11,#19 | ✅ | **GIỮ** |
| Giỏ hàng | ✅ Bắt buộc | ✅ #12 | ✅ | **GIỮ** |
| Thanh toán | ✅ Bắt buộc | ✅ #13 | ✅ | **GIỮ** |
| Guest Checkout | ⚠️ Có | ⚠️ #14 (Nâng cao) | ✅ | **GIỮ** (DB đã hỗ trợ) |
| Điểm thưởng | ❌ | ⚠️ #15 (Nâng cao) | ❌ | **LOẠI BỎ hoặc BỔ SUNG DB** |
| Lịch sử đơn hàng | ✅ Bắt buộc | ✅ #16,#17 | ✅ | **GIỮ** |
| Hoàn hàng | ❌ | ⚠️ #18,#25 (Nâng cao) | ⚠️ | **LOẠI BỎ khỏi MVP** |
| Thống kê | ✅ Bắt buộc | ✅ #20 | ✅ | **GIỮ** |
| Quản lý Danh mục | ✅ Bắt buộc | ✅ #21 | ✅ | **GIỮ** |
| Quản lý Sản phẩm | ✅ Bắt buộc | ✅ #22,#23 | ✅ | **GIỮ** |
| Quản lý Đơn hàng | ✅ Bắt buộc | ✅ #24 | ✅ | **GIỮ** |
| Quản lý Voucher | ❌ | ✅ #26 | ✅ | **GIỮ** (DB đã có) |
| Quản lý Bình luận | ❌ | ✅ #27 | ✅ | **GIỮ** (DB đã có) |
| Quản lý Banner | ❌ | ⚠️ #28 (Có thể làm) | ❌ | **LOẠI BỎ hoặc BỔ SUNG DB** |
| Quản lý Tin tức | ❌ | ⚠️ #29 (Có thể làm) | ✅ | **GIỮ** (DB đã có) |
| Quản lý Sự kiện | ❌ | ⚠️ #30 (Nâng cao) | ❌ | **LOẠI BỎ khỏi MVP** |
| Quản lý Kho hàng | ❌ | ⚠️ #31 (Có thể làm) | ⚠️ | **LOẠI BỎ khỏi MVP** |
| Quản lý Khách hàng | ❌ | #32 | ✅ | **GIỮ** (có thể coi là quản lý User) |
| Quản lý User | ⚠️ Không rõ | ✅ #33 | ✅ | **GIỮ** |
| Phân quyền | ⚠️ Có | ✅ #34 | ✅ | **GIỮ** |
| Thông báo | ❌ | ⚠️ #35 (Nâng cao) | ❌ | **LOẠI BỎ khỏi MVP** |
| Chat real-time | ❌ | ⚠️ #36 (Nâng cao) | ❌ | **LOẠI BỎ khỏi MVP** |

---

### ✅ CÁC VẤN ĐỀ ĐÃ ĐƯỢC GIẢI QUYẾT:

#### 1. ✅ **Roles và Actors đã RÕ RÀNG**
- Hệ thống có 3 roles: `admin` (chủ cửa hàng), `staff` (nhân viên), `customer` (khách hàng)
- Admin/Owner CHUNG 1 ROLE - phù hợp cửa hàng tư nhân nhỏ
- Customer CHUNG 1 ROLE - phân biệt Guest/Registered bằng logic code
- KHÔNG CẦN role Shipper

#### 2. ✅ **Database ĐÃ HOÀN HẢO**
- 27 bảng đầy đủ cho MVP
- Hỗ trợ cả Guest Checkout (user_id = NULL)
- Hỗ trợ phân quyền chi tiết (permissions)
- KHÔNG CẦN SỬA GÌ CẢ!

---

### ⚠️ CÁC VẤN ĐỀ CÒN LẠI (TÙY CHỌN):

#### 1. **SHEETS có NHIỀU chức năng NÂNG CAO không cần cho MVP**
- OAuth (#3)
- Danh sách yêu thích (#7)
- Điểm thưởng (#15)
- Hoàn hàng (#18, #25)
- Banner (#28)
- Sự kiện (#30)
- Kho hàng (#31)
- Thông báo (#35)
- Chat (#36)
- **Hành động:** LOẠI BỎ hoặc đánh dấu rõ "KHÔNG CẦN CHO MVP"

#### 2. **SRS cần CẬP NHẬT**
- Loại bỏ Actor "Shipper"
- Nâng Staff từ "tùy chọn" lên "chuẩn MVP"
- Bổ sung rõ: Tin tức, Bình luận, Voucher (đã có trong DB)
- Làm rõ: Admin/Owner là 1 role duy nhất

---

### 💡 KHUYẾN NGHỊ CUỐI CÙNG:

#### ✅ **GIỮ NGUYÊN (Đã OK):**
- ✅ Database 27 bảng - HOÀN HẢO cho MVP
- ✅ 3 roles: admin, staff, customer
- ✅ Hỗ trợ Guest Checkout
- ✅ Các chức năng Customer cơ bản (#1,#2,#4,#5,#6,#9,#10,#11,#12,#13,#16,#17,#19)
- ✅ Các chức năng Admin cơ bản (#20,#21,#22,#23,#24,#26,#27,#33)
- ✅ Tin tức, Liên hệ, Bình luận, Voucher (DB đã có)

#### ⚠️ **KHÔNG CẦN SỬA DATABASE** - ĐÃ HOÀN HẢO!

#### ⚠️ **LOẠI BỎ khỏi Google Sheets (hoặc đánh dấu "KHÔNG LÀM CHO MVP"):**
1. Sheets #3: OAuth (Facebook, Google)
2. Sheets #7: Danh sách yêu thích
3. Sheets #15: Điểm thưởng
4. Sheets #18, #25: Hoàn hàng
5. Sheets #28: Quản lý Banner
6. Sheets #30: Quản lý Sự kiện
7. Sheets #31: Quản lý Kho hàng
8. Sheets #35: Thông báo
9. Sheets #36: Chat real-time

#### ⚠️ **CẬP NHẬT SRS:**
1. ❌ Loại bỏ Actor "Shipper"
2. ✅ Làm rõ: Admin/Owner là 1 role duy nhất
3. ✅ Nâng Staff lên "chuẩn MVP"
4. ✅ Bổ sung rõ: Tin tức, Bình luận, Voucher
5. ✅ Làm rõ: Customer bao gồm cả Guest và Registered

---

## 🎯 KẾT LUẬN:

### ✅ Điểm mạnh:
- **Database được thiết kế XUẤT SẮC** - 27 bảng hoàn hảo cho MVP
- **3 Roles rõ ràng** - admin, staff, customer
- **Hỗ trợ Guest Checkout** - không bắt buộc đăng nhập
- **Phân quyền chi tiết** - permissions table
- Các chức năng cơ bản đã được phân tích chi tiết trong Sheets

### ⚠️ Điểm cần cải thiện:
1. **Sheets có quá nhiều chức năng nâng cao** không cần cho MVP (cần lọc)
2. **SRS cần cập nhật** để phản ánh đúng 3 actors và loại bỏ Shipper

### 📝 Hành động tiếp theo:
1. ✅ **GIỮ NGUYÊN Database** - KHÔNG CẦN SỬA GÌ!
2. ⚠️ **LỌC lại Sheets**: Đánh dấu rõ chức năng nào là MVP, nào là NÂNG CAO
3. ⚠️ **CẬP NHẬT SRS**: Loại bỏ Shipper, làm rõ 3 actors, bổ sung chức năng đã có trong DB

---

**Tổng kết:** Database của bạn **ĐÃ HOÀN HẢO** - KHÔNG CẦN SỬA GÌ! Chỉ cần lọc lại Sheets để tập trung vào MVP và cập nhật lại SRS cho rõ ràng!

---

## 📄 TÀI LIỆU THAM KHẢO THÊM:

Xem file chi tiết về Roles và Actors tại:
**`D:\WebServers\laragon6\www\SpaceLinkDocs\documents\PHAN_TICH_ROLES_VA_ACTORS.md`**

File này giải thích rất chi tiết:
- Tại sao Admin/Owner nên CHUNG 1 ROLE
- Tại sao Customer nên CHUNG 1 ROLE (Guest vs Registered)
- Tại sao KHÔNG CẦN role Shipper
- Cách xử lý Guest Checkout trong code
- Cách phân quyền cho Staff

### 📊 Bảng tổng hợp chức năng:

| Chức năng | SRS MVP | Sheets | Database | Khuyến nghị |
|-----------|---------|--------|----------|-------------|
| Đăng ký/Đăng nhập cơ bản | ✅ Bắt buộc | ✅ #1,#2 | ✅ | **GIỮ** |
| OAuth (Facebook, Google) | ❌ | ⚠️ #3 (Có thể làm) | ❌ | **LOẠI BỎ khỏi MVP** |
| Quản lý thông tin | ✅ Bắt buộc | ✅ #4 | ✅ | **GIỮ** |
| Danh sách sản phẩm | ✅ Bắt buộc | ✅ #5,#6 | ✅ | **GIỮ** |
| Danh sách yêu thích | ❌ | ⚠️ #7 (Nâng cao) | ❌ | **LOẠI BỎ hoặc BỔ SUNG DB** |
| Tin tức & Liên hệ | ⚠️ Không rõ | ✅ #8 | ✅ | **GIỮ** (DB đã có) |
| Chi tiết sản phẩm | ✅ Bắt buộc | ✅ #9 | ✅ | **GIỮ** |
| Bình luận | ⚠️ Không rõ | ✅ #10 | ✅ | **GIỮ** (DB đã có) |
| Đánh giá | ✅ Bắt buộc | ✅ #11,#19 | ✅ | **GIỮ** |
| Giỏ hàng | ✅ Bắt buộc | ✅ #12 | ✅ | **GIỮ** |
| Thanh toán | ✅ Bắt buộc | ✅ #13 | ✅ | **GIỮ** |
| Guest Checkout | ⚠️ Có | ⚠️ #14 (Nâng cao) | ✅ | **GIỮ** (DB đã hỗ trợ) |
| Điểm thưởng | ❌ | ⚠️ #15 (Nâng cao) | ❌ | **LOẠI BỎ hoặc BỔ SUNG DB** |
| Lịch sử đơn hàng | ✅ Bắt buộc | ✅ #16,#17 | ✅ | **GIỮ** |
| Hoàn hàng | ❌ | ⚠️ #18,#25 (Nâng cao) | ⚠️ | **LOẠI BỎ khỏi MVP** |
| Thống kê | ✅ Bắt buộc | ✅ #20 | ✅ | **GIỮ** |
| Quản lý Danh mục | ✅ Bắt buộc | ✅ #21 | ✅ | **GIỮ** |
| Quản lý Sản phẩm | ✅ Bắt buộc | ✅ #22,#23 | ✅ | **GIỮ** |
| Quản lý Đơn hàng | ✅ Bắt buộc | ✅ #24 | ✅ | **GIỮ** |
| Quản lý Voucher | ❌ | ✅ #26 | ✅ | **GIỮ** (DB đã có) |
| Quản lý Bình luận | ❌ | ✅ #27 | ✅ | **GIỮ** (DB đã có) |
| Quản lý Banner | ❌ | ⚠️ #28 (Có thể làm) | ❌ | **LOẠI BỎ hoặc BỔ SUNG DB** |
| Quản lý Tin tức | ❌ | ⚠️ #29 (Có thể làm) | ✅ | **GIỮ** (DB đã có) |
| Quản lý Sự kiện | ❌ | ⚠️ #30 (Nâng cao) | ❌ | **LOẠI BỎ khỏi MVP** |
| Quản lý Kho hàng | ❌ | ⚠️ #31 (Có thể làm) | ⚠️ | **LOẠI BỎ khỏi MVP** |
| Quản lý Khách hàng | ❌ | #32 | ✅ | **GIỮ** (có thể coi là quản lý User) |
| Quản lý User | ⚠️ Không rõ | ✅ #33 | ✅ | **GIỮ** |
| Phân quyền | ⚠️ Có | ✅ #34 | ✅ | **GIỮ** |
| Thông báo | ❌ | ⚠️ #35 (Nâng cao) | ❌ | **LOẠI BỎ khỏi MVP** |
| Chat real-time | ❌ | ⚠️ #36 (Nâng cao) | ❌ | **LOẠI BỎ khỏi MVP** |
| **SHIPPER** | ✅ **CÓ** | ❌ **THIẾU** | ⚠️ **THIẾU trường** | **⚠️ BỔ SUNG** |

---

### 🔴 CÁC VẤN ĐỀ NGHIÊM TRỌNG:

#### 1. **THIẾU CHỨC NĂNG SHIPPER trong Google Sheets**
- SRS định nghĩa rõ Actor "Shipper" (Actor 3)
- Sheets HOÀN TOÀN THIẾU chức năng cho Shipper
- **Hành động:** BỔ SUNG vào Sheets

#### 2. **THIẾU trường `shipper_id` trong Database**
- SRS yêu cầu "Phân công shipper"
- Database thiếu trường này
- **Hành động:** SỬA database

#### 3. **SHEETS có NHIỀU chức năng NÂNG CAO không cần cho MVP**
- OAuth (#3)
- Danh sách yêu thích (#7)
- Điểm thưởng (#15)
- Hoàn hàng (#18, #25)
- Banner (#28)
- Sự kiện (#30)
- Kho hàng (#31)
- Thông báo (#35)
- Chat (#36)
- **Hành động:** LOẠI BỎ hoặc đánh dấu rõ "KHÔNG CẦN CHO MVP"

---

### 💡 KHUYẾN NGHỊ CUỐI CÙNG:

#### ✅ **GIỮ NGUYÊN (Đã OK):**
- Database 27 bảng cơ bản
- Các chức năng Customer cơ bản (#1,#2,#4,#5,#6,#9,#10,#11,#12,#13,#16,#17,#19)
- Các chức năng Admin cơ bản (#20,#21,#22,#23,#24,#26,#27,#33)
- Tin tức, Liên hệ, Bình luận, Voucher (DB đã có)

#### ⚠️ **SỬA DATABASE (BẮT BUỘC):**
1. ✅ **THÊM trường `shipper_id`** vào bảng `orders`
2. ✅ **THÊM trường `assigned_at`** vào bảng `orders`

#### ⚠️ **BỔ SUNG GOOGLE SHEETS (BẮT BUỘC):**
1. ✅ **THÊM chức năng cho Shipper:**
   - Đăng nhập
   - Xem danh sách đơn được giao
   - Cập nhật trạng thái giao hàng (Đang giao → Đã giao thành công/thất bại)

#### ⚠️ **LOẠI BỎ khỏi Google Sheets (hoặc đánh dấu "KHÔNG LÀM CHO MVP"):**
1. Sheets #3: OAuth (Facebook, Google)
2. Sheets #7: Danh sách yêu thích
3. Sheets #14: Guest Checkout (có thể giữ vì DB đã hỗ trợ)
4. Sheets #15: Điểm thưởng
5. Sheets #18, #25: Hoàn hàng
6. Sheets #28: Quản lý Banner
7. Sheets #30: Quản lý Sự kiện
8. Sheets #31: Quản lý Kho hàng
9. Sheets #35: Thông báo
10. Sheets #36: Chat real-time

#### ⚠️ **CẬP NHẬT SRS (TÙY CHỌN):**
1. Bổ sung rõ ràng các chức năng:
   - Tin tức
   - Bình luận sản phẩm
   - Quản lý Voucher
   - Quản lý Bình luận
2. Nâng Staff từ "tùy chọn" lên "chuẩn MVP" nếu cần

---

## 🎯 KẾT LUẬN:

### ✅ Điểm mạnh:
- **Database được thiết kế RẤT TỐT** cho MVP
- Các chức năng cơ bản đã được phân tích chi tiết trong Sheets
- SRS có định nghĩa rõ ràng các Actor và luồng chính

### ⚠️ Điểm cần cải thiện:
1. **THIẾU chức năng Shipper trong Sheets** (nghiêm trọng)
2. **THIẾU trường `shipper_id` trong DB** (cần sửa ngay)
3. **Sheets có quá nhiều chức năng nâng cao** không cần cho MVP (cần lọc)
4. **SRS cần bổ sung** một số chức năng đã có trong DB (Tin tức, Bình luận, Voucher)

### 📝 Hành động tiếp theo:
1. ✅ **BỔ SUNG chức năng Shipper** vào Google Sheets
2. ✅ **SỬA database**: Thêm `shipper_id` vào bảng `orders`
3. ⚠️ **LỌC lại Sheets**: Đánh dấu rõ chức năng nào là MVP, nào là NÂNG CAO
4. ⚠️ **CẬP NHẬT SRS**: Bổ sung các chức năng đã có trong DB nhưng chưa ghi rõ trong SRS

---

**Tổng kết:** Database của bạn **ĐÃ RẤT TỐT** cho MVP. Chỉ cần sửa nhỏ thêm trường `shipper_id` và bổ sung chức năng Shipper vào Sheets là OK!
