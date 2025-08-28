<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Brand extends Model
{
    use SoftDeletes;
    protected $fillable = [
        
        'name',
        'slug',               
        'logo',
        'description',
        'is_active',
        'meta_title',
        'meta_description',        
    ];

    protected $casts = [
        'is_active' => 'boolean',
        
    ];
    public function getRouteKeyName()
    {
        return 'slug';
    }
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
