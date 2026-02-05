## 02 - Nên dùng 27 hay 46 bảng?

### 1. Phân biệt mục tiêu học vs mục tiêu sản phẩm

- **27 bảng** = nhóm chức năng **bắt buộc** theo yêu cầu đề bài/SRS.
- **46 bảng** = full schema bao gồm cả **tính năng nâng cao / có thể làm thêm**.

Vì vậy:

- Nếu mục tiêu chính hiện tại là **hoàn thành đúng yêu cầu mandatory, dễ kiểm soát**, thì:
  - Nên ưu tiên **implement chuẩn 27 bảng trước**.
- Nếu mục tiêu là **build nền tảng lâu dài, gần sản phẩm thật**, thì:
  - Cần **thiết kế với mindset 46 bảng**, nhưng có thể triển khai theo **phase**.

### 2. Đề xuất chiến lược thực tế

**Gợi ý cân bằng (khuyên dùng):**

1. **Phase 1 – 27 bảng bắt buộc**
   - Viết migrations + models + relations **chỉ cho 27 bảng**.
   - Tập trung hoàn thành luồng core: Users, Auth, Products, Cart, Orders, Payments cơ bản…
2. **Phase 2 – Mở rộng dần lên 46 bảng**
   - Khi core đã ổn, bắt đầu thêm các bảng còn lại (reviews, logs, vouchers nâng cao…).
   - Mỗi lần thêm, tạo **migrations mới** chứ không sửa trực tiếp file cũ (để giữ lịch sử).

Lợi ích:

- Dễ debug và học: số bảng ít hơn, ít foreign key hơn, migrations nhẹ hơn.
- Vẫn tôn trọng thiết kế 46 bảng, vì **schema gốc** luôn nằm trong file SQL và docs – bạn chỉ “triển khai dần”.

### 3. Khi nào nên triển khai đủ 46 bảng ngay?

- Bạn:
  - Đã rất nắm rõ schema.
  - Tự tin với migrations, rollback, migrate:fresh…
  - Muốn luyện cảm giác “build sản phẩm thật” ngay từ đầu.
- Khi đó có thể:
  - **Viết migrations cho toàn bộ 46 bảng**, nhưng:
    - Chỉ code business logic/API cho **nhóm tính năng mandatory** trước.
    - Các bảng nâng cao có thể **chưa dùng tới** cho đến phase sau.

Tóm lại:  
- **Cho bài tập + dễ hoàn thành** → Phase 1: 27 bảng, Phase 2: thêm dần.  
- **Cho sản phẩm dài hơi** → migrations đủ 46 bảng, nhưng roadmap code vẫn theo từng phase như trên.

