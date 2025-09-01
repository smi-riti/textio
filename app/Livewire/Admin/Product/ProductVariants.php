<?php

namespace App\Livewire\Admin\Product;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\ImageKitService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class ProductVariants extends Component
{
    use WithFileUploads;

    public $product;
    public $variants = [];
    public $variant_types = ['Size', 'Color', 'Material', 'Style', 'Finish'];
    public $isEdit = false;

    // New variant form
    public $showAddForm = false;
    public $new_variant = [
        'variant_type' => '',
        'variant_name' => '',
        'price' => '',
        'stock' => '',
        'sku' => '',
        'variant_image' => null,
    ];
    public $new_variant_image_preview;

    // Edit variant
    public $editingVariant = null;
    public $editing_variant = [];
    public $editing_variant_image_preview;

    protected $listeners = ['refreshVariants' => '$refresh'];

    protected function rules()
    {
        $rules = [
            'new_variant.variant_type' => 'required|string|max:255',
            'new_variant.variant_name' => 'required|string|max:255',
            'new_variant.price' => 'nullable|numeric|min:0',
            'new_variant.stock' => 'required|integer|min:0',
            'new_variant.sku' => 'nullable|string|max:255',
            'new_variant.variant_image' => 'nullable|image|max:2048',
        ];

        if ($this->editingVariant) {
            $rules = [
                'editing_variant.variant_type' => 'required|string|max:255',
                'editing_variant.variant_name' => 'required|string|max:255',
                'editing_variant.price' => 'nullable|numeric|min:0',
                'editing_variant.stock' => 'required|integer|min:0',
                'editing_variant.sku' => 'nullable|string|max:255',
                'editing_variant.variant_image' => 'nullable|image|max:2048',
            ];
        }

        return $rules;
    }

    protected $messages = [
        'new_variant.variant_type.required' => 'Variant type is required.',
        'new_variant.variant_name.required' => 'Variant name is required.',
        'new_variant.stock.required' => 'Stock quantity is required.',
        'new_variant.stock.integer' => 'Stock must be a valid number.',
        'new_variant.variant_image.image' => 'File must be an image.',
        'new_variant.variant_image.max' => 'Image must not exceed 2MB.',
        'editing_variant.variant_type.required' => 'Variant type is required.',
        'editing_variant.variant_name.required' => 'Variant name is required.',
        'editing_variant.stock.required' => 'Stock quantity is required.',
        'editing_variant.stock.integer' => 'Stock must be a valid number.',
        'editing_variant.variant_image.image' => 'File must be an image.',
        'editing_variant.variant_image.max' => 'Image must not exceed 2MB.',
    ];

    public function mount($product = null, $isEdit = false)
    {
        $this->product = $product;
        $this->isEdit = $isEdit;
        
        if ($product && $isEdit) {
            $this->loadVariants();
        }
    }

    public function loadVariants()
    {
        $this->variants = $this->product->variants->toArray();
    }

    public function showAddVariantForm()
    {
        $this->showAddForm = true;
        $this->resetNewVariant();
        $this->generateVariantSKU();
    }

    public function hideAddVariantForm()
    {
        $this->showAddForm = false;
        $this->resetNewVariant();
    }

    public function generateVariantSKU()
    {
        $productPrefix = $this->product ? Str::upper(Str::substr($this->product->name, 0, 3)) : 'PRD';
        $this->new_variant['sku'] = $productPrefix . '-VAR-' . strtoupper(Str::random(6));
    }

    public function updatedNewVariantVariantImage()
    {
        $this->validate(['new_variant.variant_image' => 'image|max:2048']);
        if ($this->new_variant['variant_image']) {
            $this->new_variant_image_preview = $this->new_variant['variant_image']->temporaryUrl();
        }
    }

    public function removeNewVariantImage()
    {
        $this->new_variant['variant_image'] = null;
        $this->new_variant_image_preview = null;
    }

    public function addVariant()
    {
        $this->validate();

        try {
            $variantData = [
                'variant_type' => $this->new_variant['variant_type'],
                'variant_name' => $this->new_variant['variant_name'],
                'price' => $this->new_variant['price'] ?: null,
                'stock' => $this->new_variant['stock'],
                'sku' => $this->new_variant['sku'],
            ];

            // Upload image if provided
            if ($this->new_variant['variant_image']) {
                $imageKitService = new ImageKitService();
                $fileName = 'variant_' . time() . '_' . Str::random(10) . '.' . $this->new_variant['variant_image']->getClientOriginalExtension();
                
                $response = $imageKitService->upload(
                    $this->new_variant['variant_image'],
                    $fileName,
                    config('services.imagekit.folders.product') . '/variants'
                );

                $variantData['variant_image'] = $response->url;
            }

            if ($this->product) {
                // Create variant for existing product
                $this->product->variants()->create($variantData);
                $this->loadVariants();
            } else {
                // Add to temporary variants array for new product
                $variantData['temp_id'] = uniqid();
                $this->variants[] = $variantData;
                $this->dispatch('variantAdded', $variantData);
            }

            $this->hideAddVariantForm();
            $this->dispatch('success', 'Variant added successfully!');

        } catch (\Exception $e) {
            $this->dispatch('error', 'Failed to add variant: ' . $e->getMessage());
        }
    }

    public function editVariant($index)
    {
        $this->editingVariant = $index;
        $this->editing_variant = $this->variants[$index];
        $this->editing_variant_image_preview = $this->editing_variant['variant_image'] ?? null;
    }

    public function updatedEditingVariantVariantImage()
    {
        $this->validate(['editing_variant.variant_image' => 'image|max:2048']);
        if ($this->editing_variant['variant_image']) {
            $this->editing_variant_image_preview = $this->editing_variant['variant_image']->temporaryUrl();
        }
    }

    public function removeEditingVariantImage()
    {
        $this->editing_variant['variant_image'] = null;
        $this->editing_variant_image_preview = $this->variants[$this->editingVariant]['variant_image'] ?? null;
    }

    public function updateVariant()
    {
        $this->validate();

        try {
            $variantData = [
                'variant_type' => $this->editing_variant['variant_type'],
                'variant_name' => $this->editing_variant['variant_name'],
                'price' => $this->editing_variant['price'] ?: null,
                'stock' => $this->editing_variant['stock'],
                'sku' => $this->editing_variant['sku'],
            ];

            // Handle image update
            if ($this->editing_variant['variant_image'] && is_object($this->editing_variant['variant_image'])) {
                $imageKitService = new ImageKitService();
                $fileName = 'variant_' . time() . '_' . Str::random(10) . '.' . $this->editing_variant['variant_image']->getClientOriginalExtension();
                
                $response = $imageKitService->upload(
                    $this->editing_variant['variant_image'],
                    $fileName,
                    config('services.imagekit.folders.product') . '/variants'
                );

                $variantData['variant_image'] = $response->url;
            } elseif (isset($this->editing_variant['variant_image'])) {
                $variantData['variant_image'] = $this->editing_variant['variant_image'];
            }

            if ($this->product && isset($this->variants[$this->editingVariant]['id'])) {
                // Update existing variant
                $variant = ProductVariant::find($this->variants[$this->editingVariant]['id']);
                $variant->update($variantData);
                $this->loadVariants();
            } else {
                // Update in temporary variants array
                $this->variants[$this->editingVariant] = array_merge($this->variants[$this->editingVariant], $variantData);
                $this->dispatch('variantUpdated', $this->variants[$this->editingVariant]);
            }

            $this->cancelEdit();
            $this->dispatch('success', 'Variant updated successfully!');

        } catch (\Exception $e) {
            $this->dispatch('error', 'Failed to update variant: ' . $e->getMessage());
        }
    }

    public function deleteVariant($index)
    {
        try {
            if ($this->product && isset($this->variants[$index]['id'])) {
                // Delete from database
                ProductVariant::find($this->variants[$index]['id'])->delete();
                $this->loadVariants();
            } else {
                // Remove from temporary array
                unset($this->variants[$index]);
                $this->variants = array_values($this->variants);
                $this->dispatch('variantDeleted', $index);
            }

            $this->dispatch('success', 'Variant deleted successfully!');

        } catch (\Exception $e) {
            $this->dispatch('error', 'Failed to delete variant: ' . $e->getMessage());
        }
    }

    public function cancelEdit()
    {
        $this->editingVariant = null;
        $this->editing_variant = [];
        $this->editing_variant_image_preview = null;
    }

    private function resetNewVariant()
    {
        $this->new_variant = [
            'variant_type' => '',
            'variant_name' => '',
            'price' => '',
            'stock' => '',
            'sku' => '',
            'variant_image' => null,
        ];
        $this->new_variant_image_preview = null;
    }

    public function getVariantsProperty()
    {
        return $this->variants;
    }

    public function render()
    {
        return view('livewire.admin.product.product-variants');
    }
}
