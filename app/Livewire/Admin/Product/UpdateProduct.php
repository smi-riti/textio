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
use Illuminate\Validation\Rule;

use Livewire\Attributes\Layout;
#[Layout('components.layouts.admin')]

class UpdateProduct extends Component
{
    use WithFileUploads;

    public Product $product;
    
    // Stepper
    public $currentStep = 1;
    public $completedSteps = [1, 2, 3, 4]; // All steps completed by default in edit mode
    
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
    public $current_featured_image;
    
    public $gallery_images = [];
    public $gallery_images_preview = [];
    public $existing_gallery_images = [];
    public $images_to_delete = [];

    // Variants
    public $variants = [];

    protected $listeners = [
        'stepChanged' => 'handleStepChange',
        'variantAdded' => 'handleVariantAdded',
        'variantUpdated' => 'handleVariantUpdated', 
        'variantDeleted' => 'handleVariantDeleted'
    ];

    protected function rules()
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')->ignore($this->product->id)
            ],
            'description' => 'nullable|string|min:10',
            'price' => 'required|numeric|min:0', // Price is the original price
            'discount_price' => 'required|numeric|min:0', // Discount price is the selling price
            'quantity' => 'required|integer|min:0',
            'sku' => [
                'required',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($this->product->id)
            ],
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'boolean',
            'is_customizable' => 'boolean',
            'featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'gallery_images.*' => 'nullable|image|max:2048',
            'highlights' => 'nullable|array',
        ];

        // Featured image is only required if there's no existing featured image
        if (!$this->current_featured_image) {
            $rules['featured_image'] = 'required|image|max:2048';
        } else {
            $rules['featured_image'] = 'nullable|image|max:2048';
        }

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
        'featured_image.required' => 'Featured image is required when no existing image is present.',
        'featured_image.image' => 'Featured image must be an image.',
        'featured_image.max' => 'Featured image must not exceed 2MB.',
        'gallery_images.*.image' => 'Uploaded files must be images.',
        'gallery_images.*.max' => 'Each gallery image must not exceed 2MB.',
    ];

    public function mount(Product $product)
    {
        $this->product = $product->load(['category', 'brand', 'images', 'highlights', 'variants']);
        
        // Initialize properties
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->discount_price = $product->discount_price;
        $this->quantity = $product->quantity;
        $this->sku = $product->sku;
        $this->category_id = $product->category_id;
        $this->brand_id = $product->brand_id;
        $this->status = $product->status;
        $this->is_customizable = $product->is_customizable;
        $this->featured = $product->featured;
        $this->meta_title = $product->meta_title;
        $this->meta_description = $product->meta_description;
        
        // Initialize arrays
        $this->highlights = $product->highlights->pluck('highlights')->toArray();
        
        // Initialize variants
        $this->variants = $product->variants->toArray();
        
        // Initialize images
        $featuredImage = $product->images->where('is_primary', true)->first();
        $this->current_featured_image = $featuredImage;
        
        $this->existing_gallery_images = $product->images->where('is_primary', false)->toArray();
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

    public function removeExistingImage($imageId)
    {
        $this->existing_images = array_filter($this->existing_images, function($image) use ($imageId) {
            return $image['id'] != $imageId;
        });
        
        // Reset primary image if it was deleted
        if ($this->primary_image_id == $imageId) {
            $this->primary_image_id = !empty($this->existing_images) ? array_values($this->existing_images)[0]['id'] : null;
        }
    }

    public function removeNewImage($index)
    {
        unset($this->new_images[$index]);
        $this->new_images = array_values($this->new_images);
    }

    public function setPrimaryImage($imageId)
    {
        $this->primary_image_id = $imageId;
    }

    public function updatedFeaturedImage()
    {
        $this->validate(['featured_image' => 'image|max:2048']);
        $this->featured_image_preview = $this->featured_image->temporaryUrl();
    }

    public function removeFeaturedImage()
    {
        $this->reset(['featured_image', 'featured_image_preview']);
    }

    public function removeCurrentFeaturedImage()
    {
        $this->current_featured_image = null;
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
        unset($this->gallery_images[$index]);
        unset($this->gallery_images_preview[$index]);
        
        $this->gallery_images = array_values($this->gallery_images);
        $this->gallery_images_preview = array_values($this->gallery_images_preview);
    }

    public function removeExistingGalleryImage($imageId)
    {
        $this->images_to_delete[] = $imageId;
        $this->existing_gallery_images = array_filter($this->existing_gallery_images, function($image) use ($imageId) {
            return $image['id'] != $imageId;
        });
    }

    public function update()
    {
        \Log::info('UpdateProduct - Starting update process', [
            'featured_image' => !empty($this->featured_image),
            'gallery_images_count' => count($this->gallery_images),
            'images_to_delete' => count($this->images_to_delete),
        ]);

        $this->validate();

        try {
            // Update the product
            $this->product->update([
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

            // Handle images to delete
            if (!empty($this->images_to_delete)) {
                $this->deleteMarkedImages();
            }

            // Handle featured image
            if ($this->featured_image) {
                $this->uploadNewFeaturedImage();
            }

            // Handle new gallery images
            if (!empty($this->gallery_images)) {
                $this->uploadNewGalleryImages();
            }

            // Update highlights - delete old ones and create new ones
            $this->product->highlights()->delete();
            if (!empty($this->highlights)) {
                foreach ($this->highlights as $highlight) {
                    ProductHighlist::create([
                        'product_id' => $this->product->id,
                        'highlights' => $highlight,
                    ]);
                }
            }

            session()->flash('success', 'Product updated successfully!');
            return $this->redirect(route('admin.products.index'), navigate: true);

        } catch (\Exception $e) {
            \Log::error('Product update error: ' . $e->getMessage());
            \Log::error('Product update trace: ' . $e->getTraceAsString());
            session()->flash('error', 'Error updating product: ' . $e->getMessage());
        }
    }

    private function deleteMarkedImages()
    {
        try {
            $imageKitService = new ImageKitService();
            $imagesToDelete = ProductImage::whereIn('id', $this->images_to_delete)->get();
            
            foreach ($imagesToDelete as $image) {
                // Delete from ImageKit if file_id exists
                if ($image->image_file_id) {
                    try {
                        $imageKitService->delete($image->image_file_id);
                    } catch (\Exception $e) {
                        \Log::error('Failed to delete image from ImageKit: ' . $e->getMessage());
                    }
                }
                
                // Delete from database
                $image->delete();
            }
            
            \Log::info('Deleted images', ['count' => count($imagesToDelete)]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to delete marked images: ' . $e->getMessage());
            throw new \Exception('Failed to delete images');
        }
    }

    private function uploadNewFeaturedImage()
    {
        try {
            // Delete current featured image if exists
            if ($this->current_featured_image) {
                // Mark for deletion instead of immediately deleting
                $currentFeaturedImage = ProductImage::find($this->current_featured_image['id']);
                if ($currentFeaturedImage) {
                    $this->images_to_delete[] = $this->current_featured_image['id'];
                }
            }

            $imageKitService = new ImageKitService();
            $fileName = 'featured-' . $this->product->slug . '-' . time() . '.' . $this->featured_image->getClientOriginalExtension();
            
            \Log::info('Uploading new featured image', ['fileName' => $fileName]);
            
            $result = $imageKitService->upload(
                $this->featured_image,
                $fileName,
                config('services.imagekit.folders.product')
            );

            ProductImage::create([
                'product_id' => $this->product->id,
                'image_path' => $result->url,
                'image_file_id' => $result->fileId,
                'is_primary' => true,
            ]);

            \Log::info('Featured image updated successfully', ['file_id' => $result->fileId]);

        } catch (\Exception $e) {
            \Log::error('Failed to upload featured image: ' . $e->getMessage());
            throw new \Exception('Failed to upload featured image');
        }
    }

    private function uploadNewGalleryImages()
    {
        if (empty($this->gallery_images)) {
            \Log::info('No gallery images to upload');
            return;
        }

        try {
            $imageKitService = new ImageKitService();
            \Log::info('Uploading gallery images', ['count' => count($this->gallery_images)]);
            
            foreach ($this->gallery_images as $index => $image) {
                $fileName = 'gallery-' . $this->product->slug . '-' . ($index + 1) . '-' . time() . '.' . $image->getClientOriginalExtension();
                
                \Log::info('Uploading gallery image', ['index' => $index, 'fileName' => $fileName]);
                
                $result = $imageKitService->upload(
                    $image,
                    $fileName,
                    config('services.imagekit.folders.product')
                );

                ProductImage::create([
                    'product_id' => $this->product->id,
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
        if ($this->currentStep < 5) {
            $this->currentStep++;
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
        $this->currentStep = $step;
    }

    // Variant handling methods
    public function handleVariantAdded($variant)
    {
        $this->variants[] = $variant;
    }

    public function handleVariantUpdated($variant)
    {
        // Find and update variant in array
        if (isset($variant['id'])) {
            // Existing variant - update in database is handled by ProductVariants component
            return;
        }
        
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

        return view('livewire.admin.product.update-product', [
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }
}
