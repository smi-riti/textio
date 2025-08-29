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
        'quantity',
        'sku',
        'category_id',
        'brand_id',
        'status',
        'is_customizable',
        
        'meta_title',
        'meta_description',
        'featured',
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


    public function getSavingPercentageAttribute(){
        if($this->price <= 0){
            return 0;
        }

        $discountPrice = $this->discount_price ?? $this->price;
        $saving = $this->price - $discountPrice;

        $percentage = ($saving/$this->price) * 100;

        return number_format($percentage, 2);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, "product_id", "id");
    }

 
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, "product_id", "id");
    }

    public function highlights()
    {
        return $this->hasMany(ProductHighlist::class, "product_id", "id");
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    
}
