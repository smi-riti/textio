<?php

namespace App\Livewire\Admin\Product;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
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
    public $thumbnail_img;
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
    public $showDeleted = false;

    // Validation rules
    protected function rules()
    {
        return [
            'name' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'thumbnail_img' => 'nullable|image|max:2048', // 2MB max
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

    // Fetch categories and brands for dropdowns
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
            'slug' => Str::slug($this->name), 
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

        if ($this->thumbnail_img && $this->thumbnail_img instanceof \Illuminate\Http\UploadedFile) {
            $data['thumbnail_img'] = $this->thumbnail_img->store('products', 'public');
        }

        if ($this->editingProductId) {
            Product::find($this->editingProductId)->update($data);
            session()->flash('message', 'Product updated successfully.');
        } else {
            Product::create($data);
            session()->flash('message', 'Product created successfully.');
        }

        $this->resetForm();
    }

    // Edit product
    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
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
        $this->thumbnailPreview = $product->thumbnail_img;
        $this->thumbnail_img = null;
    }

    // Delete product
    public function deleteProduct($id)
    {
        Product::findOrFail($id)->delete();
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
            'thumbnail_img', 'current_stock', 'published', 'discount', 'discount_type',
            'tags', 'meta_title', 'meta_description', 'shipping_cost', 'min_qty',
            'editingProductId', 'thumbnailPreview'
        ]);
    }

    // Render the component
    public function render()
    {
        $query = $this->showDeleted ? Product::withTrashed() : Product::query();
        $products = $query->with(['category', 'brand'])->latest()->get();

        return view('livewire.admin.product.manage-product', [
            'products' => $products,
            'categories' => $this->categories,
            'brands' => $this->brands,
        ]);
    }
}