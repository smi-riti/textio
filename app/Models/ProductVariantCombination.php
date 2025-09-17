<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariantCombination extends Model
{
    protected $fillable = ['product_id', 'price', 'stock', 'sku', 'variant_values'];

    protected $casts = [
        'variant_values' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Fixed: Correct foreign key (was 'product_varaint_combination_id')
    public function images()
    {
        return $this->hasMany(ProductImage::class, "product_variant_combination_id");
    }

    // Helper to get primary image
    public function getPrimaryImageAttribute()
    {
        return $this->images()->where('is_primary', true)->first()?->image_path;
    }

    // Helper to get gallery images
    public function getGalleryImagesAttribute()
    {
        return $this->images()->where('is_primary', false)->get(['image_path']);
    }
}