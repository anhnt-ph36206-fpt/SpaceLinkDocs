## 01 - Roadmap Frontend React

### 1. Mục tiêu

- Dùng React 19 + TS + Vite + Ant Design.
- Dùng `spacelink-frontend-test-2` làm tham khảo, nhưng code sạch lại trong project mới.

### 2. Thứ tự xây dựng

1. **Skeleton**
   - Tạo project mới, cấu trúc `src/` (components, pages, routes, services).
   - Setup React Router, layout cơ bản (Header, Footer, Content).
2. **Auth**
   - Trang Login, Register, Profile.
   - Lưu token (localStorage hoặc cookie + axios interceptor).
   - `ProtectedRoute` cho các trang cần đăng nhập.
3. **Products**
   - Trang danh sách sản phẩm (list, filter, sort, pagination).
   - Trang chi tiết sản phẩm.
4. **Cart & Checkout**
   - Trang Cart: thêm/xóa/sửa số lượng, tính tổng.
   - Trang Checkout: form địa chỉ, method thanh toán (giả lập trước).
5. **Orders History**
   - Trang lịch sử đơn hàng của user.

### 3. Admin (sau)

- Trang admin (có thể tách layout riêng):
  - Quản lý sản phẩm.
  - Quản lý đơn hàng.
  - Quản lý user (sau).

