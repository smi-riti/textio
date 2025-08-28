<?php

namespace App\Livewire\Admin\Category;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Validate;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class UpdateCategory extends Component
{
    use WithFileUploads;
    
    public $category;
    public $newImage;  // Add this line

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
                $data['image'] = $this->newImage->store('categories', 'public');
                // Delete old image if exists
                if ($this->category->image) {
                    \Storage::disk('public')->delete($this->category->image);
                }
            }

            $this->category->update($data);
            session()->flash('message', 'Category updated successfully.');

            return redirect()->route('categories.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update category. ' . $e->getMessage());
        }
    }

    public function deleteImage()
    {
        if ($this->category->image) {
            \Storage::disk('public')->delete($this->category->image);
            $this->category->update(['image' => null]);
            $this->category->refresh();
        }
    }

    public function render()
    {
        return view('livewire.admin.category.update-category');
    }
}
