# SPACELINK E-COMMERCE API DOCUMENTATION

**Version:** 1.0.0  
**Base URL:** `https://api.spacelink.com/v1`  
**Authentication:** Bearer Token (JWT)

---

## MỤC LỤC

1. [Authentication](#1-authentication)
2. [Products](#2-products)
3. [Cart](#3-cart)
4. [Orders](#4-orders)
5. [User Management](#5-user-management)
6. [Reviews & Comments](#6-reviews--comments)
7. [Admin APIs](#7-admin-apis)
8. [Marketing](#8-marketing)
9. [Content](#9-content)

---

## 1. AUTHENTICATION

### 1.1. Đăng ký tài khoản
**POST** `/api/auth/register`

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "fullname": "Nguyễn Văn A",
  "phone": "0912345678"
}
```

**Response Success (201):**
```json
{
  "success": true,
  "message": "Đăng ký thành công",
  "data": {
    "user": {
      "id": 1,
      "email": "user@example.com",
      "fullname": "Nguyễn Văn A",
      "phone": "0912345678"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
  }
}
```

**Response Error (422):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["Email đã tồn tại"],
    "password": ["Mật khẩu phải có ít nhất 6 ký tự"]
  }
}
```

---

### 1.2. Đăng nhập
**POST** `/api/auth/login`

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Đăng nhập thành công",
  "data": {
    "user": {
      "id": 1,
      "email": "user@example.com",
      "fullname": "Nguyễn Văn A",
      "phone": "0912345678",
      "avatar": null,
      "wallet_balance": "0.00",
      "loyalty_points": 0,
      "roles": ["customer"]
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "expires_in": 3600
  }
}
```

**Response Error (401):**
```json
{
  "success": false,
  "message": "Email hoặc mật khẩu không đúng"
}
```

---

### 1.3. Đăng xuất
**POST** `/api/auth/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Đăng xuất thành công"
}
```

---

### 1.4. Quên mật khẩu - Gửi email reset
**POST** `/api/auth/forgot-password`

**Request Body:**
```json
{
  "email": "user@example.com"
}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Email đặt lại mật khẩu đã được gửi"
}
```

---

### 1.5. Đặt lại mật khẩu
**POST** `/api/auth/reset-password`

**Request Body:**
```json
{
  "email": "user@example.com",
  "token": "reset_token_here",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Đặt lại mật khẩu thành công"
}
```

---

### 1.6. Refresh Token
**POST** `/api/auth/refresh`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "token": "new_token_here",
    "expires_in": 3600
  }
}
```

---

## 2. PRODUCTS

### 2.1. Danh sách sản phẩm
**GET** `/api/products`

**Query Parameters:**
- `page` (int): Số trang (default: 1)
- `per_page` (int): Số item/trang (default: 20, max: 100)
- `category_id` (int): Lọc theo danh mục
- `brand_id` (int): Lọc theo thương hiệu
- `search` (string): Tìm kiếm theo tên
- `min_price` (decimal): Giá tối thiểu
- `max_price` (decimal): Giá tối đa
- `sort` (string): Sắp xếp (`price_asc`, `price_desc`, `newest`, `popular`, `name_asc`)
- `is_featured` (boolean): Sản phẩm nổi bật
- `in_stock` (boolean): Còn hàng

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "per_page": 20,
    "total": 150,
    "last_page": 8,
    "data": [
      {
        "id": 1,
        "name": "iPhone 15 Pro Max",
        "slug": "iphone-15-pro-max",
        "sku": "IP15PM-256-BLK",
        "price": "29990000.00",
        "sale_price": "27990000.00",
        "quantity": 50,
        "sold_count": 120,
        "view_count": 500,
        "is_featured": true,
        "category": {
          "id": 5,
          "name": "iPhone"
        },
        "brand": {
          "id": 1,
          "name": "Apple",
          "logo": "/images/brands/apple.png"
        },
        "primary_image": "/images/products/iphone-15-pro-max.jpg",
        "rating": 4.5,
        "review_count": 45
      }
    ]
  }
}
```

---

### 2.2. Chi tiết sản phẩm
**GET** `/api/products/{id}`

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "iPhone 15 Pro Max",
    "slug": "iphone-15-pro-max",
    "sku": "IP15PM-256-BLK",
    "description": "Mô tả sản phẩm...",
    "content": "Nội dung chi tiết...",
    "price": "29990000.00",
    "sale_price": "27990000.00",
    "quantity": 50,
    "sold_count": 120,
    "view_count": 501,
    "is_featured": true,
    "category": {
      "id": 5,
      "name": "iPhone",
      "slug": "iphone"
    },
    "brand": {
      "id": 1,
      "name": "Apple",
      "logo": "/images/brands/apple.png"
    },
    "images": [
      {
        "id": 1,
        "image_path": "/images/products/iphone-15-pro-max-1.jpg",
        "is_primary": true,
        "display_order": 1
      }
    ],
    "variants": [
      {
        "id": 1,
        "sku": "IP15PM-256-BLK",
        "price": "29990000.00",
        "sale_price": "27990000.00",
        "quantity": 20,
        "is_active": true,
        "attributes": [
          {
            "group": "Màu sắc",
            "value": "Đen",
            "color_code": "#000000"
          },
          {
            "group": "Dung lượng",
            "value": "256GB"
          }
        ]
      }
    ],
    "rating": 4.5,
    "review_count": 45,
    "in_wishlist": false
  }
}
```

---

### 2.3. Danh sách danh mục
**GET** `/api/categories`

**Query Parameters:**
- `parent_id` (int): Lọc theo danh mục cha
- `is_active` (boolean): Chỉ lấy danh mục active

**Response Success (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Điện thoại",
      "slug": "dien-thoai",
      "image": "/images/categories/phone.jpg",
      "icon": "phone",
      "description": "Mô tả danh mục",
      "display_order": 1,
      "children": [
        {
          "id": 5,
          "name": "iPhone",
          "slug": "iphone"
        }
      ]
    }
  ]
}
```

---

### 2.4. Danh sách thương hiệu
**GET** `/api/brands`

**Response Success (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Apple",
      "slug": "apple",
      "logo": "/images/brands/apple.png",
      "description": "Mô tả thương hiệu"
    }
  ]
}
```

---

### 2.5. Sản phẩm nổi bật
**GET** `/api/products/featured`

**Query Parameters:**
- `limit` (int): Số lượng (default: 10)

**Response Success (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "iPhone 15 Pro Max",
      "slug": "iphone-15-pro-max",
      "price": "29990000.00",
      "sale_price": "27990000.00",
      "primary_image": "/images/products/iphone-15-pro-max.jpg",
      "rating": 4.5
    }
  ]
}
```

---

## 3. CART

### 3.1. Lấy giỏ hàng
**GET** `/api/cart`

**Headers:**
```
Authorization: Bearer {token}
```
*Hoặc sử dụng session_id cho guest*

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "items": [
      {
        "id": 1,
        "product": {
          "id": 1,
          "name": "iPhone 15 Pro Max",
          "slug": "iphone-15-pro-max",
          "primary_image": "/images/products/iphone-15-pro-max.jpg"
        },
        "variant": {
          "id": 1,
          "attributes": [
            {"group": "Màu sắc", "value": "Đen"},
            {"group": "Dung lượng", "value": "256GB"}
          ]
        },
        "quantity": 2,
        "price": "27990000.00",
        "subtotal": "55980000.00"
      }
    ],
    "summary": {
      "subtotal": "55980000.00",
      "shipping_fee": "30000.00",
      "total": "56010000.00"
    }
  }
}
```

---

### 3.2. Thêm sản phẩm vào giỏ hàng
**POST** `/api/cart/add`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "product_id": 1,
  "variant_id": 1,
  "quantity": 2
}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Đã thêm vào giỏ hàng",
  "data": {
    "cart_item": {
      "id": 1,
      "product_id": 1,
      "variant_id": 1,
      "quantity": 2
    }
  }
}
```

---

### 3.3. Cập nhật số lượng
**PUT** `/api/cart/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "quantity": 3
}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Cập nhật giỏ hàng thành công"
}
```

---

### 3.4. Xóa sản phẩm khỏi giỏ hàng
**DELETE** `/api/cart/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Đã xóa khỏi giỏ hàng"
}
```

---

### 3.5. Xóa toàn bộ giỏ hàng
**DELETE** `/api/cart/clear`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Đã xóa toàn bộ giỏ hàng"
}
```

---

## 4. ORDERS

### 4.1. Tạo đơn hàng
**POST** `/api/orders`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "shipping_name": "Nguyễn Văn A",
  "shipping_phone": "0912345678",
  "shipping_email": "user@example.com",
  "shipping_province": "Hà Nội",
  "shipping_district": "Quận Cầu Giấy",
  "shipping_ward": "Phường Dịch Vọng",
  "shipping_address": "123 Đường ABC",
  "payment_method": "cod",
  "voucher_code": "SALE10",
  "points_used": 0,
  "note": "Giao hàng giờ hành chính"
}
```

**Response Success (201):**
```json
{
  "success": true,
  "message": "Đặt hàng thành công",
  "data": {
    "order": {
      "id": 1,
      "order_code": "SPL-20260110-001",
      "status": "pending",
      "payment_status": "unpaid",
      "payment_method": "cod",
      "subtotal": "55980000.00",
      "discount_amount": "5598000.00",
      "shipping_fee": "30000.00",
      "total_amount": "50430000.00",
      "items": [
        {
          "id": 1,
          "product_name": "iPhone 15 Pro Max",
          "quantity": 2,
          "price": "27990000.00",
          "total": "55980000.00"
        }
      ],
      "created_at": "2026-01-10T10:30:00Z"
    },
    "payment_url": null
  }
}
```

---

### 4.2. Danh sách đơn hàng của user
**GET** `/api/orders`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `page` (int): Số trang
- `per_page` (int): Số item/trang
- `status` (string): Lọc theo trạng thái

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "per_page": 20,
    "total": 5,
    "data": [
      {
        "id": 1,
        "order_code": "SPL-20260110-001",
        "status": "pending",
        "payment_status": "unpaid",
        "total_amount": "50430000.00",
        "item_count": 2,
        "created_at": "2026-01-10T10:30:00Z"
      }
    ]
  }
}
```

---

### 4.3. Chi tiết đơn hàng
**GET** `/api/orders/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "order_code": "SPL-20260110-001",
    "status": "pending",
    "payment_status": "unpaid",
    "payment_method": "cod",
    "shipping_name": "Nguyễn Văn A",
    "shipping_phone": "0912345678",
    "shipping_email": "user@example.com",
    "shipping_province": "Hà Nội",
    "shipping_district": "Quận Cầu Giấy",
    "shipping_ward": "Phường Dịch Vọng",
    "shipping_address": "123 Đường ABC",
    "subtotal": "55980000.00",
    "discount_amount": "5598000.00",
    "shipping_fee": "30000.00",
    "total_amount": "50430000.00",
    "items": [
      {
        "id": 1,
        "product": {
          "id": 1,
          "name": "iPhone 15 Pro Max",
          "slug": "iphone-15-pro-max",
          "image": "/images/products/iphone-15-pro-max.jpg"
        },
        "quantity": 2,
        "price": "27990000.00",
        "total": "55980000.00",
        "is_reviewed": false
      }
    ],
    "status_history": [
      {
        "from_status": null,
        "to_status": "pending",
        "created_at": "2026-01-10T10:30:00Z"
      }
    ],
    "created_at": "2026-01-10T10:30:00Z"
  }
}
```

---

### 4.4. Hủy đơn hàng
**POST** `/api/orders/{id}/cancel`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "reason": "Không còn nhu cầu mua"
}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Đã hủy đơn hàng"
}
```

---

### 4.5. Thanh toán đơn hàng
**POST** `/api/orders/{id}/payment`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "payment_method": "vnpay"
}
```

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "payment_url": "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?...",
    "transaction_id": "TXN123456789"
  }
}
```

---

## 5. USER MANAGEMENT

### 5.1. Thông tin profile
**GET** `/api/user/profile`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "email": "user@example.com",
    "fullname": "Nguyễn Văn A",
    "phone": "0912345678",
    "avatar": "/images/avatars/user-1.jpg",
    "date_of_birth": "1990-01-01",
    "gender": "male",
    "wallet_balance": "500000.00",
    "loyalty_points": 1500,
    "created_at": "2026-01-01T00:00:00Z"
  }
}
```

---

### 5.2. Cập nhật profile
**PUT** `/api/user/profile`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "fullname": "Nguyễn Văn B",
  "phone": "0987654321",
  "date_of_birth": "1990-01-01",
  "gender": "male"
}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Cập nhật thông tin thành công",
  "data": {
    "id": 1,
    "fullname": "Nguyễn Văn B",
    "phone": "0987654321"
  }
}
```

---

### 5.3. Đổi mật khẩu
**POST** `/api/user/change-password`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "current_password": "oldpassword123",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Đổi mật khẩu thành công"
}
```

---

### 5.4. Upload avatar
**POST** `/api/user/avatar`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body:**
```
avatar: (file)
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Upload avatar thành công",
  "data": {
    "avatar": "/images/avatars/user-1.jpg"
  }
}
```

---

### 5.5. Danh sách địa chỉ
**GET** `/api/user/addresses`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "fullname": "Nguyễn Văn A",
      "phone": "0912345678",
      "province": "Hà Nội",
      "district": "Quận Cầu Giấy",
      "ward": "Phường Dịch Vọng",
      "address_detail": "123 Đường ABC",
      "is_default": true,
      "address_type": "home"
    }
  ]
}
```

---

### 5.6. Thêm địa chỉ
**POST** `/api/user/addresses`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "fullname": "Nguyễn Văn A",
  "phone": "0912345678",
  "province": "Hà Nội",
  "district": "Quận Cầu Giấy",
  "ward": "Phường Dịch Vọng",
  "address_detail": "123 Đường ABC",
  "is_default": true,
  "address_type": "home"
}
```

**Response Success (201):**
```json
{
  "success": true,
  "message": "Thêm địa chỉ thành công",
  "data": {
    "id": 1,
    "fullname": "Nguyễn Văn A",
    "phone": "0912345678",
    "province": "Hà Nội",
    "district": "Quận Cầu Giấy",
    "ward": "Phường Dịch Vọng",
    "address_detail": "123 Đường ABC",
    "is_default": true
  }
}
```

---

### 5.7. Cập nhật địa chỉ
**PUT** `/api/user/addresses/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:** (tương tự như thêm địa chỉ)

**Response Success (200):**
```json
{
  "success": true,
  "message": "Cập nhật địa chỉ thành công"
}
```

---

### 5.8. Xóa địa chỉ
**DELETE** `/api/user/addresses/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Xóa địa chỉ thành công"
}
```

---

### 5.9. Danh sách sản phẩm yêu thích
**GET** `/api/user/wishlist`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "product": {
        "id": 1,
        "name": "iPhone 15 Pro Max",
        "slug": "iphone-15-pro-max",
        "price": "29990000.00",
        "sale_price": "27990000.00",
        "primary_image": "/images/products/iphone-15-pro-max.jpg"
      },
      "created_at": "2026-01-10T10:00:00Z"
    }
  ]
}
```

---

### 5.10. Thêm vào wishlist
**POST** `/api/user/wishlist`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "product_id": 1
}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Đã thêm vào yêu thích"
}
```

---

### 5.11. Xóa khỏi wishlist
**DELETE** `/api/user/wishlist/{product_id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Đã xóa khỏi yêu thích"
}
```

---

## 6. REVIEWS & COMMENTS

### 6.1. Danh sách đánh giá sản phẩm
**GET** `/api/products/{product_id}/reviews`

**Query Parameters:**
- `page` (int): Số trang
- `per_page` (int): Số item/trang
- `rating` (int): Lọc theo sao (1-5)

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "per_page": 10,
    "total": 45,
    "data": [
      {
        "id": 1,
        "user": {
          "id": 2,
          "fullname": "Nguyễn Văn B",
          "avatar": "/images/avatars/user-2.jpg"
        },
        "rating": 5,
        "content": "Sản phẩm rất tốt, đáng mua!",
        "images": [
          "/images/reviews/review-1-1.jpg"
        ],
        "admin_reply": null,
        "created_at": "2026-01-09T10:00:00Z"
      }
    ],
    "summary": {
      "average_rating": 4.5,
      "total_reviews": 45,
      "rating_distribution": {
        "5": 20,
        "4": 15,
        "3": 5,
        "2": 3,
        "1": 2
      }
    }
  }
}
```

---

### 6.2. Tạo đánh giá
**POST** `/api/products/{product_id}/reviews`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "order_item_id": 1,
  "rating": 5,
  "content": "Sản phẩm rất tốt!",
  "images": ["/images/reviews/review-1-1.jpg"]
}
```

**Response Success (201):**
```json
{
  "success": true,
  "message": "Đánh giá thành công",
  "data": {
    "id": 1,
    "rating": 5,
    "content": "Sản phẩm rất tốt!",
    "created_at": "2026-01-10T10:00:00Z"
  }
}
```

---

### 6.3. Danh sách bình luận
**GET** `/api/products/{product_id}/comments`

**Query Parameters:**
- `page` (int): Số trang
- `per_page` (int): Số item/trang

**Response Success (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "user": {
        "id": 2,
        "fullname": "Nguyễn Văn B",
        "avatar": "/images/avatars/user-2.jpg"
      },
      "content": "Sản phẩm này có tốt không?",
      "replies": [
        {
          "id": 2,
          "user": {
            "id": 3,
            "fullname": "Trần Văn C",
            "avatar": "/images/avatars/user-3.jpg"
          },
          "content": "Rất tốt bạn ơi!",
          "created_at": "2026-01-10T11:00:00Z"
        }
      ],
      "created_at": "2026-01-10T10:00:00Z"
    }
  ]
}
```

---

### 6.4. Tạo bình luận
**POST** `/api/products/{product_id}/comments`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "content": "Sản phẩm này có tốt không?",
  "parent_id": null
}
```

**Response Success (201):**
```json
{
  "success": true,
  "message": "Bình luận thành công",
  "data": {
    "id": 1,
    "content": "Sản phẩm này có tốt không?",
    "created_at": "2026-01-10T10:00:00Z"
  }
}
```

---

## 7. ADMIN APIS

### 7.1. Dashboard - Thống kê tổng quan
**GET** `/api/admin/dashboard`

**Headers:**
```
Authorization: Bearer {admin_token}
```

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "overview": {
      "total_orders": 1250,
      "total_revenue": "1250000000.00",
      "total_users": 500,
      "total_products": 200
    },
    "recent_orders": [
      {
        "id": 1,
        "order_code": "SPL-20260110-001",
        "customer": "Nguyễn Văn A",
        "total_amount": "50430000.00",
        "status": "pending",
        "created_at": "2026-01-10T10:30:00Z"
      }
    ],
    "top_products": [
      {
        "id": 1,
        "name": "iPhone 15 Pro Max",
        "sold_count": 120,
        "revenue": "3358800000.00"
      }
    ]
  }
}
```

---

### 7.2. Quản lý sản phẩm - Danh sách
**GET** `/api/admin/products`

**Headers:**
```
Authorization: Bearer {admin_token}
```

**Query Parameters:**
- `page`, `per_page`, `search`, `category_id`, `brand_id`, `status`

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "per_page": 20,
    "total": 200,
    "data": [
      {
        "id": 1,
        "name": "iPhone 15 Pro Max",
        "sku": "IP15PM-256-BLK",
        "price": "29990000.00",
        "sale_price": "27990000.00",
        "quantity": 50,
        "sold_count": 120,
        "is_active": true,
        "category": {"id": 5, "name": "iPhone"},
        "brand": {"id": 1, "name": "Apple"}
      }
    ]
  }
}
```

---

### 7.3. Quản lý sản phẩm - Tạo mới
**POST** `/api/admin/products`

**Headers:**
```
Authorization: Bearer {admin_token}
```

**Request Body:**
```json
{
  "category_id": 5,
  "brand_id": 1,
  "name": "iPhone 16 Pro Max",
  "slug": "iphone-16-pro-max",
  "sku": "IP16PM-256-BLK",
  "description": "Mô tả sản phẩm",
  "content": "Nội dung chi tiết",
  "price": "32990000.00",
  "sale_price": "30990000.00",
  "quantity": 100,
  "is_featured": true,
  "is_active": true,
  "images": [
    {
      "image_path": "/images/products/iphone-16-pro-max-1.jpg",
      "is_primary": true,
      "display_order": 1
    }
  ],
  "variants": [
    {
      "sku": "IP16PM-256-BLK",
      "price": "32990000.00",
      "sale_price": "30990000.00",
      "quantity": 50,
      "attribute_ids": [1, 4]
    }
  ]
}
```

**Response Success (201):**
```json
{
  "success": true,
  "message": "Tạo sản phẩm thành công",
  "data": {
    "id": 2,
    "name": "iPhone 16 Pro Max",
    "slug": "iphone-16-pro-max"
  }
}
```

---

### 7.4. Quản lý sản phẩm - Cập nhật
**PUT** `/api/admin/products/{id}`

**Headers:**
```
Authorization: Bearer {admin_token}
```

**Request Body:** (tương tự như tạo mới)

**Response Success (200):**
```json
{
  "success": true,
  "message": "Cập nhật sản phẩm thành công"
}
```

---

### 7.5. Quản lý sản phẩm - Xóa
**DELETE** `/api/admin/products/{id}`

**Headers:**
```
Authorization: Bearer {admin_token}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Xóa sản phẩm thành công"
}
```

---

### 7.6. Quản lý đơn hàng - Danh sách
**GET** `/api/admin/orders`

**Headers:**
```
Authorization: Bearer {admin_token}
```

**Query Parameters:**
- `page`, `per_page`, `status`, `payment_status`, `search`

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "per_page": 20,
    "total": 1250,
    "data": [
      {
        "id": 1,
        "order_code": "SPL-20260110-001",
        "user": {
          "id": 1,
          "fullname": "Nguyễn Văn A",
          "email": "user@example.com"
        },
        "status": "pending",
        "payment_status": "unpaid",
        "total_amount": "50430000.00",
        "created_at": "2026-01-10T10:30:00Z"
      }
    ]
  }
}
```

---

### 7.7. Quản lý đơn hàng - Cập nhật trạng thái
**PUT** `/api/admin/orders/{id}/status`

**Headers:**
```
Authorization: Bearer {admin_token}
```

**Request Body:**
```json
{
  "status": "confirmed",
  "note": "Đã xác nhận đơn hàng"
}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Cập nhật trạng thái đơn hàng thành công"
}
```

---

### 7.8. Quản lý người dùng - Danh sách
**GET** `/api/admin/users`

**Headers:**
```
Authorization: Bearer {admin_token}
```

**Query Parameters:**
- `page`, `per_page`, `search`, `status`, `role`

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "per_page": 20,
    "total": 500,
    "data": [
      {
        "id": 1,
        "email": "user@example.com",
        "fullname": "Nguyễn Văn A",
        "phone": "0912345678",
        "status": "active",
        "roles": ["customer"],
        "created_at": "2026-01-01T00:00:00Z"
      }
    ]
  }
}
```

---

### 7.9. Quản lý người dùng - Cập nhật
**PUT** `/api/admin/users/{id}`

**Headers:**
```
Authorization: Bearer {admin_token}
```

**Request Body:**
```json
{
  "status": "banned",
  "roles": [3]
}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Cập nhật người dùng thành công"
}
```

---

### 7.10. Quản lý danh mục - Danh sách
**GET** `/api/admin/categories`

**Headers:**
```
Authorization: Bearer {admin_token}
```

**Response Success (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Điện thoại",
      "slug": "dien-thoai",
      "parent_id": null,
      "is_active": true,
      "display_order": 1,
      "children": [
        {
          "id": 5,
          "name": "iPhone",
          "slug": "iphone",
          "parent_id": 1
        }
      ]
    }
  ]
}
```

---

### 7.11. Quản lý danh mục - Tạo/Cập nhật/Xóa
**POST** `/api/admin/categories`  
**PUT** `/api/admin/categories/{id}`  
**DELETE** `/api/admin/categories/{id}`

*Tương tự như quản lý sản phẩm*

---

### 7.12. Quản lý thương hiệu - CRUD
**GET** `/api/admin/brands`  
**POST** `/api/admin/brands`  
**PUT** `/api/admin/brands/{id}`  
**DELETE** `/api/admin/brands/{id}`

*Tương tự như quản lý sản phẩm*

---

### 7.13. Quản lý voucher - Danh sách
**GET** `/api/admin/vouchers`

**Headers:**
```
Authorization: Bearer {admin_token}
```

**Response Success (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "code": "SALE10",
      "name": "Giảm 10%",
      "discount_type": "percent",
      "discount_value": "10.00",
      "min_order_amount": "1000000.00",
      "quantity": 100,
      "used_count": 25,
      "start_date": "2026-01-01T00:00:00Z",
      "end_date": "2026-12-31T23:59:59Z",
      "is_active": true
    }
  ]
}
```

---

### 7.14. Quản lý voucher - Tạo/Cập nhật/Xóa
**POST** `/api/admin/vouchers`  
**PUT** `/api/admin/vouchers/{id}`  
**DELETE** `/api/admin/vouchers/{id}`

---

## 8. MARKETING

### 8.1. Danh sách voucher khả dụng
**GET** `/api/vouchers/available`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "code": "SALE10",
      "name": "Giảm 10%",
      "description": "Giảm 10% cho đơn hàng từ 1.000.000đ",
      "discount_type": "percent",
      "discount_value": "10.00",
      "min_order_amount": "1000000.00"
    }
  ]
}
```

---

### 8.2. Kiểm tra voucher
**POST** `/api/vouchers/check`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "code": "SALE10",
  "subtotal": "2000000.00"
}
```

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "voucher": {
      "id": 1,
      "code": "SALE10",
      "name": "Giảm 10%",
      "discount_amount": "200000.00"
    },
    "discount_amount": "200000.00",
    "final_amount": "1800000.00"
  }
}
```

---

### 8.3. Danh sách Flash Sale
**GET** `/api/flash-sales`

**Response Success (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Flash Sale Tháng 1",
      "start_time": "2026-01-10T00:00:00Z",
      "end_time": "2026-01-10T23:59:59Z",
      "products": [
        {
          "id": 1,
          "name": "iPhone 15 Pro Max",
          "sale_price": "24990000.00",
          "original_price": "27990000.00",
          "quantity": 10,
          "sold_count": 5,
          "primary_image": "/images/products/iphone-15-pro-max.jpg"
        }
      ]
    }
  ]
}
```

---

## 9. CONTENT

### 9.1. Danh sách banner
**GET** `/api/banners`

**Query Parameters:**
- `position` (string): Vị trí banner (`home_slider`, `home_sidebar`, etc.)

**Response Success (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Khuyến mãi lớn",
      "image": "/images/banners/banner-1.jpg",
      "image_mobile": "/images/banners/banner-1-mobile.jpg",
      "link": "/products?category=1",
      "position": "home_slider",
      "display_order": 1
    }
  ]
}
```

---

### 9.2. Danh sách tin tức
**GET** `/api/news`

**Query Parameters:**
- `page`, `per_page`, `category_id`, `is_featured`

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "per_page": 10,
    "total": 50,
    "data": [
      {
        "id": 1,
        "title": "Tin tức công nghệ mới nhất",
        "slug": "tin-tuc-cong-nghe-moi-nhat",
        "thumbnail": "/images/news/news-1.jpg",
        "summary": "Tóm tắt tin tức...",
        "category": {
          "id": 1,
          "name": "Công nghệ"
        },
        "author": {
          "id": 1,
          "fullname": "Admin"
        },
        "view_count": 150,
        "published_at": "2026-01-10T10:00:00Z"
      }
    ]
  }
}
```

---

### 9.3. Chi tiết tin tức
**GET** `/api/news/{id}`

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Tin tức công nghệ mới nhất",
    "slug": "tin-tuc-cong-nghe-moi-nhat",
    "thumbnail": "/images/news/news-1.jpg",
    "content": "Nội dung đầy đủ...",
    "category": {
      "id": 1,
      "name": "Công nghệ"
    },
    "author": {
      "id": 1,
      "fullname": "Admin"
    },
    "view_count": 151,
    "published_at": "2026-01-10T10:00:00Z"
  }
}
```

---

### 9.4. Gửi liên hệ
**POST** `/api/contacts`

**Request Body:**
```json
{
  "name": "Nguyễn Văn A",
  "email": "user@example.com",
  "phone": "0912345678",
  "subject": "Câu hỏi về sản phẩm",
  "message": "Tôi muốn biết thêm thông tin về sản phẩm..."
}
```

**Response Success (201):**
```json
{
  "success": true,
  "message": "Gửi liên hệ thành công"
}
```

---

## ERROR RESPONSES

### 400 Bad Request
```json
{
  "success": false,
  "message": "Bad Request",
  "errors": {}
}
```

### 401 Unauthorized
```json
{
  "success": false,
  "message": "Unauthorized"
}
```

### 403 Forbidden
```json
{
  "success": false,
  "message": "Forbidden"
}
```

### 404 Not Found
```json
{
  "success": false,
  "message": "Resource not found"
}
```

### 422 Validation Error
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "field": ["Error message"]
  }
}
```

### 500 Server Error
```json
{
  "success": false,
  "message": "Internal server error"
}
```

---

## AUTHENTICATION

Tất cả các API yêu cầu authentication sẽ sử dụng Bearer Token trong header:

```
Authorization: Bearer {token}
```

Token được trả về khi đăng nhập hoặc đăng ký thành công.

---

## PAGINATION

Các API trả về danh sách sẽ sử dụng pagination với format:

```json
{
  "current_page": 1,
  "per_page": 20,
  "total": 100,
  "last_page": 5,
  "data": [...]
}
```

---

## NOTES

- Tất cả giá tiền được trả về dưới dạng string (decimal)
- Tất cả datetime được trả về dưới dạng ISO 8601 format
- API versioning: `/api/v1/...`
- Rate limiting: 60 requests/minute cho user, 120 requests/minute cho admin

