<?php

namespace App\Livewire\Admin\Brand;

use App\Models\Brand;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class ManageBrand extends Component
{
    use WithPagination;

    public $showDeleted = false;
    public $search = '';
    public $perPage = 10;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    
    protected $queryString = [
        'search' => ['except' => ''],
        'showDeleted' => ['except' => false],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    protected $listeners = [
        'confirmDelete' => 'confirmDelete',
        'confirmRestore' => 'confirmRestore',
    ];

    #[Layout('components.layouts.admin')]
    public function render()
    {
        $query = Brand::query();

        // Apply search filter
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        // Apply deleted filter
        if ($this->showDeleted) {
            $query->onlyTrashed();
        }

        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        // Get paginated results
        $brands = $query->paginate(10);
        $brands->withPath(request()->url());

        return view('livewire.admin.brand.manage-brand', [
            'brands' => $brands
        ])->layout('layouts.admin');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function showTrash()
    {
        $this->showDeleted = true;
        $this->resetPage();
    }
    
    public function showList()
    {
        $this->showDeleted = false;
        $this->resetPage();
    }

    public function deleteBrand($id)
    {
        $brand = Brand::findOrFail($id);
        $this->dispatch('openConfirmation', 
            'Delete Brand',
            'Are you sure you want to delete "' . $brand->name . '"? This action can be undone later.',
            'Delete',
            'confirmDelete',
            ['id' => $id]
        );
    }

    public function confirmDelete($data)
    {
        try {
            Brand::findOrFail($data['id'])->delete();
            session()->flash('message', 'Brand moved to trash successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete brand: ' . $e->getMessage());
        }
    }

    public function restoreBrand($id)
    {
        $brand = Brand::withTrashed()->findOrFail($id);
        $this->dispatch('openConfirmation',
            'Restore Brand', 
            'Are you sure you want to restore "' . $brand->name . '"?',
            'Restore',
            'confirmRestore',
            ['id' => $id]
        );
    }

    public function confirmRestore($data)
    {
        try {
            Brand::withTrashed()->findOrFail($data['id'])->restore();
            session()->flash('message', 'Brand restored successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to restore brand: ' . $e->getMessage());
        }
    }

    public function permanentDelete($id)
    {
        $brand = Brand::withTrashed()->findOrFail($id);
        $this->dispatch('openConfirmation',
            'Permanently Delete Brand',
            'Are you sure you want to permanently delete "' . $brand->name . '"? This action cannot be undone!',
            'Delete Forever',
            'confirmPermanentDelete',
            ['id' => $id]
        );
    }

    public function confirmPermanentDelete($data)
    {
        try {
            Brand::withTrashed()->findOrFail($data['id'])->forceDelete();
            session()->flash('message', 'Brand permanently deleted.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to permanently delete brand: ' . $e->getMessage());
        }
    }
}
