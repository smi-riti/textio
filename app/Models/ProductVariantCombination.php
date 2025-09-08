<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductVariantCombination extends Model
{
   protected $fillable = ['product_id', 'price', 'stock', 'sku', 'image', 'variant_values'];

    protected $casts = [
        'variant_values' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variantValues(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductVariantValue::class,
            'product_variant_combination_values',
            'combination_id',
            'value_id'
        );
    }
}