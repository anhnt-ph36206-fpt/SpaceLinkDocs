# Laravel Sanctum Setup - GIẢI THÍCH CHI TIẾT
**Giải quyết sự nhầm lẫn giữa API Token vs SPA Cookie**  
**Date:** 2026-01-29

---

## 🤔 VẤN ĐỀ BẠN ĐANG GẶP

Bạn đang **bối rối** giữa 2 cách setup Laravel Sanctum:

### ❓ **Cách 1: API Token (Bearer Token)** 
- Backend Laravel **RIÊNG BIỆT** với Frontend ReactJS
- ReactJS chạy trên port khác (Vite: `localhost:5173`)
- Laravel API chạy trên `localhost:8000`
- Dùng **Bearer Token** trong header

### ❓ **Cách 2: SPA Cookie (Session-based)**
- Backend Laravel **TÍCH HỢP** với Frontend ReactJS
- ReactJS build vào `public/` của Laravel
- Cùng domain, cùng port
- Dùng **Cookie/Session** (như Laravel Breeze/Jetstream)

---

## ✅ QUYẾT ĐỊNH CHO PROJECT CỦA BẠN

### 🎯 **BẠN NÊN DÙNG: CÁCH 1 - API TOKEN (BEARER TOKEN)**

**Lý do:**

1. ✅ **Backend và Frontend RIÊNG BIỆT** (đúng với yêu cầu của bạn)
   - Backend: Laravel 12 (port 8000)
   - Frontend: ReactJS 19 + Vite (port 5173)
   - FE team làm riêng, BE team làm riêng

2. ✅ **Đơn giản hơn cho team yếu**
   - Không cần config CORS phức tạp
   - Không cần config `SANCTUM_STATEFUL_DOMAINS`
   - Chỉ cần gửi token trong header

3. ✅ **Phù hợp với API-first approach**
   - FE có thể test API bằng Postman dễ dàng
   - Mobile app (nếu có sau này) cũng dùng được

4. ✅ **Deploy dễ dàng hơn**
   - Backend deploy lên server riêng
   - Frontend deploy lên Vercel/Netlify
   - Không cần lo về domain/subdomain

---

## 🚫 TẠI SAO KHÔNG DÙNG CÁCH 2 (SPA COOKIE)?

### ❌ **Cách 2 chỉ phù hợp khi:**
- Backend và Frontend **CÙNG DOMAIN** (ví dụ: `spacelink.com`)
- Dùng Laravel Blade hoặc Inertia.js
- Dùng Laravel Breeze/Jetstream starter kit
- Không cần API cho mobile app

### ❌ **Vấn đề nếu dùng Cách 2 với setup của bạn:**
- Phải config CORS phức tạp
- Phải config `SANCTUM_STATEFUL_DOMAINS` đúng
- Phải gọi `/sanctum/csrf-cookie` trước mỗi request
- FE team khó test API (không dùng Postman được)
- Deploy phức tạp (phải cùng domain hoặc subdomain)

---

## 📚 SO SÁNH CHI TIẾT 2 CÁCH

| Tiêu chí | Cách 1: API Token ✅ | Cách 2: SPA Cookie ❌ |
|----------|---------------------|----------------------|
| **Setup** | Đơn giản | Phức tạp (CORS, CSRF) |
| **Backend/FE** | Riêng biệt | Cùng domain |
| **Port** | Khác nhau (8000 vs 5173) | Cùng port |
| **Authentication** | Bearer Token | Cookie/Session |
| **Header** | `Authorization: Bearer {token}` | Cookie tự động |
| **Test Postman** | ✅ Dễ dàng | ❌ Khó (cần cookie) |
| **Mobile App** | ✅ Dùng được | ❌ Không dùng được |
| **Deploy** | ✅ Riêng biệt | ❌ Cùng server |
| **Team yếu** | ✅ Phù hợp | ❌ Khó hiểu |
| **CORS** | Không cần config | Phải config |
| **CSRF** | Không cần | Phải handle |

---

## 🔧 HƯỚNG DẪN SETUP ĐÚNG - CÁCH 1 (API TOKEN)

### 📋 **OVERVIEW:**

```
┌─────────────────────────────────────────────────────────────┐
│                    ARCHITECTURE                              │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  Frontend (ReactJS + Vite)          Backend (Laravel 12)    │
│  ┌──────────────────────┐            ┌──────────────────┐   │
│  │  localhost:5173      │            │  localhost:8000  │   │
│  │                      │            │                  │   │
│  │  - Login Form        │  ────────▶ │  POST /api/auth/ │   │
│  │  - Send email/pass   │            │       login      │   │
│  │                      │  ◀────────  │                  │   │
│  │  - Receive token     │            │  Return token    │   │
│  │  - Store in         │            │                  │   │
│  │    localStorage      │            │                  │   │
│  │                      │            │                  │   │
│  │  - Fetch products    │  ────────▶ │  GET /api/       │   │
│  │  - Header:           │            │      products    │   │
│  │    Authorization:    │            │                  │   │
│  │    Bearer {token}    │            │  Verify token    │   │
│  │                      │  ◀────────  │  Return data     │   │
│  └──────────────────────┘            └──────────────────┘   │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

---

### 🛠️ **STEP 1: Laravel Backend Setup**

#### 1.1. Install Sanctum (Đã có sẵn trong Laravel 12)

```bash
# Check composer.json
# Phải có: "laravel/sanctum": "^4.0"

# Nếu chưa có, install:
composer require laravel/sanctum
```

#### 1.2. Publish Config (Optional)

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

#### 1.3. Run Migration

```bash
php artisan migrate
```

Sẽ tạo bảng `personal_access_tokens` để lưu token.

#### 1.4. Config `.env` - **QUAN TRỌNG!**

```env
# .env

# App
APP_NAME=SpaceLink
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=spacelink_production
DB_USERNAME=root
DB_PASSWORD=

# Session (Không quan trọng cho API Token)
SESSION_DRIVER=file

# Sanctum - KHÔNG CẦN CONFIG GÌ THÊM CHO API TOKEN!
# Các dòng này CHỈ CẦN cho SPA Cookie (Cách 2)
# SANCTUM_STATEFUL_DOMAINS=localhost:5173  ❌ KHÔNG CẦN
# SESSION_DOMAIN=localhost                 ❌ KHÔNG CẦN
```

**⚠️ LƯU Ý:** Với **API Token**, bạn **KHÔNG CẦN** config `SANCTUM_STATEFUL_DOMAINS`!

#### 1.5. Config `bootstrap/app.php` - **KHÔNG CẦN SỬA GÌ!**

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ❌ KHÔNG CẦN dòng này cho API Token:
        // $middleware->api(prepend: [
        //     \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        // ]);
        
        // ✅ GIỮ NGUYÊN MẶC ĐỊNH LÀ ĐỦ!
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

**⚠️ LƯU Ý:** Middleware `EnsureFrontendRequestsAreStateful` chỉ cần cho **SPA Cookie** (Cách 2). Với **API Token**, KHÔNG CẦN!

#### 1.6. Config CORS (Cho phép ReactJS gọi API)

**File: `config/cors.php`**

```php
<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['http://localhost:5173'], // ReactJS Vite port

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false, // ✅ false cho API Token
];
```

**⚠️ LƯU Ý:** `supports_credentials` phải là `false` cho API Token!

---

### 🛠️ **STEP 2: Create Auth Controller**

**File: `app/Http/Controllers/Api/AuthController.php`**

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register new user
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|regex:/^0[0-9]{9}$/',
        ]);

        $user = User::create([
            'role_id' => 3, // customer
            'fullname' => $validated['fullname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'status' => 'active',
        ]);

        // ✅ TẠO TOKEN - ĐÂY LÀ ĐIỂM KHÁC BIỆT!
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký thành công',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'fullname' => $user->fullname,
                    'email' => $user->email,
                    'role' => $user->role->name,
                ],
                'token' => $token, // ✅ TRẢ VỀ TOKEN
            ]
        ], 201);
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email hoặc mật khẩu không đúng'],
            ]);
        }

        if ($user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản của bạn đã bị khóa',
            ], 403);
        }

        // Update last login
        $user->update(['last_login_at' => now()]);

        // ✅ TẠO TOKEN
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'fullname' => $user->fullname,
                    'email' => $user->email,
                    'role' => $user->role->name,
                ],
                'token' => $token, // ✅ TRẢ VỀ TOKEN
            ]
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        // ✅ XÓA TOKEN HIỆN TẠI
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công',
        ]);
    }

    /**
     * Get user profile
     */
    public function profile(Request $request)
    {
        $user = $request->user()->load('role');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'fullname' => $user->fullname,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => $user->avatar,
                'role' => $user->role->name,
            ]
        ]);
    }
}
```

---

### 🛠️ **STEP 3: Setup Routes**

**File: `routes/api.php`**

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// ✅ PUBLIC ROUTES (Không cần token)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// ✅ PROTECTED ROUTES (Cần token)
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
    });
    
    // Thêm các routes khác cần auth ở đây
});
```

---

### 🛠️ **STEP 4: Update User Model**

**File: `app/Models/User.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // ✅ QUAN TRỌNG!

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // ✅ PHẢI CÓ HasApiTokens

    protected $fillable = [
        'role_id',
        'fullname',
        'email',
        'password',
        'phone',
        'avatar',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
```

---

### 🛠️ **STEP 5: Test Backend với Postman**

#### 5.1. Register

```
POST http://localhost:8000/api/auth/register
Content-Type: application/json

{
  "fullname": "Test User",
  "email": "test@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "0123456789"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Đăng ký thành công",
  "data": {
    "user": {
      "id": 1,
      "fullname": "Test User",
      "email": "test@example.com",
      "role": "customer"
    },
    "token": "1|abc123xyz456..." // ✅ COPY TOKEN NÀY
  }
}
```

#### 5.2. Login

```
POST http://localhost:8000/api/auth/login
Content-Type: application/json

{
  "email": "test@example.com",
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
    "token": "2|def456uvw789..." // ✅ COPY TOKEN NÀY
  }
}
```

#### 5.3. Get Profile (Protected)

```
GET http://localhost:8000/api/auth/profile
Authorization: Bearer 2|def456uvw789... // ✅ PASTE TOKEN VÀO ĐÂY
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "fullname": "Test User",
    "email": "test@example.com",
    ...
  }
}
```

---

## 🎨 FRONTEND REACTJS SETUP

### 📋 **OVERVIEW:**

```javascript
// Flow:
1. User nhập email/password
2. Gọi API POST /api/auth/login
3. Nhận token từ response
4. Lưu token vào localStorage
5. Mọi request sau đó gửi token trong header:
   Authorization: Bearer {token}
```

---

### 🛠️ **STEP 1: Create API Service**

**File: `src/services/api.js`**

```javascript
import axios from 'axios';

// ✅ Base URL của Laravel API
const API_URL = 'http://localhost:8000/api';

// ✅ Tạo axios instance
const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// ✅ Interceptor: Tự động thêm token vào mọi request
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// ✅ Interceptor: Handle 401 Unauthorized
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Token hết hạn hoặc không hợp lệ
      localStorage.removeItem('auth_token');
      localStorage.removeItem('user');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default api;
```

---

### 🛠️ **STEP 2: Create Auth Service**

**File: `src/services/authService.js`**

```javascript
import api from './api';

const authService = {
  // ✅ Register
  register: async (data) => {
    const response = await api.post('/auth/register', data);
    if (response.data.success) {
      // Lưu token và user info
      localStorage.setItem('auth_token', response.data.data.token);
      localStorage.setItem('user', JSON.stringify(response.data.data.user));
    }
    return response.data;
  },

  // ✅ Login
  login: async (email, password) => {
    const response = await api.post('/auth/login', { email, password });
    if (response.data.success) {
      // Lưu token và user info
      localStorage.setItem('auth_token', response.data.data.token);
      localStorage.setItem('user', JSON.stringify(response.data.data.user));
    }
    return response.data;
  },

  // ✅ Logout
  logout: async () => {
    try {
      await api.post('/auth/logout');
    } finally {
      // Xóa token và user info
      localStorage.removeItem('auth_token');
      localStorage.removeItem('user');
    }
  },

  // ✅ Get Profile
  getProfile: async () => {
    const response = await api.get('/auth/profile');
    return response.data;
  },

  // ✅ Check if logged in
  isLoggedIn: () => {
    return !!localStorage.getItem('auth_token');
  },

  // ✅ Get current user
  getCurrentUser: () => {
    const user = localStorage.getItem('user');
    return user ? JSON.parse(user) : null;
  },
};

export default authService;
```

---

### 🛠️ **STEP 3: Create Login Component**

**File: `src/pages/Login.jsx`**

```jsx
import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import authService from '../services/authService';

function Login() {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    email: '',
    password: '',
  });
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value,
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);

    try {
      // ✅ Gọi API login
      const response = await authService.login(formData.email, formData.password);
      
      if (response.success) {
        // ✅ Login thành công, chuyển về trang chủ
        navigate('/');
      }
    } catch (err) {
      // ✅ Hiển thị lỗi
      if (err.response?.data?.errors?.email) {
        setError(err.response.data.errors.email[0]);
      } else {
        setError('Đã xảy ra lỗi. Vui lòng thử lại.');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="login-container">
      <h2>Đăng nhập</h2>
      
      {error && <div className="error-message">{error}</div>}
      
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label>Email:</label>
          <input
            type="email"
            name="email"
            value={formData.email}
            onChange={handleChange}
            required
          />
        </div>

        <div className="form-group">
          <label>Mật khẩu:</label>
          <input
            type="password"
            name="password"
            value={formData.password}
            onChange={handleChange}
            required
          />
        </div>

        <button type="submit" disabled={loading}>
          {loading ? 'Đang đăng nhập...' : 'Đăng nhập'}
        </button>
      </form>
    </div>
  );
}

export default Login;
```

---

### 🛠️ **STEP 4: Create Protected Route**

**File: `src/components/ProtectedRoute.jsx`**

```jsx
import { Navigate } from 'react-router-dom';
import authService from '../services/authService';

function ProtectedRoute({ children }) {
  // ✅ Check if user is logged in
  if (!authService.isLoggedIn()) {
    // ✅ Chưa login, redirect về trang login
    return <Navigate to="/login" replace />;
  }

  // ✅ Đã login, cho phép truy cập
  return children;
}

export default ProtectedRoute;
```

---

### 🛠️ **STEP 5: Setup Routes**

**File: `src/App.jsx`**

```jsx
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Login from './pages/Login';
import Home from './pages/Home';
import Products from './pages/Products';
import ProtectedRoute from './components/ProtectedRoute';

function App() {
  return (
    <BrowserRouter>
      <Routes>
        {/* Public routes */}
        <Route path="/login" element={<Login />} />
        
        {/* Protected routes */}
        <Route
          path="/"
          element={
            <ProtectedRoute>
              <Home />
            </ProtectedRoute>
          }
        />
        
        <Route
          path="/products"
          element={
            <ProtectedRoute>
              <Products />
            </ProtectedRoute>
          }
        />
      </Routes>
    </BrowserRouter>
  );
}

export default App;
```

---

### 🛠️ **STEP 6: Example - Fetch Products**

**File: `src/pages/Products.jsx`**

```jsx
import { useState, useEffect } from 'react';
import api from '../services/api';

function Products() {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchProducts();
  }, []);

  const fetchProducts = async () => {
    try {
      // ✅ Token tự động được thêm vào header bởi interceptor
      const response = await api.get('/products');
      setProducts(response.data.data);
    } catch (error) {
      console.error('Error fetching products:', error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) return <div>Loading...</div>;

  return (
    <div>
      <h2>Danh sách sản phẩm</h2>
      <div className="products-grid">
        {products.map((product) => (
          <div key={product.id} className="product-card">
            <img src={product.image} alt={product.name} />
            <h3>{product.name}</h3>
            <p>{product.price.toLocaleString()} đ</p>
          </div>
        ))}
      </div>
    </div>
  );
}

export default Products;
```

---

## ✅ TÓM TẮT - ĐIỂM KHÁC BIỆT CHÍNH

### **API Token (Cách 1) - BẠN DÙNG CÁCH NÀY:**

| Bước | Backend | Frontend |
|------|---------|----------|
| **1. Login** | Trả về `token` trong response | Lưu `token` vào `localStorage` |
| **2. Mọi request sau** | Verify token từ header `Authorization: Bearer {token}` | Gửi token trong header mọi request |
| **3. Logout** | Xóa token khỏi DB | Xóa token khỏi `localStorage` |

**Config cần:**
- ✅ CORS: `allowed_origins` = `http://localhost:5173`
- ✅ User Model: `use HasApiTokens`
- ❌ KHÔNG CẦN: `SANCTUM_STATEFUL_DOMAINS`
- ❌ KHÔNG CẦN: `EnsureFrontendRequestsAreStateful` middleware

---

### **SPA Cookie (Cách 2) - KHÔNG DÙNG:**

| Bước | Backend | Frontend |
|------|---------|----------|
| **1. CSRF Cookie** | Trả về CSRF token | Gọi `/sanctum/csrf-cookie` trước |
| **2. Login** | Set cookie tự động | Cookie tự động lưu |
| **3. Mọi request sau** | Verify cookie | Cookie tự động gửi |

**Config cần:**
- ✅ CORS: `supports_credentials` = `true`
- ✅ `SANCTUM_STATEFUL_DOMAINS` = `localhost:5173`
- ✅ `EnsureFrontendRequestsAreStateful` middleware
- ✅ Gọi `/sanctum/csrf-cookie` trước mỗi request

---

## 🎯 CHECKLIST - ĐẢM BẢO ĐÚNG

### Backend:
- [ ] `composer.json` có `laravel/sanctum: ^4.0`
- [ ] Migration `personal_access_tokens` đã chạy
- [ ] User Model có `use HasApiTokens`
- [ ] `.env` có `APP_URL=http://localhost:8000`
- [ ] `config/cors.php` có `allowed_origins` = `http://localhost:5173`
- [ ] `config/cors.php` có `supports_credentials` = `false`
- [ ] ❌ KHÔNG CÓ `SANCTUM_STATEFUL_DOMAINS` trong `.env`
- [ ] ❌ KHÔNG CÓ `EnsureFrontendRequestsAreStateful` trong `bootstrap/app.php`

### Frontend:
- [ ] Axios instance có `baseURL` = `http://localhost:8000/api`
- [ ] Interceptor tự động thêm `Authorization: Bearer {token}`
- [ ] Login thành công → Lưu token vào `localStorage`
- [ ] Logout → Xóa token khỏi `localStorage`
- [ ] Protected routes check `localStorage.getItem('auth_token')`

---

## 🚨 TROUBLESHOOTING

### ❌ Lỗi: CORS Error

**Nguyên nhân:** Backend chưa cho phép ReactJS gọi API

**Giải pháp:**
```php
// config/cors.php
'allowed_origins' => ['http://localhost:5173'],
```

### ❌ Lỗi: 401 Unauthorized

**Nguyên nhân:** Token không được gửi hoặc sai format

**Giải pháp:**
```javascript
// Check header
console.log(api.defaults.headers.common['Authorization']);
// Phải là: "Bearer 1|abc123..."
```

### ❌ Lỗi: Token not found

**Nguyên nhân:** User Model chưa có `HasApiTokens`

**Giải pháp:**
```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
}
```

---

## 📞 KẾT LUẬN

### ✅ **BẠN NÊN:**
1. Dùng **API Token (Bearer Token)** - Cách 1
2. Backend Laravel riêng (port 8000)
3. Frontend ReactJS riêng (port 5173)
4. Gửi token trong header `Authorization: Bearer {token}`
5. Lưu token trong `localStorage`

### ❌ **BẠN KHÔNG NÊN:**
1. Dùng SPA Cookie (Cách 2)
2. Config `SANCTUM_STATEFUL_DOMAINS`
3. Dùng `EnsureFrontendRequestsAreStateful` middleware
4. Gọi `/sanctum/csrf-cookie`

---

**File này giải quyết hoàn toàn sự nhầm lẫn của bạn!** 🎉

**Next steps:**
1. Đọc kỹ file này
2. Follow **STEP 1-5 Backend**
3. Test bằng Postman
4. Follow **STEP 1-6 Frontend**
5. Test login/logout

**Good luck!** 🚀
