<?php

namespace App\Livewire\Admin\Product;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;
use App\Models\ProductHighlist;
use App\Models\ProductVariantCombination;
use App\Services\ImageKitService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class CreateProduct extends Component
{
    use WithFileUploads;

    // Stepper
    public $currentStep = 1;
    public $completedSteps = [];

    // Product Properties
    public $name = '';
    public $slug = '';
    public $description = '';
    public $price = '';
    public $discount_price = '';
    public $quantity = '';
    public $sku = '';
    public $category_id = '';
    public $brand_id = '';
    public $status = false;
    public $is_customizable = false;
    public $featured = false;
    public $meta_title = '';
    public $meta_description = '';

    // Highlights
    public $highlights = [];
    public $new_highlight = '';

    // Images
    public $featured_image;
    public $featured_image_preview;
    public $featured_image_file_id;
    public $featured_image_url;
    public $isUploadingFeaturedImage = false;

    public $gallery_images = [];
    public $gallery_images_preview = [];
    public $gallery_images_data = [];
    public $isUploadingGalleryImages = false;

    // Variants
    public $variantCombinations = [];

    // Loading States
    public $isLoading = false;
    public $loadingMessage = '';
    public $isSaving = false;

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
            'quantity' => 'required|integer|min:0',
            'sku' => 'required|string|max:100|unique:products,sku',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'boolean',
            'is_customizable' => 'boolean',
            'featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'featured_image' => 'required|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
            'highlights' => 'nullable|array',
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
        'discount_price.required' => 'Selling price (discount price) is required.',
        'discount_price.numeric' => 'Selling price must be a valid number.',
        'discount_price.lt' => 'Selling price must be less than the original price.',
        'sku.required' => 'SKU is required.',
        'sku.unique' => 'This SKU already exists.',
        'description.min' => 'Description must be at least 10 characters.',
        'quantity.required' => 'Stock quantity is required.',
        'quantity.integer' => 'Stock quantity must be a valid number.',
        'category_id.exists' => 'Please select a valid category.',
        'brand_id.exists' => 'Please select a valid brand.',
        'featured_image.required' => 'Featured image is required.',
        'featured_image.image' => 'Featured image must be an image.',
        'featured_image.max' => 'Featured image must not exceed 2MB.',
        'gallery_images.*.image' => 'Uploaded files must be images.',
        'gallery_images.*.max' => 'Each gallery image must not exceed 2MB.',
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

    public function updatedFeaturedImage()
    {
        $this->validate(['featured_image' => 'image|max:2048']);
        $this->featured_image_preview = $this->featured_image->temporaryUrl();
    }

    public function removeFeaturedImage()
    {
        if ($this->featured_image_file_id) {
            try {
                $imageKitService = new ImageKitService();
                $imageKitService->delete($this->featured_image_file_id);
            } catch (\Exception $e) {
                \Log::error('Failed to delete featured image from ImageKit: ' . $e->getMessage());
            }
        }

        $this->reset(['featured_image', 'featured_image_preview', 'featured_image_file_id', 'featured_image_url']);
    }

    public function updatedGalleryImages()
    {
        $this->validate(['gallery_images.*' => 'image|max:2048']);

        foreach ($this->gallery_images as $index => $image) {
            if (!isset($this->gallery_images_preview[$index])) {
                $this->gallery_images_preview[$index] = $image->temporaryUrl();
            }
        }
    }

    public function removeGalleryImage($index)
    {
        if (isset($this->gallery_images_data[$index]['file_id'])) {
            try {
                $imageKitService = new ImageKitService();
                $imageKitService->delete($this->gallery_images_data[$index]['file_id']);
            } catch (\Exception $e) {
                \Log::error('Failed to delete gallery image from ImageKit: ' . $e->getMessage());
            }
        }

        unset($this->gallery_images[$index]);
        unset($this->gallery_images_preview[$index]);
        unset($this->gallery_images_data[$index]);

        $this->gallery_images = array_values($this->gallery_images);
        $this->gallery_images_preview = array_values($this->gallery_images_preview);
        $this->gallery_images_data = array_values($this->gallery_images_data);
    }

    public function save()
    {
        \Log::info('CreateProduct - Attempting to save product', [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'featured_image' => !empty($this->featured_image),
            'gallery_images_count' => count($this->gallery_images),
            'highlights_count' => count($this->highlights),
            'variantCombinations_count' => count($this->variantCombinations),
        ]);

        $this->validate();

        $this->isSaving = true;
        $this->loadingMessage = 'Creating product, please wait...';

        try {
            // Create the product
            $product = Product::create([
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description ?: null,
                'price' => $this->price ?: null,
                'discount_price' => $this->discount_price ?: null,
                'quantity' => $this->quantity ?: 0,
                'sku' => $this->sku ?: null,
                'category_id' => $this->category_id ?: null,
                'brand_id' => $this->brand_id ?: null,
                'status' => $this->status,
                'is_customizable' => $this->is_customizable,
                'featured' => $this->featured,
                'meta_title' => $this->meta_title ?: null,
                'meta_description' => $this->meta_description ?: null,
            ]);

            \Log::info('CreateProduct - Product created successfully', ['product_id' => $product->id]);

            // Handle featured image upload
            if ($this->featured_image) {
                $this->uploadFeaturedImage($product);
            }

            // Handle gallery images upload
            if (!empty($this->gallery_images)) {
                $this->uploadGalleryImages($product);
            }

            // Save highlights
            if (!empty($this->highlights)) {
                foreach ($this->highlights as $highlight) {
                    ProductHighlist::create([
                        'product_id' => $product->id,
                        'highlights' => $highlight,
                    ]);
                }
                \Log::info('CreateProduct - Highlights saved', ['count' => count($this->highlights)]);
            }

            // Save variant combinations
            if (!empty($this->variantCombinations)) {
                foreach ($this->variantCombinations as $combination) {
                    ProductVariantCombination::create([
                        'product_id' => $product->id,
                        'price' => $combination['price'] ?? null,
                        'stock' => $combination['stock'],
                        'sku' => $combination['sku'] ?? null,
                        'image' => $combination['image'] ?? null,
                        'variant_values' => json_encode($combination['variant_values_data'] ?? []),
                    ]);
                }
                \Log::info('CreateProduct - Variant combinations saved', ['count' => count($this->variantCombinations)]);
            }

            session()->flash('success', 'Product created successfully!');
            return $this->redirect(route('admin.products.index'), navigate: true);

        } catch (\Exception $e) {
            \Log::error('Product creation error: ' . $e->getMessage());
            \Log::error('Product creation trace: ' . $e->getTraceAsString());
            session()->flash('error', 'Error creating product: ' . $e->getMessage());
        } finally {
            $this->isSaving = false;
        }
    }

    private function uploadFeaturedImage($product)
    {
        try {
            $this->isUploadingFeaturedImage = true;
            $imageKitService = new ImageKitService();
            $fileName = 'featured-' . $product->slug . '-' . time() . '.' . $this->featured_image->getClientOriginalExtension();

            $result = $imageKitService->upload(
                $this->featured_image,
                $fileName,
                config('services.imagekit.folders.product')
            );

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $result->url,
                'image_file_id' => $result->fileId,
                'is_primary' => true,
            ]);

            \Log::info('Featured image uploaded successfully', ['file_id' => $result->fileId]);

        } catch (\Exception $e) {
            \Log::error('Failed to upload featured image: ' . $e->getMessage());
            throw new \Exception('Failed to upload featured image');
        } finally {
            $this->isUploadingFeaturedImage = false;
        }
    }

    private function uploadGalleryImages($product)
    {
        if (empty($this->gallery_images)) {
            \Log::info('No gallery images to upload');
            return;
        }

        try {
            $this->isUploadingGalleryImages = true;
            $imageKitService = new ImageKitService();
            \Log::info('Uploading gallery images', ['count' => count($this->gallery_images)]);

            foreach ($this->gallery_images as $index => $image) {
                $fileName = 'gallery-' . $product->slug . '-' . ($index + 1) . '-' . time() . '.' . $image->getClientOriginalExtension();

                \Log::info('Uploading gallery image', ['index' => $index, 'fileName' => $fileName]);

                $result = $imageKitService->upload(
                    $image,
                    $fileName,
                    config('services.imagekit.folders.product')
                );

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $result->url,
                    'image_file_id' => $result->fileId,
                    'is_primary' => false,
                ]);

                \Log::info('Gallery image uploaded successfully', ['file_id' => $result->fileId]);
            }

        } catch (\Exception $e) {
            \Log::error('Failed to upload gallery images: ' . $e->getMessage());
            throw new \Exception('Failed to upload gallery images');
        } finally {
            $this->isUploadingGalleryImages = false;
        }
    }

    // Stepper methods
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

            if ($this->currentStep < 5) {
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

    private function validateCurrentStep()
    {
        switch ($this->currentStep) {
            case 1:
                return $this->validate([
                    'name' => 'required|string|max:255',
                    'slug' => 'required|string|max:255|unique:products,slug',
                    'description' => 'nullable|string|min:10',
                    'category_id' => 'nullable|exists:categories,id',
                    'brand_id' => 'nullable|exists:brands,id',
                    'sku' => 'required|string|max:100',
                ]);

            case 2:
                $rules = [
                    'price' => 'required|numeric|min:0',
                    'discount_price' => 'required|numeric|min:0',
                    'quantity' => 'required|integer|min:0',
                ];
                if ($this->price && is_numeric($this->price) && $this->price > 0) {
                    $rules['discount_price'] .= '|lt:price';
                }
                return $this->validate($rules);

            case 3:
                return $this->validate([
                    'featured_image' => 'required|image|max:2048',
                    'gallery_images.*' => 'nullable|image|max:2048',
                ]);

            case 4:
                return true;

            case 5:
                return $this->validate([
                    'meta_title' => 'nullable|string|max:255',
                    'meta_description' => 'nullable|string|max:500',
                ]);

            default:
                return true;
        }
    }

    // Variant combination handling methods
    public function handleCombinationAdded($combination)
    {
        $this->variantCombinations[] = $combination;
    }

    public function handleCombinationUpdated($combination)
    {
        foreach ($this->variantCombinations as $index => $existingCombination) {
            if ($existingCombination['temp_id'] === $combination['temp_id']) {
                $this->variantCombinations[$index] = $combination;
                break;
            }
        }
    }

    public function handleCombinationDeleted($index)
    {
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