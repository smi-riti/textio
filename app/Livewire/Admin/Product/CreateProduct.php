<?php

namespace App\Livewire\Admin\Product;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;
use App\Models\ProductHighlist;
use App\Models\ProductVariant;
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

    public $gallery_images = [];
    public $gallery_images_preview = [];
    public $gallery_images_data = []; // Store ImageKit data for gallery images

    // Variants
    public $variants = [];

    // Loading States
    public $isLoading = false;
    public $loadingMessage = '';
    public $isSaving = false;

    protected $listeners = [
        'stepChanged' => 'handleStepChange',
        'variantAdded' => 'handleVariantAdded',
        'variantUpdated' => 'handleVariantUpdated', 
        'variantDeleted' => 'handleVariantDeleted'
    ];

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string|min:10',
            'price' => 'required|numeric|min:0', // Price is the original price
            'discount_price' => 'required|numeric|min:0', // Discount price is the selling price
            'quantity' => 'required|integer|min:0',
            'sku' => 'required|string|max:100|unique:products,sku', // SKU is required
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

        // Add lt:price rule - discount price (selling price) should be less than original price
        if ($this->price && is_numeric($this->price) && $this->price > 0) {
            $rules['discount_price'] .= '|lt:price';
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'Product name is required.',
        'name.unique' => 'This product name already exists.',
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
        // Remove from ImageKit if exists
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
        // Remove from ImageKit if exists
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

    public function removeImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
        
        // Adjust primary image index if needed
        if ($this->primary_image_index >= count($this->images)) {
            $this->primary_image_index = max(0, count($this->images) - 1);
        }
    }

    public function setPrimaryImage($index)
    {
        $this->primary_image_index = $index;
    }

    public function save()
    {
        // Debug: Log the data being validated
        \Log::info('CreateProduct - Attempting to save product', [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'featured_image' => !empty($this->featured_image),
            'gallery_images_count' => count($this->gallery_images),
            'highlights_count' => count($this->highlights)
        ]);

        $this->validate();

        $this->isSaving = true; // Set saving state
        $this->loadingMessage = 'Creating product, please wait...'; // Set loading message

        try {
            // Create the product
            $product = Product::create([
                'name' => $this->name,
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

            // Save highlights using ProductHighlist model
            if (!empty($this->highlights)) {
                foreach ($this->highlights as $highlight) {
                    ProductHighlist::create([
                        'product_id' => $product->id,
                        'highlights' => $highlight,
                    ]);
                }
                \Log::info('CreateProduct - Highlights saved', ['count' => count($this->highlights)]);
            }

            // Save variants
            if (!empty($this->variants)) {
                foreach ($this->variants as $variant) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'variant_type' => $variant['variant_type'],
                        'variant_name' => $variant['variant_name'],
                        'price' => $variant['price'] ?? null,
                        'stock' => $variant['stock'],
                        'sku' => $variant['sku'] ?? null,
                        'variant_image' => $variant['variant_image'] ?? null,
                    ]);
                }
                \Log::info('CreateProduct - Variants saved', ['count' => count($this->variants)]);
            }

            session()->flash('success', 'Product created successfully!');
            return $this->redirect(route('admin.products.index'), navigate: true);

        } catch (\Exception $e) {
            \Log::error('Product creation error: ' . $e->getMessage());
            \Log::error('Product creation trace: ' . $e->getTraceAsString());
            session()->flash('error', 'Error creating product: ' . $e->getMessage());
        } finally {
            $this->isSaving = false; // Reset saving state
        }
    }

    private function uploadFeaturedImage($product)
    {
        try {
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
        }
    }

    private function uploadGalleryImages($product)
    {
        if (empty($this->gallery_images)) {
            \Log::info('No gallery images to upload');
            return;
        }

        try {
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
        }
    }

    // Stepper methods
    public function handleStepChange($step)
    {
        $this->currentStep = $step;
    }

    public function nextStep()
    {
        // Validate current step before proceeding
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
            case 1: // Basic Info
                return $this->validate([
                    'name' => 'required|string|max:255',
                    'description' => 'nullable|string|min:10',
                    'category_id' => 'nullable|exists:categories,id',
                    'brand_id' => 'nullable|exists:brands,id',
                    'sku' => 'required|string|max:100',
                ]);
                
            case 2: // Pricing
                $rules = [
                    'price' => 'required|numeric|min:0',
                    'discount_price' => 'required|numeric|min:0',
                    'quantity' => 'required|integer|min:0',
                ];
                if ($this->price && is_numeric($this->price) && $this->price > 0) {
                    $rules['discount_price'] .= '|lt:price';
                }
                return $this->validate($rules);
                
            case 3: // Images
                return $this->validate([
                    'featured_image' => 'required|image|max:2048',
                    'gallery_images.*' => 'nullable|image|max:2048',
                ]);
                
            case 4: // Variants - optional
                return true;
                
            case 5: // SEO & Review
                return $this->validate([
                    'meta_title' => 'nullable|string|max:255',
                    'meta_description' => 'nullable|string|max:500',
                ]);
                
            default:
                return true;
        }
    }

    // Variant handling methods
    public function handleVariantAdded($variant)
    {
        $this->variants[] = $variant;
    }

    public function handleVariantUpdated($variant)
    {
        // Find and update variant in array
        foreach ($this->variants as $index => $existingVariant) {
            if ($existingVariant['temp_id'] === $variant['temp_id']) {
                $this->variants[$index] = $variant;
                break;
            }
        }
    }

    public function handleVariantDeleted($index)
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
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
