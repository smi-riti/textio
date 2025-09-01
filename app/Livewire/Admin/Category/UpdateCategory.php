<?php

namespace App\Livewire\Admin\Category;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Validate;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Services\ImageKitService;

#[Layout('components.layouts.admin')]
class UpdateCategory extends Component
{
    use WithFileUploads;
    
    public $category;
    public $newImage;

    // Add ImageKit properties
    public $currentImageUrl;
    public $currentFileId;

    #[Validate('required|string|max:100')]
    public $title = '';

    public $slug = '';

    #[Validate('nullable|image|max:2048')]
    public $image;

    #[Validate('nullable|exists:categories,id|not_in:{{ $this->category->id }}')]
    public $parent_category_id;

    #[Validate('nullable|string')]
    public $description = '';

    #[Validate('boolean')]
    public $is_active = true;

    #[Validate('nullable|string|max:200')]
    public $meta_title = '';

    #[Validate('nullable|string')]
    public $meta_description = '';

    public $parentCategories = [];

    
    public function mount($slug)
    {
        $this->category = Category::where('slug', $slug)->firstOrFail();
        $this->title = $this->category->title;
        $this->slug = $this->category->slug;
        $this->parent_category_id = $this->category->parent_category_id;
        $this->description = $this->category->description;
        $this->is_active = $this->category->is_active;
        $this->meta_title = $this->category->meta_title;
        $this->meta_description = $this->category->meta_description;
        
        // Set current image properties for ImageKit
        $this->currentImageUrl = $this->category->image;

        $this->parentCategories = Category::select('id', 'title')
            ->where('id', '!=', $this->category->id)
            ->whereNull('parent_category_id')
            ->get();
    }

    public function updatedTitle($value)
    {
        $this->slug = Str::slug($value);
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'title' => $this->title,
                'slug' => $this->slug,
                'parent_category_id' => $this->parent_category_id,
                'description' => $this->description,
                'is_active' => $this->is_active,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
            ];

            if ($this->newImage && $this->newImage instanceof \Illuminate\Http\UploadedFile) {
                $imageKitService = new ImageKitService();
                \Log::info('UpdateCategory - Attempting to upload new image to ImageKit', [
                    'category_id' => $this->category->id,
                    'image_type' => get_class($this->newImage),
                    'image_size' => $this->newImage->getSize(),
                ]);

                try {
                    $fileName = 'category-' . Str::slug($this->title) . '-' . time() . '.' . $this->newImage->getClientOriginalExtension();
                    $uploadResult = $imageKitService->upload(
                        $this->newImage, 
                        $fileName,
                        config('services.imagekit.folders.category', 'textio/category')
                    );
                    
                    if ($uploadResult && isset($uploadResult->url)) {
                        $data['image'] = $uploadResult->url;
                        \Log::info('UpdateCategory - New image uploaded successfully', [
                            'url' => $uploadResult->url,
                            'file_id' => $uploadResult->fileId ?? null
                        ]);
                        
                        // Delete old image from ImageKit if exists
                        if ($this->currentImageUrl && $this->currentFileId) {
                            try {
                                $imageKitService->delete($this->currentFileId);
                                \Log::info('UpdateCategory - Old image deleted from ImageKit', ['file_id' => $this->currentFileId]);
                            } catch (\Exception $e) {
                                \Log::warning('UpdateCategory - Could not delete old image: ' . $e->getMessage());
                            }
                        }
                    } else {
                        throw new \Exception('Invalid upload response from ImageKit');
                    }
                } catch (\Exception $e) {
                    \Log::error('UpdateCategory - Image upload failed: ' . $e->getMessage());
                    session()->flash('error', 'Image upload failed: ' . $e->getMessage());
                    return;
                }
            }

            $this->category->update($data);
            \Log::info('UpdateCategory - Category updated successfully', ['category_id' => $this->category->id]);
            
            session()->flash('message', 'Category updated successfully.');
            return redirect()->route('admin.categories.index');
            
        } catch (\Exception $e) {
            \Log::error('UpdateCategory - Category update failed: ' . $e->getMessage());
            \Log::error('UpdateCategory - Exception trace: ' . $e->getTraceAsString());
            session()->flash('error', 'Failed to update category: ' . $e->getMessage());
        }
    }

    public function deleteImage()
    {
        if ($this->currentImageUrl) {
            try {
                // Try to delete from ImageKit if we have a file ID
                if ($this->currentFileId) {
                    $imageKitService = new ImageKitService();
                    $imageKitService->delete($this->currentFileId);
                    \Log::info('UpdateCategory - Image deleted from ImageKit', ['file_id' => $this->currentFileId]);
                }
            } catch (\Exception $e) {
                \Log::warning('UpdateCategory - Could not delete image from ImageKit: ' . $e->getMessage());
            }
            
            // Update database
            $this->category->update(['image' => null]);
            $this->category->refresh();
            $this->currentImageUrl = null;
            $this->currentFileId = null;
            
            session()->flash('message', 'Image deleted successfully.');
        }
    }

    public function render()
    {
        return view('livewire.admin.category.update-category');
    }
}
