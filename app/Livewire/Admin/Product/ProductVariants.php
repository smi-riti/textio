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
    public $new_featured_image;
    public $new_featured_image_preview;
    public $temp_featured_path = null;
    public $new_gallery_images = [];
    public $new_gallery_images_preview = [];
    public $temp_gallery_paths = [];
    public $isUploading = false;
    public $editing_featured_image;
    public $editing_featured_image_preview;
    public $editing_gallery_images = [];
    public $editing_gallery_images_preview = [];
    public $showAddForm = false;
    public $selectedVariantTypes = [];
    public $selectedVariantValues = [];
    public $new_combination = [
        'price' => '',
        'stock' => '',
        'sku' => '',
    ];
    public $editingCombination = null;
    public $editing_combination = [];
    public $availableVariants = [];
    public $availableVariantValues = [];
    public $isInitialMount = true;

    protected $listeners = ['refreshVariants' => '$refresh', 'reupload-images' => 'handleReupload'];

    protected function rules()
    {
        return [
            'selectedVariantTypes' => 'required|array|min:1',
            'selectedVariantValues.*' => 'nullable|exists:product_variant_values,id',
            'selectedVariantValues' => 'array',
            'new_combination.price' => 'nullable|numeric|min:0',
            'new_combination.stock' => 'required|integer|min:0',
            'new_combination.sku' => 'nullable|string|max:255',
            'new_featured_image' => 'required|image|max:2048',
            'new_gallery_images.*' => 'nullable|image|max:2048',
            'editing_combination.price' => 'nullable|numeric|min:0',
            'editing_combination.stock' => 'required|integer|min:0',
            'editing_combination.sku' => 'nullable|string|max:255',
            'editing_featured_image' => 'nullable|image|max:2048',
            'editing_gallery_images.*' => 'nullable|image|max:2048',
        ];
    }

    protected $messages = [
        'selectedVariantTypes.required' => 'At least one variant type is required.',
        'selectedVariantTypes.min' => 'Select at least one variant type.',
        'new_combination.stock.required' => 'Stock is required.',
        'new_featured_image.required' => 'Featured image is required for each variant.',
        'new_featured_image.image' => 'Featured image must be a valid image file.',
        'new_gallery_images.*.image' => 'Invalid gallery image.',
        'selectedVariantValues.*.exists' => 'Selected value does not exist.',
        'editing_combination.stock.required' => 'Stock is required for editing.',
    ];

    public function mount($product = null, $variantCombinations = [], $isEdit = false)
    {
        $this->product = $product;
        $this->variantCombinations = $variantCombinations;
        $this->isEdit = $isEdit;
        $this->availableVariants = ProductVariant::with('values')->get();
        if ($this->product && $this->isEdit) {
            $this->loadVariantCombinations();
        }
        $this->isInitialMount = false;
    }

    public function loadVariantCombinations()
    {
        if ($this->product) {
            $this->variantCombinations = $this->product->variantCombinations()
                ->with(['images' => function ($query) {
                    $query->orderBy('is_primary', 'desc');
                }])
                ->get()
                ->map(function ($combo) {
                    $combo->variant_values_data = $combo->variant_values;
                    return $combo->toArray();
                })
                ->toArray();
        }
        if (!$this->isInitialMount) {
            $this->dispatch('refreshVariants');
        }
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
        $prefix = $this->product ? strtoupper(substr($this->product->name, 0, 3)) : 'PRD';
        $this->new_combination['sku'] = $prefix . '-COM-' . strtoupper(Str::random(6));
    }

    public function updatedSelectedVariantTypes()
    {
        Log::info('Variant types updated', ['types' => $this->selectedVariantTypes]);
        $this->selectedVariantValues = [];
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
        Log::info('Available variant values loaded', ['values' => $this->availableVariantValues]);
    }

    public function updatedNewFeaturedImage()
    {
        $this->validateOnly('new_featured_image');
        if ($this->new_featured_image instanceof TemporaryUploadedFile) {
            $this->new_featured_image_preview = $this->new_featured_image->temporaryUrl();
            $this->temp_featured_path = $this->new_featured_image->getFilename();
            Log::debug('Captured temp featured path', ['path' => $this->temp_featured_path]);
        } else {
            Log::warning('New featured image not a valid TemporaryUploadedFile', [
                'type' => is_object($this->new_featured_image) ? get_class($this->new_featured_image) : gettype($this->new_featured_image),
            ]);
            $this->addError('new_featured_image', 'Invalid featured image selected.');
        }
    }

    public function removeNewFeaturedImage()
    {
        $this->new_featured_image = null;
        $this->new_featured_image_preview = null;
        $this->temp_featured_path = null;
    }

    public function updatedNewGalleryImages()
    {
        $this->validateOnly('new_gallery_images.*');
        $this->new_gallery_images_preview = [];
        $this->temp_gallery_paths = [];
        foreach ($this->new_gallery_images as $index => $image) {
            if ($image instanceof TemporaryUploadedFile) {
                $this->new_gallery_images_preview[] = $image->temporaryUrl();
                $this->temp_gallery_paths[$index] = $image->getFilename();
                Log::debug('Captured temp gallery path', ['index' => $index, 'path' => $image->getFilename()]);
            }
        }
    }

    public function removeNewGalleryImage($index)
    {
        unset($this->new_gallery_images[$index], $this->new_gallery_images_preview[$index], $this->temp_gallery_paths[$index]);
        $this->new_gallery_images = array_values($this->new_gallery_images);
        $this->new_gallery_images_preview = array_values($this->new_gallery_images_preview);
        $this->temp_gallery_paths = array_values($this->temp_gallery_paths);
    }

    public function updatedEditingFeaturedImage()
    {
        $this->validateOnly('editing_featured_image');
        $this->editing_featured_image_preview = $this->editing_featured_image->temporaryUrl();
    }

    public function removeEditingFeaturedImage()
    {
        $this->editing_featured_image = null;
        $this->editing_featured_image_preview = null;
    }

    public function updatedEditingGalleryImages()
    {
        $this->validateOnly('editing_gallery_images.*');
        $this->editing_gallery_images_preview = array_map(fn($img) => $img->temporaryUrl(), $this->editing_gallery_images);
    }

    public function removeEditingGalleryImage($index)
    {
        unset($this->editing_gallery_images[$index], $this->editing_gallery_images_preview[$index]);
        $this->editing_gallery_images = array_values($this->editing_gallery_images);
        $this->editing_gallery_images_preview = array_values($this->editing_gallery_images_preview);
    }

    public function addCombination()
    {
        try {
            Log::info('addCombination called', [
                'selectedVariantTypes' => $this->selectedVariantTypes,
                'selectedVariantValues' => $this->selectedVariantValues,
                'new_combination' => $this->new_combination,
                'has_featured_image' => !empty($this->new_featured_image),
                'temp_path' => $this->temp_featured_path,
            ]);

            // Individual validation
            $this->validateOnly('selectedVariantTypes');
            $this->validateOnly('selectedVariantValues');
            $this->validateOnly('new_combination.stock');
            $this->validateOnly('new_combination.price');
            $this->validateOnly('new_combination.sku');
            $this->validateOnly('new_featured_image');
            if (!empty($this->new_gallery_images)) $this->validateOnly('new_gallery_images.*');
            Log::info('New combination validation passed');

            if (empty($this->selectedVariantTypes)) {
                $this->addError('selectedVariantTypes', 'Please select at least one variant type (e.g., Color, Size).');
                return;
            }

            $variantValues = [];
            foreach ($this->selectedVariantValues as $variantId => $valueId) {
                try {
                    Log::info('Processing variant', ['variantId' => $variantId, 'valueId' => $valueId]);
                    if ($valueId && $valueId !== '') {
                        $variant = $this->availableVariants->find($variantId);
                        $value = ProductVariantValue::find($valueId);
                        if ($variant && $value) {
                            $variantValues[$variant->variant_name] = $value->value;
                            Log::info('Added to variantValues', ['name' => $variant->variant_name, 'value' => $value->value]);
                        } else {
                            Log::warning('Variant or value not found', ['variantId' => $variantId, 'valueId' => $valueId]);
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Error in variant processing loop', ['variantId' => $variantId, 'error' => $e->getMessage()]);
                    $this->addError('selectedVariantValues', 'Error processing variant: ' . $e->getMessage());
                    return;
                }
            }

            if (empty($variantValues)) {
                $this->addError('selectedVariantValues', 'Please select a value for each variant type.');
                return;
            }

            foreach ($this->variantCombinations as $existing) {
                $existingValues = $existing['variant_values_data'] ?? json_decode($existing['variant_values'] ?? '[]', true);
                if ($existingValues === $variantValues) {
                    $this->addError('selectedVariantValues', 'This variant combination already exists.');
                    return;
                }
            }

            $combinationData = [
                'temp_id' => uniqid(),
                'price' => $this->new_combination['price'],
                'stock' => $this->new_combination['stock'],
                'sku' => $this->new_combination['sku'],
                'variant_values_data' => $variantValues,
                'images' => [], // Initialize images array
            ];

            Log::info('Creating combination data', ['combinationData' => $combinationData]);

            if ($this->product) {
                // Edit mode - unchanged
                try {
                    $combination = ProductVariantCombination::create([
                        'product_id' => $this->product->id,
                        'variant_values' => json_encode($variantValues),
                        'price' => $combinationData['price'],
                        'stock' => $combinationData['stock'],
                        'sku' => $combinationData['sku'],
                    ]);
                    Log::info('Combination created in DB', ['id' => $combination->id]);

                    Log::debug('Pre-upload file check (edit mode)', [
                        'new_featured_image_set' => !empty($this->new_featured_image),
                        'is_instanceof' => $this->new_featured_image instanceof TemporaryUploadedFile ? 'YES' : 'NO',
                        'temp_path_exists' => !empty($this->temp_featured_path),
                    ]);

                    $this->uploadImages($combination, true);
                    Log::info('Images uploaded (edit mode)');

                    $combination->load('images');
                    $combinationData = $combination->toArray();
                    $combinationData['variant_values_data'] = $variantValues;
                    $this->variantCombinations[] = $combinationData;
                    if (!$this->isInitialMount) {
                        $this->dispatch('combinationAdded', $combinationData);
                    }
                    Log::info('Combination created and dispatched (edit mode)');
                } catch (\Exception $e) {
                    Log::error('Error creating DB combination', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                    $this->addError('general', 'Failed to save combination: ' . $e->getMessage());
                    return;
                }
            } else {
                // Create mode - Add to array AND upload images using temp_id
                Log::info('Create mode - Uploading images using temp_id');
                $this->variantCombinations[] = $combinationData;

                // Call uploadImages with temp data (use temp_id as "id")
                $tempCombination = (object) $combinationData; // Mock object for upload
                $tempCombination->id = $combinationData['temp_id']; // Use temp_id for FK
                Log::debug('Pre-upload file check (create mode)', [
                    'temp_id' => $combinationData['temp_id'],
                    'new_featured_image_set' => !empty($this->new_featured_image),
                    'is_instanceof' => $this->new_featured_image instanceof TemporaryUploadedFile ? 'YES' : 'NO',
                    'temp_path_exists' => !empty($this->temp_featured_path),
                ]);

                $this->uploadImages($tempCombination, true); // Upload now, associate later in save()

                // Reload to include images in array
                $this->loadTempImages($combinationData['temp_id']); // Helper to reload with images

                // FIX: Fetch the updated combinationData from variantCombinations to include images
                foreach ($this->variantCombinations as $combo) {
                    if ($combo['temp_id'] === $combinationData['temp_id']) {
                        $combinationData = $combo; // Update with the version containing images
                        break;
                    }
                }

                if (!$this->isInitialMount) {
                    $this->dispatch('combinationAdded', $combinationData);
                }
                Log::info('Combination added to array with images (create mode)', ['combinationData' => $combinationData]);
            }

            $this->hideAddVariantForm();
            Log::info('addCombination completed successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation exception in addCombination', ['errors' => $e->errors()]);
        } catch (\Exception $e) {
            Log::error('Unexpected error in addCombination', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $this->addError('general', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    // Helper to load temp images from session into array (for create mode)
    private function loadTempImages($tempId)
    {
        // Find the combination in array
        foreach ($this->variantCombinations as &$combo) {
            if ($combo['temp_id'] === $tempId) {
                // Fetch images from session
                $combo['images'] = $this->getUploadedImagesForTemp($tempId);
                Log::info('Loaded temp images for combination', [
                    'temp_id' => $tempId,
                    'image_count' => count($combo['images']),
                ]);
                break;
            }
        }
    }

    // Method to retrieve uploaded images from session
    private function getUploadedImagesForTemp($tempId)
    {
        $images = [];

        // Fetch featured image
        $featured = Session::get("temp_image_{$tempId}_featured");
        if ($featured) {
            $images[] = [
                'image_path' => $featured['path'],
                'image_file_id' => $featured['file_id'] ?? null,
                'is_primary' => true,
            ];
            Log::debug('Retrieved featured image from session', ['temp_id' => $tempId, 'url' => $featured['path']]);
        }

        // Fetch gallery images
        $gallery = Session::get("temp_gallery_{$tempId}", []);
        foreach ($gallery as $imgData) {
            $images[] = [
                'image_path' => $imgData['path'],
                'image_file_id' => $imgData['file_id'] ?? null,
                'is_primary' => false,
            ];
            Log::debug('Retrieved gallery image from session', ['temp_id' => $tempId, 'url' => $imgData['path']]);
        }

        return $images;
    }

    private function resetForm()
    {
        Log::debug('resetForm called - resetting image properties');
        $this->reset(['selectedVariantTypes', 'selectedVariantValues', 'new_combination', 'new_featured_image', 'new_gallery_images', 'new_featured_image_preview', 'new_gallery_images_preview', 'temp_featured_path', 'temp_gallery_paths']);
        $this->resetErrorBag();
    }

    private function uploadImages($combination, $isNew = true)
    {
        Log::info('uploadImages called', ['isNew' => $isNew, 'combination_id' => $combination->id ?? 'temp']);
        $this->isUploading = true;
        $service = new ImageKitService();
        $slug = $this->product ? $this->product->slug : 'temp-' . time();
        $time = time();

        $featuredFile = $isNew ? $this->new_featured_image : $this->editing_featured_image;
        $galleryFiles = $isNew ? $this->new_gallery_images : $this->editing_gallery_images;

        Log::debug('Upload files state', [
            'featured_set' => !empty($featuredFile),
            'featured_type' => $featuredFile ? get_class($featuredFile) : 'null',
            'temp_path' => $isNew ? $this->temp_featured_path : null,
            'gallery_count' => count($galleryFiles ?? []),
        ]);

        // Handle featured
        $uploadFile = null;
        if ($featuredFile && $featuredFile instanceof TemporaryUploadedFile && $featuredFile->exists()) {
            $uploadFile = $featuredFile;
            Log::debug('Using original featured file', ['size' => $uploadFile->getSize()]);
        } elseif ($isNew && $this->temp_featured_path) {
            try {
                $uploadFile = TemporaryUploadedFile::createFromLivewire($this->temp_featured_path);
                Log::info('Reconstructed featured file from temp path', ['path' => $this->temp_featured_path, 'size' => $uploadFile->getSize()]);
            } catch (\Exception $e) {
                Log::error('Failed to reconstruct featured file', ['error' => $e->getMessage()]);
            }
        }

        if ($uploadFile && $uploadFile->exists()) {
            if (!$isNew) {
                $old = $combination->images()->where('is_primary', true)->first();
                if ($old && $old->image_file_id) {
                    try {
                        $service->delete($old->image_file_id);
                        Log::info('Old featured image deleted');
                    } catch (\Exception $e) {
                        Log::error('Failed to delete old featured image: ' . $e->getMessage());
                    }
                    $old->delete();
                }
            }

            $fileName = "featured-{$slug}-{$time}." . $uploadFile->getClientOriginalExtension();
            try {
                $result = $service->upload($uploadFile, $fileName, config('services.imagekit.folders.product'));
                Log::debug('ImageKit response type: ' . gettype($result));
                Log::debug('ImageKit response content: ', ['response' => json_encode($result, JSON_PRETTY_PRINT)]);
                if (is_string($combination->id)) { // Temp mode
                    Session::put("temp_image_{$combination->id}_featured", [
                        'path' => $result->url,
                        'file_id' => $result->fileId ?? null,
                    ]);
                    Log::info('Temp featured image stored in session', ['temp_id' => $combination->id, 'url' => $result->url]);
                } else {
                    $image = ProductImage::create([
                        'product_variant_combination_id' => $combination->id,
                        'image_path' => $result->url,
                        'image_file_id' => $result->fileId ?? null,
                        'is_primary' => true,
                    ]);
                    Log::info('Featured image uploaded successfully to DB', [
                        'url' => $result->url,
                        'file_id' => $result->fileId ?? 'none',
                        'db_id' => $image->id,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to upload featured image: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
                $errorMsg = $isNew ? 'new_featured_image' : 'editing_featured_image';
                $this->addError($errorMsg, 'Failed to upload featured image: ' . $e->getMessage());
            }
        } else {
            Log::warning('No valid featured image to upload', [
                'featured_set' => !empty($featuredFile),
                'instanceof_valid' => $featuredFile instanceof TemporaryUploadedFile ? true : false,
                'exists' => $featuredFile?->exists() ?? false,
                'temp_path' => $this->temp_featured_path,
            ]);
            if ($isNew) {
                $this->addError('new_featured_image', 'Featured image is required and must be valid for variants.');
            }
        }

        // Handle gallery
        if (!empty($galleryFiles)) {
            Log::info('Uploading gallery images', ['count' => count($galleryFiles)]);
            if (!$isNew) {
                $combination->images()->where('is_primary', false)->delete();
                Log::info('Old gallery images deleted');
            }
            $gallerySessionKey = "temp_gallery_{$combination->id}";
            $tempGallery = [];
            foreach ($galleryFiles as $idx => $image) {
                $uploadGalleryFile = null;
                if ($image instanceof TemporaryUploadedFile && $image->exists()) {
                    $uploadGalleryFile = $image;
                } elseif ($isNew && isset($this->temp_gallery_paths[$idx])) {
                    try {
                        $uploadGalleryFile = TemporaryUploadedFile::createFromLivewire($this->temp_gallery_paths[$idx]);
                        Log::info('Reconstructed gallery file', ['index' => $idx, 'path' => $this->temp_gallery_paths[$idx]]);
                    } catch (\Exception $e) {
                        Log::error('Failed to reconstruct gallery file', ['index' => $idx, 'error' => $e->getMessage()]);
                    }
                }

                if ($uploadGalleryFile && $uploadGalleryFile->exists()) {
                    $fileName = "gallery-{$slug}-" . ($idx + 1) . "-{$time}." . $uploadGalleryFile->getClientOriginalExtension();
                    try {
                        $result = $service->upload($uploadGalleryFile, $fileName, config('services.imagekit.folders.product'));
                        $tempGallery[] = [
                            'path' => $result->url,
                            'file_id' => $result->fileId ?? null,
                            'is_primary' => false,
                        ];
                        if (is_string($combination->id)) {
                            Session::put("{$gallerySessionKey}_{$idx}", $tempGallery[$idx]);
                        } else {
                            $galleryImage = ProductImage::create([
                                'product_variant_combination_id' => $combination->id,
                                'image_path' => $result->url,
                                'image_file_id' => $result->fileId ?? null,
                                'is_primary' => false,
                            ]);
                            Log::info('Gallery image uploaded to DB', ['index' => $idx, 'url' => $result->url, 'db_id' => $galleryImage->id]);
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to upload gallery image ' . $idx . ': ' . $e->getMessage());
                    }
                } else {
                    Log::warning('Invalid gallery image at index ' . $idx, ['path' => $this->temp_gallery_paths[$idx] ?? 'none']);
                }
            }
            if (is_string($combination->id)) {
                Session::put($gallerySessionKey, $tempGallery);
                Log::info('Temp gallery images stored in session', ['temp_id' => $combination->id, 'count' => count($tempGallery)]);
            }
        }

        $this->isUploading = false;
        Log::info('uploadImages completed');
    }

    public function handleReupload($product_id)
    {
        $this->product = Product::find($product_id);
        $this->loadVariantCombinations();
        Log::info('Reupload handled for product', ['id' => $product_id]);
    }

    public function render()
    {
        return view('livewire.admin.product.product-variants');
    }
}