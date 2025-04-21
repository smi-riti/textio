<?php

namespace App\Livewire\Public;

use App\Models\Product;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        $products = Product::with(['category', 'brand', 'images', 'variants'])
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
        return view('livewire.public.home',compact('products'));
    }
}
