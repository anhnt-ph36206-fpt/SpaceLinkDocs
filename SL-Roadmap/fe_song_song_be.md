# FE LÀM GÌ KHI BE ĐANG PHÂN TÍCH?  
## Lộ trình Frontend làm song song Backend cho dự án website bán hàng (MVP)

---

## 1. Tư duy đúng: FE KHÔNG BAO GIỜ NGỒI CHỜ BE

> ❌ Sai lầm phổ biến của sinh viên:
> "BE chưa xong thì FE chưa làm được gì"

> ✅ Tư duy đúng (giống doanh nghiệp thật):
> **FE và BE làm song song – chỉ chờ nhau ở data contract**

Trong 4 tháng làm đồ án, nếu FE chờ BE:
- Mất 30–40% thời gian
- Giai đoạn cuối dồn việc → dễ lỗi, khó bảo vệ

---

## 2. Tổng quan việc FE có thể làm song song

| Giai đoạn BE | FE làm gì song song |
|------------|--------------------|
| Khảo sát nghiệp vụ | Phân tích UI flow, user flow |
| Viết SRS | Tách màn hình từ chức năng |
| Vẽ Use Case | Vẽ wireframe từng trang |
| Thiết kế DB | Fake API + mock data |
| Chưa có API | Code UI + logic FE |

---

## 3. FE làm gì CỤ THỂ theo từng bước

---

## 3.1 Phân tích chức năng → tách màn hình (RẤT QUAN TRỌNG)

Từ SRS / Use Case, FE phải tự hỏi:

> "Người dùng sẽ nhìn thấy BAO NHIÊU màn hình?"

### Ví dụ với actor Khách hàng:

| Chức năng | Màn hình FE |
|---------|------------|
| Xem sản phẩm | Trang danh sách |
| Xem chi tiết | Trang chi tiết |
| Giỏ hàng | Cart page |
| Đặt hàng | Checkout |
| Theo dõi đơn | Order tracking |

➡️ **Output bắt buộc của FE**: danh sách màn hình

---

## 3.2 Vẽ Wireframe (KHÔNG CẦN ĐẸP)

### Mục tiêu wireframe:
- Xác định bố cục
- Xác định component
- Không quan tâm màu sắc

### Công cụ gợi ý:
- Figma
- Draw.io
- Penpot

### Wireframe tối thiểu cần có:
- Trang chủ
- Trang danh sách sản phẩm
- Trang chi tiết sản phẩm
- Giỏ hàng
- Checkout
- Admin dashboard

---

## 3.3 Fake API – điều thầy bạn đang nói tới

> "Fake API" = tạo API giả để FE code trước

### Cách đơn giản nhất (RẤT PHỔ BIẾN):

#### Option 1: JSON Server
- Tạo file `db.json`
- Chạy server fake REST API

Ví dụ data:
- products
- categories
- orders

#### Option 2: Mock API online
- mockapi.io
- beeceptor
- postman mock server

➡️ FE gọi API như thật:
```
GET /products
GET /products/1
POST /orders
```

---

## 3.4 Thống nhất DATA CONTRACT với BE (KHÔNG ĐỢI DB XONG)

FE & BE cần thống nhất sớm:

```json
{
  "id": 1,
  "name": "iPhone 15",
  "price": 25000000,
  "thumbnail": "url",
  "stock": 10
}
```

📌 **Đây là điểm rất chuyên nghiệp**, giảng viên đánh giá cao.

---

## 3.5 Code UI song song với Fake API

FE hoàn toàn có thể:
- Build layout
- Build component
- Xử lý state
- Validate form

### Những phần FE làm trước BE rất tốt:
- Form checkout
- Validate dữ liệu
- UX giỏ hàng
- Pagination
- Search / filter

---

## 3.6 Tìm & phân tích UI template (KHÔNG COPY MÙ QUÁNG)

### FE nên làm gì với template:
- Tham khảo layout
- Học cách bố trí
- Áp dụng cho wireframe

### Gợi ý nguồn:
- ThemeForest
- Dribbble
- Behance
- Mẫu của MobileCity

❌ Không nên:
- Copy nguyên UI
- Dùng template quá phức tạp

---

## 4. Timeline FE song song BE (chuẩn đồ án)

### Tháng 1
- Phân tích chức năng
- Tách màn hình
- Vẽ wireframe
- Chọn UI style

### Tháng 2
- Fake API
- Code layout
- Code component
- Hoàn thiện UI flow

### Tháng 3
- Kết nối API thật
- Fix logic
- Responsive

### Tháng 4
- Test
- Fix bug
- Chuẩn bị demo

---

## 5. Câu trả lời MẪU nếu giảng viên hỏi FE làm gì khi BE chưa xong

> "Nhóm em cho FE làm song song bằng cách phân tích chức năng từ SRS, thiết kế wireframe, sử dụng fake API để code UI và xử lý logic trước. Khi BE hoàn thiện API thật thì chỉ cần thay endpoint."

➡️ **Câu này nói ra là biết làm dự án thật.**

---

## 6. Checklist FE (bạn có thể tick từng dòng)

- [ ] Tách màn hình từ SRS
- [ ] Vẽ wireframe
- [ ] Chọn UI style
- [ ] Fake API
- [ ] Code UI
- [ ] Thống nhất data contract
- [ ] Kết nối API thật

---

## 7. Bước tiếp theo nên làm

👉 Nếu bạn muốn, mình có thể làm tiếp:
1. **Danh sách màn hình FE chi tiết cho từng actor**
2. **Wireframe text-based (dễ vẽ lại trong Figma)**
3. **Cấu trúc thư mục FE (React / Vue)**
4. **Data contract chuẩn FE–BE**

Chỉ cần nói:  
👉 *"Làm tiếp phần wireframe + màn hình FE"*

