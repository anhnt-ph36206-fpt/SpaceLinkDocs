## 05 - Spatie Permission và 3 bảng roles / permissions / role_permission

### 1. Spatie tạo những bảng gì?

Package `spatie/laravel-permission` mặc định tạo các bảng:

- `roles`
- `permissions`
- `model_has_roles`
- `model_has_permissions`
- `role_has_permissions`

Tức là:

- Nó đã có **roles** và **permissions** riêng.
- Pivot giữa role và permission tên là **role_has_permissions** (không phải `role_permission`).
- Pivot giữa user (hoặc model khác) và role/permission là `model_has_*`.

### 2. Nếu bạn đã có 3 bảng tự thiết kế (roles, permissions, role_permission) thì sao?

Có 3 hướng đi chính:

#### Option A – Không dùng Spatie, giữ nguyên 3 bảng tự thiết kế

- Dùng chính:
  - `roles`
  - `permissions`
  - `role_permission` (pivot)
- Tự viết:
  - Quan hệ Eloquent.
  - Middleware kiểm tra role/permission.
- Phù hợp khi:
  - Bạn muốn **đơn giản, kiểm soát mọi thứ**, chỉ cần role/permission cơ bản.

#### Option B – Quyết định dùng Spatie, để Spatie làm “source of truth”

- Cho phép Spatie **tạo migrations riêng** cho roles/permissions.
- Lúc này:
  - **Không nên giữ thêm** bảng `role_permission` song song (bị trùng chức năng).
  - Nếu 3 bảng cũ **chưa dùng ở môi trường thật**, có thể:
    - Xoá migrations cũ (hoặc không tạo chúng trong project `SpaceLink-API` mới).
  - Nếu docs trước đây nói về 3 bảng này:
    - Cập nhật docs lại theo schema của Spatie (role_has_permissions, model_has_roles…).
- Phù hợp khi:
  - Bạn muốn tận dụng hết features của Spatie (gán role/permission theo model, guard_name, v.v.).

#### Option C – Cố gắng “tái sử dụng” 3 bảng cũ với Spatie

- Kỹ thuật: có thể cấu hình lại Spatie để nó dùng các bảng/column có sẵn.  
- Tuy nhiên:
  - Tăng độ phức tạp config/migration.
  - Không phù hợp cho mục tiêu học + làm bài tập rõ ràng.

=> Với bối cảnh hiện tại (dự án đang ở giai đoạn định hình, chưa production):

- **Hoặc**: Dùng 3 bảng cũ, **không cài Spatie** (đơn giản, dễ hiểu).  
- **Hoặc**: Nếu đã quyết “chơi Spatie” → **để Spatie quản lý roles/permissions hoàn toàn**, và **không thêm bảng `role_permission` riêng nữa**.

### 3. Đề xuất thực tế cho lộ trình SpaceLink của bạn

- Vì bạn đang:
  - Tập trung vào 27 bảng bắt buộc.
  - Xây lại từ đầu theo roadmap, chưa có production data.
- Đề xuất:
  - **Phase 1** (core + bài tập):  
    - Không cần Spatie; dùng 3 bảng đơn giản (roles/permissions/role_user hoặc role_permission) là đủ.  
  - **Phase 2** (muốn học sâu về RBAC nâng cao):  
    - Tạo branch / project nhánh, cài Spatie, cho nó quản lý luôn schema roles/permissions.

Tóm tắt ngắn:

- Nếu **dùng Spatie nghiêm túc** trong project mới:
  - Để Spatie tạo roles/permissions/pivot theo cách của nó,
  - **Không dùng thêm** bảng `role_permission` tự thiết kế (tránh trùng lặp, khó bảo trì).
- Nếu muốn mọi thứ **dễ hiểu, nhẹ, phù hợp bài tập**:
  - Chưa cần cài Spatie vội, giữ 3 bảng cũ và tự code check role/permission.

