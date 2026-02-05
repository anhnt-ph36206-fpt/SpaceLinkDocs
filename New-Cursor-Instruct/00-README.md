## SpaceLink - Lộ trình thống nhất dự án

**Mục tiêu thư mục `New-Cursor-Instruct`**

- **Tổng hợp lại** những gì bạn đã làm rải rác ở nhiều project + nhiều file docs.
- **Định nghĩa lộ trình học / code lại** một project SpaceLink duy nhất từ dễ → khó.
- **Không chứa code chạy trực tiếp**, chủ yếu là hướng dẫn, checklist, kế hoạch. Khi cần code, bạn sẽ tự gõ hoặc copy sang repo chính `SpaceLink`.

**Hiện trạng tổng quan (ngắn gọn)**

- Bạn đã có:
  - **DB schema 46 bảng** khá hoàn chỉnh + sample data (folder `Databases/SQL-Files`).
  - **SRS, phân tích yêu cầu, roadmap BE** chi tiết (folder `Documents/Markdown` và con).
  - **Nhiều project test**: backend API, backend admin web, frontend React, mock API server.
- Chưa có:
  - **Một repo duy nhất** gom hết (backend + frontend).
  - **Một lộ trình code rõ ràng** từng bước để build lại từ đầu.

**Các phần chính trong `New-Cursor-Instruct`**

- `01-FOUNDATION/`: Tổng hợp hiện trạng, tech stack, chọn “main project”, hướng dẫn setup môi trường.
- `02-DATABASE/`: Cách dùng schema hiện tại, chiến lược migrations/seeders cho project mới.
- `03-AUTHENTICATION/`: Kế hoạch auth với Laravel Sanctum + roles/permissions.
- `04-APIS/`: Lộ trình viết API (Products, Cart, Orders, Users, Admin…).
- `05-FRONTEND/`: Hướng dẫn tổ chức frontend React + cách gọi API.
- `06-BUSINESS-LOGIC/` → `10-ADVANCED/`: Các chủ đề nâng cao (order flow, payment, testing, deployment, tối ưu…).

**Cách sử dụng**

1. Đọc `01-FOUNDATION/01-project-overview.md` để nắm rõ **những gì đã có**.
2. Đọc `01-FOUNDATION/03-learning-path-roadmap.md` để nắm **lộ trình tổng thể** từ dễ → khó.
3. Làm lần lượt:
   - Foundation → Database → Auth → APIs → Frontend → Business Logic → Admin → Testing → Deployment → Advanced.
4. Mỗi khi hoàn thành 1 bước trong project chính `SpaceLink`, quay lại tick/checklist trong các file roadmap tương ứng.

