<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'parent_category_id',
        'title',
        'slug',
        'level',
        'order',
        'image',
        'description',
        'is_active',
        'meta_title',
        'meta_description',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'level' => 'integer',
        'order' => 'integer',
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Boot method for automatic slug generation
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = $category->slug ?? \Str::slug($category->title);
            $category->created_by = $category->created_by ?? auth()->id();
            $category->updated_by = $category->updated_by ?? auth()->id();
        });

        static::updating(function ($category) {
            $category->updated_by = auth()->id();
            if ($category->isDirty('title') && !$category->isDirty('slug')) {
                $category->slug = \Str::slug($category->title);
            }
        });
    }
}
