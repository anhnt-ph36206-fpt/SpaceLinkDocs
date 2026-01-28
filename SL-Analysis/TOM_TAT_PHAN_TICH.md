# TÓM TẮT PHÂN TÍCH - SRS vs SHEETS vs DATABASE

**Ngày:** 2026-01-21  
**Kết luận:** ✅ **DATABASE ĐÃ HOÀN HẢO - KHÔNG CẦN SỬA GÌ!**

---

## 🎯 HỆ THỐNG CÓ 3 ACTORS (3 ROLES)

### 1. **Admin/Owner (Chủ cửa hàng)** - role_id = 1
- Chủ cửa hàng = Admin toàn quyền
- CHUNG 1 ROLE (không cần tách riêng)
- Toàn quyền quản lý: sản phẩm, đơn hàng, user, thống kê

### 2. **Customer (Khách hàng)** - role_id = 3
- CHUNG 1 ROLE (không cần tách riêng)
- Bao gồm 2 loại (phân biệt bằng logic code):
  - **Guest** (Khách vãng lai) - không cần đăng nhập
  - **Registered** (Khách đã đăng ký) - có tài khoản

### 3. **Staff (Nhân viên)** - role_id = 2
- Quyền hạn hạn chế hơn Admin
- Quản lý đơn hàng, sản phẩm
- Có thể giao hàng (nếu cần)

### ❌ KHÔNG CẦN ROLE "SHIPPER"
**Lý do:**
- Khách tự đến cửa hàng
- Nếu giao → Chủ/Staff tự giao
- Hoặc thuê bên thứ 3 (ngoài hệ thống)

---

## ✅ DATABASE: 27 BẢNG - HOÀN HẢO!

### Không cần sửa gì cả:
- ✅ 3 roles đúng: admin, staff, customer
- ✅ Hỗ trợ Guest Checkout (user_id = NULL)
- ✅ Hỗ trợ phân quyền chi tiết (permissions)
- ✅ Đầy đủ cho MVP

---

## ⚠️ GOOGLE SHEETS - CẦN LỌC LẠI

### Các chức năng CẦN GIỮ cho MVP:
✅ #1,#2 - Đăng nhập, Đăng ký  
✅ #4 - Quản lý thông tin  
✅ #5,#6 - Trang chủ, Danh sách sản phẩm  
✅ #8 - Tin tức, Liên hệ  
✅ #9 - Chi tiết sản phẩm  
✅ #10 - Bình luận  
✅ #11,#19 - Đánh giá  
✅ #12 - Giỏ hàng  
✅ #13 - Thanh toán  
✅ #16,#17 - Lịch sử đơn hàng  
✅ #20 - Thống kê  
✅ #21 - Quản lý Danh mục  
✅ #22,#23 - Quản lý Sản phẩm, Biến thể  
✅ #24 - Quản lý Đơn hàng  
✅ #26 - Quản lý Voucher  
✅ #27 - Quản lý Bình luận  
✅ #33 - Quản lý User  
✅ #34 - Phân quyền  

### Các chức năng LOẠI BỎ (NÂNG CAO):
❌ #3 - OAuth (Facebook, Google)  
❌ #7 - Danh sách yêu thích  
❌ #15 - Điểm thưởng  
❌ #18,#25 - Hoàn hàng  
❌ #28 - Quản lý Banner  
❌ #30 - Quản lý Sự kiện  
❌ #31 - Quản lý Kho hàng  
❌ #35 - Thông báo  
❌ #36 - Chat real-time  

---

## 📝 HÀNH ĐỘNG TIẾP THEO

### 1. ✅ DATABASE
**KHÔNG CẦN SỬA GÌ** - Đã hoàn hảo!

### 2. ⚠️ GOOGLE SHEETS
Đánh dấu rõ:
- **Yêu cầu bắt buộc** (MVP) - 20 chức năng
- **Nâng cao** (không làm) - 9 chức năng

### 3. ⚠️ SRS
Cập nhật:
- ❌ Loại bỏ Actor "Shipper"
- ✅ Làm rõ: Admin/Owner là 1 role
- ✅ Làm rõ: Customer bao gồm Guest và Registered
- ✅ Nâng Staff lên "chuẩn MVP"

---

## 📄 CÁCH XỬ LÝ GUEST vs REGISTERED

### Bảng `cart`:
```sql
user_id = NULL, session_id = "abc123"  -- Guest
user_id = 5, session_id = NULL         -- Registered
```

### Bảng `orders`:
```sql
user_id = NULL  -- Đơn hàng của Guest (không đăng nhập)
user_id = 5     -- Đơn hàng của Registered Customer
```

### Code Laravel:
```php
if (Auth::check()) {
    // Registered Customer
    $cart = Cart::where('user_id', Auth::id())->get();
} else {
    // Guest Customer
    $cart = Cart::where('session_id', session()->getId())->get();
}
```

---

## 🎯 KẾT LUẬN

### ✅ TIN TỐT:
- **Database ĐÃ XUẤT SẮC** - 27 bảng hoàn hảo
- **3 Roles rõ ràng** - admin, staff, customer
- **Hỗ trợ đầy đủ** cho tất cả chức năng MVP

### ⚠️ CẦN LÀM:
1. Lọc lại Sheets (đánh dấu MVP vs Nâng cao)
2. Cập nhật SRS (loại bỏ Shipper, làm rõ actors)

### 📊 THỐNG KÊ:
- **Database:** 27 bảng ✅
- **Roles:** 3 roles ✅
- **Chức năng MVP:** 20 chức năng ✅
- **Chức năng nâng cao:** 9 chức năng ❌

---

**👉 KHÔNG CẦN SỬA DATABASE!** ✅
