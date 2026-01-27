# 🎯 PHASE 1: CATEGORIES & PRODUCTS

## 📋 MỤC TIÊU

Sau Phase 1, bạn sẽ có:
- ✅ API CRUD Categories (danh mục đa cấp)
- ✅ API CRUD Products (sản phẩm cơ bản)
- ✅ API CRUD Brands (thương hiệu)
- ✅ Upload hình ảnh sản phẩm
- ✅ Liệt kê sản phẩm theo danh mục

---

## 📊 THỨ TỰ THỰC HIỆN

```
1. Brands (đơn giản nhất)
   ↓
2. Categories (có parent_id)
   ↓
3. Products (phụ thuộc brands, categories)
   ↓
4. Product Images (phụ thuộc products)
```

---

## 🔧 BƯỚC 1: TẠO MIGRATION - BRANDS

### 1.1 Tạo migration

```bash
php artisan make:migration create_brands_table
```

### 1.2 Nội dung migration

📁 `database/migrations/xxxx_create_brands_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();                                    // BIGINT UNSIGNED AUTO_INCREMENT
            $table->string('name');                          // Tên thương hiệu
            $table->string('slug')->unique();                // URL-friendly name
            $table->string('logo')->nullable();              // Đường dẫn logo
            $table->text('description')->nullable();         // Mô tả
            $table->boolean('is_active')->default(true);     // Trạng thái
            $table->integer('display_order')->default(0);    // Thứ tự hiển thị
            $table->timestamps();                            // created_at, updated_at
            
            // Indexes
            $table->index('slug');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
```

### 1.3 Giải thích các kiểu dữ liệu Laravel

| Laravel Method | MySQL Type | Mô tả |
|----------------|------------|-------|
| `$table->id()` | `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY` | ID tự tăng |
| `$table->string('name')` | `VARCHAR(255)` | Chuỗi ngắn |
| `$table->string('name', 100)` | `VARCHAR(100)` | Giới hạn độ dài |
| `$table->text('content')` | `TEXT` | Chuỗi dài |
| `$table->boolean('active')` | `TINYINT(1)` | True/False |
| `$table->integer('count')` | `INT` | Số nguyên |
| `$table->decimal('price', 15, 2)` | `DECIMAL(15,2)` | Tiền tệ |
| `$table->timestamps()` | `created_at, updated_at` | Thời gian |
| `$table->softDeletes()` | `deleted_at` | Xóa mềm |
| `->nullable()` | `NULL` | Cho phép null |
| `->default(value)` | `DEFAULT value` | Giá trị mặc định |
| `->unique()` | `UNIQUE INDEX` | Giá trị duy nhất |

---

## 🔧 BƯỚC 2: TẠO MIGRATION - CATEGORIES

### 2.1 Tạo migration

```bash
php artisan make:migration create_categories_table
```

### 2.2 Nội dung migration

📁 `database/migrations/xxxx_create_categories_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            
            // Self-referencing foreign key (danh mục cha)
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('categories')      // FK đến chính bảng này
                  ->onDelete('set null');          // Khi xóa parent, set null
            
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->string('icon', 100)->nullable();       // Icon class (FontAwesome)
            $table->text('description')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();                         // deleted_at column
            
            // Indexes
            $table->index('parent_id');
            $table->index('slug');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
```

### 2.3 Giải thích Self-Referencing

```
categories
├── Điện thoại (id=1, parent_id=NULL)
│   ├── iPhone (id=5, parent_id=1)
│   │   ├── iPhone 16 Series (id=10, parent_id=5)
│   │   └── iPhone 15 Series (id=11, parent_id=5)
│   └── Samsung (id=6, parent_id=1)
├── Laptop (id=2, parent_id=NULL)
└── Phụ kiện (id=3, parent_id=NULL)
```

---

## 🔧 BƯỚC 3: TẠO MIGRATION - PRODUCTS

### 3.1 Tạo migration

```bash
php artisan make:migration create_products_table
```

### 3.2 Nội dung migration

📁 `database/migrations/xxxx_create_products_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->onDelete('restrict');          // Không cho xóa category nếu còn SP
            
            $table->foreignId('brand_id')
                  ->nullable()
                  ->constrained('brands')
                  ->onDelete('set null');          // Xóa brand thì SP vẫn còn
            
            // Thông tin cơ bản
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku', 100)->unique()->nullable();  // Mã sản phẩm
            $table->text('description')->nullable();           // Mô tả ngắn
            $table->longText('content')->nullable();           // Chi tiết (HTML)
            
            // Giá
            $table->decimal('price', 15, 2);                   // Giá gốc
            $table->decimal('sale_price', 15, 2)->nullable();  // Giá KM
            
            // Số lượng & Thống kê
            $table->unsignedInteger('quantity')->default(0);   // Tồn kho
            $table->unsignedInteger('sold_count')->default(0); // Đã bán
            $table->unsignedInteger('view_count')->default(0); // Lượt xem
            
            // Trạng thái
            $table->boolean('is_featured')->default(false);    // Nổi bật
            $table->boolean('is_active')->default(true);       // Hiển thị
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('category_id');
            $table->index('brand_id');
            $table->index('price');
            $table->index('sold_count');
            $table->index('is_featured');
            $table->index('is_active');
            
            // Full-text search
            $table->fullText(['name', 'description']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

---

## 🔧 BƯỚC 4: TẠO MIGRATION - PRODUCT_IMAGES

### 4.1 Tạo migration

```bash
php artisan make:migration create_product_images_table
```

### 4.2 Nội dung migration

📁 `database/migrations/xxxx_create_product_images_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->onDelete('cascade');           // Xóa product thì xóa luôn ảnh
            
            $table->string('image_path');                      // Đường dẫn ảnh
            $table->boolean('is_primary')->default(false);     // Ảnh chính
            $table->integer('display_order')->default(0);      // Thứ tự
            
            $table->timestamp('created_at')->useCurrent();
            
            // Index
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
```

---

## 🚀 BƯỚC 5: CHẠY MIGRATION

### 5.1 Kiểm tra trạng thái

```bash
php artisan migrate:status
```

### 5.2 Chạy migration

```bash
php artisan migrate
```

### 5.3 Rollback nếu cần

```bash
# Rollback 1 batch
php artisan migrate:rollback

# Rollback tất cả
php artisan migrate:reset

# Rollback + migrate lại
php artisan migrate:refresh

# Xóa tất cả + migrate lại (CẨN THẬN!)
php artisan migrate:fresh
```

---

## 📦 BƯỚC 6: TẠO MODELS

### 6.1 Tạo Model Brand

```bash
php artisan make:model Brand
```

📁 `app/Models/Brand.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}
```

### 6.2 Tạo Model Category

```bash
php artisan make:model Category
```

📁 `app/Models/Category.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'image',
        'icon',
        'description',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // Recursive children (tất cả con cháu)
    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeParentOnly($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}
```

### 6.3 Tạo Model Product

```bash
php artisan make:model Product
```

📁 `app/Models/Product.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'description',
        'content',
        'price',
        'sale_price',
        'quantity',
        'sold_count',
        'view_count',
        'is_featured',
        'is_active',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('display_order');
    }

    public function primaryImage(): HasMany
    {
        return $this->hasMany(ProductImage::class)->where('is_primary', true);
    }

    // Accessors
    public function getFinalPriceAttribute(): float
    {
        return $this->sale_price ?? $this->price;
    }

    public function getDiscountPercentAttribute(): int
    {
        if (!$this->sale_price || $this->sale_price >= $this->price) {
            return 0;
        }
        return (int) round((1 - $this->sale_price / $this->price) * 100);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }

    public function scopeOnSale($query)
    {
        return $query->whereNotNull('sale_price')
                     ->whereColumn('sale_price', '<', 'price');
    }
}
```

### 6.4 Tạo Model ProductImage

```bash
php artisan make:model ProductImage
```

📁 `app/Models/ProductImage.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    public $timestamps = false;            // Không có updated_at

    protected $fillable = [
        'product_id',
        'image_path',
        'is_primary',
        'display_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Accessor: Full URL
    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }
}
```

---

## 🎯 BƯỚC 7: TẠO SEEDERS

### 7.1 Seeder cho Brands

```bash
php artisan make:seeder BrandSeeder
```

📁 `database/seeders/BrandSeeder.php`

```php
<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Apple', 'display_order' => 1],
            ['name' => 'Samsung', 'display_order' => 2],
            ['name' => 'Xiaomi', 'display_order' => 3],
            ['name' => 'OPPO', 'display_order' => 4],
            ['name' => 'Vivo', 'display_order' => 5],
            ['name' => 'Realme', 'display_order' => 6],
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand['name'],
                'slug' => Str::slug($brand['name']),
                'is_active' => true,
                'display_order' => $brand['display_order'],
            ]);
        }
    }
}
```

### 7.2 Seeder cho Categories

```bash
php artisan make:seeder CategorySeeder
```

📁 `database/seeders/CategorySeeder.php`

```php
<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Danh mục cha
        $parents = [
            ['name' => 'Điện thoại', 'icon' => 'fa-mobile-alt', 'order' => 1],
            ['name' => 'Máy tính bảng', 'icon' => 'fa-tablet-alt', 'order' => 2],
            ['name' => 'Laptop', 'icon' => 'fa-laptop', 'order' => 3],
            ['name' => 'Phụ kiện', 'icon' => 'fa-headphones', 'order' => 4],
        ];

        foreach ($parents as $parent) {
            Category::create([
                'name' => $parent['name'],
                'slug' => Str::slug($parent['name']),
                'icon' => $parent['icon'],
                'display_order' => $parent['order'],
                'is_active' => true,
            ]);
        }

        // Danh mục con
        $children = [
            ['parent' => 'Điện thoại', 'name' => 'iPhone', 'order' => 1],
            ['parent' => 'Điện thoại', 'name' => 'Samsung Galaxy', 'order' => 2],
            ['parent' => 'Điện thoại', 'name' => 'Xiaomi', 'order' => 3],
            ['parent' => 'Máy tính bảng', 'name' => 'iPad', 'order' => 1],
            ['parent' => 'Máy tính bảng', 'name' => 'Samsung Tab', 'order' => 2],
            ['parent' => 'Laptop', 'name' => 'MacBook', 'order' => 1],
            ['parent' => 'Laptop', 'name' => 'Dell', 'order' => 2],
            ['parent' => 'Phụ kiện', 'name' => 'Tai nghe', 'order' => 1],
            ['parent' => 'Phụ kiện', 'name' => 'Sạc & Cáp', 'order' => 2],
            ['parent' => 'Phụ kiện', 'name' => 'Ốp lưng', 'order' => 3],
        ];

        foreach ($children as $child) {
            $parent = Category::where('name', $child['parent'])->first();
            
            Category::create([
                'parent_id' => $parent->id,
                'name' => $child['name'],
                'slug' => Str::slug($child['name']),
                'display_order' => $child['order'],
                'is_active' => true,
            ]);
        }
    }
}
```

### 7.3 Thêm vào DatabaseSeeder

📁 `database/seeders/DatabaseSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            BrandSeeder::class,
            CategorySeeder::class,
            // ProductSeeder::class,  // Thêm sau
        ]);
    }
}
```

### 7.4 Chạy Seeder

```bash
php artisan db:seed
```

---

## 📝 BƯỚC TIẾP THEO

Sau khi hoàn thành migrations, models và seeders:

1. **Tạo Controllers** - Xem file `PHASE-1-CONTROLLERS.md`
2. **Tạo API Routes** - Xem file `PHASE-1-ROUTES.md`
3. **Test với Postman**

---

## ✅ CHECKLIST

- [ ] Chạy `php artisan make:migration create_brands_table`
- [ ] Chạy `php artisan make:migration create_categories_table`
- [ ] Chạy `php artisan make:migration create_products_table`
- [ ] Chạy `php artisan make:migration create_product_images_table`
- [ ] Chạy `php artisan migrate`
- [ ] Tạo Model Brand
- [ ] Tạo Model Category
- [ ] Tạo Model Product
- [ ] Tạo Model ProductImage
- [ ] Tạo và chạy Seeders
