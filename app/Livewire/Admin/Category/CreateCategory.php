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

    #[Validate('nullable|image|max:2048')]
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
            } catch (\Exception $e) {
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

    \Log::debug('Validation passed, attempting to create category');
    \Log::debug('Data:', [
        'title' => $this->title,
        'slug' => Str::slug($this->title),
        'parent_category_id' => $this->parent_category_id,
        'description' => $this->description,
        'is_active' => $this->is_active,
        'meta_title' => $this->meta_title,
        'meta_description' => $this->meta_description,
    ]);

    try {
        $category = Category::create([
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'parent_category_id' => $this->parent_category_id,
            'image' => null,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
        ]);

        \Log::debug('Category created with ID: ' . $category->id);
        
        session()->flash('message', 'Category created successfully!');
        return redirect()->route('categories.index');
        
    } catch (\Exception $e) {
        \Log::error('Category creation failed: ' . $e->getMessage());
        session()->flash('error', 'Error: ' . $e->getMessage());
    }
}

    public function render()
    {
        return view('livewire.admin.category.create-category');
    }
}
