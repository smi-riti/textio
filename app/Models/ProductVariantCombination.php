<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

  
}