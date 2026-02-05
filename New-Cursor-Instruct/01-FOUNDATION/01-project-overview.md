## 01 - Tổng hợp hiện trạng project SpaceLink

### 1. Các project code đang có

**1.1. Backend API chính (nên chọn làm base)**

- **Tên**: spacelink-backend-test-2  
- **Path**: `All-Projects/BE-Projects/spacelink-backend-test-2/`  
- **Stack**: Laravel 12, PHP 8.2+, Laravel Sanctum, MySQL  
- **Mục đích**: API backend  
- **Tình trạng** (≈ 40%):
  - Đã có: `AuthController` (register/login/logout/me), `ProductController`, `CategoryController`, routes API cơ bản.
  - Thiếu: Cart, Orders, Admin APIs, Reviews, thống nhất schema với DB 46 bảng.
- **Gợi ý**: Đây là **ứng viên tốt nhất** để sau này bạn build project backend duy nhất `SpaceLink`.

**1.2. Backend admin web (Blade)**

- **Tên**: sl-db-test (backend)  
- **Path**: `All-Projects/Fullstack-Projects/sl-db-test/backend/`  
- **Stack**: Laravel 12 + Blade, MySQL  
- **Mục đích**: Admin panel web (CRUD Brands/Categories/Products)  
- **Tình trạng** (≈ 50%):
  - Đã có: CRUD Brands, Categories, Products qua web routes, view Blade.
  - Thiếu: Orders management, User management, Dashboard, phân quyền rõ ràng.
- **Gợi ý**: Dùng như **tham khảo UI & luồng admin**, sau này nên chuyển admin sang dùng **API chung** của backend chính.

**1.3. Backend API test khác**

- **Tên**: sl-api-test (backend)  
- **Path**: `All-Projects/Fullstack-Projects/sl-api-test/backend/`  
- **Stack**: Laravel 12, Sanctum, MySQL  
- **Mục đích**: Thử nghiệm API  
- **Tình trạng** (≈ 30%):
  - Có: `ProductController`, `CategoryController`, `BrandController` cơ bản.
  - Không rõ: Đã sync bao nhiêu với DB 46 bảng.
- **Gợi ý**: Xem như **sandbox**, chỉ dùng để tham khảo ý tưởng code, không nên là main project.

**1.4. Frontend React**

- **Tên**: spacelink-frontend-test-2  
- **Path**: `All-Projects/FE-Projects/spacelink-frontend-test-2/`  
- **Stack**: React 19, TypeScript, Vite, Ant Design, React Router, Axios  
- **Mục đích**: Frontend client  
- **Tình trạng** (≈ 20%):
  - Đã có: Login, Register, Profile, `ProtectedRoute`, service call API cơ bản.
  - Thiếu: Product listing, product detail, Cart, Checkout, Order tracking, Admin pages.
- **Gợi ý**: Nên chọn làm **base cho frontend** khi gom lại vào repo `SpaceLink`.

**1.5. Mock API server**

- **Tên**: mock-api-server  
- **Path**: `All-Projects/FE-Projects/mock-api-server/`  
- **Stack**: Node.js, Express, Faker  
- **Mục đích**: Fake API cho frontend dev  
- **Tình trạng** (≈ 60%):
  - Có: Auth, Products, Categories, Brands, Cart, Orders, JWT, CORS, fake data tương đối đầy đủ.
- **Gợi ý**: Dùng để **test nhanh UI frontend** khi backend Laravel chưa xong.

---

### 2. Hệ thống tài liệu (docs) đã có

- **SRS chính**: `Documents/Markdown/SL-SRS/srs_website_online_spacelink.md`
  - Mô tả đầy đủ actors, chức năng, non-functional, timeline.
- **Phân tích & thiết kế DB**: `Documents/Markdown/Phan-Tich-Du-An/01_database_and_migrations.md` + file SQL trong `Databases/SQL-Files/`
  - Schema 46 bảng, quan hệ, index, sample data.
- **Roadmap backend**: `Documents/Markdown/SRS-BE/01-backend-roadmap-strategy.md`
  - Kế hoạch theo tuần cho backend.
- **API docs**:
  - `Documents/Markdown/Phan-Tich-Du-An/API_DOCUMENTATION.md`
  - `Documents/Markdown/SRS-BE/03-api-documentation.md`
- **Auth & Sanctum**:
  - `Documents/Markdown/Phan-Tich-Du-An/03_authentication.md`
  - `Documents/Markdown/SRS-BE/04-sanctum-setup-clarification.md`
  - `Documents/Markdown/SRS-BE/05-sanctum-quick-reference.md`
- **Roles & permissions**:
  - `PHAN_TICH_ROLES_VA_ACTORS.md`
  - `HUONG_DAN_THIET_KE_DATABASE_USERS_ROLES.md`

---

### 3. Vấn đề chính hiện tại

- **Trùng lặp**:
  - Nhiều backend project cùng làm Products/Categories.
  - Nhiều file SRS/API/roadmap lặp thông tin nhau.
- **Thiếu “một repo chuẩn”**:
  - Chưa có backend + frontend chung tên `SpaceLink` với cấu trúc rõ ràng.
- **Thiếu lộ trình code lại**:
  - Khó biết nên bắt đầu từ project nào, migrate code thế nào.

---

### 4. Định hướng gom lại thành 1 project `SpaceLink`

- **Backend**:
  - Chọn **Laravel API** (base từ `spacelink-backend-test-2`).
  - Đồng bộ schema với DB 46 bảng, viết lại migrations/seeders chuẩn.
- **Frontend**:
  - Chọn **React TS** (base từ `spacelink-frontend-test-2`).
  - Kết nối với backend qua API chuẩn hóa.
- **Admin**:
  - Ban đầu có thể dùng Blade từ `sl-db-test` để tham khảo layout.
  - Sau có thể tách thành **Admin React** dùng chung API như client.

Các bước chi tiết để gom lại sẽ nằm trong file  
`01-FOUNDATION/03-learning-path-roadmap.md`.

