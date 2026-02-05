## 09 - Models cho 27 bảng core (Eloquent + quan hệ)

> Gợi ý: bạn có thể đặt tất cả models dưới namespace `App\Models`.  
> Dưới đây là version rút gọn, tập trung vào **quan hệ**, không liệt kê hết mọi `$fillable`.

---

### `Role` + `Permission` + pivot `role_permissions`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'display_name', 'description'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    public function users()
    {
        // Nếu sau này bạn có bảng user_roles, có thể dùng belongsToMany
        return $this->hasMany(User::class);
    }
}

class Permission extends Model
{
    protected $fillable = ['name', 'display_name', 'group_name'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }
}
```

---

### `User` + `UserAddress`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'email',
        'password',
        'fullname',
        'phone',
        'avatar',
        'date_of_birth',
        'gender',
        'status',
        'wallet_balance',
        'loyalty_points',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'wallet_balance' => 'decimal:2',
    ];

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'fullname',
        'phone',
        'province',
        'district',
        'ward',
        'address_detail',
        'is_default',
        'address_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

---

### `Brand` + `Category`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

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

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
```

---

### `AttributeGroup` + `Attribute`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeGroup extends Model
{
    protected $fillable = ['name', 'display_name', 'display_order'];

    public function attributes()
    {
        return $this->hasMany(Attribute::class);
    }
}

class Attribute extends Model
{
    protected $fillable = [
        'attribute_group_id',
        'value',
        'color_code',
        'display_order',
    ];

    public function group()
    {
        return $this->belongsTo(AttributeGroup::class, 'attribute_group_id');
    }
}
```

---

### `Product` + `ProductImage` + `ProductVariant` + `ProductVariantAttribute` + `ProductView`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'image_path',
        'is_primary',
        'display_order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'sale_price',
        'quantity',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_variant_attributes', 'variant_id', 'attribute_id');
    }
}

class ProductVariantAttribute extends Model
{
    public $timestamps = false;
    protected $fillable = ['variant_id', 'attribute_id'];
}

class ProductView extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'product_id',
        'user_id',
        'session_id',
        'ip_address',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

---

### `Cart`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'variant_id',
        'quantity',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
```

---

### `Order` + `OrderItem` + `OrderStatusHistory` + `PaymentTransaction`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_code',
        'shipping_name',
        'shipping_phone',
        'shipping_email',
        'shipping_province',
        'shipping_district',
        'shipping_ward',
        'shipping_address',
        'subtotal',
        'discount_amount',
        'shipping_fee',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'voucher_id',
        'voucher_code',
        'voucher_discount',
        'note',
        'admin_note',
        'cancelled_reason',
        'cancelled_by',
        'cancelled_at',
        'confirmed_at',
        'shipped_at',
        'delivered_at',
        'completed_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'voucher_discount' => 'decimal:2',
        'cancelled_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }
}

class OrderItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'product_name',
        'product_image',
        'product_sku',
        'variant_info',
        'price',
        'quantity',
        'total',
        'is_reviewed',
        'created_at',
    ];

    protected $casts = [
        'variant_info' => 'array',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}

class OrderStatusHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'from_status',
        'to_status',
        'note',
        'changed_by',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}

class PaymentTransaction extends Model
{
    protected $fillable = [
        'order_id',
        'transaction_id',
        'payment_method',
        'amount',
        'status',
        'bank_code',
        'response_code',
        'response_message',
        'response_data',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'response_data' => 'array',
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
```

---

### `Review` + `Comment` + `CommentReport`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'order_item_id',
        'rating',
        'content',
        'images',
        'is_hidden',
        'admin_reply',
        'replied_at',
    ];

    protected $casts = [
        'images' => 'array',
        'replied_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'parent_id',
        'content',
        'is_hidden',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function reports()
    {
        return $this->hasMany(CommentReport::class);
    }
}

class CommentReport extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'comment_id',
        'user_id',
        'reason',
        'description',
        'status',
        'resolved_by',
        'resolved_at',
        'created_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
```

---

### `Voucher`, `News`, `Contact`, `Setting`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'max_discount',
        'min_order_amount',
        'quantity',
        'used_count',
        'usage_limit_per_user',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

class News extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'slug',
        'thumbnail',
        'summary',
        'content',
        'view_count',
        'is_featured',
        'is_active',
        'meta_title',
        'meta_description',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'reply_content',
        'replied_by',
        'replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    public function repliedBy()
    {
        return $this->belongsTo(User::class, 'replied_by');
    }
}

class Setting extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'key_name',
        'value',
        'type',
        'group_name',
        'description',
        'is_public',
    ];
}
```

