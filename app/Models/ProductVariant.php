<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    protected $guarded =[];
    
    public function values()
    {
        return $this->hasMany(ProductVariantValue::class);
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }

   public function variantValues()
    {
        return $this->hasMany(ProductVariantValue::class, 'product_variant_id');
    }

   
}
