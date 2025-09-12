<?php

namespace App\Livewire\Admin\Product;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductHighlist;
use App\Models\ProductImage;
use App\Models\ProductVariantCombination;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;

#[Layout('components.layouts.admin')]
class CreateProduct extends Component
{
    public $currentStep = 1;
    public $completedSteps = [];
    public $name = '';
    public $slug = '';
    public $description = '';
    public $price = '';
    public $discount_price = '';
    public $sku = '';
    public $category_id = '';
    public $brand_id = '';
    public $status = false;
    public $is_customizable = false;
    public $featured = false;
    public $meta_title = '';
    public $meta_description = '';
    public $highlights = [];
    public $new_highlight = '';
    public $variantCombinations = [];
    public $isSaving = false;
    public $loadingMessage = '';

    protected $listeners = [
        'stepChanged' => 'handleStepChange',
        'combinationAdded' => 'handleCombinationAdded',
        'combinationUpdated' => 'handleCombinationUpdated',
        'combinationDeleted' => 'handleCombinationDeleted'
    ];

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255|unique:products,name',
            'slug' => 'required|string|max:255|unique:products,slug',
            'description' => 'nullable|string|min:10',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'boolean',
            'is_customizable' => 'boolean',
            'featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'highlights' => 'nullable|array',
            'variantCombinations' => 'required|array|min:1', // Require at least one variant
            'variantCombinations.*.variant_values_data' => 'required|array|min:1',
            'variantCombinations.*.price' => 'required|numeric|min:0',
            'variantCombinations.*.stock' => 'required|integer|min:0',
            'variantCombinations.*.sku' => 'nullable|string|max:255',
        ];

        if ($this->price && is_numeric($this->price) && $this->price > 0) {
            $rules['discount_price'] .= '|lt:price';
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'Product name is required.',
        'name.unique' => 'This product name already exists.',
        'slug.required' => 'Product slug is required.',
        'slug.unique' => 'This product slug already exists.',
        'price.required' => 'Product price is required.',
        'price.numeric' => 'Price must be a valid number.',
        'discount_price.required' => 'Selling price is required.',
        'discount_price.numeric' => 'Selling price must be a valid number.',
        'discount_price.lt' => 'Selling price must be less than the original price.',
        'description.min' => 'Description must be at least 10 characters.',
        'category_id.exists' => 'Please select a valid category.',
        'brand_id.exists' => 'Please select a valid brand.',
        'variantCombinations.required' => 'At least one variant combination is required.',
        'variantCombinations.*.variant_values_data.required' => 'Each variant must have selected values.',
        'variantCombinations.*.stock.required' => 'Stock is required for each variant.',
    ];

    public function mount()
    {
        $this->generateSKU();
    }

    public function generateSKU()
    {
        $this->sku = 'PRD-' . strtoupper(Str::random(8));
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function addHighlight()
    {
        if (!empty($this->new_highlight)) {
            $this->highlights[] = $this->new_highlight;
            $this->new_highlight = '';
        }
    }

    public function removeHighlight($index)
    {
        unset($this->highlights[$index]);
        $this->highlights = array_values($this->highlights);
    }

   public function save()
    {
        Log::info('CreateProduct - Attempting to save product', [
            'step' => $this->currentStep,
            'name' => $this->name,
            'variantCombinations_count' => count($this->variantCombinations),
        ]);

        try {
            $this->validate();
            Log::info('Validation passed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed in save(): ' . json_encode($e->errors()));
            session()->flash('error', 'Validation failed: ' . implode(', ', array_merge(...array_values($e->errors()))));
            return;
        }

        $this->isSaving = true;
        $this->loadingMessage = 'Creating product, please wait...';

        try {
            $product = Product::create([
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description ?: null,
                'price' => $this->price,
                'discount_price' => $this->discount_price,
                'sku' => $this->sku ?: null,
                'category_id' => $this->category_id ?: null,
                'brand_id' => $this->brand_id ?: null,
                'status' => $this->status,
                'is_customizable' => $this->is_customizable,
                'featured' => $this->featured,
                'meta_title' => $this->meta_title ?: null,
                'meta_description' => $this->meta_description ?: null,
            ]);

            Log::info('CreateProduct - Product created', ['id' => $product->id]);

            // Save variant combinations and associate temp images
            foreach ($this->variantCombinations as $comboData) {
                $combo = ProductVariantCombination::create([
                    'product_id' => $product->id,
                    'variant_values' => json_encode($comboData['variant_values_data'] ?? []),
                    'price' => $comboData['price'] ?? null,
                    'stock' => $comboData['stock'],
                    'sku' => $comboData['sku'] ?? null,
                ]);
                Log::info('DB combination created', ['db_id' => $combo->id, 'temp_id' => $comboData['temp_id'] ?? 'n/a']);

                // FIX: Associate temp images to new DB combo
                if (isset($comboData['temp_id'])) {
                    $this->associateTempImages($comboData['temp_id'], $combo->id);
                    Log::info('Temp images associated to DB combo', ['temp_id' => $comboData['temp_id'], 'db_id' => $combo->id]);
                }
            }
            Log::info('Variant combinations saved with images', ['count' => count($this->variantCombinations)]);

            if (!empty($this->highlights)) {
                foreach ($this->highlights as $highlight) {
                    ProductHighlist::create([
                        'product_id' => $product->id,
                        'highlights' => $highlight,
                    ]);
                }
            }

            session()->flash('success', 'Product created successfully!');
            return $this->redirect(route('admin.products.index'), navigate: true);

        } catch (\Exception $e) {
            Log::error('Product creation error: ' . $e->getMessage());
            session()->flash('error', 'Error creating product: ' . $e->getMessage());
        } finally {
            $this->isSaving = false;
        }
    }
    public function handleStepChange($step)
    {
        $this->currentStep = $step;
    }

    public function nextStep()
    {
        if ($this->validateCurrentStep()) {
            if (!in_array($this->currentStep, $this->completedSteps)) {
                $this->completedSteps[] = $this->currentStep;
            }
            if ($this->currentStep < 4) {
                $this->currentStep++;
            }
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function goToStep($step)
    {
        if (in_array($step - 1, $this->completedSteps) || $step === 1) {
            $this->currentStep = $step;
        }
    }

    private function associateTempImages($tempId, $dbId)
    {
        // Featured
        $tempFeatured = session("temp_image_{$tempId}_featured");
        if ($tempFeatured) {
            ProductImage::create([
                'product_variant_combination_id' => $dbId,
                'image_path' => $tempFeatured['path'],
                'image_file_id' => $tempFeatured['file_id'],
                'is_primary' => true,
            ]);
            session()->forget("temp_image_{$tempId}_featured");
            Log::info('Featured image associated from temp', ['db_id' => $dbId]);
        }

        // Gallery
        $galleryKey = "temp_gallery_{$tempId}";
        $tempGallery = session($galleryKey, []);
        foreach ($tempGallery as $imgData) {
            ProductImage::create([
                'product_variant_combination_id' => $dbId,
                'image_path' => $imgData['path'],
                'image_file_id' => $imgData['file_id'],
                'is_primary' => false,
            ]);
        }
        session()->forget($galleryKey);
        Log::info('Gallery images associated from temp', ['db_id' => $dbId, 'count' => count($tempGallery)]);
    }
    private function validateCurrentStep()
    {
        Log::info('Validating step', ['step' => $this->currentStep]);
        switch ($this->currentStep) {
            case 1:
                try {
                    $this->validate([
                        'name' => 'required|string|max:255',
                        'slug' => 'required|string|max:255|unique:products,slug',
                        'description' => 'nullable|string|min:10',
                        'category_id' => 'nullable|exists:categories,id',
                        'brand_id' => 'nullable|exists:brands,id',
                    ]);
                    return true;
                } catch (\Illuminate\Validation\ValidationException $e) {
                    Log::error('Step 1 validation failed: ' . json_encode($e->errors()));
                    return false;
                }
            case 2:
                try {
                    $rules = [
                        'price' => 'required|numeric|min:0',
                        'discount_price' => 'required|numeric|min:0',
                    ];
                    if ($this->price && is_numeric($this->price) && $this->price > 0) {
                        $rules['discount_price'] .= '|lt:price';
                    }
                    $this->validate($rules);
                    return true;
                } catch (\Illuminate\Validation\ValidationException $e) {
                    Log::error('Step 2 validation failed: ' . json_encode($e->errors()));
                    return false;
                }
            case 3:
                try {
                    $this->validate([
                        'variantCombinations' => 'required|array|min:1',
                        'variantCombinations.*.variant_values_data' => 'required|array|min:1',
                        'variantCombinations.*.stock' => 'required|integer|min:0',
                    ]);
                    Log::info('Step 3 validation passed', ['variant_count' => count($this->variantCombinations)]);
                    return true;
                } catch (\Illuminate\Validation\ValidationException $e) {
                    Log::error('Step 3 validation failed: ' . json_encode($e->errors()));
                    session()->flash('error', 'At least one variant combination is required.');
                    return false;
                }
            case 4:
                try {
                    $this->validate([
                        'meta_title' => 'nullable|string|max:255',
                        'meta_description' => 'nullable|string|max:500',
                        'highlights' => 'nullable|array',
                    ]);
                    return true;
                } catch (\Illuminate\Validation\ValidationException $e) {
                    Log::error('Step 4 validation failed: ' . json_encode($e->errors()));
                    return false;
                }
            default:
                return true;
        }
    }

    public function handleCombinationAdded($combination)
    {
        Log::info('Combination added event received', ['combination' => $combination]);
        $this->variantCombinations[] = $combination;
    }

    public function handleCombinationUpdated($combination)
    {
        Log::info('Combination updated event received');
        foreach ($this->variantCombinations as $index => $existing) {
            if (($existing['temp_id'] ?? $existing['id']) === ($combination['temp_id'] ?? $combination['id'])) {
                $this->variantCombinations[$index] = $combination;
                break;
            }
        }
    }

    public function handleCombinationDeleted($index)
    {
        Log::info('Combination deleted event received');
        unset($this->variantCombinations[$index]);
        $this->variantCombinations = array_values($this->variantCombinations);
    }

    public function render()
    {
        $categories = Category::where('is_active', true)->orderBy('title')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();

        return view('livewire.admin.product.create-product', [
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }
}