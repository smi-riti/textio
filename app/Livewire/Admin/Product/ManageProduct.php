<?php
namespace App\Livewire\Admin\Product;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Auth;
use Str;

class ManageProduct extends Component
{
    use WithFileUploads;

    // Properties for product creation
    public $name = '';
    public $category_id = '';
    public $brand_id = '';
    public $unit_price = 0;
    public $description = '';
    public $thumbnail_img; // Keep for backward compatibility or remove if not needed
    public $images = []; // Array for multiple images
    public $current_stock = 0;
    public $published = true;
    public $discount = 0;
    public $discount_type = 'flat';
    public $tags = '';
    public $meta_title = '';
    public $meta_description = '';
    public $shipping_cost = 0;
    public $min_qty = 1;
    public $editingProductId = null;
    public $thumbnailPreview = null;
    public $imagePreviews = [];
    public $showDeleted = false;
    public $categories = [];
    public $brands = [];

    // Validation rules
    protected function rules()
    {
        return [
            'name' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'thumbnail_img' => 'nullable|image|max:2048', // Optional single thumbnail
            'images.*' => 'nullable|image|max:2048', // Validate each image
            'current_stock' => 'required|integer|min:0',
            'published' => 'boolean',
            'discount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:flat,percent',
            'tags' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'shipping_cost' => 'nullable|numeric|min:0',
            'min_qty' => 'required|integer|min:1',
        ];
    }

    // Real-time validation
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    // Update image previews when images are uploaded
    public function updatedImages()
    {
        $this->imagePreviews = [];
        foreach ($this->images as $image) {
            if ($image) {
                $this->imagePreviews[] = $image->temporaryUrl();
            }
        }
    }

    public function mount()
    {
        $this->categories = Category::where('is_active', true)->get();
        $this->brands = Brand::where('is_active', true)->get();
    }

    // Create or update product
    public function saveProduct()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->generateUniqueSlug(),
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'unit_price' => $this->unit_price,
            'description' => $this->description,
            'current_stock' => $this->current_stock,
            'published' => $this->published,
            'discount' => $this->discount,
            'discount_type' => $this->discount_type,
            'tags' => $this->tags,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'shipping_cost' => $this->shipping_cost,
            'min_qty' => $this->min_qty,
            'user_id' => Auth::id(),
        ];

        \DB::beginTransaction();
        try {
            if ($this->editingProductId) {
                $product = Product::findOrFail($this->editingProductId);
                $product->update($data);
            } else {
                $product = Product::create($data);
            }

            // Handle multiple image uploads
            if ($this->images) {
                // Optionally, delete old images when updating
                if ($this->editingProductId) {
                    $product->images()->delete(); // Or keep old images and append new ones
                    \Storage::disk('public')->deleteDirectory('image/product/' . $product->id);
                }

                foreach ($this->images as $image) {
                    if ($image) {
                        $imageName = 'P' . $product->id . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                        $image->storeAs('public/image/product/' . $product->id, $imageName, 'public');
                        $product->images()->create([
                            'image_path' => $imageName,
                        ]);
                    }
                }
            }

            // Handle thumbnail_img (optional)
            if ($this->thumbnail_img) {
                $thumbnailName = 'T' . $product->id . '_' . time() . '.' . $this->thumbnail_img->getClientOriginalExtension();
                $this->thumbnail_img->storeAs('public/image/product/' . $product->id, $thumbnailName, 'public');
                $data['thumbnail_img'] = $thumbnailName;
                $product->update(['thumbnail_img' => $thumbnailName]);
            }

            \DB::commit();
            session()->flash('message', $this->editingProductId ? 'Product updated successfully.' : 'Product created successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            session()->flash('error', 'An error occurred while saving the product.');
            return;
        }

        $this->resetForm();
    }

    // Edit product
    public function editProduct($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $this->editingProductId = $id;
        $this->name = $product->name;
        $this->category_id = $product->category_id;
        $this->brand_id = $product->brand_id;
        $this->unit_price = $product->unit_price;
        $this->description = $product->description;
        $this->current_stock = $product->current_stock;
        $this->published = $product->published;
        $this->discount = $product->discount;
        $this->discount_type = $product->discount_type;
        $this->tags = $product->tags;
        $this->meta_title = $product->meta_title;
        $this->meta_description = $product->meta_description;
        $this->shipping_cost = $product->shipping_cost;
        $this->min_qty = $product->min_qty;
        $this->thumbnailPreview = $product->thumbnail_img ? \Storage::url('image/product/' . $product->id . '/' . $product->thumbnail_img) : null;
        $this->imagePreviews = $product->images->map(fn($img) => \Storage::url('image/product/' . $product->id . '/' . $img->image_path))->toArray();
        $this->thumbnail_img = null;
        $this->images = [];
    }

    // Delete product
    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        session()->flash('message', 'Product deleted successfully.');
    }

    // Restore deleted product
    public function restoreProduct($id)
    {
        Product::withTrashed()->findOrFail($id)->restore();
        session()->flash('message', 'Product restored successfully.');
    }

    // Reset form fields
    public function resetForm()
    {
        $this->reset([
            'name', 'category_id', 'brand_id', 'unit_price', 'description',
            'thumbnail_img', 'images', 'current_stock', 'published', 'discount',
            'discount_type', 'tags', 'meta_title', 'meta_description', 'shipping_cost',
            'min_qty', 'editingProductId', 'thumbnailPreview', 'imagePreviews',
        ]);
    }

    // Generate unique slug
    private function generateUniqueSlug(): string
    {
        $baseSlug = Str::slug($this->name);
        $slug = $baseSlug;
        $counter = 1;
        while (Product::where('slug', $slug)->where('id', '!=', $this->editingProductId)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }
        return $slug;
    }

    // Render the component
    public function render()
    {
        $query = $this->showDeleted ? Product::withTrashed() : Product::query();
        $products = $query->with(['category', 'brand', 'images'])->latest()->paginate(10);

        return view('livewire.admin.product.manage-product', [
            'products' => $products,
            'categories' => $this->categories,
            'brands' => $this->brands,
        ]);
    }
}