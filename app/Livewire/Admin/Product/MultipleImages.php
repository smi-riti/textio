<?php

namespace App\Livewire\Admin\Product;

use App\Models\Product;
use App\Models\ProductImage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Storage;

class MultipleImages extends Component
{
    use WithFileUploads;

    public $product;
    public $photo; // Single image upload
    public $isEditing = true; // Always in editing mode
    
    public  $mainImage;

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function deleteImage($imageId)
    {
        $image = ProductImage::find($imageId);

        if ($image) {
            Storage::delete('public/image/product/' . $image->image_path);
            $image->delete();
            session()->flash('message', 'Image deleted successfully.');
        }
    }

    // Update product images one by one
    public function update()
    {
        // Validate the uploaded image
        $this->validate([
            'photo' => 'nullable|image|max:1024', // Validate the image
        ]);

        // Ensure photo is not empty
        if ($this->photo) {
            $imageName = "p" . time() . '.' . $this->photo->getClientOriginalExtension();
            $this->photo->storeAs('public/image/product', $imageName);

            ProductImage::create([
                'product_id' => $this->product->id,
                'image_path' => $imageName,
            ]);

            // Flash success message and reset the photo input
            session()->flash('message', 'Product image uploaded successfully!');
            $this->photo = null; // Clear the input after saving
        }
    }

    public function render()
    {
        return view('livewire.admin.product.multiple-images',[
            'productImages' => $this->product->images
        ]);
    }
}
