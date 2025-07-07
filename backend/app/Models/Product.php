<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'price_original',
        'sale_price',
        'status',
        'is_active',
        'category_id',
        'brand_id',
        'hinh_anh'
    ];

    protected $casts = [
        'price_original' => 'integer',
        'sale_price' => 'integer',
        'is_active' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'hinh_anh' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function scopeInStock($query)
    {
        return $query->where('status', 'Còn hàng');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('status', 'Hết hàng');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', 0);
    }

    public function scopeAvailable($query)
    {
        return $query->active()->inStock();
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByBrand($query, $brandId)
    {
        return $query->where('brand_id', $brandId);
    }

    public function scopeByActiveStatus($query, $isActive)
    {
        return $query->where('is_active', $isActive);
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->price_original > 0) {
            return round((($this->price_original - $this->sale_price) / $this->price_original) * 100);
        }
        return 0;
    }

    public function getIsOnSaleAttribute()
    {
        return $this->sale_price > 0 && $this->sale_price < $this->price_original;
    }

    public function getActiveStatusTextAttribute()
    {
        return $this->is_active == 1 ? 'Hiển thị' : 'Ẩn';
    }

    public function getIsAvailableAttribute()
    {
        return $this->is_active == 1 && $this->status === 'Còn hàng';
    }

    public function getImageUrlsAttribute()
    {
        if (empty($this->hinh_anh)) {
            return [];
        }

        $images = is_array($this->hinh_anh) ? $this->hinh_anh : json_decode($this->hinh_anh, true);
        
        return array_filter(array_map(function($imagePath) {
            $path = 'products/' . basename($imagePath);
            return Storage::disk('public')->exists($path) ? Storage::url($path) : null;
        }, $images ?? []));
    }

    public function getFirstImageUrlAttribute()
    {
        $imageUrls = $this->image_urls;
        return !empty($imageUrls) ? reset($imageUrls) : Storage::url('products/no-image.png');
    }

    public function deleteOldImages()
    {
        if (!empty($this->hinh_anh)) {
            $images = is_array($this->hinh_anh) ? $this->hinh_anh : json_decode($this->hinh_anh, true);
            foreach ($images ?? [] as $imagePath) {
                $path = 'products/' . basename($imagePath);
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }
    }

    public function toggleActive()
    {
        $this->is_active = $this->is_active == 1 ? 0 : 1;
        return $this->save();
    }

    public function toggleStatus()
    {
        $this->status = $this->status === 'Còn hàng' ? 'Hết hàng' : 'Còn hàng';
        return $this->save();
    }

    public function hide()
    {
        $this->is_active = 0;
        return $this->save();
    }

    public function show()
    {
        $this->is_active = 1;
        return $this->save();
    }

    public function canShowToCustomer()
    {
        return $this->is_active == 1 && $this->status === 'Còn hàng';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (is_null($product->is_active)) {
                $product->is_active = 1;
            }
        });
    }
}