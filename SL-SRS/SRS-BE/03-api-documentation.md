# SpaceLink API Documentation
**Version:** 1.0  
**Base URL:** `http://localhost:8000/api`  
**Last Updated:** 2026-01-28

---

## 📌 Overview

SpaceLink E-Commerce RESTful API cho phép FE team tương tác với backend để:
- Quản lý authentication (đăng ký, đăng nhập)
- Xem danh sách sản phẩm, chi tiết sản phẩm
- Quản lý giỏ hàng
- Đặt hàng và thanh toán
- Xem lịch sử đơn hàng

---

## 🔐 Authentication

API sử dụng **Laravel Sanctum** với Bearer Token authentication.

### Flow:
1. User đăng ký/đăng nhập → Nhận `token`
2. Gửi token trong header: `Authorization: Bearer {token}`
3. Token hết hạn sau 7 ngày

### Headers Required:
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {token}  // Chỉ cho protected routes
```

---

## 📋 Response Format

### Success Response:
```json
{
  "success": true,
  "message": "Thành công",
  "data": { ... }
}
```

### Error Response:
```json
{
  "success": false,
  "message": "Lỗi xảy ra",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

### HTTP Status Codes:
| Code | Meaning |
|------|---------|
| 200 | OK - Request thành công |
| 201 | Created - Tạo mới thành công |
| 400 | Bad Request - Validation error |
| 401 | Unauthorized - Chưa đăng nhập |
| 403 | Forbidden - Không có quyền |
| 404 | Not Found - Không tìm thấy resource |
| 500 | Server Error - Lỗi server |

---

## 🔑 AUTH ENDPOINTS

### 1. Register
Đăng ký tài khoản mới.

**Endpoint:** `POST /api/auth/register`  
**Auth Required:** No

**Request Body:**
```json
{
  "fullname": "Nguyễn Văn A",
  "email": "user@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "0123456789"
}
```

**Validation Rules:**
- `fullname`: required, string, max 150 chars
- `email`: required, email, unique
- `password`: required, min 6 chars, confirmed
- `phone`: optional, regex `/^0[0-9]{9}$/`

**Success Response (201):**
```json
{
  "success": true,
  "message": "Đăng ký thành công",
  "data": {
    "user": {
      "id": 1,
      "fullname": "Nguyễn Văn A",
      "email": "user@example.com",
      "phone": "0123456789",
      "role": "customer"
    },
    "token": "1|abc123xyz..."
  }
}
```

**Error Response (400):**
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "email": ["Email đã tồn tại"],
    "password": ["Mật khẩu phải ít nhất 6 ký tự"]
  }
}
```

---

### 2. Login
Đăng nhập vào hệ thống.

**Endpoint:** `POST /api/auth/login`  
**Auth Required:** No

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Đăng nhập thành công",
  "data": {
    "user": {
      "id": 1,
      "fullname": "Nguyễn Văn A",
      "email": "user@example.com",
      "phone": "0123456789",
      "avatar": null,
      "role": "customer"
    },
    "token": "2|def456uvw..."
  }
}
```

**Error Responses:**

**401 - Sai email/password:**
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "email": ["Email hoặc mật khẩu không đúng"]
  }
}
```

**403 - Tài khoản bị khóa:**
```json
{
  "success": false,
  "message": "Tài khoản của bạn đã bị khóa"
}
```

---

### 3. Logout
Đăng xuất (xóa token hiện tại).

**Endpoint:** `POST /api/auth/logout`  
**Auth Required:** Yes

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Đăng xuất thành công"
}
```

---

### 4. Get Profile
Xem thông tin user hiện tại.

**Endpoint:** `GET /api/auth/profile`  
**Auth Required:** Yes

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "fullname": "Nguyễn Văn A",
    "email": "user@example.com",
    "phone": "0123456789",
    "avatar": "/images/avatars/user1.jpg",
    "date_of_birth": "1990-01-01",
    "gender": "male",
    "role": "customer",
    "created_at": "2026-01-28T10:00:00.000000Z"
  }
}
```

---

### 5. Update Profile
Cập nhật thông tin user.

**Endpoint:** `PUT /api/auth/profile`  
**Auth Required:** Yes

**Request Body:**
```json
{
  "fullname": "Nguyễn Văn B",
  "phone": "0987654321",
  "date_of_birth": "1995-05-15",
  "gender": "male"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Cập nhật thông tin thành công",
  "data": {
    "id": 1,
    "fullname": "Nguyễn Văn B",
    "email": "user@example.com",
    "phone": "0987654321",
    ...
  }
}
```

---

### 6. Change Password
Đổi mật khẩu.

**Endpoint:** `POST /api/auth/change-password`  
**Auth Required:** Yes

**Request Body:**
```json
{
  "current_password": "password123",
  "new_password": "newpassword456",
  "new_password_confirmation": "newpassword456"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Đổi mật khẩu thành công"
}
```

**Error Response (400):**
```json
{
  "success": false,
  "message": "Mật khẩu hiện tại không đúng"
}
```

---

## 📦 PRODUCTS ENDPOINTS

### 1. Get Products List
Lấy danh sách sản phẩm với filter, search, pagination.

**Endpoint:** `GET /api/products`  
**Auth Required:** No

**Query Parameters:**

| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `page` | integer | Trang hiện tại | `1` |
| `per_page` | integer | Số sản phẩm/trang (default: 20) | `20` |
| `category_id` | integer | Lọc theo danh mục | `1` |
| `brand_id` | integer | Lọc theo thương hiệu | `2` |
| `min_price` | decimal | Giá tối thiểu | `1000000` |
| `max_price` | decimal | Giá tối đa | `5000000` |
| `search` | string | Tìm kiếm theo tên | `iphone` |
| `is_featured` | boolean | Sản phẩm nổi bật | `1` |
| `sort_by` | string | Sắp xếp theo (price, sold_count, view_count, created_at) | `price` |
| `sort_order` | string | Thứ tự (asc, desc) | `asc` |

**Example Request:**
```
GET /api/products?page=1&per_page=20&category_id=1&brand_id=2&min_price=1000000&max_price=5000000&sort_by=price&sort_order=asc
```

**Success Response (200):**
```json
{
  "data": [
    {
      "id": 1,
      "name": "iPhone 15 Pro Max",
      "slug": "iphone-15-pro-max",
      "price": 29990000,
      "sale_price": 27990000,
      "discount_percent": 7,
      "image": "/images/products/iphone-15-pro-max.jpg",
      "rating": 4.8,
      "sold_count": 150,
      "stock": 50,
      "is_featured": true,
      "brand": {
        "id": 1,
        "name": "Apple",
        "slug": "apple"
      },
      "category": {
        "id": 5,
        "name": "iPhone",
        "slug": "iphone"
      }
    },
    ...
  ],
  "links": {
    "first": "http://localhost:8000/api/products?page=1",
    "last": "http://localhost:8000/api/products?page=5",
    "prev": null,
    "next": "http://localhost:8000/api/products?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "per_page": 20,
    "to": 20,
    "total": 100
  }
}
```

---

### 2. Get Product Detail
Lấy thông tin chi tiết sản phẩm.

**Endpoint:** `GET /api/products/{id}`  
**Auth Required:** No

**Example Request:**
```
GET /api/products/1
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "iPhone 15 Pro Max",
    "slug": "iphone-15-pro-max",
    "sku": "IP15PM-001",
    "description": "Mô tả ngắn",
    "content": "Mô tả chi tiết HTML...",
    "price": 29990000,
    "sale_price": 27990000,
    "discount_percent": 7,
    "quantity": 50,
    "sold_count": 150,
    "view_count": 1250,
    "is_featured": true,
    "brand": {
      "id": 1,
      "name": "Apple",
      "slug": "apple",
      "logo": "/images/brands/apple.png"
    },
    "category": {
      "id": 5,
      "name": "iPhone",
      "slug": "iphone"
    },
    "images": [
      {
        "id": 1,
        "image_path": "/images/products/iphone-15-pro-max-1.jpg",
        "is_primary": true
      },
      {
        "id": 2,
        "image_path": "/images/products/iphone-15-pro-max-2.jpg",
        "is_primary": false
      }
    ],
    "variants": [
      {
        "id": 1,
        "sku": "IP15PM-BLK-256",
        "price": 29990000,
        "sale_price": 27990000,
        "quantity": 20,
        "attributes": {
          "color": "Đen",
          "storage": "256GB"
        }
      },
      {
        "id": 2,
        "sku": "IP15PM-WHT-256",
        "price": 29990000,
        "sale_price": 27990000,
        "quantity": 15,
        "attributes": {
          "color": "Trắng",
          "storage": "256GB"
        }
      }
    ]
  }
}
```

**Error Response (404):**
```json
{
  "success": false,
  "message": "Không tìm thấy sản phẩm"
}
```

---

### 3. Get Featured Products
Lấy danh sách sản phẩm nổi bật (top 10).

**Endpoint:** `GET /api/products/featured`  
**Auth Required:** No

**Success Response (200):**
```json
{
  "data": [
    {
      "id": 1,
      "name": "iPhone 15 Pro Max",
      ...
    },
    ...
  ]
}
```

---

### 4. Get Best Selling Products
Lấy danh sách sản phẩm bán chạy (top 10).

**Endpoint:** `GET /api/products/best-selling`  
**Auth Required:** No

---

### 5. Get New Arrivals
Lấy danh sách sản phẩm mới nhất (top 10).

**Endpoint:** `GET /api/products/new-arrivals`  
**Auth Required:** No

---

## 🏷️ BRANDS & CATEGORIES ENDPOINTS

### 1. Get Brands
Lấy danh sách thương hiệu.

**Endpoint:** `GET /api/brands`  
**Auth Required:** No

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Apple",
      "slug": "apple",
      "logo": "/images/brands/apple.png",
      "is_active": true
    },
    {
      "id": 2,
      "name": "Samsung",
      "slug": "samsung",
      "logo": "/images/brands/samsung.png",
      "is_active": true
    }
  ]
}
```

---

### 2. Get Categories
Lấy danh sách danh mục (có parent-child).

**Endpoint:** `GET /api/categories`  
**Auth Required:** No

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Điện thoại",
      "slug": "dien-thoai",
      "image": "/images/categories/phones.jpg",
      "children": [
        {
          "id": 5,
          "name": "iPhone",
          "slug": "iphone"
        },
        {
          "id": 6,
          "name": "Samsung Galaxy",
          "slug": "samsung-galaxy"
        }
      ]
    },
    {
      "id": 2,
      "name": "Máy tính bảng",
      "slug": "may-tinh-bang",
      "children": []
    }
  ]
}
```

---

## 🛒 CART ENDPOINTS

**Note:** Cart hỗ trợ cả user đã login (dùng `user_id`) và guest (dùng `session_id`).

### 1. Get Cart
Xem giỏ hàng hiện tại.

**Endpoint:** `GET /api/cart`  
**Auth Required:** Optional (hỗ trợ cả guest)

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "items": [
      {
        "id": 1,
        "product_id": 1,
        "variant_id": 1,
        "product_name": "iPhone 15 Pro Max",
        "product_image": "/images/products/iphone-15-pro-max.jpg",
        "variant_info": {
          "color": "Đen",
          "storage": "256GB"
        },
        "price": 27990000,
        "quantity": 2,
        "subtotal": 55980000,
        "stock": 20
      },
      {
        "id": 2,
        "product_id": 2,
        "variant_id": null,
        "product_name": "AirPods Pro",
        "product_image": "/images/products/airpods-pro.jpg",
        "variant_info": null,
        "price": 5990000,
        "quantity": 1,
        "subtotal": 5990000,
        "stock": 100
      }
    ],
    "total": 61970000,
    "count": 2
  }
}
```

---

### 2. Add to Cart
Thêm sản phẩm vào giỏ hàng.

**Endpoint:** `POST /api/cart/add`  
**Auth Required:** Optional

**Request Body:**
```json
{
  "product_id": 1,
  "variant_id": 1,
  "quantity": 2
}
```

**Validation:**
- `product_id`: required, exists in products
- `variant_id`: optional, exists in product_variants
- `quantity`: required, integer, min 1

**Success Response (201):**
```json
{
  "success": true,
  "message": "Đã thêm vào giỏ hàng"
}
```

**Error Response (400) - Vượt stock:**
```json
{
  "success": false,
  "message": "Số lượng vượt quá tồn kho (còn 20 sản phẩm)"
}
```

---

### 3. Update Cart Item
Cập nhật số lượng sản phẩm trong giỏ.

**Endpoint:** `PUT /api/cart/{id}`  
**Auth Required:** Optional

**Request Body:**
```json
{
  "quantity": 3
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Đã cập nhật giỏ hàng"
}
```

---

### 4. Remove Cart Item
Xóa sản phẩm khỏi giỏ hàng.

**Endpoint:** `DELETE /api/cart/{id}`  
**Auth Required:** Optional

**Success Response (200):**
```json
{
  "success": true,
  "message": "Đã xóa khỏi giỏ hàng"
}
```

---

### 5. Clear Cart
Xóa toàn bộ giỏ hàng.

**Endpoint:** `DELETE /api/cart`  
**Auth Required:** Optional

**Success Response (200):**
```json
{
  "success": true,
  "message": "Đã xóa toàn bộ giỏ hàng"
}
```

---

## 🚧 COMING SOON (Week 2)

### Checkout & Orders
- `POST /api/orders/checkout` - Tạo đơn hàng
- `GET /api/orders` - Lịch sử đơn hàng
- `GET /api/orders/{id}` - Chi tiết đơn hàng
- `POST /api/orders/{id}/cancel` - Hủy đơn hàng

### Payment
- `POST /api/payment/vnpay/create` - Tạo link thanh toán VNPAY
- `GET /api/payment/vnpay/callback` - Callback từ VNPAY

### Vouchers
- `GET /api/vouchers` - Danh sách voucher
- `POST /api/vouchers/apply` - Áp dụng voucher

---

## 🧪 Testing với Postman

### Environment Variables
```
base_url = http://localhost:8000/api
token = (sẽ tự động set sau khi login)
```

### Pre-request Script (Auto set token)
```javascript
// Trong Login request, thêm vào Tests tab:
if (pm.response.code === 200) {
    const response = pm.response.json();
    pm.environment.set("token", response.data.token);
}
```

### Authorization Header (Cho protected routes)
```
Type: Bearer Token
Token: {{token}}
```

---

## 📞 Support

**Backend Team:**
- Lead: [Tên bạn]
- Slack: #backend-team
- Email: backend@spacelink.com

**Issues & Bugs:**
- Report tại: [Link Trello/Jira]
- Hoặc ping trực tiếp trên Slack

---

**Last Updated:** 2026-01-28  
**Version:** 1.0  
**Status:** Week 1 APIs Ready ✅
