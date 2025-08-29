<?php

namespace App\Livewire\Public\Section;

use App\Models\Category;
use Illuminate\Cache\RateLimiting\Limit;
use Livewire\Component;

class Categories extends Component
{
    public $categories;

    public function mount()
    {
$this->categories = Category::select('id', 'title', 'slug')
    ->where('is_active', true)
    ->limit(12)
    ->get();

//$categories = Category::select('id', 'title', 'slug', 'image_url')->get();


    }
    public function render()
    {
        return view('livewire.public.section.categories');
    }
}
