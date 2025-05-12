<?php

namespace App\Livewire\Public;

use App\Models\Brand;
use Livewire\Component;

class BrandDisplay extends Component
{
    public function render()
    {
        $data['brands'] = Brand::latest()->take(4)->get();

        return view('livewire.public.brand-display',$data);
    }
}
