# ✅ MASTER CHECKLIST - SPACELINK E-COMMERCE

**Project:** SpaceLink Backend API  
**Duration:** 3 weeks (21 days)  
**Last updated:** 2026-01-27

---

## 📊 PROGRESS OVERVIEW

| Week | Focus | Progress | Status |
|------|-------|----------|--------|
| Week 1 | Foundation + Admin Basic | 0/7 days | ⏳ Not Started |
| Week 2 | Admin Advanced | 0/7 days | ⏳ Not Started |
| Week 3 | Client API | 0/7 days | ⏳ Not Started |

**Overall:** 0% Complete

---

## 🎯 WEEK 1: FOUNDATION + ADMIN BASIC

### **Day 1-2: Database & Migrations** [⏳ Not Started]

#### Setup
- [ ] Import `new-claude-sl_db.sql` vào MySQL
- [ ] Verify 27 tables created
- [ ] Verify data mẫu (roles, permissions, brands, categories, attributes)
- [ ] Create Laravel project hoặc verify existing
- [ ] Config `.env` (DB_DATABASE=spacelink_db)
- [ ] Test connection: `php artisan tinker` → `DB::connection()->getPdo()`

#### Migrations
- [ ] Create migration: `roles`
- [ ] Create migration: `permissions`
- [ ] Create migration: `role_permissions`
- [ ] Create migration: `users`
- [ ] Create migration: `user_addresses`
- [ ] Create migration: `password_reset_tokens`
- [ ] Create migration: `brands`
- [ ] Create migration: `categories`
- [ ] Create migration: `attribute_groups`
- [ ] Create migration: `attributes`
- [ ] Create migration: `products`
- [ ] Create migration: `product_images`
- [ ] Create migration: `product_variants`
- [ ] Create migration: `product_variant_attributes`
- [ ] Create migration: `product_views`
- [ ] Create migration: `cart`
- [ ] Create migration: `vouchers`
- [ ] Create migration: `orders`
- [ ] Create migration: `order_items`
- [ ] Create migration: `order_status_history`
- [ ] Create migration: `payment_transactions`
- [ ] Create migration: `reviews`
- [ ] Create migration: `comments`
- [ ] Create migration: `comment_reports`
- [ ] Create migration: `news`
- [ ] Create migration: `contacts`
- [ ] Create migration: `settings`
- [ ] Test: `php artisan migrate:fresh`
- [ ] Verify: All 27 tables created without errors

---

### **Day 3: Simple & Medium Models** [⏳ Not Started]

#### Simple Models (6)
- [ ] Create `Role` model
- [ ] Create `Permission` model
- [ ] Create `Brand` model
- [ ] Create `AttributeGroup` model
- [ ] Create `Setting` model
- [ ] Create `Contact` model

#### Medium Models (6)
- [ ] Create `User` model (belongsTo Role)
- [ ] Create `UserAddress` model
- [ ] Create `Category` model (self-referencing)
- [ ] Create `Attribute` model
- [ ] Create `News` model
- [ ] Create `Voucher` model

#### Testing
- [ ] Test relationships in tinker
- [ ] Test fillable fields
- [ ] Test casts (dates, JSON)

---

### **Day 4: Complex Models + Authentication** [⏳ Not Started]

#### Complex Models (15)
- [ ] Create `Product` model
- [ ] Create `ProductImage` model
- [ ] Create `ProductVariant` model
- [ ] Create `ProductView` model
- [ ] Create `Cart` model
- [ ] Create `Order` model
- [ ] Create `OrderItem` model
- [ ] Create `OrderStatusHistory` model
- [ ] Create `PaymentTransaction` model
- [ ] Create `Review` model
- [ ] Create `Comment` model
- [ ] Create `CommentReport` model

#### Authentication [⏳ Not Started]
- [ ] Install Sanctum: `composer require laravel/sanctum`
- [ ] Publish Sanctum: `php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"`
- [ ] Run Sanctum migrations
- [ ] Create `AuthController`
- [ ] Implement `register()` method
- [ ] Implement `login()` method
- [ ] Implement `logout()` method
- [ ] Implement `me()` method
- [ ] Setup routes in `api.php`
- [ ] Test POST `/api/auth/register`
- [ ] Test POST `/api/auth/login`
- [ ] Test POST `/api/auth/logout`
- [ ] Test GET `/api/auth/me`

---

### **Day 5: Admin - Brands** [⏳ Not Started]

#### Setup
- [ ] Create `App\Http\Controllers\Api\V1\Admin\BrandController`
- [ ] Create `App\Http\Requests\StoreBrandRequest`
- [ ] Create `App\Http\Requests\UpdateBrandRequest`
- [ ] Create `App\Http\Resources\BrandResource`

#### Implement Methods
- [ ] `index()` - List brands (pagination, search, sort)
- [ ] `store()` - Create brand
- [ ] `show($id)` - Show brand detail
- [ ] `update($id)` - Update brand
- [ ] `destroy($id)` - Delete brand

#### Routes
- [ ] GET `/api/v1/admin/brands`
- [ ] POST `/api/v1/admin/brands`
- [ ] GET `/api/v1/admin/brands/{id}`
- [ ] PUT `/api/v1/admin/brands/{id}`
- [ ] DELETE `/api/v1/admin/brands/{id}`

#### Testing
- [ ] Test list brands
- [ ] Test create brand with validation
- [ ] Test slug auto-generation
- [ ] Test update brand
- [ ] Test delete brand
- [ ] Test search brands
- [ ] Test sort by name, created_at

#### Documentation
- [ ] Create Postman collection
- [ ] Document all endpoints
- [ ] Add request/response examples

---

### **Day 6: Admin - Categories** [⏳ Not Started]

#### Setup
- [ ] Create `CategoryController`
- [ ] Create `StoreCategoryRequest`
- [ ] Create `UpdateCategoryRequest`
- [ ] Create `CategoryResource`

#### Implement Methods
- [ ] `index()` - List categories (tree structure)
- [ ] `store()` - Create category
- [ ] `show($id)` - Show category with children
- [ ] `update($id)` - Update category
- [ ] `destroy($id)` - Delete category (validate)

#### Special Features
- [ ] Recursive query cho tree structure
- [ ] Nested categories in response
- [ ] Validation: cannot delete if has products
- [ ] Soft delete support

#### Testing
- [ ] Test list categories
- [ ] Test create category without parent
- [ ] Test create subcategory with parent_id
- [ ] Test update category
- [ ] Test delete validation (has products)
- [ ] Test soft delete

---

### **Day 7: Admin - Products (Basic)** [⏳ Not Started]

#### Setup
- [ ] Create `ProductController`
- [ ] Create `StoreProductRequest`
- [ ] Create `UpdateProductRequest`
- [ ] Create `ProductResource`

#### Product CRUD
- [ ] `index()` - List products (filter, search, sort, pagination)
- [ ] `store()` - Create product
- [ ] `show($id)` - Show product detail
- [ ] `update($id)` - Update product
- [ ] `destroy($id)` - Soft delete product

#### Image Handling
- [ ] Create `ProductImageController`
- [ ] `upload()` - Upload multiple images
- [ ] `delete($id)` - Delete image
- [ ] `setPrimary($id)` - Set primary image

#### Testing
- [ ] Test list products with filters
- [ ] Test create product
- [ ] Test upload images (multiple)
- [ ] Test update product
- [ ] Test soft delete
- [ ] Test search products
- [ ] Test sort by price, created_at, sold_count

---

## 🎯 WEEK 2: ADMIN ADVANCED

### **Day 8-9: Attributes & Variants** [⏳ Not Started]

#### Attribute Groups
- [ ] Create `AttributeGroupController`
- [ ] CRUD endpoints
- [ ] Test attribute groups

#### Attributes
- [ ] Create `AttributeController`
- [ ] CRUD endpoints
- [ ] Link to attribute groups
- [ ] Support color_code for color attributes

#### Product Variants
- [ ] Create `ProductVariantController`
- [ ] `store()` - Create variant
- [ ] `update()` - Update variant
- [ ] `destroy()` - Delete variant
- [ ] Handle pivot table `product_variant_attributes`
- [ ] Stock management per variant

#### Testing
- [ ] Test create attribute group (color, storage, RAM)
- [ ] Test create attributes
- [ ] Test create product variant
- [ ] Test assign attributes to variant
- [ ] Test update variant price, stock
- [ ] Test delete variant

---

### **Day 10: Users & Vouchers** [⏳ Not Started]

#### Users Management
- [ ] Create `UserController`
- [ ] `index()` - List users (filter by role, status)
- [ ] `show($id)` - User detail
- [ ] `update($id)` - Update user
- [ ] `updateStatus($id)` - Active/Deactive user (không delete)
- [ ] Test all endpoints

#### Vouchers
- [ ] Create `VoucherController`
- [ ] CRUD endpoints
- [ ] Validation: dates, discount_type, quantity
- [ ] Test create voucher
- [ ] Test update voucher
- [ ] Test delete voucher

---

### **Day 11-12: Orders Management** [⏳ Not Started]

#### Setup
- [ ] Create `OrderController`
- [ ] Create `OrderResource`
- [ ] Create `OrderItemResource`

#### Features
- [ ] `index()` - List orders (filter by status, date)
- [ ] `show($id)` - Order detail
- [ ] `updateStatus($id)` - Change order status
- [ ] Send email on status change
- [ ] Update stock on order completion
- [ ] Handle refunds

#### Order Status Workflow
- [ ] pending → confirmed
- [ ] confirmed → processing
- [ ] processing → shipping
- [ ] shipping → delivered
- [ ] delivered → completed
- [ ] Any status → cancelled

#### Testing
- [ ] Test list orders
- [ ] Test update status
- [ ] Test email notifications
- [ ] Test stock updates
- [ ] Test cancellation

---

### **Day 13-14: Dashboard Statistics** [⏳ Not Started]

#### Setup
- [ ] Create `DashboardController`
- [ ] Create statistics queries

#### Features
- [ ] Revenue statistics (total, by period)
- [ ] Order statistics (total, by status)
- [ ] Best-selling products (top 10)
- [ ] Best-selling categories
- [ ] Top customers (by order count, revenue)
- [ ] Low stock products
- [ ] Recent orders

#### Charts Data
- [ ] Revenue chart (daily, monthly)
- [ ] Order chart (by status)
- [ ] Product chart (by category)

#### Testing
- [ ] Test all statistics
- [ ] Test date filters
- [ ] Test chart data format

---

## 🎯 WEEK 3: CLIENT API

### **Day 15-16: Public Product Endpoints** [⏳ Not Started]

#### Home Page
- [ ] `GET /api/v1/home` - Homepage data
- [ ] Best sellers
- [ ] New products
- [ ] Sale products
- [ ] Featured products

#### Products
- [ ] `GET /api/v1/products` - List products
- [ ] Filters: category, brand, price range
- [ ] Sort: price, views, sales, rating
- [ ] Pagination
- [ ] `GET /api/v1/products/{id}` - Product detail
- [ ] Include: images, variants, reviews, comments

#### Categories & Brands
-[ ] `GET /api/v1/categories` - Tree structure
- [ ] `GET /api/v1/brands` - Active brands

---

### **Day 17-18: Cart & Checkout** [⏳ Not Started]

#### Cart
- [ ] Create `CartController`
- [ ] `GET /api/v1/cart` - Get cart
- [ ] `POST /api/v1/cart` - Add to cart
- [ ] `PUT /api/v1/cart/{id}` - Update quantity
- [ ] `DELETE /api/v1/cart/{id}` - Remove item
- [ ] Validate stock quantity
- [ ] Support guest cart (session_id)

#### Checkout
- [ ] Create `CheckoutController`
- [ ] `POST /api/v1/checkout` - Create order
- [ ] Validate stock
- [ ] Apply voucher
- [ ] Calculate totals
- [ ] Create order + order_items
- [ ] Update stock
- [ ] Clear cart

#### Payment Gateway
- [ ] Integrate VNPAY
- [ ] Integrate MOMO
- [ ] Handle COD
- [ ] Payment callbacks
- [ ] Update order status on payment

#### Testing
- [ ] Test cart operations
- [ ] Test checkout flow
- [ ] Test payment gateways
- [ ] Test email notifications

---

### **Day 19-20: Reviews & Final Features** [⏳ Not Started]

#### Reviews
- [ ] Create `ReviewController`
- [ ] `POST /api/v1/products/{id}/reviews` - Create review
- [ ] Validate: must have purchased
- [ ] Validate: 1 review per order_item
- [ ] Upload review images

#### Comments
- [ ] Create `CommentController`
- [ ] `POST /api/v1/products/{id}/comments` - Create comment
- [ ] Support reply (parent_id)

#### User Profile
- [ ] `GET /api/v1/profile` - Get profile
- [ ] `PUT /api/v1/profile` - Update profile
- [ ] `POST /api/v1/profile/avatar` - Upload avatar
- [ ] `GET /api/v1/profile/addresses` - Get addresses
- [ ] `POST /api/v1/profile/addresses` - Add address

#### Orders
- [ ] `GET /api/v1/orders` - Order history
- [ ] `GET /api/v1/orders/{id}` - Order detail
- [ ] `PUT /api/v1/orders/{id}/cancel` - Cancel order

#### News & Contact
- [ ] `GET /api/v1/news` - List news
- [ ] `GET /api/v1/news/{id}` - News detail
- [ ] `POST /api/v1/contacts` - Submit contact

---

### **Day 21: Testing, Documentation & Deploy** [⏳ Not Started]

#### Testing
- [ ] Integration testing
- [ ] Test all CRUD operations
- [ ] Test all validations
- [ ] Test authentication
- [ ] Test permissions
- [ ] Performance testing

#### Documentation
- [ ] Complete Postman collection
- [ ] Add request/response examples
- [ ] Add error examples
- [ ] Export Postman to JSON
- [ ] Write API documentation (Markdown)

#### Code Quality
- [ ] Code review
- [ ] Fix linting errors
- [ ] Remove debug code
- [ ] Clean up comments

#### Deploy
- [ ] Prepare `.env` for production
- [ ] Database backup
- [ ] Deploy to staging
- [ ] Test staging
- [ ] Handoff to Frontend

---

## 📊 FINAL DELIVERABLES

### **Code:**
- [ ] 27 migrations
- [ ] 27 models
- [ ] 20+ controllers
- [ ] 50+ API endpoints
- [ ] Full authentication
- [ ] Payment integration

### **Documentation:**
- [ ] Postman collection
- [ ] API documentation (Markdown)
- [ ] Database schema diagram
- [ ] Deployment guide

### **Testing:**
- [ ] All endpoints tested
- [ ] Validation tested
- [ ] Authentication tested
- [ ] Performance tested

---

## 📈 PROGRESS TRACKING

**Update this section daily:**

- **Day 1 (2026-01-27):** ⏳ Starting project...
- **Day 2:** 
- **Day 3:** 
- ...
- **Day 21:** 

---

**Status Legend:**
- ⏳ Not Started
- 🔄 In Progress
- ✅ Completed
- ❌ Blocked
- ⚠️ Needs Review

**Last updated:** 2026-01-27
