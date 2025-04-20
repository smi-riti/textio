<?php

namespace App\Livewire\Admin\Product;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.admin')]

class ManageProduct extends Component
{
    use WithFileUploads;

    public $productId;
    public $name;
    public $description;
    public $price;
    public $discount_price;
    public $quantity;
    public $sku;
    public $category_id;
    public $brand_id;
    public $status = false;
    public $showModal = false;
    public $isEditMode = false;
    public $images = [];
    public $showImageModal = false;
    public $selectedProductId;
    public $showVariantModal = false;
    public $variantId;
    public $variant_type;
    public $variant_name;
    public $variant_price;
    public $variant_stock;
    public $variant_sku;
    public $variant_image;
    public $isVariantEditMode = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'nullable|numeric|min:0',
        'discount_price' => 'nullable|numeric|min:0',
        'quantity' => 'nullable|integer|min:0',
        'sku' => 'nullable|string|max:255',
        'category_id' => 'nullable|exists:categories,id',
        'brand_id' => 'nullable|exists:brands,id',
        'status' => 'boolean',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'variant_type' => 'required|string|max:255',
        'variant_name' => 'required|string|max:255',
        'variant_price' => 'nullable|numeric|min:0',
        'variant_stock' => 'nullable|integer|min:0',
        'variant_sku' => 'nullable|string|max:255',
        'variant_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ];

    public function render()
    {
        $products = Product::with(['category', 'brand', 'images', 'variants'])->latest()->get();
        return view('livewire.admin.product.manage-product', compact('products'));
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isEditMode = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->productId = $product->id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->discount_price = $product->discount_price;
        $this->quantity = $product->quantity;
        $this->sku = $product->sku;
        $this->category_id = $product->category_id;
        $this->brand_id = $product->brand_id;
        $this->status = $product->status;
        $this->images = [];
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'sku' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'quantity' => $this->quantity,
            'sku' => $this->sku,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'status' => $this->status,
        ];

        if ($this->isEditMode) {
            $product = Product::find($this->productId);
            $product->update($data);
            session()->flash('message', 'Product updated successfully.');
        } else {
            $product = Product::create($data);
            session()->flash('message', 'Product created successfully.');
        }

        // Handle image uploads
        if (!empty($this->images)) {
            foreach ($this->images as $image) {
                $path = $image->store('images', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                ]);
            }
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();
        session()->flash('message', 'Product deleted successfully.');
    }

    public function openImageModal($productId)
    {
        $this->selectedProductId = $productId;
        $this->images = [];
        $this->showImageModal = true;
    }

    public function saveImages()
    {
        $this->validate([
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        foreach ($this->images as $image) {
            $path = $image->store('images', 'public');
            ProductImage::create([
                'product_id' => $this->selectedProductId,
                'image_path' => $path,
            ]);
        }

        $this->images = [];
        $this->showImageModal = false;
        session()->flash('message', 'Images uploaded successfully.');
    }

    public function deleteImage($imageId)
    {
        $image = ProductImage::findOrFail($imageId);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        session()->flash('message', 'Image deleted successfully.');
    }

    public function openVariantModal($productId)
    {
        $this->selectedProductId = $productId;
        $this->resetVariantForm();
        $this->isVariantEditMode = false;
        $this->showVariantModal = true;
    }

    public function editVariant($variantId)
    {
        $variant = ProductVariant::findOrFail($variantId);
        $this->variantId = $variant->id;
        $this->selectedProductId = $variant->product_id;
        $this->variant_type = $variant->variant_type;
        $this->variant_name = $variant->variant_name;
        $this->variant_price = $variant->price;
        $this->variant_stock = $variant->stock;
        $this->variant_sku = $variant->sku;
        $this->variant_image = null;
        $this->isVariantEditMode = true;
        $this->showVariantModal = true;
    }

    public function saveVariant()
    {
        $this->validate([
            'variant_type' => 'required|string|max:255',
            'variant_name' => 'required|string|max:255',
            'variant_price' => 'nullable|numeric|min:0',
            'variant_stock' => 'nullable|integer|min:0',
            'variant_sku' => 'nullable|string|max:255',
            'variant_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'product_id' => $this->selectedProductId,
            'variant_type' => $this->variant_type,
            'variant_name' => $this->variant_name,
            'price' => $this->variant_price,
            'stock' => $this->variant_stock,
            'sku' => $this->variant_sku,
        ];

        if ($this->variant_image) {
            $data['variant_image'] = $this->variant_image->store('variant_images', 'public');
        }

        if ($this->isVariantEditMode) {
            $variant = ProductVariant::find($this->variantId);
            if ($this->variant_image && $variant->variant_image) {
                Storage::disk('public')->delete($variant->variant_image);
            }
            $variant->update($data);
            session()->flash('message', 'Variant updated successfully.');
        } else {
            ProductVariant::create($data);
            session()->flash('message', 'Variant created successfully.');
        }

        $this->showVariantModal = false;
        $this->resetVariantForm();
    }

    public function deleteVariant($variantId)
    {
        $variant = ProductVariant::findOrFail($variantId);
        if ($variant->variant_image) {
            Storage::disk('public')->delete($variant->variant_image);
        }
        $variant->delete();
        session()->flash('message', 'Variant deleted successfully.');
    }

    public function resetForm()
    {
        $this->productId = null;
        $this->name = '';
        $this->description = '';
        $this->price = null;
        $this->discount_price = null;
        $this->quantity = 0;
        $this->sku = '';
        $this->category_id = null;
        $this->brand_id = null;
        $this->status = false;
        $this->images = [];
    }

    public function resetVariantForm()
    {
        $this->variantId = null;
        $this->variant_type = '';
        $this->variant_name = '';
        $this->variant_price = null;
        $this->variant_stock = 0;
        $this->variant_sku = '';
        $this->variant_image = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showImageModal = false;
        $this->showVariantModal = false;
        $this->resetForm();
        $this->resetVariantForm();
    }
}