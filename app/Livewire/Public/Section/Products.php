<?php

namespace App\Livewire\Public\Section;

use App\Models\Product;
use Livewire\Component;

class Products extends Component
{
    public $Products;

   public function mount()
{
    $this->Products = Product::with(['images' => function($query) {
            $query->where('is_primary', true); 
        }])
        ->select('id', 'name', 'slug', 'price', 'discount_price')
        ->where('status', true)
        ->where('featured', true)
        ->inRandomOrder()
        ->limit(4)
        ->get();

        //dd($testing);

}


    public function render()
    {
        return view('livewire.public.section.products');
    }
}
