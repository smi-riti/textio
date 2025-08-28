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
use Livewire\Attributes\Layout;
#[Layout('components.layouts.admin')]
class CreateProduct extends Component
{
    use WithFileUploads;

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
    public $images = [];
    public $primary_image_index = 0;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string|min:10',
            'price' => 'required|numeric|min:0', // Price is the original price
            'discount_price' => 'required|numeric|min:0', // Discount price is the selling price
            'quantity' => 'nullable|integer|min:0',
            'sku' => 'required|string|max:100|unique:products,sku', // SKU is required
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'boolean',
            'is_customizable' => 'boolean',
            'featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'images.*' => 'nullable|image|max:2048',
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
        'images.*.image' => 'Uploaded files must be images.',
        'images.*.max' => 'Each image must not exceed 2MB.',
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
            'images_count' => count($this->images),
            'highlights_count' => count($this->highlights)
        ]);

        $this->validate();

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

            // Save images using ProductImage model
            if (!empty($this->images)) {
                foreach ($this->images as $index => $image) {
                    $path = $image->store('products', 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => $index === $this->primary_image_index,
                        'image_file_id' => null, // Can be used for future file management
                    ]);
                }
                \Log::info('CreateProduct - Images saved', ['count' => count($this->images)]);
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

            session()->flash('success', 'Product created successfully!');
            return $this->redirect(route('products.index'), navigate: true);

        } catch (\Exception $e) {
            \Log::error('Product creation error: ' . $e->getMessage());
            \Log::error('Product creation trace: ' . $e->getTraceAsString());
            session()->flash('error', 'Error creating product: ' . $e->getMessage());
        }
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
