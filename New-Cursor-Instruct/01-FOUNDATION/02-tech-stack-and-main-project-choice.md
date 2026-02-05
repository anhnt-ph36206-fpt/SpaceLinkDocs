## 02 - Tech stack & chọn project chính

### 1. Tech stack đề xuất cho project SpaceLink duy nhất

- **Backend**:
  - **Laravel 12** (API-first, không Blade cho client chính).
  - **Laravel Sanctum** cho authentication (SPA / mobile friendly).
  - **MySQL** (dùng lại schema 46 bảng đã thiết kế).
- **Frontend**:
  - **React 19 + TypeScript + Vite**.
  - **Ant Design** cho UI components.
  - **React Router** cho routing, **Axios** cho gọi API.
- **Khác**:
  - Mock API (Node/Express) chỉ dùng khi backend chưa sẵn sàng.

### 2. Chọn project nền tảng

- **Backend base**: `All-Projects/BE-Projects/spacelink-backend-test-2/`
  - Lý do:
    - Đã setup Laravel 12 + Sanctum.
    - Đã có flow auth cơ bản và Products/Categories.
  - Hướng đi:
    - Dùng nó làm “tham khảo” để tạo **project mới tên `SpaceLink`** (hoặc copy ra repo mới và refactor mạnh tay).

- **Frontend base**: `All-Projects/FE-Projects/spacelink-frontend-test-2/`
  - Lý do:
    - Đã có cấu trúc React TS + Ant Design + ProtectedRoute.
  - Hướng đi:
    - Giữ lại ý tưởng cấu trúc, khi tạo repo `SpaceLink` sẽ clone/port cấu trúc này vào.

### 3. Nguyên tắc khi gom lại

- **Không cố “merge code” trực tiếp** từ tất cả project test.
  - Thay vào đó, dùng chúng như **“thư viện snippet”**:
    - Thích đoạn nào → copy có chọn lọc sang project `SpaceLink` mới.
- **Mọi quyết định đều dựa trên docs chuẩn**:
  - SRS, DB, API docs là “source of truth”.
  - Code phải bám theo docs, không phải ngược lại.

