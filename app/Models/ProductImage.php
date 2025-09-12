<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_variant_combination_id', 'image_path', 'is_primary', 'image_file_id'];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // Fixed: Should belong to ProductVariantCombination, not Product
    public function combination()
    {
        return $this->belongsTo(ProductVariantCombination::class, 'product_variant_combination_id');
    }

    // Optional: If you need indirect access to Product, add this (via combination)
    public function product()
    {
        return $this->belongsTo(Product::class)->via('combination');
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path;
    }
}