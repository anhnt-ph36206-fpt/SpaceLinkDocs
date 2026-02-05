<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use SoftDeletes;

    /**
     * Các trường cho phép mass assignment
     */
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

    /**
     * Cast attributes
     */
    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'quantity' => 'integer',
        'sold_count' => 'integer',
        'view_count' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Eager load mặc định
     */
    protected $with = [];

    // ===========================
    // RELATIONSHIPS
    // ===========================

    /**
     * Sản phẩm thuộc 1 danh mục
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Sản phẩm thuộc 1 thương hiệu
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Sản phẩm có nhiều hình ảnh
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('display_order');
    }

    /**
     * Ảnh chính của sản phẩm
     */
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    // ===========================
    // ACCESSORS
    // ===========================

    /**
     * Giá cuối cùng (sau giảm giá)
     */
    public function getFinalPriceAttribute(): float
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Phần trăm giảm giá
     */
    public function getDiscountPercentAttribute(): int
    {
        if (!$this->sale_price || $this->sale_price >= $this->price) {
            return 0;
        }
        return (int) round((1 - $this->sale_price / $this->price) * 100);
    }

    /**
     * Số tiền được giảm
     */
    public function getDiscountAmountAttribute(): float
    {
        if (!$this->sale_price || $this->sale_price >= $this->price) {
            return 0;
        }
        return $this->price - $this->sale_price;
    }

    /**
     * Kiểm tra đang giảm giá không
     */
    public function getIsOnSaleAttribute(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    /**
     * Kiểm tra còn hàng không
     */
    public function getInStockAttribute(): bool
    {
        return $this->quantity > 0;
    }

    /**
     * URL ảnh chính
     */
    public function getPrimaryImageUrlAttribute(): ?string
    {
        $image = $this->primaryImage;
        if ($image) {
            return asset('storage/' . $image->image_path);
        }
        
        // Fallback: lấy ảnh đầu tiên
        $firstImage = $this->images()->first();
        if ($firstImage) {
            return asset('storage/' . $firstImage->image_path);
        }
        
        return null;
    }

    /**
     * Format giá tiền VND
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', '.') . 'đ';
    }

    /**
     * Format giá khuyến mãi VND
     */
    public function getFormattedSalePriceAttribute(): ?string
    {
        if (!$this->sale_price) {
            return null;
        }
        return number_format($this->sale_price, 0, ',', '.') . 'đ';
    }

    /**
     * Format giá cuối cùng VND
     */
    public function getFormattedFinalPriceAttribute(): string
    {
        return number_format($this->final_price, 0, ',', '.') . 'đ';
    }

    // ===========================
    // QUERY SCOPES
    // ===========================

    /**
     * Chỉ sản phẩm active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Sản phẩm nổi bật
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Còn hàng
     */
    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }

    /**
     * Đang giảm giá
     */
    public function scopeOnSale($query)
    {
        return $query->whereNotNull('sale_price')
                     ->whereColumn('sale_price', '<', 'price');
    }

    /**
     * Theo danh mục
     */
    public function scopeOfCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Theo thương hiệu
     */
    public function scopeOfBrand($query, int $brandId)
    {
        return $query->where('brand_id', $brandId);
    }

    /**
     * Lọc theo khoảng giá
     */
    public function scopePriceBetween($query, float $min, float $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    /**
     * Tìm kiếm theo từ khóa
     */
    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%")
              ->orWhere('sku', 'like', "%{$keyword}%");
        });
    }

    /**
     * Sắp xếp theo các tiêu chí phổ biến
     */
    public function scopeSortBy($query, string $sort)
    {
        return match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'newest' => $query->orderBy('created_at', 'desc'),
            'oldest' => $query->orderBy('created_at', 'asc'),
            'best_selling' => $query->orderBy('sold_count', 'desc'),
            'most_viewed' => $query->orderBy('view_count', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };
    }

    // ===========================
    // HELPER METHODS
    // ===========================

    /**
     * Tăng lượt xem
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * Cập nhật sau khi bán hàng
     */
    public function decrementStock(int $quantity): void
    {
        $this->decrement('quantity', $quantity);
        $this->increment('sold_count', $quantity);
    }

    /**
     * Kiểm tra có đủ hàng để mua không
     */
    public function hasEnoughStock(int $quantity): bool
    {
        return $this->quantity >= $quantity;
    }
}
