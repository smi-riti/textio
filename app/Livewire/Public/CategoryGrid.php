<?php

namespace App\Livewire\Public;

use App\Models\Category;
use Livewire\Component;

class CategoryGrid extends Component
{
    public $categories;

    public function mount()
    {
        // You can eager load product count if needed
        $this->categories = Category::withCount('products')->get();
    }
    public function render()
    {
        return view('livewire.public.category-grid');
    }
}
