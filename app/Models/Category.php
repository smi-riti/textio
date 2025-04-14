<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;

class Category extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'parent_category_id',
        'title',
        'slug',               
        'image',
        'description',
        'is_active',
        'meta_title',
        'meta_description',        
    ];

    protected $casts = [
        'is_active' => 'boolean',
        
    ];

    // Relationships
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function getRouteKeyName()
    {
        return 'slug';
    }
    public function setCatTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

}
