<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    /**
     * Các trường cho phép mass assignment
     */
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'is_active',
        'display_order',
    ];

    /**
     * Cast attributes sang đúng kiểu dữ liệu
     */
    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Thuộc tính ẩn khi serialize
     */
    protected $hidden = [];

    // ===========================
    // RELATIONSHIPS
    // ===========================

    /**
     * Brand có nhiều Products
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // ===========================
    // ACCESSORS
    // ===========================

    /**
     * Lấy URL đầy đủ của logo
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo) {
            return null;
        }
        return asset('storage/' . $this->logo);
    }

    /**
     * Đếm số sản phẩm active
     */
    public function getProductCountAttribute(): int
    {
        return $this->products()->active()->count();
    }

    // ===========================
    // QUERY SCOPES
    // ===========================

    /**
     * Chỉ lấy brands đang active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Sắp xếp theo display_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc');
    }

    /**
     * Tìm kiếm theo tên
     */
    public function scopeSearch($query, string $keyword)
    {
        return $query->where('name', 'like', "%{$keyword}%");
    }
}
