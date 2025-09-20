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
    public $existingImages = [];

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
        'editing_featured_image.required' => 'A featured image is required.',
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
        $this->editingCombination = null;
        $this->resetForm();
        $this->generateCombinationSKU();
    }

    public function hideAddVariantForm()
    {
        $this->showAddForm = false;
        $this->editingCombination = null;
        $this->resetForm();
    }

    public function generateCombinationSKU()
    {
        $prefix = $this->product ? strtoupper(substr($this->product->name, 0, 3)) : 'PRD';
        if ($this->editingCombination !== null) {
            $this->editing_combination['sku'] = $prefix . '-COM-' . strtoupper(Str::random(6));
        } else {
            $this->new_combination['sku'] = $prefix . '-COM-' . strtoupper(Str::random(6));
        }
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

    public function editCombination($index)
    {
        try {
            Log::info('editCombination called', ['index' => $index]);

            if (!isset($this->variantCombinations[$index])) {
                Log::error('Invalid combination index', ['index' => $index]);
                $this->addError('general', 'Invalid variant combination selected.');
                return;
            }

            $this->resetForm();
            $this->editingCombination = $index;
            $combination = $this->variantCombinations[$index];

            $this->editing_combination = [
                'price' => $combination['price'] ?? '',
                'stock' => $combination['stock'] ?? '',
                'sku' => $combination['sku'] ?? '',
            ];

            $this->selectedVariantTypes = [];
            $this->selectedVariantValues = [];
            $variantValues = $combination['variant_values_data'] ?? [];
            if (empty($variantValues) && !empty($combination['variant_values'])) {
                $decoded = json_decode($combination['variant_values'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $variantValues = $decoded;
                } else {
                    Log::error('Invalid JSON in variant_values', ['variant_values' => $combination['variant_values']]);
                    $this->addError('general', 'Invalid variant data format.');
                    return;
                }
            }

            foreach ($variantValues as $variantName => $value) {
                $variant = $this->availableVariants->firstWhere('variant_name', $variantName);
                if ($variant) {
                    $this->selectedVariantTypes[] = $variant->id;
                    $valueRecord = ProductVariantValue::where('product_variant_id', $variant->id)
                        ->where('value', $value)
                        ->first();
                    if ($valueRecord) {
                        $this->selectedVariantValues[$variant->id] = $valueRecord->id;
                    }
                }
            }

            if (!$this->product && isset($combination['temp_id'])) {
                $this->loadTempImages($combination['temp_id']);
                $this->existingImages = $this->variantCombinations[$index]['images'] ?? [];
            } else {
                $this->existingImages = $combination['images'] ?? [];
            }
            $this->editing_featured_image = null;
            $this->editing_featured_image_preview = null;
            $this->editing_gallery_images = [];
            $this->editing_gallery_images_preview = [];

            $this->updatedSelectedVariantTypes();
            $this->showAddForm = true;

            Log::info('Editing combination initialized', [
                'index' => $index,
                'editing_combination' => $this->editing_combination,
                'selectedVariantTypes' => $this->selectedVariantTypes,
                'selectedVariantValues' => $this->selectedVariantValues,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in editCombination', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $this->addError('general', 'Unable to load variant for editing. Please try again or contact support.');
        }
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
                'editingCombination' => $this->editingCombination,
            ]);

            $isEditing = $this->editingCombination !== null;
            $formData = $isEditing ? $this->editing_combination : $this->new_combination;
            $featuredImage = $isEditing ? $this->editing_featured_image : $this->new_featured_image;
            $galleryImages = $isEditing ? $this->editing_gallery_images : $this->new_gallery_images;

            $this->validateOnly('selectedVariantTypes');
            $this->validateOnly('selectedVariantValues');
            $this->validateOnly($isEditing ? 'editing_combination.stock' : 'new_combination.stock');
            $this->validateOnly($isEditing ? 'editing_combination.price' : 'new_combination.price');
            $this->validateOnly($isEditing ? 'editing_combination.sku' : 'new_combination.sku');
            if (!$isEditing) {
                $this->validateOnly('new_featured_image');
            } elseif (empty($this->editing_featured_image) && empty(collect($this->existingImages)->firstWhere('is_primary', true))) {
                $this->addError('editing_featured_image', 'A featured image is required.');
                return;
            }
            if (!empty($galleryImages)) {
                $this->validateOnly($isEditing ? 'editing_gallery_images.*' : 'new_gallery_images.*');
            }
            Log::info('Validation passed', ['isEditing' => $isEditing]);

            if (empty($this->selectedVariantTypes)) {
                $this->addError('selectedVariantTypes', 'Please select at least one variant type (e.g., Color, Size).');
                return;
            }

            $variantValues = [];
            foreach ($this->selectedVariantValues as $variantId => $valueId) {
                if ($valueId && $valueId !== '') {
                    $variant = $this->availableVariants->find($variantId);
                    $value = ProductVariantValue::find($valueId);
                    if ($variant && $value) {
                        $variantValues[$variant->variant_name] = $value->value;
                    } else {
                        Log::warning('Variant or value not found', ['variantId' => $variantId, 'valueId' => $valueId]);
                    }
                }
            }

            if (empty($variantValues)) {
                $this->addError('selectedVariantValues', 'Please select a value for each variant type.');
                return;
            }

            foreach ($this->variantCombinations as $index => $existing) {
                if ($isEditing && $index === $this->editingCombination) {
                    continue;
                }
                $existingValues = $existing['variant_values_data'] ?? json_decode($existing['variant_values'] ?? '[]', true);
                ksort($existingValues);
                ksort($variantValues);
                if ($existingValues === $variantValues) {
                    $this->addError('selectedVariantValues', 'This variant combination already exists.');
                    return;
                }
            }

            $combinationData = [
                'temp_id' => $isEditing ? ($this->variantCombinations[$this->editingCombination]['temp_id'] ?? null) : uniqid(),
                'price' => $formData['price'],
                'stock' => $formData['stock'],
                'sku' => $formData['sku'],
                'variant_values_data' => $variantValues,
                'images' => $isEditing ? ($this->existingImages ?? []) : [],
            ];

            if ($this->product) {
                if ($isEditing) {
                    $combination = ProductVariantCombination::find($this->variantCombinations[$this->editingCombination]['id']);
                    if (!$combination) {
                        $this->addError('general', 'Variant combination not found.');
                        return;
                    }
                    $combination->update([
                        'variant_values' => json_encode($variantValues),
                        'price' => $formData['price'],
                        'stock' => $formData['stock'],
                        'sku' => $formData['sku'],
                    ]);
                    Log::info('Combination updated in DB', ['id' => $combination->id]);

                    if ($featuredImage || !empty($galleryImages)) {
                        $this->uploadImages($combination, false);
                    }

                    $combination->load('images');
                    $this->variantCombinations[$this->editingCombination] = $combination->toArray();
                    $this->variantCombinations[$this->editingCombination]['variant_values_data'] = $variantValues;
                } else {
                    $combination = ProductVariantCombination::create([
                        'product_id' => $this->product->id,
                        'variant_values' => json_encode($variantValues),
                        'price' => $formData['price'] ?? null,
                        'stock' => $formData['stock'],
                        'sku' => $formData['sku'],
                    ]);
                    $this->uploadImages($combination, true);
                    $combination->load('images');
                    $combinationData = $combination->toArray();
                    $combinationData['variant_values_data'] = $variantValues;
                    $this->variantCombinations[] = $combinationData;
                }
            } else {
                if ($isEditing) {
                    $this->variantCombinations[$this->editingCombination] = $combinationData;
                    if ($featuredImage || !empty($galleryImages)) {
                        $tempCombination = (object) $combinationData;
                        $tempCombination->id = $combinationData['temp_id'];
                        $this->uploadImages($tempCombination, false);
                        $this->loadTempImages($combinationData['temp_id']);
                        foreach ($this->variantCombinations as &$combo) {
                            if ($combo['temp_id'] === $combinationData['temp_id']) {
                                $combinationData = $combo;
                                break;
                            }
                        }
                    }
                } else {
                    $this->variantCombinations[] = $combinationData;
                    $tempCombination = (object) $combinationData;
                    $tempCombination->id = $combinationData['temp_id'];
                    $this->uploadImages($tempCombination, true);
                    $this->loadTempImages($combinationData['temp_id']);
                    foreach ($this->variantCombinations as &$combo) {
                        if ($combo['temp_id'] === $combinationData['temp_id']) {
                            $combinationData = $combo;
                            break;
                        }
                    }
                }
            }

            if (!$this->isInitialMount) {
                $this->dispatch('combinationAdded', $combinationData);
            }

            $this->hideAddVariantForm();
            $this->editingCombination = null;
            Log::info('addCombination completed successfully', ['isEditing' => $isEditing]);
        } catch (\Exception $e) {
            Log::error('Unexpected error in addCombination', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $this->addError('general', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    public function removeExistingImage($index)
    {
        try {
            Log::info('removeExistingImage called', ['index' => $index]);

            if (!isset($this->existingImages[$index])) {
                Log::error('Invalid image index', ['index' => $index]);
                $this->addError('general', 'Invalid image selected.');
                return;
            }

            $image = $this->existingImages[$index];

            if ($this->product && isset($image['id'])) {
                $dbImage = ProductImage::find($image['id']);
                if ($dbImage) {
                    if ($dbImage->image_file_id) {
                        $service = new ImageKitService();
                        $service->delete($dbImage->image_file_id);
                        Log::info('Image deleted from ImageKit', ['file_id' => $dbImage->image_file_id]);
                    }
                    $dbImage->delete();
                    Log::info('Image deleted from DB', ['id' => $image['id']]);
                }
            } else {
                $tempId = $this->variantCombinations[$this->editingCombination]['temp_id'] ?? null;
                if ($tempId) {
                    if ($image['is_primary']) {
                        Session::forget("temp_image_{$tempId}_featured");
                        Log::info('Removed featured image from session', ['temp_id' => $tempId]);
                    } else {
                        $gallery = Session::get("temp_gallery_{$tempId}", []);
                        unset($gallery[$index]);
                        Session::put("temp_gallery_{$tempId}", array_values($gallery));
                        Log::info('Removed gallery image from session', ['temp_id' => $tempId, 'index' => $index]);
                    }
                }
            }

            unset($this->existingImages[$index]);
            $this->existingImages = array_values($this->existingImages);

            if ($this->editingCombination !== null) {
                $this->variantCombinations[$this->editingCombination]['images'] = $this->existingImages;
            }

            Log::info('Image removed successfully', ['index' => $index]);
        } catch (\Exception $e) {
            Log::error('Error in removeExistingImage', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $this->addError('general', 'Failed to remove image: ' . $e->getMessage());
        }
    }

    public function deleteCombination($index)
    {
        try {
            Log::info('deleteCombination called', ['index' => $index]);

            if (!isset($this->variantCombinations[$index])) {
                Log::error('Invalid combination index', ['index' => $index]);
                $this->addError('general', 'Invalid variant combination selected.');
                return;
            }

            $combination = $this->variantCombinations[$index];

            if ($this->product && isset($combination['id'])) {
                $dbCombination = ProductVariantCombination::find($combination['id']);
                if ($dbCombination) {
                    $service = new ImageKitService();
                    foreach ($dbCombination->images as $image) {
                        if ($image->image_file_id) {
                            $service->delete($image->image_file_id);
                            Log::info('Image deleted from ImageKit', ['file_id' => $image->image_file_id]);
                        }
                    }
                    $dbCombination->images()->delete();
                    $dbCombination->delete();
                    Log::info('Combination deleted from DB', ['id' => $combination['id']]);
                }
            } else {
                $tempId = $combination['temp_id'] ?? null;
                if ($tempId) {
                    Session::forget("temp_image_{$tempId}_featured");
                    Session::forget("temp_gallery_{$tempId}");
                    Log::info('Removed temp images from session', ['temp_id' => $tempId]);
                }
            }

            unset($this->variantCombinations[$index]);
            $this->variantCombinations = array_values($this->variantCombinations);

            Log::info('Combination deleted successfully', ['index' => $index]);
            $this->dispatch('combinationDeleted');
        } catch (\Exception $e) {
            Log::error('Error in deleteCombination', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $this->addError('general', 'Failed to delete variant: ' . $e->getMessage());
        }
    }

    private function loadTempImages($tempId)
    {
        foreach ($this->variantCombinations as &$combo) {
            if ($combo['temp_id'] === $tempId) {
                $images = $this->getUploadedImagesForTemp($tempId);
                if (empty($images)) {
                    Log::warning('No temp images found in session', ['temp_id' => $tempId]);
                }
                $combo['images'] = $images;
                Log::info('Loaded temp images for combination', [
                    'temp_id' => $tempId,
                    'image_count' => count($combo['images']),
                ]);
                break;
            }
        }
    }

    private function getUploadedImagesForTemp($tempId)
    {
        $images = [];

        $featured = Session::get("temp_image_{$tempId}_featured");
        if ($featured) {
            $images[] = [
                'image_path' => $featured['path'],
                'image_file_id' => $featured['file_id'] ?? null,
                'is_primary' => true,
            ];
            Log::debug('Retrieved featured image from session', ['temp_id' => $tempId, 'url' => $featured['path']]);
        }

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
        $this->reset([
            'selectedVariantTypes',
            'selectedVariantValues',
            'new_combination',
            'new_featured_image',
            'new_gallery_images',
            'new_featured_image_preview',
            'new_gallery_images_preview',
            'temp_featured_path',
            'temp_gallery_paths',
            'editing_combination',
            'editing_featured_image',
            'editing_gallery_images',
            'editing_featured_image_preview',
            'editing_gallery_images_preview',
            'existingImages'
        ]);
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
                if (is_string($combination->id)) {
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

        if (!empty($galleryFiles)) {
            Log::info('Uploading gallery images', ['count' => count($galleryFiles)]);
            $gallerySessionKey = "temp_gallery_{$combination->id}";
            $tempGallery = $isNew ? [] : (is_string($combination->id) ? Session::get($gallerySessionKey, []) : []);
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