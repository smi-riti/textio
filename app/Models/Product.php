<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'category_id',
        'brand_id',
        'status',
        'is_customizable',
        'print_area',
        'meta_title',
        'meta_description',
        'featured',
        'weight',
        'length',
        'breadth',
        'height'

    ];

    protected $casts = [
        'print_area' => 'array',
        'is_customizable' => 'boolean',
        'featured' => 'boolean',
        'status' => 'boolean',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = \Str::slug($value);
    }

    public function getFormattedDiscountPriceAttribute()
    {
        return '₹' . number_format($this->discount_price, 2);
    }

    public function getFormattedPriceAttribute()
    {
        return '₹' . number_format($this->price, 2);
    }

    public function getSavingPercentageAttribute()
    {
        if ($this->price <= 0) {
            return 0;
        }

        $discountPrice = $this->discount_price ?? $this->price;
        $saving = $this->price - $discountPrice;

        $percentage = ($saving / $this->price) * 100;

        return number_format($percentage, 2);
    }

    // Single hasMany for variants (removed duplicate)
    public function variantCombinations()
    {
        return $this->hasMany(ProductVariantCombination::class);
    }

    public function isInStock()
    {
        return $this->variantCombinations()->where('stock', '>', 0)->exists();
    }

    public function highlights()
    {
        return $this->hasMany(ProductHighlist::class, "product_id", "id");
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }
    public function images()
    {
        return $this->hasManyThrough(ProductImage::class, ProductVariantCombination::class, 'id', 'product_variant_combination_id', 'id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariantCombination::class);
    }

    public function firstVariantImage()
    {
        return $this->hasOneThrough(
            ProductImage::class,                // final model
            ProductVariantCombination::class,   // intermediate model
            'product_id',                       // FK on product_variant_combinations
            'product_variant_combination_id',   // FK on product_images
            'id',                               // local key on products
            'id'                                // local key on product_variant_combinations
        )->where('is_primary', true);
    }

}