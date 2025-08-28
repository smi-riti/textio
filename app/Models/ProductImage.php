<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'image_path', 'is_primary','image_file_id'];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
