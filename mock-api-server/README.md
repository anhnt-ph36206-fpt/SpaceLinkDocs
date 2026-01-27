# SpaceLink Mock API Server

Mock API Server cho Frontend SpaceLink E-Commerce - Có thể chạy độc lập mà không cần Backend thật.

## 🎯 Mục đích

Cho phép Frontend Team:
- ✅ Phát triển song song với Backend
- ✅ Test UI/UX với dữ liệu giả thực tế
- ✅ Không cần đợi Backend hoàn thành
- ✅ Demo sản phẩm với dữ liệu đẹp

## 🚀 Cài đặt và Chạy

### Bước 1: Cài đặt Node.js
Đảm bảo bạn đã cài Node.js (version >= 16)
```bash
node --version
npm --version
```

### Bước 2: Cài đặt dependencies
```bash
cd D:\WebServers\laragon6\www\SpaceLinkDocs\mock-api-server
npm install
```

### Bước 3: Chạy server
```bash
npm start
```

Hoặc chạy ở chế độ dev (auto-reload khi code thay đổi):
```bash
npm run dev
```

Server sẽ chạy tại: **http://localhost:3000**

---

## 📚 API Endpoints

### Base URL: `http://localhost:3000/api`

### 1. AUTHENTICATION

#### Đăng ký
```bash
POST /api/auth/register
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "fullname": "Nguyễn Văn A",
  "phone": "0912345678"
}
```

#### Đăng nhập
```bash
POST /api/auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Đăng nhập thành công",
  "data": {
    "user": { ... },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "expires_in": 86400
  }
}
```

### 2. PRODUCTS

#### Danh sách sản phẩm
```bash
GET /api/products?page=1&per_page=20&category_id=5&sort=price_asc
```

#### Chi tiết sản phẩm
```bash
GET /api/products/1
```

#### Sản phẩm nổi bật
```bash
GET /api/products/featured?limit=10
```

#### Danh mục
```bash
GET /api/categories
```

#### Thương hiệu
```bash
GET /api/brands
```

### 3. CART

#### Lấy giỏ hàng
```bash
GET /api/cart
```

#### Thêm vào giỏ
```bash
POST /api/cart/add
Content-Type: application/json

{
  "product_id": 1,
  "variant_id": 1,
  "quantity": 2
}
```

### 4. ORDERS

#### Tạo đơn hàng
```bash
POST /api/orders
Content-Type: application/json
Authorization: Bearer {token}

{
  "shipping_name": "Nguyễn Văn A",
  "shipping_phone": "0912345678",
  "shipping_email": "user@example.com",
  "shipping_province": "Hà Nội",
  "shipping_district": "Quận Cầu Giấy",
  "shipping_ward": "Phường Dịch Vọng",
  "shipping_address": "123 Đường ABC",
  "payment_method": "cod",
  "note": "Giao hàng giờ hành chính"
}
```

#### Danh sách đơn hàng
```bash
GET /api/orders?page=1
Authorization: Bearer {token}
```

#### Chi tiết đơn hàng
```bash
GET /api/orders/1
Authorization: Bearer {token}
```

### 5. USER PROFILE

#### Lấy thông tin profile
```bash
GET /api/user/profile
Authorization: Bearer {token}
```

#### Cập nhật profile
```bash
PUT /api/user/profile
Authorization: Bearer {token}
Content-Type: application/json

{
  "fullname": "Nguyễn Văn B",
  "phone": "0987654321",
  "date_of_birth": "1990-01-01"
}
```

---

## 🧪 Test API với Postman/Insomnia/Thunder Client

### Bước 1: Import collection

Tạo một collection mới và thêm các request theo endpoints trên.

### Bước 2: Lấy token

1. Gọi API đăng nhập `/api/auth/login`
2. Copy token từ response
3. Dùng token này cho các request cần authentication

### Bước 3: Test

Ví dụ test với **curl**:

```bash
# Đăng nhập
curl -X POST http://localhost:3000/api/auth/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"test@example.com\",\"password\":\"123456\"}"

# Lấy danh sách sản phẩm
curl http://localhost:3000/api/products?page=1&per_page=10

# Lấy giỏ hàng (cần token)
curl http://localhost:3000/api/cart \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 📱 Test với Frontend (ReactJS/ViteJS)

### Cài đặt Axios
```bash
npm install axios
```

### Tạo API service

**File:** `src/services/api.js`

```javascript
import axios from 'axios';

const API_BASE_URL = 'http://localhost:3000/api';

// Tạo axios instance
const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json'
  }
});

// Thêm token vào header tự động
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// API functions
export const authAPI = {
  login: (email, password) => api.post('/auth/login', { email, password }),
  register: (data) => api.post('/auth/register', data),
  logout: () => api.post('/auth/logout')
};

export const productAPI = {
  getList: (params) => api.get('/products', { params }),
  getDetail: (id) => api.get(`/products/${id}`),
  getFeatured: (limit = 10) => api.get('/products/featured', { params: { limit } })
};

export const cartAPI = {
  get: () => api.get('/cart'),
  add: (productId, variantId, quantity) => 
    api.post('/cart/add', { product_id: productId, variant_id: variantId, quantity }),
  update: (id, quantity) => api.put(`/cart/${id}`, { quantity }),
  remove: (id) => api.delete(`/cart/${id}`),
  clear: () => api.delete('/cart/clear')
};

export const orderAPI = {
  create: (data) => api.post('/orders', data),
  getList: (params) => api.get('/orders', { params }),
  getDetail: (id) => api.get(`/orders/${id}`),
  cancel: (id, reason) => api.post(`/orders/${id}/cancel`, { reason })
};

export const userAPI = {
  getProfile: () => api.get('/user/profile'),
  updateProfile: (data) => api.put('/user/profile', data),
  changePassword: (data) => api.put('/user/change-password', data)
};

export default api;
```

### Sử dụng trong Component

```javascript
import { useEffect, useState } from 'react';
import { productAPI } from '../services/api';

function ProductList() {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchProducts = async () => {
      try {
        const response = await productAPI.getList({ page: 1, per_page: 20 });
        setProducts(response.data.data.data);
      } catch (error) {
        console.error('Error fetching products:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchProducts();
  }, []);

  if (loading) return <div>Loading...</div>;

  return (
    <div className="product-list">
      {products.map(product => (
        <div key={product.id} className="product-card">
          <img src={product.primary_image} alt={product.name} />
          <h3>{product.name}</h3>
          <p className="price">{Number(product.price).toLocaleString('vi-VN')} đ</p>
        </div>
      ))}
    </div>
  );
}

export default ProductList;
```

---

## 🔧 Tùy chỉnh Mock Data

### Thay đổi dữ liệu brands/categories

Mở file `server.js` và chỉnh sửa:

```javascript
// Dòng 36-46: Brands
const brands = [
    { id: 1, name: 'Apple', slug: 'apple', logo: '/images/brands/apple.png' },
    // Thêm brands của bạn...
];

// Dòng 49-56: Categories
const categories = [
    { id: 1, name: 'Điện thoại', slug: 'dien-thoai', parent_id: null },
    // Thêm categories của bạn...
];
```

### Thay đổi số lượng dữ liệ giả

Trong các hàm generate, thay đổi các tham số:

```javascript
// Số lượng sản phẩm trong danh sách
const products = Array.from({ length: 20 }, generateProduct); // Thay 20 thành số khác

// Số lượng items trong giỏ hàng
const items = Array.from({ length: faker.number.int({ min: 1, max: 5 }) }, ...); // Thay min, max
```

---

## 🌐 CORS Configuration

Server đã bật CORS cho tất cả origins. Nếu cần giới hạn:

```javascript
// Trong server.js
const cors = require('cors');

app.use(cors({
  origin: 'http://localhost:5173', // URL của Vite dev server
  credentials: true
}));
```

---

## 🎓 Học và Tham khảo

### Faker.js Documentation
- Website: https://fakerjs.dev/
- Tạo dữ liệu giả chất lượng cao
- Hỗ trợ nhiều ngôn ngữ (có tiếng Việt)

### Express.js Documentation
- Website: https://expressjs.com/
- Framework Node.js để tạo API

### JWT Documentation
- Website: https://jwt.io/
- JSON Web Token cho authentication

---

## ❓ FAQ

### 1. Dữ liệu có lưu lại không?
**Không.** Mỗi lần gọi API sẽ tạo dữ liệu mới. Server chỉ là mock, không có database.

### 2. Token có thật không?
**Có.** Token là JWT thật, có thể verify được. Nhưng không kết nối với database, chỉ để test flow authen/author.

### 3. Có thể dùng cho production không?
**KHÔNG.** Đây chỉ là mock server cho development. Production phải dùng Backend thật.

### 4. Làm sao thay đổi port?
Sửa dòng `const PORT = 3000;` trong `server.js` thành port khác.

### 5. Làm sao thêm endpoint mới?
Copy một endpoint tương tự, sửa route và logic theo nhu cầu.

---

## 📞 Hỗ trợ

Nếu gặp vấn đề, kiểm tra:
1. Node.js đã cài chưa? (`node --version`)
2. Dependencies đã install? (`npm install`)
3. Port 3000 đã bị chiếm chưa?
4. CORS có lỗi không? (check browser console)

---

**Happy Coding! 🚀**
