# HƯỚNG DẪN CHO FRONTEND TEAM

## 📋 TÓM TẮT

File `API_DOCUMENTATION.md` do Backend phân tích từ các bảng chức năng. Backend ĐANG LÀM nhưng chưa xong.

**Frontend có thể làm gì NGAY BÂY GIỜ?**

✅ Sử dụng **Mock API Server** để phát triển song song!

---

## 🎯 FRONTEND TEAM SẼ HIỂU ĐƯỢC GÌ TỪ FILE API_DOCUMENTATION.md?

### 1. **API Endpoints** (URL và HTTP Methods)
```
POST /api/auth/login      → Đăng nhập
GET  /api/products        → Danh sách sản phẩm
POST /api/cart/add        → Thêm vào giỏ hàng
...
```

### 2. **Request Format** (Gửi data kiểu gì)
```json
POST /api/auth/login
Body: {
  "email": "user@example.com",
  "password": "password123"
}
```

### 3. **Response Format** (Nhận data kiểu gì)
```json
{
  "success": true,
  "message": "Đăng nhập thành công",
  "data": {
    "user": { ... },
    "token": "..."
  }
}
```

### 4. **HTTP Headers** (Authorization, Content-Type,...)
```
Authorization: Bearer {token}
Content-Type: application/json
```

### 5. **Query Parameters** (Phân trang, lọc, tìm kiếm)
```
GET /api/products?page=1&per_page=20&category_id=5&sort=price_asc
```

### 6. **Error Handling** (Xử lý lỗi)
```json
// 401 Unauthorized
{
  "success": false,
  "message": "Email hoặc mật khẩu không đúng"
}

// 422 Validation Error
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

## 🚀 QUY TRÌNH LÀM VIỆC SONG SONG FE + BE

### Giai đoạn 1: Backend đang làm (HIỆN TẠI)

**Backend Team:**
- Phân tích chức năng
- Thiết kế database
- Viết API (chưa xong)

**Frontend Team:**
- ✅ Đọc file `API_DOCUMENTATION.md`
- ✅ Hiểu rõ API format
- ✅ **SỬ DỤNG MOCK API SERVER** để code UI/UX
- ✅ Integration với Mock API
- ✅ Test flow với dữ liệu giả

### Giai đoạn 2: Backend xong

**Frontend Team:**
- ⚡ Chỉ cần **thay đổi Base URL** trong code
- Từ `http://localhost:3000/api` → `https://api.spacelink.com/v1/api`
- XONG! Không cần sửa gì thêm

---

## 🔧 CÔNG CỤ TEST API

### 1. **Postman** (Khuyên dùng)
- Website: https://www.postman.com/downloads/
- Cài đặt: Tải về và cài đặt
- Tính năng:
  - ✅ Test API dễ dàng
  - ✅ Lưu collection
  - ✅ Environment variables
  - ✅ Test automation

**Cách sử dụng:**
1. Mở Postman
2. Tạo request mới: `POST http://localhost:3000/api/auth/login`
3. Chọn Body → raw → JSON
4. Nhập:
```json
{
  "email": "test@example.com",
  "password": "123456"
}
```
5. Click Send
6. Xem response và copy token
7. Dùng token cho các request khác

---

### 2. **Thunder Client** (VS Code Extension)
- Cài đặt: VS Code Extensions → Search "Thunder Client"
- Tương tự Postman nhưng ngay trong VS Code
- Nhẹ hơn, nhanh hơn

---

### 3. **Insomnia**
- Website: https://insomnia.rest/download
- Tương tự Postman
- UI đẹp hơn một chút

---

### 4. **cURL** (Command Line)
```bash
# Đăng nhập
curl -X POST http://localhost:3000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"123456"}'

# Lấy sản phẩm
curl http://localhost:3000/api/products?page=1
```

---

## 🌐 WEBSITE KIẾM DỮ LIỆU GIẢ

Mock Server đã tự tạo dữ liệu giả bằng **Faker.js**, nhưng nếu bạn muốn dữ liệu đẹp hơn:

### 1. **Faker.js** (Đã tích hợp sẵn trong Mock Server)
- Website: https://fakerjs.dev/
- Tạo: Tên, email, số điện thoại, địa chỉ, ngày tháng,...
- Có hỗ trợ tiếng Việt!

**Ví dụ:**
```javascript
import { faker } from '@faker-js/faker';

faker.person.fullName();        // "Nguyễn Văn A"
faker.internet.email();          // "user@example.com"
faker.phone.number('09########'); // "0912345678"
faker.commerce.productName();    // "iPhone 15 Pro Max"
faker.commerce.price();          // "29990000"
```

---

### 2. **JSONPlaceholder**
- Website: https://jsonplaceholder.typicode.com/
- Cung cấp REST API giả miễn phí
- Dùng để test HTTP requests

---

### 3. **Mockaroo**
- Website: https://www.mockaroo.com/
- Tạo dữ liệu giả theo schema tùy chỉnh
- Export ra JSON, CSV, SQL

---

### 4. **Lorem Picsum** (Ảnh giả)
- Website: https://picsum.photos/
- Ảnh random đẹp
- Ví dụ: `https://picsum.photos/800/600` → Ảnh 800x600px

---

### 5. **UI Faces** (Avatar giả)
- Website: https://uifaces.co/
- Avatar người thật đẹp
- Dùng cho profile pictures

---

## 📱 INTEGRATION VỚI REACT/VUE/ANGULAR

### React Example (đã có trong README.md)

```javascript
// src/services/api.js
import axios from 'axios';

const API = axios.create({
  baseURL: 'http://localhost:3000/api'  // ← Mock API
  // baseURL: 'https://api.spacelink.com/v1/api'  // ← Real API (sau này)
});

// Thêm token tự động
API.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export const login = (email, password) => 
  API.post('/auth/login', { email, password });

export const getProducts = (params) => 
  API.get('/products', { params });
```

### Vue.js Example

```javascript
// src/services/api.js
import axios from 'axios';

const API = axios.create({
  baseURL: 'http://localhost:3000/api'
});

API.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default {
  auth: {
    login: (email, password) => API.post('/auth/login', { email, password }),
    register: (data) => API.post('/auth/register', data)
  },
  products: {
    getList: (params) => API.get('/products', { params }),
    getDetail: (id) => API.get(`/products/${id}`)
  }
};
```

---

## 🎨 MẪU CODE INTEGRATION

### 1. Login Page

```javascript
import { useState } from 'react';
import { authAPI } from '../services/api';

function LoginPage() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const handleLogin = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');

    try {
      const response = await authAPI.login(email, password);
      const { token, user } = response.data.data;
      
      // Lưu token
      localStorage.setItem('token', token);
      localStorage.setItem('user', JSON.stringify(user));
      
      // Chuyển hướng
      window.location.href = '/';
    } catch (err) {
      setError(err.response?.data?.message || 'Đăng nhập thất bại');
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handleLogin}>
      <input
        type="email"
        value={email}
        onChange={(e) => setEmail(e.target.value)}
        placeholder="Email"
        required
      />
      <input
        type="password"
        value={password}
        onChange={(e) => setPassword(e.target.value)}
        placeholder="Mật khẩu"
        required
      />
      {error && <div className="error">{error}</div>}
      <button type="submit" disabled={loading}>
        {loading ? 'Đang đăng nhập...' : 'Đăng nhập'}
      </button>
    </form>
  );
}
```

---

### 2. Product List Page

```javascript
import { useEffect, useState } from 'react';
import { productAPI } from '../services/api';

function ProductListPage() {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [total, setTotal] = useState(0);

  useEffect(() => {
    fetchProducts();
  }, [page]);

  const fetchProducts = async () => {
    try {
      setLoading(true);
      const response = await productAPI.getList({ 
        page, 
        per_page: 20,
        sort: 'newest'
      });
      
      const { data, total } = response.data.data;
      setProducts(data);
      setTotal(total);
    } catch (error) {
      console.error('Error:', error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) return <div>Loading...</div>;

  return (
    <div>
      <h1>Sản phẩm ({total})</h1>
      <div className="product-grid">
        {products.map(product => (
          <div key={product.id} className="product-card">
            <img src={product.primary_image} alt={product.name} />
            <h3>{product.name}</h3>
            <p className="price">
              {Number(product.sale_price || product.price).toLocaleString('vi-VN')} đ
            </p>
            <button>Thêm vào giỏ</button>
          </div>
        ))}
      </div>
      <div className="pagination">
        <button 
          onClick={() => setPage(p => Math.max(1, p - 1))}
          disabled={page === 1}
        >
          Trang trước
        </button>
        <span>Trang {page}</span>
        <button onClick={() => setPage(p => p + 1)}>
          Trang sau
        </button>
      </div>
    </div>
  );
}
```

---

### 3. Add to Cart

```javascript
import { cartAPI } from '../services/api';
import { toast } from 'react-toastify'; // Hoặc notification library khác

const handleAddToCart = async (productId, variantId, quantity = 1) => {
  try {
    await cartAPI.add(productId, variantId, quantity);
    toast.success('Đã thêm vào giỏ hàng!');
    
    // Cập nhật số lượng giỏ hàng ở header
    // Có thể dùng Redux, Context API, hoặc gọi lại API
  } catch (error) {
    toast.error('Thêm vào giỏ thất bại');
    console.error(error);
  }
};
```

---

## 🔐 XỬ LÝ AUTHENTICATION

### Lưu token sau khi login

```javascript
// Sau khi login thành công
const { token, user } = response.data.data;
localStorage.setItem('token', token);
localStorage.setItem('user', JSON.stringify(user));
```

### Tự động gửi token với mỗi request

```javascript
// Trong axios interceptor (đã có trong api.js)
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});
```

### Xử lý token hết hạn

```javascript
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Token hết hạn hoặc không hợp lệ
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);
```

---

## 📊 TÓM TẮT - FRONTEND TEAM CẦN LÀM GÌ?

### Bước 1: Hiểu API Document
✅ Đọc `API_DOCUMENTATION.md`
✅ Hiểu các endpoints, request/response format

### Bước 2: Chạy Mock API Server
✅ `cd mock-api-server`
✅ `npm install`
✅ `npm start`

### Bước 3: Test API
✅ Dùng Postman/Thunder Client test từng endpoint
✅ Hiểu rõ flow: Login → Get token → Call API với token

### Bước 4: Tích hợp vào Frontend
✅ Tạo `api.js` service
✅ Gọi Mock API từ components
✅ Hiển thị dữ liệu, xử lý lỗi

### Bước 5: Khi Backend xong
✅ Thay đổi Base URL từ `localhost:3000` → URL thật
✅ DONE!

---

## 💡 LỢI ÍCH CỦA SỬ DỤNG MOCK API

1. ✅ **Không phải đợi Backend** - Làm song song
2. ✅ **Test UI/UX sớm** - Phát hiện bug UI sớm
3. ✅ **Demo được sớm** - Có dữ liệu đẹp để demo
4. ✅ **Hiểu rõ API Contract** - Biết Backend sẽ trả về gì
5. ✅ **Dễ dàng switch** - Chỉ đổi Base URL khi Backend xong

---

## ❓ FAQ

**Q: Mock API có giống thật 100% không?**
A: Gần giống. Có thể có 1-2% khác biệt nhỏ khi Backend triển khai thật.

**Q: Dữ liệu có lưu lại không?**
A: Không. Mỗi lần gọi API sẽ tạo dữ liệu mới.

**Q: Có thể thêm endpoint không?**
A: Có. Sửa file `server.js`, thêm route mới.

**Q: Khi nào nên chuyển sang Real API?**
A: Khi Backend Team thông báo API đã sẵn sàng.

---

**Happy Coding! 🚀**
