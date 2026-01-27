<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    /**
     * Tắt tự động quản lý updated_at
     */
    public $timestamps = false;

    /**
     * Chỉ định column created_at thủ công
     */
    const CREATED_AT = 'created_at';

    /**
     * Các trường cho phép mass assignment
     */
    protected $fillable = [
        'product_id',
        'image_path',
        'is_primary',
        'display_order',
    ];

    /**
     * Cast attributes
     */
    protected $casts = [
        'is_primary' => 'boolean',
        'display_order' => 'integer',
        'created_at' => 'datetime',
    ];

    // ===========================
    // RELATIONSHIPS
    // ===========================

    /**
     * Ảnh thuộc về 1 sản phẩm
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // ===========================
    // ACCESSORS
    // ===========================

    /**
     * URL đầy đủ của ảnh
     */
    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }

    /**
     * URL thumbnail (nếu có)
     */
    public function getThumbnailUrlAttribute(): string
    {
        // Giả sử lưu thumbnail với prefix 'thumb_'
        $path = dirname($this->image_path);
        $filename = basename($this->image_path);
        $thumbPath = $path . '/thumb_' . $filename;
        
        // Nếu không có thumb, trả về ảnh gốc
        if (!file_exists(storage_path('app/public/' . $thumbPath))) {
            return $this->image_url;
        }
        
        return asset('storage/' . $thumbPath);
    }

    // ===========================
    // QUERY SCOPES
    // ===========================

    /**
     * Lấy ảnh chính
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Sắp xếp theo thứ tự
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc');
    }

    // ===========================
    // HELPER METHODS
    // ===========================

    /**
     * Đặt làm ảnh chính
     */
    public function setAsPrimary(): void
    {
        // Bỏ primary của các ảnh khác cùng product
        static::where('product_id', $this->product_id)
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);
        
        // Set primary cho ảnh này
        $this->update(['is_primary' => true]);
    }
}
