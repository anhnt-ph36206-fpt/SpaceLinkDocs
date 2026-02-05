<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    use SoftDeletes;

    /**
     * Các trường cho phép mass assignment
     */
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

    /**
     * Cast attributes
     */
    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
        'parent_id' => 'integer',
    ];

    // ===========================
    // RELATIONSHIPS
    // ===========================

    /**
     * Danh mục cha (nếu có)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Danh mục con trực tiếp
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Danh mục con đang active
     */
    public function activeChildren(): HasMany
    {
        return $this->children()->active()->ordered();
    }

    /**
     * Tất cả danh mục con cháu (recursive)
     */
    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
    }

    /**
     * Sản phẩm thuộc danh mục này
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // ===========================
    // ACCESSORS
    // ===========================

    /**
     * URL hình ảnh đại diện
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }
        return asset('storage/' . $this->image);
    }

    /**
     * Đếm số sản phẩm
     */
    public function getProductCountAttribute(): int
    {
        return $this->products()->active()->count();
    }

    /**
     * Kiểm tra có danh mục con không
     */
    public function getHasChildrenAttribute(): bool
    {
        return $this->children()->exists();
    }

    /**
     * Lấy đường dẫn đầy đủ: Điện thoại > iPhone > iPhone 16
     */
    public function getFullPathAttribute(): string
    {
        $path = collect([$this->name]);
        $parent = $this->parent;

        while ($parent) {
            $path->prepend($parent->name);
            $parent = $parent->parent;
        }

        return $path->implode(' > ');
    }

    /**
     * Lấy độ sâu của danh mục (level)
     */
    public function getDepthAttribute(): int
    {
        $depth = 0;
        $parent = $this->parent;

        while ($parent) {
            $depth++;
            $parent = $parent->parent;
        }

        return $depth;
    }

    // ===========================
    // QUERY SCOPES
    // ===========================

    /**
     * Chỉ danh mục active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Chỉ danh mục gốc (không có parent)
     */
    public function scopeParentOnly($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Chỉ danh mục con
     */
    public function scopeChildOnly($query)
    {
        return $query->whereNotNull('parent_id');
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

    /**
     * Lấy danh mục theo parent_id
     */
    public function scopeOfParent($query, ?int $parentId)
    {
        if ($parentId === null) {
            return $query->whereNull('parent_id');
        }
        return $query->where('parent_id', $parentId);
    }

    // ===========================
    // HELPER METHODS
    // ===========================

    /**
     * Kiểm tra có thể xóa không (không còn sản phẩm)
     */
    public function canDelete(): bool
    {
        // Không xóa nếu còn sản phẩm
        if ($this->products()->exists()) {
            return false;
        }
        
        // Không xóa nếu còn danh mục con
        if ($this->children()->exists()) {
            return false;
        }

        return true;
    }
}
