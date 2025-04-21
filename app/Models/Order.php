<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];

    // to show the discount price in formatted way:
    public function getFormattedDiscountPriceAttribute()
    {
        return '₹' . number_format($this->discount_price, 2);
    }

    // to show the price in formatted way:
    public function getFormattedPriceAttribute()
    {
        return '₹' . number_format($this->price, 2);
    }


    // saving percentage calculation here:
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

    public function category()
    {
        return $this->hasOne(Category::class, "id", "category_id");
    }


    public function brand()
    {
        return $this->hasOne(brand::class, "id", "brand_id");
    }

    public function variants()
    {
        // return $this->hasMany(Product_Variant::class,"id","product_id");
        return $this->hasMany(ProductVariant::class, "product_id", "id");
    }}
