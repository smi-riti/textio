<?php

namespace App\Livewire\Admin\Category;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Category;
use App\Services\ImageKitService;
use Livewire\Attributes\Validate;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class CreateCategory extends Component
{
    use WithFileUploads;
    #[Validate('required|string|max:100')]
    public $title = '';

    public $slug = '';

    #[Validate('required|image|max:2048')]
    public $image;
    #[Validate('nullable|exists:categories,id')]
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

  // For file preview
    public $imagePreview = null;
    
    // Add these properties for ImageKit management
    public $fileId = null;
    public $imageUrl = null;
    public $imagePath = null;

    public function mount()
    {
        $this->parentCategories = Category::select('id', 'title')
            ->whereNull('parent_category_id')
            ->get();
    }

    public function updatedTitle($value)
    {
        $this->slug = Str::slug($value);
    }

    public function removeImage()
    {
        if ($this->fileId) {
            try {
                $imageKitService = new ImageKitService();
                $imageKitService->delete($this->fileId);
                \Log::info('CreateCategory - Image deleted from ImageKit', ['file_id' => $this->fileId]);
            } catch (\Exception $e) {
                \Log::error('CreateCategory - Error deleting image: ' . $e->getMessage());
                session()->flash('error', 'Error deleting image: ' . $e->getMessage());
            }
        }
        
        // Reset image properties
        $this->reset('image', 'fileId', 'imageUrl', 'imagePath');
        $this->imagePreview = null;
    }


    public function save()
    {
        $this->validate();

        try {
            $categoryImageUrl = null;

            if ($this->image) {
                $imageKitService = new ImageKitService();
                \Log::info('CreateCategory - Attempting to upload image to ImageKit', [
                    'image_type' => get_class($this->image),
                    'image_size' => $this->image->getSize(),
                    'image_mime' => $this->image->getClientOriginalExtension(),
                ]);

                try {
                    $fileName = 'category-' . Str::slug($this->title) . '-' . time() . '.' . $this->image->getClientOriginalExtension();
                    $uploadResult = $imageKitService->upload(
                        $this->image, 
                        $fileName,
                        config('services.imagekit.folders.category', 'textio/category')
                    );
                    
                    if ($uploadResult && isset($uploadResult->url)) {
                        $categoryImageUrl = $uploadResult->url;
                        $this->fileId = $uploadResult->fileId ?? null;
                        \Log::info('CreateCategory - Image uploaded successfully', [
                            'url' => $categoryImageUrl,
                            'file_id' => $this->fileId
                        ]);
                    } else {
                        throw new \Exception('Invalid upload response from ImageKit');
                    }
                } catch (\Exception $e) {
                    \Log::error('CreateCategory - Image upload failed: ' . $e->getMessage());
                    session()->flash('error', 'Image upload failed: ' . $e->getMessage());
                    return;
                }
            }

            \Log::info('CreateCategory - Creating category', [
                'title' => $this->title,
                'slug' => Str::slug($this->title),
                'image_url' => $categoryImageUrl
            ]);

            $category = Category::create([
                'title' => $this->title,
                'slug' => Str::slug($this->title),
                'parent_category_id' => $this->parent_category_id,
                'image' => $categoryImageUrl,
                'description' => $this->description,
                'is_active' => $this->is_active,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
            ]);

            \Log::info('CreateCategory - Category created successfully', ['category_id' => $category->id]);
            
            session()->flash('message', 'Category created successfully!');
            return redirect()->route('admin.categories.index');
            
        } catch (\Exception $e) {
            \Log::error('CreateCategory - Category creation failed: ' . $e->getMessage());
            \Log::error('CreateCategory - Exception trace: ' . $e->getTraceAsString());
            session()->flash('error', 'Error creating category: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.category.create-category');
    }
}
