## 01 - Kế hoạch Authentication & Authorization

### 1. Mục tiêu

- Dùng **Laravel Sanctum** cho SPA (React) + hỗ trợ mobile sau này.
- Hệ thống **User + Role + Permission** bám sát docs đã phân tích.

### 2. Các bước chính

1. **Cài đặt Sanctum trên project `SpaceLink-API` mới**
   - Dựa lại vào các file hướng dẫn:
     - `03_authentication.md`
     - `04-sanctum-setup-clarification.md`
     - `05-sanctum-quick-reference.md`
2. **Thiết kế bảng & model**
   - users
   - roles, role_user (hoặc sử dụng 1 bảng trung gian khác tùy thiết kế hiện tại)
   - permissions (nếu cần chi tiết) + bảng trung gian.
3. **Viết API auth cơ bản**
   - POST `/auth/register`
   - POST `/auth/login`
   - POST `/auth/logout`
   - GET `/auth/me`
4. **Gắn middleware bảo vệ routes**
   - Nhóm route `auth:sanctum` cho user đã đăng nhập.
   - Middleware riêng cho `role:admin` cho các route admin.

### 3. Checklist triển khai

- [ ] Cài Sanctum, publish config, migrate token table.
- [ ] Thiết kế lại model User + quan hệ với Role (theo docs roles/actors).
- [ ] Viết controller cho các endpoint auth cơ bản.
- [ ] Test thủ công bằng Postman/Thunder Client.
- [ ] Viết ít nhất 1–2 feature test cho login/register.

