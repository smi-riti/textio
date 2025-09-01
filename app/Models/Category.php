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
        'deleted_at' => 'datetime',
    ];

    // Boot method to handle cascade soft deletes
    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function($category) {
            // Check for children
            if ($category->children()->count() > 0) {
                throw new \Exception('Cannot delete category with sub-categories');
            }
            
            // Check for products
            if ($category->products()->count() > 0) {
                throw new \Exception('Cannot delete category with products');
            }
        });
    }

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

    /**
     * Get optimized image URL using ImageKit transformations
     */
    public function getOptimizedImageUrl($width = null, $height = null, $quality = 80)
    {
        if (!$this->image) {
            return null;
        }

        // If it's already an ImageKit URL, add transformations
        if (str_contains($this->image, 'ik.imagekit.io')) {
            $transformations = [];
            
            if ($width) {
                $transformations[] = "w-{$width}";
            }
            
            if ($height) {
                $transformations[] = "h-{$height}";
            }
            
            $transformations[] = "q-{$quality}";
            
            if (!empty($transformations)) {
                $transformString = implode(',', $transformations);
                return str_replace('ik.imagekit.io/', "ik.imagekit.io/tr:{$transformString}/", $this->image);
            }
        }
        
        return $this->image;
    }

    /**
     * Get thumbnail URL (small optimized version)
     */
    public function getThumbnailUrlAttribute()
    {
        return $this->getOptimizedImageUrl(150, 150, 70);
    }

    /**
     * Get medium sized image URL
     */
    public function getMediumImageUrlAttribute()
    {
        return $this->getOptimizedImageUrl(400, 300, 80);
    }
}
