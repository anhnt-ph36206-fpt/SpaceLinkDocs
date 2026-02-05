## 03 - Foreign key trong migrations: nên hay không?

### 1. Hai cách tiếp cận

- **Cách A – Có foreign key trong DB**
  - Migrations khai báo `foreignId()->constrained()` …  
  - DB **ép buộc toàn vẹn dữ liệu**: không thể có order_item trỏ tới order đã xoá.
  - Ưu điểm:
    - Dữ liệu sạch, ít “mồ côi”.
    - Dễ debug lỗi logic vì DB chặn ngay.
  - Nhược điểm:
    - Khi **rollback / đổi schema** phải cẩn thận thứ tự (drop FK trước, rồi drop bảng).
    - Khi muốn xoá dữ liệu hàng loạt có thể bị FK chặn (cần set `onDelete('cascade')` hoặc xoá theo đúng thứ tự).

- **Cách B – Không khai báo foreign key, chỉ dùng quan hệ Eloquent**
  - Migrations chỉ tạo `unsignedBigInteger`/`foreignId` nhưng **không add constraint**.
  - Laravel Model vẫn có `belongsTo`, `hasMany` hoạt động bình thường.
  - Ưu điểm:
    - Linh hoạt khi đổi schema, rollback, migrate:fresh.
    - Ít bị “kẹt” vì lỗi FK khi đang dev.
  - Nhược điểm:
    - DB không kiểm soát, dễ sinh dữ liệu lỗi (order_item trỏ tới order không tồn tại).
    - Phải tự viết logic dọn dẹp, dễ quên ở các chỗ hiếm khi chạy.

### 2. Đề xuất cho SpaceLink (cân bằng)

**Gợi ý:**  
- Phase đầu (học + dựng nhanh):
  - Có thể chọn **Cách B** cho **một số bảng ít critical** để bạn thoải mái refactor.
- Với các quan hệ rất quan trọng:
  - `orders` ↔ `order_items`
  - `users` ↔ `orders`
  - `products` ↔ `order_items`
  - `categories` ↔ `products`
  - Nên **đặt foreign key chuẩn** (Cách A) ngay từ đầu.

Nói ngắn gọn:

- **Core business data** (đơn hàng, chi tiết đơn, người dùng, sản phẩm, category) → **có FK**.  
- **Bảng phụ / logging / optional** (ví dụ logs, audit, một số bảng thống kê…) → có thể **không bắt buộc FK** ở phase 1.

### 3. Cách giảm rủi ro khi dùng foreign key

- **1) Chuẩn hoá thứ tự migrations**
  - Tạo bảng cha trước (users, products, orders), rồi mới tới bảng con (order_items…).
- **2) Khi sửa schema**
  - Tuyệt đối không sửa file migration đã chạy trong môi trường thật.
  - Luôn tạo **migration mới** để:
    - Drop foreign key cũ (nếu cần).
    - Add foreign key mới / đổi kiểu cột.
- **3) Khi cần reset DB trong dev**
  - Dùng `php artisan migrate:fresh --seed` (Laravel sẽ xử lý thứ tự).
- **4) Thiết lập cascade hợp lý**
  - Ví dụ: khi xoá `order` thì tự xoá `order_items` → dùng `onDelete('cascade')`.

### 4. Nếu bạn rất lo rollback / nâng cấp khó

Một lộ trình an toàn:

1. **Phase 1**:
   - Viết migrations **không FK** cho tất cả bảng.
   - Viết Eloquent relationships đầy đủ.
   - Code logic, test luồng business, refactor thoải mái.
2. **Phase 2**:
   - Khi schema tương đối ổn:
     - Thêm **migration mới** chỉ để add foreign key cho các quan hệ quan trọng.

Như vậy:

- Bạn **vẫn luyện được thiết kế FK chuẩn**,  
- Nhưng tránh việc bị “trói tay” quá sớm khi schema còn thay đổi liên tục.

