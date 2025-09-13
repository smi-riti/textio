<?php

namespace App\Livewire\Public\Section;

use App\Models\Category;
use Illuminate\Cache\RateLimiting\Limit;
use Livewire\Component;

class Categories extends Component
{
    public $categories;
 
    public $selectedCategory;
    public function mount()
    {
$this->categories = Category::select('id', 'title', 'slug','image')
    ->where('is_active', true)
    ->limit(3)
    ->get();


//$categories = Category::select('id', 'title', 'slug', 'image_url')->get();

if (request()->has('category_slug')) {
        $cat = Category::where('slug', request('category_slug'))->first();
        $this->selectedCategory = $cat?->id ?? null;
    }
    }
    public function render()
    {
        return view('livewire.public.section.categories');
    }
}
