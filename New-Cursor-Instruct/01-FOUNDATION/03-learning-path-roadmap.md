## 03 - Lộ trình tổng thể (từ dễ đến khó)

### Giai đoạn 0 – Chuẩn bị (dễ)

1. **Ổn định tech stack**
   - Chấp nhận stack trong `02-tech-stack-and-main-project-choice.md`.
   - Chọn cách triển khai: 1 repo monorepo hay 2 repo (backend `SpaceLink-API`, frontend `SpaceLink-Web`).
2. **Tạo repo/project mới**
   - Tạo project Laravel mới cho backend (`SpaceLink-API`).
   - Tạo project React TS mới cho frontend (`SpaceLink-Web`).
   - Không import code cũ ngay, chỉ setup skeleton + cấu trúc folder chuẩn.

### Giai đoạn 1 – Database (nền tảng)

1. **Review schema 46 bảng**
   - Đọc lại file SQL canonical (chọn 1 file: `spacelink_database.sql`).
   - So sánh nhanh với docs trong `01_database_and_migrations.md`.
2. **Thiết kế migrations mới cho backend `SpaceLink-API`**
   - Viết lại migrations để tái hiện schema 46 bảng (có thể chia phase: core tables trước, optional sau).
   - Tạo seeders cơ bản (users, categories, products, brands…).
3. **Kết nối Laravel với MySQL**
   - Cấu hình `.env`, chạy `migrate` + `db:seed`.

Chi tiết hơn xem `02-DATABASE/01-schema-overview-and-next-steps.md`.

### Giai đoạn 2 – Authentication & Users

1. **Cấu hình Sanctum trên project mới**
   - Làm lại sạch sẽ dựa trên các docs Sanctum đã có.
2. **Model Users, Roles, Permissions**
   - Thiết kế lại models/relations dựa trên docs roles/actors.
3. **API Auth cơ bản**
   - Register, Login, Logout, Me, Refresh (nếu cần).
4. **Bảo vệ routes**
   - Group routes theo guard/middleware: public, authenticated, admin.

Chi tiết hơn xem `03-AUTHENTICATION/01-auth-plan.md`.

### Giai đoạn 3 – Core APIs (Products, Categories, Brands)

1. **Chuẩn hóa chuẩn API**
   - Quy ước URL, status code, response format (theo `API_DOCUMENTATION.md`).
2. **Xây Product/Category/Brand API**
   - CRUD cơ bản, filter, pagination.
3. **Áp dụng policy/permission đơn giản**
   - Ai được tạo/sửa/xóa sản phẩm, ai chỉ được xem.

Chi tiết hơn xem `04-APIS/01-api-implementation-roadmap.md`.

### Giai đoạn 4 – Cart, Orders, Payments (khó hơn)

1. **Thiết kế luồng Order**
   - Từ Add to cart → Checkout → Order created → Payment → Order status flow.
2. **Implement Cart API**
   - Thêm/xóa/sửa item trong cart, tính tổng tiền, ship fee (simple version).
3. **Implement Order API**
   - Tạo đơn, xem lịch sử đơn, đổi trạng thái (admin).
4. **Payment integration (sau)**
   - VNPay/MoMo integration sau khi luồng đơn hàng chạy ổn.

Chi tiết sau này sẽ bổ sung trong `06-BUSINESS-LOGIC/*`.

### Giai đoạn 5 – Frontend React (client)

1. **Skeleton frontend**
   - Layout chính, router, cấu trúc pages/components.
2. **Tích hợp Auth**
   - Login/Register/Profile, lưu token, ProtectedRoute, redirect.
3. **Trang Products**
   - Danh sách sản phẩm, chi tiết sản phẩm, lọc/sort.
4. **Cart & Checkout UI**
   - Cart page, checkout form, call API phù hợp.

Chi tiết xem `05-FRONTEND/01-frontend-roadmap.md`.

### Giai đoạn 6 – Admin panel

1. **Admin dashboard**
   - Tổng quan orders, doanh thu (simple).
2. **Quản lý sản phẩm, đơn hàng, người dùng**
   - Tận dụng API backend đã build, chỉ build UI.
3. **Quyền & phân vai**
   - Chỉ role admin mới vào được admin routes.

### Giai đoạn 7 – Testing, Deployment, Advanced

1. **Testing**
   - Backend: feature tests cho auth, products, orders.
   - Frontend: basic component tests + E2E tối thiểu.
2. **Deployment**
   - Hướng dẫn deploy backend + frontend (VPS/shared hosting…).
3. **Advanced**
   - Caching, performance, security hardening, logging/monitoring.

Các phần chi tiết hơn sẽ được bổ sung dần trong các folder:

- `08-TESTING/`
- `09-DEPLOYMENT/`
- `10-ADVANCED/`

