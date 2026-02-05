## 04 - API Cart & Orders chi tiết

### 0. Chuẩn bị

- Đã có:
  - Bảng: `cart`, `orders`, `order_items`, `order_status_history`, `payment_transactions`, `vouchers`.
  - Models tương ứng trong file 09.
- Phần này **bắt buộc cần auth** cho user (trừ giỏ hàng guest).

---

### 1. Bước 1 – Cart API (user đã đăng nhập)

**Mục tiêu**

- Cho phép user:
  - Thêm sản phẩm vào giỏ.
  - Cập nhật số lượng.
  - Xóa khỏi giỏ.
  - Lấy danh sách giỏ hiện tại.

**Endpoints gợi ý**

- `GET /api/cart` → Lấy giỏ hiện tại.
- `POST /api/cart/items` → Thêm mới (product_id, variant_id?, quantity).
- `PUT /api/cart/items/{id}` → Update quantity.
- `DELETE /api/cart/items/{id}` → Xóa dòng.

**Logic chính**

- `GET /cart`:
  - Lấy tất cả dòng trong `cart` theo `user_id` hiện tại.
  - Join/eager load `product`, `variant` để trả thêm tên + giá.
- `POST /cart/items`:
  - Nếu đã có dòng cùng `user_id + product_id + variant_id` → cộng dồn quantity.
  - Nếu chưa → tạo mới.
- `PUT /cart/items/{id}`:
  - Cập nhật `quantity` (>=1).
- `DELETE /cart/items/{id}`:
  - Xóa dòng tương ứng.

**Middleware**

- Nhóm route:
  - `Route::middleware('auth:sanctum')->group(...)` cho các endpoint cart dùng user_id.

---

### 2. Bước 2 – Checkout & Orders

**Mục tiêu**

- Từ giỏ hàng → tạo đơn (`orders` + `order_items`).

**Endpoint gợi ý**

- `POST /api/checkout`
  - Body:
    - `shipping_address` (có thể chọn từ `user_addresses` hoặc truyền form).
    - `payment_method` (`cod`, `vnpay`, `momo`, …).
    - `voucher_code` (nếu có).

**Logic chính**

1. Lấy tất cả `cart` items của user.
2. Tính:
   - `subtotal` = tổng `price * quantity` (theo giá product/variant hiện tại).
   - Áp dụng voucher (nếu có):
     - Check điều kiện từ bảng `vouchers`.
   - `shipping_fee` (tạm fix cứng/0).
   - `total_amount` = subtotal - discount + shipping_fee.
3. Tạo bản ghi `orders`.
4. Tạo nhiều `order_items` từ giỏ.
5. Xóa cart của user sau khi tạo đơn thành công.

**Response**

- Trả về:
  - Thông tin order.
  - Danh sách order_items.

---

### 3. Bước 3 – Xem lịch sử đơn hàng

**Endpoints**

- `GET /api/orders` → Danh sách đơn của user hiện tại.
- `GET /api/orders/{order_code}` → Chi tiết 1 đơn (items + status history).

**Logic**

- `GET /orders`:
  - Lọc theo `user_id`.
  - Có phân trang.
- `GET /orders/{order_code}`:
  - Tìm theo `order_code` và `user_id`.
  - Trả kèm:
    - `items`
    - `statusHistory`
    - `paymentTransactions` (nếu có).

---

### 4. Bước 4 – API cho admin quản lý orders

**Endpoints gợi ý**

- `GET /api/admin/orders` → List tất cả đơn (filter theo status, date, user email...).
- `GET /api/admin/orders/{id}` → Chi tiết đơn.
- `PATCH /api/admin/orders/{id}/status` → Đổi trạng thái (confirmed, shipping, delivered, cancelled…).

**Logic**

- Khi đổi status:
  - Cập nhật cột `status` trên bảng `orders`.
  - Tạo thêm bản ghi vào `order_status_history`.
  - Có thể cập nhật `cancelled_at`, `confirmed_at`… tùy status.

---

### 5. Bước 5 – Tích hợp Payment (Phase sau)

Ban đầu:

- Bạn có thể chỉ cho phép `payment_method = cod`.
- Sau khi đơn tạo thành công:
  - Ghi `payment_status = 'unpaid'`.

Khi tích hợp cổng thanh toán:

- Tạo thêm endpoint:
  - `GET /api/payment/redirect` (hoặc backend build URL redirect).
  - `GET/POST /api/payment/callback` từ cổng thanh toán.
- Trong callback:
  - Tạo/Update `payment_transactions`.
  - Đổi `payment_status` và `status` đơn tùy kết quả.

---

### 6. Thứ tự triển khai đề xuất

1. Cart APIs (user đã login).
2. Checkout → create order (chỉ COD).
3. History orders (user side).
4. Admin view orders + change status.
5. Sau cùng mới mở rộng phần payment online/phức tạp. 

