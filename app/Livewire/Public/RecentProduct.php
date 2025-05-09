<?php

namespace App\Livewire\Public;

use App\Models\Product;
use Livewire\Component;

class RecentProduct extends Component
{
    public function render()
    {
        $data['products'] = Product::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->latest()
            ->take(10)
            ->get();
        return view('livewire.public.recent-product',$data);
    }
}
