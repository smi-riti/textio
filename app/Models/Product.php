<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'slug', 'category_id', 'brand_id', 'unit_price', 'description',
        'current_stock', 'published', 'discount', 'discount_type', 'tags',
        'meta_title', 'meta_description', 'shipping_cost', 'min_qty', 'user_id',
        'thumbnail_img', 
    ];
    protected $casts = [
        'is_active' => 'boolean',
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
}
