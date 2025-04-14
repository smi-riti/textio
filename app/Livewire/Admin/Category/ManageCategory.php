<?php

namespace App\Livewire\Admin\Category;

use App\Models\Category;
use Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

use Str;

class ManageCategory extends Component
{
    use WithFileUploads;

    // Properties for form
    public $title = '';
    public $parent_category_id = null;
    public $image;
    public $description = '';
    public $is_active = true;
    public $meta_title = '';
    public $meta_description = '';
    public $order = 0;
    public $editingCategoryId = null;

    // For file preview
    public $imagePreview = null;

    public $showDeleted = false;

    // Validation rules
    protected function rules()
    {
        return [
            'title' => 'required|string|max:100',
            'parent_category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048', // 2MB max
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string',
        ];
    }



    // Save category (create or update)
    public function saveCategory()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'slug' => Str::slug($this->title), 
            'parent_category_id' => $this->parent_category_id,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,          
        ];

        if ($this->image && $this->image instanceof \Illuminate\Http\UploadedFile) {
            $data['image'] = $this->image->store('categories', 'public');
        }

        if ($this->editingCategoryId) {
            Category::find($this->editingCategoryId)->update($data);
            session()->flash('message', 'Category updated successfully.');
        } else {
            Category::create($data);
            session()->flash('message', 'Category created successfully.');
        }

        $this->resetForm();
    }

    // Edit category
    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        $this->editingCategoryId = $id;
        $this->title = $category->title;
        $this->parent_category_id = $category->parent_category_id;
        $this->description = $category->description;
        $this->is_active = $category->is_active;
        $this->meta_title = $category->meta_title;
        $this->meta_description = $category->meta_description;
        $this->imagePreview = $category->image;
        $this->image = null;
    }

    // Delete category
    public function deleteCategory($id)
    {
        Category::findOrFail($id)->delete();
        session()->flash('message', 'Category deleted successfully.');
    }

    // Restore deleted category
    public function restoreCategory($id)
    {
        Category::withTrashed()->findOrFail($id)->restore();
        session()->flash('message', 'Category restored successfully.');
    }

    // Reset form
    public function resetForm()
    {
        $this->reset([
            'title', 'parent_category_id', 'image', 'description',
            'is_active', 'meta_title', 'meta_description',  'editingCategoryId', 'imagePreview'
        ]);
    }

    // Render the component
    public function render()
{
    $query = $this->showDeleted ? Category::withTrashed() : Category::query();
    $categories = $query->with('parent')->get();
    $parentCategories = Category::whereNull('parent_category_id')->where('is_active', true)->get();
    return view('livewire.admin.category.manage-category', [
        'categories' => $categories,
        'parentCategories' => $parentCategories,
    ]);
}
}
