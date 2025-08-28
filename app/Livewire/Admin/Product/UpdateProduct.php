<?php

namespace App\Livewire\Admin\Product;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;
use App\Models\ProductHighlist;
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
    public $existing_images = [];
    public $new_images = [];
    public $primary_image_id = null;

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
            'quantity' => 'nullable|integer|min:0',
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
            'new_images.*' => 'nullable|image|max:2048',
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
        'quantity.integer' => 'Stock quantity must be a valid number.',
        'category_id.exists' => 'Please select a valid category.',
        'brand_id.exists' => 'Please select a valid brand.',
        'new_images.*.image' => 'Uploaded files must be images.',
        'new_images.*.max' => 'Each image must not exceed 2MB.',
    ];

    public function mount(Product $product)
    {
        $this->product = $product->load(['category', 'brand', 'images', 'highlights']);
        
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
        $this->existing_images = $product->images->toArray();
        
        // Set primary image
        $primaryImage = $product->images->where('is_primary', true)->first();
        $this->primary_image_id = $primaryImage ? $primaryImage->id : ($product->images->first()?->id ?? null);
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

    public function update()
    {
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

            // Handle existing images removal - delete from storage and database
            $existingImageIds = array_column($this->existing_images, 'id');
            $imagesToDelete = $this->product->images()->whereNotIn('id', $existingImageIds)->get();
            
            foreach ($imagesToDelete as $image) {
                // Delete physical file from storage
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
                // Delete database record
                $image->delete();
            }

            // Add new images using ProductImage model
            if (!empty($this->new_images)) {
                foreach ($this->new_images as $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $this->product->id,
                        'image_path' => $path,
                        'is_primary' => false, // New images are not primary by default
                        'image_file_id' => null, // Can be used for future file management
                    ]);
                }
            }

            // Update primary image - ensure only one image is primary
            if ($this->primary_image_id) {
                // First, set all images to not primary
                $this->product->images()->update(['is_primary' => false]);
                // Then set the selected image as primary
                $this->product->images()->where('id', $this->primary_image_id)->update(['is_primary' => true]);
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
            return $this->redirect(route('products.index'), navigate: true);

        } catch (\Exception $e) {
            \Log::error('Product update error: ' . $e->getMessage());
            \Log::error('Product update trace: ' . $e->getTraceAsString());
            session()->flash('error', 'Error updating product: ' . $e->getMessage());
        }
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
