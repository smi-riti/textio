<?php

namespace App\Livewire\Admin\Product;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantValue;
use App\Models\ProductVariantCombination;
use App\Services\ImageKitService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class ProductVariants extends Component
{
    use WithFileUploads;

    public $product;
    public $variantCombinations = [];
    public $isEdit = false;

    // Form state
    public $showAddForm = false;
    public $selectedVariantTypes = [];
    public $selectedVariantValues = [];
    public $new_combination = [
        'price' => '',
        'stock' => '',
        'sku' => '',
        'variant_image' => null,
    ];
    public $new_variant_image_preview;

    // Edit state
    public $editingCombination = null;
    public $editing_combination = [];
    public $editing_combination_image_preview;

    // Available variants and values
    public $availableVariants = [];
    public $availableVariantValues = [];

    protected $listeners = ['refreshVariants' => '$refresh'];

    protected function rules()
    {
        $rules = [
            'selectedVariantTypes' => 'required|array|min:1',
            'selectedVariantValues' => 'required|array|min:1',
            'new_combination.price' => 'nullable|numeric|min:0',
            'new_combination.stock' => 'required|integer|min:0',
            'new_combination.sku' => 'nullable|string|max:255|unique:product_variant_combinations,sku',
            'new_combination.variant_image' => 'nullable|image|max:2048',
        ];

        if ($this->editingCombination !== null) {
            $rules = [
                'editing_combination.price' => 'nullable|numeric|min:0',
                'editing_combination.stock' => 'required|integer|min:0',
                'editing_combination.sku' => 'nullable|string|max:255|unique:product_variant_combinations,sku,' . ($this->variantCombinations[$this->editingCombination]['id'] ?? null),
                'editing_combination.variant_image' => 'nullable|image|max:2048',
            ];
        }

        return $rules;
    }

    protected $messages = [
        'selectedVariantTypes.required' => 'At least one variant type is required.',
        'selectedVariantValues.required' => 'At least one variant value is required.',
        'new_combination.stock.required' => 'Stock quantity is required.',
        'new_combination.stock.integer' => 'Stock must be a valid number.',
        'new_combination.variant_image.image' => 'File must be an image.',
        'new_combination.variant_image.max' => 'Image must not exceed 2MB.',
        'new_combination.sku.unique' => 'This SKU is already in use.',
        'editing_combination.stock.required' => 'Stock quantity is required.',
        'editing_combination.stock.integer' => 'Stock must be a valid number.',
        'editing_combination.variant_image.image' => 'File must be an image.',
        'editing_combination.variant_image.max' => 'Image must not exceed 2MB.',
        'editing_combination.sku.unique' => 'This SKU is already in use.',
    ];

    public function mount($product = null, $isEdit = false, $variantCombinations = [])
    {
        $this->isEdit = $isEdit;
        $this->product = $product;
        $this->variantCombinations = $variantCombinations;

        \Log::info('ProductVariants - Mounted', [
            'product_id' => $this->product?->id,
            'isEdit' => $isEdit,
            'variantCombinations_count' => count($variantCombinations)
        ]);

        $this->availableVariants = ProductVariant::with('values')->get();

        if ($this->product && $this->isEdit) {
            $this->loadVariantCombinations();
        }
    }

    public function loadVariantCombinations()
    {
        $this->variantCombinations = $this->product->variantCombinations()->get()->toArray();
    }

    public function showAddVariantForm()
    {
        $this->showAddForm = true;
        $this->resetForm();
        $this->generateCombinationSKU();
    }

    public function hideAddVariantForm()
    {
        $this->showAddForm = false;
        $this->resetForm();
    }

    public function generateCombinationSKU()
    {
        $productPrefix = $this->product ? Str::upper(Str::substr($this->product->name, 0, 3)) : 'PRD';
        $this->new_combination['sku'] = $productPrefix . '-COM-' . strtoupper(Str::random(6));
    }

    public function updatedSelectedVariantTypes()
    {
        $this->selectedVariantValues = [];

        $this->availableVariantValues = ProductVariantValue::whereIn('product_variant_id', $this->selectedVariantTypes)
            ->get()
            ->groupBy('product_variant_id')
            ->mapWithKeys(function ($values, $variantId) {
                $variant = $this->availableVariants->find($variantId);
                return [$variant->variant_name => $values->pluck('value', 'id')->toArray()];
            })->toArray();
    }

    public function updatedNewCombinationVariantImage()
    {
        $this->validate(['new_combination.variant_image' => 'image|max:2048']);
        if ($this->new_combination['variant_image']) {
            $this->new_variant_image_preview = $this->new_combination['variant_image']->temporaryUrl();
        }
    }

    public function removeNewVariantImage()
    {
        $this->new_combination['variant_image'] = null;
        $this->new_variant_image_preview = null;
    }

    public function addCombination()
    {
        $this->validate();

        try {
            $variantValues = [];
            foreach ($this->selectedVariantValues as $variantId => $valueId) {
                if (!empty($valueId)) {
                    $variant = $this->availableVariants->find($variantId);
                    $value = ProductVariantValue::find($valueId);
                    if ($variant && $value) {
                        $variantValues[$variant->variant_name] = $value->value;
                    }
                }
            }

            if (empty($variantValues)) {
                $this->addError('selectedVariantValues', 'At least one variant value must be selected.');
                return;
            }

            // Check for duplicate combinations in memory
            foreach ($this->variantCombinations as $existingCombination) {
                $existingValues = isset($existingCombination['variant_values_data'])
                    ? $existingCombination['variant_values_data']
                    : json_decode($existingCombination['variant_values'] ?? '[]', true);
                if ($existingValues == $variantValues) {
                    $this->addError('selectedVariantValues', 'This variant combination already exists.');
                    return;
                }
            }

            $combinationData = [
                'temp_id' => uniqid(),
                'price' => $this->new_combination['price'] ?: null,
                'stock' => $this->new_combination['stock'],
                'sku' => $this->new_combination['sku'],
                'variant_values_data' => $variantValues,
            ];

            if ($this->new_combination['variant_image']) {
                $imageKitService = new ImageKitService();
                $fileName = 'combination_' . time() . '_' . Str::random(10) . '.' . $this->new_combination['variant_image']->getClientOriginalExtension();

                $response = $imageKitService->upload(
                    $this->new_combination['variant_image'],
                    $fileName,
                    config('services.imagekit.folders.product') . '/combinations'
                );

                $combinationData['image'] = $response->url;
            }

            $this->variantCombinations[] = $combinationData;
            $this->dispatch('combinationAdded', $combinationData);

            $this->hideAddVariantForm();
            $this->dispatch('success', 'Variant combination added successfully!');

        } catch (\Exception $e) {
            $this->dispatch('error', 'Failed to add variant combination: ' . $e->getMessage());
        }
    }

    public function editCombination($index)
    {
        $this->editingCombination = $index;
        $this->editing_combination = $this->variantCombinations[$index];
        $this->editing_combination_image_preview = $this->editing_combination['image'] ?? null;
    }

    public function updatedEditingCombinationVariantImage()
    {
        $this->validate(['editing_combination.variant_image' => 'image|max:2048']);
        if ($this->editing_combination['variant_image']) {
            $this->editing_combination_image_preview = $this->editing_combination['variant_image']->temporaryUrl();
        }
    }

    public function removeEditingCombinationImage()
    {
        $this->editing_combination['variant_image'] = null;
        $this->editing_combination_image_preview = $this->variantCombinations[$this->editingCombination]['image'] ?? null;
    }

    public function updateCombination()
    {
        $this->validate();

        try {
            $combinationData = [
                'price' => $this->editing_combination['price'] ?: null,
                'stock' => $this->editing_combination['stock'],
                'sku' => $this->editing_combination['sku'],
            ];

            if ($this->editing_combination['variant_image'] && is_object($this->editing_combination['variant_image'])) {
                $imageKitService = new ImageKitService();
                $fileName = 'combination_' . time() . '_' . Str::random(10) . '.' . $this->editing_combination['variant_image']->getClientOriginalExtension();

                $response = $imageKitService->upload(
                    $this->editing_combination['variant_image'],
                    $fileName,
                    config('services.imagekit.folders.product') . '/combinations'
                );

                $combinationData['image'] = $response->url;
            } elseif (isset($this->editing_combination['image'])) {
                $combinationData['image'] = $this->editing_combination['image'];
            }

            if ($this->product && isset($this->variantCombinations[$this->editingCombination]['id'])) {
                $combination = ProductVariantCombination::find($this->variantCombinations[$this->editingCombination]['id']);
                $combination->update($combinationData);
                $this->loadVariantCombinations();
            } else {
                $this->variantCombinations[$this->editingCombination] = array_merge(
                    $this->variantCombinations[$this->editingCombination],
                    $combinationData
                );
                $this->dispatch('combinationUpdated', $this->variantCombinations[$this->editingCombination]);
            }

            $this->cancelEdit();
            $this->dispatch('success', 'Variant combination updated successfully!');

        } catch (\Exception $e) {
            $this->dispatch('error', 'Failed to update variant combination: ' . $e->getMessage());
        }
    }

    public function deleteCombination($index)
    {
        try {
            if ($this->product && isset($this->variantCombinations[$index]['id'])) {
                ProductVariantCombination::find($this->variantCombinations[$index]['id'])->delete();
                $this->loadVariantCombinations();
            } else {
                unset($this->variantCombinations[$index]);
                $this->variantCombinations = array_values($this->variantCombinations);
                $this->dispatch('combinationDeleted', $index);
            }

            $this->dispatch('success', 'Variant combination deleted successfully!');

        } catch (\Exception $e) {
            $this->dispatch('error', 'Failed to delete variant combination: ' . $e->getMessage());
        }
    }

    public function cancelEdit()
    {
        $this->editingCombination = null;
        $this->editing_combination = [];
        $this->editing_combination_image_preview = null;
    }

    private function resetForm()
    {
        $this->selectedVariantTypes = [];
        $this->selectedVariantValues = [];
        $this->new_combination = [
            'price' => '',
            'stock' => '',
            'sku' => '',
            'variant_image' => null,
        ];
        $this->new_variant_image_preview = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.product.product-variants');
    }
}