<?php

namespace App\Livewire\Admin\Category;

use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
#[Layout('components.layouts.admin')]
class ListCategory extends Component
{
    use WithPagination;

    public $showDeleted = false;
    public $search = '';
    public $selectedCategory;

    #[Url]
    public $tab = 'active';

    protected $listeners = ['deleteCategory' => 'delete', 'restoreCategory' => 'restore'];

    public function toggleStatus($id)
    {
        $category = Category::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Status updated successfully.'
        ]);
    }

    public function confirmDelete($id)
    {
        $category = Category::withCount(['products', 'children'])->find($id);
        
        if (!$category) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Category not found.'
            ]);
            return;
        }

        if ($category->children_count > 0) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Cannot delete category as it has sub-categories. Please delete sub-categories first.'
            ]);
            return;
        }

        if ($category->products_count > 0) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Cannot delete category as it has associated products.'
            ]);
            return;
        }

        $this->selectedCategory = $id;
        $this->dispatch('openConfirmation', 
            'Delete Category', 
            'Are you sure you want to delete this category? All associated data will be moved to trash.',
            'Delete',
            'deleteCategory'
        );
    }

    public function confirmRestore($id)
    {
        $this->selectedCategory = $id;
        $this->dispatch('openConfirmation', 
            'Restore Category', 
            'Are you sure you want to restore this category?',
            'Restore',
            'restoreCategory'
        );
    }

    public function delete()
    {
        $category = Category::withCount(['products', 'children'])->find($this->selectedCategory);
        
        if (!$category) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Category not found.'
            ]);
            return;
        }

        if ($category->children_count > 0 || $category->products_count > 0) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Cannot delete category as it has associated items.'
            ]);
            return;
        }

        $category->delete();
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Category moved to trash successfully.'
        ]);
    }

    public function restore()
    {
        Category::withTrashed()->find($this->selectedCategory)->restore();
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Category restored successfully.'
        ]);
    }

    public function setTab($tab)
    {
        $this->tab = $tab;
    }

    public function render()
    {
        $query = Category::query()
            ->select('id', 'title', 'slug', 'parent_category_id', 'is_active', 'image', 'deleted_at')
            ->with('parent:id,title')
            ->withCount(['products', 'children']);

        // Apply trash filter first
        if ($this->tab === 'trash') {
            $query->onlyTrashed();
        } else {
            $query->whereNull('deleted_at');
        }

        // Then apply search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('slug', 'like', '%' . $this->search . '%');
            });
        }

        $categories = $query->latest()->paginate(10);

        return view('livewire.admin.category.list-category', [
            'categories' => $categories
        ]);
    }
}

