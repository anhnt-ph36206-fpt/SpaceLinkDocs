# 🔐 AUTHENTICATION API

**Module:** Auth  
**Priority:** 🔴 Critical  
**Độ khó:** ⭐⭐⭐⭐⭐ Trung bình  
**Thời gian:** 4-5 giờ  
**Day:** 4

---

## 🎯 MỤC TIÊU

- ✅ Setup Laravel Sanctum
- ✅ Implement Register API
- ✅ Implement Login API
- ✅ Implement Logout API
- ✅ Implement Get User Profile API
- ✅ Token-based authentication hoạt động

---

## 📋 PREREQUISITES

- ✅ Migrations đã chạy
- ✅ User model đã tạo
- ✅ Role model đã tạo

---

## 📝 STEP-BY-STEP GUIDE

### **STEP 1: Install Laravel Sanctum** (15 phút)

```bash
# Install package:
composer require laravel/sanctum

# Publish config:
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Run Sanctum migrations:
php artisan migrate

# Expected: personal_access_tokens table created
```

---

### **STEP 2: Configure Sanctum** (15 phút)

#### **File: `config/sanctum.php`**
```php
<?php

return [
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
        '%s%s',
        'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
        env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : ''
    ))),

    'guard' => ['web'],

    'expiration' => null, // Tokens never expire

    'middleware' => [
        'verify_csrf_token' => App\Http\Middleware\VerifyCsrfToken::class,
        'encrypt_cookies' => App\Http\Middleware\EncryptCookies::class,
    ],
];
```

#### **File: `app/Http/Kernel.php`** (Laravel 11-12 dùng bootstrap/app.php)

Nếu Laravel 11+:
```php
// bootstrap/app.php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

#### **File: `app/Models/User.php`**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'email',
        'password',
        'fullname',
        'phone',
        'avatar',
        'date_of_birth',
        'gender',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
    ];

    // Relationships
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }
}
```

---

### **STEP 3: Create AuthController** (2-3 giờ)

```bash
php artisan make:controller Api/V1/AuthController
```

#### **File: `app/Http/Controllers/Api/V1/AuthController.php`**

```php
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register a new user
     * POST /api/auth/register
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:150',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role_id' => 3, // Customer role
            'status' => 'active',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký thành công',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'fullname' => $user->fullname,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role->name,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ]
        ], 201);
    }

    /**
     * Login user
     * POST /api/auth/login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email hoặc mật khẩu không đúng',
            ], 401);
        }

        if ($user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản đã bị khóa',
            ], 403);
        }

        // Update last login
        $user->update(['last_login_at' => now()]);

        // Create token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'fullname' => $user->fullname,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'avatar' => $user->avatar,
                    'role' => $user->role->name,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ]
        ], 200);
    }

    /**
     * Logout user
     * POST /api/auth/logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công',
        ], 200);
    }

    /**
     * Get authenticated user
     * GET /api/auth/me
     */
    public function me(Request $request)
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
                'date_of_birth' => $user->date_of_birth,
                'gender' => $user->gender,
                'status' => $user->status,
                'role' => [
                    'id' => $user->role->id,
                    'name' => $user->role->name,
                    'display_name' => $user->role->display_name,
                ],
                'email_verified_at' => $user->email_verified_at,
                'last_login_at' => $user->last_login_at,
                'created_at' => $user->created_at,
            ]
        ], 200);
    }
}
```

---

### **STEP 4: Setup Routes** (15 phút)

#### **File: `routes/api.php`**

```php
<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});
```

---

## ✅ TESTING

### **Test với Postman/Thunder Client:**

#### **1. Register**
```http
POST http://localhost:8000/api/auth/register
Content-Type: application/json

{
    "fullname": "Nguyen Van A",
    "email": "nguyenvana@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "0123456789"
}
```

**Expected Response (201):**
```json
{
    "success": true,
    "message": "Đăng ký thành công",
    "data": {
        "user": {
            "id": 1,
            "fullname": "Nguyen Van A",
            "email": "nguyenvana@example.com",
            "phone": "0123456789",
            "role": "customer"
        },
        "token": "1|xxx...xxx",
        "token_type": "Bearer"
    }
}
```

#### **2. Login**
```http
POST http://localhost:8000/api/auth/login
Content-Type: application/json

{
    "email": "nguyenvana@example.com",
    "password": "password123"
}
```

**Expected Response (200):**
```json
{
    "success": true,
    "message": "Đăng nhập thành công",
    "data": {
        "user": {
            "id": 1,
            "fullname": "Nguyen Van A",
            "email": "nguyenvana@example.com",
            "phone": "0123456789",
            "avatar": null,
            "role": "customer"
        },
        "token": "2|yyy...yyy",
        "token_type": "Bearer"
    }
}
```

#### **3. Get User Info**
```http
GET http://localhost:8000/api/auth/me
Authorization: Bearer {your_token_here}
```

**Expected Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "fullname": "Nguyen Van A",
        "email": "nguyenvana@example.com",
        "phone": "0123456789",
        "avatar": null,
        "role": {
            "id": 3,
            "name": "customer",
            "display_name": "Khách hàng"
        },
        "created_at": "2026-01-27T10:00:00.000000Z"
    }
}
```

#### **4. Logout**
```http
POST http://localhost:8000/api/auth/logout
Authorization: Bearer {your_token_here}
```

**Expected Response (200):**
```json
{
    "success": true,
    "message": "Đăng xuất thành công"
}
```

---

## 📋 CHECKLIST

- [ ] Sanctum installed
- [ ] Sanctum configured
- [ ] User model updated (HasApiTokens)
- [ ] AuthController created
- [ ] Routes configured
- [ ] Register endpoint works
- [ ] Login endpoint works
- [ ] Logout endpoint works
- [ ] Me endpoint works
- [ ] Token authentication works
- [ ] Validation works
- [ ] Error handling works
- [ ] Postman collection created

---

## 🚨 TROUBLESHOOTING

### **Problem: 401 Unauthenticated**
```
# Check token format:
Authorization: Bearer {token}

# Not:
Authorization: {token}
```

### **Problem: CORS errors**
```php
// config/cors.php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_origins' => ['http://localhost:3000'],
'supports_credentials' => true,
```

---

## ✅ DELIVERABLES

- [ ] Authentication API working
- [ ] 4 endpoints tested
- [ ] Ready for Admin CRUD

**Next:** `features/04_admin_brands.md`

---

**Last updated:** 2026-01-27
