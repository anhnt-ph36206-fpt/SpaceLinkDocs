# 🎉 HOÀN THÀNH! MOCK API SERVER SẴN SÀNG

Tôi đã tạo xong một **Mock API Server** hoàn chỉnh cho bạn!

---

## 📁 CẤU TRÚC THƯ MỤC

```
D:\WebServers\laragon6\www\SpaceLinkDocs\mock-api-server\
│
├── package.json                      # Dependencies
├── server.js                         # Mock API server (Node.js + Express)
├── .gitignore                        # Git ignore
│
├── README.md                         # Hướng dẫn cài đặt và sử dụng
└── HUONG_DAN_CHO_FRONTEND_TEAM.md   # Hướng dẫn cho FE Team
```

---

## 🚀 CÁCH CHẠY SERVER

### Bước 1: Mở Terminal/PowerShell

```bash
cd D:\WebServers\laragon6\www\SpaceLinkDocs\mock-api-server
```

### Bước 2: Cài đặt dependencies (chỉ làm 1 lần)

```bash
npm install
```

### Bước 3: Chạy server

```bash
npm start
```

Server sẽ chạy tại: **http://localhost:3333**

---

## ⚠️ LƯU Ý: NẾU PORT 3333 BỊ CHIẾM

### Cách 1: Đổi port trong code

Mở file `server.js`, dòng 7:
```javascript
const PORT = 3333; // Đổi thành 8080, 5000, ...
```

### Cách 2: Tìm và tắt process đang chiếm port

**Windows:**
```powershell
# Tìm process đang dùng port 3333
netstat -ano | findstr :3333

# Kill process (thay PID bằng số hiện ra ở trên)
taskkill /PID <PID> /F
```

**Mac/Linux:**
```bash
# Tìm process
lsof -i :3333

# Kill process
kill -9 <PID>
```

---

## 🧪 TEST API

### Test với cURL (Command Line)

```bash
# Test đăng nhập
curl -X POST http://localhost:3333/api/auth/login ^
  -H "Content-Type: application/json" ^
  -d "{\"email\":\"test@example.com\",\"password\":\"123456\"}"

# Test lấy sản phẩm
curl http://localhost:3333/api/products?page=1
```

### Test với Postman

1. Tải Postman: https://www.postman.com/downloads/
2. Tạo request: `POST http://localhost:3333/api/auth/login`
3. Body → raw → JSON:
```json
{
  "email": "test@example.com",
  "password": "123456"
}
```
4. Send → Copy token từ response
5. Dùng token cho các request khác

---

## 📚 TÀI LIỆU HƯỚNG DẪN

### 1. README.md
- Cài đặt và chạy server
- API endpoints
- Ví dụ integration với React
- Tùy chỉnh mock data

### 2. HUONG_DAN_CHO_FRONTEND_TEAM.md
- FE Team sẽ hiểu được gì từ file API_DOCUMENTATION.md?
- Quy trình làm việc song song FE + BE
- Công cụ test API (Postman, Thunder Client,...)
- Website kiếm dữ liệu giả
- Mẫu code integration (React, Vue)
- Xử lý authentication
- FAQ

---

## 🎯 FE TEAM CẦN LÀM GÌ?

### 1. Đọc API Documentation
✅ File: `D:\WebServers\laragon6\www\SpaceLinkDocs\documents\API_DOCUMENTATION.md`
✅ Hiểu: Endpoints, Request/Response format, HTTP methods

### 2. Chạy Mock API Server
✅ `cd mock-api-server`
✅ `npm install` (chỉ làm 1 lần)
✅ `npm start`

### 3. Test API với Postman
✅ Cài Postman
✅ Test từng endpoint
✅ Hiểu flow: Login → Get token → Use token

### 4. Tích hợp vào Frontend
✅ Tạo `api.js` service (ví dụ có trong README.md)
✅ Gọi API từ components
✅ Hiển thị dữ liệu, xử lý lỗi

### 5. Khi Backend xong
✅ Thay Base URL: `localhost:3333` → URL thật
✅ DONE! Không cần sửa gì khác

---

## 💡 LỢI ÍCH

1. ✅ **Không đợi Backend** - Làm song song ngay
2. ✅ **Dữ liệu giả chất lượng** - Faker.js tạo data đẹp
3. ✅ **Test UI/UX sớm** - Phát hiện bug UI sớm
4. ✅ **Demo sớm** - Có dữ liệu để demo
5. ✅ **Dễ switch** - Chỉ đổi Base URL

---

## 📊 CÁC ENDPOINT CÓ SẴN

### Authentication
- `POST /api/auth/register` - Đăng ký
- `POST /api/auth/login` - Đăng nhập
- `POST /api/auth/logout` - Đăng xuất (auth required)
- `POST /api/auth/forgot-password` - Quên mật khẩu
- `POST /api/auth/refresh` - Refresh token (auth required)

### Products
- `GET /api/products` - Danh sách sản phẩm (có phân trang, lọc, tìm kiếm)
- `GET /api/products/:id` - Chi tiết sản phẩm
- `GET /api/products/featured` - Sản phẩm nổi bật
- `GET /api/categories` - Danh sách danh mục
- `GET /api/brands` - Danh sách thương hiệu

### Cart
- `GET /api/cart` - Lấy giỏ hàng
- `POST /api/cart/add` - Thêm vào giỏ
- `PUT /api/cart/:id` - Cập nhật số lượng
- `DELETE /api/cart/:id` - Xóa khỏi giỏ
- `DELETE /api/cart/clear` - Xóa toàn bộ giỏ

### Orders
- `POST /api/orders` - Tạo đơn hàng
- `GET /api/orders` - Danh sách đơn (auth required)
- `GET /api/orders/:id` - Chi tiết đơn (auth required)
- `POST /api/orders/:id/cancel` - Hủy đơn (auth required)

### User Profile
- `GET /api/user/profile` - Lấy profile (auth required)
- `PUT /api/user/profile` - Cập nhật profile (auth required)
- `PUT /api/user/change-password` - Đổi mật khẩu (auth required)

---

## 🔧 CÔNG NGHỆ SỬ DỤNG

- **Node.js** - JavaScript runtime
- **Express.js** - Web framework
- **Faker.js** - Fake data generator
- **JSON Web Token (JWT)** - Authentication
- **CORS** - Cross-Origin Resource Sharing

---

## ❓ FAQ

**Q: Dữ liệu có lưu lại không?**
A: Không. Mỗi lần gọi API tạo dữ liệu mới.

**Q: Token có thật không?**
A: Có. JWT thật, nhưng chỉ để test flow authen.

**Q: Có thể dùng cho production?**
A: KHÔNG. Chỉ dùng cho development.

**Q: Thêm endpoint mới?**
A: Copy endpoint tương tự trong `server.js`, sửa route và logic.

**Q: Port bị chiếm?**
A: Đổi `PORT` trong `server.js` hoặc kill process cũ.

---

## 📞 LIÊN HỆ HỖ TRỢ

Nếu gặp vấn đề:
1. Kiểm tra Node.js đã cài chưa
2. Kiểm tra dependencies đã install chưa
3. Kiểm tra port có bị chiếm không
4. Xem log lỗi trong terminal

---

**Happy Coding! 🚀**

**P/S:** File này được tạo tự động bởi AI Assistant. Nếu cần thêm endpoint hoặc tùy chỉnh, sửa file `server.js` theo hướng dẫn trong README.md!
