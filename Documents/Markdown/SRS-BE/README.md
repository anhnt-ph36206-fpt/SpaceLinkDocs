# SpaceLink Backend Documentation
**Tech Stack:** Laravel 12 + MySQL + Laravel Sanctum (API Token)  
**Timeline:** 3 tuần (chức năng cơ bản) | 3 tháng (tổng thể)  
**Team:** 1 Backend Lead + 2 Backend Interns

---

## 🚨 BẠN ĐANG BỐI RỐI VỀ SANCTUM?

### 👉 **ĐỌC NGAY:**
1. **[05-sanctum-quick-reference.md](./05-sanctum-quick-reference.md)** - Setup nhanh 5 phút ⚡
2. **[04-sanctum-setup-clarification.md](./04-sanctum-setup-clarification.md)** - Giải thích chi tiết 📚

**Tóm tắt:**
- ✅ Dùng **API Token (Bearer Token)** - Backend và FE riêng biệt
- ❌ KHÔNG dùng **SPA Cookie** - Chỉ cho cùng domain
- ✅ KHÔNG CẦN config `SANCTUM_STATEFUL_DOMAINS`
- ✅ KHÔNG CẦN middleware `EnsureFrontendRequestsAreStateful`

---

## 📚 TÀI LIỆU TỔNG HỢP

### 1. **Q&A Session - Trao đổi ban đầu**
📄 **File:** [`00-qa-session-initial.md`](./00-qa-session-initial.md)

**Nội dung:**
- Tổng hợp tất cả câu hỏi và trả lời từ session tư vấn
- Đánh giá database hiện tại (8.5/10)
- Tech decisions (Sanctum, RESTful API)
- Phân tích team và phân công
- Giải thích real-time features

**Đọc khi nào:**
- ✅ Muốn hiểu context và lý do các quyết định
- ✅ Onboarding thành viên mới vào project

---

### 2. **Backend Roadmap & Strategy - Kế hoạch tổng thể**
📄 **File:** [`01-backend-roadmap-strategy.md`](./01-backend-roadmap-strategy.md)

**Nội dung:**
- Roadmap 3 tuần chi tiết (Week 1, 2, 3)
- Phân công tasks cho từng người
- Database review và đánh giá
- Best practices và critical notes
- Success criteria

**Đọc khi nào:**
- ✅ Lên planning cho sprint/week
- ✅ Cần overview toàn bộ project

---

### 3. **Quick Start Guide Week 1 - Hướng dẫn triển khai**
📄 **File:** [`02-quick-start-week1.md`](./02-quick-start-week1.md)

**Nội dung:**
- Step-by-step implementation cho Week 1
- Code examples đầy đủ (Controllers, Models, Routes)
- Database setup và seeders
- Auth APIs implementation
- Products APIs implementation
- Cart APIs implementation

**Đọc khi nào:**
- ✅ BẮT ĐẦU CODE (Ngày 1)
- ✅ Cần code examples cụ thể

---

### 4. **API Documentation - Tài liệu cho FE team**
📄 **File:** [`03-api-documentation.md`](./03-api-documentation.md)

**Nội dung:**
- Tất cả API endpoints (Auth, Products, Cart)
- Request/Response examples
- Validation rules
- Error handling
- Testing với Postman

**Đọc khi nào:**
- ✅ Gửi cho FE team
- ✅ Test API bằng Postman

---

### 5. **Sanctum Setup Clarification - GIẢI ĐÁP NHẦM LẪN** 🔥
📄 **File:** [`04-sanctum-setup-clarification.md`](./04-sanctum-setup-clarification.md)

**Nội dung:**
- So sánh chi tiết **API Token vs SPA Cookie**
- Giải thích tại sao dùng API Token
- Setup Backend Laravel từng bước
- Setup Frontend ReactJS từng bước
- Flow diagram và code examples đầy đủ
- Troubleshooting

**Đọc khi nào:**
- 🔥 **BẠN ĐANG BỐI RỐI VỀ SANCTUM** (ĐỌC NGAY!)
- ✅ Cần hiểu sâu về 2 cách setup Sanctum
- ✅ Muốn biết config nào cần, config nào KHÔNG cần

---

### 6. **Sanctum Quick Reference - Cheat Sheet** ⚡
📄 **File:** [`05-sanctum-quick-reference.md`](./05-sanctum-quick-reference.md)

**Nội dung:**
- Setup nhanh 5 phút
- Code snippets Backend + Frontend
- Checklist
- Common errors
- Flow diagram

**Đọc khi nào:**
- ⚡ **CẦN SETUP NHANH**
- ✅ Cần nhìn lại flow
- ✅ Debug lỗi Sanctum

---

## 🎯 QUICK NAVIGATION

### Bạn muốn làm gì?

#### 🚨 **Đang bối rối về Sanctum?**
→ Đọc: `05-sanctum-quick-reference.md` (5 phút)  
→ Hoặc: `04-sanctum-setup-clarification.md` (chi tiết)

#### 📖 **Hiểu tổng quan project**
→ Đọc: `01-backend-roadmap-strategy.md`

#### 💻 **Bắt đầu code ngay**
→ Đọc: `02-quick-start-week1.md`

#### 🔍 **Tìm API endpoint cụ thể**
→ Đọc: `03-api-documentation.md`

#### 👥 **Onboarding intern mới**
→ Đọc theo thứ tự: 00 → 01 → 05 → 02

---

## 📅 TIMELINE OVERVIEW

### **Week 1: Foundation & Core APIs** (Ngày 1-7)
**Focus:** Database + Auth + Products + Cart

**Deliverables:**
- ✅ Auth APIs (Register, Login, Logout, Profile)
- ✅ Products APIs (List, Detail, Featured, Best Selling)
- ✅ Brands & Categories APIs
- ✅ Cart APIs (Add, Update, Remove, Get)

**Files cần đọc:**
- `05-sanctum-quick-reference.md` (Setup Auth)
- `02-quick-start-week1.md` (Implementation)
- `03-api-documentation.md` (API specs)

---

### **Week 2: Transaction Flow** (Ngày 8-14)
**Focus:** Checkout + Payment + Orders

**Deliverables:**
- ✅ Checkout API
- ✅ Payment Integration (COD + VNPAY)
- ✅ Order Management APIs
- ✅ Voucher APIs

---

### **Week 3: Admin & Polish** (Ngày 15-21)
**Focus:** Admin APIs + Reviews + Testing

**Deliverables:**
- ✅ Admin: Product Management
- ✅ Admin: Order Management
- ✅ Reviews & Comments APIs
- ✅ Complete Testing

---

## 🔧 TECH STACK

### Backend:
- **Framework:** Laravel 12
- **PHP:** 8.2+
- **Database:** MySQL 8.0
- **Auth:** Laravel Sanctum (API Token)
- **API:** RESTful

### Frontend:
- **Framework:** ReactJS 19
- **Build Tool:** Vite
- **HTTP Client:** Axios
- **Routing:** React Router

### Tools:
- **API Testing:** Postman
- **Version Control:** Git
- **Database Tool:** HeidiSQL / phpMyAdmin

---

## 📊 PROJECT STATUS

### Database: ✅ READY
- 26 bảng đã design
- Migrations đã test
- Seeders cơ bản có sẵn

### APIs: 🚧 IN PROGRESS
- Week 1: Auth + Products + Cart (Đang làm)
- Week 2: Checkout + Orders (Chưa bắt đầu)
- Week 3: Admin + Reviews (Chưa bắt đầu)

### Documentation: ✅ READY
- API Documentation (Week 1)
- Implementation Guide (Week 1)
- Sanctum Setup Guide ⭐ NEW!
- Roadmap & Strategy

---

## 🚀 GETTING STARTED (Ngày mai)

### Backend Lead (Bạn):
1. ✅ Đọc `05-sanctum-quick-reference.md` (5 phút)
2. ✅ Đọc `04-sanctum-setup-clarification.md` (20 phút)
3. ✅ Setup Sanctum theo hướng dẫn
4. ✅ Test Auth APIs bằng Postman
5. ✅ Gửi Postman Collection cho FE team

### Intern 1:
1. ✅ Đọc `00-qa-session-initial.md` (hiểu context)
2. ✅ Đọc `02-quick-start-week1.md` (Day 3-4: Products API)
3. ✅ Setup project local
4. ✅ Test Auth APIs bằng Postman

### Intern 2:
1. ✅ Đọc `02-quick-start-week1.md` (Day 1: Seeders)
2. ✅ Setup project local
3. ✅ Tạo BrandCategorySeeder

---

## 📝 CHANGELOG

### 2026-01-29 (Update - Sanctum Clarification)
- ✅ Created `04-sanctum-setup-clarification.md` (27 KB)
- ✅ Created `05-sanctum-quick-reference.md` (7 KB)
- ✅ Updated `README.md`
- 🎯 **Giải quyết hoàn toàn sự nhầm lẫn về Sanctum!**

### 2026-01-28 (Initial Release)
- ✅ Created `00-qa-session-initial.md`
- ✅ Created `01-backend-roadmap-strategy.md`
- ✅ Created `02-quick-start-week1.md`
- ✅ Created `03-api-documentation.md`

---

## 🎯 SUCCESS CRITERIA

### Week 1 (Ngày 7):
- [ ] FE có thể đăng ký/đăng nhập ✅
- [ ] FE có thể xem danh sách sản phẩm ✅
- [ ] FE có thể thêm sản phẩm vào giỏ hàng ✅
- [ ] Postman Collection v1.0 gửi cho FE ✅

---

## 📂 FILE STRUCTURE

```
SL-SRS/SRS-BE/
├── README.md                              # File này - Navigation tổng hợp
├── 00-qa-session-initial.md               # Q&A session
├── 01-backend-roadmap-strategy.md         # Roadmap 3 tuần
├── 02-quick-start-week1.md                # Implementation guide Week 1
├── 03-api-documentation.md                # API docs cho FE
├── 04-sanctum-setup-clarification.md      # ⭐ Sanctum chi tiết
├── 05-sanctum-quick-reference.md          # ⭐ Sanctum quick setup
└── spacelink-backend-test/                # Test project
```

---

## 🆘 TROUBLESHOOTING

### Issue: Sanctum token không hoạt động
**Solution:** 
1. Check User Model có `use HasApiTokens`
2. Check header: `Authorization: Bearer {token}` (có space)
3. Đọc `05-sanctum-quick-reference.md` → Common Errors

### Issue: CORS Error
**Solution:** 
```php
// config/cors.php
'allowed_origins' => ['http://localhost:5173'],
'supports_credentials' => false, // ✅ false!
```

### Issue: Migration failed
**Solution:** Check `02-quick-start-week1.md` → Day 1 → Database Setup

---

## 💡 TIPS & BEST PRACTICES

### 1. **Sanctum Setup:**
- ✅ Dùng API Token (Bearer Token)
- ❌ KHÔNG dùng SPA Cookie
- ❌ KHÔNG config `SANCTUM_STATEFUL_DOMAINS`
- ✅ Frontend lưu token vào `localStorage`

### 2. **Code Review Checklist:**
- [ ] Validation đầy đủ?
- [ ] Response format chuẩn?
- [ ] HTTP status code đúng?
- [ ] Có handle error?
- [ ] Có test bằng Postman?

### 3. **Git Workflow:**
```bash
git checkout -b feature/auth-api
git add .
git commit -m "feat: implement login API"
git push origin feature/auth-api
```

---

## 📞 SUPPORT

**Khi gặp vấn đề:**
1. **Tự research:** Google, Laravel Docs (15 phút)
2. **Check docs:** Đọc lại file tương ứng
3. **Hỏi team:** Slack (30 phút)
4. **Escalate:** Nếu block quá 1 giờ

**Resources:**
- Laravel Docs: https://laravel.com/docs/12.x
- Sanctum Docs: https://laravel.com/docs/12.x/sanctum
- Stack Overflow: https://stackoverflow.com/questions/tagged/laravel

---

**Created:** 2026-01-28  
**Last Updated:** 2026-01-29  
**Version:** 1.1  
**Maintainer:** Backend Lead

---

**🚀 LET'S BUILD SOMETHING GREAT! 🚀**
