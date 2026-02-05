## 04 - Seeder, Factory, Eloquent hay Query Builder?

### 1. Có nên dùng Seeder + Factory không?

**Seeder: NÊN dùng (bắt buộc nên có)**

- Vì bạn có **React frontend chạy song song với Laravel**, cần:
  - Dữ liệu đủ nhiều để test UI (list, filter, pagination…).
  - Dữ liệu “ổn định” giữa các lần reset DB (migrate:fresh + seed).
- Đề xuất:
  - Seeder core cho:
    - Users (ít nhất 1 admin, 1–2 user thường).
    - Categories, Brands.
    - Products (+ vài product_images).
  - Có thể viết seeders tay, hoặc dùng Factory hỗ trợ sinh nhiều record.

**Factory: NÊN dùng nhưng không bắt buộc**

- Trong `composer.json` các project Laravel của bạn đã có:
  - `"fakerphp/faker"` (dev dependency) → rất phù hợp để sinh dữ liệu giả.
- Đề xuất dùng factory khi:
  - Cần sinh **nhiều dữ liệu** (ví dụ: 100 products, 500 order_items) để test performance/UI.
  - Cần viết **test (Pest / PHPUnit)**, vì factory rất tiện để tạo data cho test.
- Nếu bạn muốn đơn giản:
  - Phase 1: Seeder tay cho ~10–20 sản phẩm là đủ test UI.
  - Phase 2: Thêm factory để sinh nhiều dữ liệu hơn (khi cần).

Kết luận ngắn:

- Seeder: **nên làm ngay** (core data cho BE + FE).  
- Factory: **nên làm**, nhưng có thể để **sau** khi đã ổn migrations + seeder cơ bản.

---

### 2. Nên dùng Eloquent hay Query Builder?

**Eloquent (khuyên dùng cho 80–90% use case)**

- Phù hợp với:
  - CRUD chuẩn (Products, Categories, Orders…).
  - Quan hệ đã định nghĩa (belongsTo, hasMany, etc.).
- Lợi ích:
  - Code ngắn, dễ đọc, dễ map với business (đặc biệt khi bạn đã định nghĩa relation rõ ràng).
  - Dễ dùng cùng với Resource/API Resource, Policy, Scope…

**Query Builder (dùng cho case đặc biệt)**

- Phù hợp khi:
  - Cần query rất phức tạp, nhiều join, aggregate, thống kê/report.
  - Cần tối ưu query ở mức SQL “thấp” hơn.
- Đề xuất:
  - Bắt đầu với **Eloquent**.
  - Khi gặp case hiệu năng phức tạp → cân nhắc chuyển 1 số đoạn sang Query Builder/Raw SQL.

Kết luận ngắn:

- **Mặc định**: dùng Eloquent cho models chính (User, Product, Order…).  
- Chỉ dùng Query Builder khi:
  - Cần report nặng,
  - Hoặc khi Eloquent quá rối / khó tối ưu.

---

### 3. Thư viện hiện tại & có nên cài thêm gì không?

Dựa trên các `composer.json`:

- `spacelink-backend-test-2` / `sl-api-test`:
  - Đã có:
    - `"laravel/framework": "^12.0"`
    - `"laravel/sanctum": "^4.0"`
    - Dev: Faker, Pest, Pint… → đủ để:
      - Dùng Sanctum cho API auth.
      - Dùng Factory + Seeder + Test sau này.
- `sl-db-test` (backend):
  - Tương tự skeleton Laravel 12, **chưa cài Sanctum** (hợp lý vì là web admin test).

**Đề xuất cho project `SpaceLink-API` mới:**

- **Bắt buộc nên có ngay:**
  - `laravel/sanctum` (cho auth) – bạn đã quen, docs cũng có.
- **Cân nhắc khi cần (không bắt buộc ở phase 1):**
  - `spatie/laravel-permission` nếu bạn muốn quản lý roles/permissions phức tạp bằng package.
  - `barryvdh/laravel-debugbar` chỉ dùng trong dev để debug query/response.

Nếu mục tiêu chính là **học + hoàn thành bài + code theo roadmap**, thì:

- Dùng **stack mặc định** như trong `composer.json` hiện tại là **đủ**.  
- Tập trung:
  - Migrations (27 bảng).
  - Seeder core + (Factory sau).
  - Eloquent models + relations chuẩn.
  - API + frontend React theo roadmap.

