<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'length',
        'width',
        'height',
        'unit',
        'price',
        'stock',
        'low_stock',
        'label',
        'category_id',
    ];

    protected $casts = [
        'length'    => 'decimal:2',
        'width'     => 'decimal:2',
        'height'    => 'decimal:2',
        'price'     => 'decimal:2',
        'stock'     => 'integer',
        'low_stock' => 'integer',
    ];

    // ─── RELATIONS ────────────────────────────────────────────────────────────

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function mainImage()
    {
        return $this->hasOne(ProductImage::class)
            ->where('is_main', true)
            ->orderBy('sort_order');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    // ─── ACCESSORS ────────────────────────────────────────────────────────────

    public function getMainImageUrlAttribute(): string
    {
        if (!$this->mainImage) {
            return 'https://placehold.co/800x800/f2ede6/272e1d?text=' . urlencode($this->name);
        }
        return asset($this->mainImage->image_path);
    }

    protected $appends = ['slug'];

    public function getSlugAttribute(): string
    {
        return \Illuminate\Support\Str::slug($this->name);
    }
}