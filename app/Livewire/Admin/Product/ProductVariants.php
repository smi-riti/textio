<?php

namespace App\Livewire\Admin\Product;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductVariantValue;
use App\Models\ProductVariantCombination;
use App\Services\ImageKitService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ProductVariants extends Component
{
    use WithFileUploads;

    public $product;
    public $variantCombinations = [];
    public $isEdit = false;
    
    // Form fields for variant combinations
    public $combination = [
        'id' => null,
        'price' => '',
        'stock' => '',
        'sku' => '',
        'variant_values' => [],
    ];
    
    // Image handling
    public $featuredImage;
    public $featuredImagePreview;
    public $galleryImages = [];
    public $galleryImagePreviews = [];
    public $existingImages = [];
    
    // UI states
    public $isUploading = false;
    public $showForm = false;
    public $editingCombination = null;
    
    // Variant selection
    public $selectedVariantTypes = [];
    public $selectedVariantValues = [];
    public $availableVariants = [];
    public $availableVariantValues = [];

    protected $listeners = ['refreshVariants' => '$refresh'];

    protected function rules()
    {
        $rules = [
            'selectedVariantTypes' => 'required|array|min:1',
            'combination.price' => 'nullable|numeric|min:0',
            'combination.stock' => 'required|integer|min:0',
            'combination.sku' => 'nullable|string|max:255',
            'featuredImage' => $this->editingCombination ? 'nullable|image|max:2048' : 'required|image|max:2048',
            'galleryImages.*' => 'nullable|image|max:2048',
        ];
        
        foreach ($this->selectedVariantTypes as $variantId) {
            $rules["selectedVariantValues.{$variantId}"] = 'required|exists:product_variant_values,id';
        }
        
        return $rules;
    }

    protected $messages = [
        'selectedVariantTypes.required' => 'At least one variant type is required.',
        'selectedVariantTypes.min' => 'Select at least one variant type.',
        'combination.stock.required' => 'Stock is required.',
        'featuredImage.required' => 'Featured image is required for each variant.',
        'featuredImage.image' => 'Featured image must be a valid image file.',
        'galleryImages.*.image' => 'Invalid gallery image.',
        'selectedVariantValues.*.required' => 'Please select a value for each variant type.',
        'selectedVariantValues.*.exists' => 'Selected value does not exist.',
    ];

    public function mount($product = null, $isEdit = false)
    {
        $this->product = $product;
        $this->isEdit = $isEdit;
        $this->availableVariants = ProductVariant::with('values')->get();
        
        if ($this->product && $this->isEdit) {
            $this->loadVariantCombinations();
        }
    }

    public function loadVariantCombinations()
    {
        if ($this->product) {
            $this->variantCombinations = $this->product->variantCombinations()
                ->with(['images' => function ($query) {
                    $query->orderBy('is_primary', 'desc');
                }])
                ->get()
                ->map(function ($combination) {
                    $combinationArray = $combination->toArray();
                    // Ensure variant_values is properly decoded
                    if (isset($combinationArray['variant_values']) && is_string($combinationArray['variant_values'])) {
                        $combinationArray['variant_values'] = json_decode($combinationArray['variant_values'], true) ?? [];
                    }
                    return $combinationArray;
                })
                ->toArray();
        }
    }

    public function showAddForm()
    {
        $this->showForm = true;
        $this->editingCombination = null;
        $this->resetForm();
        $this->generateSKU();
    }

    public function editCombination($combinationId)
    {
        $combination = collect($this->variantCombinations)->firstWhere('id', $combinationId);
        
        if ($combination) {
            $this->editingCombination = $combinationId;
            $this->showForm = true;
            
            // Load combination data
            $this->combination = [
                'id' => $combination['id'],
                'price' => $combination['price'],
                'stock' => $combination['stock'],
                'sku' => $combination['sku'],
                'variant_values' => $combination['variant_values'] ?? [],
            ];
            
            // Load variant selection
            $variantValues = $combination['variant_values'] ?? [];
            // Ensure it's an array
            if (is_string($variantValues)) {
                $variantValues = json_decode($variantValues, true) ?? [];
            }
            
            $this->selectedVariantTypes = [];
            $this->selectedVariantValues = [];
            
            foreach ($variantValues as $variantName => $value) {
                $variant = $this->availableVariants->firstWhere('variant_name', $variantName);
                if ($variant) {
                    $this->selectedVariantTypes[] = $variant->id;
                    $variantValue = $variant->values->firstWhere('value', $value);
                    if ($variantValue) {
                        $this->selectedVariantValues[$variant->id] = $variantValue->id;
                    }
                }
            }
            
            // Update available variant values
            $this->updatedSelectedVariantTypes();
            
            // Load existing images
            $this->existingImages = $combination['images'] ?? [];
            $this->featuredImagePreview = collect($this->existingImages)->firstWhere('is_primary', true)['image_path'] ?? null;
            $this->galleryImagePreviews = collect($this->existingImages)->where('is_primary', false)->pluck('image_path')->toArray();
        }
    }

    public function hideForm()
    {
        $this->showForm = false;
        $this->editingCombination = null;
        $this->resetForm();
    }

    public function generateSKU()
    {
        $prefix = $this->product ? strtoupper(substr($this->product->name, 0, 3)) : 'PRD';
        $this->combination['sku'] = $prefix . '-' . strtoupper(Str::random(6));
    }

    public function updatedSelectedVariantTypes()
    {
        // Reset selected values for removed types
        $this->selectedVariantValues = array_intersect_key(
            $this->selectedVariantValues,
            array_flip($this->selectedVariantTypes)
        );
        
        if (empty($this->selectedVariantTypes)) {
            $this->availableVariantValues = [];
            return;
        }
        
        $this->availableVariantValues = ProductVariantValue::whereIn('product_variant_id', $this->selectedVariantTypes)
            ->get()
            ->groupBy('product_variant_id')
            ->mapWithKeys(function ($values, $variantId) {
                $variant = $this->availableVariants->find($variantId);
                return [$variant->variant_name => $values->pluck('value', 'id')->toArray()];
            })->toArray();
    }

    public function updatedFeaturedImage()
    {
        $this->validateOnly('featuredImage');
        if ($this->featuredImage) {
            $this->featuredImagePreview = $this->featuredImage->temporaryUrl();
        }
    }

    public function removeFeaturedImage()
    {
        $this->featuredImage = null;
        $this->featuredImagePreview = null;
    }

    public function updatedGalleryImages()
    {
        $this->validateOnly('galleryImages.*');
        $this->galleryImagePreviews = [];
        
        foreach ($this->galleryImages as $image) {
            if ($image) {
                $this->galleryImagePreviews[] = $image->temporaryUrl();
            }
        }
    }

    public function removeGalleryImage($index)
    {
        unset($this->galleryImages[$index], $this->galleryImagePreviews[$index]);
        $this->galleryImages = array_values($this->galleryImages);
        $this->galleryImagePreviews = array_values($this->galleryImagePreviews);
    }

    public function removeExistingImage($imageId)
    {
        if ($this->editingCombination) {
            $image = ProductImage::find($imageId);
            if ($image) {
                // Delete from ImageKit if file_id exists
                if ($image->image_file_id) {
                    try {
                        $service = new ImageKitService();
                        $service->delete($image->image_file_id);
                    } catch (\Exception $e) {
                        Log::error('Failed to delete image from ImageKit: ' . $e->getMessage());
                    }
                }
                
                $image->delete();
                
                // Reload existing images
                $this->loadExistingImages();
                session()->flash('message', 'Image deleted successfully!');
            }
        }
    }

    private function loadExistingImages()
    {
        if ($this->editingCombination) {
            $combination = ProductVariantCombination::with('images')->find($this->editingCombination);
            if ($combination) {
                $this->existingImages = $combination->images->toArray();
                $this->featuredImagePreview = collect($this->existingImages)->firstWhere('is_primary', true)['image_path'] ?? null;
                $this->galleryImagePreviews = collect($this->existingImages)->where('is_primary', false)->pluck('image_path')->toArray();
            }
        }
    }

    public function saveCombination()
    {
        $this->validate();
        
        try {
            // Build variant values array
            $variantValues = [];
            foreach ($this->selectedVariantValues as $variantId => $valueId) {
                $variant = $this->availableVariants->find($variantId);
                $value = ProductVariantValue::find($valueId);
                if ($variant && $value) {
                    $variantValues[$variant->variant_name] = $value->value;
                }
            }
            
            // Check for duplicate combinations (only if creating new)
            if (!$this->editingCombination) {
                foreach ($this->variantCombinations as $existing) {
                    $existingValues = $existing['variant_values'] ?? [];
                    // Ensure it's an array for comparison
                    if (is_string($existingValues)) {
                        $existingValues = json_decode($existingValues, true) ?? [];
                    }
                    if ($existingValues === $variantValues) {
                        $this->addError('selectedVariantValues', 'This variant combination already exists.');
                        return;
                    }
                }
            }
            
            // Create or update combination
            if ($this->editingCombination) {
                $combination = ProductVariantCombination::find($this->editingCombination);
                $combination->update([
                    'variant_values' => $variantValues,
                    'price' => $this->combination['price'],
                    'stock' => $this->combination['stock'],
                    'sku' => $this->combination['sku'],
                ]);
            } else {
                $combination = ProductVariantCombination::create([
                    'product_id' => $this->product->id,
                    'variant_values' => $variantValues,
                    'price' => $this->combination['price'],
                    'stock' => $this->combination['stock'],
                    'sku' => $this->combination['sku'],
                ]);
            }
            
            // Upload images
            $this->uploadImages($combination);
            
            // Reload combinations
            $this->loadVariantCombinations();
            
            // Hide form and show success message
            $this->hideForm();
            session()->flash('message', $this->editingCombination ? 'Variant updated successfully!' : 'Variant created successfully!');
            
        } catch (\Exception $e) {
            Log::error('Error saving combination: ' . $e->getMessage());
            $this->addError('general', 'Failed to save combination: ' . $e->getMessage());
        }
    }

    public function deleteCombination($combinationId)
    {
        try {
            $combination = ProductVariantCombination::with('images')->find($combinationId);
            
            if ($combination) {
                // Delete images from ImageKit and database
                foreach ($combination->images as $image) {
                    if ($image->image_file_id) {
                        try {
                            $service = new ImageKitService();
                            $service->delete($image->image_file_id);
                        } catch (\Exception $e) {
                            Log::error('Failed to delete image from ImageKit: ' . $e->getMessage());
                        }
                    }
                    $image->delete();
                }
                
                // Delete combination
                $combination->delete();
                
                // Reload combinations
                $this->loadVariantCombinations();
                
                session()->flash('message', 'Variant deleted successfully!');
            }
        } catch (\Exception $e) {
            Log::error('Error deleting combination: ' . $e->getMessage());
            session()->flash('error', 'Failed to delete variant: ' . $e->getMessage());
        }
    }

    private function uploadImages($combination)
    {
        $this->isUploading = true;
        $service = new ImageKitService();
        $slug = $this->product->slug;
        $time = time();
        
        try {
            // Upload featured image
            if ($this->featuredImage) {
                // Delete existing featured image if editing
                if ($this->editingCombination) {
                    $existingFeatured = $combination->images()->where('is_primary', true)->first();
                    if ($existingFeatured) {
                        if ($existingFeatured->image_file_id) {
                            try {
                                $service->delete($existingFeatured->image_file_id);
                            } catch (\Exception $e) {
                                Log::error('Failed to delete existing featured image: ' . $e->getMessage());
                            }
                        }
                        $existingFeatured->delete();
                    }
                }
                
                $fileName = "featured-{$slug}-{$combination->id}-{$time}." . $this->featuredImage->getClientOriginalExtension();
                $result = $service->upload($this->featuredImage, $fileName, config('services.imagekit.folders.product'));
                
                ProductImage::create([
                    'product_variant_combination_id' => $combination->id,
                    'image_path' => $result->url,
                    'image_file_id' => $result->fileId ?? null,
                    'is_primary' => true,
                ]);
            }
            
            // Upload gallery images
            if (!empty($this->galleryImages)) {
                // Delete existing gallery images if editing
                if ($this->editingCombination) {
                    $existingGallery = $combination->images()->where('is_primary', false)->get();
                    foreach ($existingGallery as $image) {
                        if ($image->image_file_id) {
                            try {
                                $service->delete($image->image_file_id);
                            } catch (\Exception $e) {
                                Log::error('Failed to delete existing gallery image: ' . $e->getMessage());
                            }
                        }
                        $image->delete();
                    }
                }
                
                foreach ($this->galleryImages as $index => $image) {
                    if ($image) {
                        $fileName = "gallery-{$slug}-{$combination->id}-" . ($index + 1) . "-{$time}." . $image->getClientOriginalExtension();
                        $result = $service->upload($image, $fileName, config('services.imagekit.folders.product'));
                        
                        ProductImage::create([
                            'product_variant_combination_id' => $combination->id,
                            'image_path' => $result->url,
                            'image_file_id' => $result->fileId ?? null,
                            'is_primary' => false,
                        ]);
                    }
                }
            }
            
        } catch (\Exception $e) {
            Log::error('Failed to upload images: ' . $e->getMessage());
            $this->addError('general', 'Failed to upload images: ' . $e->getMessage());
        } finally {
            $this->isUploading = false;
        }
    }

    private function resetForm()
    {
        $this->combination = [
            'id' => null,
            'price' => '',
            'stock' => '',
            'sku' => '',
            'variant_values' => [],
        ];
        
        $this->selectedVariantTypes = [];
        $this->selectedVariantValues = [];
        $this->availableVariantValues = [];
        
        $this->featuredImage = null;
        $this->featuredImagePreview = null;
        $this->galleryImages = [];
        $this->galleryImagePreviews = [];
        $this->existingImages = [];
        
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.product.product-variants');
    }
}