<?php

namespace App\Livewire\Admin\Product;

use App\Models\Product;
use App\Models\ProductVariantCombination;
use App\Models\ProductVariant;
use App\Models\ProductVariantValue;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class ProductVariantEdit extends Component
{
    public Product $product;
    public ProductVariantCombination $variant;
    
    public $price = '';
    public $stock = '';
    public $sku = '';
    public $variant_values = [];

    // Available variant types and values for editing
    public $availableVariants = [];

    protected function rules()
    {
        return [
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100',
            'variant_values' => 'required|array|min:1',
        ];
    }

    protected $messages = [
        'price.required' => 'Price is required.',
        'price.numeric' => 'Price must be a valid number.',
        'stock.required' => 'Stock is required.',
        'stock.integer' => 'Stock must be a valid number.',
        'variant_values.required' => 'At least one variant value is required.',
        'variant_values.min' => 'At least one variant value is required.',
    ];

    public function mount(Product $product, ProductVariantCombination $variant)
    {
        $this->product = $product;
        $this->variant = $variant;
        
        // Initialize form fields
        $this->price = $variant->price;
        $this->stock = $variant->stock;
        $this->sku = $variant->sku;
        $this->variant_values = $variant->variant_values ?? [];
        
        // Load available variants
        $this->loadAvailableVariants();
    }

    private function loadAvailableVariants()
    {
        $variants = ProductVariant::with('variantValues')->get();
        
        foreach ($variants as $variant) {
            $this->availableVariants[$variant->variant_name] = $variant->variantValues->pluck('value', 'id')->toArray();
        }
    }

    public function updateVariant()
    {
        $this->validate();

        try {
            $this->variant->update([
                'price' => $this->price,
                'stock' => $this->stock,
                'sku' => $this->sku,
                'variant_values' => $this->variant_values,
            ]);

            session()->flash('success', 'Variant updated successfully!');
            return redirect()->route('admin.products.variants', $this->product);

        } catch (\Exception $e) {
            session()->flash('error', 'Error updating variant: ' . $e->getMessage());
        }
    }

    public function deleteVariant()
    {
        try {
            // Delete associated images
            $this->variant->images()->delete();
            
            // Delete the variant
            $this->variant->delete();

            session()->flash('success', 'Variant deleted successfully!');
            return redirect()->route('admin.products.variants', $this->product);

        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting variant: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.product.product-variant-edit');
    }
}