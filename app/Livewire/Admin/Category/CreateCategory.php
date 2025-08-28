<?php

namespace App\Livewire\Admin\Category;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Category;
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

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'parent_category_id' => $this->parent_category_id,
                'title' => $this->title,
                'slug' => $this->slug,
                'description' => $this->description,
                'is_active' => $this->is_active,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
            ];

            if ($this->image && $this->image instanceof \Illuminate\Http\UploadedFile) {
                $data['image'] = $this->image->store('categories', 'public');
            }

            Category::create($data);
            session()->flash('message', 'Category created successfully.');

            return redirect()->route('categories.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create category. ' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.admin.category.create-category');
    }
}
