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
    ];
  
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
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
