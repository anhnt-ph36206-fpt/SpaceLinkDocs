## 03 - API Products & Categories (catalog) chi tiết

### 0. Chuẩn bị

- Đã có migrations + models:
  - `Category`, `Brand`, `Product`, `ProductImage`, `ProductVariant`…
- Tập trung trước vào bản **đơn giản**:
  - Categories (có thể đa cấp).
  - Brands.
  - Products + Images (chưa cần variants nếu muốn làm nhanh).

---

### 1. Bước 1 – Public API cho FE (không cần auth)

**Mục tiêu**

- Cho phép client:
  - Lấy danh sách danh mục.
  - Lấy danh sách thương hiệu.
  - Lấy danh sách sản phẩm có filter cơ bản.
  - Lấy chi tiết sản phẩm.

**Endpoints gợi ý**

- `GET /api/categories`
- `GET /api/brands`
- `GET /api/products`
- `GET /api/products/{id-or-slug}`

**Logic chính**

- `GET /categories`:
  - Trả về danh sách categories đang `is_active = true`.
  - Có thể trả dạng cây (nested) hoặc flat + `parent_id`.
- `GET /brands`:
  - Trả về brands `is_active = true`, sắp xếp theo `display_order`.
- `GET /products`:
  - Query:
    - Filter theo `category_id`, `brand_id`.
    - Filter theo `min_price`, `max_price`.
    - Sort theo `price`, `created_at`, `sold_count`.
    - Pagination (vd: 12 sản phẩm/trang).
- `GET /products/{id-or-slug}`:
  - Lấy 1 product + `images` + `category` + `brand`.

**Cách làm (từng bước)**

1. Tạo `CategoryController`, `BrandController`, `ProductController` trong namespace API.
2. Viết Resource (optional nhưng nên làm):
   - `CategoryResource`, `BrandResource`, `ProductResource`.
3. Định nghĩa routes:
   - `Route::get('categories', [...]);`
   - `Route::get('brands', [...]);`
   - `Route::get('products', [...]);`
   - `Route::get('products/{product}', [...]);`
4. Test bằng Postman rồi mới nối sang React.

---

### 2. Bước 2 – API quản trị Products/Categories/Brands (admin)

**Mục tiêu**

- Cho phép admin CRUD catalog.
- Giai đoạn đầu có thể làm phiên bản cơ bản:
  - Không cần hỗ trợ mọi field nâng cao ngay.

**Endpoints gợi ý (admin)**

- `GET /api/admin/categories`
- `POST /api/admin/categories`
- `PUT /api/admin/categories/{id}`
- `DELETE /api/admin/categories/{id}`

Tương tự cho:

- `/api/admin/brands`
- `/api/admin/products`

**Middleware**

- Nhóm route:
  - `Route::prefix('admin')->middleware(['auth:sanctum', 'can:manage_catalog'])`…
  - Hoặc tạm thời chỉ dùng `auth:sanctum` và check role trong controller.

**Thứ tự làm**

1. Làm public GET trước (xem mục 1).
2. Làm admin `store/update/delete` cho:
   - Categories.
   - Brands.
   - Products (chỉ phần thông tin chính, chưa cần variants phức tạp).

---

### 3. Bước 3 – Bổ sung search & SEO-friendly

Khi cơ bản đã chạy:

- Thêm:
  - Tìm kiếm theo `q` (search theo `name`, `description`).
  - Lọc theo `is_featured`, `price range`.
- Chuẩn hóa:
  - Dùng slug cho chi tiết sản phẩm: `GET /products/{slug}`.
  - Redirect/404 hợp lý khi không tìm thấy.

---

### 4. Đề xuất workflow implement

1. Viết các **query Eloquent** cho:
   - `Category::active()->get()`.
   - `Brand::active()->orderBy('display_order')`.
   - Product listing với filter.
2. Test bằng Postman với DB seeders.
3. Sau đó mới:
   - Viết UI React: trang Home, Category page, Product detail.
4. Khi mọi thứ stable, nếu cần:
   - Bổ sung `ProductVariant` + filter theo thuộc tính. 

