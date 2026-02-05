## 06 - Đánh giá 27 bảng core: đã đủ cho web bán hàng cơ bản chưa?

### 1. Nhóm bảng bắt buộc cho web bán hàng cơ bản

Dựa trên file `spacelink_database.sql` và các migrations trong `sl-api-test`, ta có thể gom **~27 bảng core** thành các nhóm logic chính:

1. **Users & Auth (tối thiểu)**
   - `users`
   - `user_addresses`
   - (tùy chọn: `roles`, `user_roles`, `password_reset_tokens`)
2. **Catalog (Sản phẩm & danh mục)**
   - `brands`
   - `categories`
   - `products`
   - `product_images`
   - (tùy chọn nâng cao: `attribute_groups`, `attributes`, `product_variants`, `product_variant_attributes`, `product_views`)
3. **Cart & Orders**
   - `cart`
   - `orders`
   - `order_items`
   - `order_status_history`
4. **Thanh toán & ví (tối thiểu)**
   - `payment_transactions`
   - (tùy chọn: `wallet_transactions`)
5. **Marketing cơ bản**
   - `vouchers`
   - `user_vouchers`
6. **User experience bổ sung**
   - `wishlists`
   - `reviews`
   - `comments`

### 2. Những luồng bắt buộc và bảng tương ứng

**Luồng 1: Duyệt sản phẩm**

- Bảng liên quan:
  - `categories` → danh mục nhiều cấp.
  - `brands` → thương hiệu.
  - `products` → thông tin sản phẩm, giá, số lượng.
  - `product_images` → ảnh sản phẩm.
- Đảm bảo logic:
  - 1 category → nhiều products (1-n).
  - 1 brand → nhiều products (1-n).
  - 1 product → nhiều product_images (1-n).

**Luồng 2: Đăng ký / đăng nhập / địa chỉ giao hàng**

- Bảng liên quan:
  - `users` → thông tin tài khoản.
  - `user_addresses` → nhiều địa chỉ nhận hàng cho 1 user.
  - `password_reset_tokens` → reset mật khẩu.
- Đảm bảo logic:
  - 1 user → nhiều addresses (1-n).

**Luồng 3: Thêm vào giỏ / đặt hàng**

- Bảng liên quan:
  - `cart` → chứa dòng sản phẩm user đang chọn.
  - `orders` → đơn hàng sau khi checkout.
  - `order_items` → các dòng chi tiết trong đơn.
  - `order_status_history` → lịch sử trạng thái đơn.
- Đảm bảo logic:
  - 1 user → nhiều cart items, nhiều orders (1-n).
  - 1 order → nhiều order_items (1-n).

**Luồng 4: Thanh toán**

- Bảng liên quan:
  - `payment_transactions` → log chi tiết từng giao dịch (COD/vnpay/momo…).
  - (tùy chọn: `wallet_transactions` nếu dùng ví nội bộ).

**Luồng 5: Voucher / Giảm giá (nếu coi là bắt buộc)**

- `vouchers`, `user_vouchers`:
  - Gắn voucher vào đơn hàng hoặc lịch sử sử dụng voucher.

**Luồng 6: Wishlist, Reviews, Comments (có thể coi là nâng cao, nhưng rất phổ biến)**

- `wishlists` → danh sách yêu thích.
- `reviews` → đánh giá sau mua (gắn với `order_items`).
- `comments` → bình luận chung trên sản phẩm.

### 3. Đánh giá: schema này đã đủ cho web bán hàng cơ bản chưa?

Với góc nhìn **chức năng bắt buộc của một web bán hàng cơ bản**, nhóm bảng ở trên:

- **ĐÃ ĐỦ** để triển khai:
  - Đăng ký/đăng nhập user.
  - Quản lý danh mục, thương hiệu, sản phẩm, hình ảnh.
  - Thêm sản phẩm vào giỏ, checkout, tạo đơn, lưu chi tiết đơn.
  - Theo dõi trạng thái đơn (pending → completed…).
  - Tích hợp/payment log cơ bản (dù ban đầu có thể chỉ dùng COD).
  - Áp dụng voucher đơn giản (mã giảm giá theo order).
- **DƯ SỨC** cho mức “bán hàng cơ bản”:
  - Vì còn có thêm reviews/comments, wishlists, wallet_transactions… là các tính năng tăng trải nghiệm.

Như vậy, nếu bạn chọn **27 bảng mandatory** chủ yếu xoay quanh các nhóm trên, thì:

- Về **logic database**, đã đủ để bạn yên tâm build 1 web bán hàng cơ bản.  
- Vấn đề còn lại là:
  - Thực hiện đúng quan hệ giữa các bảng.
  - Ánh xạ chính xác sang **Eloquent models & API**.

### 4. Bước tiếp theo trong roadmap

Để bạn dễ nhìn và nhớ hơn, tôi sẽ tạo thêm một file:

- `07-core-relationships-cheatsheet.md`  
  - Liệt kê rõ ràng các quan hệ 1-n, n-n quan trọng (vd: 1 danh mục có nhiều sản phẩm, 1 đơn hàng có nhiều order_items, 1 user có nhiều địa chỉ…) để bạn tra cứu nhanh khi viết models & migrations.

