# 📅 TUẦN 1: FOUNDATION + ADMIN BASIC

**Timeline:** Day 1-7  
**Focus:** Setup môi trường, Database, Auth, Admin CRUD cơ bản

---

## 🎯 MỤC TIÊU TUẦN 1

- ✅ Import database và verify data
- ✅ Tạo tất cả migrations (27 bảng)
- ✅ Tạo tất cả models với relationships
- ✅ Setup authentication API (Sanctum)
- ✅ Hoàn thành 3 admin modules: **Brands, Categories, Products (basic)**

**Kết quả mong đợi:** Admin có thể quản lý sản phẩm cơ bản, Frontend có API để test

---

## 📋 LỊCH CHI TIẾT

### **DAY 1-2: DATABASE & MIGRATIONS** (⭐⭐ Dễ)

#### **Morning (4h):**
- [ ] Import SQL vào MySQL
- [ ] Verify tables và data mẫu
- [ ] Setup Laravel project (hoặc check project có sẵn)
- [ ] Config `.env` file

#### **Afternoon (4h):**
- [ ] Tạo 27 migration files
- [ ] Test `php artisan migrate:fresh`
- [ ] Fix lỗi nếu có

**Deliverable:** Database hoạt động, migrations OK

**Chi tiết:** Xem `features/01_database_and_migrations.md`

---

### **DAY 3: MODELS & RELATIONSHIPS** (⭐⭐⭐ Trung bình)

#### **Morning (4h):**
- [ ] Tạo Simple Models (6 models)
  - Role, Permission, Brand, AttributeGroup, Setting, Contact
- [ ] Define fillable, casts, dates
- [ ] Test với tinker

#### **Afternoon (4h):**
- [ ] Tạo Medium Models (6 models)
  - User, UserAddress, Category, Attribute, News, Voucher
- [ ] Define relationships
- [ ] Test relationships với tinker

**Deliverable:** 12/27 models xong

**Chi tiết:** Xem `features/02_models_and_relationships.md`

---

### **DAY 4: MODELS (CONT.) + AUTHENTICATION** (⭐⭐⭐⭐ Khó)

#### **Morning (4h):**
- [ ] Tạo Complex Models (15 models)
  - Product, ProductImage, ProductVariant, Cart, Order, Review, Comment...
- [ ] Define relationships phức tạp
- [ ] Test relationships

#### **Afternoon (4h):**
- [ ] Install Laravel Sanctum
- [ ] Setup Sanctum config
- [ ] Create AuthController
- [ ] Test Login/Register API

**Deliverable:** 
- 27/27 models hoàn chỉnh
- Auth API hoạt động

**Chi tiết:** Xem `features/03_authentication.md`

---

### **DAY 5: ADMIN - BRANDS** (⭐⭐⭐⭐⭐ Trung bình)

#### **Morning (3h):**
- [ ] Create BrandController
- [ ] Create StoreBrandRequest, UpdateBrandRequest
- [ ] Create BrandResource
- [ ] Setup routes

#### **Afternoon (3h):**
- [ ] Test all CRUD endpoints với Postman:
  - GET /api/v1/admin/brands
  - POST /api/v1/admin/brands
  - GET /api/v1/admin/brands/{id}
  - PUT /api/v1/admin/brands/{id}
  - DELETE /api/v1/admin/brands/{id}
- [ ] Document API trong Postman
- [ ] Commit code

**Deliverable:** 
- Brands CRUD hoàn chỉnh
- 5 API endpoints
- Template cho các modules khác

**Chi tiết:** Xem `features/04_admin_brands.md`

---

### **DAY 6: ADMIN - CATEGORIES** (⭐⭐⭐⭐⭐⭐ Khó)

#### **Morning (3h):**
- [ ] Create CategoryController
- [ ] Handle parent-child relationship
- [ ] Create validation requests
- [ ] Create CategoryResource (với children)

#### **Afternoon (3h):**
- [ ] Test CRUD với parent-child
- [ ] Test validation: không xóa nếu còn products
- [ ] Test recursive query
- [ ] Document API

**Deliverable:** 
- Categories CRUD với tree structure
- 5+ API endpoints

**Chi tiết:** Xem `features/05_admin_categories.md`

---

### **DAY 7: ADMIN - PRODUCTS (BASIC)** (⭐⭐⭐⭐⭐⭐⭐ Khó)

#### **Morning (4h):**
- [ ] Create ProductController (chưa có variants)
- [ ] Create StoreProductRequest, UpdateProductRequest
- [ ] Create ProductResource
- [ ] Handle soft delete

#### **Afternoon (4h):**
- [ ] Create ProductImageController
- [ ] Handle multiple image upload
- [ ] Test CRUD products
- [ ] Test image upload/delete
- [ ] Test soft delete

**Deliverable:** 
- Products CRUD (chưa variants)
- Image upload working
- 6+ API endpoints

**Chi tiết:** Xem `features/06_admin_products.md`

---

## ✅ CHECKLIST TUẦN 1

### **Infrastructure:**
- [ ] Database imported successfully
- [ ] 27 migrations created and tested
- [ ] All migrations run without errors
- [ ] Data mẫu có trong DB

### **Models:**
- [ ] 27 models created
- [ ] All relationships defined
- [ ] Fillable, casts, dates configured
- [ ] Tested với tinker

### **Authentication:**
- [ ] Sanctum installed and configured
- [ ] Register endpoint working
- [ ] Login endpoint working
- [ ] Logout endpoint working
- [ ] `/me` endpoint working
- [ ] Token authentication tested

### **Admin - Brands:**
- [ ] List brands (pagination, search, filter)
- [ ] Create brand (with validation)
- [ ] Show brand detail
- [ ] Update brand
- [ ] Delete brand
- [ ] Slug auto-generation

### **Admin - Categories:**
- [ ] List categories (tree structure)
- [ ] Create category (với parent_id)
- [ ] Show category with children
- [ ] Update category
- [ ] Delete category (validate: no products)
- [ ] Soft delete working

### **Admin - Products:**
- [ ] List products (pagination, filter, search)
- [ ] Create product (basic fields)
- [ ] Upload multiple images
- [ ] Show product with images
- [ ] Update product
- [ ] Delete images
- [ ] Soft delete products

### **Testing & Documentation:**
- [ ] Postman collection created
- [ ] All endpoints tested
- [ ] API documentation updated
- [ ] Code committed to Git

---

## 📊 METRICS TUẦN 1

**Expected Output:**
- ✅ 27 tables in database
- ✅ 27 models with relationships
- ✅ 4 auth endpoints
- ✅ 15+ admin endpoints
- ✅ 3 admin modules completed

**Code Statistics:**
- Migrations: ~27 files
- Models: ~27 files
- Controllers: ~4 files (Auth, Brand, Category, Product)
- Requests: ~6 files
- Resources: ~3 files
- Total: ~70 files

---

## 🚀 HANDOFF TO WEEK 2

**Completed:**
- ✅ Database ready
- ✅ Authentication working
- ✅ Admin can manage: Brands, Categories, Products (basic)

**Next Week Goals:**
- Product Variants & Attributes
- User Management
- Order Management
- Dashboard Statistics

**Frontend Team:**
- Có thể bắt đầu consume Auth API
- Có thể test Brands, Categories, Products API
- Có API documentation trong Postman

---

**Last updated:** 2026-01-27
