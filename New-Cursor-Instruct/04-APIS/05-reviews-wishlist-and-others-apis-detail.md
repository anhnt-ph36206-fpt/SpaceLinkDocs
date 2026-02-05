## 05 - API Reviews, Wishlist và các phần bổ sung

> Nhóm này có thể làm **sau** khi Auth + Catalog + Cart/Orders đã chạy ổn.

---

### 1. Wishlist API

**Mục tiêu**

- Cho phép user:
  - Thêm sản phẩm vào danh sách yêu thích.
  - Xóa khỏi wishlist.
  - Lấy danh sách sản phẩm yêu thích.

**Endpoints**

- `GET /api/wishlist`
- `POST /api/wishlist/{product_id}`
- `DELETE /api/wishlist/{product_id}`

**Logic**

- Bảng bạn có: `wishlists` (nếu muốn có thể thêm), hoặc làm nhanh bằng 1 bảng pivot.
- Khi `POST`:
  - Nếu đã tồn tại (user_id + product_id) → không tạo mới, trả 200.
  - Nếu chưa → tạo mới.
- Khi `DELETE`:
  - Xóa bản ghi ứng với user hiện tại + product_id.

---

### 2. Reviews API

**Mục tiêu**

- Người dùng **đã mua** sản phẩm có thể:
  - Viết review.
  - Xem lại review của mình.
- Public:
  - Ai cũng xem được danh sách reviews của 1 product.

**Endpoints**

- Public:
  - `GET /api/products/{product_id}/reviews`
- User (auth):
  - `POST /api/orders/{order_item_id}/review`
  - `PUT /api/reviews/{id}` (sửa nội dung trước khi được duyệt, tùy policy)

**Logic chính**

- Dựa trên bảng:
  - `reviews` (có `order_item_id` unique).
- `POST /orders/{order_item_id}/review`:
  - Check:
    - `order_item_id` thuộc về `order` của user hiện tại.
    - Trạng thái order (vd: delivered/completed).
    - `reviews` chưa tồn tại cho order_item đó.
  - Sau đó tạo:
    - rating (1–5).
    - content.
    - images (nếu có).
  - Set `is_reviewed = true` trên `order_items`.

---

### 3. Comments API

**Mục tiêu**

- Bình luận chung trên trang chi tiết sản phẩm (không bắt buộc là người đã mua).

**Endpoints**

- Public:
  - `GET /api/products/{product_id}/comments`
- User (auth):
  - `POST /api/products/{product_id}/comments`
  - `POST /api/comments/{id}/reply`

**Logic**

- Bảng `comments`:
  - `parent_id` để support reply.
- `GET`:
  - Lấy comments theo product, có thể chỉ lấy `status = approved`.
- `POST`:
  - Tạo comment với `user_id`, `product_id`, `content`.
  - Admin sau đó duyệt/thay đổi `status`.

---

### 4. Đề xuất thứ tự thực hiện tổng thể API (gộp tất cả)

1. **Auth API** (file `02-auth-apis-detail.md`)
   - Register, login, me, logout.
2. **Catalog public API** (file `03-products-and-categories-apis-detail.md`)
   - Categories, brands, products list/detail.
3. **Cart & Orders** (file `04-cart-and-orders-apis-detail.md`)
   - Cart CRUD, checkout, orders history.
4. **Admin APIs cơ bản**
   - CRUD categories/brands/products, quản lý orders.
5. **Wishlist + Reviews + Comments** (file này)
   - Tăng trải nghiệm người dùng sau khi core flow đã ổn.

Bạn có thể đánh dấu hoàn thành từng nhóm trong các file tương ứng, rồi quay lại roadmap ở `01-FOUNDATION/03-learning-path-roadmap.md` để đảm bảo đi đúng thứ tự. 

