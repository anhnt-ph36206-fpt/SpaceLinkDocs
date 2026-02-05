# SOFTWARE REQUIREMENT SPECIFICATION (SRS)
## Dự án: Website bán hàng online cho cửa hàng công nghệ tư nhân tại Hà Nội

---

## 1. Giới thiệu

### 1.1 Mục đích tài liệu
Tài liệu SRS này mô tả đầy đủ các yêu cầu chức năng và phi chức năng của hệ thống website bán hàng online dành cho **một cửa hàng công nghệ tư nhân**, phục vụ cho mục tiêu **dự án tốt nghiệp (thời gian thực hiện 4 tháng)**. Tài liệu là cơ sở để:
- Phân tích, thiết kế hệ thống
- Phát triển, kiểm thử
- Đánh giá và bảo vệ đồ án

### 1.2 Phạm vi dự án
Hệ thống là **website bán hàng online (không phải sàn TMĐT)**, chỉ phục vụ cho **một cửa hàng duy nhất** với thương hiệu riêng, chuyên kinh doanh:
- Điện thoại
- Laptop
- Tablet
- Phụ kiện công nghệ

Website hỗ trợ bán hàng online song song với cửa hàng offline tại Hà Nội, tập trung vào các luồng chính đạt chuẩn **MVP (Minimum Viable Product)**.

### 1.3 Định nghĩa, thuật ngữ
- **MVP**: Phiên bản sản phẩm tối thiểu, đáp ứng luồng nghiệp vụ cốt lõi
- **Khách vãng lai**: Người dùng chưa đăng ký tài khoản
- **COD**: Thanh toán khi nhận hàng

---

## 2. Tổng quan hệ thống

### 2.1 Mô tả chung
Hệ thống là website cho phép khách hàng xem sản phẩm, đặt hàng online; chủ cửa hàng quản lý sản phẩm – đơn hàng; shipper xử lý giao hàng.

### 2.2 Mục tiêu hệ thống
- Hỗ trợ bán hàng online cho cửa hàng nhỏ
- Giảm phụ thuộc vào bán hàng offline
- Quản lý đơn hàng tập trung
- Tạo trải nghiệm mua sắm đơn giản, dễ dùng

### 2.3 Nhóm người dùng (Actors)

> Một website bán hàng nhỏ **không chỉ có User và Admin**, mà nên được tách vai trò rõ ràng theo nghiệp vụ.

#### Actor 1: Khách hàng (Customer)
Bao gồm:
- **Khách vãng lai (Guest)**
- **Khách đã đăng ký (Registered Customer)**

Quyền hạn:
- Xem danh sách sản phẩm
- Tìm kiếm, lọc sản phẩm
- Xem chi tiết sản phẩm
- Thêm vào giỏ hàng
- Đặt hàng (Guest / Logged-in)
- Theo dõi trạng thái đơn hàng
- Đánh giá sản phẩm (nếu đã mua)

---

#### Actor 2: Chủ cửa hàng (Store Owner / Admin)
Là người sở hữu cửa hàng, chịu trách nhiệm toàn bộ hoạt động kinh doanh.

Quyền hạn:
- Quản lý sản phẩm (CRUD)
- Quản lý danh mục, thương hiệu
- Quản lý tồn kho
- Quản lý đơn hàng
- Xác nhận / huỷ đơn
- Phân công đơn cho shipper
- Xem báo cáo doanh thu

---

#### Actor 3: Nhân viên giao hàng (Shipper)
Có thể là shipper nội bộ hoặc cộng tác viên.

Quyền hạn:
- Xem danh sách đơn hàng được giao
- Cập nhật trạng thái giao hàng
- Xác nhận giao thành công / thất bại

---

#### Actor 4 (Tuỳ chọn – mở rộng): Nhân viên cửa hàng (Staff)
(Actor này có thể trình bày trong phần mở rộng, không bắt buộc cho MVP)

- Hỗ trợ xử lý đơn
- Cập nhật trạng thái đơn hàng

---

## 3. Yêu cầu chức năng (Functional Requirements)

### 3.1 Chức năng cho Khách hàng

#### 3.1.1 Quản lý tài khoản
- Đăng ký tài khoản
- Đăng nhập / đăng xuất
- Quên mật khẩu

#### 3.1.2 Xem & tìm kiếm sản phẩm
- Xem danh sách sản phẩm
- Xem chi tiết sản phẩm
- Tìm kiếm theo tên
- Lọc theo giá, danh mục, thương hiệu

#### 3.1.3 Giỏ hàng
- Thêm / xoá sản phẩm
- Cập nhật số lượng
- Lưu giỏ hàng (đối với user đăng nhập)

#### 3.1.4 Đặt hàng
- Đặt hàng không cần đăng nhập (Guest Checkout)
- Nhập thông tin giao hàng
- Chọn phương thức thanh toán (COD)

#### 3.1.5 Theo dõi đơn hàng
- Xem trạng thái đơn hàng
- Nhận thông báo khi thay đổi trạng thái

---

### 3.2 Chức năng cho Chủ cửa hàng

#### 3.2.1 Quản lý sản phẩm
- Thêm / sửa / xoá sản phẩm
- Upload hình ảnh
- Quản lý giá, khuyến mãi

#### 3.2.2 Quản lý danh mục & thương hiệu
- CRUD danh mục
- CRUD thương hiệu

#### 3.2.3 Quản lý đơn hàng
- Xem danh sách đơn
- Xác nhận đơn
- Huỷ đơn
- Phân công shipper

#### 3.2.4 Báo cáo & thống kê
- Doanh thu theo ngày / tháng
- Số lượng đơn hàng

---

### 3.3 Chức năng cho Shipper
- Đăng nhập hệ thống
- Xem đơn được giao
- Cập nhật trạng thái giao hàng

---

## 4. Yêu cầu phi chức năng (Non-Functional Requirements)

### 4.1 Hiệu năng
- Website phản hồi dưới 3 giây
- Hỗ trợ tối thiểu 100 user đồng thời

### 4.2 Bảo mật
- Mã hoá mật khẩu
- Phân quyền rõ ràng
- Chống SQL Injection, XSS

### 4.3 Khả năng sử dụng
- Giao diện đơn giản
- Responsive (Desktop / Mobile)

### 4.4 Khả năng mở rộng
- Có thể tích hợp thanh toán online trong tương lai
- Có thể thêm role Staff

---

## 5. Lộ trình thực hiện dự án (4 tháng)

### Tháng 1: Phân tích & thiết kế
- Thu thập yêu cầu
- Viết SRS
- Vẽ Use Case, ERD
- Thiết kế wireframe

### Tháng 2: Backend
- Thiết kế database
- Xây dựng API
- Auth & phân quyền

### Tháng 3: Frontend
- Giao diện người dùng
- Tích hợp API
- Hoàn thiện luồng mua hàng

### Tháng 4: Hoàn thiện & báo cáo
- Test hệ thống
- Fix bug
- Viết báo cáo đồ án
- Chuẩn bị demo & bảo vệ

---

## 6. Giới hạn hệ thống
- Không hỗ trợ đa cửa hàng
- Không tích hợp thanh toán online (MVP)
- Không có hệ thống đánh giá phức tạp

---

## 7. Kết luận
Hệ thống đáp ứng nhu cầu bán hàng online cho cửa hàng công nghệ nhỏ, phù hợp quy mô dự án tốt nghiệp, đảm bảo luồng nghiệp vụ rõ ràng và khả năng mở rộng trong tương lai.

