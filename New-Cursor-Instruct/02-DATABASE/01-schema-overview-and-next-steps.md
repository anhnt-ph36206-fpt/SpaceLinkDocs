## 01 - Database: tổng quan & bước tiếp theo

### 1. Canonical schema

- Chọn **01 file SQL chuẩn** làm “source of truth”, ví dụ:
  - `Databases/SQL-Files/spacelink_database.sql`
- Các file khác (`database_schema.sql`, `new-claude-sl_db.sql`) chỉ dùng để so sánh/tham khảo.

### 2. Mục tiêu khi đưa vào project `SpaceLink-API`

- **Không import file SQL trực tiếp** vào MySQL của project mới (tránh lệ thuộc, khó migrate).
- Thay vào đó:
  - Viết **migrations Laravel** để định nghĩa lại 46 bảng.
  - Viết **seeders** để tạo dữ liệu mẫu quan trọng (users admin, categories, brands, vài products).

### 3. Thứ tự làm việc đề xuất

1. **Phase 1 – Bảng core**
   - users, roles, permissions (hoặc bảng role_user), personal_access_tokens.
   - categories, brands, products, product_images.
2. **Phase 2 – Bảng giao dịch**
   - carts, cart_items, orders, order_items, payments.
3. **Phase 3 – Bảng bổ trợ**
   - vouchers/coupons, addresses, reviews, logs…

### 4. Checklist thao tác cụ thể

- [ ] Đọc lại `01_database_and_migrations.md` để nắm logic từng nhóm bảng.
- [ ] So sánh nhanh schema trong docs với `spacelink_database.sql`.
- [ ] Tạo migrations Phase 1 trên project `SpaceLink-API`.
- [ ] Tạo seeders cho:
  - [ ] User admin mặc định.
  - [ ] 3–5 categories mẫu.
  - [ ] Vài brands + products + product_images.
- [ ] Chạy thử `migrate` + `db:seed` trên local, verify bằng phpMyAdmin/MySQL client.

### 5. Gợi ý mapping sang Eloquent

- Mỗi bảng chính → 1 model Eloquent (Users, Category, Product, Order, OrderItem…).
- Đặt quan hệ:
  - 1-n: Category – Products, Order – OrderItems.
  - n-n: Product – Tags (nếu có bảng trung gian).
- Đặt `foreign keys` trong migrations giống y schema SQL gốc (nếu có).

