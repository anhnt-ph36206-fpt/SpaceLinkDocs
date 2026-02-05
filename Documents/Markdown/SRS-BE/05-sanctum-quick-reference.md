# Sanctum API Token - Quick Reference
**TL;DR - Cheat Sheet cho Backend Laravel + Frontend ReactJS riêng biệt**

---

## 🎯 SETUP NHANH - 5 PHÚT

### Backend Laravel:

```bash
# 1. Check Sanctum đã cài
composer show laravel/sanctum

# 2. Run migration
php artisan migrate

# 3. KHÔNG CẦN config gì thêm trong .env!
```

**File: `config/cors.php`**
```php
'allowed_origins' => ['http://localhost:5173'],
'supports_credentials' => false, // ✅ false!
```

**File: `app/Models/User.php`**
```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // ✅
}
```

**File: `app/Http/Controllers/Api/AuthController.php`**
```php
// Login
public function login(Request $request)
{
    // ... validate ...
    
    $token = $user->createToken('auth_token')->plainTextToken;
    
    return response()->json([
        'success' => true,
        'data' => [
            'user' => $user,
            'token' => $token, // ✅ Trả về token
        ]
    ]);
}

// Logout
public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();
    return response()->json(['success' => true]);
}
```

**File: `routes/api.php`**
```php
// Public
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/profile', [AuthController::class, 'profile']);
});
```

---

### Frontend ReactJS:

**File: `src/services/api.js`**
```javascript
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
});

// ✅ Auto add token to all requests
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default api;
```

**File: `src/services/authService.js`**
```javascript
import api from './api';

const authService = {
  login: async (email, password) => {
    const response = await api.post('/auth/login', { email, password });
    
    // ✅ Save token
    localStorage.setItem('auth_token', response.data.data.token);
    localStorage.setItem('user', JSON.stringify(response.data.data.user));
    
    return response.data;
  },

  logout: async () => {
    await api.post('/auth/logout');
    
    // ✅ Remove token
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user');
  },

  isLoggedIn: () => !!localStorage.getItem('auth_token'),
};

export default authService;
```

**File: `src/pages/Login.jsx`**
```jsx
import authService from '../services/authService';

function Login() {
  const handleSubmit = async (e) => {
    e.preventDefault();
    
    try {
      await authService.login(email, password);
      navigate('/'); // ✅ Redirect after login
    } catch (error) {
      console.error(error);
    }
  };
  
  // ... form JSX ...
}
```

---

## ✅ CHECKLIST

### Backend:
- [ ] User Model có `use HasApiTokens`
- [ ] Login trả về `token` trong response
- [ ] Routes protected dùng `middleware('auth:sanctum')`
- [ ] CORS cho phép `http://localhost:5173`
- [ ] ❌ KHÔNG CÓ `SANCTUM_STATEFUL_DOMAINS` trong `.env`

### Frontend:
- [ ] Axios interceptor thêm `Authorization: Bearer {token}`
- [ ] Login → Lưu token vào `localStorage`
- [ ] Logout → Xóa token khỏi `localStorage`
- [ ] Protected routes check token

---

## 🧪 TEST NHANH

### Postman:

```
# 1. Login
POST http://localhost:8000/api/auth/login
Body: { "email": "test@test.com", "password": "password" }

# Response:
{ "data": { "token": "1|abc123..." } }

# 2. Get Profile (copy token từ bước 1)
GET http://localhost:8000/api/auth/profile
Authorization: Bearer 1|abc123...
```

### Browser Console:

```javascript
// Check token
console.log(localStorage.getItem('auth_token'));

// Check axios header
console.log(api.defaults.headers.common['Authorization']);
// Phải là: "Bearer 1|abc123..."
```

---

## 🚨 COMMON ERRORS

### ❌ "Unauthenticated"
→ Token không được gửi hoặc sai format
→ Check: `Authorization: Bearer {token}` (có space sau "Bearer")

### ❌ CORS Error
→ Backend chưa cho phép ReactJS
→ Fix: `config/cors.php` → `allowed_origins`

### ❌ "Call to undefined method createToken()"
→ User Model chưa có `HasApiTokens`
→ Fix: `use Laravel\Sanctum\HasApiTokens;`

---

## 📊 FLOW DIAGRAM

```
┌─────────────┐                    ┌──────────────┐
│   ReactJS   │                    │   Laravel    │
│ localhost:  │                    │ localhost:   │
│    5173     │                    │    8000      │
└─────────────┘                    └──────────────┘
      │                                    │
      │  POST /api/auth/login              │
      │  { email, password }               │
      │───────────────────────────────────▶│
      │                                    │
      │                                    │ Validate
      │                                    │ Create token
      │                                    │
      │  { token: "1|abc123..." }          │
      │◀───────────────────────────────────│
      │                                    │
      │ Save to localStorage               │
      │                                    │
      │                                    │
      │  GET /api/products                 │
      │  Header: Authorization:            │
      │          Bearer 1|abc123...        │
      │───────────────────────────────────▶│
      │                                    │
      │                                    │ Verify token
      │                                    │
      │  { data: [...products] }           │
      │◀───────────────────────────────────│
      │                                    │
```

---

## 🎯 KEY POINTS

1. **Backend trả về token** → Frontend lưu vào `localStorage`
2. **Mọi request sau** → Gửi token trong header `Authorization: Bearer {token}`
3. **Logout** → Xóa token khỏi `localStorage` và DB
4. **KHÔNG CẦN** config `SANCTUM_STATEFUL_DOMAINS`
5. **KHÔNG CẦN** gọi `/sanctum/csrf-cookie`

---

**Đọc file này khi:**
- ✅ Cần setup nhanh
- ✅ Cần nhìn lại flow
- ✅ Debug lỗi

**Đọc file `04-sanctum-setup-clarification.md` khi:**
- ✅ Cần hiểu chi tiết
- ✅ Cần so sánh 2 cách
- ✅ Cần code examples đầy đủ
