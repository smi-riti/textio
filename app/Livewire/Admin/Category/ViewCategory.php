<?php

namespace App\Livewire\Admin\Category;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Category;

#[Layout('components.layouts.admin')]
class ViewCategory extends Component
{
    public Category $category;

    public function mount($slug)
    {
        $this->category = Category::where('slug', $slug)
            ->with(['parent:id,title', 'products'])
            ->withCount('products')
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.admin.category.view-category');
    }
}
