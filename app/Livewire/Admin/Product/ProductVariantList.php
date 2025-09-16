<?php

namespace App\Livewire\Admin\Product;

use App\Models\Product;
use App\Models\ProductVariantCombination;
use Livewire\Component;
use Livewire\WithPagination;

class ProductVariantList extends Component
{
    use WithPagination;

    public Product $product;
    public $search = '';
    public $perPage = 10;

    protected $paginationTheme = 'tailwind';

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteVariant($variantId)
    {
        try {
            $variant = ProductVariantCombination::findOrFail($variantId);
            
            // Delete associated images first
            foreach ($variant->images as $image) {
                // Delete from ImageKit if needed
                // app(ImageKitService::class)->deleteImage($image->imagekit_file_id);
                $image->delete();
            }
            
            $variant->delete();
            
            session()->flash('success', 'Variant deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete variant: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $variants = $this->product->variantCombinations()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('sku', 'like', '%' . $this->search . '%')
                      ->orWhere('variant_values', 'like', '%' . $this->search . '%');
                });
            })
            ->with(['images'])
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.product.product-variant-list', [
            'variants' => $variants
        ]);
    }
}