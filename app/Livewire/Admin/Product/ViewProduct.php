<?php

namespace App\Livewire\Admin\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class ViewProduct extends Component
{
    public Product $product;

    public function mount(Product $product)
    {
        $this->product = $product->load([
            'category:id,title', 
            'brand:id,name', 
            'images' => function($query) {
                $query->select('id', 'product_id', 'image_path', 'is_primary')
                      ->orderBy('is_primary', 'desc')
                      ->orderBy('created_at', 'asc');
            },
            'highlights:id,product_id,highlights',
            'variants' => function($query) {
                $query->select('id', 'product_id', 'variant_type', 'variant_name', 'price', 'stock', 'sku', 'variant_image')
                      ->orderBy('variant_type')
                      ->orderBy('variant_name');
            }
        ]);
    }

    public function render()
    {
        return view('livewire.admin.product.view-product');
    }
}
