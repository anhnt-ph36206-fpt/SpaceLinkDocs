## 07 - Bảng quan hệ logic (cheatsheet cho 27 bảng core)

### 1. Users & Auth

- `users` ↔ `user_addresses`
  - 1 user **có nhiều** địa chỉ giao hàng.
  - Quan hệ:
    - User `hasMany(UserAddress)`
    - UserAddress `belongsTo(User)`

- `users` ↔ `orders`
  - 1 user **có nhiều** đơn hàng.
  - Quan hệ:
    - User `hasMany(Order)`
    - Order `belongsTo(User)`

- `users` ↔ `cart`
  - 1 user **có nhiều** dòng cart (hoặc dùng `session_id` cho guest).
  - Quan hệ:
    - User `hasMany(CartItem)`
    - CartItem `belongsTo(User)`

---

### 2. Catalog (Danh mục, sản phẩm)

- `categories` (self-reference)
  - 1 category cha **có nhiều** category con.
  - Quan hệ:
    - Category `hasMany(Category, 'parent_id')`
    - Category `belongsTo(Category, 'parent_id')` (parent)

- `categories` ↔ `products`
  - 1 category **có nhiều** products.
  - Quan hệ:
    - Category `hasMany(Product)`
    - Product `belongsTo(Category)`

- `brands` ↔ `products`
  - 1 brand **có nhiều** products.
  - Quan hệ:
    - Brand `hasMany(Product)`
    - Product `belongsTo(Brand)`

- `products` ↔ `product_images`
  - 1 product **có nhiều** ảnh.
  - Quan hệ:
    - Product `hasMany(ProductImage)`
    - ProductImage `belongsTo(Product)`

- (Nếu dùng biến thể):
  - `products` ↔ `product_variants`
    - 1 product **có nhiều** biến thể (RAM, màu, dung lượng…).
  - `product_variants` ↔ `product_variant_attributes`
    - Bảng trung gian nối `variant` với `attributes`.

---

### 3. Cart & Orders

- `users` ↔ `cart`
  - Như trên: 1 user nhiều cart items.

- `products` ↔ `cart`
  - 1 product **có thể xuất hiện nhiều lần trong cart của nhiều user**.
  - Quan hệ:
    - Product `hasMany(CartItem)`
    - CartItem `belongsTo(Product)`

- `orders` ↔ `order_items`
  - 1 order **có nhiều** order_items.
  - Quan hệ:
    - Order `hasMany(OrderItem)`
    - OrderItem `belongsTo(Order)`

- `products` ↔ `order_items`
  - 1 product **có thể nằm trong nhiều** order_items (nhiều đơn khác nhau).
  - Quan hệ:
    - Product `hasMany(OrderItem)`
    - OrderItem `belongsTo(Product)`

- `orders` ↔ `order_status_history`
  - 1 order **có nhiều** bản ghi lịch sử trạng thái.
  - Quan hệ:
    - Order `hasMany(OrderStatusHistory)`
    - OrderStatusHistory `belongsTo(Order)`

---

### 4. Thanh toán & ví

- `orders` ↔ `payment_transactions`
  - 1 order **có nhiều** giao dịch thanh toán (retry, refund…).
  - Quan hệ:
    - Order `hasMany(PaymentTransaction)`
    - PaymentTransaction `belongsTo(Order)`

- `users` ↔ `wallet_transactions` (nếu dùng)
  - 1 user **có nhiều** giao dịch ví.
  - Quan hệ:
    - User `hasMany(WalletTransaction)`
    - WalletTransaction `belongsTo(User)`

---

### 5. Voucher & Marketing

- `vouchers` ↔ `user_vouchers`
  - 1 voucher **có thể được nhiều user dùng** (tuỳ usage_limit).
  - Quan hệ:
    - Voucher `hasMany(UserVoucher)`
    - UserVoucher `belongsTo(Voucher)`

- `users` ↔ `user_vouchers`
  - 1 user **có thể dùng nhiều** voucher (trong các đơn khác nhau).
  - Quan hệ:
    - User `hasMany(UserVoucher)`
    - UserVoucher `belongsTo(User)`

- `orders` ↔ `user_vouchers`
  - 1 order có thể gắn với 1 user_voucher record (lưu lịch sử dùng).

---

### 6. Wishlist, Reviews, Comments

- `users` ↔ `wishlists`
  - 1 user **có nhiều** wishlist items.

- `products` ↔ `wishlists`
  - 1 product **có thể nằm trong wishlist của nhiều user**.

→ Thực chất là quan hệ **n-n** giữa users và products, được triển khai qua bảng `wishlists` (pivot ẩn).

- `users` ↔ `reviews`
  - 1 user **có thể viết nhiều** reviews.

- `products` ↔ `reviews`
  - 1 product **có nhiều** reviews.

- `order_items` ↔ `reviews`
  - 1 order_item **có tối đa 1** review (unique theo order_item_id).

- `users` ↔ `comments`
  - 1 user **có nhiều** comments.

- `products` ↔ `comments`
  - 1 product **có nhiều** comments.

- `comments` (self-reference)
  - 1 comment cha **có nhiều** comment con (reply).

---

### 7. Cách dùng cheatsheet này khi code

- Khi viết **migrations**:
  - Tra bảng này để biết bảng nào là **cha**, bảng nào là **con** (để tạo theo đúng thứ tự, set foreign key).
- Khi viết **model Eloquent**:
  - Dựa vào các “1-n” / “n-n” để khai báo `hasMany`, `belongsTo`, `belongsToMany` phù hợp.
- Khi viết **API**:
  - Dùng relationships này để:
    - Eager loading (`with(...)`) tránh N+1.
    - Validate dữ liệu (ví dụ: chỉ cho review nếu order_item thuộc về user đó, v.v.). 

