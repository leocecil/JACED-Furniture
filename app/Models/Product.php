<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Tambahkan ini karena di migration pakai softDeletes

class Product extends Model
{
    use HasFactory, SoftDeletes; // Aktifkan softDeletes agar sinkron dengan migration

    // Pastikan semua kolom yang ada di migration masuk ke sini agar bisa diisi data
    protected $fillable = [
        'name', 
        'description', 
        'length', 
        'width', 
        'height',
        'unit', 
        'price', 
        'stock', 
        'label', 
        'category_id'
    ];

    // Casts berguna agar data tipe decimal dari DB otomatis dikonversi ke float/decimal di PHP
    protected $casts = [
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}