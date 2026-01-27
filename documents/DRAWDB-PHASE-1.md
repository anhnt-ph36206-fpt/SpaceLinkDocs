# 🎨 HƯỚNG DẪN THIẾT KẾ DATABASE TRÊN DRAWDB

## 📍 Link: https://drawdb.vercel.app/editor

---

## 🚀 BƯỚC 1: MỞ DRAWDB VÀ CHỌN DATABASE

1. Truy cập: https://drawdb.vercel.app/editor
2. Khi popup hiện ra, chọn **MySQL**
3. Click **Confirm** để vào editor

---

## 📊 PHASE 1: 4 BẢNG CƠ BẢN

### Thứ tự tạo bảng (quan trọng!):
```
1. brands      (không phụ thuộc bảng nào)
2. categories  (tự tham chiếu parent_id)
3. products    (phụ thuộc brands, categories)
4. product_images (phụ thuộc products)
```

---

## 🔧 BƯỚC 2: TẠO BẢNG BRANDS

### 2.1 Thêm bảng mới
- Click nút **"+ Add Table"** (góc trên bên trái)
- Hoặc double-click vào canvas

### 2.2 Đặt tên bảng
- Đổi tên thành: `brands`

### 2.3 Thêm các fields

| Field Name | Type | Length | Nullable | Default | Extra |
|------------|------|--------|----------|---------|-------|
| `id` | BIGINT | - | ❌ | - | PRIMARY KEY, AUTO_INCREMENT, UNSIGNED |
| `name` | VARCHAR | 255 | ❌ | - | |
| `slug` | VARCHAR | 255 | ❌ | - | UNIQUE |
| `logo` | VARCHAR | 255 | ✅ | NULL | |
| `description` | TEXT | - | ✅ | NULL | |
| `is_active` | TINYINT | 1 | ❌ | 1 | |
| `display_order` | INT | - | ❌ | 0 | |
| `created_at` | TIMESTAMP | - | ✅ | CURRENT_TIMESTAMP | |
| `updated_at` | TIMESTAMP | - | ✅ | CURRENT_TIMESTAMP | ON UPDATE |

### 2.4 Cách thêm field trong DrawDB:
1. Click vào bảng `brands`
2. Click icon **"+"** để thêm field
3. Nhập tên field
4. Chọn Type từ dropdown
5. Tick các options: NOT NULL, UNIQUE, PRIMARY KEY...

---

## 🔧 BƯỚC 3: TẠO BẢNG CATEGORIES

### 3.1 Thêm bảng mới
- Click **"+ Add Table"**
- Đặt tên: `categories`

### 3.2 Thêm các fields

| Field Name | Type | Length | Nullable | Default | Extra |
|------------|------|--------|----------|---------|-------|
| `id` | BIGINT | - | ❌ | - | PK, AUTO_INCREMENT, UNSIGNED |
| `parent_id` | BIGINT | - | ✅ | NULL | UNSIGNED, FK → categories.id |
| `name` | VARCHAR | 255 | ❌ | - | |
| `slug` | VARCHAR | 255 | ❌ | - | UNIQUE |
| `image` | VARCHAR | 255 | ✅ | NULL | |
| `icon` | VARCHAR | 100 | ✅ | NULL | |
| `description` | TEXT | - | ✅ | NULL | |
| `display_order` | INT | - | ❌ | 0 | |
| `is_active` | TINYINT | 1 | ❌ | 1 | |
| `created_at` | TIMESTAMP | - | ✅ | CURRENT_TIMESTAMP | |
| `updated_at` | TIMESTAMP | - | ✅ | CURRENT_TIMESTAMP | |
| `deleted_at` | TIMESTAMP | - | ✅ | NULL | Soft Delete |

### 3.3 Tạo Self-Reference (parent_id → id)
1. Click vào field `parent_id`
2. Kéo đường nối từ `parent_id` đến `id` của chính bảng `categories`
3. Hoặc: Tab "Relationships" → Add → Chọn categories.parent_id → categories.id
4. Set **ON DELETE: SET NULL**

**Giải thích:** Đây là quan hệ "tự tham chiếu" - một category có thể là con của category khác.

---

## 🔧 BƯỚC 4: TẠO BẢNG PRODUCTS

### 4.1 Thêm bảng mới
- Đặt tên: `products`

### 4.2 Thêm các fields

| Field Name | Type | Length | Nullable | Default | Extra |
|------------|------|--------|----------|---------|-------|
| `id` | BIGINT | - | ❌ | - | PK, AUTO_INCREMENT, UNSIGNED |
| `category_id` | BIGINT | - | ❌ | - | UNSIGNED, FK → categories.id |
| `brand_id` | BIGINT | - | ✅ | NULL | UNSIGNED, FK → brands.id |
| `name` | VARCHAR | 255 | ❌ | - | |
| `slug` | VARCHAR | 255 | ❌ | - | UNIQUE |
| `sku` | VARCHAR | 100 | ✅ | NULL | UNIQUE |
| `description` | TEXT | - | ✅ | NULL | |
| `content` | LONGTEXT | - | ✅ | NULL | |
| `price` | DECIMAL | 15,2 | ❌ | - | |
| `sale_price` | DECIMAL | 15,2 | ✅ | NULL | |
| `quantity` | INT | - | ❌ | 0 | UNSIGNED |
| `sold_count` | INT | - | ❌ | 0 | UNSIGNED |
| `view_count` | INT | - | ❌ | 0 | UNSIGNED |
| `is_featured` | TINYINT | 1 | ❌ | 0 | |
| `is_active` | TINYINT | 1 | ❌ | 1 | |
| `meta_title` | VARCHAR | 255 | ✅ | NULL | |
| `meta_description` | TEXT | - | ✅ | NULL | |
| `created_at` | TIMESTAMP | - | ✅ | CURRENT_TIMESTAMP | |
| `updated_at` | TIMESTAMP | - | ✅ | CURRENT_TIMESTAMP | |
| `deleted_at` | TIMESTAMP | - | ✅ | NULL | Soft Delete |

### 4.3 Tạo Foreign Keys

**FK 1: products.category_id → categories.id**
- Click vào `category_id`
- Kéo đến `categories.id`
- ON DELETE: **RESTRICT** (không cho xóa category nếu còn sản phẩm)

**FK 2: products.brand_id → brands.id**
- Click vào `brand_id`
- Kéo đến `brands.id`
- ON DELETE: **SET NULL** (xóa brand thì product vẫn còn)

---

## 🔧 BƯỚC 5: TẠO BẢNG PRODUCT_IMAGES

### 5.1 Thêm bảng mới
- Đặt tên: `product_images`

### 5.2 Thêm các fields

| Field Name | Type | Length | Nullable | Default | Extra |
|------------|------|--------|----------|---------|-------|
| `id` | BIGINT | - | ❌ | - | PK, AUTO_INCREMENT, UNSIGNED |
| `product_id` | BIGINT | - | ❌ | - | UNSIGNED, FK → products.id |
| `image_path` | VARCHAR | 255 | ❌ | - | |
| `is_primary` | TINYINT | 1 | ❌ | 0 | |
| `display_order` | INT | - | ❌ | 0 | |
| `created_at` | TIMESTAMP | - | ✅ | CURRENT_TIMESTAMP | |

### 5.3 Tạo Foreign Key

**FK: product_images.product_id → products.id**
- ON DELETE: **CASCADE** (xóa product → xóa luôn tất cả ảnh)

---

## 📐 BƯỚC 6: SẮP XẾP LAYOUT

### Vị trí gợi ý:
```
        [brands]
           ↓
[categories] ← [products] → [product_images]
     ↑________________|
     (self-reference)
```

### Cách sắp xếp:
1. Kéo thả các bảng trên canvas
2. Đặt `products` ở giữa
3. `brands` ở trên
4. `categories` bên trái
5. `product_images` bên phải

---

## 💾 BƯỚC 7: LƯU VÀ EXPORT

### 7.1 Lưu diagram
- **File** → **Save As** → Đặt tên `spacelink_phase1.ddb`
- Hoặc **Ctrl + S**

### 7.2 Export SQL
- **File** → **Export** → **SQL**
- Hoặc click icon **Export** trên toolbar

### 7.3 Export Image
- **File** → **Export** → **PNG** hoặc **SVG**

---

## 📊 SƠ ĐỒ QUAN HỆ PHASE 1

```
┌─────────────────┐
│     brands      │
├─────────────────┤
│ id (PK)         │
│ name            │
│ slug            │
│ logo            │
│ description     │
│ is_active       │
│ display_order   │
│ timestamps      │
└────────┬────────┘
         │ 1:N
         ↓
┌─────────────────┐         ┌─────────────────┐
│   categories    │         │    products     │
├─────────────────┤         ├─────────────────┤
│ id (PK)         │←───────→│ id (PK)         │
│ parent_id (FK)──┤ self    │ category_id (FK)│
│ name            │         │ brand_id (FK)   │
│ slug            │         │ name, slug      │
│ image, icon     │         │ sku             │
│ description     │         │ price           │
│ display_order   │         │ sale_price      │
│ is_active       │         │ quantity        │
│ timestamps      │         │ sold_count      │
│ deleted_at      │         │ view_count      │
└─────────────────┘         │ is_featured     │
                            │ is_active       │
                            │ timestamps      │
                            │ deleted_at      │
                            └────────┬────────┘
                                     │ 1:N
                                     ↓
                            ┌─────────────────┐
                            │ product_images  │
                            ├─────────────────┤
                            │ id (PK)         │
                            │ product_id (FK) │
                            │ image_path      │
                            │ is_primary      │
                            │ display_order   │
                            │ created_at      │
                            └─────────────────┘
```

---

## ✅ CHECKLIST PHASE 1

- [ ] Tạo bảng `brands` với đầy đủ fields
- [ ] Tạo bảng `categories` với self-reference
- [ ] Tạo bảng `products` với 2 foreign keys
- [ ] Tạo bảng `product_images` với cascade delete
- [ ] Sắp xếp layout đẹp
- [ ] Lưu file .ddb
- [ ] Export SQL để backup

---

## 🚀 TIẾP THEO: PHASE 2 - BIẾN THỂ SẢN PHẨM

Sau khi hoàn thành Phase 1, thêm các bảng:
- `attribute_groups` (Màu sắc, RAM, Dung lượng)
- `attributes` (Đen, Trắng, 128GB, 256GB)
- `product_variants`
- `product_variant_attributes`

Xem file: **DRAWDB-PHASE-2.md**
