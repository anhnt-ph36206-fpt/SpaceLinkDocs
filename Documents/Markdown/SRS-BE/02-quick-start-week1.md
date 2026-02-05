# Quick Start Guide - Week 1 Implementation
**SpaceLink Backend - Ngày 1-7**  
**Goal:** Setup foundation + Core APIs cho FE team

---

## 🚀 DAY 1: Database & Auth Setup

### Morning (3 giờ) - Backend Lead

#### 1. Clean Setup Project Chính
```bash
# Navigate to project chính
cd D:\WebServers\laragon6\www\SpaceLink-Projects\SL-SRS\SRS-BE

# Nếu chưa có project Laravel, tạo mới
laravel new backend --php=8.2

# Hoặc copy từ project test
# Nhưng CLEAN trước (xóa .env, vendor, node_modules)
```

#### 2. Config Database
```env
# .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=spacelink_production
DB_USERNAME=root
DB_PASSWORD=

# Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost:5173,127.0.0.1:5173
SESSION_DRIVER=cookie
```

#### 3. Copy Migrations từ project test
```bash
# Copy tất cả migrations từ project đã test
cp D:\WebServers\laragon6\www\SpaceLink-Projects\SL-API-Database-Test\SpaceLink-API-Test\backend\database\migrations\2026_*.php ./database/migrations/

# Run migrations
php artisan migrate:fresh

# Check
php artisan migrate:status
```

#### 4. Setup Sanctum
```bash
# Publish config (optional)
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Add to bootstrap/app.php (Laravel 12)
```

**File: `bootstrap/app.php`**
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
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

---

### Afternoon (4 giờ) - Backend Lead + Interns

#### 5. Create Seeders (Phân công)

**Lead: RolePermissionSeeder**
```bash
php artisan make:seeder RolePermissionSeeder
```

**File: `database/seeders/RolePermissionSeeder.php`**
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'Quản trị viên',
            'description' => 'Có toàn quyền quản lý hệ thống'
        ]);

        $staff = Role::create([
            'name' => 'staff',
            'display_name' => 'Nhân viên',
            'description' => 'Quản lý đơn hàng và sản phẩm'
        ]);

        $customer = Role::create([
            'name' => 'customer',
            'display_name' => 'Khách hàng',
            'description' => 'Người dùng mua hàng'
        ]);

        // Permissions
        $permissions = [
            ['name' => 'dashboard.view', 'display_name' => 'Xem Dashboard', 'group_name' => 'dashboard'],
            ['name' => 'products.view', 'display_name' => 'Xem sản phẩm', 'group_name' => 'products'],
            ['name' => 'products.create', 'display_name' => 'Thêm sản phẩm', 'group_name' => 'products'],
            ['name' => 'products.edit', 'display_name' => 'Sửa sản phẩm', 'group_name' => 'products'],
            ['name' => 'products.delete', 'display_name' => 'Xóa sản phẩm', 'group_name' => 'products'],
            ['name' => 'orders.view', 'display_name' => 'Xem đơn hàng', 'group_name' => 'orders'],
            ['name' => 'orders.edit', 'display_name' => 'Sửa đơn hàng', 'group_name' => 'orders'],
        ];

        foreach ($permissions as $perm) {
            Permission::create($perm);
        }

        // Assign all permissions to admin
        $admin->permissions()->attach(Permission::all());

        // Assign limited permissions to staff
        $staff->permissions()->attach(Permission::whereIn('name', [
            'dashboard.view',
            'products.view',
            'orders.view',
            'orders.edit'
        ])->get());
    }
}
```

**Intern 1: UserSeeder**
```bash
php artisan make:seeder UserSeeder
```

**File: `database/seeders/UserSeeder.php`**
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'role_id' => 1, // admin
            'fullname' => 'Admin SpaceLink',
            'email' => 'admin@spacelink.com',
            'password' => Hash::make('password'),
            'phone' => '0123456789',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Customer 1
        User::create([
            'role_id' => 3, // customer
            'fullname' => 'Nguyễn Văn A',
            'email' => 'customer1@test.com',
            'password' => Hash::make('password'),
            'phone' => '0987654321',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Customer 2
        User::create([
            'role_id' => 3,
            'fullname' => 'Trần Thị B',
            'email' => 'customer2@test.com',
            'password' => Hash::make('password'),
            'phone' => '0912345678',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}
```

**Intern 2: BrandCategorySeeder**
```bash
php artisan make:seeder BrandCategorySeeder
```

**File: `database/seeders/BrandCategorySeeder.php`**
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Category;

class BrandCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Brands
        $brands = [
            ['name' => 'Apple', 'slug' => 'apple', 'is_active' => true, 'display_order' => 1],
            ['name' => 'Samsung', 'slug' => 'samsung', 'is_active' => true, 'display_order' => 2],
            ['name' => 'Xiaomi', 'slug' => 'xiaomi', 'is_active' => true, 'display_order' => 3],
            ['name' => 'OPPO', 'slug' => 'oppo', 'is_active' => true, 'display_order' => 4],
            ['name' => 'Vivo', 'slug' => 'vivo', 'is_active' => true, 'display_order' => 5],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }

        // Categories (Parent)
        $phone = Category::create([
            'name' => 'Điện thoại',
            'slug' => 'dien-thoai',
            'is_active' => true,
            'display_order' => 1
        ]);

        $tablet = Category::create([
            'name' => 'Máy tính bảng',
            'slug' => 'may-tinh-bang',
            'is_active' => true,
            'display_order' => 2
        ]);

        $laptop = Category::create([
            'name' => 'Laptop',
            'slug' => 'laptop',
            'is_active' => true,
            'display_order' => 3
        ]);

        $accessory = Category::create([
            'name' => 'Phụ kiện',
            'slug' => 'phu-kien',
            'is_active' => true,
            'display_order' => 4
        ]);

        // Categories (Children)
        Category::create([
            'parent_id' => $phone->id,
            'name' => 'iPhone',
            'slug' => 'iphone',
            'is_active' => true,
            'display_order' => 1
        ]);

        Category::create([
            'parent_id' => $phone->id,
            'name' => 'Samsung Galaxy',
            'slug' => 'samsung-galaxy',
            'is_active' => true,
            'display_order' => 2
        ]);
    }
}
```

#### 6. Run Seeders
```bash
php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=BrandCategorySeeder
```

---

## 🚀 DAY 2: Auth APIs

### Morning (4 giờ) - Backend Lead

#### 1. Create Models (nếu chưa có)
```bash
php artisan make:model Role
php artisan make:model Permission
php artisan make:model User # Đã có sẵn
```

**File: `app/Models/User.php`**
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
        'fullname',
        'email',
        'password',
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

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
```

#### 2. Create Auth Controller
```bash
php artisan make:controller Api/AuthController
```

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
            ]
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
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
                'date_of_birth' => $user->date_of_birth,
                'gender' => $user->gender,
                'role' => $user->role->name,
                'created_at' => $user->created_at,
            ]
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'fullname' => 'sometimes|string|max:150',
            'phone' => 'sometimes|nullable|regex:/^0[0-9]{9}$/',
            'date_of_birth' => 'sometimes|nullable|date',
            'gender' => 'sometimes|nullable|in:male,female,other',
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thông tin thành công',
            'data' => $user,
        ]);
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu hiện tại không đúng',
            ], 400);
        }

        $user->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đổi mật khẩu thành công',
        ]);
    }
}
```

#### 3. Add Routes
**File: `routes/api.php`**
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Auth routes (public)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
    });
});
```

#### 4. Test với Postman

**Create Postman Collection:**

**1. Register**
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

**2. Login**
```
POST http://localhost:8000/api/auth/login
Content-Type: application/json

{
  "email": "test@example.com",
  "password": "password123"
}
```

**3. Profile (cần token)**
```
GET http://localhost:8000/api/auth/profile
Authorization: Bearer {token}
```

---

## 🚀 DAY 3-4: Products API

### Intern 1 Task (Pair với Lead)

#### 1. Create Product Resource
```bash
php artisan make:resource ProductResource
php artisan make:resource ProductDetailResource
```

**File: `app/Http/Resources/ProductResource.php`**
```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'discount_percent' => $this->sale_price 
                ? round((($this->price - $this->sale_price) / $this->price) * 100) 
                : 0,
            'image' => $this->images->where('is_primary', true)->first()?->image_path 
                ?? '/images/no-image.png',
            'rating' => 4.5, // TODO: Calculate from reviews
            'sold_count' => $this->sold_count,
            'stock' => $this->quantity,
            'is_featured' => $this->is_featured,
            'brand' => [
                'id' => $this->brand?->id,
                'name' => $this->brand?->name,
                'slug' => $this->brand?->slug,
            ],
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ],
        ];
    }
}
```

#### 2. Create Product Controller
```bash
php artisan make:controller Api/ProductController
```

**File: `app/Http/Controllers/Api/ProductController.php`**
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get products list with filters
     */
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'category', 'images'])
            ->where('is_active', true);

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by brand
        if ($request->has('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Search
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Featured products
        if ($request->has('is_featured')) {
            $query->where('is_featured', $request->is_featured);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 20);
        $products = $query->paginate($perPage);

        return ProductResource::collection($products);
    }

    /**
     * Get product detail
     */
    public function show($id)
    {
        $product = Product::with([
            'brand',
            'category',
            'images',
            'variants.attributes.attributeGroup'
        ])->findOrFail($id);

        // Increment view count
        $product->increment('view_count');

        return response()->json([
            'success' => true,
            'data' => new ProductResource($product),
        ]);
    }

    /**
     * Get featured products
     */
    public function featured()
    {
        $products = Product::with(['brand', 'category', 'images'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sold_count', 'desc')
            ->limit(10)
            ->get();

        return ProductResource::collection($products);
    }

    /**
     * Get best selling products
     */
    public function bestSelling()
    {
        $products = Product::with(['brand', 'category', 'images'])
            ->where('is_active', true)
            ->orderBy('sold_count', 'desc')
            ->limit(10)
            ->get();

        return ProductResource::collection($products);
    }

    /**
     * Get new arrivals
     */
    public function newArrivals()
    {
        $products = Product::with(['brand', 'category', 'images'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return ProductResource::collection($products);
    }
}
```

#### 3. Add Routes
**File: `routes/api.php`**
```php
use App\Http\Controllers\Api\ProductController;

// Products
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/featured', [ProductController::class, 'featured']);
    Route::get('/best-selling', [ProductController::class, 'bestSelling']);
    Route::get('/new-arrivals', [ProductController::class, 'newArrivals']);
    Route::get('/{id}', [ProductController::class, 'show']);
});
```

---

## 🚀 DAY 5-7: Cart API

### Lead Task

#### 1. Create Cart Controller
```bash
php artisan make:controller Api/CartController
```

**File: `app/Http/Controllers/Api/CartController.php`**
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Get cart items
     */
    public function index(Request $request)
    {
        $cartItems = $this->getCartQuery($request)->get();

        $total = $cartItems->sum(function ($item) {
            $price = $item->variant 
                ? ($item->variant->sale_price ?? $item->variant->price)
                : ($item->product->sale_price ?? $item->product->price);
            return $price * $item->quantity;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $cartItems->map(function ($item) {
                    $price = $item->variant 
                        ? ($item->variant->sale_price ?? $item->variant->price)
                        : ($item->product->sale_price ?? $item->product->price);

                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'variant_id' => $item->variant_id,
                        'product_name' => $item->product->name,
                        'product_image' => $item->product->images->where('is_primary', true)->first()?->image_path,
                        'variant_info' => $item->variant ? [
                            'color' => $item->variant->attributes->where('attribute_group.name', 'color')->first()?->value,
                            'storage' => $item->variant->attributes->where('attribute_group.name', 'storage')->first()?->value,
                        ] : null,
                        'price' => $price,
                        'quantity' => $item->quantity,
                        'subtotal' => $price * $item->quantity,
                        'stock' => $item->variant ? $item->variant->quantity : $item->product->quantity,
                    ];
                }),
                'total' => $total,
                'count' => $cartItems->count(),
            ]
        ]);
    }

    /**
     * Add to cart
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Check stock
        $stock = $this->getStock($validated['product_id'], $validated['variant_id']);
        
        if ($validated['quantity'] > $stock) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng vượt quá tồn kho (còn ' . $stock . ' sản phẩm)',
            ], 400);
        }

        // Add or update cart
        $cartData = [
            'product_id' => $validated['product_id'],
            'variant_id' => $validated['variant_id'],
        ];

        if (auth()->check()) {
            $cartData['user_id'] = auth()->id();
        } else {
            $cartData['session_id'] = $request->session()->getId();
        }

        $cart = Cart::updateOrCreate(
            $cartData,
            ['quantity' => DB::raw('quantity + ' . $validated['quantity'])]
        );

        // Re-check stock after update
        $cart->refresh();
        if ($cart->quantity > $stock) {
            $cart->update(['quantity' => $stock]);
            return response()->json([
                'success' => false,
                'message' => 'Đã thêm tối đa ' . $stock . ' sản phẩm vào giỏ hàng',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm vào giỏ hàng',
        ], 201);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->getCartQuery($request)->findOrFail($id);

        // Check stock
        $stock = $this->getStock($cart->product_id, $cart->variant_id);
        
        if ($validated['quantity'] > $stock) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng vượt quá tồn kho (còn ' . $stock . ' sản phẩm)',
            ], 400);
        }

        $cart->update(['quantity' => $validated['quantity']]);

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật giỏ hàng',
        ]);
    }

    /**
     * Remove cart item
     */
    public function remove(Request $request, $id)
    {
        $cart = $this->getCartQuery($request)->findOrFail($id);
        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa khỏi giỏ hàng',
        ]);
    }

    /**
     * Clear all cart items
     */
    public function clear(Request $request)
    {
        $this->getCartQuery($request)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa toàn bộ giỏ hàng',
        ]);
    }

    /**
     * Get cart query (user or guest)
     */
    private function getCartQuery(Request $request)
    {
        $query = Cart::with(['product.images', 'variant.attributes.attributeGroup']);

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $query->where('session_id', $request->session()->getId());
        }

        return $query;
    }

    /**
     * Get stock quantity
     */
    private function getStock($productId, $variantId = null)
    {
        if ($variantId) {
            return ProductVariant::find($variantId)->quantity;
        }
        return Product::find($productId)->quantity;
    }
}
```

#### 2. Add Routes
**File: `routes/api.php`**
```php
use App\Http\Controllers\Api\CartController;

// Cart (support both auth and guest)
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/add', [CartController::class, 'add']);
    Route::put('/{id}', [CartController::class, 'update']);
    Route::delete('/{id}', [CartController::class, 'remove']);
    Route::delete('/', [CartController::class, 'clear']);
});
```

---

## 📋 END OF WEEK 1 CHECKLIST

### ✅ Deliverables:

- [ ] Database migrated với 26 bảng
- [ ] Seeders chạy thành công (roles, users, brands, categories)
- [ ] Auth APIs hoạt động (Register, Login, Logout, Profile)
- [ ] Products APIs hoạt động (List, Detail, Featured, Best Selling)
- [ ] Brands & Categories APIs hoạt động
- [ ] Cart APIs hoạt động (Add, Update, Remove, Get)
- [ ] Postman Collection v1.0 gửi cho FE team

### 📊 Testing Checklist:

**Auth:**
- [ ] Register với email mới → Success
- [ ] Register với email trùng → Error 400
- [ ] Login với đúng password → Success + Token
- [ ] Login với sai password → Error 401
- [ ] Get profile với token → Success
- [ ] Get profile không token → Error 401

**Products:**
- [ ] Get products list → Success với pagination
- [ ] Filter by category → Đúng products
- [ ] Filter by brand → Đúng products
- [ ] Search by name → Đúng products
- [ ] Get product detail → Success với đầy đủ info

**Cart:**
- [ ] Add product (guest) → Success
- [ ] Add product (user) → Success
- [ ] Add vượt stock → Error 400
- [ ] Update quantity → Success
- [ ] Remove item → Success
- [ ] Get cart → Đúng items + total

---

## 🎯 NEXT WEEK PREVIEW

**Week 2 Focus:**
- Checkout API
- Payment Integration (COD + VNPAY)
- Order Management
- Voucher System

**Preparation:**
- Đọc Laravel Transaction docs
- Đọc VNPAY API docs
- Setup VNPAY sandbox account

---

**Created:** 2026-01-28  
**Version:** 1.0  
**Status:** Ready to implement
