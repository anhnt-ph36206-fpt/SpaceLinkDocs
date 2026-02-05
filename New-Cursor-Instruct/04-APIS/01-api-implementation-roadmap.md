## 01 - Roadmap triển khai API

### 1. Chuẩn chung cho API

- Tuân theo docs:
  - `API_DOCUMENTATION.md`
  - `SRS-BE/03-api-documentation.md`
- Thống nhất:
  - **Base URL** (ví dụ `/api/v1`).
  - **Format JSON** (data, meta, errors).
  - **Status code** chuẩn (2xx, 4xx, 5xx).

### 2. Thứ tự build API (từ dễ đến khó)

1. **Public APIs**
   - GET `/categories`
   - GET `/products` (list + filter + pagination)
   - GET `/products/{id}`
2. **Auth-required APIs (user bình thường)**
   - GET `/me`
   - GET `/orders` (của user hiện tại)
   - GET `/orders/{id}`
3. **Cart APIs**
   - POST `/cart/items`
   - PATCH `/cart/items/{id}`
   - DELETE `/cart/items/{id}`
   - GET `/cart`
4. **Order APIs**
   - POST `/orders` (tạo đơn từ cart)
   - PATCH `/orders/{id}/cancel`
5. **Admin APIs**
   - CRUD Products/Categories/Brands.
   - Quản lý Orders (đổi trạng thái, xem chi tiết).

### 3. Checklist cho mỗi nhóm API

- [ ] Định nghĩa route trong `routes/api.php`.
- [ ] Viết controller methods tương ứng.
- [ ] Áp middleware phù hợp (public / auth / admin).
- [ ] Viết Resource/Transformers (nếu dùng) cho response.
- [ ] Test bằng Postman/Thunder Client.

