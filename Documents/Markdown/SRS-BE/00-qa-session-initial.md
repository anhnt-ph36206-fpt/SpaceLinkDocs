# Q&A Session: Backend Strategy & Planning
**Date:** 2026-01-28  
**Participants:** Backend Lead (User) + Senior Dev AI (10 years exp)  
**Topic:** Backend development strategy cho SpaceLink E-Commerce

---

## 📋 CONTEXT

**Tình huống:**
- User đang lead 1 team BE rất yếu (2 interns)
- Phải đảm nhận cả BE và FE trước đây → quá tải
- Sau meeting với giáo viên: Tập trung BE, FE có người khác đảm nhận
- Timeline: 3 tuần cho chức năng cơ bản, 3 tháng tổng thể
- Tech stack: Laravel 12 + ReactJS 19

---

## ❓ QUESTION 1: Về Database hiện tại

### User hỏi:
> "Database hiện tại của tôi như thế nào? Đã có file SQL thuần 26-27 bảng, đã convert sang migrations và chạy ngon. Đánh giá và cho biết thiếu sót?"

### Senior Dev trả lời:

#### ✅ **Đánh giá tổng quan: 8.5/10 - RẤT TỐT**

**Điểm mạnh:**
1. **Cấu trúc logic rõ ràng:** Chia 6 phần (Users, Products, Orders, Reviews, Content, System)
2. **Relationships đầy đủ:** Foreign keys, indexes hợp lý
3. **Soft Delete:** Có `deleted_at` cho bảng quan trọng (products, categories, users)
4. **Audit Trail:** `created_at`, `updated_at` đầy đủ
5. **Flexible Design:**
   - Product Variants (màu sắc, RAM, dung lượng)
   - Voucher system (percent/fixed discount)
   - Guest cart support (`session_id`)
   - Order status tracking
6. **Business Logic:**
   - Roles & Permissions (RBAC)
   - Order status history
   - Payment transactions tracking
   - Reviews chỉ cho người đã mua

**Điểm cần lưu ý:**
1. **Product Variants:** Cần validate kỹ logic khi add to cart (check variant_id)
2. **Order Status Flow:** Cần define rõ state machine
3. **Stock Management:** Cần dùng DB transaction khi checkout
4. **Voucher Validation:** Cần check date, quantity, min_order_amount
5. **Payment Callback:** Cần handle VNPAY/MOMO webhook

**Thiếu sót nhỏ:**
- ⚠️ Bảng `user_addresses` có trong SQL nhưng cần verify trong migrations
- ⚠️ Bảng `password_reset_tokens` cần check
- ℹ️ Chưa có bảng `notifications` (nếu cần real-time - làm sau)

**Kết luận:** Database design rất tốt, đủ cho yêu cầu bắt buộc. Không cần sửa gì lớn.

---

## ❓ QUESTION 2: Về trạng thái Backend hiện tại

### User hỏi:
> "Laravel 12 đã setup cơ bản, có 3 API controllers (Brands, Categories, Products). Auth chưa có. Sanctum đã cài chưa? Nên dùng gì?"

### Senior Dev trả lời:

#### ✅ **Laravel Sanctum - Lựa chọn TỐT NHẤT**

**Lý do:**
1. ✅ **Đã built-in Laravel 12** (check `composer.json` → có `laravel/sanctum: ^4.0`)
2. ✅ **Đơn giản nhất** cho team yếu
3. ✅ **Phù hợp SPA** (ReactJS frontend)
4. ✅ **Token-based, stateless**
5. ✅ **Không cần config phức tạp**

**So sánh alternatives:**

| Feature | Sanctum ✅ | Passport ❌ | JWT ❌ |
|---------|-----------|------------|--------|
| Độ phức tạp | Thấp | Cao | Trung bình |
| Setup time | 10 phút | 30 phút | 20 phút |
| Built-in Laravel | Yes | Yes | No (cần package) |
| Phù hợp SPA | Yes | Yes | Yes |
| Phù hợp team yếu | Yes | No | No |
| OAuth2 support | No | Yes | No |

**Quyết định:** Dùng **Laravel Sanctum**

**Setup steps:**
```bash
# 1. Publish config (optional)
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# 2. Run migration
php artisan migrate

# 3. Add middleware to api routes (đã có sẵn)
```

---

## ❓ QUESTION 3: Về team và phân công

### User hỏi:
> "2 BE còn lại skill ở mức intern, CRUD cơ bản chưa vững, không biết API/Auth/Middleware. Họ có thể làm gì? Timeline 3 tuần cho chức năng cơ bản."

### Senior Dev trả lời:

#### 📊 **Phân tích skill level:**

**Intern 1 (Mạnh hơn):**
- ✅ Có thể: Simple CRUD, Migrations, Seeders
- ⚠️ Cần học: API Resources, Eloquent Relationships, Validation
- 🎯 **Giao việc:** Products API, Order Management API

**Intern 2 (Yếu hơn):**
- ✅ Có thể: Basic CRUD, Seeders
- ⚠️ Cần học: API basics, Request handling
- 🎯 **Giao việc:** Brands/Categories API, Voucher API, Simple statistics

#### 📅 **Phân công 3 tuần:**

**Week 1: Foundation**
- **Lead:** Auth system + Cart API (complex logic)
- **Intern 1:** Products API (pair với Lead)
- **Intern 2:** Brands & Categories API (simple CRUD)

**Week 2: Transaction Flow**
- **Lead:** Checkout + Payment integration
- **Intern 1:** Order Management API
- **Intern 2:** Voucher API

**Week 3: Polish**
- **Lead:** Admin APIs + Code review
- **Intern 1:** Reviews & Comments API
- **Intern 2:** Dashboard statistics

#### 🎓 **Mentoring strategy:**
- **Daily standup:** 15 phút/ngày
- **Pair programming:** 
  - Intern 1: 2 sessions/week (1h)
  - Intern 2: 3 sessions/week (1h)
- **Code review:** Mỗi PR phải review trước khi merge

---

## ❓ QUESTION 4: Về luồng chính bán hàng

### User hỏi:
> "Luồng chính bán hàng gồm những gì? User đã đăng nhập xem sản phẩm, thêm giỏ hàng, đặt mua, thanh toán, check lịch sử. Còn khách vãng lai thì sao?"

### Senior Dev trả lời:

#### 🛒 **Luồng chính (User đã login) - PRIORITY 1:**

```
1. Browse Products
   ↓
2. View Product Detail
   ↓
3. Add to Cart (validate stock)
   ↓
4. View Cart (update quantity, remove items)
   ↓
5. Checkout (fill shipping info, select payment)
   ↓
6. Payment (COD / VNPAY)
   ↓
7. Order Confirmation (email + order code)
   ↓
8. Order Tracking (view status, cancel if pending)
   ↓
9. Order Completed → Review Product
```

**APIs cần implement:**
- ✅ Products: List, Detail, Search, Filter
- ✅ Cart: Add, Update, Remove, Get
- ✅ Checkout: Create Order, Validate Stock
- ✅ Payment: COD, VNPAY callback
- ✅ Orders: List, Detail, Cancel
- ✅ Reviews: Create (after delivered)

#### 👤 **Luồng phụ (Guest user) - PRIORITY 2:**

**Option 1: Bắt buộc login để checkout** ⭐ **KHUYÊN DÙNG**
- ✅ Đơn giản hơn
- ✅ Dễ quản lý đơn hàng
- ✅ Phù hợp team yếu
- ❌ UX kém hơn một chút

**Option 2: Guest checkout** (Khó hơn)
- ✅ UX tốt hơn
- ❌ Phức tạp (cần track session, merge cart khi login)
- ❌ Khó quản lý lịch sử đơn hàng

**Quyết định:** 
- **Week 1-3:** Chỉ làm Option 1 (bắt buộc login)
- **Week 4+:** Nếu còn thời gian, làm thêm Guest checkout

**Guest Cart (Giải pháp tạm):**
```php
// Cho phép guest add to cart (dùng session_id)
// Nhưng khi checkout → bắt buộc login
if (!auth()->check()) {
    return response()->json([
        'success' => false,
        'message' => 'Vui lòng đăng nhập để đặt hàng',
        'redirect' => '/login'
    ], 401);
}
```

---

## ❓ QUESTION 5: FE team cần API nào trước?

### User hỏi:
> "FE team cũng lơ mơ, chưa biết cần API nào. Họ đang dùng db.json. Thầy bảo làm auth trước. Theo bạn thì sao?"

### Senior Dev trả lời:

#### 📊 **Priority Matrix:**

| Priority | API Group | Lý do | Timeline |
|----------|-----------|-------|----------|
| **P0** | Auth APIs | FE cần làm login/register page trước | Ngày 1-2 |
| **P0** | Products APIs | FE cần data để làm trang chủ, listing | Ngày 2-4 |
| **P1** | Brands & Categories | FE cần cho menu, filter | Ngày 4-5 |
| **P0** | Cart APIs | FE cần làm giỏ hàng | Ngày 5-7 |
| **P0** | Checkout & Orders | FE cần làm luồng đặt hàng | Ngày 8-14 |
| **P2** | Reviews, Admin | Làm sau | Ngày 15+ |

#### 📅 **Delivery Schedule cho FE:**

**Ngày 1 (Sáng):**
```
✅ Gửi FE: Postman Collection v0.1
- Auth endpoints (Register, Login) - MOCK DATA
- Base URL, response format
```

**Ngày 2 (Chiều):**
```
✅ Gửi FE: Postman Collection v0.5
- Auth endpoints (WORKING)
- Products endpoints (List, Detail) - WORKING
```

**Ngày 5:**
```
✅ Gửi FE: Postman Collection v1.0
- Auth ✅
- Products ✅
- Brands & Categories ✅
- Cart ✅
```

**Ngày 10:**
```
✅ Gửi FE: Postman Collection v2.0
- Checkout ✅
- Orders ✅
- Payment ✅
```

#### 💬 **Communication Protocol:**

**Daily:**
- Morning: Slack message về progress
- Evening: Update Postman collection nếu có API mới

**Weekly:**
- Monday: Planning meeting (30 phút)
- Friday: Demo + sync issues (1 giờ)

**Khi có API mới:**
1. Update Postman Collection
2. Ping FE team trên Slack
3. Gửi kèm example request/response

---

## ❓ QUESTION 6: Real-time features là gì?

### User hỏi:
> "Bạn nhắc đến real-time features: notifications, inventory update. Đó là gì? Có cần làm không?"

### Senior Dev trả lời:

#### 🔔 **1. Notifications (Thông báo real-time)**

**Ví dụ use case:**
- Admin đang xem dashboard → User đặt hàng → Popup "Có đơn hàng mới #SL-20260128-0001"
- User đang xem đơn hàng → Admin cập nhật trạng thái → Popup "Đơn hàng đã được xác nhận"

**Cách implement:**

**Option A: WebSocket (Real-time thật)**
```
Laravel Broadcasting + Pusher/Laravel Reverb
- Pros: Real-time thật (< 1s delay)
- Cons: Phức tạp, cần setup server WebSocket
```

**Option B: Polling (Fake real-time)**
```javascript
// FE gọi API mỗi 30 giây
setInterval(() => {
  fetch('/api/notifications/unread')
    .then(res => res.json())
    .then(data => {
      if (data.count > 0) {
        showNotification(data.notifications);
      }
    });
}, 30000);
```

#### 📦 **2. Inventory Update (Cập nhật tồn kho real-time)**

**Ví dụ use case:**
- User A đang xem iPhone 15 Pro (còn 5 cái)
- User B mua 3 cái
- Màn hình User A tự động hiện "Còn 2 cái" (không cần refresh)

**Cách implement:**

**Option A: WebSocket**
```php
// Khi có order mới
event(new ProductStockUpdated($productId, $newStock));

// FE listen
Echo.channel('products')
  .listen('ProductStockUpdated', (e) => {
    updateStockDisplay(e.productId, e.newStock);
  });
```

**Option B: Polling**
```javascript
// FE gọi API mỗi 10 giây khi đang xem product detail
setInterval(() => {
  fetch(`/api/products/${productId}/stock`)
    .then(res => res.json())
    .then(data => updateStockDisplay(data.stock));
}, 10000);
```

#### 🎯 **Quyết định cho project này:**

| Feature | Week 1-3 | Week 4+ | Lý do |
|---------|----------|---------|-------|
| Notifications | ❌ KHÔNG | ✅ Polling | Không phải yêu cầu bắt buộc |
| Inventory Update | ❌ KHÔNG | ⚠️ Có thể | Validate khi checkout là đủ |

**Lý do KHÔNG làm real-time ngay:**
1. ❌ Phức tạp, team yếu
2. ❌ Không phải yêu cầu bắt buộc
3. ❌ Timeline chặt (3 tuần)
4. ✅ Alternative đơn giản: Validate stock khi checkout (đủ tốt)

**Khi nào làm:**
- ✅ Sau khi hoàn thành 26 chức năng bắt buộc
- ✅ Khi team đã vững hơn
- ✅ Khi có thời gian (tuần 4+)

---

## ❓ QUESTION 7: Về tài liệu và resources

### User hỏi:
> "Tôi có file SQL, file Excel sheets chức năng, file SRS. Bạn cần gì để đánh giá?"

### Senior Dev trả lời:

#### 📂 **Tài liệu đã nhận:**

1. ✅ **DB cơ bản (SQL thuần):**
   - Path: `D:\...\SL-SQL-Database\import-sql\new-claude-sl_db.sql`
   - Đánh giá: 26 bảng, cấu trúc tốt, đầy đủ

2. ✅ **DB đã migrate (SQL export):**
   - Path: `D:\...\SpaceLink-API-Test\backend\database\spacelink-api-ai-test-code.sql`
   - Đánh giá: Đã test migrations, chạy ngon

3. ✅ **File Excel chức năng:**
   - Path: `D:\...\SL-Docs\SL-Excel\SpaceLink.xlsx`
   - Đánh giá: 36 chức năng, phân loại rõ (Bắt buộc, Có thể làm, Nâng cao)

4. ✅ **File CSV chức năng:**
   - Path: `D:\...\SL-Docs\SL-Excel\SpaceLink - Chức năng.csv`
   - Đánh giá: Chi tiết luồng, input/output

5. ✅ **Google Sheets online:**
   - Link: https://docs.google.com/spreadsheets/d/1gG2pAj_kSiUJRh9NFiSd2AXv-JNMYY_8AwwhgRWVHT4/...
   - Đánh giá: Cột màu xanh = Yêu cầu bắt buộc

#### 📊 **Phân tích chức năng từ Excel:**

**Yêu cầu bắt buộc (26 chức năng):**

| STT | Chức năng | Độ khó | Owner | Week |
|-----|-----------|--------|-------|------|
| 1 | Đăng nhập hệ thống | Trung bình | Lead | 1 |
| 2 | Đăng ký tài khoản | Trung bình | Lead | 1 |
| 4 | Quản lý thông tin user | Dễ | Intern 1 | 1 |
| 5 | Trang chủ (sản phẩm) | Dễ | Intern 1 | 1 |
| 6 | Danh sách sản phẩm (filter, search) | Trung bình | Lead + Intern 1 | 1 |
| 9 | Chi tiết sản phẩm | Dễ | Intern 1 | 1 |
| 10 | Bình luận sản phẩm | Trung bình | Intern 1 | 3 |
| 11 | Đánh giá sản phẩm | Trung bình | Intern 1 | 3 |
| 12 | Quản lý giỏ hàng | Trung bình | Lead | 1 |
| 13 | Thanh toán | Khó | Lead | 2 |
| 16 | Lịch sử đơn hàng | Dễ | Intern 1 | 2 |
| 17 | Chi tiết đơn hàng | Dễ | Intern 1 | 2 |
| 19 | Đánh giá sau khi mua | Trung bình | Intern 1 | 3 |
| 20 | Thống kê (Admin) | Trung bình | Intern 2 | 3 |
| 21 | Quản lý Danh mục | Dễ | Intern 2 | 1 |
| 22 | Quản lý Sản phẩm | Trung bình | Lead | 3 |
| 23 | Quản lý Biến thể | Khó | Lead | 3 |
| 24 | Quản lý Đơn hàng | Trung bình | Lead | 3 |
| 26 | Quản lý Voucher | Trung bình | Intern 2 | 2 |
| 27 | Quản lý Bình luận | Dễ | Intern 2 | 3 |
| 33 | Quản lý User | Dễ | Intern 2 | 3 |

**Có thể làm (nếu còn thời gian):**
- Banner management
- Tin tức
- Kho hàng nâng cao

**Nâng cao (làm sau):**
- Phân quyền phức tạp
- Thông báo real-time
- Chat real-time
- Hoàn hàng

#### ✅ **Kết luận:**

Tài liệu đầy đủ, chi tiết. Có thể bắt đầu implementation ngay.

---

## 🎯 SUMMARY & ACTION ITEMS

### ✅ **Decisions Made:**

1. **Auth:** Laravel Sanctum
2. **API Style:** RESTful
3. **Database:** Giữ nguyên 26 bảng
4. **Guest Checkout:** Không làm trong 3 tuần đầu
5. **Real-time:** Không làm trong 3 tuần đầu
6. **Timeline:** 3 tuần cho chức năng bắt buộc

### 📋 **Next Steps (Ngày mai):**

**Backend Lead:**
- [ ] Review file roadmap chi tiết (`01-backend-roadmap-strategy.md`)
- [ ] Setup Sanctum trong project chính
- [ ] Tạo Postman Collection template
- [ ] Meeting với FE team (sync API needs)
- [ ] Assign tasks cho 2 interns

**Intern 1:**
- [ ] Đọc Laravel API Resources docs
- [ ] Setup project local
- [ ] Chạy migrations + seeders
- [ ] Test 3 API hiện có bằng Postman

**Intern 2:**
- [ ] Đọc Laravel Eloquent basics
- [ ] Setup project local
- [ ] Chạy migrations + seeders
- [ ] Tạo BrandSeeder với 10 brands

### 📚 **Documents Created:**

1. ✅ `00-qa-session-initial.md` - Q&A tổng hợp (file này)
2. ✅ `01-backend-roadmap-strategy.md` - Roadmap chi tiết 3 tuần

### 📞 **Communication:**

**Daily Standup:** 9:00 AM (15 phút)
**Weekly Sync:** Thứ 6, 4:00 PM (1 giờ)
**Slack Channel:** #backend-team

---

**Session End:** 2026-01-28 23:49  
**Duration:** ~30 phút  
**Status:** ✅ Complete
