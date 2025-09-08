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

    // Remove incorrect relationships unless you have a pivot table or combination_id column
    // If you have a pivot table `product_variant_combination_values`, keep this:
    /*
    public function variantValues(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductVariantValue::class,
            'product_variant_combination_values',
            'combination_id',
            'value_id'
        );
    }
    */

    // If you want to query valid sizes from product_variant_values, you can add a helper method
  public static function getValidColors($productId)
    {
        return self::where('product_id', $productId)
            ->get()
            ->pluck('variant_values.Color')
            ->filter()
            ->unique()
            ->values();
    }

    // Get valid sizes for a product
    public static function getValidSizes($productId)
    {
        return self::where('product_id', $productId)
            ->get()
            ->pluck('variant_values.Size')
            ->filter()
            ->unique()
            ->values();
    }
}