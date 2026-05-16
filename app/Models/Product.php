<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'material_id', 'name', 'slug',
        'short_description', 'description', 'price', 'old_price',
        'main_image', 'badge', 'stock', 'is_active', 'is_recommended',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_recommended' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function defaultVariant()
    {
        return $this->hasOne(ProductVariant::class)->where('is_default', true);
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}